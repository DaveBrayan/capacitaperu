<x-layouts.guest>

    <!-- HERO SECTION PREMIUM (Imagen de Fondo + Tarjeta de Curso Destacado) -->
    <div class="relative min-h-screen flex items-center pt-20 pb-20 overflow-hidden">
        
        <!-- FONDO: Estilo Template con Degradado Suave -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset($platformSettings['home_hero_bg'] ?? 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?q=80&w=2000&auto=format&fit=crop') }}" 
                 alt="Fondo Educativo" 
                 class="w-full h-full object-cover object-center opacity-40">
            <!-- Overlay degradado de superficie (Warm Neutral) hacia transparente -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#fff8f5] via-[#fff8f5]/90 to-transparent"></div>
        </div>

        <!-- CONTENIDO HERO -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <!-- COLUMNA IZQUIERDA: Texto Principal -->
                <div class="text-center lg:text-left pt-10 lg:pt-0">
                    <div class="inline-flex items-center gap-2 py-1 px-3 rounded-full bg-[#FF7A00]/10 border border-[#FF7A00]/30 backdrop-blur-md mb-6 fade-in-up">
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#FF7A00] opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-[#FF7A00]"></span>
                        </span>
                        <span class="text-[#FF7A00] text-xs font-bold tracking-widest uppercase">{{ $platformSettings['home_hero_tag'] ?? 'Plataforma Educativa' }}</span>
                    </div>

                    <h1 class="text-5xl lg:text-7xl font-extrabold text-[#001144] mb-6 leading-[1.1] fade-in-up delay-100 drop-shadow-sm">
                        {!! $platformSettings['home_hero_title'] ?? 'Bienvenidos a <br> <span class="text-[#FF7A00]">CAPACITAPERU</span>' !!}
                    </h1>

                    <p class="text-xl text-[#584235] mb-8 max-w-2xl mx-auto lg:mx-0 fade-in-up delay-200 leading-relaxed font-medium">
                        {{ $platformSettings['home_hero_desc'] ?? 'Tu espacio creado para responder a las nuevas demandas del mercado laboral. Ofrecemos capacitación especializada y accesible para profesionales, técnicos y estudiantes, respaldados por certificación reconocida a nivel nacional.' }}
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start fade-in-up delay-300">
                        <a href="{{ route('catalog') }}" class="btn-primary text-white px-8 py-4 rounded-full font-bold text-lg shadow-[0_0_20px_rgba(255,122,0,0.4)] hover:shadow-[0_0_30px_rgba(255,122,0,0.6)] flex items-center justify-center gap-2 group transition-all transform hover:-translate-y-1">
                            {{ $platformSettings['home_hero_btn1_text'] ?? 'Ver Programas Disponibles' }}
                            <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="{{ route('front.certificate.individual.search') }}" class="bg-[#0033CC] text-white px-8 py-4 rounded-full font-bold text-lg transition-all flex items-center justify-center gap-2 hover:bg-[#001144] shadow-lg shadow-[#0033CC]/20 transform hover:-translate-y-1">
                            <i class="ph-bold ph-certificate"></i>
                            {{ $platformSettings['home_hero_btn2_text'] ?? 'Validar Certificado' }}
                        </a>
                    </div>
                </div>

                <!-- COLUMNA DERECHA: Imagen Fija y Animada Premium con Elementos Flotantes -->
                <div class="hidden lg:flex justify-center relative fade-in-up delay-300 overflow-visible">
                    <!-- Decoración de fondo brillante y animada -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-gradient-to-br from-[#FF7A00]/25 to-[#0033CC]/30 rounded-full blur-[80px] animate-pulse"></div>
                    
                    <div class="relative w-full max-w-lg">
                        <!-- Marco principal con diseño premium y bordes redondeados (Glassmorphism) -->
                        <div class="relative z-10 rounded-[3rem] p-3 bg-gradient-to-br from-white/40 to-white/10 backdrop-blur-md border border-white/30 shadow-[0_50px_100px_-20px_rgba(0,17,68,0.25)] overflow-hidden transform hover:scale-[1.02] hover:rotate-1 transition-all duration-700">
                            <img src="{{ asset($platformSettings['home_hero_right_img'] ?? 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200&auto=format&fit=crop') }}" 
                                 alt="Capacitación Profesional CapacitaPerú" 
                                 class="rounded-[2.5rem] w-full h-[420px] object-cover shadow-2xl">
                            
                            <!-- Overlay sutil con gradiente de color de la marca -->
                            <div class="absolute inset-0 bg-gradient-to-t from-[#001144]/60 via-transparent to-transparent rounded-[2.5rem] pointer-events-none"></div>
                        </div>

                        <!-- Elemento flotante 1: Certificación Nacional (Micro-animación de rebote) -->
                        <div class="absolute -top-6 -right-6 z-20 bg-white/95 backdrop-blur-md border border-white/50 px-6 py-4 rounded-3xl shadow-[0_20px_40px_rgba(0,0,0,0.15)] flex items-center gap-3 animate-[bounce_4s_infinite]">
                            <div class="w-12 h-12 rounded-2xl bg-[#FF7A00]/10 flex items-center justify-center text-[#FF7A00]">
                                <i class="ph-bold ph-certificate text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-[#001144] uppercase tracking-wider">{{ $platformSettings['home_hero_badge1_title'] ?? 'Certificación' }}</h4>
                                <p class="text-[10px] text-gray-500 font-bold">{{ $platformSettings['home_hero_badge1_desc'] ?? 'Oficial e Inmediata' }}</p>
                            </div>
                        </div>

                        <!-- Elemento flotante 2: Soporte 24/7 (Micro-animación flotante lenta) -->
                        <div class="absolute -bottom-6 -left-6 z-20 bg-white/95 backdrop-blur-md border border-white/50 px-6 py-4 rounded-3xl shadow-[0_20px_40px_rgba(0,0,0,0.15)] flex items-center gap-3 animate-[bounce_5s_infinite_1s]">
                            <div class="w-12 h-12 rounded-2xl bg-[#0033CC]/10 flex items-center justify-center text-[#0033CC]">
                                <i class="ph-bold ph-users-three text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-[#001144] uppercase tracking-wider">{{ $platformSettings['home_hero_badge2_title'] ?? 'Asesoría Activa' }}</h4>
                                <p class="text-[10px] text-gray-500 font-bold">{{ $platformSettings['home_hero_badge2_desc'] ?? 'Respuesta Inmediata' }}</p>
                            </div>
                        </div>

                        <!-- Elemento flotante 3: 100% Online -->
                        <div class="absolute top-1/2 -left-12 z-20 bg-[#001144] text-white px-5 py-3 rounded-2xl shadow-xl flex items-center gap-2 transform -translate-y-1/2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-xs font-black uppercase tracking-widest">{{ $platformSettings['home_hero_badge3_text'] ?? '100% Virtual' }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- SECCIÓN: QUIÉNES SOMOS -->
    <div id="nosotros" class="bg-white py-24 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-16 items-center">
                <div class="w-full md:w-1/2 relative">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-[#FF7A00]/5 rounded-full blur-3xl"></div>
                    <div class="relative rounded-[3rem] overflow-hidden shadow-2xl">
                        <img src="{{ asset($platformSettings['home_about_img'] ?? 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200&auto=format&fit=crop') }}" class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700">
                    </div>
                    <!-- Card flotante de valores -->
                    <div class="absolute -bottom-8 -right-8 bg-[#001144] p-8 rounded-3xl shadow-2xl text-white max-w-xs z-20">
                        <div class="flex gap-4 items-center mb-4">
                            <div class="w-10 h-10 rounded-full bg-[#FF7A00] flex items-center justify-center">
                                <i class="ph-bold ph-star text-xl"></i>
                            </div>
                            <span class="font-bold">{{ $platformSettings['home_about_badge_title'] ?? 'Nuestra Misión' }}</span>
                        </div>
                        <p class="text-sm text-gray-300 leading-relaxed">
                            {{ $platformSettings['home_about_badge_desc'] ?? 'Fortalecer tus competencias e impulsar tu crecimiento profesional para los retos del futuro.' }}
                        </p>
                    </div>
                </div>
                <div class="w-full md:w-1/2">
                    <span class="text-[#FF7A00] font-black tracking-[0.2em] text-xs uppercase mb-4 block">{{ $platformSettings['home_about_tag'] ?? 'Sobre Nosotros' }}</span>
                    <h2 class="text-4xl md:text-5xl font-black text-[#001144] mb-8 leading-tight">{!! $platformSettings['home_about_title'] ?? 'Fortaleciendo el Futuro <br> Profesional de la Región' !!}</h2>
                    <p class="text-gray-600 mb-6 leading-relaxed text-lg italic border-l-4 border-[#FF7A00] pl-6 py-2">
                        {{ $platformSettings['home_about_quote'] ?? '"En CAPACITAPERU, nos comprometemos en fortalecer tus competencias e impulsamos tu crecimiento profesional, preparándote para afrontar con éxito los retos actuales y futuros."' }}
                    </p>
                    <p class="text-gray-500 mb-8 leading-relaxed">
                        {{ $platformSettings['home_about_desc'] ?? 'Somos un centro de formación y actualización profesional creado para responder a las nuevas demandas del mercado laboral. Ofrecemos capacitación especializada y accesible para profesionales, técnicos, estudiantes y trabajadores de diversos sectores, respaldados por certificación reconocida a nivel nacional.' }}
                    </p>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100">
                            <i class="ph-bold ph-shield-check text-[#FF7A00] text-3xl mb-3"></i>
                            <h4 class="font-bold text-[#001144]">Calidad</h4>
                            <p class="text-xs text-gray-400">Contenidos actualizados</p>
                        </div>
                        <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100">
                            <i class="ph-bold ph-users-three text-[#FF7A00] text-3xl mb-3"></i>
                            <h4 class="font-bold text-[#001144]">Comunidad</h4>
                            <p class="text-xs text-gray-400">+10k Estudiantes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN: ESTADÍSTICAS CLAVE -->
    <div class="py-20 bg-[#001144] relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#FF7A00_1px,transparent_1px)] [background-size:20px_20px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-12 text-center">
                <div class="space-y-2">
                    <p class="text-5xl md:text-6xl font-black text-white">{{ $platformSettings['home_about_stat1_val'] ?? '+10,000' }}</p>
                    <p class="text-[#FF7A00] font-bold uppercase tracking-widest text-xs">{{ $platformSettings['home_about_stat1_lbl'] ?? 'Estudiantes' }}</p>
                </div>
                <div class="space-y-2">
                    <p class="text-5xl md:text-6xl font-black text-white">{{ $platformSettings['home_about_stat2_val'] ?? '+8' }}</p>
                    <p class="text-[#FF7A00] font-bold uppercase tracking-widest text-xs">{{ $platformSettings['home_about_stat2_lbl'] ?? 'Años de Exp.' }}</p>
                </div>
                <div class="space-y-2">
                    <p class="text-5xl md:text-6xl font-black text-white">{{ $platformSettings['home_about_stat3_val'] ?? '+150' }}</p>
                    <p class="text-[#FF7A00] font-bold uppercase tracking-widest text-xs">{{ $platformSettings['home_about_stat3_lbl'] ?? 'Cursos Dictados' }}</p>
                </div>
                <div class="space-y-2">
                    <p class="text-5xl md:text-6xl font-black text-white">{{ $platformSettings['home_about_stat4_val'] ?? '99%' }}</p>
                    <p class="text-[#FF7A00] font-bold uppercase tracking-widest text-xs">{{ $platformSettings['home_about_stat4_lbl'] ?? 'Satisfacción' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN: BENTO GRID BENEFICIOS -->
    <div id="certificaciones" class="py-24 bg-[#fff8f5]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Gran Bloque 1 -->
                <div class="col-span-1 md:col-span-2 bg-white border border-[#0033CC]/10 rounded-3xl p-10 flex flex-col justify-between shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-10 -top-10 opacity-5 group-hover:scale-110 transition-transform duration-500 pointer-events-none">
                        <i class="ph-bold ph-graduation-cap text-[200px] text-[#0033CC]"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-[#001144] mb-4">{{ $platformSettings['home_bento1_title'] ?? 'Eleva tu Carrera Profesional' }}</h3>
                        <p class="text-[#584235] text-lg leading-relaxed">
                            {{ $platformSettings['home_bento1_desc'] ?? 'Diseñado para profesionales que buscan entrar al ecosistema industrial y tecnológico de Perú. No necesitas experiencia previa, solo las ganas de aprender.' }}
                        </p>
                    </div>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <div class="bg-[#FF7A00]/10 py-2 px-4 rounded-xl font-bold text-xs text-[#FF7A00]">Flexible</div>
                        <div class="bg-[#0033CC]/10 py-2 px-4 rounded-xl font-bold text-xs text-[#0033CC]">Portafolio Real</div>
                    </div>
                </div>

                <!-- Bloque 2 -->
                <div class="col-span-1 bg-white border border-[#0033CC]/10 rounded-3xl p-8 flex flex-col items-center justify-center text-center shadow-sm hover:border-[#FF7A00] transition-colors">
                    <div class="w-16 h-16 rounded-full bg-[#FF7A00]/10 flex items-center justify-center mb-4">
                        <i class="ph-bold ph-clock text-3xl text-[#FF7A00]"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-[#001144]">{{ $platformSettings['home_bento2_title'] ?? 'Tu Ritmo' }}</h4>
                    <p class="text-sm text-gray-500 mt-1">{{ $platformSettings['home_bento2_sub'] ?? 'Acceso vitalicio 24/7' }}</p>
                </div>

                <!-- Bloque 3 -->
                <div class="col-span-1 bg-white border border-[#0033CC]/10 rounded-3xl p-8 flex flex-col items-center justify-center text-center shadow-sm hover:border-[#FF7A00] transition-colors">
                    <div class="w-16 h-16 rounded-full bg-[#0033CC]/10 flex items-center justify-center mb-4">
                        <i class="ph-bold ph-certificate text-3xl text-[#0033CC]"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-[#001144]">{{ $platformSettings['home_bento3_title'] ?? 'QR Seguro' }}</h4>
                    <p class="text-sm text-gray-500 mt-1">{{ $platformSettings['home_bento3_sub'] ?? 'Verificación inmediata' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN: CATEGORÍAS (Sustituye a Unete a nosotros) -->
    <div class="py-24 bg-[#001144]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                <div class="text-white">
                    <span class="text-[#FF7A00] font-black tracking-[0.2em] text-xs uppercase mb-3 block">{{ $platformSettings['home_cat_tag'] ?? 'Explora Áreas' }}</span>
                    <h2 class="text-4xl md:text-5xl font-black leading-tight">{!! $platformSettings['home_cat_title'] ?? 'Especialízate en las <br> Mejores Categorías' !!}</h2>
                </div>
                <p class="text-gray-400 max-w-sm text-lg">{{ $platformSettings['home_cat_desc'] ?? 'Descubre rutas de aprendizaje diseñadas para las demandas reales de la industria técnica peruana.' }}</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @forelse($categories as $category)
                <a href="{{ route('catalog', ['category' => $category->slug]) }}" class="group relative bg-white/5 border border-white/10 rounded-[2rem] px-3 py-8 text-center hover:bg-white hover:border-white hover:-translate-y-3 transition-all duration-500 overflow-hidden shadow-2xl shadow-black/20">
                    <!-- Glow effect on hover -->
                    <div class="absolute inset-0 bg-gradient-to-br from-[#FF7A00]/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-[#FF7A00] group-hover:scale-110 transition-all duration-500 shadow-xl">
                            <i class="ph-bold {{ $category->icon ?? 'ph-layout' }} text-3xl text-white group-hover:text-white"></i>
                        </div>
                        <h4 class="text-white font-black text-xs sm:text-sm md:text-base mb-2 group-hover:text-[#001144] transition-colors leading-tight break-words uppercase px-1">{{ $category->name }}</h4>
                        <p class="text-white/40 text-[10px] font-black group-hover:text-[#001144]/60 transition-colors uppercase tracking-widest">{{ $category->courses_count }} Cursos</p>
                    </div>

                    <!-- Decorative background circle that expands on hover -->
                    <div class="absolute -right-6 -bottom-6 w-20 h-20 bg-white/5 rounded-full group-hover:scale-[10] group-hover:bg-white transition-all duration-700 ease-in-out"></div>
                </a>
                @empty
                <div class="col-span-full text-center text-white/40 py-12">
                    <p>No hay categorías disponibles por ahora.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- SECCIÓN: MÉTODOS DE PAGO -->
    <div class="py-24 bg-gray-50 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-[#FF7A00] font-black tracking-[0.2em] text-xs uppercase mb-3 block">{{ $platformSettings['home_pay_tag'] ?? 'Inscripción Simple' }}</span>
                <h2 class="text-4xl font-black text-[#001144]">{{ $platformSettings['home_pay_title'] ?? 'Métodos de Pago Seguros' }}</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div onclick="openQrModal()" class="bg-white p-8 rounded-3xl border border-gray-200 shadow-xl hover:border-[#FF7A00] transition-all group cursor-pointer hover:-translate-y-1 duration-300">
                    <div class="w-16 h-16 bg-[#FF7A00]/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#FF7A00] transition-all">
                        <i class="ph-bold ph-qr-code text-3xl text-[#FF7A00] group-hover:text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-[#001144] mb-3 flex items-center gap-2">
                        {{ $platformSettings['home_pay_c1_title'] ?? 'Transferencia QR (Simple)' }}
                        <i class="ph-bold ph-arrow-up-right text-xs opacity-0 group-hover:opacity-100 transition-all text-[#FF7A00]"></i>
                    </h4>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $platformSettings['home_pay_c1_desc'] ?? 'Paga de forma inmediata escaneando nuestro código QR desde cualquier aplicación bancaria.' }}</p>
                </div>
                <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-xl hover:border-[#FF7A00] transition-all group">
                    <div class="w-16 h-16 bg-[#0033CC]/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#0033CC] transition-all">
                        <i class="ph-bold ph-phone text-3xl text-[#0033CC] group-hover:text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-[#001144] mb-3">{{ $platformSettings['home_pay_c2_title'] ?? 'Pling' }}</h4>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $platformSettings['home_pay_c2_desc'] ?? 'Realiza tu pago de forma rápida y segura a través de tu billetera Pling.' }}</p>
                </div>
                <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-xl hover:border-[#FF7A00] transition-all group">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-gray-800 transition-all">
                        <i class="ph-bold ph-credit-card text-3xl text-gray-600 group-hover:text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-[#001144] mb-3">{{ $platformSettings['home_pay_c3_title'] ?? 'Tarjetas de Crédito/Débito' }}</h4>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $platformSettings['home_pay_c3_desc'] ?? 'Aceptamos todas las tarjetas nacionales e internacionales con procesamiento seguro.' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CALL TO ACTION FINAL -->
    <div class="py-24 relative overflow-hidden bg-[#001144]">
        <div class="absolute inset-0">
             <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=2000&auto=format&fit=crop" class="w-full h-full object-cover opacity-10 mix-blend-overlay">
        </div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-[#FF7A00] opacity-10 rounded-full blur-[100px]"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h2 class="text-3xl md:text-5xl font-bold text-white mb-6">{{ $platformSettings['home_cta_title'] ?? 'Comienza hoy tu camino hacia el éxito profesional' }}</h2>
            <p class="text-gray-300 text-lg mb-10 leading-relaxed max-w-2xl mx-auto">
                {{ $platformSettings['home_cta_desc'] ?? 'Aprovecha las oportunidades laborales con las certificaciones que las empresas más buscan.' }}
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="https://wa.me/{{ $platformSettings['home_cta_whatsapp'] ?? '51972539712' }}" target="_blank" class="bg-[#FF7A00] hover:bg-[#FF9500] text-white px-10 py-4 rounded-full font-bold text-lg transition-all shadow-[0_0_20px_rgba(255,122,0,0.3)] hover:shadow-[0_0_30px_rgba(255,122,0,0.5)] transform hover:-translate-y-1">
                    {{ $platformSettings['home_cta_btn_text'] ?? 'Contactar por WhatsApp' }}
                </a>
            </div>
        </div>
    </div>

    <!-- MODAL DE PAGO QR (MÉTODOS DE PAGO) -->
    <div id="qr-payment-modal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-[#001144]/60 backdrop-blur-md transition-opacity" onclick="closeQrModal()"></div>
        
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md border border-white/20">
                    
                    <!-- Header -->
                    <div class="bg-[#001144] px-8 py-6 text-white flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-[#FF7A00]">
                                <i class="ph-fill ph-qr-code text-3xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black tracking-tight">Pago con QR</h3>
                                <p class="text-[10px] text-gray-300 font-bold uppercase tracking-widest">Inscripción Inmediata</p>
                            </div>
                        </div>
                        <button onclick="closeQrModal()" class="w-10 h-10 flex items-center justify-center text-gray-300 hover:text-white transition-colors rounded-full hover:bg-white/10 cursor-pointer">
                            <i class="ph-bold ph-x text-xl"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-8 text-center space-y-6">
                        @if(isset($platformSettings['home_pay_qr_img']) && !empty($platformSettings['home_pay_qr_img']))
                            <div class="w-64 h-64 bg-gray-50 rounded-[2rem] border border-gray-100 flex items-center justify-center mx-auto overflow-hidden shadow-lg p-4 bg-white relative group">
                                <img src="{{ asset($platformSettings['home_pay_qr_img']) }}" class="w-full h-full object-contain">
                            </div>
                        @else
                            <!-- QR de demostración por defecto -->
                            <div class="w-64 h-64 bg-gray-50 rounded-[2rem] border border-gray-100 flex flex-col items-center justify-center mx-auto overflow-hidden shadow-lg p-6 bg-white">
                                <i class="ph-bold ph-qr-code text-8xl text-gray-200 mb-2"></i>
                                <span class="text-[10px] text-gray-400 font-bold uppercase">QR No Configurado</span>
                            </div>
                        @endif

                        <p class="text-gray-600 text-xs font-semibold leading-relaxed px-2 text-left">
                            {!! nl2br(e($platformSettings['home_pay_qr_desc'] ?? 'Escanea este código QR desde tu app bancaria favorita (Yape, Plin, o banca móvil) para realizar el pago de forma instantánea.')) !!}
                        </p>

                        <div class="pt-4 border-t border-gray-50 flex justify-end">
                            <button onclick="closeQrModal()" class="px-6 py-3 bg-[#001144] hover:bg-[#FF7A00] text-white font-black rounded-xl text-xs uppercase tracking-widest transition-all cursor-pointer">
                                Entendido
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openQrModal() {
            document.getElementById('qr-payment-modal').classList.remove('hidden');
        }
        function closeQrModal() {
            document.getElementById('qr-payment-modal').classList.add('hidden');
        }
    </script>

</x-layouts.guest>