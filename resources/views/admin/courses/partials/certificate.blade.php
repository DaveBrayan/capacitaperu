<!-- LIBRERÍAS "MINIWORD" (QUILL) -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- INPUT OCULTO -->
<input type="hidden" name="certificate_data" id="certificate_data_input" value="{{ json_encode($course->certificate_template ?? []) }}">

<style>
    #cert-canvas, #cert-canvas-2 {
        width: 1123px;
        height: 794px;
        background-color: white;
        position: relative;
        box-shadow: 0 0 50px rgba(0,0,0,0.3);
        overflow: hidden;
        margin: 0;
        flex-shrink: 0;
        line-height: 1.15 !important;
        text-align: left;
        color: black;
        font-size: 16px;
        transform-origin: top left;
        transition: transform 0.2s ease-out, box-shadow 0.2s;
    }

    #cert-canvas-wrapper, #cert-canvas-2-wrapper {
        transition: width 0.2s ease-out, height 0.2s ease-out;
    }

    #cert-canvas * {
        box-sizing: border-box !important;
        margin: 0 !important;
        padding: 0 !important;
        border: 0 !important;
    }

    .cert-element {
        position: absolute;
        user-select: none;
        cursor: grab;
        z-index: 10;
        border: 1px dashed rgba(0, 0, 0, 0.15) !important;
        min-width: 50px;
    }

    .cert-element:hover {
        border-color: #0AD1C8 !important;
        background-color: rgba(10, 209, 200, 0.05);
    }
    .cert-element.selected {
        border: 2px solid #213A57 !important;
        background-color: rgba(33, 58, 87, 0.05);
        z-index: 100;
        cursor: grabbing;
        box-shadow: 0 0 0 4px rgba(33, 58, 87, 0.1);
    }

    .resize-handle {
        position: absolute;
        width: 12px;
        height: 12px;
        background-color: #0AD1C8;
        border: 2px solid white;
        border-radius: 50%;
        z-index: 110;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        display: none;
    }

    .cert-element.selected .resize-handle { display: block; }
    .handle-nw { top: -6px; left: -6px; cursor: nw-resize; }
    .handle-ne { top: -6px; right: -6px; cursor: ne-resize; }
    .handle-sw { bottom: -6px; left: -6px; cursor: sw-resize; }
    .handle-se { bottom: -6px; right: -6px; cursor: se-resize; }

    .cert-element img {
        display: block;
        pointer-events: none;
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .ql-toolbar.ql-snow { border: 1px solid #e5e7eb; border-radius: 8px 8px 0 0; background: #f9fafb; }
    .ql-container.ql-snow { border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 8px 8px; background: white; font-family: inherit; font-size: 13px; }
    .ql-editor { min-height: 120px; }

    @import url('https://fonts.googleapis.com/css2?family=Great+Vibes&family=Lato:wght@400;700;900&family=Merriweather:wght@400;700&family=Pinyon+Script&family=Montserrat:wght@400;700&family=Open+Sans:wght@400;700&display=swap');

    .font-lato { font-family: 'Lato', sans-serif; }
    .font-merriweather { font-family: 'Merriweather', serif; }
    .font-pinyon { font-family: 'Pinyon Script', cursive; }
    .font-greatvibes { font-family: 'Great Vibes', cursive; }
    .font-montserrat { font-family: 'Montserrat', sans-serif; }
    .font-opensans { font-family: 'Open Sans', sans-serif; }

    .align-btn.active { background-color: #e0f2fe; color: #0284c7; border-color: #0284c7; }

    #cert-canvas b, #cert-canvas strong, #cert-canvas i, #cert-canvas em, #cert-canvas span {
        display: inline !important;
        vertical-align: baseline !important;
    }
    #cert-canvas p {
        margin: 0 !important;
        padding: 0 !important;
        display: block !important;
    }

    .custom-scrollbar::-webkit-scrollbar { width: 0px; height: 0px; display: none; }
    .custom-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
    @php
        $fmtDate = function($date) {
            if(!$date) return null;
            $d = \Carbon\Carbon::parse($date);
            $months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
            return $d->day . ' de ' . $months[$d->month - 1] . ' del ' . $d->year;
        };
        $courseMeta = $course->metadata ?? [];
    @endphp
    window.courseMeta = {
        title: @json($course->title),
        instructor: {
            name: @json($course->instructor->certificate_name ?? $course->instructor->name ?? 'Instructor'),
            role: "CAPACITADOR",
            signature: @json($course->instructor->signature_url ? asset($course->instructor->signature_url) : null)
        },
        executive: {
            name: "Gerente General",
            role: "GERENTE GENERAL",
            signature: "{{ !empty($executive_signature) ? asset($executive_signature) : null }}"
        },
        academic: {
            name: "Coordinadora Académica",
            role: "COORDINADORA ACADÉMICA",
            signature: "{{ !empty($academic_signature) ? asset($academic_signature) : null }}"
        },
        executiveSeal: "{{ !empty($executive_seal) ? asset($executive_seal) : null }}",
        academicSeal: "{{ !empty($academic_seal) ? asset($academic_seal) : null }}",
        studentExample: "JUAN PÉREZ DE LA CRUZ",
        startDate: @json($fmtDate($courseMeta['start_date'] ?? null) ?? '01 de enero del 2025'),
        endDate: @json($fmtDate($courseMeta['end_date'] ?? null) ?? '31 de enero del 2025'),
        hours: @json($course->duration ?? ($courseMeta['hours'] ?? '80')),
        registrationNumber: @json($courseMeta['registration_number'] ?? 'CP-2024-001')
    };

    const mediaRoutes = {
        list: "{{ route('admin.media.list') }}",
        upload: "{{ route('admin.media.upload') }}",
        rename: "{{ route('admin.media.rename') }}",
        delete: "{{ route('admin.media.delete') }}"
    };

    function formatDateES(dateStr) {
        if(!dateStr) return '...';
        const months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        const parts = dateStr.split('-');
        if(parts.length !== 3) return dateStr;
        const d = new Date(parts[0], parts[1] - 1, parts[2]);
        return `${d.getDate()} de ${months[d.getMonth()]} del ${d.getFullYear()}`;
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Escuchar cambios en los campos del Step 1 (Info)
        const startInp = document.querySelector('input[name="metadata[start_date]"]');
        const endInp   = document.querySelector('input[name="metadata[end_date]"]');
        const hoursInp = document.querySelector('input[name="duration"]');
        const regInp   = document.querySelector('input[name="metadata[registration_number]"]');
        const titleInp = document.querySelector('input[name="title"]');

        if(titleInp) titleInp.addEventListener('input', (e) => {
            window.courseMeta.title = e.target.value;
            if (typeof window.updateDynamicElements === 'function') window.updateDynamicElements();
        });
        if(startInp) startInp.addEventListener('change', (e) => {
            window.courseMeta.startDate = formatDateES(e.target.value);
            if (typeof window.updateDynamicElements === 'function') window.updateDynamicElements();
        });
        if(endInp) endInp.addEventListener('change', (e) => {
            window.courseMeta.endDate = formatDateES(e.target.value);
            if (typeof window.updateDynamicElements === 'function') window.updateDynamicElements();
        });
        if(hoursInp) hoursInp.addEventListener('input', (e) => {
            window.courseMeta.hours = e.target.value;
            if (typeof window.updateDynamicElements === 'function') window.updateDynamicElements();
        });
        if(regInp) regInp.addEventListener('input', (e) => {
            window.courseMeta.registrationNumber = e.target.value;
            if (typeof window.updateDynamicElements === 'function') window.updateDynamicElements();
        });
    });
</script>

<div class="flex flex-col lg:flex-row h-[800px] border border-gray-200 rounded-2xl overflow-hidden bg-gray-100">
    <!-- BARRA LATERAL -->
    <div class="w-full lg:w-72 bg-white border-r border-gray-200 flex flex-col z-30 shadow-lg relative transition-all duration-300 h-[450px] lg:h-full">
        <div class="p-4 bg-[#213A57] text-white font-bold flex justify-between items-center shrink-0">
            <span id="sidebar-title">Diseño de Diploma</span>
            <div class="flex gap-1">
                <button type="button" onclick="zoomCanvas(-0.1)" class="w-8 h-8 flex items-center justify-center bg-white/10 rounded-lg hover:bg-white/20 transition-colors" title="Alejar"><i class="ph-bold ph-minus text-sm"></i></button>
                <button type="button" onclick="zoomCanvas(0.1)" class="w-8 h-8 flex items-center justify-center bg-white/10 rounded-lg hover:bg-white/20 transition-colors" title="Acercar"><i class="ph-bold ph-plus text-sm"></i></button>
                <button type="button" onclick="fitCanvas()" class="w-8 h-8 flex items-center justify-center bg-white/10 rounded-lg hover:bg-white/20 transition-colors" title="Ajustar"><i class="ph-bold ph-arrows-out text-sm"></i></button>
            </div>
        </div>

        <!-- PANEL ADD -->
        <div id="panel-add" class="flex-1 overflow-y-auto p-3 space-y-2">
            <!-- BOTÓN IMPORTAR DE PLANTILLA -->
            <div class="mb-3">
                <button type="button" onclick="openImportTemplateModal()" 
                        class="w-full py-2.5 px-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl shadow-md flex items-center justify-center gap-2 font-black text-xs transition-all transform hover:-translate-y-0.5 border border-emerald-500/30">
                    <i class="ph-bold ph-file-arrow-down text-base"></i>
                    <span>Importar de Plantilla</span>
                </button>
            </div>

            <!-- Selector de páginas -->
            <div class="mb-4">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 block">Configuración</label>
                <select id="cert-pages-select" class="w-full p-2 bg-white border border-gray-300 rounded-lg text-xs font-bold outline-none" onchange="changePageCount(this.value)">
                    <option value="1">1 Hoja</option>
                    <option value="2">2 Hojas</option>
                </select>
            </div>

            <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-2 text-center" id="active-page-indicator">
                <span class="text-xs font-bold text-blue-600">Editando: <span id="active-page-text">Hoja 1</span></span>
            </div>

            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Textos Dinámicos</p>
            <button type="button" onclick="addElement('student_name')" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-lg flex items-center gap-3 hover:border-blue-400 transition-all text-left group">
                <i class="ph-bold ph-student text-lg text-gray-400 group-hover:text-blue-500"></i><span class="text-xs font-bold text-gray-600">Nombre Estudiante</span>
            </button>
            <button type="button" onclick="addElement('course_title')" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-lg flex items-center gap-3 hover:border-blue-400 transition-all text-left group">
                <i class="ph-bold ph-graduation-cap text-lg text-gray-400 group-hover:text-blue-500"></i><span class="text-xs font-bold text-gray-600">Título Curso</span>
            </button>
            <button type="button" onclick="addElement('text')" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-lg flex items-center gap-3 hover:border-blue-400 transition-all text-left group">
                <i class="ph-bold ph-text-t text-lg text-gray-400 group-hover:text-blue-500"></i><span class="text-xs font-bold text-gray-600">Texto Libre</span>
            </button>
            <button type="button" onclick="addElement('certificate_body')" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-lg flex items-center gap-3 hover:border-blue-400 transition-all text-left group">
                <i class="ph-bold ph-article text-lg text-gray-400 group-hover:text-blue-500"></i><span class="text-xs font-bold text-gray-600">Cuerpo Certificado</span>
            </button>
            <button type="button" onclick="addElement('issue_date')" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-lg flex items-center gap-3 hover:border-blue-400 transition-all text-left group">
                <i class="ph-bold ph-calendar text-lg text-gray-400 group-hover:text-blue-500"></i><span class="text-xs font-bold text-gray-600">Fecha de Emisión</span>
            </button>
            <button type="button" onclick="addElement('registration_number')" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-lg flex items-center gap-3 hover:border-blue-400 transition-all text-left group">
                <i class="ph-bold ph-hash text-lg text-gray-400 group-hover:text-blue-500"></i><span class="text-xs font-bold text-gray-600">Número de Registro</span>
            </button>

            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-4 mb-2">Gráficos</p>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" onclick="addElement('qr')" class="p-2.5 bg-gray-50 border border-gray-200 rounded-lg flex flex-col items-center gap-1 hover:border-blue-400 transition-all">
                    <i class="ph-bold ph-qr-code text-xl text-gray-400"></i><span class="text-[10px] font-bold text-gray-600">QR</span>
                </button>
                <button type="button" onclick="openMediaManager('seal')" class="p-2.5 bg-gray-50 border border-gray-200 rounded-lg flex flex-col items-center gap-1 hover:border-blue-400 transition-all">
                    <i class="ph-bold ph-image text-xl text-gray-400"></i><span class="text-[10px] font-bold text-gray-600">Imagen</span>
                </button>
            </div>

            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-4 mb-2">Firmas Institucionales</p>
            <button type="button" onclick="addElement('signature')" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-lg flex items-center gap-3 hover:border-[#FF7A00] transition-all text-left group">
                <i class="ph-bold ph-pen-nib text-lg text-gray-400 group-hover:text-[#FF7A00]"></i>
                <div class="flex-1 truncate">
                    <span class="text-xs font-bold text-gray-700 block truncate">Instructor</span>
                    <span class="text-[9px] text-gray-400 block">Capacitador del curso</span>
                </div>
            </button>
            <button type="button" onclick="addElement('executive_signature')" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-lg flex items-center gap-3 hover:border-[#FF7A00] transition-all text-left group">
                <i class="ph-bold ph-briefcase text-lg text-gray-400 group-hover:text-[#FF7A00]"></i>
                <div class="flex-1 truncate">
                    <span class="text-xs font-bold text-gray-700 block truncate">Gerente General</span>
                    <span class="text-[9px] text-gray-400 block">Firma configurada</span>
                </div>
            </button>
            <button type="button" onclick="addElement('academic_signature')" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-lg flex items-center gap-3 hover:border-[#FF7A00] transition-all text-left group">
                <i class="ph-bold ph-graduation-cap text-lg text-gray-400 group-hover:text-[#FF7A00]"></i>
                <div class="flex-1 truncate">
                    <span class="text-xs font-bold text-gray-700 block truncate">Coordinadora Académica</span>
                    <span class="text-[9px] text-gray-400 block">Firma configurada</span>
                </div>
            </button>

            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-4 mb-2">Sellos Institucionales</p>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" onclick="addElement('executive_seal')" class="p-2.5 bg-gray-50 border border-gray-200 rounded-lg flex flex-col items-center gap-1 hover:border-[#FF7A00] transition-all text-center group">
                    <i class="ph-bold ph-seal-check text-xl text-gray-400 group-hover:text-[#FF7A00]"></i>
                    <span class="text-[10px] font-bold text-gray-700 leading-tight">Sello Gerente General</span>
                </button>
                <button type="button" onclick="addElement('academic_seal')" class="p-2.5 bg-gray-50 border border-gray-200 rounded-lg flex flex-col items-center gap-1 hover:border-[#FF7A00] transition-all text-center group">
                    <i class="ph-bold ph-seal-check text-xl text-gray-400 group-hover:text-[#FF7A00]"></i>
                    <span class="text-[10px] font-bold text-gray-700 leading-tight">Sello Coord. Académica</span>
                </button>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-200 space-y-3">
                <button type="button" onclick="openMediaManager('background')" class="w-full py-2.5 bg-[#E0F9F6] border border-[#0AD1C8] text-[#0B6477] rounded-xl font-bold text-xs flex items-center justify-center gap-2 hover:bg-[#0AD1C8] hover:text-white transition-all">
                    <i class="ph-bold ph-image-square"></i> Cambiar Fondo
                </button>
            </div>
        </div>

        <!-- PANEL EDIT -->
        <div id="panel-edit" class="hidden flex flex-col h-full bg-gray-50 absolute inset-0 z-40">
            <div class="px-4 py-3 bg-white border-b border-gray-200 flex items-center gap-2 shrink-0">
                <button type="button" onclick="deselectAll()" class="p-1.5 hover:bg-gray-100 rounded-lg text-gray-500"><i class="ph-bold ph-arrow-left"></i></button>
                <span class="text-xs font-bold text-gray-700 uppercase tracking-wide">Editar Elemento</span>
            </div>
            <div class="flex-1 overflow-y-auto p-4 space-y-6">
                <div id="group-content" class="hidden space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Contenido del Texto</label>
                    <div id="cert-text-editor"></div>
                </div>
                <div id="group-typography" class="space-y-4 hidden">
                    <label class="text-[10px] font-bold text-gray-400 uppercase border-b border-gray-200 pb-1 block">Tipografía</label>
                    <div class="space-y-2">
                        <label class="text-xs text-gray-500 font-medium">Fuente</label>
                        <select id="prop-font-family" class="w-full p-2 bg-white border border-gray-300 rounded-lg text-xs outline-none" onchange="updateSelectedProp('fontFamily', this.value)">
                            <option value="font-lato">Lato (Sans)</option>
                            <option value="font-montserrat">Montserrat (Geo)</option>
                            <option value="font-opensans">Open Sans (Clean)</option>
                            <option value="font-merriweather">Merriweather (Serif)</option>
                            <option value="font-pinyon">Pinyon Script (Cursiva)</option>
                            <option value="font-greatvibes">Great Vibes (Elegante)</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-xs text-gray-500 font-medium">Tamaño (px)</label>
                            <input type="number" id="prop-font-size" class="w-full p-2 bg-white border border-gray-300 rounded-lg text-xs" oninput="updateSelectedProp('fontSize', this.value)">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs text-gray-500 font-medium">Color</label>
                            <div class="flex items-center gap-2 bg-white border border-gray-300 rounded-lg p-1">
                                <input type="color" id="prop-color" class="w-6 h-6 border-none p-0 bg-transparent cursor-pointer" oninput="updateSelectedProp('color', this.value)">
                                <span class="text-[10px] text-gray-400">PICK</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="toggleStyle('fontWeight')" id="btn-bold" class="flex-1 py-2 bg-white border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 text-xs font-bold">B</button>
                        <button type="button" onclick="toggleStyle('fontStyle')" id="btn-italic" class="flex-1 py-2 bg-white border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 text-xs italic">I</button>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs text-gray-500 font-medium">Alineación</label>
                        <div class="flex bg-gray-200 p-1 rounded-lg">
                            <button type="button" onclick="updateSelectedProp('textAlign', 'left')" id="align-left" class="align-btn flex-1 py-1 rounded text-gray-600 text-xs transition-colors"><i class="ph-bold ph-text-align-left"></i></button>
                            <button type="button" onclick="updateSelectedProp('textAlign', 'center')" id="align-center" class="align-btn flex-1 py-1 rounded text-gray-600 text-xs transition-colors"><i class="ph-bold ph-text-align-center"></i></button>
                            <button type="button" onclick="updateSelectedProp('textAlign', 'right')" id="align-right" class="align-btn flex-1 py-1 rounded text-gray-600 text-xs transition-colors"><i class="ph-bold ph-text-align-right"></i></button>
                            <button type="button" onclick="updateSelectedProp('textAlign', 'justify')" id="align-justify" class="align-btn flex-1 py-1 rounded text-gray-600 text-xs transition-colors"><i class="ph-bold ph-text-align-justify"></i></button>
                        </div>
                    </div>
                </div>
                <div id="group-dimensions" class="space-y-4 hidden">
                    <label class="text-[10px] font-bold text-gray-400 uppercase border-b border-gray-200 pb-1 block">Dimensiones</label>
                    <div class="space-y-2">
                        <input type="range" id="prop-width-range" min="100" max="1100" step="10" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-[#213A57]" oninput="updateSelectedProp('width', this.value)">
                        <input type="number" id="prop-width" class="w-full p-2 bg-white border border-gray-300 rounded-lg text-xs" placeholder="Auto" oninput="updateSelectedProp('width', this.value)">
                    </div>
                </div>
                <div id="group-image" class="space-y-4 hidden">
                    <label class="text-[10px] font-bold text-gray-400 uppercase border-b border-gray-200 pb-1 block">Imagen</label>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="number" id="prop-img-w" class="w-full p-2 bg-white border border-gray-300 rounded-lg text-xs" oninput="updateSelectedProp('width', this.value)">
                        <input type="number" id="prop-img-h" class="w-full p-2 bg-white border border-gray-300 rounded-lg text-xs" oninput="updateSelectedProp('height', this.value)">
                    </div>
                    <button type="button" id="btn-change-img" onclick="openMediaManager('seal')" class="w-full py-2 bg-blue-50 text-blue-600 border border-blue-200 rounded-lg text-xs font-bold mt-2">Cambiar Imagen</button>
                </div>
                <div class="pt-6 border-t border-gray-200">
                    <button type="button" onclick="removeSelectedElement()" class="w-full py-2.5 bg-red-50 text-red-600 border border-red-200 rounded-xl text-xs font-bold hover:bg-red-100 flex items-center justify-center gap-2">
                        <i class="ph-bold ph-trash"></i> Eliminar Elemento
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ÁREA DE TRABAJO -->
    <div class="flex-1 overflow-hidden relative bg-[#1e293b] flex flex-col" id="workspace-container">
        <div class="bg-black/20 backdrop-blur-sm border-b border-white/5 p-3 flex justify-between items-center z-20">
            <span class="text-[10px] font-black text-white/40 uppercase ml-2">Mesa de Trabajo A4 Horizontal</span>
            <div class="flex items-center gap-2">
                <button type="button" onclick="zoomCanvas(-0.1)" class="p-2 text-white/60 hover:text-white"><i class="ph-bold ph-minus"></i></button>
                <span class="text-[10px] font-bold text-white/80 min-w-[40px] text-center" id="zoom-level-indicator">100%</span>
                <button type="button" onclick="zoomCanvas(0.1)" class="p-2 text-white/60 hover:text-white"><i class="ph-bold ph-plus"></i></button>
            </div>
        </div>

        <div class="flex-1 overflow-auto flex flex-col items-center p-10 gap-6 custom-scrollbar" id="scroll-area">
            <div id="cert-canvas-wrapper" class="flex-shrink-0 relative">
                <div id="cert-canvas" data-page="1" onclick="setActivePage(1); if(event.target === this) deselectAll()" class="ring-4 ring-blue-500">
                    <img id="cert-background" src="" class="absolute inset-0 w-full h-full object-fill pointer-events-none z-0 hidden">
                    <div id="cert-background-placeholder" class="absolute inset-0 flex items-center justify-center text-gray-400 z-0 border-2 border-dashed border-gray-300 m-8 rounded-2xl pointer-events-none bg-white">
                        <div class="text-center">
                            <i class="ph-duotone ph-image text-6xl mb-3 text-slate-300"></i>
                            <p class="text-sm font-bold text-slate-400">Sin Fondo</p>
                        </div>
                    </div>
                    <div id="elements-layer" class="absolute inset-0 z-10 w-full h-full"></div>
                </div>
            </div>

            <div id="cert-canvas-2-wrapper" class="flex-shrink-0 relative hidden">
                <div id="cert-canvas-2" data-page="2" onclick="setActivePage(2); if(event.target === this) deselectAll()">
                    <img id="cert-background-2" src="" class="absolute inset-0 w-full h-full object-fill pointer-events-none z-0 hidden">
                    <div id="cert-background-placeholder-2" class="absolute inset-0 flex items-center justify-center text-gray-400 z-0 border-2 border-dashed border-gray-300 m-8 rounded-2xl pointer-events-none bg-white">
                        <div class="text-center">
                            <i class="ph-duotone ph-image text-6xl mb-3 text-slate-300"></i>
                            <p class="text-sm font-bold text-slate-400">Sin Fondo (Hoja 2)</p>
                        </div>
                    </div>
                    <div id="elements-layer-2" class="absolute inset-0 z-10 w-full h-full"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MEDIA MODAL -->
@include('admin.partials.certificate-media-modal')

<!-- IMPORT TEMPLATE MODAL -->
@include('admin.partials.import-certificate-template-modal')

<script>
    let certState = { pages: 1, background: null, elements: [], background2: null, elements2: [] };
    let activePage = 1;
    let selectedElementId = null;
    let currentZoom = 0.6;
    let isDragging = false;
    let isResizing = false;
    let dragOffset = { mouseX: 0, mouseY: 0, elX: 0, elY: 0 };
    let dragElId = null;
    let resizeData = { handle: '', startX: 0, startY: 0, startW: 0, startH: 0, el: null };
    let mediaContext = null;
    let quillEditor = null;

    document.addEventListener("DOMContentLoaded", function() {
        quillEditor = new Quill('#cert-text-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'color': [] }],
                    ['clean']
                ]
            }
        });

        quillEditor.on('text-change', function() {
            updateSelectedProp('content', quillEditor.root.innerHTML);
        });

        const saved = @json($course->certificate_template ?? null);
        if (saved) {
            certState = {
                pages: saved.pages || 1,
                background: fixUrl(saved.background),
                elements: saved.elements || [],
                background2: fixUrl(saved.background2),
                elements2: saved.elements2 || []
            };
        }

        const sel = document.getElementById('cert-pages-select');
        if(sel) sel.value = certState.pages;

        renderCanvas();
        setTimeout(updateZoom, 500);
    });

    function changePageCount(val) {
        certState.pages = parseInt(val);
        if (certState.pages === 1 && activePage === 2) {
            setActivePage(1);
        }
        renderCanvas();
        updateZoom();
    }

    function setActivePage(page) {
        activePage = page;
        document.getElementById('active-page-text').innerText = 'Hoja ' + page;

        const c1 = document.getElementById('cert-canvas');
        const c2 = document.getElementById('cert-canvas-2');

        if (page === 1) {
            c1.classList.add('ring-4', 'ring-blue-500');
            c2.classList.remove('ring-4', 'ring-blue-500');
        } else {
            c2.classList.add('ring-4', 'ring-blue-500');
            c1.classList.remove('ring-4', 'ring-blue-500');
        }
    }

    function getElementAndPage(id) {
        let el = certState.elements.find(x => x.id === id);
        if (el) return { el, page: 1 };
        el = certState.elements2.find(x => x.id === id);
        if (el) return { el, page: 2 };
        return null;
    }

    function addElement(type, src = null) {
        const id = 'el_' + Date.now();
        let newEl = {
            id, type,
            x: 100, y: 100,
            style: {
                fontFamily: 'font-lato',
                fontSize: '24px',
                color: '#000000',
                fontWeight: 'normal',
                textAlign: 'center',
                width: 'auto'
            },
            content: ''
        };

        if (type === 'student_name') newEl.content = window.courseMeta.studentExample;
        if (type === 'course_title') newEl.content = window.courseMeta.title;
        if (['text', 'certificate_body', 'issue_date', 'registration_number'].includes(type)) {
            if (type === 'certificate_body') {
                newEl.content = `<p>Realizado el <b>${window.courseMeta.startDate}</b> al <b>${window.courseMeta.endDate}</b>, con una duración de <b>${window.courseMeta.hours} horas académicas</b>.</p><p><br></p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.</p>`;
                newEl.style.width = '725px';
            } else if (type === 'issue_date') {
                newEl.content = `<p>Ayacucho, ${window.courseMeta.endDate}</p>`;
                newEl.style.width = '400px';
            } else if (type === 'registration_number') {
                newEl.content = `<p>Reg. N° ${window.courseMeta.registrationNumber || 'CP-2024-001'}</p>`;
                newEl.style.width = '300px';
            } else {
                newEl.content = 'Nuevo Texto';
                newEl.style.width = 'auto';
            }
            newEl.style.fontSize = (type === 'certificate_body' || type === 'issue_date') ? '20px' : (type === 'registration_number' ? '16px' : '24px');
            newEl.style.textAlign = (type === 'certificate_body') ? 'justify' : 'center';
            if (type !== 'registration_number') {
                newEl.style.fontStyle = 'italic';
            }
        }
        if (type === 'qr') {
            newEl.style = { width: '120px', height: '120px' };
        }
        if (type === 'executive_signature') {
            newEl.src = window.courseMeta.executive?.signature || null;
            newEl.style = { width: '180px', height: '80px' };
        }
        if (type === 'academic_signature') {
            newEl.src = window.courseMeta.academic?.signature || null;
            newEl.style = { width: '180px', height: '80px' };
        }
        if (type === 'signature') {
            newEl.style.width = '200px';
            newEl.content = {
                name: window.courseMeta.instructor.name,
                role: window.courseMeta.instructor.role,
                size: 80,
                nameStyle: { fontSize: '14px', color: '#000000', fontWeight: 'bold' },
                roleStyle: { fontSize: '10px', color: '#666666' }
            };
        }
        if (type === 'executive_seal') {
            newEl.src = window.courseMeta.executiveSeal || null;
            newEl.style = { width: '130px', height: '130px' };
        }
        if (type === 'academic_seal') {
            newEl.src = window.courseMeta.academicSeal || null;
            newEl.style = { width: '130px', height: '130px' };
        }
        if (type === 'image' || type === 'seal') {
            newEl.src = src;
            newEl.style = { width: '150px', height: '150px' };
        }

        if (activePage === 1) {
            certState.elements.push(newEl);
        } else {
            certState.elements2.push(newEl);
        }
        selectElement(id);
    }

    function selectElement(id) { selectedElementId = id; renderCanvas(); showEditPanel(); }
    function deselectAll() { selectedElementId = null; renderCanvas(); showAddPanel(); }

    function showAddPanel() {
        document.getElementById('panel-add').classList.remove('hidden');
        document.getElementById('panel-edit').classList.add('hidden');
    }

    function showEditPanel() {
        const found = getElementAndPage(selectedElementId);
        if (!found) return;
        const el = found.el;
        if (activePage !== found.page) setActivePage(found.page);

        document.getElementById('panel-add').classList.add('hidden');
        document.getElementById('panel-edit').classList.remove('hidden');

        ['group-content', 'group-typography', 'group-dimensions', 'group-image'].forEach(id => {
            const grp = document.getElementById(id);
            if(grp) grp.classList.add('hidden');
        });

        if (['text', 'student_name', 'course_title', 'certificate_body', 'issue_date', 'registration_number'].includes(el.type)) {
            document.getElementById('group-typography').classList.remove('hidden');
            document.getElementById('group-dimensions').classList.remove('hidden');

            setValue('prop-font-family', el.style.fontFamily || 'font-lato');
            setValue('prop-font-size', parseInt(el.style.fontSize) || 18);
            setValue('prop-color', el.style.color || '#000000');
            setValue('prop-width', (el.style.width === 'auto' ? '' : parseInt(el.style.width)));
            setValue('prop-width-range', (el.style.width === 'auto' ? 100 : parseInt(el.style.width)));

            toggleBtnClass('btn-bold', el.style.fontWeight === 'bold');
            toggleBtnClass('btn-italic', el.style.fontStyle === 'italic');

            document.querySelectorAll('.align-btn').forEach(b => b.classList.remove('active'));
            const alignBtn = document.getElementById('align-' + (el.style.textAlign || 'left'));
            if(alignBtn) alignBtn.classList.add('active');

            if (['text', 'course_title', 'certificate_body', 'issue_date', 'registration_number'].includes(el.type)) {
                document.getElementById('group-content').classList.remove('hidden');
                quillEditor.root.innerHTML = el.content || '';
            }
        } else if (['qr', 'seal', 'image', 'executive_seal', 'academic_seal', 'executive_signature', 'academic_signature'].includes(el.type)) {
            document.getElementById('group-image').classList.remove('hidden');
            setValue('prop-img-w', parseInt(el.style.width) || 180);
            setValue('prop-img-h', parseInt(el.style.height) || 80);

            const btnChangeImg = document.getElementById('btn-change-img');
            if (btnChangeImg) {
                if (['executive_signature', 'academic_signature', 'executive_seal', 'academic_seal'].includes(el.type)) {
                    btnChangeImg.classList.add('hidden');
                } else {
                    btnChangeImg.classList.remove('hidden');
                }
            }
        }
    }

    function updateSelectedProp(prop, val) {
        if (!selectedElementId) return;
        const found = getElementAndPage(selectedElementId);
        if (!found) return;
        const el = found.el;

        if (prop === 'width' || prop === 'height') {
            el.style[prop] = (val === 'auto' || val === '') ? 'auto' : val + 'px';
            if(prop === 'width') {
                if(document.getElementById('prop-width').value != val) document.getElementById('prop-width').value = val;
                if(document.getElementById('prop-width-range').value != val) document.getElementById('prop-width-range').value = val;
            }
        } else if (prop === 'fontSize') {
            el.style[prop] = val + 'px';
        } else if (prop === 'content') {
            el.content = val;
        } else {
            el.style[prop] = val;
        }

        if (prop === 'textAlign') showEditPanel();
        renderCanvas();
    }

    function toggleStyle(prop) {
        if (!selectedElementId) return;
        const found = getElementAndPage(selectedElementId);
        if (!found) return;
        const el = found.el;

        if (prop === 'fontWeight') el.style[prop] = (el.style.fontWeight === 'bold') ? 'normal' : 'bold';
        if (prop === 'fontStyle') el.style[prop] = (el.style.fontStyle === 'italic') ? 'normal' : 'italic';

        renderCanvas();
        showEditPanel();
    }

    function removeSelectedElement() {
        if (!selectedElementId) return;
        certState.elements = certState.elements.filter(e => e.id !== selectedElementId);
        certState.elements2 = certState.elements2.filter(e => e.id !== selectedElementId);
        deselectAll();
    }

    window.updateDynamicElements = function() {
        const bodyHTML = `<p>Realizado el <b>${window.courseMeta.startDate}</b> al <b>${window.courseMeta.endDate}</b>, con una duración de <b>${window.courseMeta.hours} horas académicas</b>.</p><p><br></p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.</p>`;
        const dateHTML = `<p>Ayacucho, ${window.courseMeta.endDate}</p>`;
        const titleHTML = window.courseMeta.title;
        const regHTML = `<p>Reg. N° ${window.courseMeta.registrationNumber || 'CP-2024-001'}</p>`;

        [certState.elements, certState.elements2].forEach(list => {
            if (!list) return;
            list.forEach(el => {
                if (el.type === 'certificate_body') el.content = bodyHTML;
                else if (el.type === 'issue_date') el.content = dateHTML;
                else if (el.type === 'course_title') el.content = titleHTML;
                else if (el.type === 'registration_number') el.content = regHTML;
            });
        });

        if (selectedElementId) {
            const found = getElementAndPage(selectedElementId);
            if (found) {
                const el = found.el;
                if (['certificate_body', 'issue_date', 'course_title', 'registration_number'].includes(el.type)) {
                    if (quillEditor && quillEditor.root.innerHTML !== el.content) {
                        quillEditor.root.innerHTML = el.content || '';
                    }
                }
            }
        }

        renderCanvas();
    };

    function renderCanvas() {
        // Hoja 1
        const bgImg = document.getElementById('cert-background');
        const placeholder = document.getElementById('cert-background-placeholder');
        const layer = document.getElementById('elements-layer');

        if (certState.background) {
            bgImg.src = certState.background;
            bgImg.classList.remove('hidden');
            placeholder.classList.add('hidden');
        } else {
            bgImg.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
        layer.innerHTML = '';
        renderElementsToLayer(certState.elements, layer);

        // Hoja 2
        const wrapper2 = document.getElementById('cert-canvas-2-wrapper');
        const c2 = document.getElementById('cert-canvas-2');
        if (certState.pages === 2) {
            if (wrapper2) wrapper2.classList.remove('hidden');
            if (c2) c2.classList.remove('hidden');
            const bgImg2 = document.getElementById('cert-background-2');
            const placeholder2 = document.getElementById('cert-background-placeholder-2');
            const layer2 = document.getElementById('elements-layer-2');

            if (certState.background2) {
                bgImg2.src = certState.background2;
                bgImg2.classList.remove('hidden');
                placeholder2.classList.add('hidden');
            } else {
                bgImg2.classList.add('hidden');
                placeholder2.classList.remove('hidden');
            }
            layer2.innerHTML = '';
            renderElementsToLayer(certState.elements2, layer2);
        } else {
            if (wrapper2) wrapper2.classList.add('hidden');
            if (c2) c2.classList.add('hidden');
        }

        window.updateCertificateInput();
    }

    function renderElementsToLayer(elements, layer) {
        elements.forEach(el => {
            const div = document.createElement('div');
            div.id = el.id;
            div.className = `cert-element ${selectedElementId === el.id ? 'selected' : ''}`;
            div.style.left = el.x + 'px';
            div.style.top = el.y + 'px';

            if (el.style) {
                div.style.fontFamily = getFontFamily(el.style.fontFamily);
                div.style.fontSize = el.style.fontSize;
                div.style.color = el.style.color;
                div.style.fontWeight = el.style.fontWeight;
                div.style.fontStyle = el.style.fontStyle || 'normal';

                if (['text', 'student_name', 'course_title', 'certificate_body', 'issue_date', 'registration_number'].includes(el.type)) {
                    div.style.display = 'block';
                    div.style.whiteSpace = 'normal';
                } else {
                    div.style.display = 'flex';
                    div.style.flexDirection = 'column';
                    if (el.style.textAlign === 'center') div.style.alignItems = 'center';
                    else if (el.style.textAlign === 'right') div.style.alignItems = 'flex-end';
                    else div.style.alignItems = 'flex-start';
                }

                div.style.textAlign = el.style.textAlign;

                if (el.style.width && el.style.width !== 'auto') {
                    div.style.width = el.style.width;
                } else {
                    div.style.width = 'auto';
                    if (!['text', 'student_name', 'course_title', 'certificate_body', 'issue_date', 'registration_number'].includes(el.type)) {
                        div.style.whiteSpace = 'nowrap';
                    }
                }

                if(el.style.height) div.style.height = el.style.height;
            }

            div.onmousedown = (e) => startDrag(e, el.id);

            if (el.type === 'qr') {
                div.innerHTML = '<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=PREVIEW">';
            } else if (['executive_signature', 'academic_signature', 'image', 'seal', 'executive_seal', 'academic_seal'].includes(el.type)) {
                let imgUrl = el.src;
                if (el.type === 'executive_signature') imgUrl = window.courseMeta.executive?.signature || el.src;
                if (el.type === 'academic_signature') imgUrl = window.courseMeta.academic?.signature || el.src;
                if (el.type === 'executive_seal') imgUrl = window.courseMeta.executiveSeal || el.src;
                if (el.type === 'academic_seal') imgUrl = window.courseMeta.academicSeal || el.src;

                if (!el.style.width) el.style.width = '180px';
                if (!el.style.height) el.style.height = (el.type.includes('seal') ? '130px' : '80px');

                if (imgUrl) {
                    div.innerHTML = `<img src="${fixUrl(imgUrl)}" style="width:100%; height:100%; object-fit:contain; display:block; pointer-events:none;">`;
                } else {
                    const label = (el.type === 'executive_signature' || el.type === 'academic_signature') ? 'Sin firma cargada' : 'Sin sello cargado';
                    div.innerHTML = `<div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#9ca3af; font-size:10px; border:1px dashed #d1d5db; border-radius:6px; text-align:center; padding:4px;">${label}</div>`;
                }
            } else if (el.type === 'signature') {
                let sigUrl = window.courseMeta.instructor.signature;
                const size = el.content?.size || 80;
                const imgHtml = (sigUrl && sigUrl !== 'null')
                    ? `<img src="${sigUrl}" style="height:${size}px; object-fit:contain;">`
                    : `<div style="height:${size}px; width:100px; border:1px dashed #ccc; display:flex; align-items:center; justify-content:center; font-size:10px; color:#aaa;">Firma</div>`;

                div.innerHTML = `
                    ${imgHtml}
                    <div style="border-top:1px solid ${el.style.color || '#000'}; width:100%; text-align:center; padding-top:4px;">
                        <div style="font-size:14px; font-weight:bold;">${el.content?.name || 'Instructor'}</div>
                        <div style="font-size:10px;">${el.content?.role || 'Capacitador'}</div>
                    </div>
                `;
            } else {
                div.innerHTML = el.content || 'Texto';
            }

            if (selectedElementId === el.id) {
                const handles = ['nw', 'ne', 'sw', 'se'];
                handles.forEach(h => {
                    const handle = document.createElement('div');
                    handle.className = `resize-handle handle-${h}`;
                    handle.onmousedown = (e) => { e.stopPropagation(); startResize(e, h, el.id); };
                    div.appendChild(handle);
                });

                if (['qr', 'seal', 'image', 'executive_seal', 'academic_seal', 'executive_signature', 'academic_signature'].includes(el.type)) {
                    const curW = parseInt(el.style.width) || div.offsetWidth || 100;
                    const curH = parseInt(el.style.height) || div.offsetHeight || 100;
                    const badge = document.createElement('div');
                    badge.id = 'size-badge-' + el.id;
                    badge.className = 'absolute -bottom-7 left-1/2 -translate-x-1/2 bg-[#001144] text-white text-[9px] font-bold px-2 py-0.5 rounded shadow pointer-events-none whitespace-nowrap z-[120]';
                    badge.innerText = `${curW} × ${curH} px`;
                    div.appendChild(badge);
                }
            }

            layer.appendChild(div);
        });
    }

    function startResize(e, handle, id) {
        e.preventDefault(); e.stopPropagation();
        isResizing = true;
        const found = getElementAndPage(id);
        const el = found.el;
        const div = document.getElementById(id);
        resizeData = { handle, startX: e.clientX, startY: e.clientY, startW: div.offsetWidth, startH: div.offsetHeight, el };
        document.addEventListener('mousemove', onDragMove);
        document.addEventListener('mouseup', onDragEnd);
    }

    function startDrag(e, id) {
        if(e.button !== 0) return;
        e.preventDefault(); e.stopPropagation();
        selectElement(id);
        dragElId = id;
        isDragging = true;
        const elDiv = document.getElementById(id);
        dragOffset.mouseX = e.clientX;
        dragOffset.mouseY = e.clientY;
        dragOffset.elX = parseFloat(elDiv.style.left);
        dragOffset.elY = parseFloat(elDiv.style.top);
        document.addEventListener('mousemove', onDragMove);
        document.addEventListener('mouseup', onDragEnd);
    }

    function onDragMove(e) {
        if (isResizing) {
            const deltaX = (e.clientX - resizeData.startX) / currentZoom;
            const deltaY = (e.clientY - resizeData.startY) / currentZoom;
            let newW = resizeData.startW; let newH = resizeData.startH;

            if (resizeData.handle.includes('e')) newW = resizeData.startW + deltaX;
            if (resizeData.handle.includes('s')) newH = resizeData.startH + deltaY;
            if (resizeData.handle.includes('w')) newW = resizeData.startW - deltaX;
            if (resizeData.handle.includes('n')) newH = resizeData.startH - deltaY;

            if (newW < 20) newW = 20; if (newH < 20) newH = 20;
            if (resizeData.el.type === 'qr') { const s = Math.max(newW, newH); newW = s; newH = s; }

            const intW = Math.round(newW);
            const intH = Math.round(newH);

            resizeData.el.style.width = intW + 'px';
            resizeData.el.style.height = intH + 'px';
            const div = document.getElementById(resizeData.el.id);
            if (div) {
                div.style.width = intW + 'px'; 
                div.style.height = intH + 'px';
                const badge = document.getElementById('size-badge-' + resizeData.el.id);
                if (badge) badge.innerText = `${intW} × ${intH} px`;
            }

            const wInput = document.getElementById('prop-img-w');
            const hInput = document.getElementById('prop-img-h');
            if (wInput) wInput.value = intW;
            if (hInput) hInput.value = intH;
            return;
        }
        if (!isDragging) return;
        const deltaX = (e.clientX - dragOffset.mouseX) / currentZoom;
        const deltaY = (e.clientY - dragOffset.mouseY) / currentZoom;
        const found = getElementAndPage(dragElId);
        const el = found.el;
        el.x = dragOffset.elX + deltaX;
        el.y = dragOffset.elY + deltaY;
        const elDiv = document.getElementById(dragElId);
        elDiv.style.left = el.x + 'px';
        elDiv.style.top = el.y + 'px';
    }

    function onDragEnd() {
        isDragging = false; isResizing = false;
        document.removeEventListener('mousemove', onDragMove);
        document.removeEventListener('mouseup', onDragEnd);
        window.updateCertificateInput();
        renderCanvas();
    }

    function zoomCanvas(delta) { currentZoom = Math.max(0.1, currentZoom + delta); updateZoom(); }

    function updateZoom() {
        const w = 1123 * currentZoom;
        const h = 794 * currentZoom;

        document.getElementById('cert-canvas').style.transform = `scale(${currentZoom})`;
        document.getElementById('cert-canvas-2').style.transform = `scale(${currentZoom})`;

        const w1 = document.getElementById('cert-canvas-wrapper');
        const w2 = document.getElementById('cert-canvas-2-wrapper');

        if (w1) { w1.style.width = w + 'px'; w1.style.height = h + 'px'; }
        if (w2) {
            w2.style.width = w + 'px'; w2.style.height = h + 'px';
            if (certState.pages === 2) w2.classList.remove('hidden');
            else w2.classList.add('hidden');
        }

        document.getElementById('zoom-level-indicator').innerText = Math.round(currentZoom * 100) + '%';
    }

    function fitCanvas() {
        const container = document.getElementById('workspace-container');
        if (!container) return;
        currentZoom = Math.min((container.clientWidth - 100) / 1123, (container.clientHeight - 100) / 794);
        updateZoom();
    }

    function fixUrl(url) {
        if (!url) return null;
        if (url.startsWith('http') || url.startsWith('data:')) return url;
        return url.includes('storage') ? url : '/storage/' + url;
    }

    // Lógica de Media Manager modularizada en admin.partials.certificate-media-modal

    window.updateCertificateInput = function() {
        const input = document.getElementById('certificate_data_input');
        if (input) input.value = JSON.stringify(certState);
    };

    function setValue(id, v) { const el = document.getElementById(id); if(el) el.value = v; }
    function toggleBtnClass(id, active) {
        const el = document.getElementById(id);
        if(el) {
            if(active) { el.classList.add('bg-blue-100', 'text-blue-700', 'border-blue-300'); el.classList.remove('bg-white', 'text-gray-600', 'border-gray-300'); }
            else { el.classList.remove('bg-blue-100', 'text-blue-700', 'border-blue-300'); el.classList.add('bg-white', 'text-gray-600', 'border-gray-300'); }
        }
    }
    function getFontFamily(k) {
        const f = {
            'font-lato': "'Lato', sans-serif",
            'font-merriweather': "'Merriweather', serif",
            'font-pinyon': "'Pinyon Script', cursive",
            'font-greatvibes': "'Great Vibes', cursive",
            'font-montserrat': "'Montserrat', sans-serif",
            'font-opensans': "'Open Sans', sans-serif"
        };
        return f[k] || 'sans-serif';
    }
</script>