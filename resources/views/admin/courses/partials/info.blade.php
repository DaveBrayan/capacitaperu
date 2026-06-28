<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Columna Izquierda: Datos Principales -->
        <div class="lg:col-span-2 space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Título del Curso</label>
                <input type="text" name="title" value="{{ old('title', $course->title) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#0AD1C8] focus:ring-4 focus:ring-[#0AD1C8]/10 outline-none transition-all" placeholder="Ej: Especialista en Seguridad Industrial">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Resumen Corto</label>
                <textarea name="short_description" rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#0AD1C8] outline-none resize-none">{{ old('short_description', $course->short_description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Descripción Detallada</label>
                <textarea name="description" rows="8" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#0AD1C8] outline-none">{{ old('description', $course->description) }}</textarea>
            </div>

            <!-- ============================================== -->
            <!-- SECCIÓN DE BENEFICIOS (EDITOR WYSIWYG)         -->
            <!-- ============================================== -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Beneficios del Curso</label>
                <p class="text-xs text-gray-500 mb-2">Utiliza la barra de herramientas para dar formato a los beneficios.</p>
                
                <!-- Contenedor para estilos específicos de CKEditor -->
                <div class="prose max-w-none">
                    <textarea id="benefits_editor" name="benefits" class="w-full">{{ old('benefits', $course->benefits) }}</textarea>
                </div>
            </div>
            <!-- ============================================== -->
            <!-- FIN SECCIÓN DE BENEFICIOS                      -->
            <!-- ============================================== -->

        </div>

        <!-- Columna Derecha: Configuración y Media -->
        <div class="space-y-6">
            
            <!-- Imagen Portada -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Imagen de Portada</label>
                @php
                    $hasImage = false;
                    $imageUrl = '';
                    
                    if (!empty($course->cover_image)) {
                        $imageUrl = asset($course->cover_image);
                        $hasImage = true;
                    }
                @endphp
                
                <!-- Input hidden para saber si borrar la imagen actual -->
                <input type="hidden" name="delete_cover" id="delete_cover_input" value="0">

                <div class="relative w-full aspect-video bg-gray-100 rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center cursor-pointer hover:border-[#0AD1C8] transition-all group overflow-hidden">
                    <input type="file" name="cover_image" id="cover_image_input" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-20" onchange="previewImage(this)">
                    
                    <!-- Previsualización -->
                    <img id="image-preview" 
                         src="{{ $course->cover_image ? asset($course->cover_image) : '' }}" 
                         class="{{ $course->cover_image ? '' : 'hidden' }} absolute inset-0 w-full h-full object-cover"
                         onload="logImageSuccess(this.src)"
                         onerror="logImageError(this, this.src)">
                    
                    <!-- Placeholder -->
                    <div id="upload-placeholder" 
                         class="text-center p-4 flex flex-col items-center justify-center" 
                         style="{{ $hasImage ? 'display:none' : 'display:flex' }}">
                        <i class="ph-bold ph-image text-3xl text-gray-300 mb-2 group-hover:text-[#0AD1C8] transition-colors"></i>
                        <p class="text-xs font-bold text-gray-400">Click para subir</p>
                    </div>

                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity z-15 flex items-center justify-center pointer-events-none">
                        <span class="text-white text-xs font-bold bg-black/20 px-3 py-1 rounded-full backdrop-blur-sm">Cambiar imagen</span>
                    </div>
                </div>
                
                <div id="image-actions" class="{{ $hasImage ? 'flex' : 'hidden' }} mt-2 items-center justify-between px-1">
                    <p class="text-[10px] text-gray-400 italic truncate max-w-[180px]">Imagen cargada</p>
                    <button type="button" onclick="resetImage()" class="text-[10px] text-red-500 font-bold hover:underline">Quitar</button>
                </div>
            </div>

            <!-- Datos de Cursada -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">N° Registro Certificado</label>
                    <input type="text" name="metadata[registration_number]" value="{{ old('metadata.registration_number', $course->metadata['registration_number'] ?? '') }}" placeholder="CP-2024-001" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm outline-none focus:border-[#0AD1C8]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Carga Horaria (Hrs)</label>
                    <input type="number" name="duration" value="{{ old('duration', $course->duration) }}" placeholder="40" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm outline-none focus:border-[#0AD1C8]">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Fecha de Inicio</label>
                    <input type="date" name="metadata[start_date]" value="{{ old('metadata.start_date', $course->metadata['start_date'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm outline-none focus:border-[#0AD1C8]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Fecha de Finalización</label>
                    <input type="date" name="metadata[end_date]" value="{{ old('metadata.end_date', $course->metadata['end_date'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm outline-none focus:border-[#0AD1C8]">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Modalidad</label>
                    <select name="metadata[modality]" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm bg-white outline-none focus:border-[#0AD1C8]">
                        <option value="Virtual" {{ (old('metadata.modality', $course->metadata['modality'] ?? '') == 'Virtual') ? 'selected' : '' }}>Virtual</option>
                        <option value="Presencial" {{ (old('metadata.modality', $course->metadata['modality'] ?? '') == 'Presencial') ? 'selected' : '' }}>Presencial</option>
                        <option value="Semipresencial" {{ (old('metadata.modality', $course->metadata['modality'] ?? '') == 'Semipresencial') ? 'selected' : '' }}>Semipresencial</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nivel</label>
                    <select name="level" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm bg-white outline-none focus:border-[#0AD1C8]">
                        <option value="Básico" {{ $course->level == 'Básico' ? 'selected' : '' }}>Básico</option>
                        <option value="Intermedio" {{ $course->level == 'Intermedio' ? 'selected' : '' }}>Intermedio</option>
                        <option value="Avanzado" {{ $course->level == 'Avanzado' ? 'selected' : '' }}>Avanzado</option>
                    </select>
                </div>
            </div>

            <!-- Instructor -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Instructor</label>
                <select name="instructor_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:border-[#0AD1C8] outline-none">
                    <option value="" disabled {{ is_null($course->instructor_id) ? 'selected' : '' }}>Seleccionar Instructor</option>
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" {{ $course->instructor_id == $instructor->id ? 'selected' : '' }}>
                            {{ $instructor->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Precio y Estado -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Precio ({{ $platformSettings['app_currency'] ?? 'Bs' }})</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-xs">{{ $platformSettings['app_currency'] ?? 'Bs' }}</span>
                        <input type="number" name="price" value="{{ old('price', $course->price ?? 0) }}" class="w-full pl-8 pr-3 py-2 rounded-lg border border-gray-200 focus:border-[#0AD1C8] font-bold text-[#213A57] outline-none text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Estado</label>
                    <select name="status" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm bg-white outline-none focus:border-amber-400 font-medium">
                        <option value="draft" {{ old('status', $course->status) == 'draft' ? 'selected' : '' }}>Borrador</option>
                        <option value="published" {{ old('status', $course->status) == 'published' ? 'selected' : '' }}>Publicado</option>
                    </select>
                </div>
            </div>

            <!-- Categorías -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Categorías</label>
                <select name="categories[]" multiple class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#0AD1C8] outline-none h-24">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                            {{ (collect(old('categories', $course->categories->pluck('id')))->contains($category->id)) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Cargar CKEditor desde CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

<style>
/* Estilos personalizados para ajustar CKEditor al diseño */
.ck-editor__editable_inline {
    min-height: 200px;
    border-bottom-left-radius: 0.75rem !important; /* rounded-xl */
    border-bottom-right-radius: 0.75rem !important;
    border-color: #E5E7EB !important; /* gray-200 */
}
.ck-toolbar {
    border-top-left-radius: 0.75rem !important; /* rounded-xl */
    border-top-right-radius: 0.75rem !important;
    border-color: #E5E7EB !important;
    background-color: #F9FAFB !important; /* gray-50 */
}
.ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
    border-color: #E5E7EB !important;
}
.ck.ck-editor__main>.ck-editor__editable.ck-focused {
    border-color: #0AD1C8 !important; /* Tu color accent */
    box-shadow: 0 0 0 4px rgba(10, 209, 200, 0.1) !important;
}

/* CORRECCIÓN PARA LISTAS EN TAILWIND:
   Tailwind resetea los estilos de lista (ul, ol) por defecto.
   Aquí forzamos que dentro del editor sí se muestren los puntos y números.
*/
.ck-content ul {
    list-style-type: disc !important;
    padding-left: 1.5rem !important;
    margin-bottom: 1rem !important;
}
.ck-content ol {
    list-style-type: decimal !important;
    padding-left: 1.5rem !important;
    margin-bottom: 1rem !important;
}
.ck-content li {
    margin-bottom: 0.25rem;
}
</style>

<script>
// Inicializar CKEditor para Beneficios
document.addEventListener("DOMContentLoaded", function() {
    ClassicEditor
        .create(document.querySelector('#benefits_editor'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'],
            placeholder: 'Escribe los beneficios aquí...'
        })
        .catch(error => {
            console.error(error);
        });
});

// --- LÓGICA DE IMAGENES ---
function logImageSuccess(url) {
    console.log("✅ Imagen cargada con éxito:", url);
}

function logImageError(img, url) {
    console.error("❌ Error al cargar imagen (404 o rota):", url);
    img.classList.add('hidden');
    document.getElementById('upload-placeholder').style.display = 'flex';
    document.getElementById('image-actions').classList.add('hidden');
    document.getElementById('image-actions').classList.remove('flex');
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('upload-placeholder');
            const actions = document.getElementById('image-actions');
            const deleteInput = document.getElementById('delete_cover_input');
            
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            preview.style.display = 'block';
            if(placeholder) placeholder.style.display = 'none';
            
            // Mostrar acciones
            actions.classList.remove('hidden');
            actions.classList.add('flex');
            
            // Resetear flag de borrado porque acabamos de subir una nueva
            deleteInput.value = "0";
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function resetImage() {
    const preview = document.getElementById('image-preview');
    const placeholder = document.getElementById('upload-placeholder');
    const input = document.getElementById('cover_image_input');
    const actions = document.getElementById('image-actions');
    const deleteInput = document.getElementById('delete_cover_input');
    
    preview.src = '';
    preview.classList.add('hidden');
    preview.style.display = 'none';
    placeholder.style.display = 'flex';
    input.value = '';
    
    actions.classList.add('hidden');
    actions.classList.remove('flex');
    
    // Marcar para borrado en el servidor
    deleteInput.value = "1";
}
</script>