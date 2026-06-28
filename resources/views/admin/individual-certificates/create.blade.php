<x-layouts.admin>
    <x-slot name="title">Certificado Individual - Editor</x-slot>
    <x-slot name="header">{{ $certificate->exists ? 'Editar Certificado' : 'Nuevo Certificado Individual' }}</x-slot>

    <!-- TOAST NOTIFICATION -->
    <div id="toast-notification" class="fixed top-24 right-8 z-[100] transform transition-all duration-700 translate-x-[120%] opacity-0 pointer-events-none">
        <div class="bg-[#001144] text-white px-8 py-5 rounded-[1.5rem] shadow-2xl flex items-center gap-5 border border-white/10 min-w-[350px] backdrop-blur-xl">
            <div class="w-12 h-12 rounded-2xl bg-[#FF7A00] flex items-center justify-center shadow-lg shadow-[#FF7A00]/20 shrink-0">
                <i class="ph-fill ph-check-circle text-2xl text-white"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-black text-[10px] uppercase tracking-[0.2em] text-[#FF7A00]">Éxito</h4>
                <p class="text-xs text-white/80 font-medium mt-0.5" id="toast-message">Cambios sincronizados.</p>
            </div>
            <button onclick="hideToast()" class="w-8 h-8 rounded-full flex items-center justify-center text-white/30 hover:text-white transition-colors">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>
    </div>

    <!-- FORMULARIO -->
    <form id="certForm" action="{{ $certificate->exists ? route('admin.individual-certificates.update', $certificate->id) : route('admin.individual-certificates.store') }}" method="POST" enctype="multipart/form-data" class="relative pb-32 max-w-6xl mx-auto">
        @csrf
        @if($certificate->exists) @method('PUT') @endif
        
        @if($errors->any())
            <div class="mb-8 p-6 bg-red-50 border border-red-100 rounded-[2rem]">
                <div class="flex items-center gap-3 mb-3">
                    <i class="ph-fill ph-warning-circle text-xl text-red-500"></i>
                    <h3 class="text-sm font-black text-red-600 uppercase tracking-widest">Se encontraron errores:</h3>
                </div>
                <ul class="list-disc list-inside text-xs text-red-500 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        
        <input type="hidden" name="students_json" id="students_json" value="{{ old('students_json') }}">
        <input type="hidden" name="active_tab" id="active_tab" value="info">
        
        <!-- NAVEGACIÓN (TABS) -->
        <div class="bg-white rounded-[1.5rem] md:rounded-[2.5rem] shadow-sm border border-gray-100 mb-8 overflow-hidden">
            <div class="flex p-1 md:p-2">
                <button type="button" onclick="showTab('info')" id="tab-btn-info" 
                        class="flex-1 py-3 px-1 md:py-5 md:px-8 text-center rounded-[1rem] md:rounded-[2rem] transition-all duration-500 flex flex-col md:flex-row items-center justify-center gap-1 md:gap-3 group">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-lg md:rounded-xl bg-gray-50 text-gray-400 group-hover:bg-[#FF7A00] group-hover:text-white flex items-center justify-center shrink-0 transition-all font-black text-[9px] md:text-[10px]">01</div>
                    <span class="font-black text-[8px] md:text-[10px] uppercase tracking-wider md:tracking-widest text-gray-400 group-hover:text-[#001144]">
                        <span class="hidden md:inline">Información del Certificado</span>
                        <span class="inline md:hidden">Información</span>
                    </span>
                </button>
                
                <button type="button" onclick="showTab('certificate')" id="tab-btn-certificate" 
                        class="flex-1 py-3 px-1 md:py-5 md:px-8 text-center rounded-[1rem] md:rounded-[2rem] transition-all duration-500 flex flex-col md:flex-row items-center justify-center gap-1 md:gap-3 group">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-lg md:rounded-xl bg-gray-50 text-gray-400 group-hover:bg-[#FF7A00] group-hover:text-white flex items-center justify-center shrink-0 transition-all font-black text-[9px] md:text-[10px]">02</div>
                    <span class="font-black text-[8px] md:text-[10px] uppercase tracking-wider md:tracking-widest text-gray-400 group-hover:text-[#001144]">
                        <span class="hidden md:inline">Diseño del Diploma</span>
                        <span class="inline md:hidden">Diseño</span>
                    </span>
                </button>

                <button type="button" onclick="showTab('students')" id="tab-btn-students" 
                        class="flex-1 py-3 px-1 md:py-5 md:px-8 text-center rounded-[1rem] md:rounded-[2rem] transition-all duration-500 flex flex-col md:flex-row items-center justify-center gap-1 md:gap-3 group">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-lg md:rounded-xl bg-gray-50 text-gray-400 group-hover:bg-[#FF7A00] group-hover:text-white flex items-center justify-center shrink-0 transition-all font-black text-[9px] md:text-[10px]">03</div>
                    <span class="font-black text-[8px] md:text-[10px] uppercase tracking-wider md:tracking-widest text-gray-400 group-hover:text-[#001144]">
                        <span class="hidden md:inline">Gestión de Estudiantes</span>
                        <span class="inline md:hidden">Estudiantes</span>
                    </span>
                </button>
            </div>
        </div>

        <!-- CONTENIDO -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-10 min-h-[600px]">
            <div id="tab-info" class="tab-content">
                @include('admin.individual-certificates.partials.info')
            </div>
            
            <div id="tab-certificate" class="tab-content hidden">
                @if($certificate->exists)
                    @include('admin.individual-certificates.partials.certificate')
                @else
                    <div class="flex flex-col items-center justify-center py-32">
                        <div class="w-24 h-24 rounded-[2.5rem] bg-orange-50 flex items-center justify-center text-[#FF7A00] mb-8 shadow-inner">
                            <i class="ph-duotone ph-seal-warning text-6xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-[#001144] tracking-tight">Faltan Requisitos</h3>
                        <p class="max-w-md text-center text-sm text-gray-400 mt-4 font-medium leading-relaxed">
                            Para configurar el diseño del diploma, primero debes <span class="text-[#001144] font-black">Guardar los datos</span> de la pestaña de información.
                        </p>
                    </div>
                @endif
            </div>

            <div id="tab-students" class="tab-content hidden">
                @include('admin.individual-certificates.partials.students')
            </div>
        </div>

        <!-- ACCIÓN FLOTANTE -->
        <div class="fixed bottom-8 right-8 z-[60]">
            <button type="button" onclick="submitForm()" 
                    class="group flex items-center gap-4 pl-8 pr-10 py-5 bg-[#001144] text-white rounded-full hover:bg-[#FF7A00] transition-all duration-500 shadow-[0_20px_50px_rgba(0,17,68,0.3)] hover:shadow-[0_20px_50px_rgba(255,122,0,0.3)] transform hover:-translate-y-2">
                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center group-hover:rotate-12 transition-transform">
                    <i class="ph-bold ph-floppy-disk text-xl"></i>
                </div>
                <span class="font-black text-[10px] uppercase tracking-[0.3em]">Guardar Certificado</span>
            </button>
        </div>

    </form>

    <!-- MODAL AÑADIR ESTUDIANTE (FUERA DEL FORM PRINCIPAL) -->
    <div id="student-modal" class="fixed inset-0 z-[200] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-[#001144]/60 backdrop-blur-sm" onclick="closeStudentModal()"></div>
            <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden">
                <div class="p-10">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-2xl font-black text-[#001144] tracking-tight">Nuevo Estudiante</h3>
                            <p class="text-xs text-gray-400 font-medium mt-1">Ingresa los datos del beneficiario.</p>
                        </div>
                        <button type="button" onclick="closeStudentModal()" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>
                    <div class="space-y-6">
                        <!-- Nombre -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">Nombre Completo</label>
                            <div class="relative">
                                <i class="ph-bold ph-user absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" id="modal_full_name" placeholder="Ej. Juan Pérez García"
                                       class="w-full pl-12 pr-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#FF7A00] transition-all text-sm font-bold text-[#001144]">
                            </div>
                        </div>

                        <!-- DNI -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">DNI / CI</label>
                            <div class="relative">
                                <i class="ph-bold ph-identification-card absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" id="modal_id_number" placeholder="Ej. 70654321"
                                       class="w-full pl-12 pr-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#FF7A00] transition-all text-sm font-bold text-[#001144]"
                                       onkeydown="if(event.key==='Enter'){event.preventDefault();addStudentToList();}">
                            </div>
                        </div>

                        <!-- Selector Mantener vs Personalizar -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">Datos de Certificado</label>
                            <div class="grid grid-cols-2 gap-3">
                                <button type="button" id="btn-custom-false" onclick="setStudentCustom(false)"
                                        class="py-3 px-4 rounded-2xl text-[10px] font-black uppercase tracking-wider border-2 transition-all flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-copy"></i>
                                    Mantener
                                </button>
                                <button type="button" id="btn-custom-true" onclick="setStudentCustom(true)"
                                        class="py-3 px-4 rounded-2xl text-[10px] font-black uppercase tracking-wider border-2 transition-all flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-sliders"></i>
                                    Personalizar
                                </button>
                            </div>
                            <input type="hidden" id="modal_is_custom" value="0">
                        </div>

                        <!-- Campos personalizados (Ocultos por defecto) -->
                        <div id="custom-fields-wrapper" class="space-y-4 hidden pt-4 border-t border-gray-100">
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Fecha de Inicio -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">Fecha Inicio</label>
                                    <input type="date" id="modal_start_date" 
                                           class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-[#FF7A00] transition-all text-xs font-bold text-[#001144]">
                                </div>
                                <!-- Fecha Fin -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">Fecha Fin</label>
                                    <input type="date" id="modal_end_date" 
                                           class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-[#FF7A00] transition-all text-xs font-bold text-[#001144]">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Carga Horaria -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">Carga Horaria</label>
                                    <input type="number" id="modal_hours" placeholder="Ej: 120"
                                           class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-[#FF7A00] transition-all text-xs font-bold text-[#001144]">
                                </div>
                                <!-- Fecha de Emisión -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">Fecha Emisión</label>
                                    <input type="date" id="modal_issue_date" 
                                           class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-[#FF7A00] transition-all text-xs font-bold text-[#001144]">
                                </div>
                            </div>
                        </div>

                        <button type="button" onclick="addStudentToList()" 
                                class="w-full py-5 bg-[#001144] text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-[#FF7A00] transition-all duration-500 shadow-lg shadow-[#001144]/10 hover:shadow-[#FF7A00]/20">
                            Añadir a la lista
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.currentAdminName = "{{ auth()->user()->name }}";

        // Alternar visualización de personalización
        function setStudentCustom(isCustom) {
            const valInput = document.getElementById('modal_is_custom');
            const wrapper = document.getElementById('custom-fields-wrapper');
            const btnTrue = document.getElementById('btn-custom-true');
            const btnFalse = document.getElementById('btn-custom-false');
            
            if (!valInput || !wrapper || !btnTrue || !btnFalse) return;
            
            if (isCustom) {
                valInput.value = "1";
                wrapper.classList.remove('hidden');
                
                btnTrue.className = "py-3 px-4 rounded-2xl text-[10px] font-black uppercase tracking-wider border-2 transition-all flex items-center justify-center gap-2 border-[#FF7A00] bg-[#FF7A00]/5 text-[#FF7A00]";
                btnFalse.className = "py-3 px-4 rounded-2xl text-[10px] font-black uppercase tracking-wider border-2 transition-all flex items-center justify-center gap-2 border-gray-100 bg-gray-50/50 text-gray-400 hover:border-gray-200 hover:bg-white";
            } else {
                valInput.value = "0";
                wrapper.classList.add('hidden');
                
                btnFalse.className = "py-3 px-4 rounded-2xl text-[10px] font-black uppercase tracking-wider border-2 transition-all flex items-center justify-center gap-2 border-[#001144] bg-[#001144]/5 text-[#001144]";
                btnTrue.className = "py-3 px-4 rounded-2xl text-[10px] font-black uppercase tracking-wider border-2 transition-all flex items-center justify-center gap-2 border-gray-100 bg-gray-50/50 text-gray-400 hover:border-gray-200 hover:bg-white";
            }
        }

        // Funciones del Modal de Estudiantes
        function openStudentModal() {
            const modal = document.getElementById('student-modal');
            if (modal) {
                // Resetear estado por si venimos de una edición
                window.editingStudentIndex = null;
                modal.querySelector('h3').innerText = 'Nuevo Estudiante';
                modal.querySelector('p').innerText = 'Ingresa los datos del beneficiario.';
                modal.querySelector('button[onclick="addStudentToList()"]').innerText = 'Añadir a la lista';
                document.getElementById('modal_full_name').value = '';
                document.getElementById('modal_id_number').value = '';
                
                // Resetear campos personalizados
                document.getElementById('modal_start_date').value = '';
                document.getElementById('modal_end_date').value = '';
                document.getElementById('modal_hours').value = '';
                document.getElementById('modal_issue_date').value = '';
                setStudentCustom(false);

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeStudentModal() {
            const modal = document.getElementById('student-modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                const f = document.getElementById('modal_full_name');
                const i = document.getElementById('modal_id_number');
                if (f) f.value = '';
                if (i) i.value = '';
                window.editingStudentIndex = null;
            }
        }

        function addStudentToList() {
            try {
                console.log("--> Iniciando addStudentToList()");
                const fullName = document.getElementById('modal_full_name')?.value?.trim();
                const idNumber = document.getElementById('modal_id_number')?.value?.trim();
                const isCustom = document.getElementById('modal_is_custom')?.value === "1";
                
                console.log("Datos obtenidos del modal:", { fullName, idNumber, isCustom });

                let startDate = null;
                let endDate = null;
                let hours = null;
                let issueDate = null;
                
                if (isCustom) {
                    startDate = document.getElementById('modal_start_date')?.value || null;
                    endDate = document.getElementById('modal_end_date')?.value || null;
                    hours = document.getElementById('modal_hours')?.value ? parseInt(document.getElementById('modal_hours')?.value, 10) : null;
                    issueDate = document.getElementById('modal_issue_date')?.value || null;
                }

                if (!fullName || !idNumber) {
                    alert('Por favor complete todos los campos principales (Nombre y DNI).');
                    return;
                }

                if (!window.studentsList || !Array.isArray(window.studentsList)) {
                    console.log("window.studentsList no es array. Normalizando...");
                    let rawList = window.studentsList;
                    if (rawList && typeof rawList === 'object') {
                        window.studentsList = Object.values(rawList);
                    } else {
                        window.studentsList = [];
                    }
                }

                const studentData = {
                    fullname: fullName, 
                    dni: idNumber,
                    is_custom: isCustom,
                    start_date: startDate,
                    end_date: endDate,
                    hours: hours,
                    issue_date: issueDate,
                    created_at: (window.editingStudentIndex !== null && window.editingStudentIndex !== undefined) 
                        ? (window.studentsList[window.editingStudentIndex].created_at || new Date().toISOString())
                        : new Date().toISOString(),
                    updated_at: new Date().toISOString(),
                    user: { name: window.currentAdminName }
                };
                console.log("Objeto estudiante creado:", studentData);

                if (window.editingStudentIndex !== null && window.editingStudentIndex !== undefined) {
                    console.log("Actualizando estudiante existente en index:", window.editingStudentIndex);
                    // Actualizar existente preservando su ID y UUID original si los tiene
                    window.studentsList[window.editingStudentIndex] = { 
                        ...window.studentsList[window.editingStudentIndex],
                        ...studentData
                    };
                } else {
                    console.log("Añadiendo nuevo estudiante a la lista");
                    // Añadir nuevo
                    window.studentsList.push(studentData);
                }

                console.log("Lista actualizada:", window.studentsList);

                closeStudentModal();

                if (typeof renderStudentsTable === 'function') {
                    console.log("Llamando a renderStudentsTable()...");
                    renderStudentsTable();
                } else {
                    console.error("renderStudentsTable NO está definida.");
                }
            } catch (e) {
                console.error("Error en addStudentToList:", e);
                alert("Error crítico al añadir estudiante: " + e.message);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            @if(session('success'))
                showToast("{{ session('success') }}");
            @endif

            let activeTab = 'info';
            const hash = window.location.hash.substring(1); 
            if (hash && document.getElementById('tab-' + hash)) {
                activeTab = hash;
            }
            showTab(activeTab, false);
        });

        function showTab(tabId, shouldScroll = true) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById('tab-' + tabId).classList.remove('hidden');
            
            document.querySelectorAll('[id^="tab-btn-"]').forEach(btn => {
                btn.classList.remove('bg-[#001144]', 'text-white');
                btn.querySelector('div').classList.add('bg-gray-50', 'text-gray-400');
                btn.querySelector('div').classList.remove('bg-[#FF7A00]', 'text-white');
                btn.querySelector('span').classList.add('text-gray-400');
                btn.querySelector('span').classList.remove('text-white');
            });

            const activeBtn = document.getElementById('tab-btn-' + tabId);
            activeBtn.classList.add('bg-[#001144]');
            activeBtn.querySelector('div').classList.remove('bg-gray-50', 'text-gray-400');
            activeBtn.querySelector('div').classList.add('bg-[#FF7A00]', 'text-white');
            activeBtn.querySelector('span').classList.remove('text-gray-400');
            activeBtn.querySelector('span').classList.add('text-white');

            document.getElementById('active_tab').value = tabId;
            window.location.hash = tabId;
            if (shouldScroll) window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function showToast(message) {
            const toast = document.getElementById('toast-notification');
            document.getElementById('toast-message').innerText = message;
            toast.classList.remove('translate-x-[120%]', 'opacity-0', 'pointer-events-none');
            setTimeout(hideToast, 5000);
        }

        function hideToast() {
            document.getElementById('toast-notification').classList.add('translate-x-[120%]', 'opacity-0', 'pointer-events-none');
        }

        function submitForm() {
            const form = document.getElementById('certForm');
            if (!form.checkValidity()) {
                showTab('info');
                setTimeout(() => form.reportValidity(), 100);
                return;
            }
            
            try { 
                if (typeof window.updateCertificateInput === 'function') {
                    window.updateCertificateInput();
                }
                if (typeof window.syncStudentsJSON === 'function') {
                    console.log('Sincronizando lista de estudiantes...');
                    window.syncStudentsJSON();
                }
            } catch(e) { console.error('Error sync form data:', e); }

            console.log('Enviando formulario...');
            form.submit();
        }
    </script>
</x-layouts.admin>
