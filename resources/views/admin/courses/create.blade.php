<x-layouts.admin>
    <x-slot name="title">Creador de {{ $viewType ?? 'Cursos' }} - CapacitaPerú</x-slot>
    <x-slot name="header">{{ $course->exists ? 'Perfeccionar ' . ($viewType ?? 'Programa') : 'Nuevo ' . ($viewType ?? 'Programa') }}</x-slot>

    <!-- TOAST NOTIFICATION (Premium) -->
    <div id="toast-notification" class="fixed top-24 right-8 z-[100] transform transition-all duration-700 translate-x-[120%] opacity-0 pointer-events-none">
        <div class="bg-[#001144] text-white px-8 py-5 rounded-[1.5rem] shadow-2xl flex items-center gap-5 border border-white/10 min-w-[350px] backdrop-blur-xl">
            <div class="w-12 h-12 rounded-2xl bg-[#FF7A00] flex items-center justify-center shadow-lg shadow-[#FF7A00]/20 shrink-0">
                <i class="ph-fill ph-check-circle text-2xl text-white"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-black text-[10px] uppercase tracking-[0.2em] text-[#FF7A00]">Éxito</h4>
                <p class="text-xs text-white/80 font-medium mt-0.5" id="toast-message">Cambios sincronizados correctamente.</p>
            </div>
            <button onclick="hideToast()" class="w-8 h-8 rounded-full flex items-center justify-center text-white/30 hover:text-white transition-colors">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>
    </div>

    <!-- FORMULARIO -->
    <form id="courseForm" action="{{ $course->exists ? route(($routeNamePrefix ?? 'admin.courses.') . 'update', $course->id) : route(($routeNamePrefix ?? 'admin.courses.') . 'store') }}" method="POST" enctype="multipart/form-data" class="relative pb-32 max-w-6xl mx-auto">
        @csrf
        @if($course->exists) @method('PUT') @endif

        <input type="hidden" name="type" value="{{ $type ?? 'course' }}">
        <input type="hidden" name="builder_data" id="builder_data">
        
        <!-- NAVEGACIÓN PREMIUM (TABS) -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 mb-8 overflow-hidden">
            <div class="flex overflow-x-auto no-scrollbar p-2">
                <button type="button" onclick="showTab('info')" id="tab-btn-info" 
                        class="flex-1 py-5 px-8 text-center rounded-[2rem] transition-all duration-500 flex items-center justify-center gap-3 whitespace-nowrap min-w-[160px] group">
                    <div class="w-8 h-8 rounded-xl bg-gray-50 text-gray-400 group-hover:bg-[#FF7A00] group-hover:text-white flex items-center justify-center shrink-0 transition-all font-black text-[10px]">01</div>
                    <span class="font-black text-[10px] uppercase tracking-widest text-gray-400 group-hover:text-[#001144]">Información</span>
                </button>
                
                <button type="button" onclick="showTab('attendance')" id="tab-btn-attendance" 
                        class="flex-1 py-5 px-8 text-center rounded-[2rem] transition-all duration-500 flex items-center justify-center gap-3 whitespace-nowrap min-w-[160px] group">
                    <div class="w-8 h-8 rounded-xl bg-gray-50 text-gray-400 group-hover:bg-[#FF7A00] group-hover:text-white flex items-center justify-center shrink-0 transition-all font-black text-[10px]">02</div>
                    <span class="font-black text-[10px] uppercase tracking-widest text-gray-400 group-hover:text-[#001144]">Asistencia</span>
                </button>

                <button type="button" onclick="showTab('builder')" id="tab-btn-builder" 
                        class="flex-1 py-4 px-8 text-center rounded-[2rem] transition-all duration-500 flex items-center justify-center gap-3 whitespace-nowrap min-w-[160px] group">
                    <div class="w-8 h-8 rounded-xl bg-gray-50 text-gray-400 group-hover:bg-[#FF7A00] group-hover:text-white flex items-center justify-center shrink-0 transition-all font-black text-[10px]">03</div>
                    <span class="font-black text-[10px] uppercase tracking-widest text-gray-400 group-hover:text-[#001144]">Módulos</span>
                </button>

                <button type="button" onclick="showTab('evaluation')" id="tab-btn-evaluation" 
                        class="flex-1 py-4 px-8 text-center rounded-[2rem] transition-all duration-500 flex items-center justify-center gap-3 whitespace-nowrap min-w-[160px] group">
                    <div class="w-8 h-8 rounded-xl bg-gray-50 text-gray-400 group-hover:bg-[#FF7A00] group-hover:text-white flex items-center justify-center shrink-0 transition-all font-black text-[10px]">04</div>
                    <span class="font-black text-[10px] uppercase tracking-widest text-gray-400 group-hover:text-[#001144]">Evaluación</span>
                </button>

                <button type="button" onclick="showTab('certificate')" id="tab-btn-certificate" 
                        class="flex-1 py-4 px-8 text-center rounded-[2rem] transition-all duration-500 flex items-center justify-center gap-3 whitespace-nowrap min-w-[160px] group">
                    <div class="w-8 h-8 rounded-xl bg-gray-50 text-gray-400 group-hover:bg-[#FF7A00] group-hover:text-white flex items-center justify-center shrink-0 transition-all font-black text-[10px]">05</div>
                    <span class="font-black text-[10px] uppercase tracking-widest text-gray-400 group-hover:text-[#001144]">Certificado</span>
                </button>
            </div>
        </div>

        <!-- CONTENIDO DE LOS PASOS -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-10 min-h-[600px] fade-in-up">
            <div id="tab-info" class="tab-content animate-fade-in">@include('admin.courses.partials.info')</div>
            <div id="tab-attendance" class="tab-content hidden animate-fade-in">@include('admin.courses.partials.attendance')</div>
            <div id="tab-builder" class="tab-content hidden animate-fade-in">@include('admin.courses.partials.builder')</div>
            <div id="tab-evaluation" class="tab-content hidden animate-fade-in">@include('admin.courses.partials.evaluation')</div>
            
            <div id="tab-certificate" class="tab-content hidden animate-fade-in">
                @if($course->exists && $course->instructor)
                    @include('admin.courses.partials.certificate')
                @else
                    <div class="flex flex-col items-center justify-center py-32">
                        <div class="w-24 h-24 rounded-[2.5rem] bg-orange-50 flex items-center justify-center text-[#FF7A00] mb-8 shadow-inner">
                            <i class="ph-duotone ph-seal-warning text-6xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-[#001144] tracking-tight">Faltan Requisitos</h3>
                        <p class="max-w-md text-center text-sm text-gray-400 mt-4 font-medium leading-relaxed">
                            Para configurar el certificado, primero debes <span class="text-[#001144] font-black">Guardar el curso</span> y asegurarte de asignar un <span class="text-[#FF7A00] font-black">Docente</span> responsable en la pestaña de Información.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- ACCIÓN FLOTANTE -->
        <div class="fixed bottom-8 right-8 z-[60]">
            <button type="button" onclick="submitCourseAll()" 
                    class="group flex items-center gap-4 pl-8 pr-10 py-5 bg-[#001144] text-white rounded-full hover:bg-[#FF7A00] transition-all duration-500 shadow-[0_20px_50px_rgba(0,17,68,0.3)] hover:shadow-[0_20px_50px_rgba(255,122,0,0.3)] transform hover:-translate-y-2">
                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center group-hover:rotate-12 transition-transform">
                    <i class="ph-bold ph-floppy-disk text-xl"></i>
                </div>
                <span class="font-black text-[10px] uppercase tracking-[0.3em]">Guardar Cambios</span>
            </button>
        </div>

    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if(session('success'))
                showToast("{{ session('success') }}");
            @endif
            @if($errors->any())
                showToast("{{ $errors->first() }}", true);
            @endif

            let activeTab = 'info';
            const hash = window.location.hash.substring(1); 
            
            if (hash && document.getElementById('tab-' + hash)) {
                activeTab = hash;
            } else if (localStorage.getItem('activeCourseTab') && document.getElementById('tab-' + localStorage.getItem('activeCourseTab'))) {
                activeTab = localStorage.getItem('activeCourseTab');
            }

            @if(session('success'))
                if (localStorage.getItem('activeCourseTab')) {
                    activeTab = localStorage.getItem('activeCourseTab');
                }
            @endif

            showTab(activeTab, false);
        });

        function showTab(tabId, shouldScroll = true) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            const content = document.getElementById('tab-' + tabId);
            if(content) content.classList.remove('hidden');
            
            document.querySelectorAll('.overflow-x-auto button').forEach(btn => {
                btn.classList.remove('bg-[#001144]', 'shadow-xl', 'shadow-blue-900/10');
                btn.querySelector('div').classList.remove('bg-[#FF7A00]', 'text-white', 'shadow-lg');
                btn.querySelector('div').classList.add('bg-gray-50', 'text-gray-400');
                btn.querySelector('span').classList.remove('text-[#001144]', 'text-white');
                btn.querySelector('span').classList.add('text-gray-400');
            });

            const activeBtn = document.getElementById('tab-btn-' + tabId);
            if(activeBtn) {
                activeBtn.classList.add('bg-[#001144]', 'shadow-xl', 'shadow-blue-900/10');
                activeBtn.querySelector('div').classList.remove('bg-gray-50', 'text-gray-400');
                activeBtn.querySelector('div').classList.add('bg-[#FF7A00]', 'text-white', 'shadow-lg');
                activeBtn.querySelector('span').classList.remove('text-gray-400');
                activeBtn.querySelector('span').classList.add('text-white');
            }

            history.replaceState(null, null, '#' + tabId); 
            localStorage.setItem('activeCourseTab', tabId); 

            if (shouldScroll) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }

        function showToast(message, isError = false) {
            const toast = document.getElementById('toast-notification');
            const msgEl = document.getElementById('toast-message');
            msgEl.innerText = message;
            
            const titleEl = toast.querySelector('h4');
            const iconContainer = toast.querySelector('.bg-\\[\\#FF7A00\\]') || toast.querySelector('.bg-red-500');
            const icon = iconContainer.querySelector('i');
            
            if (isError) {
                titleEl.innerText = "Error";
                titleEl.classList.remove('text-[#FF7A00]');
                titleEl.classList.add('text-red-500');
                iconContainer.classList.remove('bg-[#FF7A00]');
                iconContainer.classList.add('bg-red-500', 'shadow-red-500/20');
                icon.className = "ph-fill ph-x-circle text-2xl text-white";
            } else {
                titleEl.innerText = "Éxito";
                titleEl.classList.remove('text-red-500');
                titleEl.classList.add('text-[#FF7A00]');
                iconContainer.classList.remove('bg-red-500', 'shadow-red-500/20');
                iconContainer.classList.add('bg-[#FF7A00]');
                icon.className = "ph-fill ph-check-circle text-2xl text-white";
            }
            
            toast.classList.remove('translate-x-[120%]', 'opacity-0', 'pointer-events-none');
            setTimeout(() => { hideToast(); }, 5000);
        }

        function hideToast() {
            const toast = document.getElementById('toast-notification');
            toast.classList.add('translate-x-[120%]', 'opacity-0', 'pointer-events-none');
        }

        function submitCourseAll() {
            const form = document.getElementById('courseForm');

            if (!form.checkValidity()) {
                showTab('info');
                setTimeout(() => form.reportValidity(), 100); 
                showToast("Completa los campos obligatorios.");
                return; 
            }

            try {
                if (typeof modules !== 'undefined') {
                    document.getElementById('builder_data').value = JSON.stringify(modules);
                }
            } catch(e) { console.warn('Builder:', e); }
            
            try { if (typeof updateEvaluationInput === 'function') updateEvaluationInput(); } catch(e) {}
            try { if (typeof syncAttendanceInput === 'function') syncAttendanceInput(); } catch(e) {}
            
            try { 
                if (typeof updateCertificateInput === 'function') {
                    updateCertificateInput(); 
                } else if (typeof window.updateCertificateInput === 'function') {
                    window.updateCertificateInput();
                }
            } catch(e) { console.error('Error sync certificado:', e); }

            const currentHash = window.location.hash.substring(1) || 'info';
            localStorage.setItem('activeCourseTab', currentHash);

            form.submit();
        }
    </script>
</x-layouts.admin>