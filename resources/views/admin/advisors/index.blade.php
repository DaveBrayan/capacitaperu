<x-layouts.admin>
    <x-slot name="title">Gestión de Asesores - CapacitaPerú</x-slot>
    <x-slot name="header">Asesores de WhatsApp</x-slot>

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
            <input type="text" placeholder="Buscar asesor..." 
                   class="w-full pl-12 pr-4 py-4 rounded-[1.2rem] border border-gray-100 bg-white shadow-sm focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none text-sm transition-all font-medium">
        </div>

        <button type="button" onclick="openCreateModal()" class="w-full md:w-auto flex items-center justify-center gap-3 px-8 py-4 bg-[#001144] text-white rounded-[1.2rem] hover:bg-[#001144]/90 transition-all shadow-xl shadow-blue-900/20 text-sm font-black uppercase tracking-widest group">
            <i class="ph-bold ph-plus text-lg group-hover:rotate-12 transition-transform"></i>
            Nuevo Asesor
        </button>
    </div>

    <!-- TABLA DE ASESORES -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden fade-in-up" style="animation-delay: 0.2s">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Asesor</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Cargo</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Número</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Estado</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($advisors as $advisor)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-[1rem] bg-gray-100 flex items-center justify-center overflow-hidden border-2 border-white group-hover:border-[#FF7A00] shadow-sm transition-all rotate-2 group-hover:rotate-0">
                                    @if($advisor->avatar_url && Str::startsWith($advisor->avatar_url, 'http'))
                                        <img src="{{ $advisor->avatar_url }}" class="w-full h-full object-cover">
                                    @elseif($advisor->avatar_url)
                                        <img src="{{ asset($advisor->avatar_url) }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($advisor->name) }}&background=001144&color=fff&size=128" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div>
                                    <p class="font-black text-[#001144] tracking-tight text-base leading-none mb-1">{{ $advisor->name }}</p>
                                    <p class="text-[11px] text-gray-400 font-medium">CapacitaPerú Team</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border bg-blue-50 text-blue-600 border-blue-100">
                                <i class="ph-bold ph-identification-card"></i>
                                {{ $advisor->position }}
                            </span>
                        </td>
                        <td class="px-8 py-6 font-mono font-bold text-[#001144]">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $advisor->phone) }}" target="_blank" class="hover:text-[#FF7A00] transition-colors flex items-center gap-2">
                                <i class="ph-fill ph-whatsapp-logo text-green-500 text-lg"></i>
                                {{ $advisor->phone }}
                            </a>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $advisor->status ? 'bg-green-500 animate-pulse' : 'bg-red-500' }}"></span>
                                <span class="text-[10px] font-black uppercase tracking-widest {{ $advisor->status ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $advisor->status ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" onclick='openEditModal(@json($advisor))' class="w-10 h-10 rounded-xl flex items-center justify-center text-[#001144] hover:bg-[#001144] hover:text-white border border-gray-100 transition-all shadow-sm" title="Editar">
                                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                                </button>
                                
                                <form id="form-delete-{{ $advisor->id }}" action="{{ route('admin.advisors.destroy', $advisor->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmAction('form-delete-{{ $advisor->id }}', '¿Eliminar Asesor?', 'Esta acción quitará al asesor de los botones de contacto.', 'error')" class="w-10 h-10 rounded-xl flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white border border-gray-100 transition-all shadow-sm" title="Eliminar">
                                        <i class="ph-bold ph-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-24 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6">
                                <i class="ph-duotone ph-whatsapp-logo text-5xl text-gray-200"></i>
                            </div>
                            <h3 class="text-xl font-black text-[#001144] mb-2 tracking-tight">Sin asesores registrados</h3>
                            <p class="text-gray-400 font-medium max-w-xs mx-auto text-sm">No hay asesores configurados para el contacto por WhatsApp.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <div class="mt-10">
        {{ $advisors->links() }}
    </div>

    <!-- MODAL: ASESOR (CREAR / EDITAR) -->
    <div id="modal-advisor" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-[#001144]/40 backdrop-blur-md transition-opacity" onclick="closeModal()"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                
                <form id="advisorForm" action="{{ route('admin.advisors.store') }}" method="POST" class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all w-full max-w-4xl border border-white/20">
                    @csrf
                    <input type="hidden" name="_method" id="methodField" value="POST">
                    
                    <div class="bg-white px-10 py-8 border-b border-gray-50 flex justify-between items-center">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 rounded-2xl bg-orange-50 flex items-center justify-center text-[#FF7A00]">
                                <i class="ph-fill ph-whatsapp-logo text-3xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-[#001144] tracking-tight" id="modalTitle">Nuevo Asesor</h3>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Contacto WhatsApp</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeModal()" class="w-12 h-12 rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
                            <i class="ph-bold ph-x text-xl"></i>
                        </button>
                    </div>

                    <div class="flex flex-col lg:flex-row">
                        <!-- SELECCIÓN DE AVATAR -->
                        <div class="lg:w-1/3 bg-gray-50/30 p-10 flex flex-col items-center border-r border-gray-50">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-8">Icono de Asesor</p>
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
                        </div>

                        <!-- DATOS DEL ASESOR -->
                        <div class="lg:w-2/3 p-10 bg-white">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nombre Completo</label>
                                    <input type="text" id="name" name="name" required 
                                           class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none text-sm font-bold text-[#001144] transition-all" 
                                           placeholder="Ej: Lic. Juan Pérez">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Cargo / Especialidad</label>
                                    <input type="text" id="position" name="position" required 
                                           class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none text-sm font-bold text-[#001144] transition-all" 
                                           placeholder="Ej: Asesor Académico">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Número WhatsApp</label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-whatsapp-logo absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00] transition-colors"></i>
                                        <input type="text" id="phone" name="phone" required 
                                               class="w-full pl-14 pr-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] transition-all" 
                                               placeholder="+51 987 654 321">
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Estado de Visibilidad</label>
                                    <div class="relative group">
                                        <select id="status" name="status" class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] appearance-none cursor-pointer transition-all">
                                            <option value="1">Activo (Visible en la web)</option>
                                            <option value="0">Inactivo (Oculto)</option>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none group-focus-within:rotate-180 transition-transform"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-12 flex gap-4 pt-10 border-t border-gray-50">
                                <button type="button" onclick="closeModal()" class="flex-1 px-8 py-4 bg-white text-[#001144] font-black uppercase tracking-widest rounded-[1.2rem] border border-gray-100 hover:bg-gray-50 transition-all text-[10px]">Cancelar</button>
                                <button type="submit" class="flex-[2] px-8 py-4 bg-[#001144] text-white font-black uppercase tracking-widest rounded-[1.2rem] hover:bg-[#FF7A00] transition-all shadow-xl shadow-blue-900/10 text-[10px] flex items-center justify-center gap-3 transform hover:-translate-y-1">
                                    <i class="ph-bold ph-check text-lg"></i> Guardar Asesor
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
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
        }

        function openCreateModal() {
            document.getElementById('modalTitle').innerText = "Nuevo Asesor";
            document.getElementById('advisorForm').action = "{{ route('admin.advisors.store') }}";
            document.getElementById('methodField').value = "POST";
            document.getElementById('advisorForm').reset();
            document.getElementById('selected_avatar').value = "";
            document.getElementById('avatar-preview-large').src = "https://ui-avatars.com/api/?name=User&background=001144&color=fff&size=256";
            document.getElementById('modal-advisor').classList.remove('hidden');
        }

        function openEditModal(advisor) {
            document.getElementById('modalTitle').innerText = "Editar Asesor";
            let baseUrl = "{{ route('admin.advisors.index') }}"; 
            document.getElementById('advisorForm').action = baseUrl + '/' + advisor.id;
            document.getElementById('methodField').value = "PUT";

            document.getElementById('name').value = advisor.name;
            document.getElementById('position').value = advisor.position;
            document.getElementById('phone').value = advisor.phone;
            document.getElementById('status').value = advisor.status;
            
            let avatarPath = advisor.avatar_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(advisor.name) + '&background=001144&color=fff';
            document.getElementById('selected_avatar').value = avatarPath;
            document.getElementById('avatar-preview-large').src = avatarPath.startsWith('http') ? avatarPath : "{{ asset('') }}" + avatarPath;

            document.getElementById('modal-advisor').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal-advisor').classList.add('hidden');
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #00114410; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #00114420; }
    </style>
</x-layouts.admin>
