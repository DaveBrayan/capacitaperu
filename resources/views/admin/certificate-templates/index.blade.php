<x-layouts.admin>
    <x-slot name="title">Plantillas de Certificado</x-slot>
    <x-slot name="header">Gestión de Plantillas de Diploma</x-slot>

    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 md:px-8 pb-20 space-y-8">
        <!-- HEADER & ACTIONS -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-6 sm:p-8 rounded-[2rem] border border-gray-100 shadow-sm">
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-orange-50 text-[#FF7A00] flex items-center justify-center font-black">
                        <i class="ph-bold ph-layout text-xl"></i>
                    </div>
                    <h1 class="text-2xl font-black text-[#001144] tracking-tight">Plantillas de Certificado</h1>
                </div>
                <p class="text-sm font-medium text-gray-400 pl-1">
                    Crea y guarda diseños maestros para importarlos al instante en certificados de cursos, diplomados e individuales.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.certificate-templates.create') }}" 
                   class="flex items-center justify-center gap-2.5 px-6 py-3.5 bg-gradient-to-r from-[#FF7A00] to-[#FF9433] hover:from-[#e66e00] hover:to-[#FF7A00] text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-[#FF7A00]/25 transition-all duration-300 transform hover:-translate-y-0.5">
                    <i class="ph-bold ph-plus text-base"></i>
                    <span>Nueva Plantilla</span>
                </a>
            </div>
        </div>

        <!-- SEARCH BAR -->
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between gap-4">
            <form method="GET" action="{{ route('admin.certificate-templates.index') }}" class="flex-1 max-w-md relative">
                <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Buscar plantilla por nombre..." 
                       class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:outline-none focus:border-[#FF7A00] focus:bg-white transition-all">
                @if(request('search'))
                    <a href="{{ route('admin.certificate-templates.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 hover:text-gray-600">
                        <i class="ph-bold ph-x"></i>
                    </a>
                @endif
            </form>
            <span class="text-xs font-bold text-gray-400">
                Total: {{ $templates->total() }} {{ $templates->total() == 1 ? 'plantilla' : 'plantillas' }}
            </span>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-800 text-xs font-bold shadow-sm">
                <i class="ph-bold ph-check-circle text-lg text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3 text-red-800 text-xs font-bold shadow-sm">
                <i class="ph-bold ph-warning-circle text-lg text-red-600"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- TEMPLATES GRID -->
        @if($templates->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($templates as $tpl)
                    @php
                        $data = $tpl->template_data ?? [];
                        $pagesCount = $data['pages'] ?? 1;
                        $elCount = count($data['elements'] ?? []) + count($data['elements2'] ?? []);
                        $bgThumb = $data['background'] ?? null;
                    @endphp
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group hover:-translate-y-1">
                        <!-- THUMBNAIL / PREVIEW -->
                        <div class="relative w-full h-44 bg-gradient-to-br from-slate-100 to-slate-200 overflow-hidden flex items-center justify-center border-b border-gray-100">
                            @if($bgThumb)
                                <img src="{{ $bgThumb }}" alt="{{ $tpl->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                            @else
                                <div class="flex flex-col items-center gap-2 text-slate-400 p-4 text-center">
                                    <div class="w-14 h-14 rounded-2xl bg-white/80 shadow-sm flex items-center justify-center text-slate-400">
                                        <i class="ph-duotone ph-certificate text-3xl text-[#001144]/40"></i>
                                    </div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Fondo Predeterminado</span>
                                </div>
                            @endif

                            <!-- BADGES -->
                            <div class="absolute top-3 left-3 flex items-center gap-1.5">
                                <span class="px-2.5 py-1 bg-[#001144]/90 backdrop-blur-md text-white text-[9px] font-black rounded-lg shadow uppercase tracking-wider">
                                    {{ $pagesCount == 2 ? '2 Hojas' : '1 Hoja' }}
                                </span>
                            </div>

                            <div class="absolute top-3 right-3">
                                <span class="px-2.5 py-1 bg-white/90 backdrop-blur-md text-[#FF7A00] text-[9px] font-bold rounded-lg shadow flex items-center gap-1">
                                    <i class="ph-bold ph-shapes"></i>
                                    {{ $elCount }} elementos
                                </span>
                            </div>
                        </div>

                        <!-- CONTENT -->
                        <div class="p-5 flex-1 flex flex-col justify-between gap-4">
                            <div>
                                <h3 class="font-black text-sm text-[#001144] line-clamp-1 group-hover:text-[#FF7A00] transition-colors" title="{{ $tpl->title }}">
                                    {{ $tpl->title }}
                                </h3>
                                @if($tpl->description)
                                    <p class="text-[11px] text-gray-400 mt-1 line-clamp-2 leading-relaxed">
                                        {{ $tpl->description }}
                                    </p>
                                @endif
                                <div class="flex items-center gap-2 text-[10px] text-gray-400 mt-2 font-medium">
                                    <i class="ph-bold ph-clock"></i>
                                    <span>Actualizado {{ $tpl->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <!-- ACTIONS -->
                            <div class="pt-3 border-t border-gray-100 flex items-center justify-between gap-2">
                                <a href="{{ route('admin.certificate-templates.edit', $tpl->id) }}" 
                                   class="flex-1 py-2 px-3 bg-[#001144] hover:bg-[#FF7A00] text-white rounded-xl text-center text-xs font-black transition-all duration-300 flex items-center justify-center gap-1.5 shadow-sm">
                                    <i class="ph-bold ph-pencil-simple"></i>
                                    <span>Editar Diseño</span>
                                </a>

                                <form action="{{ route('admin.certificate-templates.duplicate', $tpl->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" title="Duplicar Plantilla" class="w-8 h-8 rounded-xl bg-gray-50 hover:bg-blue-50 text-gray-400 hover:text-blue-600 transition-colors flex items-center justify-center border border-gray-100">
                                        <i class="ph-bold ph-copy"></i>
                                    </button>
                                </form>

                                <form action="{{ route('admin.certificate-templates.destroy', $tpl->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar la plantilla \'{{ $tpl->title }}\'?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Eliminar Plantilla" class="w-8 h-8 rounded-xl bg-gray-50 hover:bg-red-50 text-gray-400 hover:text-red-600 transition-colors flex items-center justify-center border border-gray-100">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- PAGINATION -->
            <div class="mt-8">
                {{ $templates->links() }}
            </div>
        @else
            <!-- EMPTY STATE -->
            <div class="bg-white rounded-3xl border border-gray-100 p-16 text-center shadow-sm max-w-xl mx-auto">
                <div class="w-20 h-20 rounded-3xl bg-orange-50 text-[#FF7A00] flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <i class="ph-duotone ph-layout text-4xl"></i>
                </div>
                <h3 class="text-xl font-black text-[#001144]">No se encontraron plantillas</h3>
                <p class="text-xs text-gray-400 mt-2 max-w-sm mx-auto font-medium leading-relaxed">
                    Crea tu primera plantilla de certificado con el diseñador visual para reutilizar su fondo, sellos, firmas y disposición en todos tus cursos y diplomados.
                </p>
                <div class="mt-8">
                    <a href="{{ route('admin.certificate-templates.create') }}" 
                       class="inline-flex items-center gap-2 px-8 py-3.5 bg-[#FF7A00] text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-[#FF7A00]/20 hover:bg-[#e66e00] transition-all">
                        <i class="ph-bold ph-plus"></i>
                        <span>Crear Primera Plantilla</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-layouts.admin>
