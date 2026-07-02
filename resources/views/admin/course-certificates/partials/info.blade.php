<div class="space-y-12">
    <!-- SECCIÓN: VINCULACIÓN ACADÉMICA -->
    <div>
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-[#FF7A00] flex items-center justify-center shadow-sm">
                <i class="ph-fill ph-link text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-[#001144] tracking-tight">Vinculación Académica</h3>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Asocia este certificado a un docente y a un curso o diplomado dictado por él</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Instructor / Docente -->
            <div class="space-y-2 md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Docente / Instructor <span class="text-red-500">*</span></label>
                <div class="relative group">
                    <i class="ph-bold ph-user-circle absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00] transition-colors"></i>
                    <select name="instructor_id" id="instructor_id" class="w-full pl-14 pr-12 py-4 rounded-[1.5rem] border border-gray-100 focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/5 outline-none transition-all text-sm font-bold text-[#001144] bg-gray-50/50 focus:bg-white appearance-none" required>
                        <option value="">Seleccionar Docente / Instructor</option>
                        @foreach($instructors as $instructor)
                            <option value="{{ $instructor->id }}" 
                                    data-cert-name="{{ $instructor->certificate_name ?? $instructor->name }}" 
                                    data-signature="{{ $instructor->signature_url ? asset($instructor->signature_url) : '' }}"
                                    {{ old('instructor_id', $certificate->instructor_id) == $instructor->id ? 'selected' : '' }}>
                                {{ $instructor->name }}
                            </option>
                        @endforeach
                    </select>
                    <i class="ph-bold ph-caret-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-300 pointer-events-none"></i>
                </div>
            </div>

            <!-- Curso / Diplomado -->
            <div class="space-y-2 md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Curso o Diplomado <span class="text-red-500">*</span></label>
                <div class="relative group">
                    <i class="ph-bold ph-graduation-cap absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00] transition-colors"></i>
                    <select name="course_id" id="course_id" class="w-full pl-14 pr-12 py-4 rounded-[1.5rem] border border-gray-100 focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/5 outline-none transition-all text-sm font-bold text-[#001144] bg-gray-50/50 focus:bg-white appearance-none" required>
                        <option value="">Seleccione primero un docente</option>
                    </select>
                    <i class="ph-bold ph-caret-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-300 pointer-events-none"></i>
                </div>
            </div>

            <!-- Título del Certificado -->
            <div class="space-y-2 md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Título de la Plantilla de Certificado <span class="text-red-500">*</span></label>
                <div class="relative group">
                    <i class="ph-bold ph-text-t absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00] transition-colors"></i>
                    <input type="text" name="title" id="title" value="{{ old('title', $certificate->title) }}" placeholder="Ej: Especialización en Gestión Pública" class="w-full pl-14 pr-6 py-4 rounded-[1.5rem] border border-gray-100 focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/5 outline-none transition-all text-sm font-bold text-[#001144] bg-gray-50/50 focus:bg-white" required>
                </div>
            </div>

            <!-- Carga Horaria Override -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Horas Académicas (Opcional Override)</label>
                <div class="relative group">
                    <i class="ph-bold ph-clock absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00] transition-colors"></i>
                    <input type="number" name="hours" value="{{ old('hours', $certificate->hours) }}" placeholder="Si se deja vacío se usará la duración del curso" class="w-full pl-14 pr-6 py-4 rounded-[1.5rem] border border-gray-100 focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/5 outline-none transition-all text-sm font-bold text-[#001144] bg-gray-50/50 focus:bg-white">
                </div>
            </div>

            <!-- Número de Registro Override -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Registro Base (Opcional Override)</label>
                <div class="relative group">
                    <i class="ph-bold ph-hash absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00] transition-colors"></i>
                    <input type="text" name="registration_number" value="{{ old('registration_number', $certificate->registration_number) }}" placeholder="Ej: CP-2024-001 (Por defecto usa el UUID autogenerado)" class="w-full pl-14 pr-6 py-4 rounded-[1.5rem] border border-gray-100 focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/5 outline-none transition-all text-sm font-bold text-[#001144] bg-gray-50/50 focus:bg-white">
                </div>
            </div>

            <!-- Fecha Inicio Override -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Fecha de Inicio (Opcional Override)</label>
                <div class="relative group">
                    <i class="ph-bold ph-calendar-blank absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00] transition-colors"></i>
                    <input type="date" name="start_date" value="{{ old('start_date', $certificate->start_date ? $certificate->start_date->format('Y-m-d') : '') }}" class="w-full pl-14 pr-6 py-4 rounded-[1.5rem] border border-gray-100 focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/5 outline-none transition-all text-sm font-bold text-[#001144] bg-gray-50/50 focus:bg-white">
                </div>
            </div>

            <!-- Fecha Fin Override -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Fecha de Finalización (Opcional Override)</label>
                <div class="relative group">
                    <i class="ph-bold ph-calendar-check absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00] transition-colors"></i>
                    <input type="date" name="end_date" value="{{ old('end_date', $certificate->end_date ? $certificate->end_date->format('Y-m-d') : '') }}" class="w-full pl-14 pr-6 py-4 rounded-[1.5rem] border border-gray-100 focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/5 outline-none transition-all text-sm font-bold text-[#001144] bg-gray-50/50 focus:bg-white">
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN: ESTADO -->
    <div class="pt-8 border-t border-gray-100">
        <div class="max-w-md">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-500 flex items-center justify-center">
                    <i class="ph-fill ph-toggle-left text-xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-black text-[#001144] uppercase tracking-wider">Estado del Registro</h4>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Control de visibilidad</p>
                </div>
            </div>

            <div class="bg-gray-50/50 p-6 rounded-[2rem] border border-gray-100 space-y-4">
                <label class="flex items-center gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all {{ old('status', $certificate->status) == 'published' ? 'border-[#001144] bg-white' : 'border-transparent' }}">
                    <input type="radio" name="status" value="published" class="hidden" {{ old('status', $certificate->status) == 'published' ? 'checked' : '' }} onchange="this.closest('div').querySelectorAll('label').forEach(l => l.classList.remove('border-[#001144]', 'bg-white')); this.parentElement.classList.add('border-[#001144]', 'bg-white')">
                    <div class="w-10 h-10 rounded-xl bg-green-500 text-white flex items-center justify-center shadow-lg shadow-green-500/20">
                        <i class="ph-bold ph-paper-plane-tilt"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-[#001144] uppercase tracking-widest">Publicado</p>
                        <p class="text-[10px] text-gray-400 font-medium">Disponible para emitir certificados en el curso.</p>
                    </div>
                </label>

                <label class="flex items-center gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all {{ old('status', $certificate->status) == 'draft' ? 'border-[#FF7A00] bg-white' : 'border-transparent' }}">
                    <input type="radio" name="status" value="draft" class="hidden" {{ old('status', $certificate->status) == 'draft' ? 'checked' : '' }} onchange="this.closest('div').querySelectorAll('label').forEach(l => l.classList.remove('border-[#001144]', 'bg-white')); this.parentElement.classList.add('border-[#FF7A00]', 'bg-white')">
                    <div class="w-10 h-10 rounded-xl bg-gray-200 text-gray-500 flex items-center justify-center">
                        <i class="ph-bold ph-pencil-line"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-gray-500 uppercase tracking-widest">Borrador</p>
                        <p class="text-[10px] text-gray-400 font-medium">Solo visible para administradores.</p>
                    </div>
                </label>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const allCourses = @json($courses);
        const instructorSelect = document.getElementById('instructor_id');
        const courseSelect = document.getElementById('course_id');
        const titleInput = document.getElementById('title');
        
        const initialCourseId = "{{ old('course_id', $certificate->course_id) }}";

        function filterCoursesByInstructor() {
            const instructorId = instructorSelect.value;
            
            // Clear current options
            courseSelect.innerHTML = '<option value="">Seleccionar Curso o Diplomado</option>';
            
            if (!instructorId) {
                courseSelect.innerHTML = '<option value="">Seleccione primero un docente</option>';
                return;
            }
            
            // Filter
            const filtered = allCourses.filter(c => c.instructor_id == instructorId);
            
            if (filtered.length === 0) {
                courseSelect.innerHTML = '<option value="">El docente seleccionado no tiene cursos asignados</option>';
                return;
            }
            
            filtered.forEach(c => {
                const typeLabel = c.type === 'diploma' ? 'Diplomado' : 'Curso';
                const isSelected = c.id == initialCourseId ? 'selected' : '';
                const option = document.createElement('option');
                option.value = c.id;
                option.textContent = `[${typeLabel.toUpperCase()}] ${c.title}`;
                if (isSelected) option.selected = true;
                courseSelect.appendChild(option);
            });
        }

        // Event listener for instructor change
        instructorSelect.addEventListener('change', function() {
            filterCoursesByInstructor();
            
            // Update canvas metadata in real-time (Step 2)
            if (window.courseMeta && window.courseMeta.instructor) {
                const selectedOption = instructorSelect.options[instructorSelect.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    const certName = selectedOption.getAttribute('data-cert-name') || selectedOption.text;
                    const signature = selectedOption.getAttribute('data-signature') || null;
                    
                    window.courseMeta.instructor.name = certName;
                    window.courseMeta.instructor.signature = signature;
                    
                    if (typeof window.updateDynamicElements === 'function') {
                        window.updateDynamicElements();
                    }
                }
            }
        });

        // Event listener for course change
        courseSelect.addEventListener('change', function() {
            const selectedOption = courseSelect.options[courseSelect.selectedIndex];
            if (selectedOption && selectedOption.value) {
                // Strip the type prefix like "[CURSO] "
                const courseTitle = selectedOption.text.replace(/^\[.*?\]\s*/, '');
                
                // Update template title if it is default or empty
                if (!titleInput.value || titleInput.value.includes('Certificado - ')) {
                    titleInput.value = courseTitle;
                }
                
                // Update canvas metadata in real-time (Step 2)
                if (window.courseMeta) {
                    window.courseMeta.title = courseTitle;
                    if (typeof window.updateDynamicElements === 'function') {
                        window.updateDynamicElements();
                    }
                }
            }
        });

        // Run initial filtering
        filterCoursesByInstructor();
    });
</script>
