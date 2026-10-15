<!-- INPUT OCULTO PARA LA BASE DE DATOS -->
<input type="hidden" name="certificate_data" id="certificate_data_input" value="{{ json_encode($course->certificate_template ?? []) }}">

<!-- LÓGICA PHP PARA URL DE FIRMA -->
@php
    $signatureUrl = null;
    $instructorName = 'Instructor No Asignado';
    if ($course->instructor) {
        $instructorName = $course->instructor->certificate_name ?? $course->instructor->name;
        if ($course->instructor->signature_url) {
            $url = $course->instructor->signature_url;
            if (Str::startsWith($url, 'http')) {
                $signatureUrl = $url;
            } else {
                $filename = basename($url); 
                $signatureUrl = route('admin.instructors.signature', ['filename' => $filename]);
            }
        }
    }
@endphp

<!-- DATOS DEL CURSO PARA JS -->
<script>
    const uniqueTs = Date.now(); 
    window.courseMeta = {
        title: @json($course->title),
        instructor: {
            name: @json($instructorName),
            role: "CAPACITADOR", 
            signature: "{{ $signatureUrl }}" 
        },
        manager: {
            name: "Ing. Fanny Apaza",
            role: "GERENTE DE CAPACITACIONES\nPACHABOL S.R.L.", 
            signature: null 
        },
        studentExample: "JUAN SEBASTIÁN DE LA CRUZ Y MENDOZzaA"
    };

    const mediaRoutes = {
        list: "{{ route('admin.media.list') }}",
        upload: "{{ route('admin.media.upload') }}",
        delete: "{{ route('admin.media.delete') }}"
    };
</script>

<style>
    .cert-element { position: absolute; user-select: none; cursor: grab; border: 1px dashed transparent; transition: border 0.1s; }
    /* Borde punteado suave al pasar el mouse para ver el área */
    .cert-element:hover { border-color: #0AD1C8; background-color: rgba(10, 209, 200, 0.05); }
    
    .cert-element.selected { border: 2px solid #213A57; z-index: 100; cursor: grabbing; background-color: rgba(33, 58, 87, 0.05); }
    /* Indicador de selección */
    .cert-element.selected::after { content: ''; position: absolute; top: -6px; right: -6px; width: 12px; height: 12px; background: #213A57; border-radius: 50%; border: 2px solid white; }
    
    .rich-editor-btn { padding: 4px 8px; border-radius: 4px; color: #4B5563; transition: all 0.2s; }
    .rich-editor-btn:hover { background-color: #E0F9F6; color: #0AD1C8; }
    
    .style-btn.active { background-color: #213A57; color: white; border-color: #213A57; }
    .align-btn.active { background-color: #E0F2F1; color: #00695C; border-color: #00695C; font-weight: bold; }

    .media-tab { cursor: pointer; padding: 10px 20px; border-bottom: 2px solid transparent; font-weight: 600; color: #64748b; }
    .media-tab.active { color: #213A57; border-bottom-color: #213A57; }
    .media-item { position: relative; cursor: pointer; border: 2px solid transparent; border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; background: #f1f5f9; }
    .media-item.selected { border-color: #213A57; box-shadow: 0 0 0 2px rgba(33, 58, 87, 0.2); }
    .media-item .check-icon { display: none; position: absolute; top: 5px; right: 5px; background: #213A57; color: white; border-radius: 50%; padding: 2px; font-size: 12px; z-index: 10; }
    .media-item.selected .check-icon { display: block; }
    .media-img-container { flex: 1; overflow: hidden; position: relative; }
    .media-name { padding: 6px 8px; font-size: 10px; color: #64748b; text-align: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; border-top: 1px solid #e2e8f0; background: white; }

    @import url('https://fonts.googleapis.com/css2?family=Great+Vibes&family=Lato:wght@400;700;900&family=Merriweather:wght@400;700&family=Pinyon+Script&family=Montserrat:wght@400;700&family=Open+Sans:wght@400;700&display=swap');
    .font-lato { font-family: 'Lato', sans-serif; }
    .font-merriweather { font-family: 'Merriweather', serif; }
    .font-pinyon { font-family: 'Pinyon Script', cursive; }
    .font-greatvibes { font-family: 'Great Vibes', cursive; }
    .font-montserrat { font-family: 'Montserrat', sans-serif; }
    .font-opensans { font-family: 'Open Sans', sans-serif; }
</style>

<!-- === CONFIGURACIÓN DE PUBLICACIÓN === -->
<div class="bg-white border border-gray-200 rounded-2xl p-5 mb-6 shadow-sm">
    <h4 class="text-sm font-bold text-[#213A57] mb-3 flex items-center gap-2">
        <i class="ph-bold ph-calendar-check text-[#0AD1C8]"></i> Configuración de Publicación
    </h4>
    <div class="flex flex-col md:flex-row gap-6">
        <label class="flex items-start gap-3 cursor-pointer group">
            <div class="relative flex items-center mt-0.5">
                <input type="radio" name="release_mode" value="completion" class="peer h-4 w-4 border-gray-300 text-[#213A57] focus:ring-[#0AD1C8]" onchange="toggleReleaseMode()">
            </div>
            <div>
                <span class="block text-sm font-bold text-gray-700 group-hover:text-[#213A57]">Automático (Recomendado)</span>
                <span class="text-xs text-gray-500">Se libera al completar el 100%.</span>
            </div>
        </label>

        <label class="flex items-start gap-3 cursor-pointer group">
            <div class="relative flex items-center mt-0.5">
                <input type="radio" name="release_mode" value="scheduled" class="peer h-4 w-4 border-gray-300 text-[#213A57] focus:ring-[#0AD1C8]" onchange="toggleReleaseMode()">
            </div>
            <div>
                <span class="block text-sm font-bold text-gray-700 group-hover:text-[#213A57]">Programar Fecha</span>
                <div id="date-picker-container" class="hidden mt-2">
                    <input type="date" id="release_date_input" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:border-[#0AD1C8] outline-none text-gray-600" onchange="updateReleaseDate()">
                </div>
            </div>
        </label>
    </div>
</div>

<!-- === EDITOR VISUAL === -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-0 border border-gray-200 rounded-2xl overflow-hidden h-[850px] bg-gray-100">
    <!-- PANEL IZQUIERDO -->
    <div class="lg:col-span-3 bg-white border-r border-gray-200 flex flex-col h-full overflow-hidden shadow-xl z-20">
        <div class="p-4 border-b border-gray-100 bg-[#213A57] text-white flex justify-between items-center">
            <div class="flex items-center gap-2">
                <button type="button" onclick="deselectAll()" class="hover:text-[#0AD1C8] transition-colors" title="Volver al menú"><i class="ph-bold ph-arrow-left text-lg"></i></button>
                <h4 class="font-bold text-sm" id="panel-title">Elementos</h4>
            </div>
            <i class="ph-fill ph-puzzle-piece opacity-50"></i>
        </div>
        <div class="flex-1 overflow-y-auto p-5 custom-scrollbar" id="panel-content">
            
            <!-- VISTA AGREGAR -->
            <div id="view-add-elements">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Estructura</p>
                <div class="space-y-3">
                    <button type="button" onclick="openMediaManager('background')" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl flex items-center gap-3 hover:border-[#0AD1C8] hover:shadow-md transition-all group text-left">
                        <div class="w-8 h-8 bg-white rounded flex items-center justify-center text-gray-500 group-hover:text-[#0AD1C8]"><i class="ph-bold ph-image"></i></div>
                        <div><span class="block text-sm font-bold text-gray-700">Fondo</span><span class="text-[10px] text-gray-400">Cambiar imagen</span></div>
                    </button>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mt-6 mb-4">Datos Dinámicos</p>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="addElement('student_name')" class="p-3 bg-white border border-gray-200 rounded-xl flex flex-col items-center justify-center gap-2 hover:border-[#0AD1C8] hover:text-[#0AD1C8] transition-all group">
                        <i class="ph-bold ph-student text-2xl text-gray-400 group-hover:text-[#0AD1C8]"></i><span class="text-xs font-bold text-gray-600 group-hover:text-[#213A57]">Estudiante</span>
                    </button>
                    <button type="button" onclick="addElement('course_title')" class="p-3 bg-white border border-gray-200 rounded-xl flex flex-col items-center justify-center gap-2 hover:border-[#0AD1C8] hover:text-[#0AD1C8] transition-all group">
                        <i class="ph-bold ph-graduation-cap text-2xl text-gray-400 group-hover:text-[#0AD1C8]"></i><span class="text-xs font-bold text-gray-600 group-hover:text-[#213A57]">Título Curso</span>
                    </button>
                    <button type="button" onclick="addElement('text')" class="p-3 bg-white border border-gray-200 rounded-xl flex flex-col items-center justify-center gap-2 hover:border-[#0AD1C8] hover:text-[#0AD1C8] transition-all group">
                        <i class="ph-bold ph-text-t text-2xl text-gray-400 group-hover:text-[#0AD1C8]"></i><span class="text-xs font-bold text-gray-600 group-hover:text-[#213A57]">Texto Libre</span>
                    </button>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mt-6 mb-4">Firmas y Gráficos</p>
                <div class="space-y-3">
                    <button type="button" onclick="addElement('signature')" class="w-full p-3 bg-white border border-gray-200 rounded-xl flex items-center gap-3 hover:border-[#0AD1C8] transition-all group text-left">
                        <div class="w-8 h-8 bg-gray-50 rounded flex items-center justify-center text-gray-500 group-hover:text-[#0AD1C8]"><i class="ph-bold ph-pen-nib"></i></div><div><span class="block text-sm font-bold text-gray-600">Firma Instructor</span><span class="text-[10px] text-gray-400">Auto: Nombre + Cargo</span></div>
                    </button>
                    <button type="button" onclick="addElement('manager_signature')" class="w-full p-3 bg-white border border-gray-200 rounded-xl flex items-center gap-3 hover:border-[#0AD1C8] transition-all group text-left">
                        <div class="w-8 h-8 bg-blue-50 rounded flex items-center justify-center text-blue-500 group-hover:text-[#0AD1C8]"><i class="ph-bold ph-briefcase"></i></div><div><span class="block text-sm font-bold text-gray-600">Firma Gerente</span><span class="text-[10px] text-gray-400">Ing. Fanny Apaza</span></div>
                    </button>
                    <button type="button" onclick="openMediaManager('seal')" class="w-full p-3 bg-white border border-gray-200 rounded-xl flex items-center gap-3 hover:border-[#0AD1C8] transition-all group text-left">
                        <div class="w-8 h-8 bg-gray-50 rounded flex items-center justify-center text-gray-500 group-hover:text-[#0AD1C8]"><i class="ph-bold ph-seal-check"></i></div><span class="text-sm font-bold text-gray-600">Sello / Logo</span>
                    </button>
                    <button type="button" onclick="addElement('qr')" class="w-full p-3 bg-white border border-gray-200 rounded-xl flex items-center gap-3 hover:border-[#0AD1C8] transition-all group text-left">
                        <div class="w-8 h-8 bg-gray-50 rounded flex items-center justify-center text-gray-500 group-hover:text-[#0AD1C8]"><i class="ph-bold ph-qr-code"></i></div><span class="text-sm font-bold text-gray-600">Código QR</span>
                    </button>
                </div>
            </div>

            <!-- VISTA EDITAR -->
            <div id="view-edit-element" class="hidden space-y-6">
                
                <!-- GRUPO: TEXTO / FUENTE -->
                <div id="edit-text-group" class="hidden space-y-4">
                    
                    <!-- ESTILOS (Visible para todo texto) -->
                    <div id="global-text-styles">
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Formato</label>
                        <div class="flex gap-2">
                            <button type="button" onclick="toggleStyle('fontWeight')" id="btn-bold" class="style-btn flex-1 py-2 border border-gray-300 rounded-lg bg-white text-gray-600 transition-colors" title="Negrita"><i class="ph-bold ph-text-b"></i></button>
                            <button type="button" onclick="toggleStyle('fontStyle')" id="btn-italic" class="style-btn flex-1 py-2 border border-gray-300 rounded-lg bg-white text-gray-600 transition-colors" title="Cursiva"><i class="ph-bold ph-text-italic"></i></button>
                            <button type="button" onclick="toggleStyle('textDecoration')" id="btn-underline" class="style-btn flex-1 py-2 border border-gray-300 rounded-lg bg-white text-gray-600 transition-colors" title="Subrayado"><i class="ph-bold ph-text-underline"></i></button>
                        </div>
                    </div>

                    <!-- ALINEACIÓN (VISIBLE PARA ESTUDIANTE Y TODOS LOS TEXTOS) -->
                    <div id="global-text-alignment">
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Alineación</label>
                        <div class="flex gap-2 bg-white border border-gray-300 rounded-lg p-1">
                             <button type="button" onclick="updateSelectedProp('textAlign', 'left')" id="align-left" class="align-btn flex-1 py-1.5 rounded hover:bg-gray-100 text-gray-600 transition-colors" title="Izquierda"><i class="ph-bold ph-text-align-left"></i></button>
                             <button type="button" onclick="updateSelectedProp('textAlign', 'center')" id="align-center" class="align-btn flex-1 py-1.5 rounded hover:bg-gray-100 text-gray-600 transition-colors" title="Centro"><i class="ph-bold ph-text-align-center"></i></button>
                             <button type="button" onclick="updateSelectedProp('textAlign', 'right')" id="align-right" class="align-btn flex-1 py-1.5 rounded hover:bg-gray-100 text-gray-600 transition-colors" title="Derecha"><i class="ph-bold ph-text-align-right"></i></button>
                             <button type="button" onclick="updateSelectedProp('textAlign', 'justify')" id="align-justify" class="align-btn flex-1 py-1.5 rounded hover:bg-gray-100 text-gray-600 transition-colors" title="Justificado"><i class="ph-bold ph-text-align-justify"></i></button>
                        </div>
                    </div>
                    
                    <!-- CONTENIDO HTML (Solo para Texto Libre) -->
                    <div id="wrapper-text-content">
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Contenido HTML</label>
                        <div class="border border-gray-300 rounded-lg overflow-hidden focus-within:border-[#0AD1C8] transition-colors">
                             <div class="flex items-center gap-1 p-1 bg-gray-50 border-b border-gray-200">
                                <button type="button" onclick="formatText('bold')" class="rich-editor-btn" title="Negrita"><i class="ph-bold ph-text-b"></i></button>
                                <button type="button" onclick="formatText('italic')" class="rich-editor-btn" title="Cursiva"><i class="ph-bold ph-text-italic"></i></button>
                                <button type="button" onclick="formatText('underline')" class="rich-editor-btn" title="Subrayado"><i class="ph-bold ph-text-underline"></i></button>
                            </div>
                            <div id="prop-text-content" contenteditable="true" class="p-3 text-sm min-h-[60px] outline-none bg-white" oninput="updateSelectedProp('html', this.innerHTML)"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Tipografía</label>
                            <select id="prop-font-family" class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm bg-white" onchange="updateSelectedProp('fontFamily', this.value)">
                                <option value="font-lato">Lato (Moderno)</option>
                                <option value="font-montserrat">Montserrat (Geométrico)</option>
                                <option value="font-opensans">Open Sans (Limpio)</option>
                                <option value="font-merriweather">Merriweather (Serif)</option>
                                <option value="font-pinyon">Pinyon Script (Elegante)</option>
                                <option value="font-greatvibes">Great Vibes (Cursiva)</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Tamaño Fuente</label>
                            <div class="flex items-center border border-gray-300 rounded-lg bg-white"><input type="number" id="prop-font-size" class="w-full px-2 py-2 border-none rounded-lg text-sm focus:ring-0" oninput="updateSelectedProp('fontSize', this.value + 'px')"><span class="text-xs text-gray-400 pr-2">px</span></div>
                        </div>
                         <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Color</label>
                            <div class="flex items-center gap-2 border border-gray-300 rounded-lg p-1 bg-white h-[38px]"><input type="color" id="prop-color" class="w-8 h-full border-none bg-transparent cursor-pointer rounded" oninput="updateSelectedProp('color', this.value)"></div>
                        </div>
                    </div>

                    <!-- DIMENSIONES DEL CUADRO (Siempre visible para textos) -->
                    <div id="text-box-dimensions" class="border-t border-gray-200 pt-3">
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Ancho del Cuadro (px)</label>
                        <div class="flex items-center border border-gray-300 rounded-lg bg-white">
                            <input type="number" id="prop-text-width" class="w-full px-2 py-2 border-none rounded-lg text-sm focus:ring-0" oninput="updateSelectedProp('width', this.value + 'px')">
                            <span class="text-xs text-gray-400 pr-2">px</span>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1">Define el área disponible. Útil para centrar nombres largos.</p>
                    </div>
                </div>

                <!-- GRUPO: FIRMA AVANZADA -->
                <div id="edit-signature-group" class="hidden space-y-6">
                    <!-- (Contenido igual que antes) -->
                    <div class="space-y-3 p-3 bg-gray-50 rounded-xl border border-gray-200">
                        <label class="text-xs font-bold text-gray-400 uppercase">Datos</label>
                        <div><label class="text-xs font-bold text-gray-500 block mb-1">Nombre</label><input type="text" id="prop-sig-name" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[#0AD1C8] outline-none" oninput="updateSignatureProp('name', this.value)"></div>
                        <div><label class="text-xs font-bold text-gray-500 block mb-1">Cargo</label><textarea id="prop-sig-role" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-[#0AD1C8] outline-none resize-none" oninput="updateSignatureProp('role', this.value)"></textarea></div>
                    </div>
                    <div class="space-y-3 p-3 bg-blue-50 rounded-xl border border-blue-100">
                        <label class="text-xs font-bold text-[#213A57] uppercase flex items-center gap-2"><i class="ph-bold ph-text-aa"></i> Estilo Nombre</label>
                        <div class="grid grid-cols-2 gap-2">
                            <select id="prop-sig-name-font" class="w-full px-2 py-1.5 border border-blue-200 rounded-lg text-xs bg-white" onchange="updateSignatureStyle('name', 'fontFamily', this.value)">
                                <option value="font-lato">Lato</option><option value="font-montserrat">Montserrat</option><option value="font-opensans">Open Sans</option><option value="font-merriweather">Merriweather</option><option value="font-pinyon">Pinyon</option><option value="font-greatvibes">Great Vibes</option>
                            </select>
                            <div class="flex items-center border border-blue-200 rounded-lg bg-white"><input type="number" id="prop-sig-name-size" class="w-full px-2 py-1.5 border-none rounded-lg text-xs focus:ring-0" oninput="updateSignatureStyle('name', 'fontSize', this.value + 'px')"><span class="text-[10px] text-gray-400 pr-2">px</span></div>
                        </div>
                        <div class="flex gap-2">
                            <div class="flex items-center gap-2 border border-blue-200 rounded-lg p-1 bg-white h-[32px] w-12 justify-center"><input type="color" id="prop-sig-name-color" class="w-6 h-full border-none bg-transparent cursor-pointer rounded" oninput="updateSignatureStyle('name', 'color', this.value)"></div>
                            <div class="flex-1 flex gap-1"><button type="button" onclick="toggleSigStyle('name', 'fontWeight')" id="btn-sig-name-bold" class="flex-1 border border-blue-200 rounded-lg bg-white hover:bg-blue-100 text-xs"><i class="ph-bold ph-text-b"></i></button><button type="button" onclick="toggleSigStyle('name', 'fontStyle')" id="btn-sig-name-italic" class="flex-1 border border-blue-200 rounded-lg bg-white hover:bg-blue-100 text-xs"><i class="ph-bold ph-text-italic"></i></button></div>
                        </div>
                    </div>
                    <div class="space-y-3 p-3 bg-gray-50 rounded-xl border border-gray-200">
                        <label class="text-xs font-bold text-gray-500 uppercase flex items-center gap-2"><i class="ph-bold ph-briefcase"></i> Estilo Cargo</label>
                        <div class="grid grid-cols-2 gap-2">
                            <select id="prop-sig-role-font" class="w-full px-2 py-1.5 border border-gray-300 rounded-lg text-xs bg-white" onchange="updateSignatureStyle('role', 'fontFamily', this.value)">
                                <option value="font-lato">Lato</option><option value="font-montserrat">Montserrat</option><option value="font-opensans">Open Sans</option><option value="font-merriweather">Merriweather</option><option value="font-pinyon">Pinyon</option><option value="font-greatvibes">Great Vibes</option>
                            </select>
                            <div class="flex items-center border border-gray-300 rounded-lg bg-white"><input type="number" id="prop-sig-role-size" class="w-full px-2 py-1.5 border-none rounded-lg text-xs focus:ring-0" oninput="updateSignatureStyle('role', 'fontSize', this.value + 'px')"><span class="text-[10px] text-gray-400 pr-2">px</span></div>
                        </div>
                        <div class="flex gap-2">
                            <div class="flex items-center gap-2 border border-gray-300 rounded-lg p-1 bg-white h-[32px] w-12 justify-center"><input type="color" id="prop-sig-role-color" class="w-6 h-full border-none bg-transparent cursor-pointer rounded" oninput="updateSignatureStyle('role', 'color', this.value)"></div>
                            <div class="flex-1 flex gap-1"><button type="button" onclick="toggleSigStyle('role', 'fontWeight')" id="btn-sig-role-bold" class="flex-1 border border-gray-300 rounded-lg bg-white hover:bg-gray-100 text-xs"><i class="ph-bold ph-text-b"></i></button><button type="button" onclick="toggleSigStyle('role', 'fontStyle')" id="btn-sig-role-italic" class="flex-1 border border-gray-300 rounded-lg bg-white hover:bg-gray-100 text-xs"><i class="ph-bold ph-text-italic"></i></button></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-3 pt-2 border-t border-gray-100">
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Imagen de Firma</label>
                        <button type="button" id="btn-change-sig-image" onclick="openMediaManager('seal')" class="w-full py-2 bg-[#213A57] text-white rounded-lg text-xs font-bold hover:bg-[#0B6477] transition-colors">Cambiar Imagen</button>
                         <div class="mt-2"><label class="text-xs font-bold text-gray-500 uppercase block mb-1">Tamaño Imagen (px)</label><input type="number" id="prop-sig-size" class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm" oninput="updateSignatureProp('size', this.value)"></div>
                    </div>
                </div>

                <!-- GRUPO: IMAGEN / QR / SELLO -->
                <div id="edit-image-group" class="hidden space-y-4">
                    <button type="button" id="btn-change-image" onclick="openMediaManager('seal')" class="w-full py-2 bg-[#213A57] text-white rounded-lg text-xs font-bold hover:bg-[#0B6477] transition-colors">Cambiar Imagen</button>
                    <div class="grid grid-cols-2 gap-3">
                         <div><label class="text-xs font-bold text-gray-500 uppercase block mb-1">Ancho (px)</label><input type="number" id="prop-img-width" class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm" oninput="updateSelectedProp('width', this.value + 'px')"></div>
                         <div><label class="text-xs font-bold text-gray-500 uppercase block mb-1">Alto (px)</label><input type="number" id="prop-img-height" class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm" oninput="updateSelectedProp('height', this.value + 'px')"></div>
                    </div>
                </div>

                <!-- Botón Eliminar -->
                <div class="pt-6 border-t border-gray-100">
                    <button type="button" onclick="removeSelectedElement()" class="w-full py-2.5 text-red-500 border border-red-200 bg-red-50 hover:bg-red-100 rounded-xl text-sm font-bold flex items-center justify-center gap-2 transition-colors"><i class="ph-bold ph-trash"></i> Eliminar Elemento</button>
                </div>
            </div>
        </div>
    </div>

    <!-- LIENZO -->
    <div class="lg:col-span-9 bg-gray-200 overflow-auto flex items-center justify-center p-8 relative" id="canvas-container">
        <div id="cert-canvas" class="bg-white shadow-2xl relative overflow-hidden transition-all" style="width: 1123px; height: 794px; transform-origin: center top;">
            <img id="cert-background" src="" class="absolute inset-0 w-full h-full object-fill pointer-events-none z-0 hidden">
            <div id="cert-background-placeholder" class="absolute inset-0 flex items-center justify-center text-gray-300 z-0 border-4 border-dashed border-gray-300 m-8 rounded-xl"><div class="text-center"><i class="ph-duotone ph-image text-6xl mb-2"></i><p class="text-sm font-bold">Lienzo Vacío</p></div></div>
            <div id="elements-layer" class="absolute inset-0 z-10"></div>
        </div>
        <div class="absolute bottom-4 right-4 bg-white rounded-lg shadow-lg flex items-center border border-gray-200 z-20">
            <button type="button" onclick="zoomCanvas(-0.1)" class="p-2 hover:bg-gray-100 text-gray-600"><i class="ph-bold ph-minus"></i></button><span class="text-xs font-bold px-2 text-gray-500" id="zoom-label">100%</span><button type="button" onclick="zoomCanvas(0.1)" class="p-2 hover:bg-gray-100 text-gray-600"><i class="ph-bold ph-plus"></i></button>
        </div>
    </div>
</div>

<!-- MEDIA MODAL (Sin cambios) -->
<div id="media-modal" class="fixed inset-0 z-[200] hidden bg-[#213A57]/90 backdrop-blur-sm flex items-center justify-center p-6">
    <div class="bg-white rounded-2xl w-full max-w-5xl h-[80vh] flex flex-col shadow-2xl overflow-hidden relative animate-fade-in-up">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-[#213A57] text-lg" id="media-title">Biblioteca de Medios</h3>
            <div class="flex items-center gap-4">
                <div class="relative"><i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i><input type="text" id="media-search" oninput="renderMediaGallery()" placeholder="Buscar archivo..." class="pl-9 pr-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:border-[#213A57] outline-none"></div>
                <button type="button" onclick="closeMediaManager()" class="text-gray-400 hover:text-red-500"><i class="ph-bold ph-x text-xl"></i></button>
            </div>
        </div>
        <div class="flex border-b border-gray-200 px-4 bg-white">
            <div class="media-tab active" onclick="switchMediaTab('browse')" id="tab-browse">Biblioteca de medios</div>
            <div class="media-tab" onclick="switchMediaTab('upload')" id="tab-upload">Subir archivos</div>
        </div>
        <div class="flex-1 overflow-hidden relative bg-gray-100">
            <div id="view-browse" class="absolute inset-0 overflow-y-auto p-6"><div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4" id="media-gallery"><div class="col-span-full flex justify-center p-8"><div class="w-8 h-8 border-4 border-gray-300 border-t-[#213A57] rounded-full animate-spin"></div></div></div><div id="empty-library-msg" class="hidden flex flex-col items-center justify-center h-full text-gray-400"><i class="ph-duotone ph-images text-6xl mb-2"></i><p>No hay imágenes. Sube alguna.</p></div></div>
            <div id="view-upload" class="absolute inset-0 hidden flex flex-col items-center justify-center p-10 bg-gray-50">
                 <div class="border-4 border-dashed border-gray-300 rounded-3xl p-12 text-center max-w-lg w-full hover:border-[#0AD1C8] hover:bg-white transition-all group"><i class="ph-duotone ph-cloud-arrow-up text-6xl text-gray-300 group-hover:text-[#0AD1C8] mb-4 transition-colors"></i><h4 class="text-xl font-bold text-gray-600 mb-2">Arrastra archivos para subirlos</h4><p class="text-gray-400 mb-6 text-sm">o haz clic para seleccionar</p><label class="px-6 py-3 bg-[#213A57] text-white rounded-xl font-bold cursor-pointer hover:bg-[#0B6477] transition-colors shadow-lg shadow-[#213A57]/20">Seleccionar Archivos<input type="file" id="media-upload-input" class="hidden" accept="image/*" onchange="handleNewFileUpload(this)"></label></div><p class="mt-6 text-xs text-gray-400">Tamaño máximo de archivo de subida: 2 MB.</p>
            </div>
        </div>
        <div class="p-4 border-t border-gray-200 bg-white flex justify-between items-center z-10">
            <div class="flex items-center gap-3"><button type="button" id="btn-delete-media" onclick="deleteSelectedMedia()" class="text-red-500 text-sm font-bold hover:bg-red-50 px-3 py-2 rounded-lg transition-colors hidden"><i class="ph-bold ph-trash"></i> Borrar permanentemente</button></div>
            <div class="flex items-center gap-3"><span id="selected-count" class="text-xs text-gray-500 font-bold hidden">1 seleccionado</span><button type="button" id="btn-insert-media" onclick="insertSelectedMedia()" disabled class="px-6 py-2.5 bg-[#213A57] text-white rounded-xl font-bold disabled:opacity-50 disabled:cursor-not-allowed hover:bg-[#0B6477] transition-all">Usar imagen</button></div>
        </div>
    </div>
</div>

<script>
    let certState = { background: null, elements: [], release_mode: 'completion', release_date: null };
    let selectedElementId = null;
    let currentZoom = 1;
    let mediaTarget = null;
    let selectedMediaUrl = null; 
    let galleryAssets = []; 
    const defaultAssets = ['https://img.freepik.com/free-vector/elegant-certificate-template-golden-frame_1435-857.jpg','https://img.freepik.com/free-vector/modern-certificate-template-blue-geometric-design_1435-1160.jpg'];

    document.addEventListener("DOMContentLoaded", function() {
        const savedData = @json($course->certificate_template ?? null);
        if (savedData && typeof savedData === 'object') {
            certState = {
                background: savedData.background || null,
                elements: Array.isArray(savedData.elements) ? savedData.elements : [],
                release_mode: savedData.release_mode || 'completion',
                release_date: savedData.release_date || null
            };
        }
        
        const rMode = document.querySelector(`input[name="release_mode"][value="${certState.release_mode}"]`);
        if(rMode) rMode.checked = true;
        if(certState.release_date) document.getElementById('release_date_input').value = certState.release_date;
        toggleReleaseMode();

        fitCanvasToScreen();
        renderCanvas();
        updateCertificateInput();
    });

    function toggleReleaseMode() {
        const mode = document.querySelector('input[name="release_mode"]:checked').value;
        const dateContainer = document.getElementById('date-picker-container');
        certState.release_mode = mode;
        if (mode === 'scheduled') { dateContainer.classList.remove('hidden'); } 
        else { dateContainer.classList.add('hidden'); certState.release_date = null; document.getElementById('release_date_input').value = ''; }
        updateCertificateInput();
    }

    function updateReleaseDate() {
        const val = document.getElementById('release_date_input').value;
        certState.release_date = val;
        updateCertificateInput();
    }

    function fitCanvasToScreen() { currentZoom = 1; applyZoom(); }

    function renderCanvas() {
        const layer = document.getElementById('elements-layer');
        const bgImg = document.getElementById('cert-background');
        const bgPlace = document.getElementById('cert-background-placeholder');
        if (certState.background) { bgImg.src = certState.background; bgImg.classList.remove('hidden'); bgPlace.classList.add('hidden'); } 
        else { bgImg.classList.add('hidden'); bgPlace.classList.remove('hidden'); }

        layer.innerHTML = '';
        certState.elements.forEach(el => {
            const domEl = document.createElement('div');
            domEl.id = el.id;
            domEl.className = `cert-element absolute ${selectedElementId === el.id ? 'selected' : ''}`;
            domEl.style.left = el.x + 'px';
            domEl.style.top = el.y + 'px';
            domEl.onmousedown = (e) => startDrag(e, el.id);

            let contentHtml = '';
            if (['text', 'student_name', 'course_title', 'signature', 'manager_signature'].includes(el.type)) {
                domEl.style.fontFamily = getFontFamily(el.style.fontFamily);
                domEl.style.fontSize = el.style.fontSize || '20px';
                domEl.style.color = el.style.color || '#000000';
                domEl.style.fontWeight = el.style.fontWeight || 'normal';
                domEl.style.fontStyle = el.style.fontStyle || 'normal';
                domEl.style.textDecoration = el.style.textDecoration || 'none';
                
                // [MODIFICACIÓN CLAVE] - APLICAR ESTILOS DE CAJA
                // Aplicar Ancho y Alineación a todos los textos
                domEl.style.width = el.style.width || 'auto'; 
                domEl.style.textAlign = el.style.textAlign || 'left';
                domEl.style.whiteSpace = 'nowrap'; 
                if(el.style.width && el.style.width !== 'auto') domEl.style.whiteSpace = 'normal'; // Wrap si hay ancho definido
                
                // Debug log
                console.log(`[DEBUG] Renderizando Elemento: ${el.type} | Ancho: ${el.style.width} | Alineación: ${el.style.textAlign}`);

                let text = el.content || 'Texto';
                if(el.type === 'student_name') text = window.courseMeta.studentExample;
                if(el.type === 'course_title') text = window.courseMeta.title;

                if (el.type === 'text') {
                    contentHtml = `<div class="rich-content">${el.content || 'Doble click'}</div>`;
                } else if (el.type === 'signature' || el.type === 'manager_signature') {
                    domEl.className += ' flex flex-col items-center justify-center';
                    domEl.style.whiteSpace = 'normal'; 
                    domEl.style.textAlign = 'center';

                    let defaultSigSrc = null;
                    if(el.type === 'signature') defaultSigSrc = window.courseMeta.instructor.signature;
                    if(el.type === 'manager_signature') defaultSigSrc = window.courseMeta.manager.signature;

                    let sigSrc = el.content?.signature_img || defaultSigSrc;
                    let sigSize = el.content?.size || 100;
                    let imgPart = '';
                    if(sigSrc && sigSrc !== 'null' && sigSrc !== '') {
                        let srcToRender = sigSrc;
                        if (!sigSrc.startsWith('data:')) { let srcClean = sigSrc.split('?')[0]; srcToRender = srcClean + '?t=' + window.uniqueTs; }
                        imgPart = `<img src="${srcToRender}" style="height: ${sigSize}px; margin-bottom: -10px;" class="pointer-events-none block" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">`;
                        imgPart += `<div style="display:none; color:red; font-size:10px;">Error Carga</div>`;
                    } else {
                        imgPart = `<div style="height:${sigSize}px; display:flex; align-items:end; color:#cbd5e1; font-style:italic; font-size:11px;">(Sin firma)</div>`;
                    }

                    let defName = (el.type === 'signature') ? window.courseMeta.instructor.name : window.courseMeta.manager.name;
                    let defRole = (el.type === 'signature') ? window.courseMeta.instructor.role : window.courseMeta.manager.role;
                    let displayName = el.content?.name || defName;
                    let rawRole = el.content?.role || defRole;
                    let displayRole = rawRole.replace(/\n/g, '<br>');

                    let ns = el.content?.nameStyle || { fontSize: '18px', color: '#000000', fontFamily: 'font-lato', fontWeight: 'bold' };
                    let rs = el.content?.roleStyle || { fontSize: '14px', color: '#666666', fontFamily: 'font-lato', fontWeight: 'normal' };
                    let nameStyleStr = `font-family: ${getFontFamily(ns.fontFamily)}; font-size: ${ns.fontSize}; color: ${ns.color}; font-weight: ${ns.fontWeight}; font-style: ${ns.fontStyle || 'normal'};`;
                    let roleStyleStr = `font-family: ${getFontFamily(rs.fontFamily)}; font-size: ${rs.fontSize}; color: ${rs.color}; font-weight: ${rs.fontWeight}; font-style: ${rs.fontStyle || 'normal'}; opacity: 0.9; line-height: 1.2;`;

                    contentHtml = `<div class="flex flex-col items-center">${imgPart}<div class="border-t border-current w-48 mt-1" style="color: ${ns.color}"></div><p class="mt-1" style="${nameStyleStr}">${displayName}</p><p class="" style="${roleStyleStr}">${displayRole}</p></div>`;
                } else {
                    contentHtml = `<span>${text}</span>`;
                }
            } else if (['image', 'seal', 'qr'].includes(el.type)) {
                let src = el.src;
                if (el.type === 'qr') src = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=Certificado-Validacion';
                domEl.style.width = el.style.width || '100px'; domEl.style.height = el.style.height || '100px';
                contentHtml = `<img src="${src}" class="w-full h-full object-contain pointer-events-none">`;
            }
            domEl.innerHTML = contentHtml;
            layer.appendChild(domEl);
        });
        updateCertificateInput();
    }

    function addElement(type) {
        console.log("[DEBUG] Agregando elemento:", type);
        const id = 'el_' + Date.now();
        let defaultStyle = { fontSize: '18px', color: '#000000', fontFamily: 'font-lato', fontWeight: 'normal', width: 'auto', height: 'auto', textAlign: 'left' };
        
        // [MODIFICACIÓN CLAVE] - INICIALIZACIÓN POR DEFECTO PARA NOMBRES
        if (type === 'student_name') {
            defaultStyle.fontSize = '30px'; 
            defaultStyle.fontFamily = 'font-lato'; 
            defaultStyle.color = '#000000';
            defaultStyle.width = '600px'; // ANCHO FIJO INICIAL
            defaultStyle.textAlign = 'center'; // CENTRADO INICIAL
        }
        if (type === 'course_title') { 
            defaultStyle.fontSize = '25px'; 
            defaultStyle.fontWeight = 'bold'; 
            defaultStyle.color = '#000000';
            defaultStyle.width = '700px'; 
            defaultStyle.textAlign = 'center';
        }

        const newEl = { id: id, type: type, x: 400, y: 300, style: defaultStyle, content: (type === 'text') ? 'Texto Libre' : {}, src: '' };

        if (type === 'signature' || type === 'manager_signature') {
            newEl.content = { 
                name: (type === 'signature') ? window.courseMeta.instructor.name : window.courseMeta.manager.name, 
                role: (type === 'signature') ? window.courseMeta.instructor.role : window.courseMeta.manager.role, 
                size: 100,
                nameStyle: { fontSize: '18px', color: '#000000', fontFamily: 'font-lato', fontWeight: 'bold' },
                roleStyle: { fontSize: '14px', color: '#555555', fontFamily: 'font-lato', fontWeight: 'normal' }
            };
        }
        if (type === 'qr') { newEl.style.width = '150px'; newEl.style.height = '150px'; }

        certState.elements.push(newEl);
        selectElement(id);
        renderCanvas();
    }

    function selectElement(id) { selectedElementId = id; renderCanvas(); updateSidebar(); }
    function deselectAll() { selectedElementId = null; renderCanvas(); updateSidebar(); }
    function removeSelectedElement() { if(selectedElementId) { certState.elements = certState.elements.filter(e=>e.id!==selectedElementId); deselectAll(); } }
    
    function updateSidebar() {
        const addView = document.getElementById('view-add-elements');
        const editView = document.getElementById('view-edit-element');
        const title = document.getElementById('panel-title');
        
        if (!selectedElementId) { addView.classList.remove('hidden'); editView.classList.add('hidden'); title.innerText = 'Elementos'; return; }

        const el = certState.elements.find(e => e.id === selectedElementId);
        if (!el) return;

        addView.classList.add('hidden'); editView.classList.remove('hidden'); title.innerText = 'Editar: ' + mapTypeName(el.type);
        document.getElementById('edit-text-group').classList.add('hidden'); document.getElementById('edit-image-group').classList.add('hidden'); document.getElementById('edit-signature-group').classList.add('hidden'); document.getElementById('wrapper-text-content').style.display = 'none'; document.getElementById('text-box-dimensions').classList.add('hidden');

        if (['text', 'student_name', 'course_title', 'signature', 'manager_signature'].includes(el.type)) {
            if (el.type === 'signature' || el.type === 'manager_signature') {
                document.getElementById('edit-signature-group').classList.remove('hidden');
                const btnChange = document.getElementById('btn-change-sig-image');
                if (el.type === 'signature') btnChange.style.display = 'none'; else btnChange.style.display = 'block';

                let defName = (el.type === 'signature') ? window.courseMeta.instructor.name : window.courseMeta.manager.name;
                let defRole = (el.type === 'signature') ? window.courseMeta.instructor.role : window.courseMeta.manager.role;
                document.getElementById('prop-sig-name').value = el.content.name || defName;
                document.getElementById('prop-sig-role').value = el.content.role || defRole;
                document.getElementById('prop-sig-size').value = el.content.size || 100;

                let ns = el.content.nameStyle || { fontSize: '18px', color: '#000000', fontFamily: 'font-lato', fontWeight: 'bold' };
                document.getElementById('prop-sig-name-font').value = ns.fontFamily || 'font-lato';
                document.getElementById('prop-sig-name-size').value = parseInt(ns.fontSize);
                document.getElementById('prop-sig-name-color').value = ns.color;
                
                let rs = el.content.roleStyle || { fontSize: '14px', color: '#555555', fontFamily: 'font-lato', fontWeight: 'normal' };
                document.getElementById('prop-sig-role-font').value = rs.fontFamily || 'font-lato';
                document.getElementById('prop-sig-role-size').value = parseInt(rs.fontSize);
                document.getElementById('prop-sig-role-color').value = rs.color;
            } else {
                document.getElementById('edit-text-group').classList.remove('hidden');
                const globalStyles = document.getElementById('global-text-styles');
                const miniWord = document.getElementById('wrapper-text-content');

                // [MODIFICACIÓN CLAVE] - MOSTRAR DIMENSIONES SIEMPRE PARA TEXTOS
                document.getElementById('text-box-dimensions').classList.remove('hidden');
                // IMPORTANTE: Asegurarnos de que el valor en el input refleje el estado
                document.getElementById('prop-text-width').value = parseInt(el.style.width) || '';
                
                // Actualizar estado visual de los botones de alineación
                document.querySelectorAll('.align-btn').forEach(b => b.classList.remove('active'));
                const align = el.style.textAlign || 'left';
                if(document.getElementById('align-'+align)) document.getElementById('align-'+align).classList.add('active');

                if (el.type === 'text') { globalStyles.classList.add('hidden'); miniWord.style.display = 'block'; document.getElementById('prop-text-content').innerHTML = el.content || 'Texto'; } 
                else {
                    globalStyles.classList.remove('hidden'); miniWord.style.display = 'none';
                    const btnBold = document.getElementById('btn-bold'); const btnItalic = document.getElementById('btn-italic'); const btnUnderline = document.getElementById('btn-underline');
                    if (el.style.fontWeight === 'bold') btnBold.classList.add('active'); else btnBold.classList.remove('active');
                    if (el.style.fontStyle === 'italic') btnItalic.classList.add('active'); else btnItalic.classList.remove('active');
                    if (el.style.textDecoration === 'underline') btnUnderline.classList.add('active'); else btnUnderline.classList.remove('active');
                }
                document.getElementById('prop-font-size').value = parseInt(el.style.fontSize);
                document.getElementById('prop-color').value = el.style.color;
                document.getElementById('prop-font-family').value = el.style.fontFamily;
            }
        } 
        else if (['seal', 'image', 'qr'].includes(el.type)) {
            document.getElementById('edit-image-group').classList.remove('hidden');
            document.getElementById('btn-change-image').style.display = (el.type === 'qr') ? 'none' : 'block';
            document.getElementById('prop-img-width').value = parseInt(el.style.width);
            document.getElementById('prop-img-height').value = parseInt(el.style.height);
        }
    }
    
    function toggleStyle(prop) { if(!selectedElementId) return; const index = certState.elements.findIndex(e => e.id === selectedElementId); const el = certState.elements[index]; let newVal; if (prop === 'fontWeight') newVal = (el.style.fontWeight === 'bold') ? 'normal' : 'bold'; if (prop === 'fontStyle') newVal = (el.style.fontStyle === 'italic') ? 'normal' : 'italic'; if (prop === 'textDecoration') newVal = (el.style.textDecoration === 'underline') ? 'none' : 'underline'; el.style[prop] = newVal; renderCanvas(); updateSidebar(); }
    
    function updateSelectedProp(prop, value) { 
        if(!selectedElementId) return; 
        console.log("[DEBUG] Actualizando Propiedad:", prop, value);
        const index = certState.elements.findIndex(e => e.id === selectedElementId); 
        if (prop === 'html') certState.elements[index].content = value; 
        else certState.elements[index].style[prop] = value; 
        renderCanvas(); 
        updateSidebar();
    }
    
    function updateSignatureProp(field, value) { if(!selectedElementId) return; const idx = certState.elements.findIndex(e=>e.id===selectedElementId); if(!certState.elements[idx].content) certState.elements[idx].content = {}; certState.elements[idx].content[field] = value; renderCanvas(); }
    function updateSignatureStyle(target, prop, value) { if(!selectedElementId) return; const idx = certState.elements.findIndex(e=>e.id===selectedElementId); let el = certState.elements[idx]; let styleKey = target === 'name' ? 'nameStyle' : 'roleStyle'; if(!el.content[styleKey]) el.content[styleKey] = {}; el.content[styleKey][prop] = value; renderCanvas(); }
    function toggleSigStyle(target, prop) { if(!selectedElementId) return; const idx = certState.elements.findIndex(e=>e.id===selectedElementId); let el = certState.elements[idx]; let styleKey = target === 'name' ? 'nameStyle' : 'roleStyle'; if(!el.content[styleKey]) el.content[styleKey] = {}; let current = el.content[styleKey]; let newVal; if (prop === 'fontWeight') newVal = (current.fontWeight === 'bold') ? 'normal' : 'bold'; if (prop === 'fontStyle') newVal = (current.fontStyle === 'italic') ? 'normal' : 'italic'; el.content[styleKey][prop] = newVal; renderCanvas(); }
    function formatText(cmd) { document.execCommand(cmd, false, null); updateSelectedProp('html', document.getElementById('prop-text-content').innerHTML); }
    
    let isDragging=false, dragOffset={x:0,y:0}, dragElId=null;
    function startDrag(e,id) {
        e.preventDefault(); e.stopPropagation(); selectElement(id); dragElId = id; isDragging = true;
        const canvas = document.getElementById('cert-canvas'); const r = canvas.getBoundingClientRect();
        const scaleX = canvas.offsetWidth / r.width; const scaleY = canvas.offsetHeight / r.height;
        dragOffset.x = (e.clientX - r.left) * scaleX; dragOffset.y = (e.clientY - r.top) * scaleY;
        document.addEventListener('mousemove', onDragMove); document.addEventListener('mouseup', onDragEnd);
    }
    function onDragMove(e) {
        if (!isDragging) return;
        const canvas = document.getElementById('cert-canvas'); const rect = canvas.getBoundingClientRect();
        const scaleX = canvas.offsetWidth / rect.width; const scaleY = canvas.offsetHeight / rect.height;
        let nx = (e.clientX - rect.left) * scaleX - dragOffset.x; let ny = (e.clientY - rect.top) * scaleY - dragOffset.y;
        const idx = certState.elements.findIndex(el => el.id === dragElId); certState.elements[idx].x = nx; certState.elements[idx].y = ny;
        const el = document.getElementById(dragElId); el.style.left = nx + 'px'; el.style.top = ny + 'px';
    }
    function onDragEnd() { isDragging=false; document.removeEventListener('mousemove',onDragMove); document.removeEventListener('mouseup',onDragEnd); updateCertificateInput(); }

    function zoomCanvas(a) { currentZoom+=a; applyZoom(); }
    function applyZoom() { document.getElementById('cert-canvas').style.transform=`scale(${currentZoom})`; document.getElementById('zoom-label').innerText=Math.round(currentZoom*100)+'%'; }
    
    function updateCertificateInput() { 
        const input = document.getElementById('certificate_data_input');
        if(input) input.value = JSON.stringify(certState); 
    }
    window.updateCertificateInput = updateCertificateInput;
    
    // Media Manager (Mismo de siempre)
    function openMediaManager(target) { mediaTarget = target; document.getElementById('media-modal').classList.remove('hidden'); document.getElementById('media-title').innerText = (target === 'background') ? 'Fondo del Certificado' : 'Seleccionar Imagen'; document.getElementById('media-search').value = ''; selectedMediaUrl = null; updateInsertButton(); switchMediaTab('browse'); loadMediaFromBackend(); }
    function loadMediaFromBackend() { const grid = document.getElementById('media-gallery'); grid.innerHTML = '<div class="col-span-full flex justify-center p-8"><div class="w-8 h-8 border-4 border-gray-300 border-t-[#213A57] rounded-full animate-spin"></div></div>'; fetch(mediaRoutes.list).then(response => response.json()).then(data => { galleryAssets = data; if (galleryAssets.length === 0) galleryAssets = [...defaultAssets]; renderMediaGallery(); }).catch(error => { console.error("Error cargando medios:", error); galleryAssets = [...defaultAssets]; renderMediaGallery(); }); }
    function closeMediaManager() { document.getElementById('media-modal').classList.add('hidden'); }
    function switchMediaTab(tab) { document.querySelectorAll('.media-tab').forEach(t => t.classList.remove('active')); document.getElementById('tab-'+tab).classList.add('active'); document.getElementById('view-browse').classList.add('hidden'); document.getElementById('view-upload').classList.add('hidden'); document.getElementById('view-'+tab).classList.remove('hidden'); }
    function renderMediaGallery() { const grid = document.getElementById('media-gallery'); const emptyMsg = document.getElementById('empty-library-msg'); const searchVal = document.getElementById('media-search').value.toLowerCase(); const filteredAssets = galleryAssets.filter(item => { const url = typeof item === 'string' ? item : (item.url || ''); const name = (typeof item === 'object' && item.name) ? item.name.toLowerCase() : url.split('/').pop().toLowerCase(); return name.includes(searchVal); }); grid.innerHTML = ''; if (filteredAssets.length === 0) { emptyMsg.classList.remove('hidden'); } else { emptyMsg.classList.add('hidden'); filteredAssets.forEach((item) => { const url = typeof item === 'string' ? item : (item.url || ''); const fileName = (typeof item === 'object' && item.name) ? item.name : url.split('/').pop(); const div = document.createElement('div'); div.className = `media-item ${selectedMediaUrl === url ? 'selected' : ''}`; div.onclick = () => selectMediaItem(url); div.innerHTML = `<div class="media-img-container"><img src="${url}" class="w-full h-full object-cover"></div><div class="media-name" title="${fileName}">${fileName}</div><div class="check-icon"><i class="ph-bold ph-check"></i></div>`; grid.appendChild(div); }); } }
    function selectMediaItem(url) { if (selectedMediaUrl === url) selectedMediaUrl = null; else selectedMediaUrl = url; renderMediaGallery(); updateInsertButton(); }
    function updateInsertButton() { const btn = document.getElementById('btn-insert-media'); const btnDel = document.getElementById('btn-delete-media'); const count = document.getElementById('selected-count'); if (selectedMediaUrl) { btn.disabled = false; btnDel.classList.remove('hidden'); count.classList.remove('hidden'); } else { btn.disabled = true; btnDel.classList.add('hidden'); count.classList.add('hidden'); } }
    function handleNewFileUpload(input) { if(input.files && input.files[0]) { const file = input.files[0]; const label = input.closest('label'); const originalText = label.innerHTML; label.innerHTML = `<div class="flex flex-col items-center"><div class="w-6 h-6 border-2 border-white border-t-transparent rounded-full animate-spin"></div><span class="text-xs mt-2 text-white">Subiendo...</span></div>`; label.classList.add('pointer-events-none', 'opacity-70'); const formData = new FormData(); formData.append('file', file); const tokenMeta = document.querySelector('meta[name="csrf-token"]'); const token = tokenMeta ? tokenMeta.getAttribute('content') : '{{ csrf_token() }}'; formData.append('_token', token); fetch(mediaRoutes.upload, { method: 'POST', body: formData, headers: { 'X-CSRF-TOKEN': token } }).then(response => response.json()).then(data => { if(data.url) { loadMediaFromBackend(); switchMediaTab('browse'); } else { alert('Error: ' + (data.error || 'No se recibió URL')); } }).catch(error => { console.error(error); alert('Error al subir imagen.'); }).finally(() => { label.innerHTML = originalText; label.classList.remove('pointer-events-none', 'opacity-70'); input.value = ''; }); } }
    function deleteSelectedMedia() { if (!selectedMediaUrl) return; if (!confirm('¿Estás seguro de eliminar esta imagen permanentemente del servidor?')) return; const tokenMeta = document.querySelector('meta[name="csrf-token"]'); const token = tokenMeta ? tokenMeta.getAttribute('content') : '{{ csrf_token() }}'; fetch(mediaRoutes.delete, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token }, body: JSON.stringify({ url: selectedMediaUrl }) }).then(res => res.json()).then(data => { if(data.success) { selectedMediaUrl = null; updateInsertButton(); loadMediaFromBackend(); } else { alert('No se pudo eliminar: ' + (data.error || 'Error desconocido')); } }).catch(err => console.error(err)); }
    function insertSelectedMedia() { if (!selectedMediaUrl) return; if (mediaTarget === 'background') { certState.background = selectedMediaUrl; renderCanvas(); } else if (mediaTarget === 'seal') { if (selectedElementId) { const elIndex = certState.elements.findIndex(e => e.id === selectedElementId); const el = certState.elements[elIndex]; if (el.type === 'signature' || el.type === 'manager_signature') { if (!el.content) el.content = {}; el.content.signature_img = selectedMediaUrl; } else { el.src = selectedMediaUrl; } } else { const id = 'el_' + Date.now(); certState.elements.push({id, type:'seal', x:350, y:250, src:selectedMediaUrl, style:{width:'150px', height:'150px'}}); selectElement(id); } renderCanvas(); } closeMediaManager(); updateSidebar(); }
    function mapTypeName(t) { return {text:'Texto', student_name:'Estudiante', course_title:'Curso', seal:'Imagen', signature:'Firma Instructor', manager_signature:'Firma Gerente', qr:'QR'}[t] || 'Elemento'; }
    function getFontFamily(k) { const f={'font-lato':"'Lato',sans-serif", 'font-merriweather':"'Merriweather',serif", 'font-pinyon':"'Pinyon Script',cursive", 'font-greatvibes':"'Great Vibes',cursive", 'font-montserrat':"'Montserrat',sans-serif", 'font-opensans':"'Open Sans',sans-serif"}; return f[k]||'sans-serif'; }
    function updateEvaluationInput() { console.log('updateEvaluationInput bypassed'); }
</script>