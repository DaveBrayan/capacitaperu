<x-layouts.admin>
    <x-slot name="title">Certificado de Curso - Editor</x-slot>
    <x-slot name="header">{{ $certificate->exists ? 'Editar Certificado' : 'Nuevo Certificado de Curso/Diplomado' }}</x-slot>

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
    <form id="certForm" action="{{ $certificate->exists ? route('admin.course-certificates.update', $certificate->id) : route('admin.course-certificates.store') }}" method="POST" enctype="multipart/form-data" class="relative pb-32 max-w-6xl mx-auto">
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
        
        <input type="hidden" name="active_tab" id="active_tab" value="info">
        
        <!-- NAVEGACIÓN (TABS) -->
        <div class="bg-white rounded-[1.5rem] md:rounded-[2.5rem] shadow-sm border border-gray-100 mb-8 overflow-hidden">
            <div class="flex p-1 md:p-2">
                <button type="button" onclick="showTab('info')" id="tab-btn-info" 
                        class="flex-1 py-3 px-1 md:py-5 md:px-8 text-center rounded-[1rem] md:rounded-[2rem] transition-all duration-500 flex flex-col md:flex-row items-center justify-center gap-1 md:gap-3 group">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-lg md:rounded-xl bg-gray-50 text-gray-400 group-hover:bg-[#FF7A00] group-hover:text-white flex items-center justify-center shrink-0 transition-all font-black text-[9px] md:text-[10px]">01</div>
                    <span class="font-black text-[8px] md:text-[10px] uppercase tracking-wider md:tracking-widest text-gray-400 group-hover:text-[#001144]">
                        Vinculación del Certificado
                    </span>
                </button>
                
                <button type="button" onclick="showTab('certificate')" id="tab-btn-certificate" 
                        class="flex-1 py-3 px-1 md:py-5 md:px-8 text-center rounded-[1rem] md:rounded-[2rem] transition-all duration-500 flex flex-col md:flex-row items-center justify-center gap-1 md:gap-3 group">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-lg md:rounded-xl bg-gray-50 text-gray-400 group-hover:bg-[#FF7A00] group-hover:text-white flex items-center justify-center shrink-0 transition-all font-black text-[9px] md:text-[10px]">02</div>
                    <span class="font-black text-[8px] md:text-[10px] uppercase tracking-wider md:tracking-widest text-gray-400 group-hover:text-[#001144]">
                        Diseño del Diploma
                    </span>
                </button>
            </div>
        </div>

        <!-- CONTENIDO -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-10 min-h-[600px]">
            <div id="tab-info" class="tab-content">
                @include('admin.course-certificates.partials.info')
            </div>
            
            <div id="tab-certificate" class="tab-content hidden">
                @if($certificate->exists)
                    @include('admin.course-certificates.partials.certificate')
                @else
                    <div class="flex flex-col items-center justify-center py-32">
                        <div class="w-24 h-24 rounded-[2.5rem] bg-orange-50 flex items-center justify-center text-[#FF7A00] mb-8 shadow-inner">
                            <i class="ph-duotone ph-seal-warning text-6xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-[#001144] tracking-tight">Faltan Requisitos</h3>
                        <p class="max-w-md text-center text-sm text-gray-400 mt-4 font-medium leading-relaxed">
                            Para configurar el diseño del diploma, primero debes <span class="text-[#001144] font-black">Guardar los datos</span> de la pestaña de vinculación e información.
                        </p>
                    </div>
                @endif
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

    <script>
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
            } catch(e) { console.error('Error sync form data:', e); }

            console.log('Enviando formulario...');
            form.submit();
        }
    </script>
</x-layouts.admin>
