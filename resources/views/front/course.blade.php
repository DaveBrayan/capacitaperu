<x-layouts.guest>
    <!-- LIBRERÍAS (Phosphor Icons) -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- HEADER / HERO SECTION (Premium Design) -->
    <div class="relative bg-[#001144] text-white pt-32 pb-40 overflow-hidden">
        <!-- Decoración de fondo dinámica -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-24 -right-24 w-[600px] h-[600px] bg-[#FF7A00] opacity-10 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-[#0033CC] opacity-20 rounded-full blur-[150px]"></div>
            
            @php
                $coverImage = $course->cover_image ? asset($course->cover_image) : 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?q=80&w=2000&auto=format&fit=crop';
            @endphp
            <div class="absolute inset-0 opacity-20 mix-blend-overlay">
                <img src="{{ $coverImage }}" class="w-full h-full object-cover">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row gap-12">
                
                <!-- Columna Izquierda (Info del Curso) -->
                <div class="lg:w-2/3 animate-fade-in-up">
                    <!-- Breadcrumb Moderno -->
                    <nav class="flex items-center gap-3 text-xs font-black uppercase tracking-[0.2em] text-gray-400 mb-8">
                        <a href="{{ route('home') }}" class="hover:text-[#FF7A00] transition-all">Inicio</a>
                        <span class="w-1 h-1 bg-gray-600 rounded-full"></span>
                        <a href="{{ route('catalog') }}" class="hover:text-[#FF7A00] transition-all">Catálogo</a>
                        <span class="w-1 h-1 bg-gray-600 rounded-full"></span>
                        <span class="text-[#FF7A00]">{{ $course->categories->first()->name ?? 'Programa Especializado' }}</span>
                    </nav>

                    <h1 class="text-3xl md:text-5xl font-black mb-8 leading-[1.1] tracking-tight">
                        {{ $course->title }}
                    </h1>

                    <div class="text-xl text-gray-300 mb-10 leading-relaxed max-w-3xl font-medium opacity-90">
                        {!! $course->short_description ?? 'Impulsa tu carrera con nuestra formación especializada diseñada para los retos del mercado actual.' !!}
                    </div>

                    <!-- Meta Datos con Estilo -->
                    <div class="flex flex-wrap gap-8 items-center">
                        <div class="flex items-center gap-3 bg-white/5 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/10 shadow-xl">
                            <div class="flex text-[#FF7A00] text-lg">
                                @php $rating = $course->rating ?? 5; @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="ph-fill ph-star {{ $i <= $rating ? '' : 'text-gray-600' }}"></i>
                                @endfor
                            </div>
                            <span class="font-black text-white">{{ $rating }}.0</span>
                            <span class="text-gray-400 text-xs font-bold uppercase tracking-widest">Estrellas</span>
                        </div>
                        
                        <div class="flex items-center gap-3 text-gray-300">
                            <div class="w-10 h-10 rounded-xl bg-[#FF7A00]/20 flex items-center justify-center text-[#FF7A00]">
                                <i class="ph-bold ph-calendar-check text-xl"></i>
                            </div>
                            <span class="font-bold">Actualizado: {{ $course->updated_at->locale('es')->translatedFormat('F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="bg-white min-h-screen py-20 relative -mt-20 rounded-t-[3rem] lg:rounded-t-[5rem] z-20 shadow-[0_-20px_50px_rgba(0,0,0,0.1)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-16">

                <!-- COLUMNA IZQUIERDA (Detalles) -->
                <div class="lg:w-2/3 space-y-16">
                    
                    <!-- NUEVA SECCIÓN: POR QUÉ ELEGIR ESTE PROGRAMA (VALOR) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-fade-in" style="animation-delay: 0.1s">
                        <div class="p-8 bg-[#001144]/5 rounded-[2rem] border border-[#001144]/10">
                            <i class="ph-bold ph-trend-up text-[#FF7A00] text-3xl mb-4"></i>
                            <h4 class="font-black text-[#001144] mb-2">Crecimiento Profesional</h4>
                            <p class="text-sm text-gray-500 font-medium leading-relaxed">Este programa está diseñado para darte las herramientas que el mercado laboral actual exige, aumentando tu competitividad.</p>
                        </div>
                        <div class="p-8 bg-[#0033CC]/5 rounded-[2rem] border border-[#0033CC]/10">
                            <i class="ph-bold ph-seal-check text-[#0033CC] text-3xl mb-4"></i>
                            <h4 class="font-black text-[#001144] mb-2">Certificación Oficial</h4>
                            <p class="text-sm text-gray-500 font-medium leading-relaxed">Al finalizar, recibirás un certificado con respaldo institucional de CapacitaPerú, válido para tu currículum.</p>
                        </div>
                    </div>

                    <!-- Lo que aprenderás (Glassmorphism Light) -->
                    @if($course->learning_outcomes)
                    <div class="bg-gray-50/50 p-8 rounded-[2rem] border border-gray-100 shadow-sm animate-fade-in">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 bg-[#FF7A00] rounded-xl flex items-center justify-center text-white shadow-lg shadow-[#FF7A00]/30">
                                <i class="ph-bold ph-lightning text-xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-[#001144]">Lo que aprenderás</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @php 
                                $outcomes = is_array($course->learning_outcomes) ? $course->learning_outcomes : json_decode($course->learning_outcomes, true);
                            @endphp
                            @if($outcomes)
                                @foreach($outcomes as $outcome)
                                <div class="flex gap-3 p-3 bg-white rounded-xl border border-gray-100 shadow-sm hover:border-[#FF7A00]/50 transition-all group">
                                    <i class="ph-bold ph-check-circle text-[#FF7A00] text-xl mt-0.5 group-hover:scale-110 transition-transform"></i>
                                    <span class="text-gray-600 font-bold text-xs leading-relaxed">{{ $outcome }}</span>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Descripción extendida -->
                    <div class="animate-fade-in" style="animation-delay: 0.2s">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 bg-[#001144] rounded-xl flex items-center justify-center text-white shadow-lg">
                                <i class="ph-bold ph-text-align-left text-xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-[#001144]">Sobre este programa</h3>
                        </div>
                        <div class="prose prose-md prose-slate max-w-none text-gray-600 leading-relaxed font-medium wysiwyg-content">
                            {!! $course->description !!}
                        </div>
                    </div>

                    <!-- Temario (Accordion Moderno) -->
                    <div class="bg-gray-50 p-10 rounded-[2.5rem] border border-gray-100 animate-fade-in" style="animation-delay: 0.3s">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-[#0033CC] rounded-xl flex items-center justify-center text-white shadow-lg">
                                    <i class="ph-bold ph-books text-xl"></i>
                                </div>
                                <h3 class="text-2xl font-black text-[#001144]">Plan de Estudios</h3>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-black text-[#001144]">{{ $course->modules->count() }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Módulos</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @foreach($course->modules as $index => $module)
                            <div class="group bg-white rounded-3xl border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-500">
                                <button onclick="toggleModule('module-{{ $index }}')" class="w-full flex items-center justify-between p-6 text-left focus:outline-none">
                                    <div class="flex items-center gap-5">
                                        <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-[#001144] font-black group-hover:bg-[#FF7A00] group-hover:text-white transition-all">
                                            {{ sprintf('%02d', $index + 1) }}
                                        </div>
                                        <span class="font-black text-[#001144] text-lg">{{ $module->title }}</span>
                                    </div>
                                    <i id="icon-module-{{ $index }}" class="ph-bold ph-caret-down text-xl text-gray-400 transition-transform duration-500"></i>
                                </button>
                                <div id="module-{{ $index }}" class="hidden px-8 pb-8 pt-2">
                                    <div class="pl-14 space-y-4 border-l-2 border-dashed border-gray-100">
                                        @foreach($module->lessons as $lesson)
                                        <div class="flex items-center justify-between group/lesson">
                                            <div class="flex items-center gap-4">
                                                <div class="w-2 h-2 rounded-full bg-gray-200 group-hover/lesson:bg-[#FF7A00] transition-colors"></div>
                                                <span class="text-gray-600 font-bold text-sm">{{ $lesson->title }}</span>
                                            </div>
                                            <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">{{ $lesson->duration ?? '15' }} min</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Instructor Detalle -->
                    @if($course->instructor)
                    <div class="bg-[#001144] p-12 rounded-[3rem] text-white overflow-hidden relative group">
                        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-[#FF7A00] opacity-10 rounded-full blur-[80px] group-hover:opacity-20 transition-opacity"></div>
                        
                        <div class="relative z-10 flex flex-col sm:flex-row gap-10 items-center sm:items-start">
                            <div class="relative">
                                <img src="{{ $course->instructor->avatar_url ? asset($course->instructor->avatar_url) : 'https://ui-avatars.com/api/?name='.urlencode($course->instructor->name).'&background=FF7A00&color=fff&size=200' }}" class="w-40 h-40 rounded-[2.5rem] object-cover border-4 border-white/10 shadow-2xl">
                                <div class="absolute -bottom-4 -right-4 w-12 h-12 bg-[#FF7A00] rounded-2xl flex items-center justify-center shadow-lg">
                                    <i class="ph-fill ph-seal-check text-2xl"></i>
                                </div>
                            </div>
                            <div class="text-center sm:text-left">
                                <p class="text-[#FF7A00] font-black text-xs uppercase tracking-[0.3em] mb-3">Instructor Certificado</p>
                                <h4 class="text-4xl font-black mb-4">{{ $course->instructor->name }}</h4>
                                <p class="text-white/60 font-medium leading-relaxed mb-6 italic">
                                    "{{ $course->instructor->bio ?? 'Dedicado a formar a la próxima generación de profesionales con excelencia y pasión.' }}"
                                </p>
                                <div class="flex justify-center sm:justify-start gap-4">
                                    <div class="px-4 py-2 bg-white/5 rounded-xl border border-white/10 text-xs font-bold">
                                        Experticia Senior
                                    </div>
                                    <div class="px-4 py-2 bg-white/5 rounded-xl border border-white/10 text-xs font-bold">
                                        Mentoria 1 a 1
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- COLUMNA DERECHA (Floating Card) -->
                <div class="lg:w-1/3">
                    <div class="sticky top-24 space-y-8 lg:-mt-96">
                        
                        <div class="bg-white rounded-[3rem] shadow-[0_50px_100px_rgba(0,17,68,0.12)] overflow-hidden border border-gray-100 animate-fade-in-right">
                            <div class="relative h-64 group cursor-pointer">
                                <img src="{{ $coverImage }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <div class="absolute inset-0 bg-[#001144]/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-2xl animate-bounce">
                                        <i class="ph-fill ph-play text-[#FF7A00] text-4xl ml-1"></i>
                                    </div>
                                </div>
                                <div class="absolute top-6 right-6 bg-[#FF7A00] text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg">
                                    @if($course->has_discount)
                                        Oferta Especial
                                    @else
                                        {{ $course->type == 'diploma' ? 'Diplomado' : 'Curso Pro' }}
                                    @endif
                                </div>
                            </div>

                            <div class="p-10">
                                @php
                                    $whatsappNumber = "51972539712";
                                    $startDateLabel = isset($course->metadata['start_date']) ? \Carbon\Carbon::parse($course->metadata['start_date'])->locale('es')->translatedFormat('d \d\e F, Y') : 'Acceso Inmediato';
                                    
                                    $waMessage = "¡Hola CapacitaPerú! Me gustaría inscribirme en el programa: " . $course->title . 
                                                 ".\n\n*Inversión:* " . ($platformSettings['app_currency'] ?? '$') . " " . number_format($course->final_price, 0) . 
                                                 "\n*Link:* " . route('course.show', $course->slug);
                                    
                                    $waUrl = "https://wa.me/" . $whatsappNumber . "?text=" . urlencode($waMessage);
                                @endphp

                                <div class="text-center mb-8">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.3em] mb-2">Inversión Total</p>
                                    <div class="flex items-center justify-center gap-4">
                                        @if($course->has_discount)
                                            <span class="text-4xl font-black text-[#001144]">{{ $platformSettings['app_currency'] ?? '$' }} {{ number_format($course->final_price) }}</span>
                                            <span class="text-lg text-gray-300 line-through font-bold">{{ $platformSettings['app_currency'] ?? '$' }} {{ number_format($course->price) }}</span>
                                        @else
                                            <span class="text-4xl font-black text-[#001144]">{{ $platformSettings['app_currency'] ?? '$' }} {{ number_format($course->price) }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="space-y-3 mb-8">
                                    <a href="{{ $waUrl }}" target="_blank" class="block w-full py-4 bg-[#25D366] text-white font-black rounded-2xl shadow-xl shadow-green-200/50 hover:bg-[#1fb355] hover:-translate-y-1 transition-all text-center flex items-center justify-center gap-3 uppercase tracking-widest text-xs">
                                        <i class="ph-fill ph-whatsapp-logo text-xl"></i> Inscribirme por WhatsApp
                                    </a>
                                    <button data-course-btn data-course-id="{{ $course->id }}" data-is-detail="true"
                                            onclick="addToCart({
                                        id: {{ $course->id }},
                                        title: '{{ addslashes($course->title) }}',
                                        price: {{ $course->final_price }},
                                        original_price: {{ $course->price }},
                                        cover: '{{ $coverImage }}',
                                        instructor: '{{ addslashes($course->instructor ? $course->instructor->name : 'CapacitaPerú') }}'
                                    })" class="block w-full py-4 bg-[#001144] text-white font-black rounded-2xl hover:bg-[#FF7A00] transition-all text-center uppercase tracking-widest text-xs cursor-pointer">
                                        Añadir al Carrito
                                    </button>
                                </div>

                                <!-- Beneficios Checklist -->
                                <div class="space-y-5 p-8 bg-gray-50 rounded-[2rem] border border-gray-100">
                                    <h4 class="font-black text-[#001144] text-xs uppercase tracking-widest mb-4">El programa incluye:</h4>
                                    <ul class="space-y-4">
                                        <li class="flex items-center gap-4 text-sm font-bold text-gray-600">
                                            <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-[#FF7A00]">
                                                <i class="ph-bold ph-certificate"></i>
                                            </div>
                                            Certificado con Valor Curricular
                                        </li>
                                        <li class="flex items-center gap-4 text-sm font-bold text-gray-600">
                                            <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-[#FF7A00]">
                                                <i class="ph-bold ph-video"></i>
                                            </div>
                                            Clases HD en Plataforma 24/7
                                        </li>
                                        <li class="flex items-center gap-4 text-sm font-bold text-gray-600">
                                            <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-[#FF7A00]">
                                                <i class="ph-bold ph-users-three"></i>
                                            </div>
                                            Comunidad Privada de Alumnos
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Badge de Confianza Corporativa -->
                        <div class="p-8 bg-gradient-to-br from-[#0033CC]/5 to-transparent rounded-[2rem] border border-blue-100/50 flex items-center gap-6">
                            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-[#0033CC] shadow-xl">
                                <i class="ph-bold ph-shield-check text-3xl"></i>
                            </div>
                            <div>
                                <h5 class="font-black text-[#001144] text-sm">Capacitación Segura</h5>
                                <p class="text-xs text-gray-500 font-medium">Respaldo institucional de CapacitaPerú.</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function toggleModule(id) {
            const el = document.getElementById(id);
            const icon = document.getElementById('icon-' + id);
            
            if (el.classList.contains('hidden')) {
                el.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                el.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }
    </script>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
        .animate-fade-in { animation: fadeIn 1s ease-out forwards; }
        .animate-fade-in-right { animation: fadeInRight 0.8s ease-out 0.2s forwards; opacity: 0; }

        .wysiwyg-content ul { list-style-type: disc !important; padding-left: 1.5rem !important; margin-bottom: 1.5rem !important; }
        .wysiwyg-content li { margin-bottom: 0.5rem !important; }
        .wysiwyg-content p { margin-bottom: 1rem !important; }
        .wysiwyg-content h2, .wysiwyg-content h3 { font-weight: 900 !important; color: #001144 !important; margin-top: 2rem !important; margin-bottom: 1rem !important; }
    </style>
</x-layouts.guest>