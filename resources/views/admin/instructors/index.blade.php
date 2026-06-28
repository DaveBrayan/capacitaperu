<x-layouts.admin>
    <x-slot name="title">Gestión de Docentes</x-slot>
    <x-slot name="header">Docentes</x-slot>

    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-10 fade-in-up">
        <div class="relative w-full md:w-96 group">
            <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00] transition-colors"></i>
            <input type="text" placeholder="Buscar docente por nombre o especialidad..." 
                   class="w-full pl-12 pr-4 py-4 rounded-[1.2rem] border border-gray-100 bg-white shadow-sm focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none text-sm transition-all font-medium">
        </div>
        
        @if(Auth::user()->hasPermission('instructors.manage'))
        <button onclick="openModal()" class="w-full md:w-auto flex items-center justify-center gap-3 px-8 py-4 bg-[#001144] text-white rounded-[1.2rem] hover:bg-[#001144]/90 transition-all shadow-xl shadow-blue-900/20 text-sm font-black uppercase tracking-widest group">
            <i class="ph-bold ph-plus text-lg group-hover:rotate-90 transition-transform"></i> Nuevo Docente
        </button>
        @endif
    </div>

    <!-- Lista de Docentes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 fade-in-up" style="animation-delay: 0.1s">
        @forelse($instructors as $instructor)
        <div class="bg-white rounded-[2.5rem] border border-gray-100 p-8 hover:shadow-2xl hover:shadow-gray-200/50 transition-all group relative flex flex-col h-full border-b-4 border-b-transparent hover:border-b-[#FF7A00]">
            
            <!-- Acciones Flotantes -->
            @if(Auth::user()->hasPermission('instructors.manage'))
            <div class="absolute top-6 right-6 flex gap-2 opacity-0 group-hover:opacity-100 transition-all transform translate-y-2 group-hover:translate-y-0 z-10">
                <button onclick="editModal({{ json_encode($instructor) }})" class="w-10 h-10 bg-white/90 backdrop-blur rounded-xl text-[#001144] hover:bg-[#001144] hover:text-white shadow-lg border border-gray-100 flex items-center justify-center transition-all">
                    <i class="ph-bold ph-pencil-simple"></i>
                </button>
                <form action="{{ route('admin.instructors.destroy', $instructor->id) }}" method="POST" onsubmit="return confirm('¿Eliminar docente?');" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-10 h-10 bg-white/90 backdrop-blur rounded-xl text-red-500 hover:bg-red-500 hover:text-white shadow-lg border border-gray-100 flex items-center justify-center transition-all">
                        <i class="ph-bold ph-trash"></i>
                    </button>
                </form>
            </div>
            @endif

            <div class="flex flex-col items-center text-center mb-6">
                <div class="relative mb-6">
                    <div class="w-32 h-32 rounded-[2.5rem] p-1 bg-gradient-to-tr from-[#001144] to-[#FF7A00] rotate-3 group-hover:rotate-0 transition-transform">
                        <img src="{{ $instructor->avatar_url ? asset($instructor->avatar_url) : 'https://ui-avatars.com/api/?name='.urlencode($instructor->name).'&background=001144&color=fff&size=128' }}" 
                             class="w-full h-full rounded-[2.2rem] object-cover border-4 border-white bg-white -rotate-3 group-hover:rotate-0 transition-transform">
                    </div>
                    <span class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full border-4 border-white {{ $instructor->status === 'active' ? 'bg-green-500' : 'bg-gray-400' }} shadow-sm"></span>
                </div>
                
                <h3 class="font-black text-[#001144] text-xl tracking-tight mb-1 group-hover:text-[#FF7A00] transition-colors leading-tight">{{ $instructor->name }}</h3>
                <p class="text-[#FF7A00] text-[10px] font-black uppercase tracking-widest mb-4 bg-orange-50 px-3 py-1 rounded-full">{{ $instructor->specialty ?? 'Docente General' }}</p>
                
                <div class="flex flex-col items-center gap-2 text-xs text-gray-500 mb-6 font-medium">
                    <span class="flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-xl border border-gray-100">
                        <i class="ph-bold ph-envelope text-[#001144]"></i> {{ $instructor->email }}
                    </span>
                </div>

                @if($instructor->signature_url)
                    <div class="mb-6 px-4 py-2 bg-green-50 text-green-700 text-[10px] font-black uppercase tracking-widest rounded-xl border border-green-100 flex items-center gap-2">
                        <i class="ph-bold ph-signature text-sm"></i> Firma Digital Activa
                    </div>
                @endif

                <p class="text-sm text-gray-500 line-clamp-3 leading-relaxed px-2 font-medium italic">"{{ $instructor->bio ?? 'Sin biografía disponible actualmente.' }}"</p>
            </div>
            
            <div class="mt-auto pt-6 border-t border-gray-50 flex justify-between items-center">
                <div class="flex items-center gap-2 bg-[#001144]/5 px-4 py-2 rounded-xl">
                    <i class="ph-bold ph-chalkboard-teacher text-[#001144]"></i>
                    <span class="text-[11px] font-black text-[#001144] uppercase tracking-wider">{{ $instructor->courses_count ?? 0 }} Cursos</span>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[9px] text-gray-400 font-black uppercase tracking-widest">Miembro desde</span>
                    <span class="text-xs font-bold text-[#001144]">{{ $instructor->created_at->format('d M, Y') }}</span>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-24 bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
            <div class="w-24 h-24 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-inner">
                <i class="ph-duotone ph-users-three text-5xl text-gray-300"></i>
            </div>
            <h3 class="text-2xl font-black text-[#001144] mb-2 tracking-tight">No hay docentes registrados</h3>
            <p class="text-gray-400 mb-8 max-w-sm mx-auto font-medium">Comienza agregando al equipo docente para asignarles cursos y emitir certificados.</p>
            
            @if(Auth::user()->hasPermission('instructors.manage'))
            <button onclick="openModal()" class="px-8 py-4 bg-[#001144] text-white rounded-[1.2rem] font-black uppercase tracking-widest hover:bg-[#FF7A00] transition-all shadow-xl shadow-blue-900/20 text-sm">
                Registrar Primer Docente
            </button>
            @endif
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    <div class="mt-12">
        {{ $instructors->links() }}
    </div>

    <!-- MODAL (CREAR / EDITAR) -->
    <div id="instructor-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-[#001144]/40 backdrop-blur-md transition-opacity" onclick="closeModal()"></div>
        
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-white/20">
                    
                    <div class="bg-white px-8 py-6 border-b border-gray-50 flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-[#FF7A00]">
                                <i class="ph-fill ph-user-circle-plus text-2xl" id="modal-icon"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-[#001144] tracking-tight" id="modal-title">Nuevo Docente</h3>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Información Profesional</p>
                            </div>
                        </div>
                        <button onclick="closeModal()" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors rounded-full hover:bg-red-50">
                            <i class="ph-bold ph-x text-xl"></i>
                        </button>
                    </div>

                    <form id="instructor-form" method="POST" enctype="multipart/form-data" action="{{ route('admin.instructors.store') }}">
                        @csrf
                        <input type="hidden" name="_method" id="form-method" value="POST">
                        
                        <div class="p-8 space-y-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
                            
                            <!-- Sección Personal -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nombre Completo <span class="text-[#FF7A00]">*</span></label>
                                    <input type="text" name="name" id="input_name" required 
                                           class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none transition-all text-sm font-bold text-[#001144]"
                                           placeholder="Ej: Reynaldo Sirpa">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Correo Electrónico <span class="text-[#FF7A00]">*</span></label>
                                    <input type="email" name="email" id="input_email" required 
                                           class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none transition-all text-sm font-bold text-[#001144]"
                                           placeholder="correo@ejemplo.com">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Contraseña <span id="pass-required" class="text-[#FF7A00]">*</span></label>
                                    <input type="password" name="password" id="input_password" 
                                           class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none transition-all text-sm font-bold text-[#001144]"
                                           placeholder="Mínimo 8 caracteres">
                                    <p class="text-[9px] text-gray-400 mt-2 font-bold uppercase hidden" id="pass-hint">Omitir para mantener actual</p>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Teléfono de Contacto</label>
                                    <input type="text" name="phone" id="input_phone" 
                                           class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none transition-all text-sm font-bold text-[#001144]"
                                           placeholder="+51 999 999 999">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Especialidad Profesional</label>
                                    <input type="text" name="specialty" id="input_specialty" placeholder="Ej: Especialista en Seguridad Minera"
                                           class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none transition-all text-sm font-bold text-[#001144]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Biografía Profesional</label>
                                <textarea name="bio" id="input_bio" rows="4"
                                          class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none transition-all text-sm font-medium text-[#001144] resize-none"
                                          placeholder="Resumen de trayectoria y experiencia docente..."></textarea>
                            </div>

                            <!-- Sección Certificados -->
                            <div class="bg-blue-50/50 rounded-[2rem] p-8 border border-blue-100/50 relative overflow-hidden group">
                                <div class="absolute top-0 right-0 p-4 opacity-10">
                                    <i class="ph-fill ph-certificate text-6xl text-[#001144]"></i>
                                </div>
                                <h4 class="text-sm font-black text-[#001144] mb-6 flex items-center gap-3 uppercase tracking-widest">
                                    <i class="ph-bold ph-seal-check text-[#FF7A00] text-xl"></i> Credenciales para Certificados
                                </h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="col-span-2">
                                        <label class="block text-[10px] font-black text-blue-400 uppercase tracking-widest mb-2">Título y Nombre de Firma</label>
                                        <input type="text" name="certificate_name" id="input_certificate_name" placeholder="Ej: Ing. Reynaldo Sirpa"
                                               class="w-full px-5 py-4 rounded-[1.2rem] border border-blue-100 bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] shadow-sm">
                                        <p class="text-[9px] text-blue-400 mt-2 font-bold uppercase">Este texto aparecerá debajo de la firma en los certificados.</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-[10px] font-black text-blue-400 uppercase tracking-widest mb-2">Cargar Firma (PNG)</label>
                                        <div class="relative group">
                                            <input type="file" name="signature" id="input_signature" accept="image/png" onchange="previewFileSignature(this)" 
                                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                            <div class="w-full px-5 py-4 rounded-[1.2rem] border border-dashed border-blue-200 bg-white flex items-center justify-center gap-3 group-hover:border-[#FF7A00] transition-colors">
                                                <i class="ph-bold ph-cloud-arrow-up text-blue-400 group-hover:text-[#FF7A00]"></i>
                                                <span class="text-xs font-bold text-blue-400 group-hover:text-[#FF7A00]">Seleccionar Archivo</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-black text-blue-400 uppercase tracking-widest mb-2">Previsualización</label>
                                        <div class="h-16 border border-gray-100 rounded-2xl flex items-center justify-center bg-white overflow-hidden relative shadow-sm">
                                            <img id="signature-preview" src="" class="h-full w-full object-contain hidden p-2">
                                            <div id="signature-placeholder" class="flex flex-col items-center gap-1">
                                                <i class="ph-bold ph-pen-nib text-gray-200 text-xl"></i>
                                                <span class="text-[9px] text-gray-300 font-black uppercase tracking-widest">Sin Firma</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Foto y Estado -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Fotografía de Perfil</label>
                                    <div class="relative group">
                                        <input type="file" name="avatar" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 flex items-center justify-center gap-3 group-hover:bg-white group-hover:border-[#FF7A00] transition-all">
                                            <i class="ph-bold ph-camera text-gray-400 group-hover:text-[#FF7A00]"></i>
                                            <span class="text-xs font-bold text-gray-400 group-hover:text-[#FF7A00]">Cambiar Foto</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Estado del Docente</label>
                                    <select name="status" id="input_status" class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] appearance-none cursor-pointer">
                                        <option value="active">Activo</option>
                                        <option value="inactive">Inactivo</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="bg-gray-50/50 px-8 py-6 flex justify-end gap-4 border-t border-gray-50 rounded-b-[2.5rem]">
                            <button type="button" onclick="closeModal()" class="px-8 py-4 bg-white border border-gray-100 text-[#001144] rounded-[1.2rem] hover:bg-gray-50 text-[10px] font-black uppercase tracking-widest transition-all">Cancelar</button>
                            <button type="submit" class="px-10 py-4 bg-[#001144] text-white rounded-[1.2rem] hover:bg-[#FF7A00] text-[10px] font-black uppercase tracking-widest shadow-xl shadow-blue-900/10 transition-all transform hover:-translate-y-1">Guardar Docente</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('instructor-modal');
        const form = document.getElementById('instructor-form');
        const title = document.getElementById('modal-title');
        const methodInput = document.getElementById('form-method');
        
        const passHint = document.getElementById('pass-hint');
        const passReq = document.getElementById('pass-required');
        const inputPass = document.getElementById('input_password');

        function openModal() {
            form.action = "{{ route('admin.instructors.store') }}";
            methodInput.value = "POST";
            title.innerText = "Nuevo Docente";
            form.reset();
            
            passHint.classList.add('hidden');
            passReq.classList.remove('hidden');
            inputPass.required = true;
            
            const sigPreview = document.getElementById('signature-preview');
            const sigPlace = document.getElementById('signature-placeholder');
            sigPreview.src = "";
            sigPreview.classList.add('hidden');
            sigPlace.classList.remove('hidden');
            
            modal.classList.remove('hidden');
        }

        function editModal(instructor) {
            let url = "{{ route('admin.instructors.update', ':id') }}";
            form.action = url.replace(':id', instructor.id);
            methodInput.value = "PUT";
            title.innerText = "Editar Docente";
            
            form.reset();

            document.getElementById('input_name').value = instructor.name;
            document.getElementById('input_email').value = instructor.email;
            document.getElementById('input_phone').value = instructor.phone || '';
            document.getElementById('input_specialty').value = instructor.specialty || '';
            document.getElementById('input_bio').value = instructor.bio || '';
            document.getElementById('input_status').value = instructor.status;
            document.getElementById('input_certificate_name').value = instructor.certificate_name || '';
            
            const sigPreview = document.getElementById('signature-preview');
            const sigPlace = document.getElementById('signature-placeholder');

            if (instructor.signature_url) {
                let sigSrc = instructor.signature_url.startsWith('http') 
                             ? instructor.signature_url 
                             : "{{ asset('') }}" + instructor.signature_url;
                
                sigPreview.src = sigSrc;
                sigPreview.classList.remove('hidden');
                sigPlace.classList.add('hidden');
            } else {
                sigPreview.src = "";
                sigPreview.classList.add('hidden');
                sigPlace.classList.remove('hidden');
            }

            passHint.classList.remove('hidden');
            passReq.classList.add('hidden');
            inputPass.required = false;
            
            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
        }

        function previewFileSignature(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('signature-preview');
                    const placeholder = document.getElementById('signature-placeholder');
                    
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #00114410; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #00114420; }
    </style>
</x-layouts.admin>