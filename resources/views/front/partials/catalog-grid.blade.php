<div id="catalog-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-y-12 gap-x-6 pt-10">
    @forelse($courses as $course)
    @php
        // --- LÓGICA DE PRECIOS Y DESCUENTOS DINÁMICOS ---
        $originalPrice = floatval($course->price);
        $finalPrice = floatval($course->final_price);
        $hasDiscount = $course->has_discount;
        $discountPercent = $course->discount_percent;

        $whatsappNumber = "51972539712"; 
        $startDate = isset($course->metadata['start_date']) ? \Carbon\Carbon::parse($course->metadata['start_date'])->locale('es')->translatedFormat('d M, Y') : 'Inmediato';
        $courseUrl = route('course.show', $course->slug);
        $displayPrice = $hasDiscount ? $finalPrice : $originalPrice;
        $waMessage = "¡Hola CAPACITAPERU! Me gustaría inscribirme en el curso: " . $course->title . 
                     ".\n\n*Precio:* " . ($platformSettings['app_currency'] ?? '$') . " " . number_format($displayPrice, 0) . 
                     "\n*Link:* " . $courseUrl;
        $waUrl = "https://wa.me/" . $whatsappNumber . "?text=" . urlencode($waMessage);
    @endphp

    <!-- Tarjeta de curso (IDÉNTICA A LA HOME pero compacta) -->
    <div class="group relative bg-white rounded-[2rem] p-3.5 border border-gray-100 shadow-2xl shadow-gray-200/50 hover:border-[#FF7A00]/30 transition-all duration-700 overflow-visible flex flex-col h-full">
        
        <!-- Imagen Flotante -->
        <div class="relative -mt-8 mb-6 rounded-[1.5rem] overflow-hidden shadow-[0_15px_35px_rgba(0,0,0,0.2)] aspect-video group-hover:-translate-y-3 transition-all duration-700">
            <img src="{{ $course->cover_image ? asset($course->cover_image) : 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?q=80&w=800&auto=format&fit=crop' }}" 
                 class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            
            <!-- Badges -->
            <div class="absolute top-3 left-3 z-10">
                <span class="bg-[#001144]/90 backdrop-blur-md text-white text-[8px] font-black px-2.5 py-1 rounded-full shadow-xl flex items-center gap-1 uppercase tracking-widest border border-white/10">
                    <i class="ph-fill ph-certificate text-[#FF7A00]"></i> Certificación
                </span>
            </div>

            @if($hasDiscount)
                <div class="absolute top-3 right-3 bg-red-500 text-white text-[8px] font-black px-2.5 py-1 rounded-full shadow-lg z-20 uppercase tracking-widest">
                    Oferta -{{ $discountPercent }}%
                </div>
            @endif
        </div>

        <div class="px-2.5 pb-2 flex flex-col flex-1">
            <!-- Categoría -->
            <div class="flex items-center gap-1.5 mb-2.5">
                <span class="w-1.5 h-1.5 rounded-full bg-[#FF7A00]"></span>
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $course->categories->first()->name ?? 'Curso' }}</span>
            </div>

            <h3 class="font-black text-base text-[#001144] mb-3 group-hover:text-[#FF7A00] transition-colors leading-tight min-h-[3rem] flex items-center">
                {{ $course->title }}
            </h3>

            <!-- Info Pills -->
            <div class="flex flex-wrap gap-1.5 mb-6">
                <span class="bg-gray-50 text-gray-500 text-[9px] font-bold px-2.5 py-1 rounded-full flex items-center gap-1 border border-gray-100">
                    <i class="ph-bold ph-calendar"></i> Inicio: {{ $startDate }}
                </span>
                <span class="bg-[#0033CC]/5 text-[#0033CC] text-[9px] font-bold px-2.5 py-1 rounded-full flex items-center gap-1 border border-[#0033CC]/10">
                    <i class="ph-bold ph-video"></i> {{ $course->metadata['modality'] ?? 'Virtual' }}
                </span>
            </div>

            <div class="mt-auto pt-4 border-t border-gray-50 flex flex-col gap-4">
                <!-- Precio Centrado -->
                <div class="text-center">
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-0.5">Inversión Total</p>
                    <div class="flex items-baseline justify-center gap-2">
                        @if($hasDiscount)
                            <span class="text-2xl font-black text-[#0033CC]">{{ $platformSettings['app_currency'] ?? '$' }} {{ number_format($finalPrice, 0) }}</span>
                            <span class="text-xs text-gray-400 line-through font-bold">{{ $platformSettings['app_currency'] ?? '$' }} {{ number_format($originalPrice, 0) }}</span>
                        @else
                            <span class="text-2xl font-black text-[#0033CC]">{{ $platformSettings['app_currency'] ?? '$' }} {{ number_format($originalPrice, 0) }}</span>
                        @endif
                    </div>
                </div>

                <!-- Botones Lado a Lado -->
                <div class="grid grid-cols-2 gap-2">
                    <button data-course-btn data-course-id="{{ $course->id }}"
                            onclick="animateFlyToCart(this, {
                        id: {{ $course->id }},
                        title: '{{ addslashes($course->title) }}',
                        price: {{ $finalPrice }},
                        original_price: {{ $originalPrice }},
                        cover: '{{ $course->cover_image ? asset($course->cover_image) : 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?q=80&w=800&auto=format&fit=crop' }}',
                        instructor: '{{ addslashes($course->instructor ? $course->instructor->name : 'CapacitaPerú') }}'
                    })" class="bg-[#25D366] text-white text-center py-3 rounded-xl font-black text-[9px] hover:bg-[#1fb355] transition-all shadow-xl shadow-green-200/30 flex items-center justify-center gap-1.5 uppercase tracking-widest cursor-pointer w-full">
                        <i class="ph-bold ph-shopping-cart-simple text-base"></i> + Carrito
                    </button>
                    <a href="{{ route('course.show', $course->slug) }}" class="bg-[#001144] text-white text-center py-3 rounded-xl font-black text-[9px] hover:bg-[#FF7A00] transition-all shadow-xl shadow-blue-200/30 flex items-center justify-center gap-1.5 uppercase tracking-widest">
                        <i class="ph-bold ph-eye text-base"></i> Detalles
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
        <div class="col-span-full text-center py-20">
            <i class="ph-bold ph-magnifying-glass text-5xl text-gray-200 mb-4"></i>
            <p class="text-gray-400">No se encontraron cursos disponibles.</p>
        </div>
    @endforelse
</div>

<!-- Conteo oculto para actualizar vía JS -->
<span id="js-course-count-value" class="hidden">{{ $courses->total() }}</span>