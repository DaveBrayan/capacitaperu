<x-layouts.admin>
    <x-slot name="title">Promociones - CapacitaPerú</x-slot>
    <x-slot name="header">Campañas y Promociones</x-slot>

    <!-- ESTILOS EXTRA -->
    <style>
        .select-multiple option { padding: 12px 16px; border-bottom: 1px solid #f8fafc; border-radius: 12px; margin-bottom: 4px; }
        .select-multiple option:checked { background: #001144 !important; color: white !important; font-weight: 900; }
        .select-multiple option:hover { background-color: #f1f5f9; }
        .logic-hidden { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>

    <!-- BARRA SUPERIOR -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-10 fade-in-up">
        <div class="relative w-full md:w-96 group">
            <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00] transition-colors"></i>
            <input type="text" placeholder="Buscar campaña o cupón..." 
                   class="w-full pl-12 pr-4 py-4 rounded-[1.2rem] border border-gray-100 bg-white shadow-sm focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none transition-all text-sm font-medium">
        </div>
        
        @if(Auth::user()->hasPermission('promotions.create'))
        <button onclick="openPromoModal()" class="flex items-center gap-3 px-8 py-4 bg-[#001144] text-white rounded-[1.2rem] hover:bg-[#FF7A00] transition-all shadow-xl shadow-blue-900/10 text-[10px] font-black uppercase tracking-[0.2em] transform hover:-translate-y-1 group">
            <i class="ph-bold ph-tag-chevron text-lg group-hover:rotate-12 transition-transform"></i>
            Nueva Promoción
        </button>
        @endif
    </div>

    <!-- TABLA -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden fade-in-up" style="animation-delay: 0.2s">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Campaña</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Descuento</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Alcance</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Vigencia</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-center">Estado</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($promotions as $promo)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-orange-50 text-[#FF7A00] flex items-center justify-center shrink-0 border border-orange-100">
                                    <i class="ph-bold ph-megaphone-simple text-xl"></i>
                                </div>
                                <p class="font-black text-[#001144] tracking-tight uppercase text-xs">{{ $promo->name }}</p>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-xl font-black text-[#FF7A00] leading-none">
                                    {{ $promo->discount_type == 'percent' ? '-' . intval($promo->value) . '%' : '- ' . ($platformSettings['app_currency'] ?? 'Bs') . ' ' . number_format($promo->value, 0) }}
                                </span>
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1">Valor Promo</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @php
                                $tIds = $promo->target_ids;
                                if(is_string($tIds)) { $tIds = json_decode($tIds, true); }
                                $tIds = is_array($tIds) ? $tIds : [];

                                $eIds = $promo->exception_ids;
                                if(is_string($eIds)) { $eIds = json_decode($eIds, true); }
                                $eIds = is_array($eIds) ? $eIds : [];
                            @endphp

                            @if($promo->target_type == 'all')
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-blue-50 text-blue-600 border border-blue-100">
                                    <i class="ph-bold ph-globe"></i> Todo el Catálogo
                                </span>
                            @elseif($promo->target_type == 'category')
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center gap-2 text-[10px] font-black text-[#001144] uppercase tracking-wider">
                                        <i class="ph-bold ph-squares-four text-[#FF7A00]"></i>
                                        {{ count($tIds) }} Categorías
                                    </div>
                                    @if(count($eIds) > 0)
                                        <span class="text-[9px] font-black text-red-400 uppercase tracking-widest flex items-center gap-1">
                                            <i class="ph-bold ph-prohibit"></i> {{ count($eIds) }} Excluidos
                                        </span>
                                    @endif
                                </div>
                            @elseif($promo->target_type == 'course')
                                <div class="flex items-center gap-2 text-[10px] font-black text-[#001144] uppercase tracking-wider">
                                    <i class="ph-bold ph-graduation-cap text-[#FF7A00]"></i>
                                    {{ count($tIds) }} Cursos
                                </div>
                            @endif
                        </td>
                        <td class="px-8 py-6 min-w-[220px]">
                            <div class="flex justify-between text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">
                                <span>{{ $promo->start_date->timezone($timezone)->locale('es')->translatedFormat('d M Y H:i') }}</span>
                                <span>{{ $promo->end_date->timezone($timezone)->locale('es')->translatedFormat('d M Y H:i') }}</span>
                            </div>
                            <div class="w-full h-1.5 bg-gray-50 rounded-full overflow-hidden border border-gray-100">
                                <div class="h-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.5)]" style="width: 100%"></div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($promo->status_label == 'Activo')
                                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-green-50 text-green-600 border border-green-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.5)]"></span> {{ $promo->status_label }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-gray-50 text-gray-400 border border-gray-100">
                                    {{ $promo->status_label }}
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-3">
                                @if(Auth::user()->hasPermission('promotions.edit'))
                                <button onclick='editPromo(@json($promo))' 
                                        class="w-10 h-10 rounded-xl flex items-center justify-center text-gray-400 hover:text-[#FF7A00] hover:bg-orange-50 transition-all border border-transparent hover:border-orange-100">
                                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                                </button>
                                @endif
                                
                                @if(Auth::user()->hasPermission('promotions.delete'))
                                <form action="{{ route('admin.promotions.destroy', $promo->id) }}" method="POST" onsubmit="return confirm('¿Desea eliminar esta promoción?')">
                                    @csrf @method('DELETE')
                                    <button class="w-10 h-10 rounded-xl flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all border border-transparent hover:border-red-100">
                                        <i class="ph-bold ph-trash text-lg"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-24 text-center">
                            <div class="w-24 h-24 bg-gray-50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6 shadow-inner">
                                <i class="ph-duotone ph-tag text-6xl text-gray-200"></i>
                            </div>
                            <h3 class="text-xl font-black text-[#001144] mb-2 tracking-tight">No hay promociones</h3>
                            <p class="text-gray-400 font-medium max-w-xs mx-auto text-sm">Crea una nueva campaña para incentivar las inscripciones.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-8 border-t border-gray-50 bg-gray-50/30">{{ $promotions->links() }}</div>
    </div>

    <!-- MODAL PRINCIPAL -->
    <div id="promo-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-[#001144]/40 backdrop-blur-md transition-opacity" onclick="closePromoModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <form id="promoForm" action="{{ route('admin.promotions.store') }}" method="POST" 
                  class="relative transform overflow-hidden rounded-[2.5rem] bg-white shadow-2xl transition-all w-full max-w-2xl border border-white/20 flex flex-col max-h-[90vh]">
                @csrf
                <div id="method-field"></div> 

                <div class="bg-white px-10 py-8 border-b border-gray-50 flex justify-between items-center shrink-0">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-2xl bg-orange-50 flex items-center justify-center text-[#FF7A00]">
                            <i class="ph-fill ph-tag text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-[#001144] tracking-tight" id="modal-title">Gestión de Campaña</h3>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Configurar Descuentos</p>
                        </div>
                    </div>
                    <button type="button" onclick="closePromoModal()" class="w-12 h-12 rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
                        <i class="ph-bold ph-x text-xl"></i>
                    </button>
                </div>

                <div class="p-10 overflow-y-auto space-y-8 custom-scrollbar">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Título de la Campaña</label>
                        <input type="text" name="name" id="name" required 
                               class="w-full px-6 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] transition-all" 
                               placeholder="Ej: LANZAMIENTO EXCLUSIVO 2024">
                    </div>

                    <div class="p-8 bg-gray-50 rounded-[2rem] border border-gray-100">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-5 ml-1">Regla de Descuento</label>
                        <div class="flex flex-col sm:flex-row gap-5">
                            <div class="w-full sm:w-1/2 group">
                                <div class="relative">
                                    <select name="discount_type" id="discount_type" class="w-full pl-6 pr-10 py-4 rounded-[1.2rem] border border-white bg-white focus:border-[#FF7A00] outline-none text-sm font-black text-[#001144] appearance-none shadow-sm cursor-pointer transition-all">
                                        <option value="percent">Porcentaje (%)</option>
                                        <option value="fixed">Monto Fijo ({{ $platformSettings['app_currency'] ?? 'Bs' }})</option>
                                    </select>
                                    <i class="ph-bold ph-caret-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                </div>
                            </div>
                            <div class="flex-1 relative group">
                                <i class="ph-bold ph-currency-dollar absolute left-6 top-1/2 -translate-y-1/2 text-[#FF7A00] text-xl"></i>
                                <input type="number" name="value" id="value" required min="0" step="0.01" 
                                       class="w-full pl-14 pr-6 py-4 rounded-[1.2rem] border border-white bg-white focus:border-[#FF7A00] outline-none text-sm font-black text-[#001144] shadow-sm transition-all"
                                       placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Público Objetivo / Alcance:</label>
                        <div class="relative group mb-4">
                            <select name="target_type" id="target_type" onchange="toggleTargets()" 
                                    class="w-full pl-6 pr-10 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-black text-[#001144] appearance-none cursor-pointer transition-all">
                                <option value="all">Todo el Catálogo</option>
                                <option value="category">Categorías Específicas</option>
                                <option value="course">Programas Académicos</option>
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>

                        <!-- CURSOS -->
                        <div id="target-courses" class="hidden animate-fade-in space-y-4">
                            <div class="relative">
                                <i class="ph-bold ph-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" id="search-courses" onkeyup="filterOptions('search-courses', 'courses-select')" 
                                       placeholder="Filtrar cursos por nombre..." 
                                       class="w-full pl-12 pr-4 py-3.5 rounded-[1.2rem] border border-gray-100 bg-gray-50 text-xs focus:bg-white focus:border-[#FF7A00] outline-none transition-all">
                            </div>
                            <select id="courses-select" name="target_ids[]" multiple disabled 
                                    class="w-full p-4 rounded-[1.5rem] border border-gray-100 bg-gray-50 focus:bg-white outline-none text-xs font-bold text-[#001144] h-48 select-multiple custom-scrollbar transition-all">
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" data-categories="{{ json_encode($course->categories->pluck('id')) }}">
                                        {{ $course->type == 'diploma' ? '[DIPLOMADO] ' . $course->title : '[CURSO] ' . $course->title }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[9px] font-black text-[#FF7A00] uppercase tracking-widest flex items-center gap-2">
                                <i class="ph-bold ph-info"></i> Usa CTRL + Click para selección múltiple
                            </p>
                        </div>

                        <!-- CATEGORIAS -->
                        <div id="target-categories" class="hidden animate-fade-in space-y-6">
                            <div class="space-y-4">
                                <div class="relative">
                                    <i class="ph-bold ph-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    <input type="text" id="search-categories" onkeyup="filterOptions('search-categories', 'categories-select')" 
                                           placeholder="Filtrar categorías..." 
                                           class="w-full pl-12 pr-4 py-3.5 rounded-[1.2rem] border border-gray-100 bg-gray-50 text-xs focus:bg-white focus:border-[#FF7A00] outline-none transition-all">
                                </div>
                                <select id="categories-select" name="target_ids[]" multiple disabled onchange="updateExclusions()" 
                                        class="w-full p-4 rounded-[1.5rem] border border-gray-100 bg-gray-50 focus:bg-white outline-none text-xs font-bold text-[#001144] h-40 select-multiple custom-scrollbar transition-all">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- EXCLUSIONES -->
                            <div id="target-exclusions" class="hidden p-6 bg-red-50/30 rounded-[2rem] border border-red-50 space-y-4">
                                <label class="text-[10px] font-black text-red-500 uppercase tracking-widest flex items-center gap-2">
                                    <i class="ph-fill ph-prohibit"></i> Excepciones de Cursos:
                                </label>
                                <select id="exclusions-select" name="exception_ids[]" multiple 
                                        class="w-full p-4 rounded-[1.2rem] border border-red-100 bg-white focus:border-red-400 outline-none text-[10px] font-bold text-gray-600 h-32 select-multiple custom-scrollbar">
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" data-categories="{{ json_encode($course->categories->pluck('id')) }}" class="hidden logic-hidden">
                                            {{ $course->type == 'diploma' ? '[DIPLOMADO] ' . $course->title : '[CURSO] ' . $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Fecha de Inicio</label>
                            <input type="datetime-local" name="start_date" id="start_date" required 
                                   class="w-full px-6 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Fecha de Expiración</label>
                            <input type="datetime-local" name="end_date" id="end_date" required 
                                   class="w-full px-6 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] transition-all">
                        </div>
                    </div>
                </div>

                <div class="p-10 border-t border-gray-50 flex justify-end gap-4 shrink-0 bg-white">
                    <button type="button" onclick="closePromoModal()" 
                            class="px-8 py-4 bg-white text-gray-400 font-black uppercase tracking-widest rounded-[1.2rem] border border-gray-100 hover:bg-gray-50 transition-all text-[10px]">Cancelar</button>
                    <button type="submit" 
                            class="px-12 py-4 bg-[#001144] text-white font-black uppercase tracking-widest rounded-[1.2rem] hover:bg-[#FF7A00] transition-all shadow-xl shadow-blue-900/10 text-[10px] transform hover:-translate-y-1">
                        Guardar Campaña
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function filterOptions(inputId, selectId) {
            const filter = document.getElementById(inputId).value.toLowerCase();
            const options = document.getElementById(selectId).getElementsByTagName('option');
            for (let i = 0; i < options.length; i++) {
                const txtValue = options[i].textContent || options[i].innerText;
                if(!options[i].classList.contains('logic-hidden')) {
                    options[i].style.display = txtValue.toLowerCase().indexOf(filter) > -1 ? "" : "none";
                }
            }
        }

        function toggleTargets() {
            const type = document.getElementById('target_type').value;
            const coursesDiv = document.getElementById('target-courses');
            const catsDiv = document.getElementById('target-categories');
            const exclusionsDiv = document.getElementById('target-exclusions');
            const coursesSelect = document.getElementById('courses-select');
            const catsSelect = document.getElementById('categories-select');
            const excSelect = document.getElementById('exclusions-select');

            coursesDiv.classList.add('hidden');
            catsDiv.classList.add('hidden');
            exclusionsDiv.classList.add('hidden');
            coursesSelect.disabled = true;
            catsSelect.disabled = true;
            excSelect.disabled = true;

            if (type === 'course') {
                coursesDiv.classList.remove('hidden');
                coursesSelect.disabled = false;
                catsSelect.name = ''; 
                coursesSelect.name = 'target_ids[]';
            } else if (type === 'category') {
                catsDiv.classList.remove('hidden');
                exclusionsDiv.classList.remove('hidden');
                catsSelect.disabled = false;
                excSelect.disabled = false;
                coursesSelect.name = '';
                catsSelect.name = 'target_ids[]';
                updateExclusions();
            }
        }

        function updateExclusions() {
            const catsSelect = document.getElementById('categories-select');
            const selectedCatIds = Array.from(catsSelect.selectedOptions).map(opt => parseInt(opt.value));
            const excOptions = document.querySelectorAll('#exclusions-select option');

            excOptions.forEach(opt => {
                const courseCats = JSON.parse(opt.getAttribute('data-categories') || '[]');
                const hasMatch = courseCats.some(catId => selectedCatIds.includes(catId));
                
                if (hasMatch) {
                    opt.classList.remove('hidden', 'logic-hidden');
                    opt.style.display = "";
                } else {
                    opt.classList.add('hidden', 'logic-hidden');
                    opt.style.display = "none";
                    opt.selected = false;
                }
            });
        }

        function openPromoModal() {
            document.getElementById('promoForm').reset();
            document.getElementById('promoForm').action = "{{ route('admin.promotions.store') }}";
            document.getElementById('method-field').innerHTML = '';
            document.getElementById('modal-title').innerText = 'Nueva Promoción';
            document.querySelectorAll('#exclusions-select option').forEach(o => { o.selected = false; o.classList.add('hidden', 'logic-hidden'); });
            toggleTargets();
            document.getElementById('promo-modal').classList.remove('hidden');
        }

        function editPromo(promo) {
            const form = document.getElementById('promoForm');
            form.action = `/admin/promociones/${promo.id}`;
            document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('modal-title').innerText = 'Editar Promoción';

            document.getElementById('name').value = promo.name;
            document.getElementById('discount_type').value = promo.discount_type;
            document.getElementById('value').value = promo.value;
            document.getElementById('target_type').value = promo.target_type;
            
            if(promo.local_start_date) {
                document.getElementById('start_date').value = promo.local_start_date;
            }
            if(promo.local_end_date) {
                document.getElementById('end_date').value = promo.local_end_date;
            }

            toggleTargets();

            const parseToArr = (val) => {
                if (!val) return [];
                if (Array.isArray(val)) return val.map(String);
                try { 
                    const parsed = JSON.parse(val); 
                    return Array.isArray(parsed) ? parsed.map(String) : [];
                } catch(e) { return []; }
            };

            const targetIds = parseToArr(promo.target_ids);
            const exceptionIds = parseToArr(promo.exception_ids);

            if (promo.target_type === 'course') {
                const select = document.getElementById('courses-select');
                Array.from(select.options).forEach(opt => opt.selected = targetIds.includes(String(opt.value)));
            } else if (promo.target_type === 'category') {
                const select = document.getElementById('categories-select');
                Array.from(select.options).forEach(opt => opt.selected = targetIds.includes(String(opt.value)));
                updateExclusions();
                const excSelect = document.getElementById('exclusions-select');
                Array.from(excSelect.options).forEach(opt => {
                    if (exceptionIds.includes(String(opt.value))) opt.selected = true;
                });
            }

            document.getElementById('promo-modal').classList.remove('hidden');
        }

        function closePromoModal() { document.getElementById('promo-modal').classList.add('hidden'); }
    </script>
</x-layouts.admin>