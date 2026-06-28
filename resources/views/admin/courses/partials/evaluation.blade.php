<!-- Estilos y Scripts Editor -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<!-- 1. PREPARACIÓN DE DATOS CON BLADE -->
@php
    $current = $course->evaluation_config ?? [];
    if (!is_array($current)) {
        $current = [];
    }

    $defaults = [
        'min_score' => 70,
        // Ponderaciones
        'attendance_weight' => 20,
        'final_assessment_weight' => 80,
        
        'final_exam_enabled' => false,
        'final_exam_data' => [
            'start_date' => '', 
            'end_date' => '', 
            'instructions' => '', 
            'questions' => []
        ],
        'final_project_enabled' => false,
        'final_project_data' => [
            'score' => '100',
            'start_date' => '', 
            'end_date' => '', 
            'instructions' => '', 
            'max_files' => '1', 
            'max_file_size' => '5',
            'file_types' => []
        ]
    ];

    $finalConfig = array_replace_recursive($defaults, $current);
@endphp

<!-- 2. INPUT OCULTO ÚNICO -->
<input type="hidden" name="evaluation_config" id="hidden_evaluation_config_input" value="{{ json_encode($finalConfig) }}">

<!-- 3. INICIALIZACIÓN JS -->
<script>
    window.evalState = @json($finalConfig);
    console.log("Estado inicial cargado:", window.evalState);
</script>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <h3 class="text-xl font-bold text-[#213A57] mb-6">Método de Evaluación</h3>
    
    <div class="space-y-8">
        <p class="text-gray-600 text-sm">Selecciona y configura los requisitos para aprobar el curso.</p>

        <!-- 1. EXAMEN FINAL -->
        <div class="border-2 border-gray-200 rounded-xl overflow-hidden transition-all" id="container-exam">
            <label class="flex items-center gap-4 p-6 cursor-pointer bg-white hover:bg-gray-50 transition-colors">
                <input type="checkbox" id="eval_chk_exam" class="peer sr-only" onchange="updateEvalState('final_exam_enabled', this.checked)">
                
                <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 shrink-0">
                    <i class="ph-bold ph-exam text-2xl"></i>
                </div>
                
                <div class="flex-1">
                    <h4 class="font-bold text-[#213A57] text-lg">Examen Final</h4>
                    <p class="text-sm text-gray-500">Cuestionario automático al finalizar todos los módulos.</p>
                </div>

                <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:bg-[#0AD1C8] peer-checked:border-[#0AD1C8] flex items-center justify-center text-white transition-all" id="eval_chk-icon-exam">
                    <i class="ph-bold ph-check text-xs opacity-0 peer-checked:opacity-100"></i>
                </div>
            </label>

            <!-- Configuración Examen -->
            <div id="eval_config-exam" class="hidden border-t border-gray-200 bg-purple-50/50 p-6">
                <div class="flex flex-col md:flex-row gap-6 items-start">
                    <div class="flex-1 space-y-4 w-full">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Habilitado Desde</label>
                                <input type="datetime-local" id="eval_exam_start_date" 
                                       oninput="updateEvalState('final_exam_data.start_date', this.value)"
                                       class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:border-purple-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Habilitado Hasta</label>
                                <input type="datetime-local" id="eval_exam_end_date" 
                                       oninput="updateEvalState('final_exam_data.end_date', this.value)"
                                       class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:border-purple-500 outline-none">
                            </div>
                        </div>
                        
                        <!-- Instrucciones -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Instrucciones del Examen</label>
                            <div class="flex items-center gap-3">
                                <button type="button" onclick="openInstructionsModal('exam')" class="px-4 py-2 bg-white border border-purple-200 text-purple-600 text-sm font-medium rounded-lg hover:bg-purple-50 transition-colors flex items-center gap-2">
                                    <i class="ph-bold ph-pencil-simple"></i> Editar Instrucciones
                                </button>
                                <span id="eval_exam-instructions-status" class="text-xs text-gray-400 italic">Sin instrucciones</span>
                            </div>
                        </div>
                    </div>

                    <!-- Botón Preguntas -->
                    <div class="w-full md:w-auto flex flex-col items-center justify-center p-4 bg-white rounded-xl border border-purple-100 shadow-sm text-center">
                        <div class="text-purple-600 font-bold text-2xl mb-1" id="eval_exam-questions-count">0</div>
                        <div class="text-xs text-gray-500 mb-3">Preguntas</div>
                        <button type="button" onclick="openExamModal()" class="px-4 py-2 bg-purple-600 text-white text-sm font-bold rounded-lg hover:bg-purple-700 transition-all shadow-lg shadow-purple-500/20 whitespace-nowrap">
                            <i class="ph-bold ph-pencil-simple"></i> Editar Preguntas
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. TRABAJO FINAL -->
        <div class="border-2 border-gray-200 rounded-xl overflow-hidden transition-all" id="container-project">
            <label class="flex items-center gap-4 p-6 cursor-pointer bg-white hover:bg-gray-50 transition-colors">
                <input type="checkbox" id="eval_chk_project" class="peer sr-only" onchange="updateEvalState('final_project_enabled', this.checked)">
                
                <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 shrink-0">
                    <i class="ph-bold ph-briefcase text-2xl"></i>
                </div>
                
                <div class="flex-1">
                    <h4 class="font-bold text-[#213A57] text-lg">Trabajo Final</h4>
                    <p class="text-sm text-gray-500">Subida de archivos para revisión manual.</p>
                </div>

                <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:bg-[#0AD1C8] peer-checked:border-[#0AD1C8] flex items-center justify-center text-white transition-all" id="eval_chk-icon-project">
                    <i class="ph-bold ph-check text-xs opacity-0 peer-checked:opacity-100"></i>
                </div>
            </label>

            <!-- Configuración Trabajo -->
            <div id="eval_config-project" class="hidden border-t border-gray-200 bg-orange-50/50 p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nota Máxima (Base)</label>
                        <input type="number" id="eval_project_score"
                               oninput="updateEvalState('final_project_data.score', this.value)"
                               class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:border-orange-500 outline-none font-bold text-orange-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Fecha Inicio</label>
                        <input type="datetime-local" id="eval_project_start_date" 
                               oninput="updateEvalState('final_project_data.start_date', this.value)"
                               class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:border-orange-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Fecha Límite</label>
                        <input type="datetime-local" id="eval_project_end_date" 
                               oninput="updateEvalState('final_project_data.end_date', this.value)"
                               class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:border-orange-500 outline-none">
                    </div>
                </div>

                <div class="mb-4 bg-white/50 p-4 rounded-xl border border-orange-100">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-3">Instrucciones y Material de Apoyo</label>
                    
                    <div class="flex flex-col md:flex-row gap-4 items-start">
                        <!-- Botón Editar Instrucciones -->
                        <div class="shrink-0">
                            <button type="button" onclick="openInstructionsModal('project')" class="px-4 py-3 bg-white border border-orange-200 text-orange-600 text-sm font-medium rounded-lg hover:bg-orange-50 transition-colors flex items-center gap-2 shadow-sm">
                                <i class="ph-bold ph-pencil-simple"></i> Editar Texto
                            </button>
                            <div id="eval_project-instructions-status" class="text-[10px] text-gray-400 italic mt-1 text-center">Sin instrucciones</div>
                        </div>

                        <!-- NUEVO: CAMPO DE ARCHIVO DE EJEMPLO -->
                        <div class="flex-1 w-full p-3 border border-dashed border-orange-300 rounded-lg bg-orange-50 hover:bg-white transition-colors relative group flex items-center gap-4">
                            <div class="bg-orange-100 p-3 rounded-lg text-orange-500">
                                <i class="ph-fill ph-file-doc text-2xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <label class="cursor-pointer block mb-1">
                                    <span class="text-sm font-bold text-orange-800 hover:text-orange-900 transition-colors hover:underline">Subir Archivo de Guía/Ejemplo</span>
                                    <input type="file" name="project_example_file" class="hidden" onchange="handleProjectFile(this)" accept=".pdf,.doc,.docx,.zip,.rar">
                                </label>
                                
                                <div class="flex items-center gap-3">
                                    <span class="text-xs text-gray-500 truncate" id="project_file_name">
                                        @if(isset($course->evaluation_config['project_file']))
                                            {{ basename($course->evaluation_config['project_file']) }}
                                        @else
                                            Ningún archivo seleccionado
                                        @endif
                                    </span>
                                    
                                    @if(isset($course->evaluation_config['project_file']))
                                        @php
                                            // Corrección de ruta para descarga directa usando el helper route()
                                            // Quitamos 'storage/' del path para pasarlo al controlador de media
                                            $cleanPath = str_replace('storage/', '', $course->evaluation_config['project_file']);
                                            $downloadUrl = route('media.show', ['filename' => $cleanPath]);
                                        @endphp
                                        
                                        <!-- Botón Ver Más Grande con enlace corregido -->
                                        <a href="{{ $downloadUrl }}" target="_blank" class="px-4 py-2 bg-white border border-orange-200 text-orange-700 text-sm font-bold rounded-lg shadow-sm hover:bg-orange-50 transition-all flex items-center gap-2 whitespace-nowrap ml-auto">
                                            <i class="ph-bold ph-download-simple text-lg"></i> Descargar
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- FIN NUEVO CAMPO -->
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Formatos Permitidos</label>
                        <div class="flex flex-wrap gap-2" id="eval_file-types-container">
                            <!-- Renderizado via JS -->
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Máximo de Archivos</label>
                        <select id="eval_project_max_files" 
                                onchange="updateEvalState('final_project_data.max_files', this.value)"
                                class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:border-orange-500 outline-none">
                            <option value="1">1 Archivo</option>
                            <option value="3">Hasta 3 Archivos</option>
                            <option value="5">Hasta 5 Archivos</option>
                            <option value="10">Hasta 10 Archivos</option>
                        </select>
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Peso Máximo (MB)</label>
                        <select id="eval_project_max_size" 
                                onchange="updateEvalState('final_project_data.max_file_size', this.value)"
                                class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:border-orange-500 outline-none">
                            <option value="2">2 MB</option>
                            <option value="5">5 MB</option>
                            <option value="10">10 MB</option>
                            <option value="20">20 MB</option>
                            <option value="50">50 MB</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN FINAL: PONDERACIÓN Y NOTA MÍNIMA -->
    <div class="mt-8 border-t border-gray-100 pt-8">
        
        <!-- Ponderación -->
        <div class="bg-blue-50 rounded-xl p-6 border border-blue-100 mb-6">
            <h4 class="font-bold text-[#213A57] mb-4 flex items-center gap-2">
                <i class="ph-bold ph-chart-pie-slice text-blue-500"></i> Ponderación Global del Curso
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Peso Asistencia (%)</label>
                    <div class="relative">
                        <input type="number" id="eval_attendance_weight" min="0" max="100"
                               oninput="updateEvalState('attendance_weight', this.value); validateWeights();"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 font-bold text-[#213A57] outline-none text-xl">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-bold">%</span>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">Se divide entre la cantidad de módulos.</p>
                </div>

                <div class="flex justify-center pb-4 text-gray-300">
                    <i class="ph-bold ph-plus text-2xl"></i>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Peso Evaluación Final (%)</label>
                    <div class="relative">
                        <input type="number" id="eval_final_assessment_weight" min="0" max="100"
                               oninput="updateEvalState('final_assessment_weight', this.value); validateWeights();"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 font-bold text-[#213A57] outline-none text-xl">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-bold">%</span>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">Aplica a Examen Final o Trabajo Final.</p>
                </div>
            </div>
            
            <!-- Mensaje de Validación de Suma -->
            <div id="weight-warning" class="mt-4 text-xs font-bold text-amber-600 bg-amber-50 p-2 rounded hidden">
                <i class="ph-bold ph-warning"></i> La suma de los pesos debe ser igual a 100%. Actualmente es: <span id="total-weight-display">0</span>%
            </div>
        </div>

        <!-- Nota Mínima -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
            <div>
                <label class="block text-sm font-bold text-gray-700">Nota Mínima de Aprobación (Global)</label>
                <p class="text-xs text-gray-500 mt-1">El estudiante debe alcanzar este puntaje ponderado final para obtener el certificado.</p>
            </div>
            <div class="flex items-center gap-3">
                <input type="number" id="eval_min_score" 
                       oninput="updateEvalState('min_score', this.value)"
                       class="w-24 px-4 py-3 rounded-xl border border-gray-200 focus:border-[#0AD1C8] font-bold text-[#213A57] text-center outline-none text-xl">
                <span class="text-sm text-gray-500 font-medium">/ 100 pts</span>
            </div>
        </div>

    </div>
</div>

<!-- MODALES (INSTRUCCIONES Y PREGUNTAS) -->
<div id="instructions-modal" class="fixed inset-0 z-[120] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-[#213A57]/90 backdrop-blur-sm transition-opacity" onclick="closeInstructionsModal()"></div>
    <div class="fixed inset-0 z-[130] w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-4xl border border-white/10 flex flex-col h-[70vh]">
                <div class="bg-gray-50 px-6 py-4 flex justify-between items-center shrink-0 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-[#213A57]">Editor de Instrucciones</h3>
                    <button type="button" onclick="closeInstructionsModal()" class="text-gray-400 hover:text-red-500"><i class="ph-bold ph-x text-xl"></i></button>
                </div>
                <div class="flex-1 bg-white flex flex-col overflow-hidden">
                    <div id="instructions-editor-container" class="flex-1 overflow-y-auto font-poppins text-gray-700"></div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end shrink-0 border-t border-gray-200">
                    <button type="button" onclick="saveInstructions()" class="px-6 py-2 bg-[#213A57] text-white font-bold rounded-lg hover:bg-[#0B6477]">Guardar Instrucciones</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="final-exam-modal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-[#213A57]/90 backdrop-blur-sm transition-opacity" onclick="closeExamModal()"></div>
    <div class="fixed inset-0 z-[110] w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-4xl border border-white/10 flex flex-col max-h-[90vh]">
                <div class="bg-purple-600 px-6 py-4 flex justify-between items-center shrink-0">
                    <h3 class="text-lg font-bold text-white"><i class="ph-bold ph-list-checks"></i> Configuración de Evaluación Final</h3>
                    <button type="button" onclick="closeExamModal()" class="text-white hover:bg-white/20 p-2 rounded-full"><i class="ph-bold ph-x text-xl"></i></button>
                </div>
                <div class="p-6 overflow-y-auto">
                    <!-- RESUMEN DE NOTA -->
                    <div class="mb-6 p-4 bg-purple-50 rounded-xl border border-purple-100 flex items-center justify-between">
                        <span class="text-xs font-bold text-purple-800 uppercase">Nota Total Calculada:</span>
                        <div class="flex items-center gap-2">
                            <input type="number" id="final-exam-total-score-display" class="w-20 px-2 py-1 rounded border border-purple-200 text-sm font-bold text-purple-700 bg-white text-center" readonly value="0">
                            <span class="text-xs text-purple-600">puntos</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-bold text-gray-700">Listado de Preguntas</h4>
                        <div class="flex gap-2">
                            <button type="button" onclick="addFinalExamQuestion('single')" class="px-3 py-1.5 bg-gray-100 hover:text-purple-700 text-xs font-bold rounded-lg transition-colors border border-gray-200 hover:border-purple-200">+ Selección Única</button>
                            <button type="button" onclick="addFinalExamQuestion('multiple')" class="px-3 py-1.5 bg-gray-100 hover:text-purple-700 text-xs font-bold rounded-lg transition-colors border border-gray-200 hover:border-purple-200">+ Selección Múltiple</button>
                            <button type="button" onclick="addFinalExamQuestion('open')" class="px-3 py-1.5 bg-gray-100 hover:text-purple-700 text-xs font-bold rounded-lg transition-colors border border-gray-200 hover:border-purple-200">+ Abierta</button>
                        </div>
                    </div>
                    <div id="final-exam-questions-container" class="space-y-4 min-h-[150px]"></div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end shrink-0 border-t border-gray-200">
                    <button type="button" onclick="saveExamChanges()" class="px-6 py-2 bg-purple-600 text-white font-bold rounded-lg hover:bg-purple-700">Guardar Preguntas</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // --- VARIABLES Y ESTADO ---
    let instructionsEditor = null;
    let currentInstructionsType = null;
    let tempQuestions = [];

    document.addEventListener("DOMContentLoaded", function() {
        // 1. Inicializar Editor
        instructionsEditor = new Quill('#instructions-editor-container', {
            theme: 'snow',
            modules: { toolbar: [[{ 'header': [1, 2, false] }], ['bold', 'italic', 'list', 'link', 'clean']] }
        });

        // 2. Renderizar formulario DESDE EL ESTADO
        renderForm();
        
        // 3. Forzar sincronización inicial del input hidden
        syncHiddenInput();
        
        // 4. Asegurar que al enviar el form, el input hidden esté fresco
        const form = document.getElementById('hidden_evaluation_config_input').closest('form');
        if(form) form.addEventListener('submit', syncHiddenInput);
    });

    // --- FUNCIONES NUEVAS PARA ARCHIVO ---
    function handleProjectFile(input) {
        if (input.files && input.files[0]) {
            document.getElementById('project_file_name').innerText = input.files[0].name;
        }
    }

    // --- FUNCIÓN PRINCIPAL DE ACTUALIZACIÓN ---
    window.updateEvalState = function(path, value) {
        // Conversión numérica para ponderaciones
        if(path === 'attendance_weight' || path === 'final_assessment_weight' || path === 'min_score') {
            value = parseInt(value) || 0;
        }

        const keys = path.split('.');
        if (keys.length === 1) {
            window.evalState[keys[0]] = value;
        } else if (keys.length === 2) {
            if (!window.evalState[keys[0]]) window.evalState[keys[0]] = {};
            window.evalState[keys[0]][keys[1]] = value;
        }

        if (path.includes('enabled')) renderVisibility();
        
        syncHiddenInput();
    };

    function validateWeights() {
        const att = parseInt(document.getElementById('eval_attendance_weight').value) || 0;
        const exam = parseInt(document.getElementById('eval_final_assessment_weight').value) || 0;
        const total = att + exam;
        
        const warning = document.getElementById('weight-warning');
        const display = document.getElementById('total-weight-display');
        
        if (display) display.innerText = total;
        if (warning) {
            if(total !== 100) warning.classList.remove('hidden');
            else warning.classList.add('hidden');
        }
    }

    function syncHiddenInput() {
        const input = document.getElementById('hidden_evaluation_config_input');
        if (input) {
            input.value = JSON.stringify(window.evalState);
            console.log("JSON sincronizado:", window.evalState);
        }
    }

    // --- RENDERIZADO DEL FORMULARIO ---
    function renderForm() {
        const s = window.evalState;

        // Ponderaciones
        setVal('eval_attendance_weight', s.attendance_weight);
        setVal('eval_final_assessment_weight', s.final_assessment_weight);
        validateWeights();

        setCheck('eval_chk_exam', s.final_exam_enabled);
        setCheck('eval_chk_project', s.final_project_enabled);
        renderVisibility();

        setVal('eval_exam_start_date', formatDateTime(s.final_exam_data.start_date));
        setVal('eval_exam_end_date', formatDateTime(s.final_exam_data.end_date));
        
        setVal('eval_project_start_date', formatDateTime(s.final_project_data.start_date));
        setVal('eval_project_end_date', formatDateTime(s.final_project_data.end_date));
        setVal('eval_project_max_files', s.final_project_data.max_files);
        setVal('eval_project_max_size', s.final_project_data.max_file_size || '5');
        setVal('eval_project_score', s.final_project_data.score || '100');
        setVal('eval_min_score', s.min_score);

        document.getElementById('eval_exam-instructions-status').innerText = s.final_exam_data.instructions ? 'Configurado' : 'Sin instrucciones';
        document.getElementById('eval_project-instructions-status').innerText = s.final_project_data.instructions ? 'Configurado' : 'Sin instrucciones';
        document.getElementById('eval_exam-questions-count').innerText = (s.final_exam_data.questions || []).length;

        renderFileTypes();
    }

    function formatDateTime(dateStr) {
        if (!dateStr) return '';
        return dateStr.replace(' ', 'T').substring(0, 16);
    }

    function renderVisibility() {
        toggleDiv('container-exam', 'eval_config-exam', 'eval_chk-icon-exam', window.evalState.final_exam_enabled, 'purple');
        toggleDiv('container-project', 'eval_config-project', 'eval_chk-icon-project', window.evalState.final_project_enabled, 'orange');
    }

    function renderFileTypes() {
        const types = ['pdf', 'docx', 'images', 'zip'];
        const selected = window.evalState.final_project_data.file_types || [];
        const container = document.getElementById('eval_file-types-container');
        container.innerHTML = types.map(t => {
            const checked = selected.includes(t) ? 'checked' : '';
            return `<label class="inline-flex items-center gap-1 cursor-pointer bg-white px-3 py-1 border rounded hover:border-orange-300"><input type="checkbox" value="${t}" ${checked} onchange="updateFileType('${t}', this.checked)" class="text-orange-500 rounded"><span class="uppercase text-xs">${t}</span></label>`;
        }).join('');
    }

    window.updateFileType = function(t, checked) {
        let list = window.evalState.final_project_data.file_types || [];
        if(checked) { if(!list.includes(t)) list.push(t); } 
        else { list = list.filter(i => i !== t); }
        updateEvalState('final_project_data.file_types', list);
    };

    // --- DOM UTILS ---
    function setVal(id, v) { const el = document.getElementById(id); if(el) el.value = v || ''; }
    function setCheck(id, v) { const el = document.getElementById(id); if(el) el.checked = !!v; }
    function toggleDiv(pId, cId, iId, show, color) {
        const p = document.getElementById(pId), c = document.getElementById(cId), i = document.querySelector(`#${iId} i`);
        if(show) {
            c.classList.remove('hidden'); p.classList.add(`border-${color}-500`, `bg-${color}-50`); p.classList.remove('border-gray-200'); i.classList.remove('opacity-0');
        } else {
            c.classList.add('hidden'); p.classList.remove(`border-${color}-500`, `bg-${color}-50`); p.classList.add('border-gray-200'); i.classList.add('opacity-0');
        }
    }

    // --- MODALES ---
    window.openInstructionsModal = function(type) {
        currentInstructionsType = type;
        const content = type === 'exam' ? window.evalState.final_exam_data.instructions : window.evalState.final_project_data.instructions;
        instructionsEditor.root.innerHTML = content || '';
        document.getElementById('instructions-modal').classList.remove('hidden');
    };
    window.closeInstructionsModal = function() { document.getElementById('instructions-modal').classList.add('hidden'); };
    window.saveInstructions = function() {
        const key = currentInstructionsType === 'exam' ? 'final_exam_data.instructions' : 'final_project_data.instructions';
        updateEvalState(key, instructionsEditor.root.innerHTML);
        renderForm();
        closeInstructionsModal();
    };

    window.openExamModal = function() {
        tempQuestions = JSON.parse(JSON.stringify(window.evalState.final_exam_data.questions || []));
        renderFinalExamQuestions();
        document.getElementById('final-exam-modal').classList.remove('hidden');
    };
    window.closeExamModal = function() { document.getElementById('final-exam-modal').classList.add('hidden'); };
    window.saveExamChanges = function() {
        updateEvalState('final_exam_data.questions', tempQuestions);
        renderForm();
        closeExamModal();
    };

    // --- PREGUNTAS ---
    window.renderFinalExamQuestions = function() {
        const container = document.getElementById('final-exam-questions-container');
        container.innerHTML = '';
        let totalScore = 0;

        if (tempQuestions.length === 0) {
            container.innerHTML = '<div class="text-center py-8 text-gray-400 border-2 border-dashed border-gray-100 rounded-lg">No hay preguntas creadas.</div>';
            const scoreEl = document.getElementById('final-exam-total-score-display');
            if(scoreEl) scoreEl.value = 0;
            return;
        }

        tempQuestions.forEach((q, index) => {
            let p = parseInt(q.points);
            if (isNaN(p)) p = 0;
            totalScore += p;
            
            let optionsHtml = '';
            if(q.type === 'single' || q.type === 'multiple') {
                const opts = q.options ? q.options.split(',') : [];
                const inputType = q.type === 'single' ? 'radio' : 'checkbox';
                const name = `fe_q_${index}`;
                
                const selectors = opts.map(opt => {
                    const val = opt.trim(); 
                    if(!val) return '';
                    const isSel = Array.isArray(q.correct) ? q.correct.includes(val) : q.correct == val;
                    return `
                        <label class="inline-flex items-center gap-1 cursor-pointer bg-white px-2 py-1 border rounded hover:border-purple-300">
                            <input type="${inputType}" name="${name}" ${isSel ? 'checked' : ''} onchange="setQuestionCorrect(${index}, '${val}', '${q.type}')" class="text-purple-600 focus:ring-purple-500">
                            <span class="text-xs text-gray-600">${val}</span>
                        </label>`;
                }).join('');

                optionsHtml = `
                    <div class="mt-3 pl-4 border-l-2 border-purple-100">
                        <div class="mb-2">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Opciones (separadas por comas)</label>
                            <input type="text" value="${q.options || ''}" 
                                   oninput="updateQuestion(${index}, 'options', this.value)" 
                                   onchange="renderFinalExamQuestions()"
                                   class="w-full px-3 py-1.5 bg-purple-50/50 border border-purple-100 rounded text-sm focus:bg-white outline-none placeholder-purple-300" 
                                   placeholder="Opción A, Opción B, Opción C">
                        </div>
                        ${opts.length > 0 && opts[0].trim() !== '' ? `
                            <div class="flex flex-wrap gap-2 items-center bg-purple-50 p-2 rounded-lg">
                                <span class="text-[10px] font-bold text-purple-400 uppercase mr-1">Respuesta Correcta:</span>
                                ${selectors}
                            </div>
                        ` : ''}
                    </div>`;
            }

            const typeLabel = q.type === 'single' ? 'Selección Única' : (q.type === 'multiple' ? 'Selección Múltiple' : 'Pregunta Abierta');

            container.insertAdjacentHTML('beforeend', `
                <div class="bg-white border border-gray-200 p-4 rounded-xl relative group hover:border-purple-300 transition-colors shadow-sm mb-3">
                    <div class="flex justify-between mb-2">
                        <span class="text-[10px] uppercase font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded">${typeLabel}</span>
                        <button onclick="removeQuestion(${index})" class="text-gray-300 hover:text-red-500 transition-colors"><i class="ph-bold ph-trash"></i></button>
                    </div>
                    
                    <div class="grid grid-cols-12 gap-3">
                        <div class="col-span-10">
                            <input type="text" value="${q.text || ''}" oninput="updateQuestion(${index}, 'text', this.value)" class="w-full font-bold text-gray-700 bg-transparent outline-none placeholder-gray-400 border-b border-transparent focus:border-purple-300 transition-colors" placeholder="Escribe la pregunta...">
                        </div>
                        <div class="col-span-2">
                            <div class="relative">
                                <input type="number" value="${q.points}" 
                                       oninput="updateQuestion(${index}, 'points', this.value); updateTotalScore();" 
                                       class="w-full text-right font-bold text-purple-700 bg-purple-50 rounded px-2 py-1 text-sm outline-none focus:ring-1 focus:ring-purple-300 pr-8">
                                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-purple-400 pointer-events-none">pts</span>
                            </div>
                        </div>
                    </div>
                    ${optionsHtml}
                </div>
            `);
        });
        
        const scoreEl = document.getElementById('final-exam-total-score-display');
        if(scoreEl) scoreEl.value = totalScore;
    };

    window.updateTotalScore = function() {
        let total = 0;
        tempQuestions.forEach(q => {
            let p = parseInt(q.points);
            if (isNaN(p)) p = 0;
            total += p;
        });
        const scoreEl = document.getElementById('final-exam-total-score-display');
        if(scoreEl) scoreEl.value = total;
    };

    window.addFinalExamQuestion = function(t) { tempQuestions.push({type:t, text:'', points:10, options:'', correct:t==='multiple'?[]:null}); renderFinalExamQuestions(); };
    window.removeQuestion = function(i) { if(confirm('¿Borrar?')) { tempQuestions.splice(i,1); renderFinalExamQuestions(); } };
    window.updateQuestion = function(i,f,v) { tempQuestions[i][f] = v; };
    window.setQuestionCorrect = function(i,v,t) {
        if(t==='single') tempQuestions[i].correct = v;
        else {
            let c = tempQuestions[i].correct || [];
            if(c.includes(v)) c = c.filter(x=>x!==v); else c.push(v);
            tempQuestions[i].correct = c;
        }
    };
</script>