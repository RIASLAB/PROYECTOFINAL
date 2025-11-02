<?php
// app/Http/Controllers/Admin/UserManagementController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->select(['id','name','email','role','status','created_at','updated_at'])
            ->orderByDesc('id')
            ->paginate(100); // lista amplia

        // veterinarios activos para el modal (asignar a clientes)
        $veterinarios = User::where('role','veterinario')
            ->where('status','activo')
            ->orderBy('name')
            ->get(['id','name']);

        // para la vista modal (crear/editar en el mismo index)
        $roles = ['veterinario','recepcionista','user','admin'];

         return view('admin.users.index', compact('users','roles'));
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'name'    => ['required','string','max:255'],
        'email'   => ['required','email','max:255','unique:users,email'],
        'password'=> ['required','string','min:8'],
        'role'    => ['required', \Illuminate\Validation\Rule::in(['admin','veterinario','recepcionista','user'])],
        'status'  => ['required', \Illuminate\Validation\Rule::in(['activo','inactivo'])],
    ]);

    \App\Models\User::create([
        'name'     => $data['name'],
        'email'    => $data['email'],
        'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
        'role'     => $data['role'],
        'status'   => $data['status'],
    ]);

    return redirect()->route('admin.users.index')->with('status','Usuario creado correctamente.');
}


    public function update(Request $request, User $user)
    {
        // Evitar que el admin se edite rol/estado de sí mismo de manera peligrosa (opcional)
        $data = $request->validate([
            'name'    => ['required','string','max:255'],
            'email'   => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'password'=> ['nullable','string','min:8'],
            'role'    => ['required', Rule::in(['veterinario','recepcionista','user','admin'])],
            'status'  => ['required', Rule::in(['activo','inactivo'])],
            
        ]);

        $user->fill([
            'name' => $data['name'],
            'email'=> $data['email'],
            'role' => $data['role'],
            'status' => $data['status'],
        ]);

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();
        return back()->with('status','Usuario actualizado.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error','No puedes eliminar tu propia cuenta.');
        }
        if ($user->role === 'admin' && User::where('role','admin')->count() <= 1) {
            return back()->with('error','Debe quedar al menos un administrador.');
        }
        $user->delete();
        return back()->with('status','Usuario eliminado.');
    }
}
