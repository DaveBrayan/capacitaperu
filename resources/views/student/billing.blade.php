<x-layouts.student>
    <x-slot name="title">Mis Pagos - CapacitaPerú</x-slot>
    <x-slot name="header">Historial de Pagos</x-slot>

    <!-- RESUMEN DE ESTADO -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12 fade-in-up">
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Transacciones</p>
                <h4 class="text-3xl font-black text-[#001144] tracking-tighter">{{ $payments->total() }}</h4>
            </div>
        </div>
        <div class="bg-[#001144] p-8 rounded-[2.5rem] shadow-xl relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#FF7A00] opacity-20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-2">Inversión Total</p>
                <h4 class="text-3xl font-black text-white tracking-tighter">S/ {{ number_format($payments->sum('price_paid'), 2) }}</h4>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Estado de Cuenta</p>
                <h4 class="text-3xl font-black text-emerald-500 tracking-tighter">Al día</h4>
            </div>
        </div>
    </div>

    <!-- TABLA DE TRANSACCIONES PREMIUM -->
    <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden fade-in-up" style="animation-delay: 0.2s">
        <div class="p-10 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h3 class="text-2xl font-black text-[#001144] tracking-tighter">Historial de Transacciones</h3>
                <p class="text-gray-400 text-sm font-medium mt-1">Registros oficiales de tus inscripciones.</p>
            </div>
            
            <div class="flex gap-4">
                <span class="px-5 py-2.5 text-[10px] font-black text-[#001144] bg-gray-50 rounded-2xl border border-gray-100 uppercase tracking-widest">
                    {{ $payments->total() }} Registros
                </span>
            </div>
        </div>

        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-[10px] text-gray-400 uppercase tracking-[0.2em] border-b border-gray-50 font-black">
                        <th class="px-10 py-6">ID Pedido</th>
                        <th class="px-10 py-6">Programa Académico</th>
                        <th class="px-10 py-6">Fecha</th>
                        <th class="px-10 py-6">Monto Pagado</th>
                        <th class="px-10 py-6">Estado</th>
                        <th class="px-10 py-6 text-right">Comprobante</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50 font-medium">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50/50 transition-all group">
                        <td class="px-10 py-6">
                            <span class="px-3 py-1.5 bg-[#001144]/5 text-[#001144] rounded-lg font-black text-[10px] tracking-widest">#{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </td>

                        <td class="px-10 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden shrink-0 border border-gray-50 shadow-sm">
                                    @if($payment->course->cover_image)
                                        <img src="{{ asset($payment->course->cover_image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-300">
                                            <i class="ph-fill ph-graduation-cap text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <span class="font-bold text-[#001144] line-clamp-1 max-w-[250px]">{{ $payment->course->title }}</span>
                            </div>
                        </td>

                        <td class="px-10 py-6 text-gray-400 font-bold uppercase text-[10px] tracking-widest">
                            {{ $payment->created_at->format('d M, Y') }}
                        </td>

                        <td class="px-10 py-6 font-black text-[#001144] text-base tracking-tighter">
                            S/ {{ number_format($payment->price_paid, 2) }}
                        </td>

                        <td class="px-10 py-6">
                            @if($payment->status === 'approved')
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-sm shadow-emerald-500/5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Aprobado
                                </span>
                            @elseif($payment->status === 'pending')
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest bg-amber-50 text-amber-600 border border-amber-100 shadow-sm shadow-amber-500/5">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Pendiente
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest bg-red-50 text-red-600 border border-red-100 shadow-sm shadow-red-500/5">
                                    Rechazado
                                </span>
                            @endif
                        </td>

                        <td class="px-10 py-6 text-right">
                            @if($payment->receipt_url)
                                <button onclick="verBoleta('{{ asset($payment->receipt_url) }}')" class="ml-auto w-10 h-10 bg-[#001144] text-white rounded-xl hover:bg-[#FF7A00] transition-all flex items-center justify-center shadow-lg shadow-blue-900/10" title="Ver Boleta">
                                    <i class="ph-bold ph-receipt text-xl"></i>
                                </button>
                            @else
                                <span class="text-[10px] text-gray-300 font-black uppercase tracking-widest">N/A</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-10 py-20 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-gray-200">
                                <i class="ph-duotone ph-receipt text-4xl"></i>
                            </div>
                            <p class="text-gray-400 font-bold uppercase tracking-[0.2em] text-[10px]">No hay transacciones registradas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-8 bg-gray-50/50 border-t border-gray-50">
            {{ $payments->links() }}
        </div>
    </div>

    <!-- MODAL BOLETA PREMIUM -->
    <div id="modal-boleta" class="fixed inset-0 z-[100] hidden bg-[#001144]/90 backdrop-blur-xl flex items-center justify-center p-8 transition-all duration-500" onclick="closeBoleta()">
        <div class="relative max-w-2xl w-full transform scale-95 opacity-0 transition-all duration-500" id="boleta-content" onclick="event.stopPropagation()">
            <div class="bg-white p-2 rounded-[3rem] shadow-2xl overflow-hidden border-8 border-white/20">
                <img id="boleta-img" src="" class="w-full rounded-[2.5rem]">
            </div>
            <button onclick="closeBoleta()" class="absolute -top-16 right-0 w-12 h-12 bg-white text-[#001144] rounded-2xl hover:bg-[#FF7A00] hover:text-white transition-all shadow-2xl flex items-center justify-center">
                <i class="ph-bold ph-x text-2xl"></i>
            </button>
        </div>
    </div>

    <script>
        function verBoleta(url) {
            document.getElementById('boleta-img').src = url;
            const modal = document.getElementById('modal-boleta');
            const content = document.getElementById('boleta-content');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeBoleta() {
            const modal = document.getElementById('modal-boleta');
            const content = document.getElementById('boleta-content');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 500);
        }
    </script>
</x-layouts.student>