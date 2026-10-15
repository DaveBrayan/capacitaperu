<div class="overflow-x-auto no-scrollbar">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50/30 text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] border-b border-gray-50">
                <th class="px-8 py-5 w-16 text-center">ID</th>
                <th class="px-8 py-5">Información</th>
                <th class="px-8 py-5">Categoría / Estado</th>
                <th class="px-8 py-5 text-center">Carga Horaria</th>
                <th class="px-8 py-5">Fechas</th>
                <th class="px-8 py-5 text-right">Gestión</th>
            </tr>
        </thead>
        <tbody class="text-sm divide-y divide-gray-50">
            @forelse($certificates as $cert)
            <tr class="hover:bg-gray-50/50 transition-all duration-300 group"
                data-title="{{ $cert->title }}"
                data-hours="{{ $cert->hours ?? 0 }}"
                data-reg="{{ $cert->registration_number ?? 'S/N' }}"
                data-status-text="{{ $cert->status === 'published' ? 'Publicado' : 'Borrador' }}"
                data-status-class="{{ $cert->status === 'published' ? 'text-green-600' : 'text-orange-500' }}">
                <!-- NUMERACIÓN -->
                <td class="px-8 py-6 text-center font-black text-gray-300">
                    {{ $loop->iteration + ($certificates->currentPage() - 1) * $certificates->perPage() }}
                </td>

                <!-- INFORMACIÓN -->
                <td class="px-8 py-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#001144] flex items-center justify-center shrink-0 border border-white shadow-sm ring-2 ring-gray-50">
                            <i class="ph-fill ph-certificate text-2xl"></i>
                        </div>
                        <div class="max-w-[300px]">
                            <p class="font-black text-[#001144] text-base leading-tight mb-1 group-hover:text-[#FF7A00] transition-colors line-clamp-1" title="{{ $cert->title }}">{{ $cert->title }}</p>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-gray-400 font-black uppercase tracking-wider">Reg: {{ $cert->registration_number ?? 'S/N' }}</span>
                            </div>
                        </div>
                    </div>
                </td>

                <!-- CATEGORÍA / ESTADO / CREADOR -->
                <td class="px-8 py-6">
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-wrap gap-1">
                            @if($cert->category)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-50 text-[#001144] border border-blue-100">
                                    <i class="ph-fill {{ $cert->category->icon ?? 'ph-tag' }}"></i> {{ $cert->category->name }}
                                </span>
                            @else
                                <span class="text-[10px] text-gray-300 font-bold uppercase">General</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full {{ $cert->status === 'published' ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.4)]' : 'bg-orange-400 shadow-[0_0_8px_rgba(251,146,60,0.4)]' }}"></div>
                            <span class="text-[10px] font-black uppercase tracking-widest {{ $cert->status === 'published' ? 'text-green-600' : 'text-orange-500' }}">
                                {{ $cert->status === 'published' ? 'Publicado' : 'Borrador' }}
                            </span>
                        </div>
                        <!-- Creador / Staff -->
                        <div class="flex items-center gap-1.5 text-[10px] text-gray-400 font-semibold pt-1 border-t border-gray-100/70">
                            <i class="ph-bold ph-user-circle text-gray-400 text-xs"></i>
                            <span class="truncate">Por: <strong class="text-[#001144] font-bold">{{ $cert->user ? $cert->user->name : 'Administrador' }}</strong></span>
                        </div>
                    </div>
                </td>

                <!-- CARGA HORARIA -->
                <td class="px-8 py-6 text-center">
                    <div class="inline-flex flex-col items-center">
                        <span class="font-black text-[#001144] text-xl tracking-tighter">{{ $cert->hours ?? 0 }}</span>
                        <span class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Horas</span>
                    </div>
                </td>

                <!-- FECHAS -->
                <td class="px-8 py-6">
                    <div class="flex flex-col text-[10px] font-black uppercase tracking-wider text-gray-500">
                        <span>I: {{ $cert->start_date ? $cert->start_date->format('d/m/Y') : '-' }}</span>
                        <span>F: {{ $cert->end_date ? $cert->end_date->format('d/m/Y') : '-' }}</span>
                    </div>
                </td>

                <!-- ACCIONES -->
                <td class="px-8 py-6 text-right">
                    <div class="flex items-center justify-end gap-2">
                        @if(Auth::user()->hasPermission('individual-certificates.view'))
                        <button onclick="openDetailsModal({{ $cert->id }}, this)" class="w-10 h-10 rounded-[0.8rem] flex items-center justify-center text-gray-400 hover:text-blue-500 hover:bg-blue-50 transition-all duration-300" title="Ver Detalles">
                            <i class="ph-bold ph-magnifying-glass text-xl"></i>
                        </button>
                        @endif

                        @if(Auth::user()->hasPermission('individual-certificates.edit'))
                        <a href="{{ route('admin.individual-certificates.edit', $cert->id) }}" class="w-10 h-10 rounded-[0.8rem] flex items-center justify-center text-gray-400 hover:text-[#FF7A00] hover:bg-orange-50 transition-all duration-300" title="Editar">
                            <i class="ph-bold ph-pencil-simple text-xl"></i>
                        </a>
                        @endif
                        
                        @if(Auth::user()->hasPermission('individual-certificates.delete'))
                        <form action="{{ route('admin.individual-certificates.destroy', $cert->id) }}" method="POST" onsubmit="return confirm('¿Seguro que desea eliminar este certificado?');" class="inline">
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
                <td colspan="6" class="px-8 py-20 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-24 h-24 bg-gray-50 rounded-[2rem] flex items-center justify-center mb-6 ring-8 ring-gray-50/50">
                            <i class="ph-duotone ph-certificate text-5xl text-gray-200"></i>
                        </div>
                        <h3 class="text-xl font-black text-gray-600 mb-2">No se encontraron certificados</h3>
                        <p class="text-sm text-gray-400 font-medium mb-8 max-w-sm">No hay certificados individuales que coincidan con la búsqueda o filtros aplicados.</p>
                        @if(Auth::user()->hasPermission('individual-certificates.create'))
                        <a href="{{ route('admin.individual-certificates.create') }}" class="px-8 py-3.5 bg-[#FF7A00] text-white rounded-[1.2rem] font-black text-sm hover:bg-[#e66e00] transition-all shadow-xl shadow-[#FF7A00]/20">
                            Crear certificado
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
    {{ $certificates->links() }}
</div>
