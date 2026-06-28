<x-layouts.guest>

    <!-- HEADER SIMPLE -->
    <div class="bg-gray-50 pt-32 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-[#213A57] mb-2 fade-in-up">Tu Carrito de Compras</h1>
            <p class="text-gray-500 fade-in-up delay-100">Estás a un paso de potenciar tu perfil profesional.</p>
        </div>
    </div>

    <!-- CONTENIDO CARRITO -->
    <div class="bg-gray-50 pb-24 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">
                
                <!-- LISTA DE CURSOS (Izquierda) -->
                <div class="lg:w-2/3 space-y-6 fade-in-up delay-200">
                    
                    <!-- Item 1 -->
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col sm:flex-row gap-4 items-center group hover:border-[#0AD1C8]/30 transition-colors">
                        <!-- Imagen -->
                        <div class="w-full sm:w-32 h-24 rounded-xl overflow-hidden shrink-0 relative">
                            <img src="https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?q=80&w=400&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                        
                        <!-- Info -->
                        <div class="flex-1 text-center sm:text-left">
                            <div class="flex flex-col sm:flex-row justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-[#213A57] text-lg mb-1 leading-tight">Supervisor SSOMA</h3>
                                    <p class="text-xs text-gray-500 mb-2">Por: Ing. Juan Pérez</p>
                                    <div class="flex items-center justify-center sm:justify-start gap-2 text-xs text-[#0B6477] bg-[#E0F9F6] px-2 py-1 rounded w-fit mx-auto sm:mx-0">
                                        <i class="ph-fill ph-certificate"></i> Con Certificado
                                    </div>
                                </div>
                                <div class="mt-4 sm:mt-0 text-right">
                                    <span class="block font-bold text-xl text-[#213A57]">{{ $platformSettings['app_currency'] ?? 'Bs' }} 350</span>
                                    <span class="block text-xs text-gray-400 line-through">{{ $platformSettings['app_currency'] ?? 'Bs' }} 500</span>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones -->
                        <button class="text-gray-400 hover:text-red-500 transition-colors p-2 hover:bg-red-50 rounded-lg" title="Eliminar">
                            <i class="ph-bold ph-trash text-xl"></i>
                        </button>
                    </div>

                    <!-- Item 2 -->
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col sm:flex-row gap-4 items-center group hover:border-[#0AD1C8]/30 transition-colors">
                        <div class="w-full sm:w-32 h-24 rounded-xl overflow-hidden shrink-0 relative">
                            <img src="https://images.unsplash.com/photo-1497436072909-60f360e1d4b0?q=80&w=400&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="flex-1 text-center sm:text-left">
                            <div class="flex flex-col sm:flex-row justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-[#213A57] text-lg mb-1 leading-tight">Auditor Interno ISO 14001</h3>
                                    <p class="text-xs text-gray-500 mb-2">Por: Dra. Ana N.</p>
                                    <div class="flex items-center justify-center sm:justify-start gap-2 text-xs text-[#0B6477] bg-[#E0F9F6] px-2 py-1 rounded w-fit mx-auto sm:mx-0">
                                        <i class="ph-fill ph-certificate"></i> Con Certificado
                                    </div>
                                </div>
                                <div class="mt-4 sm:mt-0 text-right">
                                    <span class="block font-bold text-xl text-[#213A57]">{{ $platformSettings['app_currency'] ?? 'Bs' }} 600</span>
                                    <span class="block text-xs text-gray-400 line-through">{{ $platformSettings['app_currency'] ?? 'Bs' }} 800</span>
                                </div>
                            </div>
                        </div>
                        <button class="text-gray-400 hover:text-red-500 transition-colors p-2 hover:bg-red-50 rounded-lg">
                            <i class="ph-bold ph-trash text-xl"></i>
                        </button>
                    </div>

                    <a href="/catalogo" class="inline-flex items-center gap-2 text-[#0AD1C8] font-bold hover:text-[#0B6477] transition-colors mt-4">
                        <i class="ph-bold ph-arrow-left"></i> Seguir comprando
                    </a>
                </div>

                <!-- RESUMEN DE PAGO (Derecha - Sticky) -->
                <div class="lg:w-1/3 fade-in-up delay-300">
                    <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100 sticky top-24">
                        <h3 class="text-xl font-bold text-[#213A57] mb-6">Resumen</h3>
                        
                        <div class="space-y-3 mb-6 pb-6 border-b border-gray-100 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span>{{ $platformSettings['app_currency'] ?? 'Bs' }} 950.00</span>
                            </div>
                            <div class="flex justify-between text-[#0AD1C8] font-medium">
                                <span>Descuento</span>
                                <span>- {{ $platformSettings['app_currency'] ?? 'Bs' }} 350.00</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-end mb-6">
                            <span class="text-gray-800 font-bold">Total</span>
                            <span class="text-3xl font-extrabold text-[#213A57]">{{ $platformSettings['app_currency'] ?? 'Bs' }} 950</span>
                        </div>

                        <!-- Cupón -->
                        <div class="mb-6 relative">
                            <input type="text" placeholder="Código de cupón" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-4 pr-12 text-sm focus:outline-none focus:border-[#0AD1C8] transition-colors">
                            <button class="absolute right-2 top-1/2 -translate-y-1/2 text-[#0AD1C8] font-bold text-xs hover:text-[#0B6477] px-2 py-1">APLICAR</button>
                        </div>

                        <button class="w-full py-4 bg-gradient-to-r from-[#213A57] to-[#0B6477] hover:to-[#0AD1C8] text-white font-bold rounded-xl shadow-xl shadow-[#213A57]/20 transition-all hover:-translate-y-1 group">
                            Proceder al Pago
                            <i class="ph-bold ph-lock-key ml-2 group-hover:text-[#0AD1C8] transition-colors"></i>
                        </button>

                        <!-- Trust Badges -->
                        <div class="mt-6 grid grid-cols-3 gap-2 text-center">
                            <div class="flex flex-col items-center gap-1">
                                <i class="ph-fill ph-shield-check text-[#0AD1C8] text-xl"></i>
                                <span class="text-[10px] text-gray-400 font-medium">Pago Seguro</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <i class="ph-fill ph-check-circle text-[#0AD1C8] text-xl"></i>
                                <span class="text-[10px] text-gray-400 font-medium">Garantía</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <i class="ph-fill ph-headset text-[#0AD1C8] text-xl"></i>
                                <span class="text-[10px] text-gray-400 font-medium">Soporte 24/7</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</x-layouts.guest>