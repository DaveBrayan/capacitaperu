<!-- 1. PREPARACIÓN DE DATOS -->
@php
    $attConfig = $course->attendance_config ?? [];
    // Valores por defecto (Sin score, ya que es global)
    $defaultsAtt = [
        'scope' => 'all', 
        'questions' => []
    ];
    $finalAttConfig = array_replace_recursive($defaultsAtt, is_array($attConfig) ? $attConfig : []);
@endphp

<!-- 2. INPUT OCULTO PARA GUARDADO (Sincronizado vía JS) -->
<input type="hidden" name="attendance_data" id="attendance_data_input" value="{{ json_encode($finalAttConfig) }}">

<!-- 3. ESTADO INICIAL JS -->
<script>
    window.attendanceState = @json($finalAttConfig);
</script>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- COLUMNA IZQUIERDA: CONFIGURACIÓN -->
    <div class="lg:col-span-1 space-y-6">
        
        <!-- Configuración de Alcance -->
        <div class="bg-[#F8FAFC] p-6 rounded-2xl border border-gray-200">
            <h4 class="font-bold text-[#213A57] mb-4 flex items-center gap-2">
                <i class="ph-fill ph-gear text-[#0AD1C8]"></i> Configuración
            </h4>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-2 uppercase">Aplicar Asistencia a:</label>
                    <div class="flex flex-col gap-2">
                        <label class="flex items-center gap-2 cursor-pointer bg-white p-3 rounded-xl border border-gray-200 hover:border-[#0AD1C8] transition-colors">
                            <input type="radio" name="attendance_scope" value="all" 
                                   onchange="updateAttendanceState('scope', 'all')"
                                   class="text-[#0AD1C8] focus:ring-[#0AD1C8]">
                            <span class="text-sm font-medium text-gray-700">Todos los Módulos</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer bg-white p-3 rounded-xl border border-gray-200 hover:border-[#0AD1C8] transition-colors">
                            <input type="radio" name="attendance_scope" value="specific" 
                                   onchange="updateAttendanceState('scope', 'specific')"
                                   class="text-[#0AD1C8] focus:ring-[#0AD1C8]">
                            <span class="text-sm font-medium text-gray-700">Módulos Específicos</span>
                        </label>
                    </div>
                </div>

                <div id="module-selector-container" class="hidden animate-fade-in-up">
                    <p class="text-xs text-gray-400 mb-2">Selecciona los módulos en el paso "Contenido" para activar.</p>
                </div>
            </div>
        </div>

    </div>

    <!-- COLUMNA DERECHA: CONSTRUCTOR DE FORMULARIO -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-xl font-bold text-[#213A57]">Formulario de Asistencia</h3>
                    <p class="text-sm text-gray-500">Diseña las preguntas que el estudiante debe responder.</p>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="addAttQuestion('text')" class="px-3 py-2 bg-gray-100 text-gray-600 text-xs font-bold rounded-lg hover:bg-gray-200 transition-all flex items-center gap-1">
                        <i class="ph-bold ph-text-t"></i> Abierta
                    </button>
                    <button type="button" onclick="addAttQuestion('choice')" class="px-3 py-2 bg-[#E0F9F6] text-[#0B6477] text-xs font-bold rounded-lg hover:bg-[#0AD1C8] hover:text-white transition-all flex items-center gap-1 border border-[#0AD1C8]/20">
                        <i class="ph-bold ph-list-dashes"></i> Selección
                    </button>
                </div>
            </div>

            <div id="attendance-questions-container" class="space-y-4 min-h-[200px]">
                <!-- Renderizado via JS -->
            </div>
        </div>
    </div>
</div>

<script>
    // --- ESTADO Y VARIABLES ---
    let attState = window.attendanceState || {};
    // Aseguramos que questions sea un array
    if (!Array.isArray(attState.questions)) attState.questions = [];

    document.addEventListener("DOMContentLoaded", function() {
        renderAttendanceForm();
        syncAttendanceInput();

        // Asegurar guardado al enviar
        const form = document.getElementById('attendance_data_input').closest('form');
        if(form) form.addEventListener('submit', syncAttendanceInput);
    });

    // --- SINCRONIZACIÓN ---
    function updateAttendanceState(key, value) {
        attState[key] = value;
        
        if(key === 'scope') renderScope(); // Solo actualizar visuales necesarios
        
        syncAttendanceInput();
    }

    function syncAttendanceInput() {
        const input = document.getElementById('attendance_data_input');
        if(input) input.value = JSON.stringify(attState);
    }

    // --- RENDERIZADO GENERAL ---
    function renderAttendanceForm() {
        // 1. Scope
        const radio = document.querySelector(`input[name="attendance_scope"][value="${attState.scope}"]`);
        if(radio) radio.checked = true;
        renderScope();

        // 3. Preguntas
        renderAttQuestions();
    }

    function renderScope() {
        const container = document.getElementById('module-selector-container');
        if(attState.scope === 'specific') container.classList.remove('hidden'); 
        else container.classList.add('hidden');
    }

    // --- RENDERIZADO DE PREGUNTAS ---
    function renderAttQuestions() {
        const container = document.getElementById('attendance-questions-container');
        container.innerHTML = '';

        if (attState.questions.length === 0) {
            container.innerHTML = `
                <div class="text-center py-10 border-2 border-dashed border-gray-200 rounded-xl">
                    <i class="ph-duotone ph-clipboard-text text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-400 text-sm">No hay preguntas configuradas.</p>
                </div>`;
            return;
        }

        attState.questions.forEach((q, index) => {
            let optionsHtml = '';
            if(q.type === 'choice') {
                optionsHtml = `
                    <div class="mt-3 space-y-2 pl-4 border-l-2 border-gray-100">
                        <p class="text-xs font-bold text-gray-400 uppercase">Opciones (separar por comas)</p>
                        <input type="text" value="${q.options || ''}" 
                               oninput="updateAttQuestion(${index}, 'options', this.value)" 
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:bg-white focus:border-[#0AD1C8] outline-none" 
                               placeholder="Presente, Permiso, Falta">
                    </div>`;
            }

            const typeLabel = q.type === 'choice' ? 'Selección Múltiple' : 'Pregunta Abierta';
            const typeClass = q.type === 'choice' ? 'text-[#0B6477] bg-[#E0F9F6]' : 'text-gray-600 bg-gray-100';

            const html = `
                <div class="p-4 border border-gray-200 rounded-xl bg-white relative group hover:border-[#0AD1C8]/50 transition-colors">
                    <div class="flex items-start gap-3">
                        <div class="mt-2 text-gray-300 cursor-move"><i class="ph-bold ph-dots-six-vertical"></i></div>
                        <div class="flex-1">
                            <div class="flex justify-between mb-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider ${typeClass} px-2 py-0.5 rounded">
                                    ${typeLabel}
                                </span>
                                <button onclick="removeAttQuestion(${index})" class="text-gray-300 hover:text-red-500 transition-colors"><i class="ph-bold ph-trash"></i></button>
                            </div>
                            <input type="text" value="${q.title}" 
                                   oninput="updateAttQuestion(${index}, 'title', this.value)" 
                                   class="w-full font-bold text-[#213A57] bg-transparent outline-none placeholder-gray-400 border-b border-transparent focus:border-[#0AD1C8] py-1 transition-all" 
                                   placeholder="Escribe la pregunta aquí...">
                            ${optionsHtml}
                        </div>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
        });
    }

    // --- ACCIONES DE PREGUNTAS ---
    window.addAttQuestion = function(type) {
        attState.questions.push({ type: type, title: '', options: type === 'choice' ? 'Presente' : null });
        renderAttQuestions();
        syncAttendanceInput();
    };

    window.removeAttQuestion = function(index) {
        if(confirm('¿Eliminar pregunta?')) {
            attState.questions.splice(index, 1);
            renderAttQuestions();
            syncAttendanceInput();
        }
    };

    window.updateAttQuestion = function(index, field, value) {
        attState.questions[index][field] = value;
        syncAttendanceInput();
    };
</script>