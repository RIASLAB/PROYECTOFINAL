{{-- resources/views/admin/users/index.blade.php --}}
<x-app-layout>
@php
  app()->instance('flash.inline', ['error']);
@endphp

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        .um {
            --ink:#182a2a; --ink-soft:#5a6f6e;
            --teal-900:#0a4f48; --teal-700:#0d6e63; --teal-600:#14857a; --teal-500:#1aa394; --teal-50:#e7f5f2;
            --coral:#ff7a59; --coral-dark:#ef5c39; --amber:#f4b740; --amber-bg:#fff6e2;
            --cream:#faf6ef; --white:#fff; --line:rgba(20,60,56,.12);
            --shadow-sm:0 4px 14px rgba(13,84,76,.08); --shadow-md:0 14px 34px rgba(13,84,76,.14);
            --radius:18px; --fd:"Fraunces",Georgia,serif; --fb:"Plus Jakarta Sans",system-ui,sans-serif;
            font-family:var(--fb); color:var(--ink);
        }
        body { background:var(--cream) !important; }
        .um * { box-sizing:border-box; }
        .um-wrap { max-width:1200px; margin:28px auto; padding:0 20px 48px; }

        /* Header card */
        .um-head {
            display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap;
            background:radial-gradient(600px 240px at 12% 0%, rgba(255,122,89,.28), transparent 60%),
                       linear-gradient(135deg,var(--teal-700),var(--teal-900));
            color:#fff; border-radius:var(--radius); padding:24px 28px; box-shadow:var(--shadow-sm); margin-bottom:24px;
        }
        .um-head .l { display:flex; align-items:center; gap:16px; }
        .um-head .ic { width:54px; height:54px; border-radius:16px; background:rgba(255,255,255,.16); display:grid; place-items:center; font-size:1.6rem; }
        .um-head h1 { font-family:var(--fd); font-weight:600; font-size:1.7rem; letter-spacing:-.02em; margin:0; }
        .um-head p { margin:3px 0 0; opacity:.9; font-size:.92rem; }
        .um-head .r { display:flex; gap:10px; flex-wrap:wrap; }

        .btn { appearance:none; border:none; border-radius:999px; padding:11px 18px; font-family:var(--fb); font-weight:700; font-size:.92rem; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:8px; transition:transform .2s ease, background .2s ease, box-shadow .2s ease; }
        .btn-add { background:var(--coral); color:#fff; box-shadow:0 8px 18px rgba(255,122,89,.32); }
        .btn-add:hover { background:var(--coral-dark); transform:translateY(-2px); }
        .btn-back { background:rgba(255,255,255,.16); color:#fff; border:1px solid rgba(255,255,255,.3); }
        .btn-back:hover { background:rgba(255,255,255,.28); }

        /* Stats */
        .um-stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:16px; margin-bottom:24px; }
        .um-stat { background:var(--white); border:1px solid var(--line); border-radius:16px; padding:18px 20px; box-shadow:var(--shadow-sm); }
        .um-stat h3 { font-size:.8rem; color:var(--ink-soft); margin:0 0 4px; font-weight:600; }
        .um-stat p { font-family:var(--fd); font-size:1.7rem; font-weight:600; color:var(--teal-900); margin:0; line-height:1; }

        /* Alerts */
        .alert-ok { padding:12px 16px; background:var(--teal-50); border:1px solid var(--teal-500); color:var(--teal-700); border-radius:12px; margin-bottom:14px; font-weight:500; }
        .alert-bad { padding:12px 16px; background:#fdece8; border:1px solid var(--coral); color:var(--coral-dark); border-radius:12px; margin-bottom:14px; }

        /* Toolbar */
        .um-toolbar { display:flex; gap:12px; flex-wrap:wrap; align-items:center; margin-bottom:14px; }
        .um-search { position:relative; flex:1; min-width:240px; }
        .um-search i { position:absolute; left:15px; top:50%; transform:translateY(-50%); color:var(--ink-soft); }
        .um-search input { width:100%; padding:11px 14px 11px 40px; border-radius:999px; border:1px solid var(--line); background:var(--white); font-family:var(--fb); font-size:.92rem; color:var(--ink); outline:none; transition:border-color .2s, box-shadow .2s; }
        .um-search input:focus { border-color:var(--teal-500); box-shadow:0 0 0 4px rgba(26,163,148,.16); }
        .um-filter { padding:11px 16px; border-radius:999px; border:1px solid var(--line); background:var(--white); font-family:var(--fb); font-weight:600; font-size:.9rem; color:var(--ink); cursor:pointer; outline:none; }

        /* Table */
        .um-tablecard { background:var(--white); border:1px solid var(--line); border-radius:var(--radius); box-shadow:var(--shadow-sm); overflow:hidden; }
        .um-scroll { overflow-x:auto; }
        table.um-table { width:100%; border-collapse:collapse; font-size:.9rem; min-width:760px; }
        .um-table thead th { background:var(--teal-50); color:var(--teal-700); font-weight:700; font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; text-align:left; padding:14px 14px; }
        .um-table tbody td { padding:13px 14px; border-bottom:1px solid var(--line); color:var(--ink); vertical-align:middle; }
        .um-table tbody tr { transition:background .15s; }
        .um-table tbody tr:hover { background:#f6fbf9; }
        .um-user { display:flex; align-items:center; gap:11px; }
        .um-ava { width:38px; height:38px; border-radius:50%; display:grid; place-items:center; color:#fff; font-weight:700; font-size:.85rem; background:linear-gradient(135deg,var(--teal-500),var(--teal-900)); flex-shrink:0; }
        .um-name { font-weight:700; }
        .um-id { font-size:.74rem; color:var(--ink-soft); }

        .badge { padding:5px 11px; border-radius:999px; font-size:.74rem; font-weight:700; display:inline-flex; align-items:center; gap:5px; }
        .role-admin { background:#ffe9e1; color:var(--coral-dark); }
        .role-vet   { background:var(--teal-50); color:var(--teal-700); }
        .role-recep { background:var(--amber-bg); color:#8a5a05; }
        .role-other { background:#eef1f0; color:var(--ink-soft); }
        .st-ok  { background:var(--teal-50); color:var(--teal-700); }
        .st-ok::before  { content:""; width:7px; height:7px; border-radius:50%; background:var(--teal-500); }
        .st-off { background:#eef1f0; color:var(--ink-soft); }
        .st-off::before { content:""; width:7px; height:7px; border-radius:50%; background:#aab6b4; }

        .ico-btn { width:34px; height:34px; border-radius:10px; border:1px solid var(--line); background:var(--white); cursor:pointer; display:inline-grid; place-items:center; transition:transform .15s, background .15s, color .15s; }
        .ico-btn.edit { color:var(--teal-700); } .ico-btn.edit:hover { background:var(--teal-600); color:#fff; transform:translateY(-2px); }
        .ico-btn.del  { color:var(--coral-dark); } .ico-btn.del:hover { background:var(--coral); color:#fff; transform:translateY(-2px); }
        .ico-btn.lock { color:#aab6b4; cursor:not-allowed; }
        .um-actions { display:flex; gap:8px; }
        .um-empty { text-align:center; color:var(--ink-soft); padding:30px; }

        /* Modal */
        .modal { display:none; position:fixed; inset:0; background:rgba(8,40,37,.5); align-items:center; justify-content:center; z-index:9999; padding:18px; }
        .modal__card { background:#fff; padding:26px 28px; border-radius:20px; min-width:340px; max-width:460px; width:100%; position:relative; box-shadow:var(--shadow-md); animation:pop .25s ease; }
        @keyframes pop { from { transform:translateY(14px) scale(.98); opacity:0; } to { transform:none; opacity:1; } }
        .modal__title { font-family:var(--fd); font-weight:600; font-size:1.4rem; color:var(--teal-900); margin-bottom:18px; }
        .modal__close { position:absolute; top:16px; right:20px; font-size:22px; cursor:pointer; color:var(--ink-soft); line-height:1; }
        .modal__close:hover { color:var(--coral); }
        .fg { margin-bottom:14px; }
        .fg label { display:block; margin-bottom:6px; font-weight:600; font-size:.88rem; }
        .fg input, .fg select { width:100%; padding:11px 14px; border:1.5px solid var(--line); border-radius:12px; outline:none; font-family:var(--fb); font-size:.95rem; transition:border-color .2s, box-shadow .2s; }
        .fg input:focus, .fg select:focus { border-color:var(--teal-500); box-shadow:0 0 0 4px rgba(26,163,148,.16); }
        .modal .actions { display:flex; justify-content:flex-end; gap:10px; margin-top:18px; }
        .btn-save { background:var(--teal-600); color:#fff; } .btn-save:hover { background:var(--teal-700); transform:translateY(-2px); }
        .btn-cancel { background:#eef1f0; color:var(--ink); } .btn-cancel:hover { background:#e2e7e5; }

        @media (max-width:600px) { .um-head { padding:20px; } .um-head h1 { font-size:1.4rem; } }
    </style>

    <div class="um">
        <div class="um-wrap">
            {{-- Header --}}
            <div class="um-head">
                <div class="l">
                    <div class="ic"><i class="fas fa-users"></i></div>
                    <div>
                        <h1>Gestión de Usuarios</h1>
                        <p>Crea, edita y administra las cuentas del sistema.</p>
                    </div>
                </div>
                <div class="r">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-back"><i class="fas fa-arrow-left"></i> Volver al Panel</a>
                    <button class="btn btn-add" onclick="openModal('agregar')"><i class="fas fa-user-plus"></i> Agregar Usuario</button>
                </div>
            </div>

            {{-- Estadísticas --}}
            <div class="um-stats">
                <div class="um-stat"><h3>Total Usuarios</h3><p data-count="{{ (int) $users->count() }}">0</p></div>
                <div class="um-stat"><h3>Activos</h3><p data-count="{{ (int) $users->where('status','activo')->count() }}">0</p></div>
                <div class="um-stat"><h3>Inactivos</h3><p data-count="{{ (int) $users->where('status','inactivo')->count() }}">0</p></div>
                @foreach($roles as $role)
                    <div class="um-stat"><h3>{{ ucfirst($role) }}</h3><p data-count="{{ (int) $users->where('role',$role)->count() }}">0</p></div>
                @endforeach
            </div>

            {{-- Alertas --}}
            @if ($errors->any())
                <div class="alert-bad">
                    <strong>Corrige los siguientes errores:</strong>
                    <ul style="margin-top:6px;">
                        @foreach ($errors->all() as $e) <li>• {{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif
            @if(session('status')) <div class="alert-ok">{{ session('status') }}</div> @endif
            @if(session('error')) <div class="alert-bad">{{ session('error') }}</div> @endif

            {{-- Toolbar --}}
            <div class="um-toolbar">
                <div class="um-search">
                    <i class="fas fa-search"></i>
                    <input type="text" id="umSearch" placeholder="Buscar por nombre o email...">
                </div>
                <select class="um-filter" id="umFilter">
                    <option value="">Todos los roles</option>
                    @foreach($roles as $role) <option value="{{ strtolower($role) }}">{{ ucfirst($role) }}</option> @endforeach
                </select>
            </div>

            {{-- Tabla --}}
            <div class="um-tablecard">
                <div class="um-scroll">
                    <table class="um-table">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Creado</th>
                                <th style="text-align:right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="umBody">
                            @forelse($users as $u)
                                @php
                                    $r = strtolower($u->role);
                                    $roleClass = $r === 'admin' ? 'role-admin' : (in_array($r,['veterinario','vet']) ? 'role-vet' : (in_array($r,['recepcionista','recepcion']) ? 'role-recep' : 'role-other'));
                                @endphp
                                <tr data-search="{{ strtolower($u->name.' '.$u->email) }}" data-role="{{ $r }}">
                                    <td>
                                        <div class="um-user">
                                            <div class="um-ava">{{ strtoupper(mb_substr($u->name,0,1)) }}</div>
                                            <div>
                                                <div class="um-name">{{ $u->name }}</div>
                                                <div class="um-id">ID: {{ $u->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $u->email }}</td>
                                    <td><span class="badge {{ $roleClass }}">{{ ucfirst($u->role) }}</span></td>
                                    <td><span class="badge {{ $u->status === 'activo' ? 'st-ok' : 'st-off' }}">{{ ucfirst($u->status) }}</span></td>
                                    <td>{{ $u->created_at?->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="um-actions" style="justify-content:flex-end">
                                        @if($u->id === auth()->id() || ($u->role==='admin' && \App\Models\User::where('role','admin')->count()<=1))
                                            <button class="ico-btn lock" disabled title="Bloqueado"><i class="fas fa-lock"></i></button>
                                        @else
                                            <button class="ico-btn edit" title="Editar" onclick="openModal('editar', @js($u->only('id','name','email','role','status','assigned_vet_id')))"><i class="fas fa-pen"></i></button>
                                            <form action="{{ route('admin.users.destroy',$u) }}" method="POST" onsubmit="return confirm('¿Eliminar este usuario?');" style="display:inline">
                                                @csrf @method('DELETE')
                                                <button class="ico-btn del" type="submit" title="Eliminar"><i class="fas fa-trash"></i></button>
                                            </form>
                                        @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="um-empty">No hay usuarios.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="um-empty" id="umNoResults" style="display:none">No se encontraron usuarios con esos criterios.</div>
            </div>
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

                <div class="fg"><label>Nombre</label><input type="text" name="name" id="f_name" required></div>
                <div class="fg"><label>Email</label><input type="email" name="email" id="f_email" required></div>
                <div class="fg" id="fg_password"><label>Contraseña</label><input type="password" name="password" id="f_password"></div>
                <div class="fg">
                    <label>Rol</label>
                    <select name="role" id="f_role" required>
                        @foreach($roles as $r) <option value="{{ $r }}">{{ ucfirst($r) }}</option> @endforeach
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
                    <button class="btn btn-cancel" type="button" onclick="closeModal()">Cancelar</button>
                    <button class="btn btn-save" type="submit"><i class="fas fa-floppy-disk"></i> Guardar</button>
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
            document.getElementById('modalTitle').textContent = mode === 'agregar' ? 'Agregar Usuario' : 'Editar Usuario';

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

        // Conteo animado de estadísticas
        document.querySelectorAll('.um-stat p[data-count]').forEach(el => {
            const target = parseInt(el.dataset.count,10) || 0; const dur=900; const t0=performance.now();
            (function step(now){ const p=Math.min((now-t0)/dur,1); el.textContent=Math.round(target*(1-Math.pow(1-p,3))); if(p<1) requestAnimationFrame(step); })(performance.now());
        });

        // Búsqueda + filtro en vivo
        const search = document.getElementById('umSearch');
        const filter = document.getElementById('umFilter');
        const rows = Array.from(document.querySelectorAll('#umBody tr[data-search]'));
        const noRes = document.getElementById('umNoResults');
        function applyFilter() {
            const q = search.value.trim().toLowerCase();
            const role = filter.value;
            let visible = 0;
            rows.forEach(tr => {
                const okText = tr.dataset.search.includes(q);
                const okRole = !role || tr.dataset.role === role;
                const show = okText && okRole;
                tr.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            if (noRes) noRes.style.display = (rows.length && !visible) ? 'block' : 'none';
        }
        if (search) search.addEventListener('input', applyFilter);
        if (filter) filter.addEventListener('change', applyFilter);

        @if ($errors->any() && old('_form_mode') === 'create')
            openModal('agregar');
        @endif
    </script>
</x-app-layout>