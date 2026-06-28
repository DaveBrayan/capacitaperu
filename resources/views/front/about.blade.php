<x-layouts.guest>
    <!-- LIBRERÍAS (Phosphor Icons) -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- HERO SECTION (Premium Identity) -->
    <div class="relative bg-[#001144] py-32 overflow-hidden">
        <!-- Decoración de fondo -->
        <div class="absolute inset-0">
            <img src="{{ asset($platformSettings['about_hero_bg'] ?? 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=2000&auto=format&fit=crop') }}" class="w-full h-full object-cover opacity-20 mix-blend-overlay">
            <div class="absolute inset-0 bg-gradient-to-b from-[#001144]/50 via-[#001144] to-[#001144]"></div>
        </div>
        
        <!-- Elementos Flotantes -->
        <div class="absolute top-20 right-10 w-[400px] h-[400px] bg-[#FF7A00] opacity-10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-10 left-10 w-[300px] h-[300px] bg-[#0033CC] opacity-20 rounded-full blur-[100px]"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center animate-fade-in-up">
            <span class="text-[#FF7A00] font-black tracking-[0.4em] text-[10px] uppercase mb-6 block">{{ $platformSettings['about_hero_tag'] ?? 'Nuestra Identidad' }}</span>
            <h1 class="text-4xl md:text-7xl font-black text-white mb-8 leading-tight">
                {!! $platformSettings['about_hero_title'] ?? 'Impulsando el <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FF7A00] to-orange-300">Potencial Profesional</span>' !!}
            </h1>
            <p class="text-xl text-gray-400 max-w-3xl mx-auto font-medium leading-relaxed opacity-90">
                {{ $platformSettings['about_hero_desc'] ?? 'En CapacitaPerú, transformamos la educación técnica en una herramienta real de crecimiento y éxito laboral.' }}
            </p>
        </div>
    </div>

    <!-- NUESTRA HISTORIA (Sección de Impacto) -->
    <div class="bg-white py-24 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                
                <!-- Imágenes Collage Premium -->
                <div class="relative animate-fade-in-left">
                    <div class="relative z-10 rounded-[3rem] overflow-hidden shadow-2xl border-8 border-white">
                        <img src="{{ asset($platformSettings['about_hist_img'] ?? 'https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=1000&auto=format&fit=crop') }}" class="w-full h-[500px] object-cover hover:scale-105 transition-transform duration-700">
                    </div>
                    <!-- Elemento flotante -->
                    <div class="absolute -bottom-10 -right-10 bg-[#001144] p-10 rounded-[2.5rem] shadow-2xl border border-white/10 hidden md:block">
                        <p class="text-5xl font-black text-[#FF7A00] mb-1">{{ $platformSettings['about_hist_badge_title'] ?? '100%' }}</p>
                        <p class="text-[10px] text-white font-black uppercase tracking-widest">{{ $platformSettings['about_hist_badge_desc'] ?? 'Compromiso Real' }}</p>
                    </div>
                </div>

                <!-- Texto de Historia -->
                <div class="animate-fade-in-right">
                    <div class="w-20 h-1 bg-[#FF7A00] mb-8"></div>
                    <h2 class="text-4xl font-black text-[#001144] mb-8 leading-tight">{{ $platformSettings['about_hist_title'] ?? 'Nuestra Historia' }}</h2>
                    <div class="space-y-6 text-gray-600 leading-relaxed font-medium text-lg">
                        <p>
                            {{ $platformSettings['about_hist_desc1'] ?? 'El Centro de Formación y Actualización Profesional – CapacitaPerú surge con el compromiso de responder a las nuevas demandas del mercado laboral, ofreciendo capacitación especializada y accesible para profesionales, técnicos, estudiantes y trabajadores de distintos sectores.' }}
                        </p>
                        <p>
                            {{ $platformSettings['about_hist_desc2'] ?? 'Respaldados con certificación reconocida a nivel nacional. Nuestro propósito es acompañar a cada participante en el fortalecimiento de sus competencias y en el impulso de su crecimiento profesional.' }}
                        </p>
                    </div>
                    
                    <!-- Stats Rápidos -->
                    <div class="mt-12 grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-3xl font-black text-[#001144]">{{ $platformSettings['about_hist_stat1_val'] ?? '+5,000' }}</p>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">{{ $platformSettings['about_hist_stat1_lbl'] ?? 'Estudiantes Formados' }}</p>
                        </div>
                        <div>
                            <p class="text-3xl font-black text-[#001144]">{{ $platformSettings['about_hist_stat2_val'] ?? '50+' }}</p>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">{{ $platformSettings['about_hist_stat2_lbl'] ?? 'Cursos Activos' }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- MISIÓN Y VISIÓN (Cards de Diseño) -->
    <div class="bg-gray-50 py-32 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- MISIÓN -->
                <div class="bg-white p-12 rounded-[3rem] shadow-xl border border-gray-100 hover:-translate-y-2 transition-all duration-500 group">
                    <div class="w-16 h-16 bg-[#001144] rounded-2xl flex items-center justify-center text-[#FF7A00] mb-8 group-hover:bg-[#FF7A00] group-hover:text-white transition-all shadow-lg">
                        <i class="ph-bold ph-target text-3xl"></i>
                    </div>
                    <h3 class="text-3xl font-black text-[#001144] mb-6">{{ $platformSettings['about_mis_title'] ?? 'Misión' }}</h3>
                    <p class="text-gray-500 font-medium leading-relaxed text-lg">
                        {{ $platformSettings['about_mis_desc'] ?? '"Ofrecer programas de capacitación y actualización profesional accesibles y de alta calidad, enfocados en el desarrollo de habilidades prácticas y competencias clave para el éxito laboral. Buscamos ser un aliado estratégico para los profesionales que desean fortalecer su perfil y adaptarse a los cambios del mercado."' }}
                    </p>
                </div>

                <!-- VISIÓN -->
                <div class="bg-[#001144] p-12 rounded-[3rem] shadow-xl border border-white/5 hover:-translate-y-2 transition-all duration-500 group">
                    <div class="w-16 h-16 bg-[#FF7A00] rounded-2xl flex items-center justify-center text-white mb-8 group-hover:scale-110 transition-all shadow-lg">
                        <i class="ph-bold ph-eye text-3xl"></i>
                    </div>
                    <h3 class="text-3xl font-black text-white mb-6">{{ $platformSettings['about_vis_title'] ?? 'Visión' }}</h3>
                    <p class="text-white/60 font-medium leading-relaxed text-lg">
                        {{ $platformSettings['about_vis_desc'] ?? '"Consolidarnos como una empresa emergente líder en la formación profesional, reconocida por nuestra capacidad de transformar el potencial de los profesionales mediante una educación innovadora, flexible y accesible, que impulse su crecimiento en un mundo laboral en constante evolución."' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENIDO DE VALOR: POR QUÉ CAPACITAPERÚ -->
    <div class="bg-white py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <span class="text-[#FF7A00] font-black tracking-[0.3em] text-[10px] uppercase mb-4 block">Excelencia Educativa</span>
                <h2 class="text-4xl font-black text-[#001144]">{{ $platformSettings['about_dif_title'] ?? '¿Qué nos hace diferentes?' }}</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Valor 1 -->
                <div class="p-10 bg-gray-50 rounded-[2.5rem] border border-gray-100 hover:bg-[#001144] group transition-all duration-500">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-[#FF7A00] mb-8 shadow-md group-hover:scale-110 transition-transform">
                        <i class="ph-bold ph-chalkboard-teacher text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-black text-[#001144] mb-4 group-hover:text-white transition-colors">{{ $platformSettings['about_dif_c1_title'] ?? 'Docentes Expertos' }}</h4>
                    <p class="text-gray-500 text-sm leading-relaxed font-medium group-hover:text-white/60 transition-colors">
                        {{ $platformSettings['about_dif_c1_desc'] ?? 'Nuestros instructores no solo enseñan teoría, son profesionales en activo que comparten casos reales y experiencia de campo.' }}
                    </p>
                </div>

                <!-- Valor 2 -->
                <div class="p-10 bg-gray-50 rounded-[2.5rem] border border-gray-100 hover:bg-[#FF7A00] group transition-all duration-500">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-[#001144] mb-8 shadow-md group-hover:scale-110 transition-transform">
                        <i class="ph-bold ph-certificate text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-black text-[#001144] mb-4 group-hover:text-white transition-colors">{{ $platformSettings['about_dif_c2_title'] ?? 'Certificación Nacional' }}</h4>
                    <p class="text-gray-500 text-sm leading-relaxed font-medium group-hover:text-white/60 transition-colors">
                        {{ $platformSettings['about_dif_c2_desc'] ?? 'Emitimos certificados con respaldo institucional y validación mediante códigos QR, listos para tu perfil profesional.' }}
                    </p>
                </div>

                <!-- Valor 3 -->
                <div class="p-10 bg-gray-50 rounded-[2.5rem] border border-gray-100 hover:bg-[#0033CC] group transition-all duration-500">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-[#001144] mb-8 shadow-md group-hover:scale-110 transition-transform">
                        <i class="ph-bold ph-device-mobile text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-black text-[#001144] mb-4 group-hover:text-white transition-colors">{{ $platformSettings['about_dif_c3_title'] ?? 'Acceso 24/7' }}</h4>
                    <p class="text-gray-500 text-sm leading-relaxed font-medium group-hover:text-white/60 transition-colors">
                        {{ $platformSettings['about_dif_c3_desc'] ?? 'Aprende a tu ritmo desde cualquier dispositivo. Nuestra plataforma está diseñada para adaptarse a tu estilo de vida.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- EQUIPO LIDERAZGO -->
    <div class="bg-gray-50 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <span class="text-[#FF7A00] font-black tracking-[0.3em] text-[10px] uppercase mb-4 block">Nuestro Equipo</span>
                <h2 class="text-4xl font-black text-[#001144]">Mentes Detrás del Cambio</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Miembro 1 -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 text-center hover:shadow-2xl hover:-translate-y-2 transition-all group">
                    <div class="w-32 h-32 mx-auto rounded-[2rem] overflow-hidden mb-6 border-4 border-gray-50 group-hover:border-[#FF7A00] transition-colors shadow-lg">
                        <img src="{{ isset($platformSettings['about_eq_m1_img']) ? asset($platformSettings['about_eq_m1_img']) : 'https://ui-avatars.com/api/?name=' . urlencode($platformSettings['about_eq_m1_name'] ?? 'Deivid Chipana') . '&background=001144&color=fff&size=256' }}" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-black text-lg text-[#001144]">{{ $platformSettings['about_eq_m1_name'] ?? 'Ing. Deivid Chipana' }}</h3>
                    <p class="text-[10px] text-[#FF7A00] font-black uppercase tracking-[0.2em] mb-4">{{ $platformSettings['about_eq_m1_role'] ?? 'Fundador & CEO' }}</p>
                    <p class="text-gray-500 text-xs font-bold leading-relaxed">{{ $platformSettings['about_eq_m1_desc'] ?? 'Impulsando la visión tecnológica de CapacitaPerú.' }}</p>
                </div>

                <!-- Miembro 2 -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 text-center hover:shadow-2xl hover:-translate-y-2 transition-all group">
                    <div class="w-32 h-32 mx-auto rounded-[2rem] overflow-hidden mb-6 border-4 border-gray-50 group-hover:border-[#FF7A00] transition-colors shadow-lg">
                        <img src="{{ isset($platformSettings['about_eq_m2_img']) ? asset($platformSettings['about_eq_m2_img']) : 'https://ui-avatars.com/api/?name=' . urlencode($platformSettings['about_eq_m2_name'] ?? 'Ana Sola') . '&background=FF7A00&color=fff&size=256' }}" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-black text-lg text-[#001144]">{{ $platformSettings['about_eq_m2_name'] ?? 'Ing. Ana Sola' }}</h3>
                    <p class="text-[10px] text-[#FF7A00] font-black uppercase tracking-[0.2em] mb-4">{{ $platformSettings['about_eq_m2_role'] ?? 'Dirección Académica' }}</p>
                    <p class="text-gray-500 text-xs font-bold leading-relaxed">{{ $platformSettings['about_eq_m2_desc'] ?? 'Experticia en calidad y formación técnica superior.' }}</p>
                </div>

                <!-- Miembro 3 -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 text-center hover:shadow-2xl hover:-translate-y-2 transition-all group">
                    <div class="w-32 h-32 mx-auto rounded-[2rem] overflow-hidden mb-6 border-4 border-gray-50 group-hover:border-[#FF7A00] transition-colors shadow-lg">
                        <img src="{{ isset($platformSettings['about_eq_m3_img']) ? asset($platformSettings['about_eq_m3_img']) : 'https://ui-avatars.com/api/?name=' . urlencode($platformSettings['about_eq_m3_name'] ?? 'Carlos V') . '&background=0033CC&color=fff&size=256' }}" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-black text-lg text-[#001144]">{{ $platformSettings['about_eq_m3_name'] ?? 'Ing. Carlos V.' }}</h3>
                    <p class="text-[10px] text-[#FF7A00] font-black uppercase tracking-[0.2em] mb-4">{{ $platformSettings['about_eq_m3_role'] ?? 'Arquitectura Tecnológica' }}</p>
                    <p class="text-gray-500 text-xs font-bold leading-relaxed">{{ $platformSettings['about_eq_m3_desc'] ?? 'Garantizando la mejor experiencia en nuestra plataforma.' }}</p>
                </div>

                <!-- Miembro 4 -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 text-center hover:shadow-2xl hover:-translate-y-2 transition-all group">
                    <div class="w-32 h-32 mx-auto rounded-[2rem] overflow-hidden mb-6 border-4 border-gray-50 group-hover:border-[#FF7A00] transition-colors shadow-lg">
                        <img src="{{ isset($platformSettings['about_eq_m4_img']) ? asset($platformSettings['about_eq_m4_img']) : 'https://ui-avatars.com/api/?name=' . urlencode($platformSettings['about_eq_m4_name'] ?? 'Maria L') . '&background=FF7A00&color=fff&size=256' }}" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-black text-lg text-[#001144]">{{ $platformSettings['about_eq_m4_name'] ?? 'Lic. Maria L.' }}</h3>
                    <p class="text-[10px] text-[#FF7A00] font-black uppercase tracking-[0.2em] mb-4">{{ $platformSettings['about_eq_m4_role'] ?? 'Éxito Estudiantil' }}</p>
                    <p class="text-gray-500 text-xs font-bold leading-relaxed">{{ $platformSettings['about_eq_m4_desc'] ?? 'Acompañando a nuestros alumnos en cada paso.' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA FINAL (Sección de Motivación) -->
    <div class="bg-[#001144] py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-[#FF7A00]/10 to-transparent"></div>
        <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
            <h2 class="text-3xl md:text-5xl font-black text-white mb-8">{{ $platformSettings['about_cta_title'] ?? '¿Listo para transformar tu futuro?' }}</h2>
            <p class="text-white/60 text-lg mb-10 font-medium">{{ $platformSettings['about_cta_desc'] ?? 'Únete a los miles de profesionales que ya están impulsando su carrera con CapacitaPerú.' }}</p>
            <div class="flex flex-col sm:flex-row justify-center gap-6">
                <a href="/catalogo" class="bg-[#FF7A00] text-white px-12 py-5 rounded-2xl font-black shadow-xl hover:shadow-[#FF7A00]/30 hover:-translate-y-1 transition-all uppercase tracking-widest text-xs">{{ $platformSettings['about_cta_btn1'] ?? 'Ver Programas' }}</a>
                <a href="/contacto" class="bg-white/10 text-white border border-white/20 px-12 py-5 rounded-2xl font-black backdrop-blur-md hover:bg-white/20 transition-all uppercase tracking-widest text-xs">{{ $platformSettings['about_cta_btn2'] ?? 'Contactar' }}</a>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 1s ease-out forwards; }
        .animate-fade-in-left { animation: fadeInLeft 1s ease-out forwards; }
        .animate-fade-in-right { animation: fadeInRight 1s ease-out forwards; }
    </style>
</x-layouts.guest>