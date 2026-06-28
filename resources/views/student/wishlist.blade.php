<x-layouts.student>
    <x-slot name="title">Favoritos - CapacitaPerú</x-slot>
    <x-slot name="header">Cursos Guardados</x-slot>

    <!-- Header con resumen -->
    <div class="flex flex-col md:flex-row items-end justify-between mb-12 gap-6 fade-in-up">
        <div>
            <span class="text-[#FF7A00] font-black text-[10px] uppercase tracking-[0.3em] mb-3 block">Próximos Retos</span>
            <h1 class="text-4xl font-black text-[#001144] tracking-tighter">Mi Lista de Favoritos ❤️</h1>
            <p class="text-gray-500 mt-3 font-medium">Programas académicos que tienes en la mira para tu crecimiento.</p>
        </div>
        <button class="px-6 py-2.5 text-[10px] font-black text-gray-400 hover:text-red-500 uppercase tracking-widest transition-all">
            Limpiar lista
        </button>
    </div>

    <!-- GRID DE WISHLIST -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 pb-20">
        
        <!-- CARD 1 -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group relative fade-in-up" style="animation-delay: 0.1s">
            <button class="absolute top-6 right-6 z-20 w-10 h-10 bg-white/90 backdrop-blur rounded-xl flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-white transition-all shadow-sm" title="Eliminar de la lista">
                <i class="ph-bold ph-trash text-lg"></i>
            </button>

            <div class="relative h-56 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1497436072909-60f360e1d4b0?q=80&w=600&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                <div class="absolute inset-0 bg-gradient-to-t from-[#001144]/80 to-transparent"></div>
                <div class="absolute bottom-6 left-8 bg-[#FF7A00] px-4 py-2 rounded-xl text-white font-black text-[10px] uppercase tracking-widest shadow-lg">
                    S/ 600.00
                </div>
            </div>
            
            <div class="p-8 flex-1 flex flex-col">
                <span class="text-[#001144] text-[9px] font-black uppercase tracking-widest mb-3 bg-gray-50 w-fit px-3 py-1.5 rounded-lg border border-gray-100">Ingeniería Ambiental</span>
                <h3 class="font-black text-[#001144] text-xl mb-3 leading-tight tracking-tighter group-hover:text-[#FF7A00] transition-colors">Auditor Interno ISO 14001:2015</h3>
                
                <div class="flex items-center gap-3 text-[10px] font-bold text-gray-400 mb-8 uppercase tracking-widest">
                    <div class="flex text-[#FF7A00] items-center gap-1"><i class="ph-fill ph-star"></i><span>5.0</span></div>
                    <span class="opacity-30">•</span>
                    <div class="flex items-center gap-1"><i class="ph-bold ph-clock"></i><span>60 Horas</span></div>
                </div>

                <button class="w-full py-5 bg-[#001144] hover:bg-[#FF7A00] text-white font-black rounded-2xl transition-all duration-500 flex items-center justify-center gap-3 shadow-xl shadow-blue-900/10 uppercase tracking-widest text-[10px] mt-auto">
                    <i class="ph-bold ph-shopping-cart-simple text-lg"></i>
                    Matricularme Ahora
                </button>
            </div>
        </div>

        <!-- CARD 2 -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group relative fade-in-up" style="animation-delay: 0.2s">
            <button class="absolute top-6 right-6 z-20 w-10 h-10 bg-white/90 backdrop-blur rounded-xl flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-white transition-all shadow-sm">
                <i class="ph-bold ph-trash text-lg"></i>
            </button>

            <div class="relative h-56 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1581092921461-eab62e97a780?q=80&w=600&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                <div class="absolute inset-0 bg-gradient-to-t from-[#001144]/80 to-transparent"></div>
                <div class="absolute bottom-6 left-8 bg-[#FF7A00] px-4 py-2 rounded-xl text-white font-black text-[10px] uppercase tracking-widest shadow-lg">
                    S/ 450.00
                </div>
            </div>
            
            <div class="p-8 flex-1 flex flex-col">
                <span class="text-[#001144] text-[9px] font-black uppercase tracking-widest mb-3 bg-gray-50 w-fit px-3 py-1.5 rounded-lg border border-gray-100">Gestión Industrial</span>
                <h3 class="font-black text-[#001144] text-xl mb-3 leading-tight tracking-tighter group-hover:text-[#FF7A00] transition-colors">Jefe de Mantenimiento Moderno</h3>
                
                <div class="flex items-center gap-3 text-[10px] font-bold text-gray-400 mb-8 uppercase tracking-widest">
                    <div class="flex text-[#FF7A00] items-center gap-1"><i class="ph-fill ph-star"></i><span>4.8</span></div>
                    <span class="opacity-30">•</span>
                    <div class="flex items-center gap-1"><i class="ph-bold ph-clock"></i><span>30 Horas</span></div>
                </div>

                <button class="w-full py-5 bg-[#001144] hover:bg-[#FF7A00] text-white font-black rounded-2xl transition-all duration-500 flex items-center justify-center gap-3 shadow-xl shadow-blue-900/10 uppercase tracking-widest text-[10px] mt-auto">
                    <i class="ph-bold ph-shopping-cart-simple text-lg"></i>
                    Matricularme Ahora
                </button>
            </div>
        </div>

    </div>

    <!-- Recomendaciones -->
    <div class="mt-20 pt-16 border-t border-gray-100">
        <div class="flex items-center gap-4 mb-10">
            <div class="w-1.5 h-8 bg-[#FF7A00] rounded-full"></div>
            <h3 class="font-black text-2xl text-[#001144] tracking-tight">Especialmente para ti</h3>
        </div>
        <div class="p-12 bg-gray-50 rounded-[3rem] border-2 border-dashed border-gray-200 text-center">
            <i class="ph-duotone ph-sparkle text-5xl text-[#FF7A00] mb-4 block"></i>
            <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Analizando tu perfil para ofrecerte las mejores opciones académicas...</p>
        </div>
    </div>

</x-layouts.student>