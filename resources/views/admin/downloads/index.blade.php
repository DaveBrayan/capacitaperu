<x-layouts.admin>
    <x-slot name="title">Centro de Descargas - CapacitaPerú</x-slot>
    <x-slot name="header">Centro de Descargas</x-slot>

    <!-- Librerías para Renderizado de Certificados y Compresión ZIP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <div class="space-y-8">
        <!-- HEADER HERO -->
        <div class="bg-gradient-to-r from-[#001144] via-[#001866] to-[#001144] rounded-[2.5rem] p-8 md:p-10 text-white shadow-xl shadow-[#001144]/15 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-[#FF7A00]/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-20 top-0 w-48 h-48 bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md text-white/90 text-[10px] font-black uppercase tracking-widest mb-4 border border-white/10">
                        <i class="ph-bold ph-download-simple text-[#FF7A00]"></i> Exportación Masiva de Certificados
                    </span>
                    <h1 class="text-3xl md:text-4xl font-black tracking-tight text-white mb-2">
                        Centro de Descargas
                    </h1>
                    <p class="text-white/70 text-sm md:text-base font-medium leading-relaxed">
                        Busca a cualquier alumno por su número de DNI y descarga todos sus certificados encontrados en un único archivo comprimido ZIP (PDFs separados) o combinados en un solo documento PDF.
                    </p>
                </div>

                <button type="button" onclick="openDownloadModal()" class="shrink-0 flex items-center gap-3 px-8 py-4 bg-[#FF7A00] hover:bg-[#e66e00] text-white rounded-[1.2rem] font-black text-sm uppercase tracking-wider transition-all shadow-xl shadow-[#FF7A00]/30 hover:scale-102 active:scale-98">
                    <i class="ph-bold ph-magnifying-glass text-lg"></i>
                    Buscar por DNI
                </button>
            </div>
        </div>

        <!-- TARJETAS DE ESTADÍSTICAS -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="p-6 bg-white rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-orange-50 text-[#FF7A00] flex items-center justify-center text-2xl shrink-0">
                    <i class="ph-bold ph-arrow-down-tray"></i>
                </div>
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-gray-400 block mb-0.5">Total Descargas</span>
                    <h3 class="text-2xl font-black text-[#001144] tracking-tight">{{ number_format($totalDownloads) }}</h3>
                </div>
            </div>

            <div class="p-6 bg-white rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl shrink-0">
                    <i class="ph-bold ph-certificate"></i>
                </div>
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-gray-400 block mb-0.5">Certificados Exportados</span>
                    <h3 class="text-2xl font-black text-[#001144] tracking-tight">{{ number_format($totalCertificates) }}</h3>
                </div>
            </div>

            <div class="p-6 bg-white rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl shrink-0">
                    <i class="ph-bold ph-users-three"></i>
                </div>
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-gray-400 block mb-0.5">Alumnos Atendidos</span>
                    <h3 class="text-2xl font-black text-[#001144] tracking-tight">{{ number_format($totalStudents) }}</h3>
                </div>
            </div>
        </div>

        <!-- BUSCADOR RÁPIDO HERO CARD -->
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-6 md:p-8">
            <div class="flex flex-col md:flex-row items-center gap-4">
                <div class="relative flex-1 w-full">
                    <i class="ph-bold ph-identification-card absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                    <input type="text" 
                           id="quick-dni-input" 
                           placeholder="Escribe el DNI del alumno y presiona enter para buscar..." 
                           class="w-full pl-12 pr-4 py-4 rounded-[1.2rem] border border-gray-100 focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/5 outline-none font-bold text-sm text-[#001144] placeholder:font-medium placeholder:text-gray-400"
                           onkeypress="if(event.key === 'Enter') { openDownloadModal(this.value); }">
                </div>
                <button type="button" 
                        onclick="openDownloadModal(document.getElementById('quick-dni-input').value)" 
                        class="w-full md:w-auto px-8 py-4 bg-[#001144] hover:bg-[#001c66] text-white rounded-[1.2rem] font-black text-sm uppercase tracking-wider transition-all flex items-center justify-center gap-2 shadow-lg shadow-[#001144]/15">
                    <i class="ph-bold ph-magnifying-glass text-lg"></i>
                    Buscar Certificados
                </button>
            </div>
        </div>

        <!-- TABLA DE HISTORIAL DE DESCARGAS -->
        <div class="bg-white rounded-[2.5rem] border border-gray-50 shadow-xl shadow-gray-100/50 overflow-hidden">
            <div class="p-6 md:p-8 border-b border-gray-50 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black text-[#001144]">Historial de Exportaciones</h3>
                    <p class="text-xs text-gray-400 font-medium">Registro de descargas masivas realizadas por el equipo</p>
                </div>
                <span class="px-3.5 py-1.5 rounded-full bg-blue-50 text-[#001144] text-[10px] font-black uppercase tracking-wider border border-blue-100">
                    {{ $downloadLogs->total() }} Registros
                </span>
            </div>

            <div class="overflow-x-auto no-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/40 text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] border-b border-gray-50">
                            <th class="px-8 py-5">Fecha y Hora</th>
                            <th class="px-8 py-5">Estudiante</th>
                            <th class="px-8 py-5">DNI</th>
                            <th class="px-8 py-5 text-center">Formato</th>
                            <th class="px-8 py-5 text-center">Certificados</th>
                            <th class="px-8 py-5">Generado Por</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-50">
                        @forelse($downloadLogs as $log)
                        <tr class="hover:bg-gray-50/40 transition-colors">
                            <td class="px-8 py-5 font-bold text-gray-500 text-xs">
                                {{ $log->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-8 py-5 font-black text-[#001144]">
                                {{ $log->student_name }}
                            </td>
                            <td class="px-8 py-5 font-bold text-gray-600 text-xs font-mono">
                                {{ $log->student_dni }}
                            </td>
                            <td class="px-8 py-5 text-center">
                                @if($log->format === 'zip')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-100">
                                        <i class="ph-bold ph-file-zip"></i> Archivo ZIP
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-orange-50 text-[#FF7A00] border border-orange-100">
                                        <i class="ph-bold ph-file-pdf"></i> PDF Unificado
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-center font-black text-[#001144]">
                                <span class="px-3 py-1 rounded-xl bg-gray-100 text-xs font-bold">{{ $log->certificates_count }} certs</span>
                            </td>
                            <td class="px-8 py-5 text-xs text-gray-500 font-bold">
                                {{ $log->user->name ?? 'Administrador' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-3">
                                        <i class="ph-bold ph-tray text-3xl text-gray-300"></i>
                                    </div>
                                    <p class="font-bold text-gray-500">Aún no hay descargas registradas</p>
                                    <p class="text-xs text-gray-400 mt-1">Usa el botón de arriba para buscar y descargar los primeros certificados.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($downloadLogs->hasPages())
            <div class="px-8 py-6 bg-gray-50/30 border-t border-gray-50">
                {{ $downloadLogs->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL DE BÚSQUEDA Y DESCARGA DE CERTIFICADOS -->
    <!-- ======================================================== -->
    <div id="downloadModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-[#001144]/80 backdrop-blur-md transition-opacity duration-300" onclick="closeDownloadModal()"></div>

        <!-- Panel -->
        <div class="relative w-full max-w-4xl bg-white rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh] z-10 animate-scale-up border border-white/20">
            <!-- Modal Header -->
            <div class="p-6 md:p-8 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#001144] flex items-center justify-center text-2xl shadow-sm border border-blue-100">
                        <i class="ph-bold ph-download-simple"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-[#001144] tracking-tight">Centro de Descarga de Certificados</h3>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Búsqueda por DNI y Exportación Masiva</p>
                    </div>
                </div>
                <button type="button" onclick="closeDownloadModal()" class="w-10 h-10 rounded-full bg-white shadow-sm text-gray-400 hover:text-gray-600 flex items-center justify-center border border-gray-100 transition-colors">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 md:p-8 overflow-y-auto no-scrollbar space-y-6 flex-1">
                <!-- Buscador de DNI -->
                <div class="flex gap-3">
                    <div class="relative flex-1">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" 
                               id="modal-dni-input" 
                               placeholder="Ingresa el DNI del alumno..." 
                               class="w-full pl-12 pr-4 py-3.5 rounded-[1.2rem] border border-gray-200 focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/5 outline-none font-bold text-sm text-[#001144]"
                               onkeypress="if(event.key === 'Enter') { searchCertificates(); }">
                    </div>
                    <button type="button" 
                            id="modal-search-btn" 
                            onclick="searchCertificates()" 
                            class="px-6 py-3.5 bg-[#001144] hover:bg-[#002266] text-white rounded-[1.2rem] font-black text-xs uppercase tracking-wider transition-all flex items-center gap-2">
                        <i class="ph-bold ph-magnifying-glass"></i>
                        <span>Buscar</span>
                    </button>
                </div>

                <!-- Estado de Carga -->
                <div id="modal-loading" class="hidden py-12 flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 border-4 border-[#FF7A00]/20 border-t-[#FF7A00] rounded-full animate-spin mb-4"></div>
                    <p class="text-sm font-bold text-[#001144]">Buscando certificados asociados al DNI...</p>
                    <p class="text-xs text-gray-400">Consultando base de datos académica</p>
                </div>

                <!-- Perfil del Estudiante Encontrado -->
                <div id="student-info-card" class="hidden p-5 rounded-2xl bg-gradient-to-r from-blue-50/70 to-indigo-50/70 border border-blue-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#001144] text-white flex items-center justify-center text-xl font-black shadow-md shadow-[#001144]/10" id="student-avatar-initials">
                            E
                        </div>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-blue-600 block">Estudiante Encontrado</span>
                            <h4 class="text-base font-black text-[#001144] leading-tight" id="student-name-display">-</h4>
                            <p class="text-xs font-mono text-gray-500 font-bold mt-0.5">DNI: <span id="student-dni-display">-</span></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                        <span class="px-4 py-2 rounded-xl bg-white border border-blue-100 text-[#001144] text-xs font-black shadow-sm" id="certs-count-badge">
                            0 Certificados
                        </span>
                    </div>
                </div>

                <!-- Lista de Certificados -->
                <div id="certificates-section" class="hidden space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" id="select-all-certs" onchange="toggleSelectAll(this)" checked class="w-4 h-4 rounded text-[#FF7A00] focus:ring-[#FF7A00]">
                            <span class="text-xs font-black uppercase tracking-wider text-gray-500">Seleccionar Todos</span>
                        </label>
                        <span class="text-xs font-bold text-gray-400" id="selected-summary">0 seleccionados</span>
                    </div>

                    <div id="certificates-list-container" class="space-y-2.5 max-h-[300px] overflow-y-auto pr-1">
                        <!-- Inyección JS -->
                    </div>
                </div>

                <!-- Configuración de Descarga: Firmas y Sellos -->
                <div id="export-options-section" class="hidden">
                    <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-orange-50/70 via-amber-50/40 to-white border border-[#FF7A00]/20 flex items-center justify-between gap-4 shadow-sm">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-2xl bg-[#FF7A00] text-white flex items-center justify-center text-xl shrink-0 shadow-md shadow-[#FF7A00]/20">
                                <i class="ph-bold ph-seal-check"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <label for="toggle-signatures" class="text-xs sm:text-sm font-black text-[#001144] block cursor-pointer select-none">
                                        Incluir firmas y sellos
                                    </label>
                                    <span id="signatures-badge" class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-700">
                                        Incluido
                                    </span>
                                </div>
                                <p class="text-[11px] text-gray-500 font-medium mt-0.5 leading-snug">
                                    Se respetará la posición y tamaño exactos configurados para cada firma y sello en la plantilla.
                                </p>
                            </div>
                        </div>

                        <!-- Toggle Switch -->
                        <div class="flex items-center shrink-0">
                            <label class="relative inline-flex items-center cursor-pointer select-none">
                                <input type="checkbox" id="toggle-signatures" checked class="sr-only peer" onchange="updateSignaturesBadge(this)">
                                <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-6 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#FF7A00]"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Empty State (No se encontraron certificados) -->
                <div id="modal-empty-state" class="hidden py-10 flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center text-3xl text-gray-300 mb-3">
                        <i class="ph-bold ph-folder-dashed"></i>
                    </div>
                    <h4 class="text-base font-black text-gray-600 mb-1">No se encontraron certificados</h4>
                    <p class="text-xs text-gray-400 max-w-sm">No hay registros de certificados individuales o de cursos vinculados al DNI ingresado.</p>
                </div>

                <!-- Barra de Progreso durante la Descarga -->
                <div id="download-progress-container" class="hidden p-5 rounded-2xl bg-gray-50 border border-gray-100 space-y-3 animate-fade-in">
                    <div class="flex justify-between items-center text-xs font-bold">
                        <span class="text-[#001144] flex items-center gap-2" id="download-progress-status">
                            <i class="ph-bold ph-spinner animate-spin text-[#FF7A00]"></i> Procesando...
                        </span>
                        <span class="text-gray-500 font-mono" id="download-progress-percent">0%</span>
                    </div>
                    <div class="w-full h-2.5 bg-gray-200 rounded-full overflow-hidden">
                        <div id="download-progress-bar" class="h-full bg-gradient-to-r from-[#FF7A00] to-orange-500 transition-all duration-300 rounded-full" style="width: 0%"></div>
                    </div>
                    <p class="text-[10px] text-gray-400 italic text-center">Renderizando certificados en alta fidelidad idéntico a la vista original...</p>
                </div>
            </div>

            <!-- Modal Footer Acciones -->
            <div class="p-6 md:p-8 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                <button type="button" onclick="closeDownloadModal()" class="w-full sm:w-auto px-6 py-3.5 text-xs font-bold text-gray-400 hover:text-gray-600 transition-colors uppercase tracking-wider">
                    Cerrar
                </button>

                <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                    <!-- Opción 1: ZIP con PDFs separados -->
                    <button type="button" 
                            id="btn-download-zip" 
                            onclick="startBatchDownload('zip')" 
                            disabled 
                            class="w-full sm:w-auto px-6 py-3.5 rounded-[1.2rem] bg-[#001144] hover:bg-[#002266] text-white text-xs font-black uppercase tracking-wider transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2 shadow-lg shadow-[#001144]/15">
                        <i class="ph-bold ph-file-zip text-base text-[#FF7A00]"></i>
                        <span>Descargar en ZIP (PDFs separados)</span>
                    </button>

                    <!-- Opción 2: Todo en un solo PDF -->
                    <button type="button" 
                            id="btn-download-merged" 
                            onclick="startBatchDownload('merged')" 
                            disabled 
                            class="w-full sm:w-auto px-6 py-3.5 rounded-[1.2rem] bg-[#FF7A00] hover:bg-[#e66e00] text-white text-xs font-black uppercase tracking-wider transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2 shadow-lg shadow-[#FF7A00]/20">
                        <i class="ph-bold ph-file-pdf text-base"></i>
                        <span>Descargar Todo en un Solo PDF</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Iframe invisible para renderizar los certificados idénticos a front.certificate-view -->
    <iframe id="cert-render-iframe" style="position:fixed; top:-9999px; left:-9999px; width:1123px; height:794px; border:none; visibility:hidden;"></iframe>

    <script>
        let currentCertificatesList = [];
        let currentStudentInfo = null;

        function openDownloadModal(initialDni = '') {
            const modal = document.getElementById('downloadModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            const input = document.getElementById('modal-dni-input');
            if (initialDni) {
                input.value = initialDni.trim();
                searchCertificates();
            } else {
                input.focus();
            }
        }

        function closeDownloadModal() {
            const modal = document.getElementById('downloadModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // BÚSQUEDA DE CERTIFICADOS POR DNI
        function searchCertificates() {
            const input = document.getElementById('modal-dni-input');
            const dni = input.value.trim();
            if (!dni) {
                alert('Por favor ingrese un número de DNI.');
                input.focus();
                return;
            }

            const loading = document.getElementById('modal-loading');
            const studentCard = document.getElementById('student-info-card');
            const certsSection = document.getElementById('certificates-section');
            const exportOptions = document.getElementById('export-options-section');
            const emptyState = document.getElementById('modal-empty-state');
            const btnZip = document.getElementById('btn-download-zip');
            const btnMerged = document.getElementById('btn-download-merged');
            const searchBtn = document.getElementById('modal-search-btn');

            loading.classList.remove('hidden');
            loading.classList.add('flex');
            studentCard.classList.add('hidden');
            certsSection.classList.add('hidden');
            if (exportOptions) exportOptions.classList.add('hidden');
            emptyState.classList.add('hidden');
            btnZip.disabled = true;
            btnMerged.disabled = true;
            searchBtn.disabled = true;

            fetch(`{{ route('admin.downloads.search') }}?dni=${encodeURIComponent(dni)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                loading.classList.add('hidden');
                loading.classList.remove('flex');
                searchBtn.disabled = false;

                if (!data.success || !data.certificates || data.certificates.length === 0) {
                    emptyState.classList.remove('hidden');
                    emptyState.classList.add('flex');
                    currentCertificatesList = [];
                    currentStudentInfo = null;
                    return;
                }

                currentCertificatesList = data.certificates;
                currentStudentInfo = data.student;

                // Actualizar info estudiante
                document.getElementById('student-name-display').innerText = data.student.name;
                document.getElementById('student-dni-display').innerText = data.student.dni;
                document.getElementById('student-avatar-initials').innerText = (data.student.name.charAt(0) || 'E').toUpperCase();
                document.getElementById('certs-count-badge').innerText = `${data.count} Certificado${data.count > 1 ? 's' : ''}`;

                studentCard.classList.remove('hidden');
                certsSection.classList.remove('hidden');
                if (exportOptions) exportOptions.classList.remove('hidden');

                renderCertificatesList(data.certificates);
                btnZip.disabled = false;
                btnMerged.disabled = false;
            })
            .catch(err => {
                console.error(err);
                loading.classList.add('hidden');
                loading.classList.remove('flex');
                searchBtn.disabled = false;
                alert('Ocurrió un error al consultar los certificados. Por favor intente de nuevo.');
            });
        }

        // TOGGLE FIRMAS Y SELLOS BADGE
        function updateSignaturesBadge(checkbox) {
            const badge = document.getElementById('signatures-badge');
            if (!badge) return;
            if (checkbox.checked) {
                badge.className = 'px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-700';
                badge.innerText = 'Incluido';
            } else {
                badge.className = 'px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-gray-100 text-gray-500';
                badge.innerText = 'Sin Firmas/Sellos';
            }
        }

        // RENDERIZAR LISTA EN MODAL
        function renderCertificatesList(certs) {
            const container = document.getElementById('certificates-list-container');
            container.innerHTML = certs.map((c, index) => `
                <div class="p-4 rounded-xl border border-gray-100 hover:border-gray-200 bg-white hover:bg-gray-50/50 transition-all flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <input type="checkbox" 
                               value="${c.id}" 
                               class="cert-item-checkbox w-4 h-4 rounded text-[#FF7A00] focus:ring-[#FF7A00]" 
                               checked 
                               onchange="updateSelectionSummary()">
                        
                        <div class="w-10 h-10 rounded-xl ${c.type === 'individual' ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600'} flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-certificate text-xl"></i>
                        </div>

                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider ${c.type === 'individual' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-[#001144]'}">
                                    ${c.type_label}
                                </span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider font-mono">Reg: ${c.registration_number || c.uuid}</span>
                            </div>
                            <h5 class="text-sm font-black text-[#001144] mt-0.5 line-clamp-1">${c.title}</h5>
                            <p class="text-[10px] text-gray-400 font-bold mt-0.5">Horas: ${c.hours || 'N/A'} • Fecha: ${c.date || '-'}</p>
                        </div>
                    </div>

                    <a href="${c.view_url}" target="_blank" class="w-8 h-8 rounded-lg bg-gray-100 text-gray-400 hover:text-[#001144] hover:bg-gray-200 flex items-center justify-center transition-all" title="Ver Certificado">
                        <i class="ph-bold ph-eye"></i>
                    </a>
                </div>
            `).join('');

            updateSelectionSummary();
        }

        function toggleSelectAll(master) {
            const checkboxes = document.querySelectorAll('.cert-item-checkbox');
            checkboxes.forEach(cb => cb.checked = master.checked);
            updateSelectionSummary();
        }

        function updateSelectionSummary() {
            const checkboxes = document.querySelectorAll('.cert-item-checkbox:checked');
            const total = document.querySelectorAll('.cert-item-checkbox').length;
            const count = checkboxes.length;

            document.getElementById('selected-summary').innerText = `${count} de ${total} seleccionados`;
            document.getElementById('select-all-certs').checked = count === total && total > 0;

            const btnZip = document.getElementById('btn-download-zip');
            const btnMerged = document.getElementById('btn-download-merged');
            btnZip.disabled = count === 0;
            btnMerged.disabled = count === 0;
        }

        // ====================================================================
        // MOTOR DE RENDERIZADO DEL CERTIFICADO (HTML EXACTO A front.certificate-view)
        // ====================================================================
        function getFontFamily(k) {
            const map = {
                'font-lato': "'Lato', sans-serif",
                'font-merriweather': "'Merriweather', serif",
                'font-pinyon': "'Pinyon Script', cursive",
                'font-greatvibes': "'Great Vibes', cursive",
                'font-montserrat': "'Montserrat', sans-serif",
                'font-opensans': "'Open Sans', sans-serif"
            };
            return map[k] || "'Lato', sans-serif";
        }

        function fixUrl(url) {
            if (!url) return url;
            if (url.startsWith('data:')) return url;
            const currentOrigin = window.location.origin;

            if (url.startsWith('http://') || url.startsWith('https://')) {
                try {
                    const parsed = new URL(url);
                    return currentOrigin + parsed.pathname + (parsed.search || '');
                } catch (e) {
                    return url;
                }
            }

            if (url.includes('/storage/')) {
                const parts = url.split('/storage/');
                return currentOrigin + '/storage/' + parts[parts.length - 1];
            }

            const cleanPath = url.replace(/^\/+/, '');
            return currentOrigin + '/' + cleanPath;
        }

        function isSignatureOrSealElement(type) {
            if (!type) return false;
            const t = type.toLowerCase();
            return t.includes('signature') || t.includes('seal');
        }

        function getResolvedDimension(val, fallback) {
            if (val === undefined || val === null || val === '' || val === 'auto') return fallback;
            if (typeof val === 'number') return val + 'px';
            if (typeof val === 'string') {
                const trimmed = val.trim();
                if (/^\d+(\.\d+)?$/.test(trimmed)) return trimmed + 'px';
                return trimmed;
            }
            return fallback;
        }

        function buildCertificateHtmlString(templateData, realData, includeSignatures = true) {
            const pagesCount = templateData.pages || 1;
            let pagesHtml = '';

            for (let i = 1; i <= pagesCount; i++) {
                const bg = i === 1 ? templateData.background : templateData.background2;
                const elements = i === 1 ? (templateData.elements || []) : (templateData.elements2 || []);
                const bgUrl = bg ? fixUrl(bg) : null;

                let elementsHTML = '';

                elements.forEach(el => {
                    // Si el usuario eligió NO incluir firmas ni sellos, omitir este elemento
                    if (!includeSignatures && isSignatureOrSealElement(el.type)) {
                        return;
                    }

                    const posX = (el.x !== undefined && el.x !== null) ? (parseFloat(el.x) || 0) : 0;
                    const posY = (el.y !== undefined && el.y !== null) ? (parseFloat(el.y) || 0) : 0;
                    let cStyle = `position:absolute; left:${posX}px; top:${posY}px; display:flex; flex-direction:column; z-index:10;`;

                    if (el.style) {
                        const ff = getFontFamily(el.style.fontFamily);
                        const fs = el.style.fontSize || '16px';
                        const fc = el.style.color || '#000000';
                        const fw = el.style.fontWeight || 'normal';

                        let alignItems = 'flex-start';
                        let textAlign = el.style.textAlign || 'left';
                        if (textAlign === 'center') alignItems = 'center';
                        else if (textAlign === 'right') alignItems = 'flex-end';

                        cStyle += `font-family:${ff}; font-size:${fs}; color:${fc}; font-weight:${fw}; align-items:${alignItems}; text-align:${textAlign};`;

                        if (el.style.width && el.style.width !== 'auto') {
                            cStyle += `width:${el.style.width}; white-space:normal;`;
                            if (el.style.height) cStyle += `height:${el.style.height};`;
                        } else {
                            cStyle += `width:auto; white-space:nowrap;`;
                        }
                    }

                    let innerHTML = '';

                    if (el.type === 'qr') {
                        const qrSize = parseInt(el.style?.width) || 120;
                        const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=${qrSize}x${qrSize}&data=${encodeURIComponent(realData.verification_url || '#')}`;
                        innerHTML = `
                            <div style="background:white; padding:6px; display:inline-block; border-radius:4px; box-shadow:0 1px 3px rgba(0,0,0,0.1); width:${qrSize}px; height:${qrSize}px;">
                                <img src="${qrUrl}" crossOrigin="anonymous" style="width:100%; height:100%; display:block; object-fit:contain;">
                            </div>
                        `;
                    }
                    else if (['image', 'seal', 'executive_seal', 'academic_seal', 'executive_signature', 'academic_signature', 'instructor_signature', 'signature', 'manager_signature'].includes(el.type)) {
                        let sealSrc = el.src;
                        if (el.type === 'executive_seal') sealSrc = realData.executive_seal || el.src;
                        if (el.type === 'academic_seal') sealSrc = realData.academic_seal || el.src;
                        if (el.type === 'executive_signature' || el.type === 'manager_signature') sealSrc = realData.executive_signature || el.src;
                        if (el.type === 'academic_signature') sealSrc = realData.academic_signature || el.src;
                        if (el.type === 'instructor_signature') sealSrc = realData.instructor_signature || el.src;
                        if (el.type === 'signature') sealSrc = el.content?.signature_img || realData.instructor_signature || el.src;

                        const isSig = isSignatureOrSealElement(el.type) && el.type.includes('signature');
                        const isSeal = isSignatureOrSealElement(el.type) && el.type.includes('seal');
                        const defaultW = isSig ? '180px' : (isSeal ? '130px' : '150px');
                        const defaultH = isSig ? '80px' : (isSeal ? '130px' : '150px');

                        // Respetar posición y dimensiones configuradas exactamente en la plantilla
                        const qw = getResolvedDimension(el.style?.width || el.width, defaultW);
                        const qh = getResolvedDimension(el.style?.height || el.height, defaultH);
                        cStyle += `width:${qw}; height:${qh}; overflow:hidden; display:flex; justify-content:center; align-items:center;`;

                        if (sealSrc) {
                            const src = fixUrl(sealSrc);
                            innerHTML = `<img src="${src}" class="cert-fit-img" crossOrigin="anonymous" style="width:100%; height:100%; object-fit:contain; display:block;">`;
                        } else {
                            innerHTML = '';
                        }
                    }
                    else if (el.type === 'student_name') {
                        innerHTML = `<span>${realData.student_name}</span>`;
                    }
                    else if (el.type === 'course_title') {
                        innerHTML = `<span>${realData.course_title}</span>`;
                    }
                    else if (el.type === 'registration_number') {
                        innerHTML = `<p>Reg. N° ${realData.registration_number || ''}</p>`;
                    }
                    else if (['text', 'certificate_body', 'issue_date'].includes(el.type)) {
                        let content = (el.content || '').toString();
                        content = content.replace(/{student_name}/g, realData.student_name);
                        content = content.replace(/{course_title}/g, realData.course_title);
                        content = content.replace(/{date}/g, realData.date);
                        content = content.replace(/{hours}/g, realData.hours || '');
                        content = content.replace(/{start_date}/g, realData.start_date || '');
                        content = content.replace(/{end_date}/g, realData.end_date || '');
                        content = content.replace(/{registration_number}/g, realData.registration_number || '');
                        content = content.replace(/{instructor}/g, realData.instructor_name || '');

                        if (realData.is_custom) {
                            const makeFlexDateRegex = (dateStr) => {
                                if (!dateStr) return null;
                                let escaped = dateStr.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                                escaped = escaped.replace(/\s+/g, '\\s+');
                                escaped = escaped.replace(/\b(?:de|del)\b/gi, '(?:de|del)');
                                return new RegExp(escaped, 'gi');
                            };

                            if (realData.base_start_date && realData.start_date && realData.base_start_date !== realData.start_date) {
                                const regex = makeFlexDateRegex(realData.base_start_date);
                                if (regex) content = content.replace(regex, realData.start_date);
                            }
                            if (realData.base_end_date && realData.end_date && realData.base_end_date !== realData.end_date) {
                                const regex = makeFlexDateRegex(realData.base_end_date);
                                if (regex) content = content.replace(regex, realData.end_date);
                            }
                            if (realData.base_hours && realData.hours && realData.base_hours !== realData.hours) {
                                const regex = new RegExp('\\b' + realData.base_hours + '\\b(?=\\s*(?:<[^>]+>)*\\s*horas)', 'gi');
                                content = content.replace(regex, realData.hours);
                            }
                            if (realData.base_date && realData.date && realData.base_date !== realData.date) {
                                const regex = makeFlexDateRegex(realData.base_date);
                                if (regex) content = content.replace(regex, realData.date);
                            }
                        }
                        
                        innerHTML = content;
                    }

                    elementsHTML += `<div style="${cStyle}">${innerHTML}</div>`;
                });

                pagesHtml += `
                <div id="canvas${i === 1 ? '' : '2'}" class="cert-page" style="display:block;">
                    ${bgUrl ? `<img src="${bgUrl}" crossOrigin="anonymous" style="position:absolute; top:0; left:0; width:1123px; height:794px; object-fit:fill; z-index:0;">` : ''}
                    ${elementsHTML}
                </div>
                `;
            }

            return `<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Lato:wght@400;700;900&family=Merriweather:wght@400;700&family=Pinyon+Script&family=Montserrat:wght@400;700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; border: 0; }
    html, body {
        width: 1123px;
        background: white;
        margin: 0; padding: 0;
    }
    .cert-page {
        width: 1123px;
        height: 794px;
        position: relative;
        overflow: hidden;
        background: white;
        line-height: 1.15;
        font-size: 16px;
        color: #000;
        text-align: left;
    }
    .cert-page img { display: block; }
    p { margin: 0; padding: 0; }
    .ql-align-center { text-align: center; }
    .ql-align-right { text-align: right; }
    .ql-align-left { text-align: left; }
    .ql-align-justify { text-align: justify; }
    strong { font-weight: bold; }
    em { font-style: italic; }
    u { text-decoration: underline; }
</style>
</head>
<body style="margin:0; padding:0; background:white;">
    ${pagesHtml}
</body>
</html>`;
        }

        // ====================================================================
        // CAPTURA DE PÁGINAS A CANVAS
        // ====================================================================
        async function renderCertificateCanvasPages(cert, includeSignatures = true) {
            const iframe = document.getElementById('cert-render-iframe');
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            const iframeWin = iframe.contentWindow;

            const html = buildCertificateHtmlString(cert.templateData, cert.realData, includeSignatures);

            iframeDoc.open();
            iframeDoc.write(html);
            iframeDoc.close();

            // Cargar html2canvas dentro del iframe si no está presente
            if (!iframeWin.html2canvas) {
                const script = iframeDoc.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';
                iframeDoc.head.appendChild(script);

                await new Promise((resolve) => {
                    script.onload = resolve;
                    script.onerror = resolve;
                    setTimeout(resolve, 2500);
                });
            }

            try { await iframeDoc.fonts.ready; } catch (e) { }
            await new Promise(r => setTimeout(r, 200));

            // Esperar que carguen las imágenes
            const allImgs = Array.from(iframeDoc.querySelectorAll('img'));
            await Promise.all(allImgs.map(img => {
                if (img.complete && img.naturalWidth !== 0) return Promise.resolve();
                return new Promise(resolve => {
                    img.onload = resolve;
                    img.onerror = resolve;
                    setTimeout(resolve, 3000);
                });
            }));

            await new Promise(r => setTimeout(r, 100));

            const pagesCount = cert.templateData.pages || 1;
            const canvas1 = iframeDoc.getElementById('canvas');
            const canvas2 = iframeDoc.getElementById('canvas2');
            const capturedDataUrls = [];

            for (let i = 1; i <= pagesCount; i++) {
                if (canvas1) canvas1.style.display = (i === 1) ? 'block' : 'none';
                if (canvas2) canvas2.style.display = (i === 2) ? 'block' : 'none';

                await new Promise(r => setTimeout(r, 80));

                const canvasId = i === 1 ? 'canvas' : 'canvas2';
                const certEl = iframeDoc.getElementById(canvasId);
                if (!certEl) continue;

                // Evitar distorsión de imágenes en html2canvas
                const certImgs = certEl.querySelectorAll('img');
                const oldStyles = [];
                certImgs.forEach((img, idx) => {
                    const parent = img.parentElement;
                    oldStyles[idx] = {
                        imgStyle: img.style.cssText,
                        parentStyle: parent ? parent.style.cssText : null
                    };

                    if (img.src && img.src.includes('qrserver.com')) {
                        const w = parseInt(parent?.style?.width) || 120;
                        const h = parseInt(parent?.style?.height) || 120;
                        img.style.width = w + 'px';
                        img.style.height = h + 'px';
                        img.style.objectFit = 'contain';
                        return;
                    }

                    if (parent && img.naturalWidth && img.naturalHeight) {
                        const parentW = parseFloat(parent.style.width) || parent.clientWidth;
                        const parentH = parseFloat(parent.style.height) || parent.clientHeight;
                        if (parentW > 0 && parentH > 0) {
                            const scale = Math.min(parentW / img.naturalWidth, parentH / img.naturalHeight);
                            const fitW = Math.round(img.naturalWidth * scale);
                            const fitH = Math.round(img.naturalHeight * scale);
                            parent.style.display = 'flex';
                            parent.style.justifyContent = 'center';
                            parent.style.alignItems = 'center';
                            img.style.width = fitW + 'px';
                            img.style.height = fitH + 'px';
                            img.style.objectFit = 'contain';
                            img.style.margin = 'auto';
                        }
                    }
                });

                await new Promise(r => setTimeout(r, 50));

                const canvasObj = await iframeWin.html2canvas(certEl, {
                    scale: 2.8,
                    useCORS: true,
                    allowTaint: false,
                    logging: false,
                    backgroundColor: '#ffffff',
                    width: 1123,
                    height: 794,
                    windowWidth: 1123,
                    windowHeight: 794,
                    scrollX: 0,
                    scrollY: 0,
                    x: 0,
                    y: 0
                });

                // Restaurar estilos
                certImgs.forEach((img, idx) => {
                    if (oldStyles[idx]) {
                        img.style.cssText = oldStyles[idx].imgStyle;
                        if (img.parentElement && oldStyles[idx].parentStyle !== null) {
                            img.parentElement.style.cssText = oldStyles[idx].parentStyle;
                        }
                    }
                });

                const dataUrl = canvasObj.toDataURL('image/jpeg', 0.98);
                capturedDataUrls.push(dataUrl);
            }

            return capturedDataUrls;
        }

        // ====================================================================
        // DESCARGA EN LOTE (ZIP O MERGED PDF)
        // ====================================================================
        async function startBatchDownload(format) {
            const checkedBoxes = Array.from(document.querySelectorAll('.cert-item-checkbox:checked'));
            if (checkedBoxes.length === 0) {
                alert('Selecciona al menos un certificado para descargar.');
                return;
            }

            const toggleSigEl = document.getElementById('toggle-signatures');
            const includeSignatures = toggleSigEl ? toggleSigEl.checked : true;
            const sigSuffix = includeSignatures ? '' : '_SinFirmas';

            const selectedCerts = currentCertificatesList.filter(c => 
                checkedBoxes.some(cb => cb.value === c.id)
            );

            const progressContainer = document.getElementById('download-progress-container');
            const progressBar = document.getElementById('download-progress-bar');
            const progressPercent = document.getElementById('download-progress-percent');
            const progressStatus = document.getElementById('download-progress-status');
            const btnZip = document.getElementById('btn-download-zip');
            const btnMerged = document.getElementById('btn-download-merged');

            progressContainer.classList.remove('hidden');
            btnZip.disabled = true;
            btnMerged.disabled = true;

            const total = selectedCerts.length;
            const studentName = currentStudentInfo ? currentStudentInfo.name : 'Estudiante';
            const studentDni = currentStudentInfo ? currentStudentInfo.dni : 'DNI';
            const cleanStudentName = studentName.replace(/[^a-zA-Z0-9_\-áéíóúÁÉÍÓÚñÑ ]/g, '').replace(/\s+/g, '_');

            try {
                if (format === 'zip') {
                    // ===================================
                    // MODO 1: ZIP CON PDFs INDIVIDUALES
                    // ===================================
                    const zip = new JSZip();

                    for (let idx = 0; idx < total; idx++) {
                        const cert = selectedCerts[idx];
                        const pct = Math.round(((idx) / total) * 90);
                        progressBar.style.width = `${pct}%`;
                        progressPercent.innerText = `${pct}%`;
                        progressStatus.innerHTML = `<i class="ph-bold ph-spinner animate-spin text-[#FF7A00]"></i> Generando PDF (${idx + 1}/${total}): <span class="font-bold truncate max-w-xs">${cert.title}</span>`;

                        const pageImages = await renderCertificateCanvasPages(cert, includeSignatures);

                        // Crear PDF individual para este certificado
                        const pdf = new jspdf.jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
                        pageImages.forEach((imgData, pIdx) => {
                            if (pIdx > 0) pdf.addPage('a4', 'landscape');
                            pdf.addImage(imgData, 'JPEG', 0, 0, 297, 210);
                        });

                        const pdfBlob = pdf.output('blob');
                        const cleanTitle = cert.title.replace(/[^a-zA-Z0-9_\-áéíóúÁÉÍÓÚñÑ ]/g, '').replace(/\s+/g, '_');
                        const certFilename = `${idx + 1}_${cleanTitle}_${cert.registration_number || cert.uuid}${sigSuffix}.pdf`;

                        zip.file(certFilename, pdfBlob);
                    }

                    progressStatus.innerHTML = `<i class="ph-bold ph-spinner animate-spin text-purple-600"></i> Comprimiendo archivo ZIP...`;
                    progressBar.style.width = `95%`;
                    progressPercent.innerText = `95%`;

                    const zipBlob = await zip.generateAsync({ type: 'blob' });
                    downloadBlob(zipBlob, `Certificados_${studentDni}_${cleanStudentName}${sigSuffix}.zip`);

                } else {
                    // ===================================
                    // MODO 2: UN SOLO PDF COMBINADO
                    // ===================================
                    const mergedPdf = new jspdf.jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
                    let totalPagesRendered = 0;

                    for (let idx = 0; idx < total; idx++) {
                        const cert = selectedCerts[idx];
                        const pct = Math.round(((idx) / total) * 95);
                        progressBar.style.width = `${pct}%`;
                        progressPercent.innerText = `${pct}%`;
                        progressStatus.innerHTML = `<i class="ph-bold ph-spinner animate-spin text-[#FF7A00]"></i> Agregando al PDF (${idx + 1}/${total}): <span class="font-bold truncate max-w-xs">${cert.title}</span>`;

                        const pageImages = await renderCertificateCanvasPages(cert, includeSignatures);

                        pageImages.forEach((imgData) => {
                            if (totalPagesRendered > 0) {
                                mergedPdf.addPage('a4', 'landscape');
                            }
                            mergedPdf.addImage(imgData, 'JPEG', 0, 0, 297, 210);
                            totalPagesRendered++;
                        });
                    }

                    mergedPdf.save(`Certificados_Completos_${studentDni}_${cleanStudentName}${sigSuffix}.pdf`);
                }

                // Finalizado con éxito
                progressBar.style.width = `100%`;
                progressPercent.innerText = `100%`;
                progressStatus.innerHTML = `<i class="ph-bold ph-check-circle text-emerald-500 text-lg"></i> ¡Descarga completada con éxito!`;

                // Registrar en la base de datos
                fetch("{{ route('admin.downloads.log') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        student_name: studentName,
                        student_dni: studentDni,
                        format: format === 'zip' ? 'zip' : 'merged_pdf',
                        certificates_count: total,
                        certificates_details: selectedCerts.map(c => ({ 
                            id: c.id, 
                            title: c.title, 
                            type: c.type, 
                            uuid: c.uuid,
                            with_signatures: includeSignatures
                        }))
                    })
                }).catch(e => console.error(e));

                setTimeout(() => {
                    progressContainer.classList.add('hidden');
                    btnZip.disabled = false;
                    btnMerged.disabled = false;
                }, 2500);

            } catch (err) {
                console.error('Error generando descargas masivas:', err);
                alert('Ocurrió un error al generar los certificados: ' + err.message);
                progressContainer.classList.add('hidden');
                btnZip.disabled = false;
                btnMerged.disabled = false;
            }
        }

        function downloadBlob(blob, filename) {
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            setTimeout(() => {
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            }, 300);
        }
    </script>
</x-layouts.admin>
