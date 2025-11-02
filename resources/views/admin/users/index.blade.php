{{-- resources/views/admin/users/index.blade.php --}}
<x-app-layout>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Playfair+Display:wght@700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/admin-users.css') }}">

    <div class="max-w-7xl mx-auto px-6 py-6">
        <div class="page-wrap">
            <div class="h1">Gestionar Usuarios</div>

            @if ($errors->any())
  <div class="alert-bad">
    <strong>Corrige los siguientes errores:</strong>
    <ul style="margin-top:6px;">
      @foreach ($errors->all() as $e)
        <li>• {{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif


            @if(session('status'))
                <div class="alert-ok">{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="alert-bad">{{ session('error') }}</div>
            @endif

            <button class="btn btn-add" onclick="openModal('agregar')">Agregar Usuario</button>

            <div class="table-card">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Creado</th>
                            <th>Actualizado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td>{{ $u->id }}</td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ ucfirst($u->role) }}</td>
                            <td>
                                @if($u->status === 'activo')
                                   <span class="badge ok">Activo</span>
                                @else
                                   <span class="badge off">Inactivo</span>
                                @endif
                            </td>
                            <td>{{ $u->created_at?->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $u->updated_at?->format('Y-m-d H:i:s') }}</td>
                            <td class="actions">
                                @if($u->id === auth()->id() || ($u->role==='admin' && \App\Models\User::where('role','admin')->count()<=1))
                                    <button class="btn lock" disabled title="Bloqueado">🔒</button>
                                @else
                                    <button class="btn btn-edit" 
                                        onclick="openModal('editar', @js($u->only('id','name','email','role','status','assigned_vet_id')))">
                                        Editar
                                    </button>
                                    <form action="{{ route('admin.users.destroy',$u) }}" method="POST" onsubmit="return confirm('¿Eliminar este usuario?');" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-del" type="submit">Eliminar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9">Sin usuarios.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- paginación opcional --}}
            {{-- <div class="mt-3">{{ $users->links() }}</div> --}}
        </div>
    </div>

    {{-- Modal (crear/editar) --}}
    <div class="modal" id="modal">
        <div class="modal__card">
            <div class="modal__title" id="modalTitle">Agregar Usuario</div>
            <span class="modal__close" onclick="closeModal()">×</span>

            <form id="modalForm" method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <input type="hidden" name="_method" value="POST" id="methodField">
                <input type="hidden" name="_form_mode" id="f_mode" value="create">
                <input type="hidden" name="id" id="userId">

                <div class="fg">
                    <label>Nombre</label>
                    <input type="text" name="name" id="f_name" required>
                </div>
                <div class="fg">
                    <label>Email</label>
                    <input type="email" name="email" id="f_email" required>
                </div>
                <div class="fg" id="fg_password">
                    <label>Contraseña</label>
                    <input type="password" name="password" id="f_password">
                </div>
                <div class="fg">
                    <label>Rol</label>
                    <select name="role" id="f_role" required>
                        @foreach($roles as $r)
                            <option value="{{ $r }}">{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fg">
                    <label>Estado</label>
                    <select name="status" id="f_status" required>
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>

                <div class="actions">
                    <button class="btn btn-add" type="submit">Guardar</button>
                    <button class="btn" type="button" style="background:#999" onclick="closeModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
const modal = document.getElementById('modal');
const methodField = document.getElementById('methodField');
const form = document.getElementById('modalForm');

function openModal(mode, data = null) {
  modal.style.display = 'flex';
  document.getElementById('f_mode').value = mode === 'agregar' ? 'create' : 'edit';

  if (mode === 'agregar') {
    form.action = "{{ route('admin.users.store') }}";
    methodField.value = 'POST';
    form.reset();
    document.getElementById('f_password').required = true;
  } else {
    const url = "{{ route('admin.users.update', ':id') }}".replace(':id', data.id);
    form.action = url; methodField.value = 'PUT';
    document.getElementById('userId').value = data.id;
    document.getElementById('f_name').value = data.name ?? '';
    document.getElementById('f_email').value = data.email ?? '';
    document.getElementById('f_role').value = data.role ?? 'user';
    document.getElementById('f_status').value = data.status ?? 'activo';
    document.getElementById('f_password').value = '';
    document.getElementById('f_password').required = false;
  }
}
function closeModal(){ modal.style.display='none'; }
window.addEventListener('click', (e)=>{ if(e.target===modal) closeModal(); });

// Reabrir modal automáticamente si hubo errores en "crear"
@if ($errors->any() && old('_form_mode') === 'create')
  openModal('agregar');
@endif
</script>

</x-app-layout>
