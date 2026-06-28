<x-layouts.guest>
    <x-slot name="title">Tu Carrito - {{ $platformSettings['app_name'] ?? 'CAPACITAPERU' }}</x-slot>

    <!-- HEADER DE PÁGINA (Colores del tema) -->
    <div class="relative bg-[#001144] pt-32 pb-16 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#FF7A00] opacity-10 rounded-full blur-[120px] pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-[#0033CC] opacity-20 rounded-full blur-[100px] pointer-events-none"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <span class="inline-block bg-[#FF7A00] text-white text-[9px] font-black px-3.5 py-1.5 rounded-full uppercase tracking-wider mb-4">
                Inscripción Segura
            </span>
            <h1 class="text-4xl md:text-5xl font-black text-white mb-2 tracking-tight">Tu Carrito de Compras</h1>
            <p class="text-gray-300 text-sm font-medium">Estás a un paso de potenciar tu perfil profesional con CapacitaPerú.</p>
        </div>
    </div>

    <!-- CONTENIDO CARRITO -->
    <div class="bg-gray-50 pb-24 pt-12 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Empty Cart State (Hidden by default) -->
            <div id="empty-cart-state" class="hidden text-center py-24 bg-white rounded-[3rem] border-2 border-dashed border-gray-100 max-w-3xl mx-auto">
                <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6">
                    <i class="ph-bold ph-shopping-cart-simple text-4xl text-gray-300"></i>
                </div>
                <h3 class="text-2xl font-black text-[#001144] mb-2 tracking-tight">Tu carrito está vacío</h3>
                <p class="text-gray-400 mb-8 max-w-xs mx-auto font-medium">Explora nuestros cursos y diplomados para encontrar el programa ideal para ti.</p>
                <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                    <a href="{{ route('catalog') }}" class="inline-block px-8 py-4 bg-[#001144] text-white rounded-[1.2rem] font-black uppercase tracking-widest hover:bg-[#FF7A00] transition-all shadow-xl shadow-blue-900/20 text-xs w-48 text-center">
                        Ver Cursos
                    </a>
                    <a href="{{ route('diplomados') }}" class="inline-block px-8 py-4 bg-[#0033CC] text-white rounded-[1.2rem] font-black uppercase tracking-widest hover:bg-[#FF7A00] transition-all shadow-xl shadow-blue-900/20 text-xs w-48 text-center">
                        Ver Diplomados
                    </a>
                </div>
            </div>

            <!-- Cart Content (Visible when items exist) -->
            <div id="cart-content-state" class="flex flex-col lg:flex-row gap-8">
                
                <!-- LISTA DE CURSOS (Izquierda) -->
                <div class="lg:w-2/3 space-y-4" id="cart-items-list">
                    <!-- Populated via Javascript -->
                </div>

                <!-- RESUMEN DE PAGO (Derecha - Sticky) -->
                <div class="lg:w-1/3">
                    <div class="bg-white p-8 rounded-[2rem] shadow-2xl shadow-gray-200/40 border border-gray-100 sticky top-24">
                        <h3 class="text-lg font-black text-[#001144] mb-6 uppercase tracking-wider">Resumen</h3>
                        
                        <div class="space-y-4 mb-6 pb-6 border-b border-gray-100 text-sm">
                            <div class="flex justify-between text-gray-500 font-medium">
                                <span>Subtotal</span>
                                <span id="summary-subtotal">--</span>
                            </div>
                            <div class="flex justify-between text-[#FF7A00] font-black" id="discount-row">
                                <span>Descuento</span>
                                <span id="summary-discount">--</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-end mb-8">
                            <span class="text-gray-800 font-black text-sm uppercase tracking-wider">Total</span>
                            <span class="text-3xl font-black text-[#001144]" id="summary-total">--</span>
                        </div>

                        <button onclick="openAdvisorModal()" class="w-full py-5 bg-[#FF7A00] hover:bg-[#FF7A00]/90 text-white font-black rounded-2xl shadow-xl shadow-orange-500/30 transition-all hover:scale-[1.01] hover:-translate-y-0.5 hover:shadow-orange-500/40 flex items-center justify-center gap-3 uppercase tracking-widest text-sm cursor-pointer">
                            Pagar por WhatsApp
                            <i class="ph-bold ph-whatsapp-logo text-xl"></i>
                        </button>

                        <!-- Trust Badges -->
                        <div class="mt-8 grid grid-cols-3 gap-2 text-center border-t border-gray-50 pt-6">
                            <div class="flex flex-col items-center gap-1.5">
                                <i class="ph-fill ph-shield-check text-[#0033CC] text-xl"></i>
                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Pago Seguro</span>
                            </div>
                            <div class="flex flex-col items-center gap-1.5">
                                <i class="ph-fill ph-check-circle text-[#0033CC] text-xl"></i>
                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Garantía</span>
                            </div>
                            <div class="flex flex-col items-center gap-1.5">
                                <i class="ph-fill ph-headset text-[#0033CC] text-xl"></i>
                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Soporte 24/7</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL DE ASESORES PARA WHATSAPP -->
    <div id="advisor-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-[#001144]/40 backdrop-blur-md transition-opacity" onclick="closeAdvisorModal()"></div>
        
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md border border-white/20">
                    
                    <div class="bg-[#001144] px-8 py-6 text-white flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-[#FF7A00]">
                                <i class="ph-fill ph-whatsapp-logo text-3xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black tracking-tight" id="modal-title">Hablar con un Asesor</h3>
                                <p class="text-[10px] text-gray-300 font-bold uppercase tracking-widest">Proceder al Pago</p>
                            </div>
                        </div>
                        <button onclick="closeAdvisorModal()" class="w-10 h-10 flex items-center justify-center text-gray-300 hover:text-white transition-colors rounded-full hover:bg-white/10">
                            <i class="ph-bold ph-x text-xl"></i>
                        </button>
                    </div>

                    <div class="p-8 space-y-6">
                        <p class="text-xs text-gray-500 font-medium leading-relaxed">
                            Selecciona uno de nuestros asesores disponibles para coordinar tu método de pago e inscripción inmediata. Te enviaremos con el detalle de tu carrito de compras.
                        </p>
                        
                        <div class="space-y-4" id="advisor-list">
                            @forelse($globalAdvisors as $advisor)
                                <button onclick="checkoutToWhatsapp('{{ preg_replace('/[^0-9]/', '', $advisor->phone) }}')" 
                                        class="w-full flex items-center gap-4 p-4 rounded-2xl border border-gray-100 hover:border-[#FF7A00]/30 hover:bg-orange-50/20 text-left transition-all group">
                                    <div class="relative">
                                        @if($advisor->avatar_url)
                                            <img src="{{ Str::startsWith($advisor->avatar_url, 'http') ? $advisor->avatar_url : asset($advisor->avatar_url) }}"
                                                 class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($advisor->name) }}&background=213A57&color=fff&size=128"
                                                 class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                                        @endif
                                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-[#25D366] rounded-full border-2 border-white"></div>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-gray-800 text-sm group-hover:text-[#FF7A00] transition-colors">{{ $advisor->name }}</h4>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $advisor->position }}</p>
                                        <div class="flex items-center gap-1 mt-1">
                                            <div class="w-1.5 h-1.5 bg-[#25D366] rounded-full animate-pulse"></div>
                                            <span class="text-[9px] text-[#25D366] font-bold uppercase">Disponible</span>
                                        </div>
                                    </div>
                                    <i class="ph-bold ph-caret-right ml-auto text-gray-400 group-hover:text-[#FF7A00] transition-colors"></i>
                                </button>
                            @empty
                                <div class="text-center py-6 text-gray-400">
                                    No hay asesores configurados en este momento.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT DE CARRITO DINÁMICO -->
    <script>
        const currencySymbol = "{{ $platformSettings['app_currency'] ?? 'Bs' }}";

        function renderCart() {
            const cart = window.getCart();
            const emptyState = document.getElementById('empty-cart-state');
            const contentState = document.getElementById('cart-content-state');
            const itemsList = document.getElementById('cart-items-list');

            if (cart.length === 0) {
                emptyState.classList.remove('hidden');
                contentState.classList.add('hidden');
                return;
            }

            emptyState.classList.add('hidden');
            contentState.classList.remove('hidden');

            let itemsHtml = '';
            let subtotal = 0;
            let totalDiscount = 0;

            cart.forEach(item => {
                const price = parseFloat(item.price);
                const originalPrice = parseFloat(item.original_price || item.price);
                const discount = originalPrice - price;

                subtotal += originalPrice;
                totalDiscount += discount;

                itemsHtml += `
                    <div class="bg-white p-4.5 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col sm:flex-row gap-5 items-center group hover:border-[#FF7A00]/30 transition-all">
                        <!-- Imagen -->
                        <div class="w-full sm:w-36 h-24 rounded-[1.5rem] overflow-hidden shrink-0 relative shadow-md">
                            <img src="${item.cover}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        
                        <!-- Info -->
                        <div class="flex-1 text-center sm:text-left min-w-0">
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-black text-[#001144] text-lg mb-1 leading-tight truncate" title="${item.title}">${item.title}</h3>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-3">Por: ${item.instructor}</p>
                                    <div class="flex items-center justify-center sm:justify-start gap-1.5 text-[9px] text-[#0033CC] bg-[#0033CC]/5 px-3 py-1 rounded-full font-black uppercase tracking-wider w-fit mx-auto sm:mx-0">
                                        <i class="ph-fill ph-certificate"></i> Con Certificado
                                    </div>
                                </div>
                                <div class="mt-2 sm:mt-0 text-right w-full sm:w-auto self-center sm:self-start">
                                    <span class="block font-black text-2xl text-[#0033CC]">${currencySymbol} ${price.toLocaleString(undefined, {minimumFractionDigits: 0, maximumFractionDigits: 2})}</span>
                                    ${discount > 0 ? `<span class="block text-xs text-gray-300 line-through font-bold">${currencySymbol} ${originalPrice.toLocaleString(undefined, {minimumFractionDigits: 0, maximumFractionDigits: 2})}</span>` : ''}
                                </div>
                            </div>
                        </div>

                        <!-- Acciones -->
                        <button onclick="removeFromCart(${item.id})" class="text-gray-300 hover:text-red-500 transition-colors p-3 hover:bg-red-50 rounded-2xl cursor-pointer" title="Eliminar del Carrito">
                            <i class="ph-bold ph-trash text-xl"></i>
                        </button>
                    </div>
                `;
            });

            // Enlace de seguir comprando al final
            itemsHtml += `
                <div class="pt-4">
                    <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 text-xs font-black text-[#001144] hover:text-[#FF7A00] transition-colors uppercase tracking-widest">
                        <i class="ph-bold ph-arrow-left text-sm"></i> Seguir comprando
                    </a>
                </div>
            `;

            itemsList.innerHTML = itemsHtml;

            // Totales
            const finalTotal = subtotal - totalDiscount;
            document.getElementById('summary-subtotal').innerText = `${currencySymbol} ${subtotal.toLocaleString(undefined, {minimumFractionDigits: 0, maximumFractionDigits: 2})}`;
            
            const discountRow = document.getElementById('discount-row');
            if (totalDiscount > 0) {
                discountRow.classList.remove('hidden');
                document.getElementById('summary-discount').innerText = `- ${currencySymbol} ${totalDiscount.toLocaleString(undefined, {minimumFractionDigits: 0, maximumFractionDigits: 2})}`;
            } else {
                discountRow.classList.add('hidden');
            }

            document.getElementById('summary-total').innerText = `${currencySymbol} ${finalTotal.toLocaleString(undefined, {minimumFractionDigits: 0, maximumFractionDigits: 2})}`;
        }

        // Modal triggers
        function openAdvisorModal() {
            document.getElementById('advisor-modal').classList.remove('hidden');
        }

        function closeAdvisorModal() {
            document.getElementById('advisor-modal').classList.add('hidden');
        }

        function checkoutToWhatsapp(phone) {
            const cart = window.getCart();
            if (cart.length === 0) return;
            
            let message = "¡Hola! Me gustaría inscribirme en los siguientes programas:\n\n";
            
            let subtotal = 0;
            let discount = 0;
            
            cart.forEach((item, index) => {
                const itemPrice = parseFloat(item.price);
                const origPrice = parseFloat(item.original_price || item.price);
                const itemDiscount = origPrice - itemPrice;
                
                message += `*${index + 1}. ${item.title}*\n`;
                message += `• Docente: ${item.instructor}\n`;
                if (itemDiscount > 0) {
                    message += `• Precio: ${currencySymbol} ${itemPrice} (Antes: ${currencySymbol} ${origPrice})\n\n`;
                    discount += itemDiscount;
                } else {
                    message += `• Precio: ${currencySymbol} ${itemPrice}\n\n`;
                }
                subtotal += origPrice;
            });
            
            const total = subtotal - discount;
            
            message += `*RESUMEN DE COMPRA:*\n`;
            message += `• Subtotal: ${currencySymbol} ${subtotal}\n`;
            if (discount > 0) {
                message += `• Descuento Total: - ${currencySymbol} ${discount}\n`;
            }
            message += `• *TOTAL A PAGAR: ${currencySymbol} ${total}*\n\n`;
            message += `Por favor, facilítenme los métodos de pago disponibles y los detalles de acceso.`;
            
            const url = "https://wa.me/" + phone + "?text=" + encodeURIComponent(message);
            window.open(url, '_blank');
            closeAdvisorModal();
        }

        // Listeners
        window.addEventListener('cartUpdated', renderCart);

        document.addEventListener('DOMContentLoaded', () => {
            renderCart();
        });
    </script>
</x-layouts.guest>