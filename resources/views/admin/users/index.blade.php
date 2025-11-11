{{-- resources/views/admin/users/index.blade.php --}}
<x-app-layout>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Playfair+Display:wght@700&display=swap">

    <style>
        :root{
            --primary:#0ea5e9; --primary-dark:#0369a1;
            --success:#10b981; --danger:#ef4444; --warning:#facc15;
            --bg-card:#fff; --bg-body:#f6f9fc; --bg-header:#e0f2fe;
            --text:#334155; --text-muted:#64748b; --border:#e5e7eb;
            --radius:12px; --shadow:0 8px 28px rgba(15,23,42,.08);
        }
        body{background:var(--bg-body); font-family:'Montserrat', sans-serif; color:var(--text);}

        .page-wrap{max-width:1200px;margin:30px auto;padding:0 20px;}
        .h1{font:700 22px/1.2 'Playfair Display', serif;margin-bottom:20px;color:var(--text);}

        /* Botones */
        .btn{padding:8px 16px;border-radius:999px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:6px;transition:0.2s;text-decoration:none;}
        .btn-add{background:var(--primary);color:#fff;border:1px solid var(--primary);}
        .btn-add:hover{background:var(--primary-dark);border-color:var(--primary-dark);}
        .btn-back{background:#64748b;color:#fff;border:1px solid #64748b;margin-right:10px;}
        .btn-back:hover{background:#475569;border-color:#475569;}
        .btn-edit{background:#facc15;color:#1e293b;border:1px solid #facc15;}
        .btn-edit:hover{background:#f59e0b;border-color:#f59e0b;}
        .btn-del{background:#ef4444;color:#fff;border:1px solid #ef4444;}
        .btn-del:hover{background:#b91c1c;border-color:#b91c1c;}

        /* Badges */
        .badge{padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;}
        .ok{background:var(--success);color:#fff;}
        .off{background:#f3f4f6;color:#334155;}
        .warn{background:var(--warning);color:#1e293b;}

        /* Alerts */
        .alert-ok{padding:10px 14px;background:#d1fae5;border:1px solid #10b981;color:#065f46;border-radius:8px;margin-bottom:12px;}
        .alert-bad{padding:10px 14px;background:#fee2e2;border:1px solid #ef4444;color:#991b1b;border-radius:8px;margin-bottom:12px;}

        /* Estadísticas */
        .stats{display:flex;gap:15px;margin-bottom:20px;flex-wrap:wrap;}
        .card{flex:1 1 180px;background:var(--bg-card);padding:20px;border-radius:var(--radius);box-shadow:var(--shadow);}
        .card h3{font-size:14px;color:var(--text-muted);margin-bottom:6px;}
        .card p{font-size:20px;font-weight:700;color:var(--text);}

        /* Tabla */
        .table-card{background:var(--bg-card);padding:15px;border-radius:var(--radius);box-shadow:var(--shadow);}
        table{width:100%;border-collapse:collapse;font-size:14px;margin-top:15px;}
        th,td{padding:12px 10px;text-align:left;border-bottom:1px solid var(--border);}
        th{font-weight:700;text-transform:uppercase;color:#9ca3af;font-size:12px;}
        tr:hover{background:#f8fafc;}
        .actions button{margin-right:6px;}

        /* Modal */
        .modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;z-index:9999;}
        .modal__card{background:#fff;padding:20px 25px;border-radius:var(--radius);min-width:350px;max-width:500px;position:relative;}
        .modal__title{font:700 18px/1.2 'Playfair Display', serif;margin-bottom:14px;color:var(--text);}
        .modal__close{position:absolute;top:14px;right:18px;font-size:20px;cursor:pointer;}
        .fg{margin-bottom:12px;}
        .fg label{display:block;margin-bottom:4px;font-weight:500;}
        .fg input, .fg select{width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:8px;outline:none;}
        .fg input:focus, .fg select:focus{border-color:var(--primary);}
        .modal .actions{display:flex;justify-content:flex-end;gap:10px;margin-top:14px;}
    </style>

    <div class="page-wrap">
        <div class="h1">Panel de Usuarios</div>

        {{-- Botones --}}
        <div style="margin-bottom:15px;">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-back">← Volver al Panel</a>
            <button class="btn btn-add" onclick="openModal('agregar')">+ Agregar Usuario</button>
        </div>

        {{-- Estadísticas de Usuarios --}}
        <div class="stats">
            <div class="card">
                <h3>Total Usuarios</h3>
                <p>{{ $users->count() }}</p>
            </div>
            <div class="card">
                <h3>Activos</h3>
                <p>{{ $users->where('status','activo')->count() }}</p>
            </div>
            <div class="card">
                <h3>Inactivos</h3>
                <p>{{ $users->where('status','inactivo')->count() }}</p>
            </div>
            @foreach($roles as $role)
                <div class="card">
                    <h3>{{ ucfirst($role) }}</h3>
                    <p>{{ $users->where('role',$role)->count() }}</p>
                </div>
            @endforeach
        </div>

        {{-- Mensajes de sesión y errores --}}
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

        {{-- Tabla de Usuarios --}}
        <div class="table-card">
            <table>
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
                                <span class="badge {{ $u->status === 'activo' ? 'ok' : 'off' }}">
                                    {{ ucfirst($u->status) }}
                                </span>
                            </td>
                            <td>{{ $u->created_at?->format('Y-m-d H:i') }}</td>
                            <td>{{ $u->updated_at?->format('Y-m-d H:i') }}</td>
                            <td class="actions">
                                @if($u->id === auth()->id() || ($u->role==='admin' && \App\Models\User::where('role','admin')->count()<=1))
                                    <button class="btn" disabled title="Bloqueado">🔒</button>
                                @else
                                    <button class="btn btn-edit" onclick="openModal('editar', @js($u->only('id','name','email','role','status','assigned_vet_id')))">Editar</button>
                                    <form action="{{ route('admin.users.destroy',$u) }}" method="POST" onsubmit="return confirm('¿Eliminar este usuario?');" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-del" type="submit">Eliminar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" style="text-align:center;color:#64748b;">No hay usuarios.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Crear/Editar --}}
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
                    <button class="btn" type="button" style="background:#999;color:#fff;" onclick="closeModal()">Cancelar</button>
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

        @if ($errors->any() && old('_form_mode') === 'create')
            openModal('agregar');
        @endif
    </script>
</x-app-layout>
