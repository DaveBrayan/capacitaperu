<div class="overflow-x-auto no-scrollbar">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50/30 text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] border-b border-gray-50">
                <th class="px-8 py-5 w-16 text-center">ID</th>
                <th class="px-8 py-5">Información del Programa</th>
                <th class="px-8 py-5">Instructor</th>
                <th class="px-8 py-5">Categoría / Estado</th>
                <th class="px-8 py-5 text-center">Alcance</th>
                <th class="px-8 py-5">Inversión</th>
                <th class="px-8 py-5 text-right">Gestión</th>
            </tr>
        </thead>
        <tbody class="text-sm divide-y divide-gray-50">
            @forelse($courses as $course)
            <tr class="hover:bg-gray-50/50 transition-all duration-300 group">
                <!-- NUMERACIÓN -->
                <td class="px-8 py-6 text-center font-black text-gray-300">
                    {{ $loop->iteration + ($courses->currentPage() - 1) * $courses->perPage() }}
                </td>

                <!-- CURSO -->
                <td class="px-8 py-6">
                    <div class="flex items-center gap-4">
                        <div class="w-20 h-14 rounded-2xl bg-gray-100 overflow-hidden shrink-0 border border-white shadow-sm ring-2 ring-gray-50 relative">
                            @if($course->cover_image)
                                <img src="{{ asset($course->cover_image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-300">
                                    <i class="ph-fill ph-image text-2xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="max-w-[250px]">
                            <p class="font-black text-[#001144] text-base leading-tight mb-1 group-hover:text-[#FF7A00] transition-colors line-clamp-1" title="{{ $course->title }}">{{ $course->title }}</p>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-gray-400 font-black uppercase tracking-wider">{{ $course->level }}</span>
                                <span class="w-1 h-1 rounded-full bg-gray-200"></span>
                                <span class="text-[10px] text-gray-400 font-black uppercase tracking-wider">{{ $course->duration ?? 'Duración N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </td>
                
                <!-- INSTRUCTOR -->
                <td class="px-8 py-6">
                    <div class="flex items-center gap-3">
                        @if($course->instructor)
                            <div class="w-8 h-8 rounded-full border-2 border-white shadow-sm overflow-hidden ring-1 ring-gray-100">
                                <img src="{{ $course->instructor->avatar_url ? asset($course->instructor->avatar_url) : 'https://ui-avatars.com/api/?name='.urlencode($course->instructor->name).'&background=001144&color=fff' }}" class="w-full h-full object-cover">
                            </div>
                            <span class="text-[#001144] font-bold text-xs">{{ $course->instructor->name }}</span>
                        @else
                            <div class="w-8 h-8 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center">
                                <i class="ph-bold ph-user-minus text-xs text-gray-300"></i>
                            </div>
                            <span class="text-gray-300 font-bold text-[10px] uppercase italic">Sin Docente</span>
                        @endif
                    </div>
                </td>

                <!-- CATEGORÍA / ESTADO / CREADOR -->
                <td class="px-8 py-6">
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-wrap gap-1">
                            @forelse($course->categories as $cat)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-50 text-[#001144] border border-blue-100">
                                    <i class="ph-fill {{ $cat->icon ?? 'ph-tag' }}"></i> {{ $cat->name }}
                                </span>
                            @empty
                                <span class="text-[10px] text-gray-300 font-bold uppercase">General</span>
                            @endforelse
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full {{ $course->status === 'published' ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.4)]' : 'bg-orange-400 shadow-[0_0_8px_rgba(251,146,60,0.4)]' }}"></div>
                            <span class="text-[10px] font-black uppercase tracking-widest {{ $course->status === 'published' ? 'text-green-600' : 'text-orange-500' }}">
                                {{ $course->status === 'published' ? 'Publicado' : 'Borrador' }}
                            </span>
                        </div>
                        <!-- Creador / Staff -->
                        <div class="flex items-center gap-1.5 text-[10px] text-gray-400 font-semibold pt-1 border-t border-gray-100/70">
                            <i class="ph-bold ph-user-circle text-gray-400 text-xs"></i>
                            <span class="truncate">Por: <strong class="text-[#001144] font-bold">{{ $course->user ? $course->user->name : 'Administrador' }}</strong></span>
                        </div>
                    </div>
                </td>

                <!-- ALCANCE (CONTEO REAL) -->
                <td class="px-8 py-6 text-center">
                    <div class="inline-flex flex-col items-center">
                        <span class="font-black text-[#001144] text-xl tracking-tighter">{{ $course->enrollments_count }}</span>
                        <span class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Alumnos</span>
                    </div>
                </td>

                <!-- PRECIO -->
                <td class="px-8 py-6">
                    <div class="flex flex-col">
                        @if($course->discount_price > 0 && $course->discount_price < $course->price)
                            <span class="font-black text-[#FF7A00] text-base tracking-tighter">{{ $platformSettings['app_currency'] ?? 'Bs' }} {{ number_format($course->discount_price, 2) }}</span>
                            <span class="text-[10px] text-gray-300 font-bold line-through">{{ $platformSettings['app_currency'] ?? 'Bs' }} {{ number_format($course->price, 2) }}</span>
                        @else
                            <span class="font-black text-[#001144] text-base tracking-tighter">{{ $platformSettings['app_currency'] ?? 'Bs' }} {{ number_format($course->price, 2) }}</span>
                        @endif
                    </div>
                </td>

                <!-- ACCIONES -->
                <td class="px-8 py-6 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <button type="button" onclick="showCourseDetails({{ $course->id }})" class="w-10 h-10 rounded-[0.8rem] flex items-center justify-center text-gray-400 hover:text-[#001144] hover:bg-gray-100 transition-all duration-300" title="Ver Detalles">
                            <i class="ph-bold ph-eye text-xl"></i>
                        </button>
                        
                        @if(Auth::user()->hasPermission('courses.grade'))
                        <button type="button" onclick="showGradeModal({{ $course->id }})" class="w-10 h-10 rounded-[0.8rem] flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-all duration-300 relative" title="Revisar Entregas">
                            <i class="ph-bold ph-article text-xl"></i>
                            @if($course->pending_projects_count > 0)
                                <span class="absolute top-1 right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white shadow-sm animate-pulse"></span>
                            @endif
                        </button>
                        @endif

                        @if(Auth::user()->hasPermission('courses.create'))
                        <a href="{{ route(($routeNamePrefix ?? 'admin.courses.') . 'edit', $course->id) }}" class="w-10 h-10 rounded-[0.8rem] flex items-center justify-center text-gray-400 hover:text-[#FF7A00] hover:bg-orange-50 transition-all duration-300" title="Editar">
                            <i class="ph-bold ph-pencil-simple text-xl"></i>
                        </a>
                        
                        <form action="{{ route(($routeNamePrefix ?? 'admin.courses.') . 'destroy', $course->id) }}" method="POST" onsubmit="return confirm('¿Seguro que desea eliminar permanentemente este {{ strtolower($viewType ?? 'curso') }}?');" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-10 h-10 rounded-[0.8rem] flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all duration-300" title="Eliminar">
                                <i class="ph-bold ph-trash text-xl"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-8 py-20 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-24 h-24 bg-gray-50 rounded-[2rem] flex items-center justify-center mb-6 ring-8 ring-gray-50/50">
                            <i class="ph-duotone ph-book-open-text text-5xl text-gray-200"></i>
                        </div>
                        <h3 class="text-xl font-black text-gray-600 mb-2">No se encontraron {{ strtolower($viewType ?? 'cursos') }}s</h3>
                        <p class="text-sm text-gray-400 font-medium mb-8 max-w-sm">No hay registros que coincidan con los criterios de búsqueda o filtros.</p>
                        @if(Auth::user()->hasPermission('courses.create'))
                        <a href="{{ route(($routeNamePrefix ?? 'admin.courses.') . 'create') }}" class="px-8 py-3.5 bg-[#FF7A00] text-white rounded-[1.2rem] font-black text-sm hover:bg-[#e66e00] transition-all shadow-xl shadow-[#FF7A00]/20">
                            Crear {{ strtolower($viewType ?? 'curso') }}
                        </a>
                        @endif
                    </div>
                </td>
            </tr>
            @endforelse

        </tbody>
    </table>
</div>

<!-- PAGINACIÓN -->
<div class="px-8 py-6 bg-gray-50/30 border-t border-gray-50">
    {{ $courses->links() }}
</div>
