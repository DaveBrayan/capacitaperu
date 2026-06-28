<!-- Usamos CKEditor 5 para soporte de tablas y pegado desde Word -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

<style>
    /* --- CORRECCIONES PARA CKEDITOR EN MODAL --- */
    
    /* 1. Ajuste de Z-Index para que los tooltips/dropdowns se vean sobre el modal */
    :root {
        --ck-z-default: 100;
        --ck-z-modal: 10000;
    }

    /* 2. Estilos para que el editor ocupe el 100% del contenedor padre y haga scroll interno */
    #content-modal .ck.ck-editor {
        display: flex;
        flex-direction: column;
        height: 100%; /* Ocupar todo el alto disponible */
    }

    #content-modal .ck.ck-editor__main {
        flex-grow: 1; /* Ocupar el espacio restante */
        overflow-y: auto; /* Scroll SOLO en el contenido */
    }

    #content-modal .ck.ck-content {
        min-height: 100%;
        border: none !important; /* Quitar borde duplicado */
        padding: 2rem !important;
    }

    #content-modal .ck.ck-toolbar {
        border-radius: 0 !important; /* Quitar bordes redondeados superiores para que encaje */
        border-left: none !important;
        border-right: none !important;
        border-top: none !important;
    }

    /* Estilos generales de tablas */
    .ck-content table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1em;
    }
    .ck-content td, .ck-content th {
        border: 1px solid #ddd;
        padding: 8px;
    }
</style>

<!-- ============================================== -->
<!-- 1. SECCIÓN DE RECURSOS                         -->
<!-- ============================================== -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
    <h3 class="text-xl font-bold text-[#213A57] mb-2">Recursos y Materiales</h3>
    <p class="text-sm text-gray-500 mb-6">Enlaces generales del curso para que el estudiante descargue.</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Campo: Materiales -->
        <div class="space-y-2">
            <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                <i class="ph-fill ph-folder-open text-[#0AD1C8]"></i> Materiales (Drive / Dropbox)
            </label>
            <textarea name="resources[materials]" rows="3" 
                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#0AD1C8] focus:ring-4 focus:ring-[#0AD1C8]/10 outline-none transition-all text-sm"
                placeholder="Pega aquí los enlaces a carpetas de Drive, PDFs, etc...">{{ old('resources.materials', $course->resources['materials'] ?? '') }}</textarea>
            <p class="text-[10px] text-gray-400">Ej: Link a carpeta de Google Drive con libros.</p>
        </div>

        <!-- Campo: Presentaciones -->
        <div class="space-y-2">
            <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                <i class="ph-fill ph-presentation-chart text-[#0AD1C8]"></i> Presentaciones (Diapositivas)
            </label>
            <textarea name="resources[presentations]" rows="3" 
                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#0AD1C8] focus:ring-4 focus:ring-[#0AD1C8]/10 outline-none transition-all text-sm"
                placeholder="Enlaces a Canva, PowerPoint online o PDFs de slides...">{{ old('resources.presentations', $course->resources['presentations'] ?? '') }}</textarea>
            <p class="text-[10px] text-gray-400">Ej: Link a presentación de Canva.</p>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- 2. SECCIÓN DE PLANIFICACIÓN (ACTUALIZADO)      -->
<!-- ============================================== -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
    <div class="flex justify-between items-start mb-4">
        <div>
            <h3 class="text-xl font-bold text-[#213A57] mb-2">Planificación Académica</h3>
            <p class="text-sm text-gray-500">Cronograma, silabo y grupo de comunicación.</p>
        </div>
        
        <!-- Botón para abrir el editor -->
        <button type="button" onclick="openPlanningModal()" class="px-4 py-2 bg-blue-50 text-blue-600 font-bold text-sm rounded-lg hover:bg-blue-100 transition-colors border border-blue-100 flex items-center gap-2">
            <i class="ph-bold ph-pencil-simple"></i> Editar Planificación
        </button>
    </div>

    <!-- VISTA PREVIA SIMPLIFICADA (SOLO STATUS) -->
    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 flex items-center justify-between" id="planning_status_container">
        @if(!empty($course->metadata['planning']))
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="ph-fill ph-check-circle text-xl"></i>
                </div>
                <div>
                    <p class="font-bold text-[#213A57] text-sm">Contenido de Planificación Creado</p>
                    <p class="text-xs text-gray-500">Haz clic en editar para ver los detalles.</p>
                </div>
            </div>
        @else
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center">
                    <i class="ph-fill ph-warning-circle text-xl"></i>
                </div>
                <div>
                    <p class="font-bold text-gray-500 text-sm">Sin planificación definida</p>
                    <p class="text-xs text-gray-400">Agrega el cronograma del curso.</p>
                </div>
            </div>
        @endif

        <!-- Mostrar si hay grupo de WhatsApp configurado -->
        <div id="whatsapp_status_preview" class="{{ !empty($course->metadata['whatsapp_group']) ? '' : 'hidden' }}">
            <span class="px-3 py-1 bg-green-50 text-green-700 text-xs font-bold rounded-lg border border-green-200 flex items-center gap-1">
                <i class="ph-fill ph-whatsapp-logo"></i> Grupo Activo
            </span>
        </div>
    </div>

    <!-- Inputs ocultos que se envían al servidor -->
    <input type="hidden" name="metadata[planning]" id="planning_input" value="{{ $course->metadata['planning'] ?? '' }}">
    <input type="hidden" name="metadata[whatsapp_group]" id="whatsapp_group_input" value="{{ $course->metadata['whatsapp_group'] ?? '' }}">
</div>

<!-- ============================================== -->
<!-- 3. CONSTRUCTOR DE CONTENIDO (BUILDER)          -->
<!-- ============================================== -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-xl font-bold text-[#213A57]">Constructor de Contenido</h3>
            <p class="text-sm text-gray-500">Organiza los módulos, lecciones y evaluaciones.</p>
        </div>
        <!-- Botón seguro para añadir módulo -->
        <button type="button" onclick="addModule()" class="px-4 py-2 bg-[#E0F9F6] text-[#0B6477] font-bold rounded-lg hover:bg-[#0AD1C8] hover:text-white transition-all text-sm flex items-center gap-2 border border-[#0AD1C8]/20">
            <i class="ph-bold ph-plus"></i> Añadir Módulo
        </button>
    </div>

    <div id="modules-container" class="space-y-6 min-h-[200px]">
        <!-- Mensaje por defecto -->
        <div class="text-center py-12 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50" id="empty-builder-msg">
            <i class="ph-duotone ph-list-plus text-4xl text-gray-300 mb-2"></i>
            <p class="text-gray-400 text-sm">Añade tu primer módulo para comenzar.</p>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODALES Y UTILIDADES                           -->
<!-- ============================================== -->

<!-- MODAL EDITOR DE CONTENIDO (CKEDITOR + WHATSAPP) -->
<div id="content-modal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-[#213A57]/90 backdrop-blur-sm transition-opacity" onclick="closeContentModal()"></div>
    <div class="fixed inset-0 z-[110] w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-5xl border border-white/10 flex flex-col h-[85vh]">
                
                <div class="bg-gray-50 px-6 py-4 flex justify-between items-center shrink-0 border-b border-gray-200">
                    <div>
                        <h3 class="text-lg font-bold text-[#213A57]">Editor de Contenido</h3>
                        <p class="text-gray-500 text-xs" id="content-modal-subtitle">Lección: ...</p>
                    </div>
                    <button type="button" onclick="closeContentModal()" class="text-gray-400 hover:text-red-500 p-2 rounded-full transition-colors">
                        <i class="ph-bold ph-x text-xl"></i>
                    </button>
                </div>

                <!-- CAMPO ADICIONAL: SOLO PARA PLANIFICACIÓN -->
                <div id="planning-extra-fields" class="hidden bg-green-50 px-6 py-3 border-b border-green-100 flex items-center gap-4 shrink-0">
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-green-800 mb-1 flex items-center gap-1">
                            <i class="ph-fill ph-whatsapp-logo"></i> Link Grupo de WhatsApp
                        </label>
                        <input type="text" id="modal_whatsapp_link" class="w-full px-3 py-2 rounded-lg border border-green-200 text-sm focus:border-green-500 outline-none" placeholder="https://chat.whatsapp.com/...">
                    </div>
                    <div class="text-xs text-green-700 max-w-xs leading-tight">
                        Este enlace se mostrará a los estudiantes para que se unan al grupo oficial del curso.
                    </div>
                </div>

                <!-- Contenedor Flex para CKEditor (Sin overflow-hidden aquí para permitir tooltips, pero manejado por CSS interno) -->
                <div class="flex-1 bg-white flex flex-col relative" style="overflow: hidden;">
                    <!-- Contenedor para CKEditor -->
                    <textarea id="editor-container" class="h-full"></textarea>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-200 shrink-0">
                    <button type="button" onclick="saveContentChanges()" class="px-6 py-2 bg-[#213A57] text-white font-bold rounded-lg hover:bg-[#0B6477] transition-all">
                        Guardar Contenido
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CONSTRUCTOR DE QUIZ -->
<div id="quiz-modal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-[#213A57]/90 backdrop-blur-sm transition-opacity" onclick="closeQuizModal()"></div>
    <div class="fixed inset-0 z-[110] w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-4xl border border-white/10 flex flex-col max-h-[90vh]">
                
                <div class="bg-purple-600 px-6 py-4 flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <i class="ph-bold ph-list-checks"></i> Configuración de Evaluación
                        </h3>
                        <p class="text-purple-200 text-xs" id="quiz-modal-subtitle">Editando cuestionario</p>
                    </div>
                    <button type="button" onclick="closeQuizModal()" class="text-white hover:bg-white/20 p-2 rounded-full transition-colors">
                        <i class="ph-bold ph-x text-xl"></i>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 p-4 bg-purple-50 rounded-xl border border-purple-100">
                        <div>
                            <label class="block text-xs font-bold text-purple-800 uppercase mb-1">Disponible Desde</label>
                            <input type="datetime-local" id="quiz-start" class="w-full px-3 py-2 rounded-lg border border-purple-200 text-sm focus:border-purple-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-purple-800 uppercase mb-1">Disponible Hasta</label>
                            <input type="datetime-local" id="quiz-end" class="w-full px-3 py-2 rounded-lg border border-purple-200 text-sm focus:border-purple-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-purple-800 uppercase mb-1">Nota Total</label>
                            <input type="number" id="quiz-score" class="w-full px-3 py-2 rounded-lg border border-purple-200 text-sm focus:border-purple-500 outline-none font-bold text-purple-700 bg-white" readonly value="0">
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-bold text-gray-700">Preguntas</h4>
                        <div class="flex gap-2">
                            <button type="button" onclick="addQuizQuestion('single')" class="px-3 py-1.5 bg-gray-100 hover:bg-purple-100 text-gray-600 hover:text-purple-700 text-xs font-bold rounded-lg transition-colors border border-gray-200 hover:border-purple-200">
                                + Selección Única
                            </button>
                            <button type="button" onclick="addQuizQuestion('multiple')" class="px-3 py-1.5 bg-gray-100 hover:bg-purple-100 text-gray-600 hover:text-purple-700 text-xs font-bold rounded-lg transition-colors border border-gray-200 hover:border-purple-200">
                                + Selección Múltiple
                            </button>
                            <button type="button" onclick="addQuizQuestion('open')" class="px-3 py-1.5 bg-gray-100 hover:bg-purple-100 text-gray-600 hover:text-purple-700 text-xs font-bold rounded-lg transition-colors border border-gray-200 hover:border-purple-200">
                                + Abierta
                            </button>
                        </div>
                    </div>

                    <div id="quiz-questions-container" class="space-y-4 min-h-[150px]"></div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-200 shrink-0">
                    <button type="button" onclick="saveQuizChanges()" class="px-6 py-2 bg-purple-600 text-white font-bold rounded-lg hover:bg-purple-700 transition-all shadow-lg shadow-purple-500/20">
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- NUEVO: MODAL PARA COPIAR ASISTENCIA (BONITO Y SEGURO) -->
<div id="attendance-modal" class="fixed inset-0 z-[120] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-[#213A57]/60 backdrop-blur-sm transition-opacity" onclick="closeAttendanceModal()"></div>
    <div class="fixed inset-0 z-[130] w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-center shadow-2xl transition-all w-full max-w-md p-6 border border-gray-200">
                
                <div class="w-12 h-12 bg-[#E0F9F6] rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ph-bold ph-link text-2xl text-[#0AD1C8]"></i>
                </div>
                
                <h3 class="text-xl font-bold text-[#213A57] mb-2">Enlace de Asistencia</h3>
                <p class="text-sm text-gray-500 mb-6">Comparte este enlace con tus estudiantes para que registren su asistencia.</p>

                <div class="relative mb-6">
                    <input type="text" id="attendance-link-input" readonly 
                        class="w-full pl-4 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-600 text-sm focus:border-[#0AD1C8] outline-none">
                    
                    <button type="button" onclick="copyLinkToClipboard()" 
                        class="absolute right-2 top-1/2 -translate-y-1/2 p-2 hover:bg-gray-200 rounded-lg text-[#0AD1C8] transition-colors" title="Copiar">
                        <i class="ph-bold ph-copy text-lg"></i>
                    </button>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeAttendanceModal()" class="flex-1 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">
                        Cerrar
                    </button>
                    <button type="button" onclick="copyLinkToClipboard()" class="flex-1 py-2.5 bg-[#213A57] text-white font-bold rounded-xl hover:bg-[#0B6477] transition-colors">
                        Copiar Enlace
                    </button>
                </div>

                <div id="copy-feedback" class="absolute top-4 right-4 bg-green-500 text-white text-xs px-3 py-1 rounded-full opacity-0 transition-opacity pointer-events-none">
                    ¡Copiado!
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    let modules = [];
    let ckEditorInstance = null; // Instancia del editor CKEditor
    let currentQuizModuleIndex = null;
    let currentQuizLessonIndex = null;
    let tempQuizData = {};
    let currentContentModuleIndex = null;
    let currentContentLessonIndex = null;
    
    // --- VARIABLE PARA CONTROLAR EL EDITOR (LECCIÓN O PLANIFICACIÓN) ---
    let editorTarget = null; // 'lesson' o 'planning'

    let finalExamData = @json($course->evaluation_config['final_exam_data'] ?? ['questions' => []]);
    if (!finalExamData.questions) finalExamData.questions = [];
    window.isFinalExamMode = false;

    // --- INICIALIZACIÓN ---
    document.addEventListener("DOMContentLoaded", function() {
        
        // Inicializar CKEditor 5
        ClassicEditor
            .create(document.querySelector('#editor-container'), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo' ],
                table: {
                    contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
                }
            })
            .then(editor => {
                ckEditorInstance = editor;
            })
            .catch(error => {
                console.error('Error al inicializar CKEditor:', error);
            });

        // Cargar datos
        const savedTopics = @json($course->topics->load('lessons') ?? []);

        if (savedTopics.length > 0) {
            modules = savedTopics.map(topic => ({
                id: topic.id,
                title: topic.title,
                lessons: topic.lessons.map(lesson => ({
                    id: lesson.id,
                    title: lesson.title,
                    type: lesson.type,
                    video_url: lesson.video_url,
                    duration: lesson.duration_minutes, 
                    is_preview: !!lesson.is_preview,
                    content: lesson.content, 
                    hide_video: lesson.video_source === 'hidden',
                    content_order: (lesson.video_source === 'text_first' || lesson.order_index === 99) ? 'text_first' : 'video_first',
                    quiz_data: lesson.type === 'quiz' ? (lesson.attachments || {}) : {}
                }))
            }));
            renderModules();
        }
        
        try { if(typeof updateEvaluationInput === 'function') updateEvaluationInput(); } catch(e) {}
    });

    const currentCourseSlug = "{{ $course->slug ?? '' }}";

    // --- RENDERIZADO PRINCIPAL ---
    function renderModules() {
        const container = document.getElementById('modules-container');
        const emptyMsg = document.getElementById('empty-builder-msg');
        container.innerHTML = '';

        if (modules.length === 0) { if(emptyMsg) container.appendChild(emptyMsg); return; }

        modules.forEach((module, mIndex) => {
            const topicId = module.id || 'new'; 
            const isNew = topicId === 'new';
            const linkClass = isNew ? 'opacity-50 cursor-not-allowed border-gray-200 text-gray-400' : 'border-[#0AD1C8]/30 text-[#0AD1C8] hover:bg-[#E0F9F6] cursor-pointer';
            const onclickAction = isNew ? "alert('Guarda el curso primero para generar el enlace.')" : `openAttendanceModal('${currentCourseSlug}', '${topicId}')`;

            const moduleHtml = `
                <div class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50 transition-all hover:shadow-md group/module">
                    <div class="p-4 flex flex-wrap items-center gap-3 bg-white border-b border-gray-200">
                        <div class="cursor-move text-gray-300 hover:text-[#213A57]"><i class="ph-bold ph-dots-six-vertical text-xl"></i></div>
                        
                        <div class="flex-1 min-w-[200px]">
                            <input type="text" value="${module.title}" onchange="updateModule(${mIndex}, 'title', this.value)" class="w-full font-bold text-[#213A57] text-lg bg-transparent outline-none placeholder-gray-400 focus:bg-gray-50 rounded px-2 py-1 transition-colors" placeholder="Nombre del Módulo">
                        </div>
                        
                        <button type="button" onclick="${onclickAction}" class="text-xs font-bold border px-3 py-1.5 rounded-lg flex items-center gap-2 transition-colors ${linkClass}">
                            <i class="ph-bold ph-link"></i> Link Asistencia
                        </button>

                        <div class="h-6 w-px bg-gray-300 mx-2 hidden sm:block"></div>

                        <div class="flex items-center gap-2">
                            <button type="button" onclick="addLesson(${mIndex}, 'video')" class="text-xs font-bold text-[#213A57] hover:text-[#0AD1C8] uppercase tracking-wider flex items-center gap-1 bg-gray-100 hover:bg-white px-2 py-1 rounded border border-transparent hover:border-gray-200 transition-all">
                                <i class="ph-bold ph-play-circle"></i> Clase
                            </button>
                            <button type="button" onclick="addLesson(${mIndex}, 'quiz')" class="text-xs font-bold text-[#213A57] hover:text-[#0AD1C8] uppercase tracking-wider flex items-center gap-1 bg-gray-100 hover:bg-white px-2 py-1 rounded border border-transparent hover:border-gray-200 transition-all">
                                <i class="ph-bold ph-list-checks"></i> Quiz
                            </button>
                        </div>

                        <button type="button" onclick="removeModule(${mIndex})" class="text-gray-400 hover:text-red-500 ml-2 transition-colors">
                            <i class="ph-bold ph-trash text-lg"></i>
                        </button>
                    </div>
                    
                    <div class="p-2 space-y-2">
                        ${module.lessons.map((lesson, lIndex) => `
                            <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-100 shadow-sm ml-0 sm:ml-8 group relative transition-all hover:border-[#0AD1C8]/30">
                                <div class="w-1 h-full absolute left-0 top-0 bottom-0 bg-${lesson.type === 'quiz' ? 'purple-500' : 'blue-500'} rounded-l-lg"></div>
                                <div class="cursor-move text-gray-300"><i class="ph-bold ph-dots-six-vertical"></i></div>
                                
                                <div class="text-[10px] uppercase font-bold px-2 py-0.5 rounded ${lesson.type === 'quiz' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'}">
                                    ${lesson.type === 'quiz' ? 'EXAMEN' : 'CLASE'}
                                </div>

                                <div class="flex-1"><input type="text" value="${lesson.title}" onchange="updateLesson(${mIndex}, ${lIndex}, 'title', this.value)" class="w-full text-sm text-gray-700 bg-transparent outline-none placeholder-gray-400 focus:bg-gray-50 rounded px-2" placeholder="Título"></div>
                                
                                <button type="button" onclick="toggleLessonDetails(${mIndex}, ${lIndex})" class="text-gray-400 hover:text-[#213A57] p-1 rounded hover:bg-gray-100"><i class="ph-bold ph-gear"></i></button>
                                <button type="button" onclick="removeLesson(${mIndex}, ${lIndex})" class="text-gray-300 hover:text-red-500 p-1 rounded hover:bg-red-50 transition-colors"><i class="ph-bold ph-x"></i></button>
                            </div>
                            
                            <div id="lesson-details-${mIndex}-${lIndex}" class="hidden ml-0 sm:ml-8 p-4 bg-gray-50 rounded-lg border border-gray-200 text-sm mb-2 mt-[-5px] border-t-0 rounded-t-none relative z-0 shadow-inner">
                                ${lesson.type === 'video' ? `
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div class="${lesson.hide_video ? 'opacity-50 pointer-events-none' : ''}">
                                                <label class="block text-xs font-bold text-gray-500 mb-1">URL del Video</label>
                                                <input type="text" value="${lesson.video_url || ''}" onchange="updateLesson(${mIndex}, ${lIndex}, 'video_url', this.value)" class="w-full px-3 py-2 rounded border border-gray-300 bg-white focus:border-[#0AD1C8] outline-none transition-colors" placeholder="Youtube / Vimeo">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 mb-1">Duración (min)</label>
                                                <input type="number" value="${lesson.duration || ''}" onchange="updateLesson(${mIndex}, ${lIndex}, 'duration', this.value)" class="w-full px-3 py-2 rounded border border-gray-300 bg-white focus:border-[#0AD1C8] outline-none transition-colors">
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-gray-200">
                                            <div>
                                                <span class="text-xs font-bold text-gray-500 block">Contenido de Texto</span>
                                                <span class="text-[10px] text-gray-400">${lesson.content ? 'Contenido agregado' : 'Sin contenido'}</span>
                                            </div>
                                            <button type="button" onclick="openContentModal(${mIndex}, ${lIndex})" class="px-4 py-2 bg-blue-50 text-blue-600 font-bold text-xs rounded-lg hover:bg-blue-100 transition-colors border border-blue-100 flex items-center gap-2">
                                                <i class="ph-bold ph-pencil-simple"></i> Editar Contenido
                                            </button>
                                        </div>

                                        <div class="flex flex-wrap gap-6 items-center bg-white p-3 rounded-lg border border-gray-100">
                                            <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" ${lesson.is_preview ? 'checked' : ''} onchange="updateLesson(${mIndex}, ${lIndex}, 'is_preview', this.checked)" class="rounded text-[#0AD1C8] focus:ring-[#0AD1C8]"><span class="text-xs text-gray-600 font-medium">Vista Previa (Gratis)</span></label>
                                            <div class="h-4 w-px bg-gray-300"></div>
                                            <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" ${lesson.hide_video ? 'checked' : ''} onchange="updateLesson(${mIndex}, ${lIndex}, 'hide_video', this.checked); renderModules();" class="rounded text-[#0AD1C8] focus:ring-[#0AD1C8]"><span class="text-xs text-gray-600 font-medium">Solo Texto (Ocultar Video)</span></label>
                                            
                                            ${!lesson.hide_video ? `
                                                <div class="flex items-center gap-2 ml-auto">
                                                    <span class="text-xs text-gray-400 font-medium">Orden:</span>
                                                    <select onchange="updateLesson(${mIndex}, ${lIndex}, 'content_order', this.value)" class="text-xs border border-gray-200 rounded px-2 py-1 bg-gray-50 outline-none">
                                                        <option value="video_first" ${lesson.content_order === 'video_first' ? 'selected' : ''}>Video primero</option>
                                                        <option value="text_first" ${lesson.content_order === 'text_first' ? 'selected' : ''}>Texto primero</option>
                                                    </select>
                                                </div>
                                            ` : ''}
                                        </div>
                                    </div>
                                ` : `
                                    <div class="text-center p-4">
                                        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mx-auto mb-2"><i class="ph-duotone ph-list-checks text-2xl"></i></div>
                                        <p class="text-gray-800 font-bold text-sm mb-1">Configuración del Cuestionario</p>
                                        <div class="flex justify-center gap-4 text-xs text-gray-500 mb-4">
                                            <span><i class="ph-bold ph-star"></i> ${countQuizPoints(lesson.quiz_data?.questions)} pts</span>
                                            <span><i class="ph-bold ph-list-numbers"></i> ${lesson.quiz_data?.questions?.length || 0} pregs</span>
                                        </div>
                                        <button type="button" onclick="openQuizModal(${mIndex}, ${lIndex})" class="px-4 py-2 bg-purple-600 text-white text-xs font-bold rounded-lg hover:bg-purple-700 transition-colors shadow-lg shadow-purple-200">Configurar Quiz</button>
                                    </div>
                                `}
                            </div>
                        `).join('')}
                        
                        ${module.lessons.length === 0 ? '<div class="text-center py-4 text-xs text-gray-400 italic border-2 border-dashed border-gray-100 rounded-lg mx-8">Este módulo no tiene lecciones aún.</div>' : ''}
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', moduleHtml);
        });
    }

    // --- NUEVO: FUNCIONES DEL MODAL DE ASISTENCIA ---
    function openAttendanceModal(courseSlug, topicId) {
        const url = `{{ url('/asistencia') }}/${courseSlug}/${topicId}`;
        document.getElementById('attendance-link-input').value = url;
        document.getElementById('attendance-modal').classList.remove('hidden');
    }

    function closeAttendanceModal() {
        document.getElementById('attendance-modal').classList.add('hidden');
    }

    function copyLinkToClipboard() {
        const input = document.getElementById('attendance-link-input');
        
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(input.value).then(() => showCopyFeedback());
        } else {
            input.select();
            input.setSelectionRange(0, 99999);
            document.execCommand('copy');
            showCopyFeedback();
        }
    }

    function showCopyFeedback() {
        const feedback = document.getElementById('copy-feedback');
        feedback.classList.remove('opacity-0');
        setTimeout(() => feedback.classList.add('opacity-0'), 2000);
    }

    // --- MODALES DE CONTENIDO (AHORA DUAL: PLANIFICACIÓN O LECCIÓN) ---
    
    // Abrir modal para Planificación
    function openPlanningModal() {
        editorTarget = 'planning'; 
        const content = document.getElementById('planning_input').value || '';
        const whatsappLink = document.getElementById('whatsapp_group_input').value || '';
        
        if(ckEditorInstance) ckEditorInstance.setData(content);
        
        // Mostrar campo WhatsApp en el modal
        document.getElementById('planning-extra-fields').classList.remove('hidden');
        document.getElementById('modal_whatsapp_link').value = whatsappLink;

        document.getElementById('content-modal-subtitle').innerText = "Planificación Académica y Grupo";
        document.getElementById('content-modal').classList.remove('hidden');
    }

    // Abrir modal para Lección
    function openContentModal(mIndex, lIndex) {
        editorTarget = 'lesson'; 
        currentContentModuleIndex = mIndex;
        currentContentLessonIndex = lIndex;
        
        const content = modules[mIndex].lessons[lIndex].content || '';
        
        if(ckEditorInstance) ckEditorInstance.setData(content);

        // Ocultar campo WhatsApp (no es necesario en lecciones)
        document.getElementById('planning-extra-fields').classList.add('hidden');

        document.getElementById('content-modal-subtitle').innerText = `Lección: ${modules[mIndex].lessons[lIndex].title}`;
        document.getElementById('content-modal').classList.remove('hidden');
    }

    function closeContentModal() {
        document.getElementById('content-modal').classList.add('hidden');
        editorTarget = null;
    }

    function saveContentChanges() {
        // Obtener datos del editor
        const html = ckEditorInstance ? ckEditorInstance.getData() : '';
        
        if (editorTarget === 'planning') {
            // Guardar HTML en input oculto de planificación
            document.getElementById('planning_input').value = html;
            
            // Guardar WhatsApp en input oculto
            const waLink = document.getElementById('modal_whatsapp_link').value;
            document.getElementById('whatsapp_group_input').value = waLink;

            // Actualizar vista previa simplificada (SOLO MOSTRAR SI HAY O NO HAY)
            const statusContainer = document.getElementById('planning_status_container');
            const hasContent = html && html.trim().length > 0;
            const hasWa = waLink && waLink.trim().length > 0;

            let statusHtml = '';
            if (hasContent) {
                statusHtml = `
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center"><i class="ph-fill ph-check-circle text-xl"></i></div>
                        <div><p class="font-bold text-[#213A57] text-sm">Contenido de Planificación Creado</p><p class="text-xs text-gray-500">Haz clic en editar para ver los detalles.</p></div>
                    </div>
                `;
            } else {
                statusHtml = `
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center"><i class="ph-fill ph-warning-circle text-xl"></i></div>
                        <div><p class="font-bold text-gray-500 text-sm">Sin planificación definida</p><p class="text-xs text-gray-400">Agrega el cronograma del curso.</p></div>
                    </div>
                `;
            }

            // Agregar indicador de WhatsApp si existe
            if (hasWa) {
                statusHtml += `
                    <div id="whatsapp_status_preview">
                        <span class="px-3 py-1 bg-green-50 text-green-700 text-xs font-bold rounded-lg border border-green-200 flex items-center gap-1">
                            <i class="ph-fill ph-whatsapp-logo"></i> Grupo Activo
                        </span>
                    </div>
                `;
            }

            statusContainer.innerHTML = statusHtml;

        } else if (editorTarget === 'lesson') {
            // Guardar en el array de módulos (lección)
            modules[currentContentModuleIndex].lessons[currentContentLessonIndex].content = html;
            renderModules(); // Refrescar UI de módulos
        }
        
        closeContentModal();
    }

    function openFinalExamSetup() {
        window.isFinalExamMode = true; 
        tempQuizData = JSON.parse(JSON.stringify(finalExamData));
        if (!tempQuizData.questions) tempQuizData.questions = [];
        document.getElementById('quiz-start').value = document.getElementById('exam_start_date')?.value || '';
        document.getElementById('quiz-end').value = document.getElementById('exam_end_date')?.value || '';
        document.getElementById('quiz-modal-subtitle').innerText = "EXAMEN FINAL DEL CURSO";
        renderQuizQuestions();
        document.getElementById('quiz-modal').classList.remove('hidden');
    }

    function openQuizModal(mIndex, lIndex) {
        window.isFinalExamMode = false;
        currentQuizModuleIndex = mIndex;
        currentQuizLessonIndex = lIndex;
        const existingData = modules[mIndex].lessons[lIndex].quiz_data || {};
        tempQuizData = JSON.parse(JSON.stringify(existingData));
        if (!tempQuizData.questions) tempQuizData.questions = [];
        document.getElementById('quiz-start').value = tempQuizData.start_date || '';
        document.getElementById('quiz-end').value = tempQuizData.end_date || '';
        document.getElementById('quiz-modal-subtitle').innerText = modules[mIndex].lessons[lIndex].title;
        renderQuizQuestions();
        document.getElementById('quiz-modal').classList.remove('hidden');
    }

    function closeQuizModal() {
        document.getElementById('quiz-modal').classList.add('hidden');
        tempQuizData = {};
        window.isFinalExamMode = false;
    }

    function saveQuizChanges() {
        tempQuizData.start_date = document.getElementById('quiz-start').value;
        tempQuizData.end_date = document.getElementById('quiz-end').value;

        if (window.isFinalExamMode) {
            finalExamData.questions = tempQuizData.questions;
            finalExamData.start_date = tempQuizData.start_date;
            finalExamData.end_date = tempQuizData.end_date;
            
            const countEl = document.getElementById('exam-questions-count');
            if(countEl) countEl.innerText = finalExamData.questions.length;
            const pointsEl = document.getElementById('exam-total-points');
            if(pointsEl) pointsEl.innerText = countQuizPoints(finalExamData.questions);
            if(document.getElementById('exam_start_date')) document.getElementById('exam_start_date').value = tempQuizData.start_date;
            if(document.getElementById('exam_end_date')) document.getElementById('exam_end_date').value = tempQuizData.end_date;
            try { if(typeof updateEvaluationInput === 'function') updateEvaluationInput(); } catch(e) {}
            
        } else {
            modules[currentQuizModuleIndex].lessons[currentQuizLessonIndex].quiz_data = tempQuizData;
            renderModules();
        }
        closeQuizModal();
    }

    function renderQuizQuestions() {
        const container = document.getElementById('quiz-questions-container');
        container.innerHTML = '';
        let totalScore = 0;

        tempQuizData.questions.forEach((q, qIndex) => {
            totalScore += parseInt(q.points || 0);
            let optionsHtml = '';
            
            if(q.type === 'single' || q.type === 'multiple') {
                const opts = q.options ? q.options.split(',') : [];
                const selectorsHtml = opts.map((opt, optIdx) => {
                    const cleanOpt = opt.trim();
                    if(!cleanOpt) return '';
                    const isSelected = Array.isArray(q.correct) ? q.correct.includes(cleanOpt) : q.correct == cleanOpt;
                    const inputType = q.type === 'single' ? 'radio' : 'checkbox';
                    const radioName = `correct_q_${qIndex}`; 

                    return `<label class="inline-flex items-center gap-1 cursor-pointer bg-white border border-gray-200 px-2 py-1 rounded-md hover:border-purple-300">
                                <input type="${inputType}" name="${radioName}" ${isSelected ? 'checked' : ''} onchange="setCorrectAnswer(${qIndex}, '${cleanOpt}', '${q.type}')" class="text-purple-600 focus:ring-purple-500">
                                <span class="text-xs text-gray-600">${cleanOpt}</span>
                            </label>`;
                }).join('');

                optionsHtml = `
                    <div class="mt-3 pl-4 border-l-2 border-purple-100">
                        <div class="mb-2">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Opciones (separadas por comas)</label>
                            <input type="text" value="${q.options || ''}" onchange="updateQuizQuestion(${qIndex}, 'options', this.value); renderQuizQuestions();" class="w-full px-3 py-1.5 bg-purple-50/50 border border-purple-100 rounded text-sm focus:bg-white outline-none" placeholder="Opción A, Opción B, Opción C">
                        </div>
                        ${opts.length > 0 && opts[0] !== '' ? `
                            <div class="flex flex-wrap gap-2 items-center bg-purple-50 p-2 rounded-lg">
                                <span class="text-[10px] font-bold text-purple-400 uppercase mr-1">Respuesta Correcta:</span>
                                ${selectorsHtml}
                            </div>
                        ` : ''}
                    </div>`;
            }

            const html = `
                <div class="bg-white border border-gray-200 p-4 rounded-xl relative group hover:border-purple-300 transition-colors">
                    <div class="flex justify-between mb-2">
                        <span class="text-[10px] uppercase font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded">${q.type_label}</span>
                        <button type="button" onclick="removeQuizQuestion(${qIndex})" class="text-gray-300 hover:text-red-500 transition-colors"><i class="ph-bold ph-trash"></i></button>
                    </div>
                    <div class="grid grid-cols-12 gap-3">
                        <div class="col-span-10">
                            <input type="text" value="${q.title}" onchange="updateQuizQuestion(${qIndex}, 'title', this.value)" class="w-full font-bold text-gray-700 bg-transparent outline-none placeholder-gray-400 border-b border-transparent focus:border-purple-300 transition-colors" placeholder="Escribe la pregunta...">
                        </div>
                        <div class="col-span-2">
                            <div class="relative">
                                <input type="number" value="${q.points}" onchange="updateQuizQuestion(${qIndex}, 'points', this.value)" class="w-full text-right font-bold text-purple-700 bg-purple-50 rounded px-2 py-1 text-sm outline-none focus:ring-1 focus:ring-purple-300 pr-8">
                                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-purple-400 pointer-events-none">pts</span>
                            </div>
                        </div>
                    </div>
                    ${optionsHtml}
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
        });
        document.getElementById('quiz-score').value = totalScore;
    }

    function setCorrectAnswer(qIndex, value, type) {
        if (type === 'single') {
            tempQuizData.questions[qIndex].correct = value;
        } else {
            let current = tempQuizData.questions[qIndex].correct || [];
            if (!Array.isArray(current)) current = [];
            if (current.includes(value)) {
                current = current.filter(item => item !== value);
            } else {
                current.push(value);
            }
            tempQuizData.questions[qIndex].correct = current;
        }
    }

    function addQuizQuestion(type) {
        const labels = { 'single': 'Selección Única', 'multiple': 'Selección Múltiple', 'open': 'Pregunta Abierta' };
        tempQuizData.questions.push({ type: type, type_label: labels[type], title: '', points: 10, options: '', correct: type === 'multiple' ? [] : null });
        renderQuizQuestions();
    }

    function removeQuizQuestion(index) { tempQuizData.questions.splice(index, 1); renderQuizQuestions(); }
    function updateQuizQuestion(index, field, value) { tempQuizData.questions[index][field] = value; if(field === 'points') renderQuizQuestions(); }
    function countQuizPoints(questions) { if(!questions) return 0; return questions.reduce((sum, q) => sum + parseInt(q.points || 0), 0); }
    function addModule() { modules.push({ title: "Nuevo Módulo", lessons: [] }); renderModules(); }
    function removeModule(index) { if(confirm('¿Borrar módulo?')) { modules.splice(index, 1); renderModules(); } }
    function updateModule(index, field, value) { modules[index][field] = value; }
    
    function addLesson(mIndex, type) { 
        modules[mIndex].lessons.push({ title: type === 'quiz' ? "Evaluación" : "Nueva Lección", type: type, video_url: "", duration: 0, is_preview: false, content: "", hide_video: false, content_order: "video_first", quiz_data: type === 'quiz' ? { questions: [] } : {} }); 
        renderModules(); 
    }
    function removeLesson(mIndex, lIndex) { modules[mIndex].lessons.splice(lIndex, 1); renderModules(); }
    function updateLesson(mIndex, lIndex, field, value) { modules[mIndex].lessons[lIndex][field] = value; if(field === 'hide_video') renderModules(); }
    function toggleLessonDetails(mIndex, lIndex) { document.getElementById(`lesson-details-${mIndex}-${lIndex}`).classList.toggle('hidden'); }
</script>