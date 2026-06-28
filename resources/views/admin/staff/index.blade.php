<x-layouts.admin>
    <x-slot name="title">Staff & Usuarios - CapacitaPerú</x-slot>
    <x-slot name="header">Usuarios del Sistema</x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if($errors->any())
        <div class="mb-8 p-6 rounded-[1.5rem] bg-red-50 border border-red-100 text-red-700 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <i class="ph-bold ph-warning-circle text-xl"></i>
                <span class="font-black uppercase tracking-widest text-[10px]">Se encontraron errores</span>
            </div>
            <ul class="list-disc list-inside text-xs font-medium space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- BARRA DE HERRAMIENTAS -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-10 fade-in-up">
        <div class="relative w-full md:w-96 group">
            <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00] transition-colors"></i>
            <input type="text" placeholder="Buscar usuario por nombre, email o rol..." 
                   class="w-full pl-12 pr-4 py-4 rounded-[1.2rem] border border-gray-100 bg-white shadow-sm focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none text-sm transition-all font-medium">
        </div>

        @if(Auth::user()->hasPermission('users.create'))
        <button type="button" onclick="openCreateModal()" class="w-full md:w-auto flex items-center justify-center gap-3 px-8 py-4 bg-[#001144] text-white rounded-[1.2rem] hover:bg-[#001144]/90 transition-all shadow-xl shadow-blue-900/20 text-sm font-black uppercase tracking-widest group">
            <i class="ph-bold ph-user-plus text-lg group-hover:rotate-12 transition-transform"></i>
            Nuevo Usuario
        </button>
        @endif
    </div>

    <!-- TABLA DE USUARIOS -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden fade-in-up" style="animation-delay: 0.2s">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Usuario</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Rol Base</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Acceso</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Estado</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-[1rem] bg-gray-100 flex items-center justify-center overflow-hidden border-2 border-white group-hover:border-[#FF7A00] shadow-sm transition-all rotate-2 group-hover:rotate-0">
                                    @if($user->avatar_url && Str::startsWith($user->avatar_url, 'http'))
                                        <img src="{{ $user->avatar_url }}" class="w-full h-full object-cover">
                                    @elseif($user->avatar_url)
                                        <img src="{{ asset($user->avatar_url) }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=001144&color=fff&size=128" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div>
                                    <p class="font-black text-[#001144] tracking-tight text-base leading-none mb-1">{{ $user->name }}</p>
                                    <p class="text-[11px] text-gray-400 font-medium">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @php
                                $badgeStyles = match($user->role) {
                                    'superadmin' => 'bg-[#001144] text-white border-[#001144]',
                                    'admin' => 'bg-orange-50 text-[#FF7A00] border-orange-100',
                                    'moderator' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    default => 'bg-gray-50 text-gray-500 border-gray-100'
                                };
                            @endphp
                            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $badgeStyles }}">
                                <i class="ph-bold {{ $user->role === 'superadmin' ? 'ph-crown' : 'ph-shield-check' }}"></i>
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            @if(Auth::user()->hasPermission('users.permissions') && ($user->role !== 'superadmin' || Auth::user()->role === 'superadmin'))
                            <button onclick='openPermissionsModal({{ $user->id }}, @json($user->name))' class="px-4 py-2 bg-gray-50 hover:bg-[#001144] text-[#001144] hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 border border-gray-100">
                                <i class="ph-bold ph-key"></i> Permisos
                            </button>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $user->status === 'active' ? 'bg-green-500' : 'bg-red-500' }} {{ $user->status === 'active' ? 'animate-pulse' : '' }}"></span>
                                <span class="text-[10px] font-black uppercase tracking-widest {{ $user->status === 'active' ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $user->status === 'active' ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($user->role !== 'superadmin' || Auth::user()->role === 'superadmin')
                                    @if(Auth::user()->hasPermission('users.edit'))
                                    <button type="button" onclick='openEditModal(@json($user))' class="w-10 h-10 rounded-xl flex items-center justify-center text-[#001144] hover:bg-[#001144] hover:text-white border border-gray-100 transition-all shadow-sm" title="Editar">
                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                    </button>
                                    
                                    <form id="form-reset-pass-{{ $user->id }}" action="{{ route('admin.staff.reset_password', $user->id) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <button type="button" onclick="confirmAction('form-reset-pass-{{ $user->id }}', '¿Restablecer Contraseña?', 'Se generará una nueva contraseña y podrás copiarla.', 'warning')" class="w-10 h-10 rounded-xl flex items-center justify-center text-[#FF7A00] hover:bg-[#FF7A00] hover:text-white border border-gray-100 transition-all shadow-sm" title="Restablecer Contraseña">
                                            <i class="ph-bold ph-password text-lg"></i>
                                        </button>
                                    </form>
 
                                    <form id="form-reset-pin-{{ $user->id }}" action="{{ route('admin.staff.reset_pin', $user->id) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <button type="button" onclick="confirmAction('form-reset-pin-{{ $user->id }}', '¿Eliminar PIN?', 'El usuario deberá configurar uno nuevo al ingresar.', 'warning')" class="w-10 h-10 rounded-xl flex items-center justify-center text-purple-600 hover:bg-purple-600 hover:text-white border border-gray-100 transition-all shadow-sm" title="Eliminar PIN de Seguridad">
                                            <i class="ph-bold ph-fingerprint text-lg"></i>
                                        </button>
                                    </form>
                                    @endif
                                    
                                    @if(Auth::user()->hasPermission('users.delete'))
                                    <form id="form-delete-{{ $user->id }}" action="{{ route('admin.staff.destroy', $user->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmAction('form-delete-{{ $user->id }}', '¿Eliminar Usuario?', 'Esta acción revoca todo acceso al sistema de forma inmediata.', 'error')" class="w-10 h-10 rounded-xl flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white border border-gray-100 transition-all shadow-sm" title="Eliminar">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </form>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gray-100/50 text-[10px] font-black text-gray-400 uppercase tracking-widest border border-gray-200/40 shadow-sm">
                                        <i class="ph-bold ph-lock-key text-xs text-gray-400"></i> Protegido
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-24 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6">
                                <i class="ph-duotone ph-users-three text-5xl text-gray-200"></i>
                            </div>
                            <h3 class="text-xl font-black text-[#001144] mb-2 tracking-tight">Sin usuarios registrados</h3>
                            <p class="text-gray-400 font-medium max-w-xs mx-auto text-sm">No hay otros miembros del equipo registrados en el sistema actualmente.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <div class="mt-10">
        {{ $users->links() }}
    </div>

    <!-- MODAL: GESTIÓN DE PERMISOS -->
    <div id="modal-permissions" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-[#001144]/40 backdrop-blur-md transition-opacity" onclick="closePermissionsModal()"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                
                <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col h-[80vh] border border-white/20">
                    
                    <div class="px-10 py-8 border-b border-gray-50 flex justify-between items-center bg-white">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 rounded-2xl bg-orange-50 flex items-center justify-center text-[#FF7A00]">
                                <i class="ph-fill ph-shield-check text-3xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-[#001144] tracking-tight">Matriz de Permisos</h2>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Configurando para: <span class="text-[#FF7A00]" id="perm-username">Usuario</span></p>
                            </div>
                        </div>
                        <button onclick="closePermissionsModal()" class="w-12 h-12 rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
                            <i class="ph-bold ph-x text-xl"></i>
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto p-10 relative bg-white custom-scrollbar">
                        <div id="permissions-loading" class="absolute inset-0 flex flex-col items-center justify-center bg-white/90 backdrop-blur-sm z-10">
                            <div class="w-12 h-12 border-4 border-orange-100 border-t-[#FF7A00] rounded-full animate-spin mb-4"></div>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Sincronizando permisos...</p>
                        </div>

                        <form id="permissions-form">
                            @csrf
                            <input type="hidden" id="current-perm-user-id">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="permissions-grid">
                                <!-- Contenido AJAX -->
                            </div>
                        </form>
                    </div>

                    <div class="p-8 border-t border-gray-50 flex justify-end gap-4 bg-gray-50/30">
                        <button type="button" onclick="closePermissionsModal()" class="px-8 py-4 bg-white border border-gray-100 text-[#001144] rounded-[1.2rem] hover:bg-gray-50 text-[10px] font-black uppercase tracking-widest transition-all">Cancelar</button>
                        <button type="button" onclick="savePermissions()" class="px-10 py-4 bg-[#001144] text-white rounded-[1.2rem] hover:bg-[#FF7A00] text-[10px] font-black uppercase tracking-widest shadow-xl shadow-blue-900/10 transition-all transform hover:-translate-y-1 flex items-center gap-3">
                            <i class="ph-bold ph-floppy-disk text-lg"></i> Actualizar Matriz
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: USUARIO (CREAR / EDITAR) -->
    <div id="modal-user" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-[#001144]/40 backdrop-blur-md transition-opacity" onclick="closeModal()"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                
                <form id="userForm" action="{{ route('admin.staff.store') }}" method="POST" class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all w-full max-w-4xl border border-white/20">
                    @csrf
                    <input type="hidden" name="_method" id="methodField" value="POST">
                    
                    <div class="bg-white px-10 py-8 border-b border-gray-50 flex justify-between items-center">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 rounded-2xl bg-orange-50 flex items-center justify-center text-[#FF7A00]">
                                <i class="ph-fill ph-user-circle-plus text-3xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-[#001144] tracking-tight" id="modalTitle">Nuevo Staff</h3>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Credenciales y Perfil del Sistema</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeModal()" class="w-12 h-12 rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
                            <i class="ph-bold ph-x text-xl"></i>
                        </button>
                    </div>

                    <div class="flex flex-col lg:flex-row">
                        <!-- SELECCIÓN DE AVATAR -->
                        <div class="lg:w-1/3 bg-gray-50/30 p-10 flex flex-col items-center border-r border-gray-50">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-8">Icono de Usuario</p>
                            <div class="relative group mb-10">
                                <div class="w-44 h-44 rounded-[2.5rem] p-1 bg-gradient-to-tr from-[#001144] to-[#FF7A00] rotate-3 group-hover:rotate-0 transition-transform">
                                    <div class="w-full h-full rounded-[2.2rem] overflow-hidden border-4 border-white bg-white">
                                        <img id="avatar-preview-large" src="https://ui-avatars.com/api/?name=User&background=001144&color=fff&size=256" class="w-full h-full object-cover">
                                    </div>
                                </div>
                                <div class="absolute -bottom-2 -right-2 bg-[#FF7A00] text-white w-12 h-12 rounded-2xl flex items-center justify-center shadow-xl shadow-orange-500/20 border-4 border-white transform group-hover:scale-110 transition-transform">
                                    <i class="ph-bold ph-camera text-xl"></i>
                                </div>
                            </div>
                            
                            <input type="hidden" name="avatar_url" id="selected_avatar" required>
                            <div class="grid grid-cols-4 gap-4 max-h-64 overflow-y-auto p-2 w-full custom-scrollbar">
                                @foreach($avatars as $avatar)
                                    <div onclick="selectAvatar('{{ Str::startsWith($avatar, 'http') ? $avatar : asset($avatar) }}', this)" 
                                         class="avatar-option cursor-pointer rounded-2xl border-2 border-transparent hover:border-[#FF7A00] hover:scale-105 transition-all p-1 relative group/avatar">
                                        <img src="{{ Str::startsWith($avatar, 'http') ? $avatar : asset($avatar) }}" class="w-full aspect-square rounded-xl object-cover shadow-sm bg-white">
                                        <div class="check-icon absolute -top-1 -right-1 bg-[#FF7A00] text-white rounded-lg p-1 shadow-md transform scale-0 transition-transform z-10">
                                            <i class="ph-bold ph-check text-[8px]"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <p id="avatar-helper" class="text-[9px] text-red-500 font-black uppercase tracking-widest mt-6 hidden animate-bounce">Selecciona un icono de perfil</p>
                        </div>

                        <!-- DATOS DEL USUARIO -->
                        <div class="lg:w-2/3 p-10 bg-white">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nombre</label>
                                    <input type="text" id="name" name="name" required 
                                           class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none text-sm font-bold text-[#001144] transition-all" 
                                           placeholder="Ej: Juan">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Apellido</label>
                                    <input type="text" id="lastname" name="lastname" 
                                           class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none text-sm font-bold text-[#001144] transition-all" 
                                           placeholder="Ej: Pérez">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Correo Corporativo</label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-envelope absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00] transition-colors"></i>
                                        <input type="email" id="email" name="email" required 
                                               class="w-full pl-14 pr-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] transition-all" 
                                               placeholder="usuario@capacitaperu.edu.pe">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nombre de Usuario</label>
                                    <div class="relative group">
                                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 font-black group-focus-within:text-[#FF7A00]">@</span>
                                        <input type="text" id="username" name="username" required 
                                               class="w-full pl-10 pr-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] transition-all" 
                                               placeholder="username">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Rol Jerárquico</label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-shield-check absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00] transition-colors"></i>
                                        <select id="role" name="role" class="w-full pl-14 pr-10 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] appearance-none cursor-pointer transition-all">
                                            <option value="moderator">Moderador</option>
                                            <option value="admin">Administrador</option>
                                            @if(Auth::user()->role === 'superadmin')
                                                <option value="superadmin">Super Admin</option>
                                            @endif
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none group-focus-within:rotate-180 transition-transform"></i>
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Contraseña de Acceso</label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-lock-key absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00] transition-colors"></i>
                                        <input type="text" id="password" name="password" required 
                                               class="w-full pl-14 pr-14 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] transition-all" 
                                               placeholder="••••••••">
                                        <button type="button" onclick="generatePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-white border border-gray-100 text-[#FF7A00] hover:bg-[#FF7A00] hover:text-white transition-all flex items-center justify-center shadow-sm" title="Autogenerar">
                                            <i class="ph-bold ph-arrows-clockwise"></i>
                                        </button>
                                    </div>
                                    <p class="text-[9px] text-gray-400 mt-2 font-black uppercase hidden tracking-widest" id="pass-hint">Omitir para mantener actual</p>
                                </div>
                            </div>

                            <div class="mt-12 flex gap-4 pt-10 border-t border-gray-50">
                                <button type="button" onclick="closeModal()" class="flex-1 px-8 py-4 bg-white text-[#001144] font-black uppercase tracking-widest rounded-[1.2rem] border border-gray-100 hover:bg-gray-50 transition-all text-[10px]">Cancelar</button>
                                <button type="button" onclick="handleSave()" class="flex-[2] px-8 py-4 bg-[#001144] text-white font-black uppercase tracking-widest rounded-[1.2rem] hover:bg-[#FF7A00] transition-all shadow-xl shadow-blue-900/10 text-[10px] flex items-center justify-center gap-3 transform hover:-translate-y-1">
                                    <i class="ph-bold ph-check text-lg"></i> Guardar Usuario
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: CREDENCIALES (PARA COPIAR) -->
    <div id="modal-credentials" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-[#001144]/60 backdrop-blur-xl transition-opacity"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="bg-white rounded-[3rem] shadow-2xl p-10 max-w-md w-full text-center relative border border-white/20">
                    <div class="w-20 h-20 bg-green-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-green-500 shadow-inner">
                        <i class="ph-fill ph-check-circle text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-[#001144] mb-2 tracking-tight" id="cred-title">¡Cuenta Lista!</h3>
                    <p class="text-gray-400 mb-8 text-[11px] font-medium uppercase tracking-widest" id="cred-subtitle">Copia estos datos de acceso seguro</p>
                    
                    <div class="bg-gray-50 rounded-[2rem] p-6 text-left border border-gray-100 mb-8 space-y-4 font-mono text-xs shadow-inner">
                        <div class="flex flex-col gap-1 border-b border-gray-100 pb-3">
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-widest">Correo Electrónico</span>
                            <span class="text-[#001144] font-black break-all" id="cred-email">...</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-widest">Contraseña Temporal</span>
                            <span class="text-[#FF7A00] font-black tracking-widest" id="cred-pass">...</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4">
                        <button onclick="copyToClipboard()" class="w-full py-5 bg-[#25D366] hover:bg-[#1DA851] text-white font-black uppercase tracking-[0.2em] rounded-[1.5rem] transition-all flex items-center justify-center gap-3 shadow-xl shadow-green-500/20 text-[10px]">
                            <i class="ph-fill ph-whatsapp-logo text-xl"></i> Copiar y Enviar
                        </button>
                        <button onclick="submitRealForm()" class="w-full py-4 bg-gray-50 hover:bg-gray-100 text-gray-400 font-black uppercase tracking-widest rounded-[1.5rem] transition-all text-[10px]">Cerrar y Finalizar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            },
            customClass: {
                popup: 'rounded-2xl border border-gray-50 shadow-xl'
            }
        });

        @if(session('success'))
            Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
        @endif

        @if(session('credentials_modal'))
            document.addEventListener('DOMContentLoaded', function() {
                const data = @json(session('credentials_modal'));
                document.getElementById('cred-email').innerText = data.email;
                document.getElementById('cred-pass').innerText = data.password;
                window.tempCredentialName = data.name;
                if (data.is_new) {
                    document.getElementById('cred-title').innerText = "¡Cuenta Lista!";
                    document.getElementById('cred-subtitle').innerText = "Copia estos datos de acceso seguro";
                } else {
                    document.getElementById('cred-title').innerText = "Acceso Actualizado";
                    document.getElementById('cred-subtitle').innerText = "Nuevas credenciales generadas";
                }
                document.getElementById('modal-credentials').classList.remove('hidden');
            });
        @endif

        function confirmAction(formId, title, text, iconType = 'warning') {
            Swal.fire({
                title: title,
                text: text,
                icon: iconType,
                showCancelButton: true,
                confirmButtonColor: '#001144',
                cancelButtonColor: '#EF4444',
                confirmButtonText: 'Sí, confirmar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'rounded-[2.5rem]',
                    confirmButton: 'rounded-xl font-black uppercase tracking-widest text-[10px] px-8 py-4',
                    cancelButton: 'rounded-xl font-black uppercase tracking-widest text-[10px] px-8 py-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        let isEditMode = false;

        function selectAvatar(url, element) {
            document.getElementById('selected_avatar').value = url;
            let previewUrl = url;
            if (!url.startsWith('http')) {
                 previewUrl = element.querySelector('img').src;
            }
            document.getElementById('avatar-preview-large').src = previewUrl;

            document.querySelectorAll('.avatar-option').forEach(el => {
                el.classList.remove('border-[#FF7A00]', 'scale-105', 'bg-orange-50');
                el.querySelector('.check-icon').classList.add('scale-0');
            });
            element.classList.add('border-[#FF7A00]', 'scale-105', 'bg-orange-50');
            element.querySelector('.check-icon').classList.remove('scale-0');
            
            document.getElementById('avatar-helper').classList.add('hidden');
        }

        function generatePassword() {
            const chars = "abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789"; // Removed ambiguous chars
            let pass = "";
            for(let i=0; i<10; i++) pass += chars.charAt(Math.floor(Math.random() * chars.length));
            document.getElementById('password').value = pass;
        }

        function openCreateModal() {
            isEditMode = false;
            document.getElementById('modalTitle').innerText = "Nuevo Staff";
            document.getElementById('userForm').action = "{{ route('admin.staff.store') }}";
            document.getElementById('methodField').value = "POST";
            document.getElementById('userForm').reset();
            generatePassword();
            document.getElementById('password').required = true;
            document.getElementById('pass-hint').classList.add('hidden');
            document.getElementById('selected_avatar').value = "";
            document.getElementById('avatar-preview-large').src = "https://ui-avatars.com/api/?name=User&background=001144&color=fff&size=256";
            document.getElementById('modal-user').classList.remove('hidden');
        }

        function openEditModal(user) {
            isEditMode = true;
            document.getElementById('modalTitle').innerText = "Editar Usuario";
            let baseUrl = "{{ route('admin.staff.index') }}"; 
            document.getElementById('userForm').action = baseUrl + '/' + user.id;
            document.getElementById('methodField').value = "PUT";

            let parts = (user.name || '').split(' ');
            document.getElementById('name').value = parts[0] || '';
            document.getElementById('lastname').value = parts.slice(1).join(' ') || '';
            document.getElementById('username').value = user.username || '';
            document.getElementById('email').value = user.email || '';
            document.getElementById('role').value = user.role || 'moderator';
            
            let avatarPath = user.avatar_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name || 'User') + '&background=001144&color=fff';
            document.getElementById('selected_avatar').value = avatarPath;
            document.getElementById('avatar-preview-large').src = avatarPath.startsWith('http') ? avatarPath : "{{ asset('') }}" + avatarPath;

            document.getElementById('password').value = "";
            document.getElementById('password').required = false;
            document.getElementById('pass-hint').classList.remove('hidden');
            document.getElementById('modal-user').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal-user').classList.add('hidden');
        }

        function handleSave() {
            const form = document.getElementById('userForm');
            if (!document.getElementById('selected_avatar').value) {
                document.getElementById('avatar-helper').classList.remove('hidden');
                return;
            }

            if (form.checkValidity()) {
                form.submit();
            } else {
                form.reportValidity();
            }
        }

        function copyToClipboard() {
            const nameInput = document.getElementById('name').value;
            const name = nameInput || window.tempCredentialName || 'Usuario';
            const email = document.getElementById('cred-email').innerText;
            const pass = document.getElementById('cred-pass').innerText;
            const loginUrl = "{{ route('login') }}";
            
            const text = `Hola ${name}, bienvenido a la plataforma administrativa de CapacitaPerú.\n\nAquí tienes tus credenciales de acceso:\n📧 Correo: ${email}\n🔑 Contraseña: ${pass}\n\nIngresa aquí: ${loginUrl}\n\n*Recuerda configurar tu PIN de seguridad personal al ingresar por primera vez.*`;
            
            navigator.clipboard.writeText(text).then(() => {
                Toast.fire({ icon: 'success', title: 'Copiado al portapapeles' });
            });
        }

        function submitRealForm() {
            document.getElementById('modal-credentials').classList.add('hidden');
            location.reload();
        }

        function openPermissionsModal(userId, userName) {
            document.getElementById('modal-permissions').classList.remove('hidden');
            document.getElementById('perm-username').innerText = userName;
            document.getElementById('current-perm-user-id').value = userId;
            document.getElementById('permissions-grid').innerHTML = '';
            document.getElementById('permissions-loading').classList.remove('hidden');

            fetch(`/admin/usuarios/${userId}/permissions`)
                .then(res => res.json())
                .then(data => {
                    renderPermissionsGrid(data.grouped_permissions, data.user_permissions);
                    document.getElementById('permissions-loading').classList.add('hidden');
                })
                .catch(err => {
                    Toast.fire({ icon: 'error', title: 'Error al sincronizar permisos' });
                    closePermissionsModal();
                });
        }

        function closePermissionsModal() {
            document.getElementById('modal-permissions').classList.add('hidden');
        }

        function renderPermissionsGrid(groupedPermissions, userPermissions) {
            const grid = document.getElementById('permissions-grid');
            grid.innerHTML = '';

            for (const [groupName, perms] of Object.entries(groupedPermissions)) {
                const card = document.createElement('div');
                card.className = "bg-gray-50/50 rounded-[2rem] p-6 border border-gray-100 hover:shadow-xl hover:shadow-gray-200/20 transition-all";

                let checkboxes = '';
                perms.forEach(perm => {
                    const isChecked = userPermissions.includes(perm.id) ? 'checked' : '';
                    checkboxes += `
                        <label class="flex items-start gap-4 cursor-pointer hover:bg-white p-3 rounded-2xl transition-all group/check border border-transparent hover:border-orange-100">
                            <div class="relative flex items-center mt-0.5">
                                <input type="checkbox" name="permissions[]" value="${perm.id}" ${isChecked} 
                                    class="peer appearance-none w-5 h-5 border-2 border-gray-200 rounded-lg checked:bg-[#FF7A00] checked:border-[#FF7A00] transition-all">
                                <i class="ph-bold ph-check absolute inset-0 m-auto text-white text-[10px] opacity-0 peer-checked:opacity-100 pointer-events-none"></i>
                            </div>
                            <div class="flex-1">
                                <span class="text-[11px] font-black text-[#001144] block group-hover/check:text-[#FF7A00] uppercase tracking-wider">${perm.label}</span>
                                <span class="text-[8px] text-gray-400 font-mono uppercase tracking-widest">${perm.key}</span>
                            </div>
                        </label>
                    `;
                });

                card.innerHTML = `
                    <h4 class="font-black text-[#001144] text-[10px] mb-6 pb-3 border-b border-gray-200 flex items-center gap-2 uppercase tracking-[0.2em]">
                        <i class="ph-fill ph-folder-open text-[#FF7A00]"></i> ${groupName}
                    </h4>
                    <div class="space-y-1">${checkboxes}</div>
                `;
                grid.appendChild(card);
            }
        }

        function savePermissions() {
            const userId = document.getElementById('current-perm-user-id').value;
            const form = document.getElementById('permissions-form');
            const formData = new FormData(form);

            fetch(`/admin/usuarios/${userId}/permissions`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Toast.fire({ icon: 'success', title: 'Matriz actualizada' });
                    closePermissionsModal();
                } else {
                    Toast.fire({ icon: 'error', title: 'Error al procesar solicitud' });
                }
            })
            .catch(err => Toast.fire({ icon: 'error', title: 'Error de red' }));
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #00114410; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #00114420; }
    </style>
</x-layouts.admin>