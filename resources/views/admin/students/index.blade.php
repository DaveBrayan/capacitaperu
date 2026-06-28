<x-layouts.admin>
    <x-slot name="title">Estudiantes - Gestión</x-slot>
    <x-slot name="header">Directorio de Estudiantes</x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- BARRA DE HERRAMIENTAS -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-10 fade-in-up">
        <form action="{{ route('admin.students.index') }}" method="GET" class="flex flex-wrap gap-4 w-full md:w-auto">
            <div class="relative w-full md:w-96">
                <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Nombre, correo, identificación o WhatsApp..." class="w-full pl-12 pr-4 py-3.5 rounded-[1.2rem] border border-gray-100 focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/5 outline-none transition-all text-sm font-medium bg-white shadow-sm">
            </div>
            
            <div class="flex gap-2">
                <select name="status" onchange="this.form.submit()" class="px-5 py-3.5 rounded-[1.2rem] border border-gray-100 bg-white text-[#001144] text-xs font-black uppercase tracking-wider focus:border-[#FF7A00] outline-none cursor-pointer shadow-sm hover:bg-gray-50 transition-colors">
                    <option value="Todos" {{ request('status') == 'Todos' ? 'selected' : '' }}>Todos los Estados</option>
                    <option value="Activos" {{ request('status') == 'Activos' ? 'selected' : '' }}>Solo Activos</option>
                    <option value="Inactivos" {{ request('status') == 'Inactivos' ? 'selected' : '' }}>Solo Inactivos</option>
                </select>
            </div>
        </form>

        <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-blue-50 text-[10px] font-black uppercase tracking-widest text-[#001144] border border-blue-100">
            <i class="ph-fill ph-info text-blue-500"></i> Mostrando alumnos con inscripciones aprobadas
        </div>
    </div>

    <!-- TABLA DE ESTUDIANTES -->
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-100/50 border border-gray-50 overflow-hidden fade-in-up" style="animation-delay: 0.2s">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/30 text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] border-b border-gray-50">
                        <th class="px-8 py-5 w-16 text-center">ID</th>
                        <th class="px-8 py-5">Perfil del Estudiante</th>
                        <th class="px-8 py-5">Identificación / Contacto</th>
                        <th class="px-8 py-5">Inscripciones Activas</th>
                        <th class="px-8 py-5 text-center">Estado</th>
                        <th class="px-8 py-5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($students as $index => $student)
                    <tr class="hover:bg-gray-50/50 transition-all duration-300 group">
                        <!-- NUMERACIÓN -->
                        <td class="px-8 py-6 text-center font-black text-gray-300">
                            {{ $students->firstItem() + $index }}
                        </td>

                        <!-- ESTUDIANTE -->
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 p-0.5 border-2 border-white shadow-sm ring-1 ring-gray-100 overflow-hidden shrink-0">
                                    <img src="{{ Str::startsWith($student->avatar_url, 'http') ? $student->avatar_url : asset($student->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($student->name).'&background=001144&color=fff') }}" class="w-full h-full rounded-[0.8rem] object-cover">
                                </div>
                                <div class="max-w-[200px]">
                                    <p class="font-black text-[#001144] text-base leading-tight mb-0.5 group-hover:text-[#FF7A00] transition-colors">{{ $student->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider truncate">{{ $student->email }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- CONTACTO -->
                        <td class="px-8 py-6">
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 text-xs font-bold text-[#001144]">
                                    <i class="ph-bold ph-identification-card text-[#FF7A00]"></i>
                                    <span>{{ $student->ci_nit ?? 'DNI/CI no registrado' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs font-bold text-[#001144]">
                                    <i class="ph-fill ph-whatsapp-logo text-[#25D366] text-lg"></i>
                                    <span class="font-black tracking-tight">{{ $student->phone ?? 'S/N' }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- CURSOS -->
                        <td class="px-8 py-6">
                            <div class="flex flex-col gap-2 py-1">
                                @forelse($student->enrollments as $enrollment)
                                    <div class="flex items-center gap-2 group/item">
                                        <div class="w-1.5 h-1.5 rounded-full bg-[#FF7A00] shrink-0 shadow-[0_0_5px_rgba(255,122,0,0.4)]"></div>
                                        <span class="text-[10px] font-black text-[#001144] uppercase tracking-wide leading-tight group-hover/item:text-[#FF7A00] transition-colors truncate max-w-[180px]" title="{{ $enrollment->course->title }}">
                                            {{ $enrollment->course->title }}
                                        </span>
                                    </div>
                                @empty
                                    <span class="text-[10px] text-gray-300 font-bold uppercase italic tracking-widest">Sin cursos activos</span>
                                @endforelse
                                @if($student->enrollments_count > 2)
                                    <span class="text-[9px] font-black text-[#FF7A00] bg-orange-50 px-2 py-0.5 rounded-full w-fit mt-1 uppercase tracking-tighter">
                                        + {{ $student->enrollments_count - 2 }} programas más
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- ESTADO -->
                        <td class="px-8 py-6 text-center">
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border text-[10px] font-black uppercase tracking-widest {{ $student->status === 'active' ? 'bg-green-50 text-green-500 border-green-100' : 'bg-gray-100 text-gray-400 border-gray-200' }}">
                                <div class="w-2 h-2 rounded-full {{ $student->status === 'active' ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                                {{ $student->status === 'active' ? 'Activo' : 'Inactivo' }}
                            </div>
                        </td>

                        <!-- ACCIONES -->
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(Auth::user()->hasPermission('students.manage'))
                                    <button type="button" onclick='openEditModal(@json($student))' class="w-10 h-10 rounded-[0.8rem] flex items-center justify-center text-gray-400 hover:text-[#001144] hover:bg-gray-100 transition-all duration-300" title="Editar Perfil">
                                        <i class="ph-bold ph-pencil-simple text-xl"></i>
                                    </button>
                                    <button type="button" onclick="confirmResetPass({{ $student->id }})" class="w-10 h-10 rounded-[0.8rem] flex items-center justify-center text-gray-400 hover:text-blue-500 hover:bg-blue-50 transition-all duration-300" title="Nueva Contraseña">
                                        <i class="ph-bold ph-key text-xl"></i>
                                    </button>
                                    <button type="button" onclick="confirmResetPin({{ $student->id }})" class="w-10 h-10 rounded-[0.8rem] flex items-center justify-center text-gray-400 hover:text-purple-500 hover:bg-purple-50 transition-all duration-300" title="Nuevo PIN">
                                        <i class="ph-bold ph-password text-xl"></i>
                                    </button>
                                    <button type="button" onclick="openHistoryModal({{ $student->id }}, '{{ $student->name }}')" class="w-10 h-10 rounded-[0.8rem] flex items-center justify-center text-gray-400 hover:text-[#FF7A00] hover:bg-orange-50 transition-all duration-300" title="Avance Académico">
                                        <i class="ph-bold ph-chart-line-up text-xl"></i>
                                    </button>
                                    <button type="button" onclick="confirmDelete({{ $student->id }})" class="w-10 h-10 rounded-[0.8rem] flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all duration-300" title="Eliminar Acceso">
                                        <i class="ph-bold ph-trash text-xl"></i>
                                    </button>

                                    <form id="form-reset-{{ $student->id }}" action="{{ route('admin.students.reset_password', $student->id) }}" method="POST" class="hidden">@csrf @method('PUT')</form>
                                    <form id="form-reset-pin-{{ $student->id }}" action="{{ route('admin.students.reset_pin', $student->id) }}" method="POST" class="hidden">@csrf @method('PUT')</form>
                                    <form id="form-delete-{{ $student->id }}" action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-24 h-24 bg-gray-50 rounded-[2rem] flex items-center justify-center mb-6 ring-8 ring-gray-50/50">
                                    <i class="ph-duotone ph-users text-5xl text-gray-200"></i>
                                </div>
                                <h3 class="text-xl font-black text-gray-600 mb-2">No se encontraron alumnos</h3>
                                <p class="text-sm text-gray-400 font-medium max-w-sm">No hay registros que coincidan con los criterios de búsqueda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-8 py-6 bg-gray-50/30 border-t border-gray-50">
            {{ $students->links() }}
        </div>
    </div>

    <!-- MODAL DE EDICIÓN -->
    <div id="modal-student" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-[#001144]/80 backdrop-blur-md transition-opacity duration-500" onclick="closeModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <form id="studentForm" action="" method="POST" enctype="multipart/form-data" class="relative bg-[#f8faff] rounded-[2.5rem] shadow-[0_25px_80px_-15px_rgba(0,0,0,0.4)] w-full max-w-2xl overflow-hidden z-10 border border-white/20">
                @csrf @method('PUT')
                <div class="bg-[#001144] p-8 text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-black tracking-tight">Gestión de Perfil</h3>
                        <p class="text-white/40 text-[10px] font-black uppercase tracking-widest mt-1">Actualización de datos del estudiante</p>
                    </div>
                    <button type="button" onclick="closeModal()" class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center transition-all"><i class="ph-bold ph-x text-lg"></i></button>
                </div>
                <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2 flex justify-center mb-4">
                         <div class="relative">
                            <div class="w-32 h-32 rounded-[2.5rem] bg-white p-1 border-4 border-white shadow-xl overflow-hidden ring-2 ring-gray-100">
                                <img id="avatar-preview-large" src="" class="w-full h-full rounded-[2.2rem] object-cover">
                            </div>
                            <input type="file" name="avatar_file" id="avatar_file" class="hidden" onchange="previewFileAvatar(this)">
                            <button type="button" onclick="document.getElementById('avatar_file').click()" class="absolute -bottom-2 -right-2 w-10 h-10 bg-[#FF7A00] text-white rounded-2xl shadow-lg hover:scale-110 active:scale-95 transition-all flex items-center justify-center">
                                <i class="ph-bold ph-camera text-lg"></i>
                            </button>
                         </div>
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Nombre Completo</label>
                        <input type="text" id="name" name="name" required class="w-full px-5 py-3.5 rounded-2xl border border-gray-100 bg-white focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/5 outline-none text-sm font-black text-[#001144] transition-all">
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Identificación (CI/NIT)</label>
                        <input type="text" id="ci_nit" name="ci_nit" class="w-full px-5 py-3.5 rounded-2xl border border-gray-100 bg-white focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/5 outline-none text-sm font-black text-[#001144] transition-all">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Correo Electrónico de Acceso</label>
                        <input type="email" id="email" name="email" required class="w-full px-5 py-3.5 rounded-2xl border border-gray-100 bg-white focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/5 outline-none text-sm font-black text-[#001144] transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">WhatsApp / Contacto</label>
                        <input type="text" id="phone" name="phone" class="w-full px-5 py-3.5 rounded-2xl border border-gray-100 bg-white focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/5 outline-none text-sm font-black text-[#001144] transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Estado Operativo</label>
                        <select id="status" name="status" class="w-full px-5 py-3.5 rounded-2xl border border-gray-100 bg-white focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/5 outline-none text-sm font-black text-[#001144] transition-all appearance-none cursor-pointer">
                            <option value="active">Activo - Acceso Total</option>
                            <option value="inactive">Inactivo - Acceso Restringido</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 flex justify-end gap-4 pt-6 border-t border-gray-100">
                        <button type="button" onclick="closeModal()" class="px-8 py-3.5 text-gray-400 font-black text-sm uppercase tracking-widest hover:text-[#001144] transition-colors">Cancelar</button>
                        <button type="submit" class="px-10 py-3.5 bg-[#001144] text-white font-black text-sm uppercase tracking-widest rounded-[1.2rem] shadow-xl shadow-[#001144]/20 hover:bg-[#002266] transition-all">Actualizar Estudiante</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DE CREDENCIALES -->
    <div id="modal-credentials" class="fixed inset-0 z-[60] hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-[#001144]/90 backdrop-blur-lg transition-opacity"></div>
        <div class="relative z-10 flex min-h-full items-center justify-center p-4">
            <div class="bg-white rounded-[3rem] shadow-[0_25px_80px_-15px_rgba(0,0,0,0.5)] p-10 max-w-md w-full text-center border border-white/20">
                <div class="w-20 h-20 bg-blue-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-[#001144] ring-8 ring-blue-50/50">
                    <i class="ph-fill ph-shield-check text-4xl"></i>
                </div>
                <h3 class="text-2xl font-black text-[#001144] mb-2 tracking-tight">Acceso Generado</h3>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-8">Nuevas credenciales de seguridad</p>
                
                <div class="bg-gray-50 rounded-[1.5rem] p-6 text-left border border-gray-100 mb-8 space-y-4">
                    <div class="flex flex-col">
                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-widest mb-1">Identificador de Usuario</span>
                        <span class="text-[#001144] font-black text-sm" id="cred-email">...</span>
                    </div>
                    <div id="row-pass" class="flex flex-col hidden pt-3 border-t border-gray-200/50">
                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-widest mb-1">Nueva Contraseña</span>
                        <span class="text-[#FF7A00] font-black text-xl tracking-wider" id="cred-pass">...</span>
                    </div>
                    <div id="row-pin" class="flex flex-col hidden pt-3 border-t border-gray-200/50">
                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-widest mb-1">PIN de Transacciones</span>
                        <span class="text-purple-600 font-black text-xl tracking-[0.2em]" id="cred-pin">...</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <button onclick="copyToClipboard()" class="w-full py-4 bg-[#25D366] text-white font-black uppercase tracking-widest text-xs rounded-2xl shadow-xl shadow-green-500/20 flex items-center justify-center gap-3 hover:bg-[#20bd5b] transition-all">
                        <i class="ph-fill ph-whatsapp-logo text-xl"></i> Copiar y Notificar por WhatsApp
                    </button>
                    <button onclick="document.getElementById('modal-credentials').classList.add('hidden')" class="w-full py-4 bg-gray-100 text-gray-400 font-black uppercase tracking-widest text-[10px] rounded-2xl hover:text-[#001144] transition-all">
                        Cerrar Ventana
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL HISTORIAL ACADÉMICO -->
    <div id="modal-history" class="fixed inset-0 z-[70] hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-[#001144]/95 backdrop-blur-xl transition-opacity duration-500" onclick="closeHistoryModal()"></div>
        <div class="relative z-10 flex min-h-full items-center justify-center p-4">
            <div class="bg-[#f8faff] rounded-[3rem] shadow-[0_25px_80px_-15px_rgba(0,0,0,0.6)] w-full max-w-5xl overflow-hidden flex flex-col max-h-[92vh] border border-white/20">
                <div class="bg-[#001144] p-8 text-white flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="text-xl font-black flex items-center gap-4 tracking-tight">
                            <div class="w-10 h-10 rounded-2xl bg-[#FF7A00] flex items-center justify-center text-white shadow-lg shadow-orange-500/20">
                                <i class="ph-bold ph-graduation-cap"></i>
                            </div>
                            Expediente de <span id="history-student-name" class="text-[#FF7A00]">...</span>
                        </h3>
                        <p class="text-white/40 text-[10px] font-black uppercase tracking-[0.15em] mt-2">Monitoreo de avance académico y certificaciones</p>
                    </div>
                    <button onclick="closeHistoryModal()" class="w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center transition-all"><i class="ph-bold ph-x text-xl"></i></button>
                </div>
                
                <div class="flex-1 overflow-y-auto p-8 custom-scrollbar bg-gray-50/50" id="history-container">
                    <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                        <div class="w-20 h-20 rounded-full border-4 border-gray-100 border-t-[#FF7A00] animate-spin mb-6"></div>
                        <p class="font-black text-xs uppercase tracking-widest">Sincronizando expediente académico...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const Toast = Swal.mixin({ 
            toast: true, 
            position: 'top-end', 
            showConfirmButton: false, 
            timer: 3000, 
            timerProgressBar: true,
            background: '#001144',
            color: '#fff',
            customClass: {
                popup: 'rounded-2xl border border-white/10 shadow-2xl'
            }
        });

        @if(session('success')) Toast.fire({ icon: 'success', title: '{{ session('success') }}' }); @endif
        @if(session('credentials_modal'))
            document.addEventListener('DOMContentLoaded', function() {
                const data = @json(session('credentials_modal'));
                document.getElementById('cred-email').innerText = data.email;
                if (data.password) {
                    document.getElementById('row-pass').classList.remove('hidden');
                    document.getElementById('cred-pass').innerText = data.password;
                    window.tempCredentialType = 'password';
                }
                if (data.pin) {
                    document.getElementById('row-pin').classList.remove('hidden');
                    document.getElementById('cred-pin').innerText = data.pin;
                    window.tempCredentialType = 'pin';
                }
                window.tempCredentialData = data;
                document.getElementById('modal-credentials').classList.remove('hidden');
            });
        @endif

        function confirmResetPass(id) { 
            Swal.fire({ 
                title: '¿Generar nueva contraseña?', 
                text: 'El estudiante recibirá una clave aleatoria para su próximo acceso.', 
                icon: 'warning', 
                showCancelButton: true, 
                confirmButtonColor: '#001144', 
                cancelButtonColor: '#f44336',
                confirmButtonText: 'Sí, generar',
                cancelButtonText: 'Cancelar',
                customClass: { popup: 'rounded-[2rem]', confirmButton: 'rounded-xl px-6 py-3', cancelButton: 'rounded-xl px-6 py-3' }
            }).then((r) => { if(r.isConfirmed) document.getElementById('form-reset-'+id).submit(); }); 
        }

        function confirmResetPin(id) { 
            Swal.fire({ 
                title: '¿Restablecer PIN de seguridad?', 
                text: 'Se generará un nuevo PIN numérico para transacciones en la plataforma.', 
                icon: 'question', 
                showCancelButton: true, 
                confirmButtonColor: '#001144', 
                confirmButtonText: 'Generar PIN',
                customClass: { popup: 'rounded-[2rem]' }
            }).then((r) => { if(r.isConfirmed) document.getElementById('form-reset-pin-'+id).submit(); }); 
        }

        function confirmDelete(id) { 
            Swal.fire({ 
                title: '¿Eliminar permanentemente?', 
                text: 'Esta acción borrará el acceso, historial y todo el progreso del estudiante. ¡Cuidado!', 
                icon: 'error', 
                showCancelButton: true, 
                confirmButtonColor: '#d33', 
                confirmButtonText: 'Sí, borrar todo',
                customClass: { popup: 'rounded-[2rem]' }
            }).then((r) => { if(r.isConfirmed) document.getElementById('form-delete-'+id).submit(); }); 
        }

        function openEditModal(student) {
            document.getElementById('studentForm').action = "{{ route('admin.students.update', ':id') }}".replace(':id', student.id);
            document.getElementById('name').value = student.name;
            document.getElementById('email').value = student.email;
            document.getElementById('ci_nit').value = student.ci_nit || '';
            document.getElementById('phone').value = student.phone || '';
            document.getElementById('status').value = student.status;
            let avatarSrc = student.avatar_url ? (student.avatar_url.startsWith('http') ? student.avatar_url : "{{ asset('') }}" + student.avatar_url) : "https://ui-avatars.com/api/?name=" + encodeURIComponent(student.name) + "&background=001144&color=fff";
            document.getElementById('avatar-preview-large').src = avatarSrc;
            document.getElementById('modal-student').classList.remove('hidden');
        }

        function closeModal() { document.getElementById('modal-student').classList.add('hidden'); }

        function previewFileAvatar(input) { if (input.files && input.files[0]) { const reader = new FileReader(); reader.onload = e => { document.getElementById('avatar-preview-large').src = e.target.result; }; reader.readAsDataURL(input.files[0]); } }

        function copyToClipboard() {
            const data = window.tempCredentialData || {};
            const type = window.tempCredentialType || 'password';
            let text = '';
            if (type === 'password') text = `Hola ${data.name}, tu nueva contraseña para CapacitaPerú es: ${document.getElementById('cred-pass').innerText}`;
            else if (type === 'pin') text = `Hola ${data.name}, tu nuevo PIN de seguridad para CapacitaPerú es: ${document.getElementById('cred-pin').innerText}`;
            navigator.clipboard.writeText(text).then(() => Toast.fire({ icon: 'success', title: 'Copiado al portapapeles' }));
        }

        let currentStudentId = null;

        function openHistoryModal(id, name) {
            currentStudentId = id;
            document.getElementById('history-student-name').innerText = name;
            document.getElementById('modal-history').classList.remove('hidden');
            loadHistory(id);
        }

        function closeHistoryModal() {
            document.getElementById('modal-history').classList.add('hidden');
            currentStudentId = null;
        }

        function loadHistory(id) {
            const container = document.getElementById('history-container');
            container.innerHTML = '<div class="flex flex-col items-center justify-center py-20 text-gray-400"><div class="w-16 h-16 rounded-full border-4 border-gray-100 border-t-[#FF7A00] animate-spin mb-4"></div><p class="font-black text-xs uppercase tracking-widest">Sincronizando expediente académico...</p></div>';

            fetch(`/admin/estudiantes/${id}/historial`)
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        container.innerHTML = `
                            <div class="flex flex-col items-center justify-center py-20 bg-white rounded-[2rem] border-2 border-dashed border-gray-100">
                                <div class="w-20 h-20 rounded-full bg-gray-50 flex items-center justify-center mb-6">
                                    <i class="ph-duotone ph-student text-4xl text-gray-200"></i>
                                </div>
                                <h3 class="text-lg font-black text-gray-600 mb-1">Sin programas activos</h3>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider text-center">Este estudiante no registra inscripciones aprobadas en el sistema</p>
                            </div>
                        `;
                        return;
                    }

                    let html = '<div class="grid grid-cols-1 gap-6" x-data="{ activeCourse: null }">';
                    data.forEach((course, index) => {
                        const progress = course.progress_percent;
                        const certUrl = course.certificate_uuid ? `/validar/certificado/${course.certificate_uuid}` : '#';
                        const isOpen = index === 0 ? 'true' : 'false';

                        let topicsHtml = '';
                        course.topics.forEach(topic => {
                            let lessonsHtml = '';
                            topic.lessons.forEach(lesson => {
                                const isChecked = lesson.is_completed ? 'checked' : '';
                                const typeIcon = lesson.type === 'quiz' ? 'ph-list-checks text-purple-500 bg-purple-50' : 'ph-play-circle text-blue-500 bg-blue-50';
                                
                                lessonsHtml += `
                                    <div class="flex items-center justify-between p-4 bg-white rounded-2xl border border-gray-100 hover:border-[#FF7A00]/20 transition-all group/lesson">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center ${typeIcon} shrink-0 transition-transform group-hover/lesson:scale-110">
                                                <i class="ph-bold text-xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-[#001144]">${lesson.title}</p>
                                                <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">${lesson.type}</p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" value="" class="sr-only peer" ${isChecked} onchange="toggleLesson(${lesson.id}, this.checked)">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                                        </label>
                                    </div>
                                `;
                            });

                            topicsHtml += `
                                <div class="mb-8 last:mb-0">
                                    <h5 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-1 flex items-center gap-2">
                                        <span class="w-8 h-[1px] bg-gray-200"></span> ${topic.title}
                                    </h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">${lessonsHtml}</div>
                                </div>
                            `;
                        });

                        let finalEvalHtml = '';
                        let evalConfig = course.evaluation_config;
                        if (typeof evalConfig === 'string') {
                            try { evalConfig = JSON.parse(evalConfig); } catch(e) { evalConfig = {}; }
                        }
                        evalConfig = evalConfig || {};

                        const isExam = evalConfig.final_exam_enabled == true || evalConfig.final_exam_enabled == 1 || evalConfig.final_exam_enabled == '1';
                        const isProject = evalConfig.final_project_enabled == true || evalConfig.final_project_enabled == 1 || evalConfig.final_project_enabled == '1';

                        const minScore = parseInt(evalConfig.min_score || 51);
                        const finalScore = course.final_score;
                        const isCertAvailable = (progress === 100) || (finalScore !== null && finalScore >= minScore);

                        if (isExam || isProject) {
                            const finalTitle = (isExam && !isProject) ? 'Examen de Certificación' : ((!isExam && isProject) ? 'Proyecto de Grado' : 'Evaluación Integral');
                            const hasAttempt = finalScore !== null;
                            
                            let statusBadge = '';
                            let resetButton = '';
                            let detailsHtml = '';

                            if (!hasAttempt) {
                                statusBadge = '<span class="px-3 py-1 bg-gray-100 text-gray-400 font-black text-[9px] uppercase tracking-widest rounded-full">Pendiente</span>';
                            } else {
                                if (finalScore >= minScore) {
                                    statusBadge = `<span class="px-3 py-1 bg-green-50 text-green-500 border border-green-100 font-black text-[9px] uppercase tracking-widest rounded-full">Aprobado • ${finalScore} pts</span>`;
                                } else {
                                    statusBadge = `<span class="px-3 py-1 bg-red-50 text-red-500 border border-red-100 font-black text-[9px] uppercase tracking-widest rounded-full">Reprobado • ${finalScore} pts</span>`;
                                    
                                    resetButton = `
                                        <button onclick="confirmResetEval(${currentStudentId}, ${course.course_id})" 
                                                class="px-4 py-2 bg-white border border-red-100 text-red-500 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-red-50 transition-all shadow-sm flex items-center gap-2">
                                            <i class="ph-bold ph-arrow-counter-clockwise"></i> Reiniciar Intento
                                        </button>
                                    `;
                                }
                            }

                            if (isExam && evalConfig.final_exam_data && evalConfig.final_exam_data.questions && evalConfig.final_exam_data.questions.length > 0) {
                                let questionsList = '';
                                let studentAnswers = course.exam_answers;
                                if (typeof studentAnswers === 'string') {
                                    try { studentAnswers = JSON.parse(studentAnswers); } catch(e) { studentAnswers = {}; }
                                }
                                studentAnswers = studentAnswers || {}; 

                                evalConfig.final_exam_data.questions.forEach((q, qIdx) => {
                                    let studentAns = null;
                                    let key = 'q_' + qIdx;
                                    
                                    if(studentAnswers[key] !== undefined) studentAns = studentAnswers[key];
                                    else if (studentAnswers[qIdx] !== undefined) studentAns = studentAnswers[qIdx];

                                    if(Array.isArray(studentAns)) studentAns = studentAns.join(', ');
                                    if(studentAns === null) studentAns = 'Sin respuesta';

                                    const correctAns = Array.isArray(q.correct) ? q.correct.join(', ') : (q.correct || 'Evaluación Manual');
                                    
                                    let isCorrectVisual = false;
                                    if(q.type !== 'open' && String(studentAns).trim() == String(correctAns).trim()) isCorrectVisual = true;
                                    
                                    const ansColor = isCorrectVisual ? 'bg-green-50/50 border-green-100' : 'bg-red-50/50 border-red-100';
                                    const icon = isCorrectVisual ? 'ph-check-circle text-green-500' : 'ph-x-circle text-red-500';

                                    questionsList += `
                                        <div class="mb-6 last:mb-0">
                                            <p class="font-black text-[#001144] text-xs mb-3 flex items-start gap-2">
                                                <span class="w-6 h-6 rounded-lg bg-gray-100 flex items-center justify-center text-[10px] shrink-0">${qIdx+1}</span>
                                                ${q.title || q.text}
                                                <span class="text-[9px] text-gray-400 font-bold uppercase ml-auto">(${q.points} pts)</span>
                                            </p>
                                            
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div class="p-4 rounded-2xl border ${ansColor} flex items-start gap-3">
                                                    <i class="ph-fill ${icon} text-lg shrink-0 mt-0.5"></i>
                                                    <div>
                                                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block mb-1">Respuesta del Alumno</span> 
                                                        <span class="text-xs font-bold text-[#001144]">${studentAns}</span>
                                                    </div>
                                                </div>
                                                <div class="p-4 rounded-2xl border border-gray-100 bg-gray-50/50 flex items-start gap-3">
                                                    <i class="ph-fill ph-seal-check text-blue-400 text-lg shrink-0 mt-0.5"></i>
                                                    <div>
                                                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block mb-1">Respuesta Correcta</span> 
                                                        <span class="text-xs font-bold text-[#001144]">${correctAns}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;
                                });

                                detailsHtml = `
                                    <div x-data="{ showDetails: false }" class="mt-4 w-full">
                                        <button @click="showDetails = !showDetails" class="px-5 py-2 bg-white/50 border border-purple-100 rounded-xl text-[9px] font-black text-purple-600 uppercase tracking-widest hover:bg-purple-100 transition-all flex items-center gap-2">
                                            <i class="ph-bold" :class="showDetails ? 'ph-caret-up' : 'ph-caret-down'"></i> Auditoría de Respuestas
                                        </button>
                                        <div x-show="showDetails" x-collapse class="mt-4 bg-white p-6 rounded-[2rem] border border-purple-100 shadow-xl shadow-purple-500/5">
                                            ${questionsList}
                                        </div>
                                    </div>
                                `;
                            }

                            finalEvalHtml = `
                                <div class="mt-10 pt-8 border-t border-gray-100">
                                    <div class="p-6 bg-purple-50/30 rounded-[2rem] border border-purple-100/50">
                                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                            <div class="flex items-center gap-4">
                                                <div class="w-14 h-14 rounded-2xl bg-purple-600 flex items-center justify-center text-white shrink-0 shadow-xl shadow-purple-500/20">
                                                    <i class="ph-fill ph-sketch-logo text-2xl"></i>
                                                </div>
                                                <div>
                                                    <p class="text-base font-black text-[#001144] tracking-tight">${finalTitle}</p>
                                                    <p class="text-[9px] text-purple-600 font-black uppercase tracking-widest">Requisito Final de Certificación</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                ${statusBadge}
                                                ${resetButton}
                                            </div>
                                        </div>
                                        ${detailsHtml}
                                    </div>
                                </div>
                            `;
                        }

                        html += `
                            <div class="bg-white border border-gray-100 rounded-[2.5rem] overflow-hidden shadow-sm transition-all hover:shadow-xl hover:shadow-gray-200/20" x-data="{ open: ${isOpen} }">
                                <div @click="open = !open" class="p-6 md:p-8 flex items-center justify-between cursor-pointer bg-white hover:bg-gray-50/50 transition-all">
                                    <div class="flex items-center gap-6">
                                        <div class="w-16 h-16 rounded-[1.5rem] bg-blue-50 flex flex-col items-center justify-center border border-blue-100 shadow-inner group">
                                            <span class="text-xl font-black text-[#001144] tracking-tighter leading-none">${progress}</span>
                                            <span class="text-[8px] font-black text-blue-400 uppercase tracking-widest">%</span>
                                        </div>
                                        <div>
                                            <h4 class="font-black text-[#001144] text-lg tracking-tight mb-1">${course.course_title}</h4>
                                            <div class="flex items-center gap-2">
                                                <i class="ph-bold ph-calendar-check text-[#FF7A00]"></i>
                                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Iniciado el ${course.enrolled_at}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        ${(course.certificate_uuid && isCertAvailable) ? 
                                            `<a href="${certUrl}" target="_blank" @click.stop class="px-6 py-3 bg-[#FF7A00] hover:bg-[#e66e00] text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-xl shadow-orange-500/20 flex items-center gap-2 transition-all hover:-translate-y-1">
                                                <i class="ph-bold ph-certificate text-lg"></i> Certificado
                                            </a>` : 
                                            `<span class="px-4 py-2 bg-gray-50 text-gray-300 text-[9px] font-black uppercase tracking-[0.2em] rounded-full border border-gray-100">Sin Emitir</span>`
                                        }
                                        <div class="w-10 h-10 rounded-full border border-gray-100 flex items-center justify-center text-gray-400 transition-all duration-500" :class="{ 'rotate-180 bg-[#001144] border-[#001144] text-white': open }">
                                            <i class="ph-bold ph-caret-down"></i>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="open" x-collapse class="border-t border-gray-50 p-8 md:p-10 bg-white">
                                    <div class="max-w-4xl mx-auto">
                                        ${topicsHtml}
                                        ${finalEvalHtml}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    container.innerHTML = html;
                });
        }

        function toggleLesson(lessonId, isCompleted) {
            if (!currentStudentId) return;

            fetch('{{ route("admin.students.toggle_progress") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    student_id: currentStudentId,
                    lesson_id: lessonId,
                    completed: isCompleted ? 1 : 0
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Toast.fire({ icon: 'success', title: 'Progreso del alumno actualizado' });
                    if (data.new_progress === 100 && data.certificate_uuid) {
                        loadHistory(currentStudentId);
                    }
                }
            })
            .catch(err => console.error(err));
        }

        function confirmResetEval(studentId, courseId) {
            Swal.fire({
                title: '¿Reiniciar evaluación?',
                text: "Se borrará la calificación y el estudiante podrá rendir su prueba final nuevamente. Esta acción es irreversible.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#001144',
                confirmButtonText: 'Sí, reiniciar',
                cancelButtonText: 'Cancelar',
                customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/estudiantes/${studentId}/reset-eval/${courseId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            Toast.fire({ icon: 'success', title: 'La evaluación ha sido reseteada' });
                            loadHistory(studentId);
                        } else {
                            Swal.fire('Error', data.message || 'No se pudo procesar la solicitud.', 'error');
                        }
                    })
                    .catch(err => Swal.fire('Error', 'Fallo de conexión con el servidor.', 'error'));
                }
            })
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
    </style>
</x-layouts.admin>