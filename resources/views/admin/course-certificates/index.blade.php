<x-layouts.admin>
    <x-slot name="title">Certificados de Cursos y Diplomados - Gestión</x-slot>
    <x-slot name="header">Certificados de Cursos y Diplomados</x-slot>

    <!-- MENSAJES FLASH -->
    @if(session('success'))
        <div class="mb-8 p-4 rounded-[1.5rem] bg-green-50 border border-green-100 text-green-700 flex items-center gap-3 animate-bounce-in shadow-sm">
            <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center shadow-lg shadow-green-500/20">
                <i class="ph-bold ph-check text-sm"></i>
            </div>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <!-- BARRA DE HERRAMIENTAS -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-10 fade-in-up" style="animation-delay: 0.1s">
        
        <!-- Buscador -->
        <div class="flex flex-wrap gap-4 w-full md:w-auto">
            <div class="relative w-full md:w-96">
                <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="certSearch" placeholder="Buscar certificados..." class="w-full pl-12 pr-4 py-3.5 rounded-[1.2rem] border border-gray-100 focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/5 outline-none transition-all text-sm font-medium bg-white shadow-sm" onkeyup="filterCertificates()">
            </div>
        </div>

        <!-- Botones de Acción Global -->
        <div class="flex gap-3 w-full md:w-auto">
            @if(Auth::user()->hasPermission('individual-certificates.create'))
            <a href="{{ route('admin.course-certificates.create') }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-8 py-3.5 bg-[#001144] text-white rounded-[1.2rem] hover:bg-[#002266] transition-all shadow-xl shadow-[#001144]/20 text-sm font-black uppercase tracking-wider">
                <i class="ph-bold ph-plus"></i> Nuevo Certificado de Curso
            </a>
            @endif
        </div>
    </div>

    <!-- TABLA DE CERTIFICADOS -->
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-100/50 border border-gray-50 overflow-hidden fade-in-up" style="animation-delay: 0.2s">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse" id="certsTable">
                <thead>
                    <tr class="bg-gray-50/30 text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] border-b border-gray-50">
                        <th class="px-8 py-5 w-16 text-center">ID</th>
                        <th class="px-8 py-5">Información</th>
                        <th class="px-8 py-5">Curso / Diplomado Vinculado</th>
                        <th class="px-8 py-5">Instructor</th>
                        <th class="px-8 py-5 text-center">Carga Horaria</th>
                        <th class="px-8 py-5">Estado</th>
                        <th class="px-8 py-5 text-right">Gestión</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    
                    @forelse($certificates as $cert)
                    <tr class="hover:bg-gray-50/50 transition-all duration-300 group cert-row"
                        data-title="{{ $cert->title }}"
                        data-course="{{ $cert->course->title ?? '' }}">
                        <!-- NUMERACIÓN -->
                        <td class="px-8 py-6 text-center font-black text-gray-300">
                            {{ $loop->iteration + ($certificates->currentPage() - 1) * $certificates->perPage() }}
                        </td>

                        <!-- INFORMACIÓN -->
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-orange-50 text-[#FF7A00] flex items-center justify-center shrink-0 border border-white shadow-sm ring-2 ring-gray-50">
                                    <i class="ph-fill ph-certificate text-2xl"></i>
                                </div>
                                <div class="max-w-[250px]">
                                    <p class="font-black text-[#001144] text-base leading-tight mb-1 group-hover:text-[#FF7A00] transition-colors line-clamp-1" title="{{ $cert->title }}">{{ $cert->title }}</p>
                                    <span class="text-[10px] text-gray-400 font-black uppercase tracking-wider">Reg: {{ $cert->registration_number ?? 'CP-AUTOGEN' }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- CURSO VINCULADO -->
                        <td class="px-8 py-6">
                            @if($cert->course)
                                <div class="flex flex-col">
                                    <span class="font-bold text-[#001144] line-clamp-1" title="{{ $cert->course->title }}">{{ $cert->course->title }}</span>
                                    <span class="text-[9px] font-black uppercase tracking-widest mt-1 {{ $cert->course->type === 'diploma' ? 'text-purple-600 bg-purple-50' : 'text-blue-600 bg-blue-50' }} px-2 py-0.5 rounded-full w-max">
                                        {{ $cert->course->type === 'diploma' ? 'Diplomado' : 'Curso' }}
                                    </span>
                                </div>
                            @else
                                <span class="text-xs text-gray-400 italic">No vinculado</span>
                            @endif
                        </td>

                        <!-- INSTRUCTOR -->
                        <td class="px-8 py-6 font-bold text-gray-700">
                            {{ $cert->instructor->name ?? 'Sin asignar' }}
                        </td>

                        <!-- CARGA HORARIA -->
                        <td class="px-8 py-6 text-center">
                            <div class="inline-flex flex-col items-center">
                                <span class="font-black text-[#001144] text-xl tracking-tighter">{{ $cert->hours ?? 0 }}</span>
                                <span class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Horas</span>
                            </div>
                        </td>

                        <!-- ESTADO -->
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full {{ $cert->status === 'published' ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.4)]' : 'bg-orange-400 shadow-[0_0_8px_rgba(251,146,60,0.4)]' }}"></div>
                                <span class="text-[10px] font-black uppercase tracking-widest {{ $cert->status === 'published' ? 'text-green-600' : 'text-orange-500' }}">
                                    {{ $cert->status === 'published' ? 'Publicado' : 'Borrador' }}
                                </span>
                            </div>
                        </td>

                        <!-- ACCIONES -->
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(Auth::user()->hasPermission('individual-certificates.edit'))
                                <a href="{{ route('admin.course-certificates.edit', $cert->id) }}" class="w-10 h-10 rounded-[0.8rem] flex items-center justify-center text-gray-400 hover:text-[#FF7A00] hover:bg-orange-50 transition-all duration-300" title="Editar Diseño">
                                    <i class="ph-bold ph-pencil-simple text-xl"></i>
                                </a>
                                @endif
                                
                                @if(Auth::user()->hasPermission('individual-certificates.delete'))
                                <form action="{{ route('admin.course-certificates.destroy', $cert->id) }}" method="POST" onsubmit="return confirm('¿Seguro que desea eliminar este certificado? Se desvinculará del curso.');" class="inline">
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
                                    <i class="ph-duotone ph-certificate text-5xl text-gray-200"></i>
                                </div>
                                <h3 class="text-xl font-black text-gray-600 mb-2">No se encontraron certificados</h3>
                                <p class="text-sm text-gray-400 font-medium mb-8 max-w-sm">Aún no hay certificados de cursos registrados.</p>
                                <a href="{{ route('admin.course-certificates.create') }}" class="px-8 py-3.5 bg-[#FF7A00] text-white rounded-[1.2rem] font-black text-sm hover:bg-[#e66e00] transition-all shadow-xl shadow-[#FF7A00]/20 uppercase tracking-wider">
                                    Crear mi primer certificado
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
        
        <!-- PAGINACIÓN -->
        <div class="px-8 py-6 bg-gray-50/30 border-t border-gray-50">
            {{ $certificates->links() }}
        </div>
    </div>

    <script>
        function filterCertificates() {
            const query = document.getElementById('certSearch').value.toLowerCase();
            const rows = document.querySelectorAll('.cert-row');
            
            rows.forEach(row => {
                const title = row.dataset.title.toLowerCase();
                const course = row.dataset.course.toLowerCase();
                
                if (title.includes(query) || course.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</x-layouts.admin>
