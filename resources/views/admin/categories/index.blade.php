<x-layouts.admin>
    <x-slot name="title">Gestión de Categorías</x-slot>
    <x-slot name="header">Categorías</x-slot>

    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-10 fade-in-up">
        <form method="GET" class="relative w-full md:w-96 group">
            <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00] transition-colors"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar categoría por nombre..." 
                   class="w-full pl-12 pr-4 py-4 rounded-[1.2rem] border border-gray-100 bg-white shadow-sm focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none text-sm transition-all font-medium">
        </form>
        
        @if(Auth::user()->hasPermission('categories.manage'))
        <button onclick="openModal()" class="w-full md:w-auto flex items-center justify-center gap-3 px-8 py-4 bg-[#001144] text-white rounded-[1.2rem] hover:bg-[#001144]/90 transition-all shadow-xl shadow-blue-900/20 text-sm font-black uppercase tracking-widest group">
            <i class="ph-bold ph-plus text-lg group-hover:rotate-90 transition-transform"></i> Nueva Categoría
        </button>
        @endif
    </div>

    <!-- Lista de Categorías -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 fade-in-up" style="animation-delay: 0.1s">
        @forelse($categories as $category)
        <div class="bg-white rounded-[2.5rem] border border-gray-100 p-8 hover:shadow-2xl hover:shadow-gray-200/50 transition-all group relative border-b-4 border-b-transparent hover:border-b-[#FF7A00]">
            
            <!-- Acciones -->
            @if(Auth::user()->hasPermission('categories.manage'))
            <div class="absolute top-6 right-6 flex gap-2 opacity-0 group-hover:opacity-100 transition-all transform translate-y-2 group-hover:translate-y-0 z-10">
                <button onclick="editModal({{ json_encode($category) }})" class="w-9 h-9 bg-white/90 backdrop-blur rounded-xl text-[#001144] hover:bg-[#001144] hover:text-white shadow-lg border border-gray-100 flex items-center justify-center transition-all">
                    <i class="ph-bold ph-pencil-simple"></i>
                </button>
                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('¿Eliminar categoría?');" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-9 h-9 bg-white/90 backdrop-blur rounded-xl text-red-500 hover:bg-red-500 hover:text-white shadow-lg border border-gray-100 flex items-center justify-center transition-all">
                        <i class="ph-bold ph-trash"></i>
                    </button>
                </form>
            </div>
            @endif

            <div class="flex flex-col gap-6">
                <div class="w-16 h-16 rounded-[1.5rem] bg-orange-50 flex items-center justify-center text-[#FF7A00] shrink-0 group-hover:bg-[#FF7A00] group-hover:text-white transition-all shadow-lg shadow-orange-500/10">
                    <i class="ph-bold {{ $category->icon ?? 'ph-tag' }} text-3xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-black text-[#001144] text-lg truncate tracking-tight mb-2 group-hover:text-[#FF7A00] transition-colors" title="{{ $category->name }}">{{ $category->name }}</h3>
                    <p class="text-xs text-gray-400 font-medium leading-relaxed line-clamp-3 h-12 mb-4">{{ $category->description ?? 'Sin descripción proporcionada.' }}</p>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                        <div class="flex flex-wrap items-center gap-1.5">
                            <span class="text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full {{ $category->status ? 'bg-green-50 text-green-600' : 'bg-gray-50 text-gray-400' }}">
                                {{ $category->status ? 'Activo' : 'Inactivo' }}
                            </span>
                            <span class="text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full bg-blue-50 text-[#001144]">
                                @if(($category->target_type ?? 'both') === 'course')
                                    Cursos
                                @elseif(($category->target_type ?? 'both') === 'diploma')
                                    Diplomados
                                @else
                                    Ambos
                                @endif
                            </span>
                        </div>
                        <div class="flex items-center gap-2 text-[#001144]/40 font-black">
                            <i class="ph-bold ph-book-open"></i>
                            <span class="text-[10px] uppercase tracking-wider">{{ $category->courses_count }} Cursos</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-24 bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
            <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6">
                <i class="ph-duotone ph-tag text-4xl text-gray-300"></i>
            </div>
            <h3 class="text-xl font-black text-[#001144] mb-2 tracking-tight">No hay categorías registradas</h3>
            <p class="text-gray-400 mb-8 max-w-xs mx-auto font-medium">Organiza tus cursos creando categorías temáticas para facilitar la navegación.</p>
            
            @if(Auth::user()->hasPermission('categories.manage'))
            <button onclick="openModal()" class="px-8 py-4 bg-[#001144] text-white rounded-[1.2rem] font-black uppercase tracking-widest hover:bg-[#FF7A00] transition-all shadow-xl shadow-blue-900/20 text-sm">
                Crear Primera Categoría
            </button>
            @endif
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    <div class="mt-12">
        {{ $categories->links() }}
    </div>

    <!-- MODAL (CREAR / EDITAR) -->
    <div id="category-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-[#001144]/40 backdrop-blur-md transition-opacity" onclick="closeModal()"></div>
        
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md border border-white/20">
                    
                    <div class="bg-white px-8 py-6 border-b border-gray-50 flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-[#FF7A00]">
                                <i class="ph-fill ph-tag text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-[#001144] tracking-tight" id="modal-title">Nueva Categoría</h3>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Organización de Cursos</p>
                            </div>
                        </div>
                        <button onclick="closeModal()" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors rounded-full hover:bg-red-50">
                            <i class="ph-bold ph-x text-xl"></i>
                        </button>
                    </div>

                    <form id="category-form" method="POST" action="{{ route('admin.categories.store') }}">
                        @csrf
                        <input type="hidden" name="_method" id="form-method" value="POST">
                        
                        <div class="p-8 space-y-6">
                            <!-- Nombre -->
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nombre de Categoría <span class="text-[#FF7A00]">*</span></label>
                                <input type="text" name="name" id="input_name" required 
                                       class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none text-sm font-bold text-[#001144] transition-all"
                                       placeholder="Ej: Seguridad Industrial">
                            </div>

                            <!-- Icono -->
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Icono de la Categoría <span class="text-[#FF7A00]">*</span></label>
                                <div class="flex gap-4 mb-3">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center text-[#001144] border border-gray-100 shadow-inner group transition-all" id="icon-preview">
                                        <i class="ph-bold ph-tag text-2xl group-hover:scale-110 transition-transform"></i>
                                    </div>
                                    <input type="text" name="icon" id="input_icon" required readonly
                                           class="flex-1 px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-150 outline-none text-sm font-bold text-[#001144]/60 transition-all cursor-not-allowed"
                                           placeholder="Selecciona un icono abajo...">
                                </div>
                                <div class="grid grid-cols-5 gap-2 max-h-36 overflow-y-auto p-3 bg-gray-50 rounded-[1.5rem] border border-gray-100" id="icon-picker">
                                    <!-- Populated via Javascript -->
                                </div>
                            </div>

                            <!-- Aplica a -->
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Aplica a <span class="text-[#FF7A00]">*</span></label>
                                <select name="target_type" id="input_target_type" class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] appearance-none cursor-pointer transition-all">
                                    <option value="both">Ambos (Cursos y Diplomados)</option>
                                    <option value="course">Solo Cursos</option>
                                    <option value="diploma">Solo Diplomados</option>
                                </select>
                            </div>

                            <!-- Descripción -->
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Breve Descripción</label>
                                <textarea name="description" id="input_description" rows="4"
                                          class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-medium text-[#001144] resize-none transition-all"
                                          placeholder="¿De qué trata esta categoría?"></textarea>
                            </div>

                            <!-- Estado -->
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Visibilidad</label>
                                <select name="status" id="input_status" class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] appearance-none cursor-pointer transition-all">
                                    <option value="1">Activo (Visible en web)</option>
                                    <option value="0">Inactivo (Oculto)</option>
                                </select>
                            </div>
                        </div>

                        <div class="bg-gray-50/50 px-8 py-6 flex justify-end gap-4 border-t border-gray-50 rounded-b-[2.5rem]">
                            <button type="button" onclick="closeModal()" class="px-8 py-4 bg-white border border-gray-100 text-[#001144] rounded-[1.2rem] hover:bg-gray-50 text-[10px] font-black uppercase tracking-widest transition-all">Cancelar</button>
                            <button type="submit" class="px-10 py-4 bg-[#001144] text-white rounded-[1.2rem] hover:bg-[#FF7A00] text-[10px] font-black uppercase tracking-widest shadow-xl shadow-blue-900/10 transition-all transform hover:-translate-y-1">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('category-modal');
        const form = document.getElementById('category-form');
        const title = document.getElementById('modal-title');
        const methodInput = document.getElementById('form-method');

        const availableIcons = [
            'ph-tag', 'ph-book-open', 'ph-shield-check', 'ph-hard-hat', 'ph-briefcase',
            'ph-heartbeat', 'ph-chart-line', 'ph-desktop', 'ph-leaf', 'ph-gear',
            'ph-wrench', 'ph-users', 'ph-graduation-cap', 'ph-globe', 'ph-first-aid',
            'ph-buildings', 'ph-truck', 'ph-scales', 'ph-calculator', 'ph-brain'
        ];

        document.addEventListener('DOMContentLoaded', () => {
            const picker = document.getElementById('icon-picker');
            if (picker) {
                picker.innerHTML = availableIcons.map(icon => `
                    <button type="button" onclick="selectIcon('${icon}')" data-icon="${icon}"
                            class="icon-picker-btn w-10 h-10 border border-gray-100 rounded-xl bg-white text-[#001144] flex items-center justify-center hover:border-[#FF7A00] hover:text-[#FF7A00] transition-all">
                        <i class="ph-bold ${icon} text-lg"></i>
                    </button>
                `).join('');
            }
        });

        function selectIcon(iconClass) {
            document.getElementById('input_icon').value = iconClass;
            updateIconPreview(iconClass);
            
            document.querySelectorAll('.icon-picker-btn').forEach(btn => {
                if (btn.dataset.icon === iconClass) {
                    btn.classList.add('border-[#FF7A00]', 'bg-orange-50', 'text-[#FF7A00]');
                    btn.classList.remove('border-gray-100', 'bg-white', 'text-[#001144]');
                } else {
                    btn.classList.remove('border-[#FF7A00]', 'bg-orange-50', 'text-[#FF7A00]');
                    btn.classList.add('border-gray-100', 'bg-white', 'text-[#001144]');
                }
            });
        }

        function updateIconPreview(val) {
            const preview = document.getElementById('icon-preview');
            const iconClass = val && val.trim() !== '' ? val : 'ph-tag';
            preview.innerHTML = `<i class="ph-bold ${iconClass} text-2xl"></i>`;
        }

        function openModal() {
            form.action = "{{ route('admin.categories.store') }}";
            methodInput.value = "POST";
            title.innerText = "Nueva Categoría";
            form.reset();
            document.getElementById('input_target_type').value = "both";
            selectIcon('ph-tag');
            modal.classList.remove('hidden');
        }

        function editModal(category) {
            let url = "{{ route('admin.categories.update', ':id') }}";
            form.action = url.replace(':id', category.id);
            methodInput.value = "PUT";
            title.innerText = "Editar Categoría";
            
            document.getElementById('input_name').value = category.name;
            document.getElementById('input_description').value = category.description;
            document.getElementById('input_status').value = category.status ? "1" : "0";
            document.getElementById('input_target_type').value = category.target_type || "both";
            
            selectIcon(category.icon || 'ph-tag');
            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
        }
    </script>
</x-layouts.admin>