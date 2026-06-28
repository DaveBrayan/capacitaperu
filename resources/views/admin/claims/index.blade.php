<x-layouts.admin>
    <x-slot name="title">Gestión de Reclamaciones</x-slot>
    <x-slot name="header">Reclamaciones Recibidas</x-slot>

    <!-- Filtros y Búsqueda -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-10 fade-in-up">
        <form method="GET" action="{{ route('admin.claims.index') }}" class="flex flex-col sm:flex-row gap-4 w-full md:w-auto flex-1">
            <div class="relative w-full sm:w-80 group">
                <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00] transition-colors"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, correo..." 
                       class="w-full pl-12 pr-4 py-4 rounded-[1.2rem] border border-gray-100 bg-white shadow-sm focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none text-sm transition-all font-medium">
            </div>

            <div class="relative w-full sm:w-48">
                <select name="status" onchange="this.form.submit()" 
                        class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-white shadow-sm focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] appearance-none cursor-pointer transition-all">
                    <option value="">Todos los Estados</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendientes</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resueltos</option>
                    <option value="dismissed" {{ request('status') === 'dismissed' ? 'selected' : '' }}>Desestimados</option>
                </select>
                <i class="ph-bold ph-caret-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
            </div>
            
            @if(request()->filled('search') || request()->filled('status'))
                <a href="{{ route('admin.claims.index') }}" class="px-6 py-4 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-[1.2rem] text-sm font-black uppercase tracking-widest flex items-center justify-center transition-all">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <!-- Lista de Reclamaciones -->
    <div class="bg-white rounded-[2.5rem] border border-gray-100 overflow-hidden shadow-sm fade-in-up" style="animation-delay: 0.1s">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Remitente</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Contacto</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tipo de Problema</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Fecha</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Estado</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($claims as $claim)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-6">
                            <span class="font-black text-[#001144] block text-sm">{{ $claim->name }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-xs text-gray-500 font-bold block">{{ $claim->email }}</span>
                            @if($claim->phone)
                            <span class="text-[10px] text-gray-400 font-medium block mt-0.5">{{ $claim->phone }}</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1.5 rounded-lg bg-orange-50 text-[#FF7A00] font-black text-[10px] uppercase tracking-wide">
                                {{ $claim->issue_type }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-xs text-gray-400 font-bold">
                            {{ $claim->created_at->diffForHumans() }}
                        </td>
                        <td class="px-8 py-6">
                            @if($claim->status === 'pending')
                                <span class="px-3 py-1 rounded-full bg-yellow-50 text-yellow-600 font-black text-[9px] uppercase tracking-widest border border-yellow-200">Pendiente</span>
                            @elseif($claim->status === 'resolved')
                                <span class="px-3 py-1 rounded-full bg-green-50 text-green-600 font-black text-[9px] uppercase tracking-widest border border-green-200">Resuelto</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-gray-50 text-gray-500 font-black text-[9px] uppercase tracking-widest border border-gray-200">Desestimado</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <!-- Ver Detalle -->
                                <button onclick="viewClaimDetails({{ json_encode($claim) }})" 
                                        class="w-9 h-9 bg-[#001144]/5 hover:bg-[#001144] hover:text-white rounded-xl text-[#001144] flex items-center justify-center transition-all"
                                        title="Ver detalles">
                                    <i class="ph-bold ph-eye text-base"></i>
                                </button>

                                @if(Auth::user()->hasPermission('claims.manage'))
                                    <!-- Resolver -->
                                    @if($claim->status !== 'resolved')
                                    <form action="{{ route('admin.claims.update', $claim->id) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="resolved">
                                        <button type="submit" class="w-9 h-9 bg-green-50 hover:bg-green-500 hover:text-white rounded-xl text-green-600 flex items-center justify-center transition-all border border-green-100" title="Marcar como Resuelto">
                                            <i class="ph-bold ph-check text-base"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Desestimar -->
                                    @if($claim->status !== 'dismissed')
                                    <form action="{{ route('admin.claims.update', $claim->id) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="dismissed">
                                        <button type="submit" class="w-9 h-9 bg-gray-50 hover:bg-gray-400 hover:text-white rounded-xl text-gray-500 flex items-center justify-center transition-all border border-gray-200" title="Desestimar">
                                            <i class="ph-bold ph-prohibit text-base"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Eliminar -->
                                    <form action="{{ route('admin.claims.destroy', $claim->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta reclamación? Esta acción no se puede deshacer.');" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-9 h-9 bg-red-50 hover:bg-red-500 hover:text-white rounded-xl text-red-500 flex items-center justify-center transition-all border border-red-100" title="Eliminar">
                                            <i class="ph-bold ph-trash text-base"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-16 text-center text-gray-400 font-bold">
                            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="ph-bold ph-warning-octagon text-3xl text-gray-300"></i>
                            </div>
                            No se encontraron reclamaciones registradas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($claims->hasPages())
        <div class="px-8 py-6 border-t border-gray-50">
            {{ $claims->links() }}
        </div>
        @endif
    </div>

    <!-- MODAL DETALLE DE RECLAMACIÓN -->
    <div id="claim-details-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-[#001144]/40 backdrop-blur-md transition-opacity" onclick="closeClaimDetailsModal()"></div>
        
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-lg border border-white/20">
                    
                    <!-- Header -->
                    <div class="bg-[#001144] px-8 py-6 text-white flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-[#FF7A00]">
                                <i class="ph-bold ph-warning-octagon text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black tracking-tight">Detalle de Reclamación</h3>
                                <p class="text-[10px] text-gray-300 font-bold uppercase tracking-widest">Información Registrada</p>
                            </div>
                        </div>
                        <button onclick="closeClaimDetailsModal()" class="w-10 h-10 flex items-center justify-center text-gray-300 hover:text-white transition-colors rounded-full hover:bg-white/10 cursor-pointer">
                            <i class="ph-bold ph-x text-xl"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-8 space-y-6">
                        
                        <div class="grid grid-cols-2 gap-6 bg-gray-50 p-5 rounded-[1.5rem] border border-gray-100">
                            <div>
                                <span class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Remitente</span>
                                <span id="detail-name" class="text-sm font-black text-[#001144] block"></span>
                            </div>
                            <div>
                                <span class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Tipo de Problema</span>
                                <span id="detail-issue-type" class="px-2.5 py-1 rounded bg-[#FF7A00]/10 text-[#FF7A00] font-black text-[10px] uppercase tracking-wide inline-block"></span>
                            </div>
                            <div>
                                <span class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Email</span>
                                <a id="detail-email" href="" class="text-xs font-bold text-blue-600 hover:underline block"></a>
                            </div>
                            <div>
                                <span class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Teléfono</span>
                                <span id="detail-phone" class="text-xs font-bold text-[#001144] block"></span>
                            </div>
                        </div>

                        <div>
                            <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Descripción del Reclamante</span>
                            <div class="p-6 bg-white rounded-[1.5rem] border border-gray-100 text-xs font-medium text-gray-600 leading-relaxed max-h-60 overflow-y-auto" id="detail-description">
                            </div>
                        </div>

                        <div>
                            <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Estado Actual</span>
                            <div class="flex items-center gap-2">
                                <span id="detail-status" class="px-3.5 py-1.5 rounded-full font-black text-[10px] uppercase tracking-widest border"></span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-50 flex justify-end">
                            <button onclick="closeClaimDetailsModal()" class="px-6 py-3 bg-[#001144] hover:bg-[#FF7A00] text-white font-black rounded-xl text-xs uppercase tracking-widest transition-all cursor-pointer">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const detailsModal = document.getElementById('claim-details-modal');

        function viewClaimDetails(claim) {
            document.getElementById('detail-name').innerText = claim.name;
            document.getElementById('detail-issue-type').innerText = claim.issue_type;
            
            const emailLink = document.getElementById('detail-email');
            emailLink.innerText = claim.email;
            emailLink.href = 'mailto:' + claim.email;
            
            document.getElementById('detail-phone').innerText = claim.phone || 'No especificado';
            
            // Reemplazar saltos de línea
            document.getElementById('detail-description').innerHTML = claim.description.replace(/\n/g, '<br>');
            
            const statusBadge = document.getElementById('detail-status');
            statusBadge.innerText = claim.status;
            if (claim.status === 'pending') {
                statusBadge.className = 'px-3.5 py-1.5 rounded-full bg-yellow-50 text-yellow-600 font-black text-[10px] uppercase tracking-widest border border-yellow-200';
                statusBadge.innerText = 'PENDIENTE';
            } else if (claim.status === 'resolved') {
                statusBadge.className = 'px-3.5 py-1.5 rounded-full bg-green-50 text-green-600 font-black text-[10px] uppercase tracking-widest border border-green-200';
                statusBadge.innerText = 'RESUELTO';
            } else {
                statusBadge.className = 'px-3.5 py-1.5 rounded-full bg-gray-50 text-gray-500 font-black text-[10px] uppercase tracking-widest border border-gray-200';
                statusBadge.innerText = 'DESESTIMADO';
            }

            detailsModal.classList.remove('hidden');
        }

        function closeClaimDetailsModal() {
            detailsModal.classList.add('hidden');
        }
    </script>
</x-layouts.admin>
