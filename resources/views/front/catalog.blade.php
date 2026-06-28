<x-layouts.guest>

    <!-- CABECERA DE PÁGINA (Modernizada y Compacta) -->
    <div class="relative bg-[#001144] pt-28 pb-14 overflow-hidden">
        <!-- Elementos decorativos de fondo -->
        <div class="absolute inset-0 z-0">
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-[#FF7A00] opacity-10 rounded-full blur-[150px] pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-[#0033CC] opacity-20 rounded-full blur-[100px] pointer-events-none"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:32px_32px] opacity-[0.03]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-12">
                <div class="text-left max-w-2xl">
                    <span class="inline-block bg-[#FF7A00] text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-[0.2em] mb-6 fade-in-up">
                        {{ isset($isDiplomados) ? ($platformSettings['diplomas_header_tag'] ?? 'Nuestros Diplomados') : ($platformSettings['courses_header_tag'] ?? 'Nuestra Oferta Académica') }}
                    </span>
                    <h1 class="text-5xl md:text-7xl font-black text-white mb-6 leading-tight fade-in-up delay-100">
                        {!! isset($isDiplomados) ? ($platformSettings['diplomas_header_title'] ?? 'Diplomados <br><span class="text-[#FF7A00]">Profesionales</span>') : ($platformSettings['courses_header_title'] ?? 'Especialízate <br><span class="text-[#FF7A00]">Sin Límites</span>') !!}
                    </h1>
                    <p class="text-gray-300 text-xl leading-relaxed fade-in-up delay-200">
                        {{ isset($isDiplomados) ? ($platformSettings['diplomas_header_desc'] ?? 'Programas avanzados de especialización con certificación oficial y validez nacional.') : ($platformSettings['courses_header_desc'] ?? 'Programas diseñados para responder a las demandas reales del mercado laboral, con certificación nacional.') }}
                    </p>
                </div>
                <!-- Stats en el header -->
                <div class="grid grid-cols-2 gap-4 fade-in-up delay-300">
                    <div class="bg-white/5 backdrop-blur-xl border border-white/10 p-6 rounded-3xl text-center">
                        <p class="text-3xl font-black text-[#FF7A00]">
                            {{ isset($isDiplomados) ? ($platformSettings['diplomas_stat1_val'] ?? '+150') : ($platformSettings['courses_stat1_val'] ?? '+150') }}
                        </p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">
                            {{ isset($isDiplomados) ? ($platformSettings['diplomas_stat1_lbl'] ?? 'Cursos') : ($platformSettings['courses_stat1_lbl'] ?? 'Cursos') }}
                        </p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-xl border border-white/10 p-6 rounded-3xl text-center">
                        <p class="text-3xl font-black text-white">
                            {{ isset($isDiplomados) ? ($platformSettings['diplomas_stat2_val'] ?? '98%') : ($platformSettings['courses_stat2_val'] ?? '98%') }}
                        </p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">
                            {{ isset($isDiplomados) ? ($platformSettings['diplomas_stat2_lbl'] ?? 'Éxito') : ($platformSettings['courses_stat2_lbl'] ?? 'Éxito') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN PRINCIPAL -->
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-12">
                
                <!-- BARRA DE FILTROS HORIZONTAL -->
                <div class="fade-in-up delay-200">
                    <form id="filter-form" method="GET" action="{{ isset($isDiplomados) ? route('diplomados') : route('catalog') }}">
                        <div class="bg-white p-4.5 rounded-[2rem] shadow-2xl shadow-gray-200/40 border border-gray-100 flex flex-wrap items-center gap-2.5">
                            <h3 class="font-black text-[#001144] px-3 flex items-center gap-1.5 text-xs border-r border-gray-100 mr-1.5">
                                <i class="ph-fill ph-funnel text-[#FF7A00]"></i> Áreas:
                            </h3>
                            
                            <!-- "Todas" Button -->
                            <label class="cursor-pointer group">
                                <input type="checkbox" id="check-all" class="hidden peer" {{ !request()->has('categories') ? 'checked' : '' }}>
                                <span class="px-4 py-2 rounded-xl border-2 border-gray-50 text-gray-500 font-bold text-[10px] transition-all peer-checked:bg-[#001144] peer-checked:text-white peer-checked:border-[#001144] group-hover:border-[#FF7A00]/30 block uppercase tracking-widest">
                                    Todas
                                </span>
                            </label>

                            @foreach($categories as $category)
                                <label class="cursor-pointer group">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="category-checkbox hidden peer" {{ in_array($category->id, request()->get('categories', [])) ? 'checked' : '' }}>
                                    <span class="px-4 py-2 rounded-xl border-2 border-gray-50 text-gray-500 font-bold text-[10px] transition-all peer-checked:bg-[#FF7A00] peer-checked:text-white peer-checked:border-[#FF7A00] group-hover:border-[#FF7A00]/30 block uppercase tracking-widest flex items-center gap-1.5">
                                        {{ $category->name }}
                                        <span class="text-[9px] opacity-60">({{ $category->courses_count }})</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </form>
                </div>

                <!-- CUADRÍCULA DE CURSOS -->
                <div class="w-full">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6 fade-in-up delay-200">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-[#FF7A00]/10 rounded-2xl flex items-center justify-center text-[#FF7A00]">
                                <i class="ph-bold ph-magnifying-glass text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-[#001144] font-black text-xl">
                                    <span id="course-count">{{ $courses->total() }}</span> Programas
                                </p>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Encontrados para ti</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-4 w-full md:w-auto">
                            <div class="relative w-full md:w-80">
                                <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" id="search-input" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre o tema..." class="w-full pl-12 pr-6 py-4 rounded-2xl border-2 border-gray-100 focus:border-[#FF7A00] outline-none text-sm transition-all shadow-sm">
                            </div>
                        </div>
                    </div>

                    <div id="catalog-container" class="relative min-h-[400px]">
                        @include('front.partials.catalog-grid')
                        
                        <!-- Pantalla de carga -->
                        <div id="grid-loader" class="hidden absolute inset-0 bg-white/50 backdrop-blur-[1px] z-30 flex items-center justify-center rounded-2xl">
                            <div class="flex flex-col items-center gap-2">
                                <div class="animate-spin rounded-full h-12 w-12 border-4 border-[#FF7A00] border-t-transparent"></div>
                                <span class="text-xs font-black text-[#001144] uppercase tracking-[0.2em]">Actualizando Catálogo...</span>
                            </div>
                        </div>
                    </div>

                    <!-- PAGINACIÓN -->
                    <div id="pagination-container" class="mt-12 flex justify-center">
                        {{ $courses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE VALOR (Refuerzo de marca) -->
    <div class="py-16 bg-gray-50 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="flex items-center gap-6 p-8 bg-white rounded-3xl shadow-sm border border-gray-100 group hover:border-[#FF7A00] transition-all">
                    <div class="w-16 h-16 bg-[#FF7A00]/10 rounded-2xl flex items-center justify-center text-[#FF7A00] group-hover:bg-[#FF7A00] group-hover:text-white transition-all">
                        <i class="ph-bold ph-certificate text-3xl"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-[#001144]">{{ $platformSettings['courses_val1_title'] ?? 'Certificación Nacional' }}</h4>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">{{ $platformSettings['courses_val1_desc'] ?? 'Validez Profesional' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-6 p-8 bg-white rounded-3xl shadow-sm border border-gray-100 group hover:border-[#0033CC] transition-all">
                    <div class="w-16 h-16 bg-[#001144]/10 rounded-2xl flex items-center justify-center text-[#001144] group-hover:bg-[#001144] group-hover:text-white transition-all">
                        <i class="ph-bold ph-users text-3xl"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-[#001144]">{{ $platformSettings['courses_val2_title'] ?? 'Asesores Expertos' }}</h4>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">{{ $platformSettings['courses_val2_desc'] ?? 'Acompañamiento Real' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-6 p-8 bg-white rounded-3xl shadow-sm border border-gray-100 group hover:border-[#FF7A00] transition-all">
                    <div class="w-16 h-16 bg-[#FF7A00]/10 rounded-2xl flex items-center justify-center text-[#FF7A00] group-hover:bg-[#FF7A00] group-hover:text-white transition-all">
                        <i class="ph-bold ph-infinity text-3xl"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-[#001144]">{{ $platformSettings['courses_val3_title'] ?? 'Acceso de por Vida' }}</h4>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">{{ $platformSettings['courses_val3_desc'] ?? 'A tu propio ritmo' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('filter-form');
        const searchInput = document.getElementById('search-input');
        const container = document.getElementById('catalog-container');
        const loader = document.getElementById('grid-loader');
        const countDisplay = document.getElementById('course-count');
        const checkAll = document.getElementById('check-all');
        const categoryChecks = document.querySelectorAll('.category-checkbox');
        
        let timeout = null;

        // Proceso AJAX para actualizar el contenido sin recargar la página
        const updateCatalog = (url = null) => {
            if(loader) loader.classList.remove('hidden');
            
            if (!url) {
                const formData = new FormData(form);
                if (searchInput) formData.append('search', searchInput.value);
                const params = new URLSearchParams(formData);
                url = `{{ isset($isDiplomados) ? route('diplomados') : route('catalog') }}?${params.toString()}`;
            }
            
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                container.innerHTML = html;
                const newCount = document.getElementById('js-course-count-value');
                if(newCount) countDisplay.innerText = newCount.innerText;
                
                if(loader) loader.classList.add('hidden');
            })
            .catch(err => {
                console.error('Error al actualizar el catálogo:', err);
                if(loader) loader.classList.add('hidden');
            });
        };

        // Búsqueda de cursos (Debounce para evitar peticiones excesivas)
        if(searchInput) {
            searchInput.addEventListener('input', () => {
                clearTimeout(timeout);
                timeout = setTimeout(() => updateCatalog(), 500);
            });
        }

        // Selección de categorías
        categoryChecks.forEach(check => {
            check.addEventListener('change', () => {
                if (check.checked) checkAll.checked = false;
                const anyChecked = Array.from(categoryChecks).some(c => c.checked);
                if (!anyChecked) checkAll.checked = true;
                updateCatalog();
            });
        });

        // Opción "Todas las áreas"
        checkAll.addEventListener('change', () => {
            if (checkAll.checked) {
                categoryChecks.forEach(c => c.checked = false);
                updateCatalog();
            }
        });

        // Intercepción de clicks en la paginación para usar AJAX
        document.addEventListener('click', (e) => {
            const link = e.target.closest('#pagination-container a, .pagination a');
            if (link) {
                e.preventDefault();
                updateCatalog(link.href);
                window.scrollTo({ top: 300, behavior: 'smooth' });
            }
        });
    });
    </script>
</x-layouts.guest>