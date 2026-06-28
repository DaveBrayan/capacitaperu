<x-layouts.guest>
    <!-- LIBRERÍAS (Phosphor Icons) -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- HERO SECTION (Contacto) -->
    <div class="relative bg-[#001144] py-32 overflow-hidden">
        <!-- Decoración de fondo -->
        <div class="absolute inset-0">
            <img src="{{ asset($platformSettings['contact_hero_bg'] ?? 'https://images.unsplash.com/photo-1516387933901-82614480c2ad?q=80&w=2000&auto=format&fit=crop') }}" class="w-full h-full object-cover opacity-20 mix-blend-overlay">
            <div class="absolute inset-0 bg-gradient-to-b from-[#001144]/50 via-[#001144] to-[#001144]"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center animate-fade-in-up">
            <span class="text-[#FF7A00] font-black tracking-[0.4em] text-[10px] uppercase mb-6 block">{{ $platformSettings['contact_hero_tag'] ?? 'Hablemos' }}</span>
            <h1 class="text-4xl md:text-7xl font-black text-white mb-8 leading-tight">
                {!! $platformSettings['contact_hero_title'] ?? '¿Cómo podemos <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FF7A00] to-orange-300">Ayudarte Hoy?</span>' !!}
            </h1>
            <p class="text-xl text-gray-400 max-w-3xl mx-auto font-medium leading-relaxed opacity-90">
                {{ $platformSettings['contact_hero_desc'] ?? 'Nuestro equipo de asesores está listo para brindarte la información que necesitas para dar el siguiente paso en tu carrera.' }}
            </p>
        </div>
    </div>

    <!-- SECCIÓN PRINCIPAL: CONTACTO -->
    <div class="bg-white py-24 relative -mt-16 rounded-t-[3rem] lg:rounded-t-[5rem] z-20 shadow-[0_-20px_50px_rgba(0,0,0,0.05)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20">
                
                <!-- COLUMNA IZQUIERDA: Info y Valor -->
                <div class="animate-fade-in-left">
                    <h2 class="text-4xl font-black text-[#001144] mb-8">{{ $platformSettings['contact_info_title'] ?? 'Conéctate con Nosotros' }}</h2>
                    <p class="text-gray-500 text-lg font-medium leading-relaxed mb-12">
                        {{ $platformSettings['contact_info_desc'] ?? 'Ya sea que tengas una duda sobre un curso, necesites capacitación corporativa o simplemente quieras saludarnos, estamos a un clic de distancia.' }}
                    </p>

                    <!-- Tarjetas de Contacto Directo -->
                    <div class="space-y-6">
                        <!-- WhatsApp -->
                        <a href="https://wa.me/{{ $platformSettings['contact_info_c1_lnk'] ?? '51972539712' }}" target="_blank" class="flex items-center gap-6 p-8 bg-gray-50 rounded-[2rem] border border-gray-100 hover:border-[#25D366] hover:bg-white transition-all group shadow-sm">
                            <div class="w-16 h-16 bg-[#25D366] rounded-2xl flex items-center justify-center text-white shadow-lg shadow-green-200 group-hover:scale-110 transition-transform">
                                <i class="ph-fill ph-whatsapp-logo text-3xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">{{ $platformSettings['contact_info_c1_title'] ?? 'WhatsApp Oficial' }}</p>
                                <p class="text-xl font-black text-[#001144]">{{ $platformSettings['contact_info_c1_val'] ?? '+51 972 539 712' }}</p>
                                <p class="text-xs text-[#25D366] font-bold mt-1 uppercase tracking-tighter">{{ $platformSettings['contact_info_c1_desc'] ?? 'Respuesta Inmediata' }}</p>
                            </div>
                        </a>

                        <!-- Email -->
                        <a href="mailto:{{ $platformSettings['contact_info_c2_val'] ?? 'capacitaperu08@gmail.com' }}" class="flex items-center gap-6 p-8 bg-gray-50 rounded-[2rem] border border-gray-100 hover:border-[#FF7A00] hover:bg-white transition-all group shadow-sm">
                            <div class="w-16 h-16 bg-[#FF7A00] rounded-2xl flex items-center justify-center text-white shadow-lg shadow-orange-200 group-hover:scale-110 transition-transform">
                                <i class="ph-bold ph-envelope text-3xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">{{ $platformSettings['contact_info_c2_title'] ?? 'Correo Electrónico' }}</p>
                                <p class="text-xl font-black text-[#001144]">{{ $platformSettings['contact_info_c2_val'] ?? 'capacitaperu08@gmail.com' }}</p>
                                <p class="text-xs text-[#FF7A00] font-bold mt-1 uppercase tracking-tighter">{{ $platformSettings['contact_info_c2_desc'] ?? 'Soporte Académico' }}</p>
                            </div>
                        </a>
                    </div>

                    <!-- Contenido de Valor -->
                    <div class="mt-16 p-10 bg-[#001144] rounded-[2.5rem] text-white overflow-hidden relative">
                        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-[#FF7A00] opacity-20 rounded-full blur-[40px]"></div>
                        <h4 class="text-2xl font-black mb-4 relative z-10">{{ $platformSettings['contact_blue_title'] ?? '¿Por qué contactarnos?' }}</h4>
                        @php
                            $bullets = explode("\n", $platformSettings['contact_blue_bullets'] ?? "Asesoría personalizada sobre tu plan de estudios.\nCotizaciones a medida para empresas y grupos.\nResolución de dudas técnicas y acceso a plataforma.");
                        @endphp
                        <ul class="space-y-4 relative z-10">
                            @foreach($bullets as $bullet)
                                @if(trim($bullet))
                                    <li class="flex items-center gap-4 text-sm font-medium text-white/80">
                                        <i class="ph-bold ph-check-circle text-[#FF7A00]"></i>
                                        {{ trim($bullet) }}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- COLUMNA DERECHA: Formulario -->
                <div class="animate-fade-in-right">
                    <div class="bg-white p-10 rounded-[3rem] shadow-[0_50px_100px_rgba(0,17,68,0.08)] border border-gray-50">
                        <h3 class="text-2xl font-black text-[#001144] mb-8">{{ $platformSettings['contact_form_title'] ?? 'Envíanos un Mensaje' }}</h3>
                        <form action="#" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] text-gray-400 font-black uppercase tracking-widest ml-4">Nombre Completo</label>
                                    <input type="text" placeholder="Ej. Juan Pérez" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-gray-50 focus:border-[#FF7A00] focus:bg-white outline-none transition-all font-bold text-gray-700">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] text-gray-400 font-black uppercase tracking-widest ml-4">Teléfono / WhatsApp</label>
                                    <input type="tel" placeholder="+51 ..." class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-gray-50 focus:border-[#FF7A00] focus:bg-white outline-none transition-all font-bold text-gray-700">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] text-gray-400 font-black uppercase tracking-widest ml-4">Correo Electrónico</label>
                                <input type="email" placeholder="tu@email.com" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-gray-50 focus:border-[#FF7A00] focus:bg-white outline-none transition-all font-bold text-gray-700">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] text-gray-400 font-black uppercase tracking-widest ml-4">Interés Académico</label>
                                <select class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-gray-50 focus:border-[#FF7A00] focus:bg-white outline-none transition-all font-bold text-gray-700 appearance-none">
                                    <option value="">Selecciona un área</option>
                                    <option value="ingenieria">Ingeniería y Proyectos</option>
                                    <option value="gestion">Gestión Pública</option>
                                    <option value="seguridad">Seguridad Industrial</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] text-gray-400 font-black uppercase tracking-widest ml-4">Tu Mensaje</label>
                                <textarea rows="4" placeholder="¿En qué podemos ayudarte?" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-gray-50 focus:border-[#FF7A00] focus:bg-white outline-none transition-all font-bold text-gray-700 resize-none"></textarea>
                            </div>
                            <button class="w-full py-5 bg-[#001144] text-white font-black rounded-2xl hover:bg-[#FF7A00] hover:-translate-y-1 transition-all shadow-xl shadow-blue-100 uppercase tracking-widest text-xs">
                                {{ $platformSettings['contact_form_btn'] ?? 'Enviar Mensaje Ahora' }}
                            </button>
                        </form>
                    </div>
                </div>

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