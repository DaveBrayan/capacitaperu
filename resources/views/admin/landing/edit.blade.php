<x-layouts.admin>
    <x-slot name="title">Editar Landing Page - CapacitaPerú</x-slot>
    <x-slot name="header">Editar Landing Page</x-slot>

    <!-- INCLUIR SWEETALERT2 PARA QUE CORRA EL CONFIRM -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="max-w-5xl mx-auto fade-in-up">
        
        @if(session('success'))
            <div class="mb-8 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-2xl flex items-center gap-3 shadow-sm animate-bounce">
                <i class="ph-bold ph-check-circle text-emerald-500 text-2xl"></i>
                <p class="text-emerald-800 font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <!-- NAVEGACIÓN PREMIUM (TABS EN LA PARTE SUPERIOR) -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 mb-8 overflow-hidden">
            <div class="flex overflow-x-auto no-scrollbar p-2">
                <button type="button" onclick="goToStep(1)" id="step-tab-1" 
                        class="step-tab-btn flex-1 py-5 px-6 text-center rounded-[2rem] transition-all duration-500 flex items-center justify-center gap-3 whitespace-nowrap min-w-[160px] group">
                    <div class="step-num w-8 h-8 rounded-xl bg-gray-50 text-gray-400 group-hover:bg-[#FF7A00] group-hover:text-white flex items-center justify-center shrink-0 transition-all font-black text-[10px]">01</div>
                    <span class="step-label font-black text-[10px] uppercase tracking-widest text-gray-400 group-hover:text-[#001144]">Header y Footer</span>
                </button>
                
                <button type="button" onclick="goToStep(2)" id="step-tab-2" 
                        class="step-tab-btn flex-1 py-5 px-6 text-center rounded-[2rem] transition-all duration-500 flex items-center justify-center gap-3 whitespace-nowrap min-w-[160px] group">
                    <div class="step-num w-8 h-8 rounded-xl bg-gray-50 text-gray-400 group-hover:bg-[#FF7A00] group-hover:text-white flex items-center justify-center shrink-0 transition-all font-black text-[10px]">02</div>
                    <span class="step-label font-black text-[10px] uppercase tracking-widest text-gray-400 group-hover:text-[#001144]">Inicio</span>
                </button>

                <button type="button" onclick="goToStep(3)" id="step-tab-3" 
                        class="step-tab-btn flex-1 py-5 px-6 text-center rounded-[2rem] transition-all duration-500 flex items-center justify-center gap-3 whitespace-nowrap min-w-[160px] group">
                    <div class="step-num w-8 h-8 rounded-xl bg-gray-50 text-gray-400 group-hover:bg-[#FF7A00] group-hover:text-white flex items-center justify-center shrink-0 transition-all font-black text-[10px]">03</div>
                    <span class="step-label font-black text-[10px] uppercase tracking-widest text-gray-400 group-hover:text-[#001144]">Cursos</span>
                </button>

                <button type="button" onclick="goToStep(4)" id="step-tab-4" 
                        class="step-tab-btn flex-1 py-5 px-6 text-center rounded-[2rem] transition-all duration-500 flex items-center justify-center gap-3 whitespace-nowrap min-w-[160px] group">
                    <div class="step-num w-8 h-8 rounded-xl bg-gray-50 text-gray-400 group-hover:bg-[#FF7A00] group-hover:text-white flex items-center justify-center shrink-0 transition-all font-black text-[10px]">04</div>
                    <span class="step-label font-black text-[10px] uppercase tracking-widest text-gray-400 group-hover:text-[#001144]">Diplomados</span>
                </button>

                <button type="button" onclick="goToStep(5)" id="step-tab-5" 
                        class="step-tab-btn flex-1 py-5 px-6 text-center rounded-[2rem] transition-all duration-500 flex items-center justify-center gap-3 whitespace-nowrap min-w-[160px] group">
                    <div class="step-num w-8 h-8 rounded-xl bg-gray-50 text-gray-400 group-hover:bg-[#FF7A00] group-hover:text-white flex items-center justify-center shrink-0 transition-all font-black text-[10px]">05</div>
                    <span class="step-label font-black text-[10px] uppercase tracking-widest text-gray-400 group-hover:text-[#001144]">Nosotros</span>
                </button>

                <button type="button" onclick="goToStep(6)" id="step-tab-6" 
                        class="step-tab-btn flex-1 py-5 px-6 text-center rounded-[2rem] transition-all duration-500 flex items-center justify-center gap-3 whitespace-nowrap min-w-[160px] group">
                    <div class="step-num w-8 h-8 rounded-xl bg-gray-50 text-gray-400 group-hover:bg-[#FF7A00] group-hover:text-white flex items-center justify-center shrink-0 transition-all font-black text-[10px]">06</div>
                    <span class="step-label font-black text-[10px] uppercase tracking-widest text-gray-400 group-hover:text-[#001144]">Contactos</span>
                </button>
            </div>
        </div>

        <!-- CONTENEDOR PRINCIPAL DE FORMULARIOS -->
        <div class="w-full">
                <form id="landing-editor-form" action="{{ route('admin.landing.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <!-- PASO 1: HEADER & FOOTER -->
                    <div id="step-content-1" class="step-content bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl shadow-blue-900/5 border border-gray-100 space-y-10">
                        <div class="border-b border-gray-50 pb-6">
                            <h3 class="text-2xl font-black text-[#001144] tracking-tight">Header (Navegación) y Footer</h3>
                            <p class="text-gray-400 text-sm font-medium">Configura el menú superior y el pie de página de la web pública</p>
                        </div>

                        <!-- Sub-sección: Menú Superior -->
                        <div class="space-y-6">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em]">Menú Superior (Navbar)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2 col-span-1 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Logotipo (Principal Color)</label>
                                    <div class="flex flex-col sm:flex-row gap-4 items-center bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                        <img id="preview-app_logo" src="{{ isset($settings['app_logo']) ? asset($settings['app_logo']) : '' }}" class="h-10 object-contain max-w-[200px] {{ isset($settings['app_logo']) ? '' : 'hidden' }}">
                                        <input type="file" name="app_logo" onchange="previewImage(this, 'preview-app_logo')" class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-orange-50 file:text-[#FF7A00] hover:file:bg-orange-100">
                                    </div>
                                </div>
                                <div class="space-y-2 col-span-1 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Logotipo Blanco (Modo Oscuro / Footer)</label>
                                    <div class="flex flex-col sm:flex-row gap-4 items-center bg-[#001144] p-4 rounded-2xl border border-white/5">
                                        <img id="preview-app_logo_white" src="{{ isset($settings['app_logo_white']) ? asset($settings['app_logo_white']) : '' }}" class="h-10 object-contain max-w-[200px] {{ isset($settings['app_logo_white']) ? '' : 'hidden' }}">
                                        <input type="file" name="app_logo_white" onchange="previewImage(this, 'preview-app_logo_white')" class="text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-white/10 file:text-white hover:file:bg-white/20">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Texto Botón de Ingreso</label>
                                    <input type="text" name="nav_btn_text" value="{{ $settings['nav_btn_text'] ?? 'Ingresar' }}" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144] text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Sub-sección: Footer General -->
                        <div class="space-y-6 pt-6 border-t border-gray-50">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em]">Pie de Página (Footer)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2 col-span-1 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Descripción Breve de Marca</label>
                                    <textarea rows="3" name="footer_desc" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144] text-sm resize-none">{{ $settings['footer_desc'] ?? 'Líderes en capacitación técnica y profesional. Potenciamos tu talento con certificación de alto impacto nacional y respaldo institucional.' }}</textarea>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Enlace Facebook</label>
                                    <input type="text" name="social_facebook" value="{{ $settings['social_facebook'] ?? '#' }}" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144] text-sm">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Enlace Instagram</label>
                                    <input type="text" name="social_instagram" value="{{ $settings['social_instagram'] ?? '#' }}" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144] text-sm">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Enlace LinkedIn</label>
                                    <input type="text" name="social_linkedin" value="{{ $settings['social_linkedin'] ?? '#' }}" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144] text-sm">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Enlace YouTube</label>
                                    <input type="text" name="social_youtube" value="{{ $settings['social_youtube'] ?? '#' }}" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144] text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Sub-sección: Footer Columnas de Navegación -->
                        <div class="space-y-6 pt-6 border-t border-gray-50">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em]">Columnas de Navegación del Footer</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Columna 1 -->
                                <div class="bg-gray-50 p-6 rounded-3xl space-y-4 border border-gray-100">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Columna 1: Formación</p>
                                    <input type="text" name="footer_col1_title" value="{{ $settings['footer_col1_title'] ?? 'Formación' }}" placeholder="Título Columna" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:border-[#FF7A00] outline-none text-xs font-bold text-[#001144]">
                                    
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="text" name="footer_col1_l1_text" value="{{ $settings['footer_col1_l1_text'] ?? 'Explorar Cursos' }}" placeholder="Texto Enlace 1" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:border-[#FF7A00] outline-none text-xs font-bold text-[#001144]">
                                        <input type="text" name="footer_col1_l1_url" value="{{ $settings['footer_col1_l1_url'] ?? '/catalogo' }}" placeholder="Link 1 (ej: /catalogo)" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:border-[#FF7A00] outline-none text-xs font-bold text-[#001144]">
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="text" name="footer_col1_l2_text" value="{{ $settings['footer_col1_l2_text'] ?? 'Especializaciones' }}" placeholder="Texto Enlace 2" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:border-[#FF7A00] outline-none text-xs font-bold text-[#001144]">
                                        <input type="text" name="footer_col1_l2_url" value="{{ $settings['footer_col1_l2_url'] ?? '/catalogo' }}" placeholder="Link 2 (ej: /catalogo)" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:border-[#FF7A00] outline-none text-xs font-bold text-[#001144]">
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="text" name="footer_col1_l3_text" value="{{ $settings['footer_col1_l3_text'] ?? 'Para Empresas' }}" placeholder="Texto Enlace 3" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:border-[#FF7A00] outline-none text-xs font-bold text-[#001144]">
                                        <input type="text" name="footer_col1_l3_url" value="{{ $settings['footer_col1_l3_url'] ?? '/contacto' }}" placeholder="Link 3 (ej: /contacto)" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:border-[#FF7A00] outline-none text-xs font-bold text-[#001144]">
                                    </div>
                                </div>
                                <!-- Columna 2 -->
                                <div class="bg-gray-50 p-6 rounded-3xl space-y-4 border border-gray-100">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Columna 2: Recursos</p>
                                    <input type="text" name="footer_col2_title" value="{{ $settings['footer_col2_title'] ?? 'Recursos' }}" placeholder="Título Columna" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:border-[#FF7A00] outline-none text-xs font-bold text-[#001144]">
                                    
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="text" name="footer_col2_l1_text" value="{{ $settings['footer_col2_l1_text'] ?? 'Nuestra Historia' }}" placeholder="Texto Enlace 1" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:border-[#FF7A00] outline-none text-xs font-bold text-[#001144]">
                                        <input type="text" name="footer_col2_l1_url" value="{{ $settings['footer_col2_l1_url'] ?? '/nosotros' }}" placeholder="Link 1 (ej: /nosotros)" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:border-[#FF7A00] outline-none text-xs font-bold text-[#001144]">
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="text" name="footer_col2_l2_text" value="{{ $settings['footer_col2_l2_text'] ?? 'Verificar Certificado' }}" placeholder="Texto Enlace 2" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:border-[#FF7A00] outline-none text-xs font-bold text-[#001144]">
                                        <input type="text" name="footer_col2_l2_url" value="{{ $settings['footer_col2_l2_url'] ?? '/certificado' }}" placeholder="Link 2 (ej: /certificado)" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:border-[#FF7A00] outline-none text-xs font-bold text-[#001144]">
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="text" name="footer_col2_l3_text" value="{{ $settings['footer_col2_l3_text'] ?? 'Preguntas Frecuentes' }}" placeholder="Texto Enlace 3" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:border-[#FF7A00] outline-none text-xs font-bold text-[#001144]">
                                        <input type="text" name="footer_col2_l3_url" value="{{ $settings['footer_col2_l3_url'] ?? '#' }}" placeholder="Link 3 (ej: #)" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:border-[#FF7A00] outline-none text-xs font-bold text-[#001144]">
                                    </div>
                                </div>
                                <!-- Columna 3 (Atención) -->
                                <div class="bg-gray-50 p-6 rounded-3xl col-span-1 md:col-span-2 space-y-4 border border-gray-100">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Columna 3: Datos de Contacto y Atención</p>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="space-y-1">
                                            <span class="text-[9px] font-black text-gray-400 uppercase ml-1">Título Sección</span>
                                            <input type="text" name="footer_col3_title" value="{{ $settings['footer_col3_title'] ?? 'Atención' }}" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:border-[#FF7A00] outline-none text-xs font-bold text-[#001144]">
                                        </div>
                                        <div class="space-y-1">
                                            <span class="text-[9px] font-black text-gray-400 uppercase ml-1">Email Atención</span>
                                            <input type="email" name="footer_col3_email" value="{{ $settings['footer_col3_email'] ?? 'capacitaperu08@gmail.com' }}" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:border-[#FF7A00] outline-none text-xs font-bold text-[#001144]">
                                        </div>
                                        <div class="space-y-1">
                                            <span class="text-[9px] font-black text-gray-400 uppercase ml-1">WhatsApp Soporte</span>
                                            <input type="text" name="footer_col3_phone" value="{{ $settings['footer_col3_phone'] ?? '+51 972 539 712' }}" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 focus:border-[#FF7A00] outline-none text-xs font-bold text-[#001144]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sub-sección: Derechos y Copyright -->
                        <div class="space-y-6 pt-6 border-t border-gray-50">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em]">Derechos de Autor</h4>
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Copyright Texto</label>
                                    <input type="text" name="footer_copyright" value="{{ $settings['footer_copyright'] ?? '© 2026 CAPACITAPERU. Centro de Formación y Actualización Profesional. Todos los derechos reservados.' }}" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144] text-sm">
                                </div>
                                <div class="bg-orange-50 border-l-4 border-[#FF7A00] p-4 rounded-xl flex items-start gap-3">
                                    <i class="ph-bold ph-info text-[#FF7A00] text-xl mt-0.5"></i>
                                    <div>
                                        <h5 class="text-[#FF7A00] font-black text-xs uppercase tracking-wider mb-1">Nota del Editor:</h5>
                                        <p class="text-orange-950 font-medium text-xs">La firma de autoría en el pie de página ("Desarrollado por Deivid Chipana") se mantiene estática e inalterable en el código por políticas de propiedad intelectual.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PASO 2: HOME PAGE -->
                    <div id="step-content-2" class="step-content hidden bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl shadow-blue-900/5 border border-gray-100 space-y-10">
                        <div class="border-b border-gray-50 pb-6">
                            <h3 class="text-2xl font-black text-[#001144] tracking-tight">Página de Inicio (Home)</h3>
                            <p class="text-gray-400 text-sm font-medium">Configura textos, imágenes y secciones clave de la Home pública</p>
                        </div>

                        <!-- Sección Hero -->
                        <div class="space-y-6">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] border-l-4 border-[#FF7A00] pl-3">Sección Hero</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Imagen de Fondo Hero (Unsplash / Local)</label>
                                    <div class="flex flex-col gap-3">
                                        <img id="preview-home_hero_bg" src="{{ isset($settings['home_hero_bg']) ? asset($settings['home_hero_bg']) : '' }}" class="h-20 w-auto object-cover rounded-xl border border-gray-100 max-w-[200px] {{ isset($settings['home_hero_bg']) ? '' : 'hidden' }}">
                                        <input type="file" name="home_hero_bg" onchange="previewImage(this, 'preview-home_hero_bg')" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-orange-50 file:text-[#FF7A00] hover:file:bg-orange-100">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Imagen de Apoyo Derecha Hero</label>
                                    <div class="flex flex-col gap-3">
                                        <img id="preview-home_hero_right_img" src="{{ isset($settings['home_hero_right_img']) ? asset($settings['home_hero_right_img']) : '' }}" class="h-20 w-auto object-cover rounded-xl border border-gray-100 max-w-[200px] {{ isset($settings['home_hero_right_img']) ? '' : 'hidden' }}">
                                        <input type="file" name="home_hero_right_img" onchange="previewImage(this, 'preview-home_hero_right_img')" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-orange-50 file:text-[#FF7A00] hover:file:bg-orange-100">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Mini-Etiqueta Hero</label>
                                    <input type="text" name="home_hero_tag" value="{{ $settings['home_hero_tag'] ?? 'Plataforma Educativa' }}" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144] text-sm">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Título Principal (HTML H1)</label>
                                    <input type="text" name="home_hero_title" value="{{ $settings['home_hero_title'] ?? 'Bienvenidos a <br> <span class="text-[#FF7A00]">CAPACITAPERU</span>' }}" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144] text-sm">
                                </div>
                                <div class="space-y-2 col-span-1 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Descripción del Hero</label>
                                    <textarea rows="3" name="home_hero_desc" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144] text-sm resize-none">{{ $settings['home_hero_desc'] ?? 'Tu espacio creado para responder a las nuevas demandas del mercado laboral. Ofrecemos capacitación especializada y accesible para profesionales, técnicos y estudiantes, respaldados por certificación reconocida a nivel nacional.' }}</textarea>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Botón Principal - Texto</label>
                                    <input type="text" name="home_hero_btn1_text" value="{{ $settings['home_hero_btn1_text'] ?? 'Ver Programas Disponibles' }}" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144] text-sm">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Botón Secundario - Texto</label>
                                    <input type="text" name="home_hero_btn2_text" value="{{ $settings['home_hero_btn2_text'] ?? 'Validar Certificado' }}" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144] text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Badges Flotantes -->
                        <div class="space-y-6 pt-6 border-t border-gray-50">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] border-l-4 border-[#FF7A00] pl-3">Badges Flotantes (Hero)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Badge 1: Título</label>
                                    <input type="text" name="home_hero_badge1_title" value="{{ $settings['home_hero_badge1_title'] ?? 'Certificación' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Badge 1: Subtítulo</label>
                                    <input type="text" name="home_hero_badge1_desc" value="{{ $settings['home_hero_badge1_desc'] ?? 'Oficial e Inmediata' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Badge 2: Título</label>
                                    <input type="text" name="home_hero_badge2_title" value="{{ $settings['home_hero_badge2_title'] ?? 'Asesoría Activa' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Badge 2: Subtítulo</label>
                                    <input type="text" name="home_hero_badge2_desc" value="{{ $settings['home_hero_badge2_desc'] ?? 'Respuesta Inmediata' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Badge 3: Texto de Modalidad</label>
                                    <input type="text" name="home_hero_badge3_text" value="{{ $settings['home_hero_badge3_text'] ?? '100% Virtual' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                            </div>
                        </div>

                        <!-- Sección Nosotros (Home) -->
                        <div class="space-y-6 pt-6 border-t border-gray-50">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] border-l-4 border-[#FF7A00] pl-3">Nosotros (Bloque de Resumen)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Imagen de Sección</label>
                                    <div class="flex flex-col gap-3">
                                        <img id="preview-home_about_img" src="{{ isset($settings['home_about_img']) ? asset($settings['home_about_img']) : '' }}" class="h-20 w-auto object-cover rounded-xl border border-gray-100 max-w-[200px] {{ isset($settings['home_about_img']) ? '' : 'hidden' }}">
                                        <input type="file" name="home_about_img" onchange="previewImage(this, 'preview-home_about_img')" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-orange-50 file:text-[#FF7A00]">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Etiqueta Sobre Nosotros</label>
                                    <input type="text" name="home_about_tag" value="{{ $settings['home_about_tag'] ?? 'Sobre Nosotros' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Título de Sección</label>
                                    <input type="text" name="home_about_title" value="{{ $settings['home_about_title'] ?? 'Fortaleciendo el Futuro <br> Profesional de la Región' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2 col-span-1 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cita del Fundador (Quote)</label>
                                    <input type="text" name="home_about_quote" value="{{ $settings['home_about_quote'] ?? '"En CAPACITAPERU, nos comprometemos en fortalecer tus competencias e impulsamos tu crecimiento profesional, preparándote para afrontar con éxito los retos actuales y futuros."' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2 col-span-1 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Descripción Sobre Nosotros</label>
                                    <textarea rows="3" name="home_about_desc" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144] text-sm resize-none">{{ $settings['home_about_desc'] ?? 'Somos un centro de formación y actualización profesional creado para responder a las nuevas demandas del mercado laboral. Ofrecemos capacitación especializada y accesible para profesionales, técnicos, estudiantes y trabajadores de diversos sectores, respaldados por certificación reconocida a nivel nacional.' }}</textarea>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Título del Bloque Flotante</label>
                                    <input type="text" name="home_about_badge_title" value="{{ $settings['home_about_badge_title'] ?? 'Nuestra Misión' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Texto del Bloque Flotante</label>
                                    <input type="text" name="home_about_badge_desc" value="{{ $settings['home_about_badge_desc'] ?? 'Fortalecer tus competencias e impulsar tu crecimiento profesional para los retos del futuro.' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                            </div>
                        </div>

                        <!-- Cifras Estadísticas -->
                        <div class="space-y-6 pt-6 border-t border-gray-50">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] border-l-4 border-[#FF7A00] pl-3">Cifras Estadísticas</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 p-6 rounded-3xl border border-gray-100">
                                <div class="space-y-1">
                                    <span class="text-[8px] font-black text-gray-400 uppercase">Stat 1 (Valor)</span>
                                    <input type="text" name="home_about_stat1_val" value="{{ $settings['home_about_stat1_val'] ?? '+10,000' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <span class="text-[8px] font-black text-gray-400 uppercase block mt-1">Stat 1 (Etiqueta)</span>
                                    <input type="text" name="home_about_stat1_lbl" value="{{ $settings['home_about_stat1_lbl'] ?? 'Estudiantes' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[8px] font-black text-gray-400 uppercase">Stat 2 (Valor)</span>
                                    <input type="text" name="home_about_stat2_val" value="{{ $settings['home_about_stat2_val'] ?? '+8' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <span class="text-[8px] font-black text-gray-400 uppercase block mt-1">Stat 2 (Etiqueta)</span>
                                    <input type="text" name="home_about_stat2_lbl" value="{{ $settings['home_about_stat2_lbl'] ?? 'Años de Exp.' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[8px] font-black text-gray-400 uppercase">Stat 3 (Valor)</span>
                                    <input type="text" name="home_about_stat3_val" value="{{ $settings['home_about_stat3_val'] ?? '+150' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <span class="text-[8px] font-black text-gray-400 uppercase block mt-1">Stat 3 (Etiqueta)</span>
                                    <input type="text" name="home_about_stat3_lbl" value="{{ $settings['home_about_stat3_lbl'] ?? 'Cursos Dictados' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[8px] font-black text-gray-400 uppercase">Stat 4 (Valor)</span>
                                    <input type="text" name="home_about_stat4_val" value="{{ $settings['home_about_stat4_val'] ?? '99%' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <span class="text-[8px] font-black text-gray-400 uppercase block mt-1">Stat 4 (Etiqueta)</span>
                                    <input type="text" name="home_about_stat4_lbl" value="{{ $settings['home_about_stat4_lbl'] ?? 'Satisfacción' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                </div>
                            </div>
                        </div>

                        <!-- Bento Grid & Categorías -->
                        <div class="space-y-6 pt-6 border-t border-gray-50">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] border-l-4 border-[#FF7A00] pl-3">Bento Grid & Categorías</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Bento 1: Título</label>
                                    <input type="text" name="home_bento1_title" value="{{ $settings['home_bento1_title'] ?? 'Eleva tu Carrera Profesional' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Bento 1: Descripción</label>
                                    <input type="text" name="home_bento1_desc" value="{{ $settings['home_bento1_desc'] ?? 'Diseñado para profesionales que buscan entrar al ecosistema industrial y tecnológico de Perú. No necesitas experiencia previa, solo las ganas de aprender.' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Bento 2: Título</label>
                                    <input type="text" name="home_bento2_title" value="{{ $settings['home_bento2_title'] ?? 'Tu Ritmo' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Bento 2: Subtítulo</label>
                                    <input type="text" name="home_bento2_sub" value="{{ $settings['home_bento2_sub'] ?? 'Acceso vitalicio 24/7' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Bento 3: Título</label>
                                    <input type="text" name="home_bento3_title" value="{{ $settings['home_bento3_title'] ?? 'QR Seguro' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Bento 3: Subtítulo</label>
                                    <input type="text" name="home_bento3_sub" value="{{ $settings['home_bento3_sub'] ?? 'Verificación inmediata' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <!-- Categorías Header -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Áreas: Subtítulo</label>
                                    <input type="text" name="home_cat_tag" value="{{ $settings['home_cat_tag'] ?? 'Explora Áreas' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Áreas: Título</label>
                                    <input type="text" name="home_cat_title" value="{{ $settings['home_cat_title'] ?? 'Especialízate en las <br> Mejores Categorías' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2 col-span-1 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Áreas: Descripción Corta</label>
                                    <input type="text" name="home_cat_desc" value="{{ $settings['home_cat_desc'] ?? 'Descubre rutas de aprendizaje diseñadas para las demandas reales de la industria técnica peruana.' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                            </div>
                        </div>

                        <!-- Métodos de Pago & CTA Final -->
                        <div class="space-y-6 pt-6 border-t border-gray-50">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] border-l-4 border-[#FF7A00] pl-3">Métodos de Pago & Llamado a la Acción (CTA)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Pagos: Subtítulo</label>
                                    <input type="text" name="home_pay_tag" value="{{ $settings['home_pay_tag'] ?? 'Inscripción Simple' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Pagos: Título</label>
                                    <input type="text" name="home_pay_title" value="{{ $settings['home_pay_title'] ?? 'Métodos de Pago Seguros' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <!-- Pago Card 1 -->
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 space-y-2">
                                    <span class="text-[8px] font-black text-gray-400 uppercase">Pago 1: Título</span>
                                    <input type="text" name="home_pay_c1_title" value="{{ $settings['home_pay_c1_title'] ?? 'Transferencia QR (Simple)' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <span class="text-[8px] font-black text-gray-400 uppercase block mt-1">Pago 1: Desc</span>
                                    <textarea rows="2" name="home_pay_c1_desc" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144] resize-none">{{ $settings['home_pay_c1_desc'] ?? 'Paga de forma inmediata escaneando nuestro código QR desde cualquier aplicación bancaria.' }}</textarea>
                                </div>
                                <!-- Pago Card 2 -->
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 space-y-2">
                                    <span class="text-[8px] font-black text-gray-400 uppercase">Pago 2: Título</span>
                                    <input type="text" name="home_pay_c2_title" value="{{ $settings['home_pay_c2_title'] ?? 'Pling' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <span class="text-[8px] font-black text-gray-400 uppercase block mt-1">Pago 2: Desc</span>
                                    <textarea rows="2" name="home_pay_c2_desc" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144] resize-none">{{ $settings['home_pay_c2_desc'] ?? 'Realiza tu pago de forma rápida y segura a través de tu billetera Pling.' }}</textarea>
                                </div>
                                <!-- Pago Card 3 -->
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 col-span-1 md:col-span-2 space-y-2">
                                    <span class="text-[8px] font-black text-gray-400 uppercase">Pago 3: Título</span>
                                    <input type="text" name="home_pay_c3_title" value="{{ $settings['home_pay_c3_title'] ?? 'Tarjetas de Crédito/Débito' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <span class="text-[8px] font-black text-gray-400 uppercase block mt-1">Pago 3: Desc</span>
                                    <textarea rows="2" name="home_pay_c3_desc" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144] resize-none">{{ $settings['home_pay_c3_desc'] ?? 'Aceptamos todas las tarjetas nacionales e internacionales con procesamiento seguro.' }}</textarea>
                                </div>
                                <!-- CTA Final Home -->
                                <div class="space-y-2 col-span-1 md:col-span-2 pt-4 border-t border-gray-100">
                                    <span class="text-[9px] font-black text-[#FF7A00] uppercase block">CTA Final - Título</span>
                                    <input type="text" name="home_cta_title" value="{{ $settings['home_cta_title'] ?? 'Comienza hoy tu camino hacia el éxito profesional' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2 col-span-1 md:col-span-2">
                                    <span class="text-[9px] font-black text-[#FF7A00] uppercase block">CTA Final - Desc</span>
                                    <input type="text" name="home_cta_desc" value="{{ $settings['home_cta_desc'] ?? 'Aprovecha las oportunidades laborales con las certificaciones que las empresas más buscan.' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <span class="text-[9px] font-black text-[#FF7A00] uppercase block">CTA Final - Botón Texto</span>
                                    <input type="text" name="home_cta_btn_text" value="{{ $settings['home_cta_btn_text'] ?? 'Contactar por WhatsApp' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <span class="text-[9px] font-black text-[#FF7A00] uppercase block">CTA Final - Número WhatsApp (Sin símbolos)</span>
                                    <input type="text" name="home_cta_whatsapp" value="{{ $settings['home_cta_whatsapp'] ?? '51972539712' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PASO 3: PÁGINA CURSOS -->
                    <div id="step-content-3" class="step-content hidden bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl shadow-blue-900/5 border border-gray-100 space-y-10">
                        <div class="border-b border-gray-50 pb-6">
                            <h3 class="text-2xl font-black text-[#001144] tracking-tight">Página de Cursos</h3>
                            <p class="text-gray-400 text-sm font-medium">Gestiona la cabecera y banners del listado de Cursos</p>
                        </div>

                        <div class="space-y-6">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] border-l-4 border-[#FF7A00] pl-3">Cabecera del Catálogo</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Mini-Etiqueta Cabecera</label>
                                    <input type="text" name="courses_header_tag" value="{{ $settings['courses_header_tag'] ?? 'Nuestra Oferta Académica' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Título de Cabecera</label>
                                    <input type="text" name="courses_header_title" value="{{ $settings['courses_header_title'] ?? 'Especialízate <br><span class="text-[#FF7A00]">Sin Límites</span>' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2 col-span-1 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Descripción</label>
                                    <textarea rows="2" name="courses_header_desc" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144] text-sm resize-none">{{ $settings['courses_header_desc'] ?? 'Programas diseñados para responder a las demandas reales del mercado laboral, con certificación nacional.' }}</textarea>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cifra 1 (Valor)</label>
                                    <input type="text" name="courses_stat1_val" value="{{ $settings['courses_stat1_val'] ?? '+150' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cifra 1 (Etiqueta)</label>
                                    <input type="text" name="courses_stat1_lbl" value="{{ $settings['courses_stat1_lbl'] ?? 'Cursos' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cifra 2 (Valor)</label>
                                    <input type="text" name="courses_stat2_val" value="{{ $settings['courses_stat2_val'] ?? '98%' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cifra 2 (Etiqueta)</label>
                                    <input type="text" name="courses_stat2_lbl" value="{{ $settings['courses_stat2_lbl'] ?? 'Éxito' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Valor Catálogo -->
                        <div class="space-y-6 pt-6 border-t border-gray-50">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] border-l-4 border-[#FF7A00] pl-3">Banners de Valor (Inferior Catálogo)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 space-y-2">
                                    <span class="text-[8px] font-black text-gray-400 uppercase">Banner 1: Título</span>
                                    <input type="text" name="courses_val1_title" value="{{ $settings['courses_val1_title'] ?? 'Certificación Nacional' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <span class="text-[8px] font-black text-gray-400 uppercase block mt-1">Banner 1: Desc</span>
                                    <input type="text" name="courses_val1_desc" value="{{ $settings['courses_val1_desc'] ?? 'Validez Profesional' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                </div>
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 space-y-2">
                                    <span class="text-[8px] font-black text-gray-400 uppercase">Banner 2: Título</span>
                                    <input type="text" name="courses_val2_title" value="{{ $settings['courses_val2_title'] ?? 'Asesores Expertos' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <span class="text-[8px] font-black text-gray-400 uppercase block mt-1">Banner 2: Desc</span>
                                    <input type="text" name="courses_val2_desc" value="{{ $settings['courses_val2_desc'] ?? 'Acompañamiento Real' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                </div>
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 space-y-2">
                                    <span class="text-[8px] font-black text-gray-400 uppercase">Banner 3: Título</span>
                                    <input type="text" name="courses_val3_title" value="{{ $settings['courses_val3_title'] ?? 'Acceso de por Vida' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <span class="text-[8px] font-black text-gray-400 uppercase block mt-1">Banner 3: Desc</span>
                                    <input type="text" name="courses_val3_desc" value="{{ $settings['courses_val3_desc'] ?? 'A tu propio ritmo' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PASO 4: PÁGINA DIPLOMADOS -->
                    <div id="step-content-4" class="step-content hidden bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl shadow-blue-900/5 border border-gray-100 space-y-10">
                        <div class="border-b border-gray-50 pb-6">
                            <h3 class="text-2xl font-black text-[#001144] tracking-tight">Página de Diplomados</h3>
                            <p class="text-gray-400 text-sm font-medium">Gestiona la cabecera y estadísticas del catálogo de Diplomados</p>
                        </div>

                        <div class="space-y-6">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] border-l-4 border-[#FF7A00] pl-3">Cabecera del Catálogo de Diplomados</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Mini-Etiqueta Cabecera</label>
                                    <input type="text" name="diplomas_header_tag" value="{{ $settings['diplomas_header_tag'] ?? 'Nuestros Diplomados' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Título de Cabecera</label>
                                    <input type="text" name="diplomas_header_title" value="{{ $settings['diplomas_header_title'] ?? 'Diplomados <br><span class="text-[#FF7A00]">Profesionales</span>' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2 col-span-1 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Descripción</label>
                                    <textarea rows="2" name="diplomas_header_desc" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144] text-sm resize-none">{{ $settings['diplomas_header_desc'] ?? 'Programas avanzados de especialización con certificación oficial y validez nacional.' }}</textarea>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cifra 1 (Valor)</label>
                                    <input type="text" name="diplomas_stat1_val" value="{{ $settings['diplomas_stat1_val'] ?? '+150' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cifra 1 (Etiqueta)</label>
                                    <input type="text" name="diplomas_stat1_lbl" value="{{ $settings['diplomas_stat1_lbl'] ?? 'Cursos' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cifra 2 (Valor)</label>
                                    <input type="text" name="diplomas_stat2_val" value="{{ $settings['diplomas_stat2_val'] ?? '98%' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cifra 2 (Etiqueta)</label>
                                    <input type="text" name="diplomas_stat2_lbl" value="{{ $settings['diplomas_stat2_lbl'] ?? 'Éxito' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PASO 5: PÁGINA NOSOTROS -->
                    <div id="step-content-5" class="step-content hidden bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl shadow-blue-900/5 border border-gray-100 space-y-10">
                        <div class="border-b border-gray-50 pb-6">
                            <h3 class="text-2xl font-black text-[#001144] tracking-tight">Página Nosotros</h3>
                            <p class="text-gray-400 text-sm font-medium">Edita los textos corporativos, historia, valores y equipo de nosotros</p>
                        </div>

                        <!-- Nosotros Hero -->
                        <div class="space-y-6">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] border-l-4 border-[#FF7A00] pl-3">Hero de Nosotros</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Fondo de Cabecera (Imagen)</label>
                                    <div class="flex flex-col gap-3">
                                        <img id="preview-about_hero_bg" src="{{ isset($settings['about_hero_bg']) ? asset($settings['about_hero_bg']) : '' }}" class="h-20 w-auto object-cover rounded-xl border border-gray-100 max-w-[200px] {{ isset($settings['about_hero_bg']) ? '' : 'hidden' }}">
                                        <input type="file" name="about_hero_bg" onchange="previewImage(this, 'preview-about_hero_bg')" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-orange-50 file:text-[#FF7A00]">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Etiqueta Identidad</label>
                                    <input type="text" name="about_hero_tag" value="{{ $settings['about_hero_tag'] ?? 'Nuestra Identidad' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2 col-span-1 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Título de Cabecera (HTML / Gradient)</label>
                                    <input type="text" name="about_hero_title" value="{{ $settings['about_hero_title'] ?? 'Impulsando el <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FF7A00] to-orange-300">Potencial Profesional</span>' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2 col-span-1 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Descripción Hero</label>
                                    <input type="text" name="about_hero_desc" value="{{ $settings['about_hero_desc'] ?? 'En CapacitaPerú, transformamos la educación técnica en una herramienta real de crecimiento y éxito laboral.' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                            </div>
                        </div>

                        <!-- Sección Historia -->
                        <div class="space-y-6 pt-6 border-t border-gray-50">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] border-l-4 border-[#FF7A00] pl-3">Nuestra Historia</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Imagen Histórica Principal</label>
                                    <div class="flex flex-col gap-3">
                                        <img id="preview-about_hist_img" src="{{ isset($settings['about_hist_img']) ? asset($settings['about_hist_img']) : '' }}" class="h-20 w-auto object-cover rounded-xl border border-gray-100 max-w-[200px] {{ isset($settings['about_hist_img']) ? '' : 'hidden' }}">
                                        <input type="file" name="about_hist_img" onchange="previewImage(this, 'preview-about_hist_img')" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-orange-50 file:text-[#FF7A00]">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Título de Historia</label>
                                    <input type="text" name="about_hist_title" value="{{ $settings['about_hist_title'] ?? 'Nuestra Historia' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2 col-span-1 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Párrafo de Historia 1</label>
                                    <textarea rows="3" name="about_hist_desc1" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144] text-sm resize-none">{{ $settings['about_hist_desc1'] ?? 'El Centro de Formación y Actualización Profesional – CapacitaPerú surge con el compromiso de responder a las nuevas demandas del mercado laboral, ofreciendo capacitación especializada y accesible para profesionales, técnicos, estudiantes y trabajadores de distintos sectores.' }}</textarea>
                                </div>
                                <div class="space-y-2 col-span-1 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Párrafo de Historia 2</label>
                                    <textarea rows="3" name="about_hist_desc2" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144] text-sm resize-none">{{ $settings['about_hist_desc2'] ?? 'Respaldados con certificación reconocida a nivel nacional. Nuestro propósito es acompañar a cada participante en el fortalecimiento de sus competencias y en el impulso de su crecimiento profesional.' }}</textarea>
                                </div>
                                <!-- Caja Flotante de Historia -->
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 space-y-2">
                                    <span class="text-[8px] font-black text-gray-400 uppercase">Caja Flotante: Cifra</span>
                                    <input type="text" name="about_hist_badge_title" value="{{ $settings['about_hist_badge_title'] ?? '100%' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <span class="text-[8px] font-black text-gray-400 uppercase block mt-1">Caja Flotante: Texto</span>
                                    <input type="text" name="about_hist_badge_desc" value="{{ $settings['about_hist_badge_desc'] ?? 'Compromiso Real' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                </div>
                                <!-- Cifras Rápidas Historia -->
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 space-y-2">
                                    <span class="text-[8px] font-black text-gray-400 uppercase">Cifra 1 (Valor y Desc)</span>
                                    <input type="text" name="about_hist_stat1_val" value="{{ $settings['about_hist_stat1_val'] ?? '+5,000' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144] mb-1">
                                    <input type="text" name="about_hist_stat1_lbl" value="{{ $settings['about_hist_stat1_lbl'] ?? 'Estudiantes Formados' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    
                                    <span class="text-[8px] font-black text-gray-400 uppercase block mt-2">Cifra 2 (Valor y Desc)</span>
                                    <input type="text" name="about_hist_stat2_val" value="{{ $settings['about_hist_stat2_val'] ?? '50+' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144] mb-1">
                                    <input type="text" name="about_hist_stat2_lbl" value="{{ $settings['about_hist_stat2_lbl'] ?? 'Cursos Activos' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                </div>
                            </div>
                        </div>

                        <!-- Misión y Visión -->
                        <div class="space-y-6 pt-6 border-t border-gray-50">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] border-l-4 border-[#FF7A00] pl-3">Misión y Visión</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Misión: Título</label>
                                    <input type="text" name="about_mis_title" value="{{ $settings['about_mis_title'] ?? 'Misión' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mt-2 ml-1">Misión: Contenido</label>
                                    <textarea rows="4" name="about_mis_desc" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144] text-xs resize-none">{{ $settings['about_mis_desc'] ?? '"Ofrecer programas de capacitación y actualización profesional accesibles y de alta calidad, enfocados en el desarrollo de habilidades prácticas y competencias clave para el éxito laboral. Buscamos ser un aliado estratégico para los profesionales que desean fortalecer su perfil y adaptarse a los cambios del mercado."' }}</textarea>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Visión: Título</label>
                                    <input type="text" name="about_vis_title" value="{{ $settings['about_vis_title'] ?? 'Visión' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mt-2 ml-1">Visión: Contenido</label>
                                    <textarea rows="4" name="about_vis_desc" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144] text-xs resize-none">{{ $settings['about_vis_desc'] ?? '"Consolidarnos como una empresa emergente líder en la formación profesional, reconocida por nuestra capacidad de transformar el potencial de los profesionales mediante una educación innovadora, flexible y accesible, que impulse su crecimiento en un mundo laboral en constante evolución."' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Qué nos hace diferentes -->
                        <div class="space-y-6 pt-6 border-t border-gray-50">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] border-l-4 border-[#FF7A00] pl-3">Diferenciadores principales</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2 col-span-1 md:col-span-3">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Título Sección Diferenciadores</label>
                                    <input type="text" name="about_dif_title" value="{{ $settings['about_dif_title'] ?? '¿Qué nos hace diferentes?' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 space-y-2">
                                    <span class="text-[8px] font-black text-gray-400 uppercase">Diferencia 1: Título</span>
                                    <input type="text" name="about_dif_c1_title" value="{{ $settings['about_dif_c1_title'] ?? 'Docentes Expertos' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <span class="text-[8px] font-black text-gray-400 uppercase block mt-1">Diferencia 1: Desc</span>
                                    <textarea rows="2" name="about_dif_c1_desc" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144] resize-none">{{ $settings['about_dif_c1_desc'] ?? 'Nuestros instructores no solo enseñan teoría, son profesionales en activo que comparten casos reales y experiencia de campo.' }}</textarea>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 space-y-2">
                                    <span class="text-[8px] font-black text-gray-400 uppercase">Diferencia 2: Título</span>
                                    <input type="text" name="about_dif_c2_title" value="{{ $settings['about_dif_c2_title'] ?? 'Certificación Nacional' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <span class="text-[8px] font-black text-gray-400 uppercase block mt-1">Diferencia 2: Desc</span>
                                    <textarea rows="2" name="about_dif_c2_desc" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144] resize-none">{{ $settings['about_dif_c2_desc'] ?? 'Emitimos certificados con respaldo institucional y validación mediante códigos QR, listos para tu perfil profesional.' }}</textarea>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 space-y-2">
                                    <span class="text-[8px] font-black text-gray-400 uppercase">Diferencia 3: Título</span>
                                    <input type="text" name="about_dif_c3_title" value="{{ $settings['about_dif_c3_title'] ?? 'Acceso 24/7' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <span class="text-[8px] font-black text-gray-400 uppercase block mt-1">Diferencia 3: Desc</span>
                                    <textarea rows="2" name="about_dif_c3_desc" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144] resize-none">{{ $settings['about_dif_c3_desc'] ?? 'Aprende a tu ritmo desde cualquier dispositivo. Nuestra plataforma está diseñada para adaptarse a tu estilo de vida.' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Equipo Directivo / Instructores -->
                        <div class="space-y-6 pt-6 border-t border-gray-50">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] border-l-4 border-[#FF7A00] pl-3">Equipo de Liderazgo (Docentes/Directivos)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Miembro 1 -->
                                <div class="bg-gray-50 p-4 rounded-3xl border border-gray-100 space-y-2">
                                    <span class="text-[9px] font-black text-[#001144] uppercase">Miembro 1</span>
                                    <input type="text" name="about_eq_m1_name" value="{{ $settings['about_eq_m1_name'] ?? 'Ing. Deivid Chipana' }}" placeholder="Nombre" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <input type="text" name="about_eq_m1_role" value="{{ $settings['about_eq_m1_role'] ?? 'Fundador & CEO' }}" placeholder="Rol/Cargo" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <input type="text" name="about_eq_m1_desc" value="{{ $settings['about_eq_m1_desc'] ?? 'Impulsando la visión tecnológica de CapacitaPerú.' }}" placeholder="Desc" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <div class="flex items-center gap-3 mt-1">
                                        <img id="preview-about_eq_m1_img" src="{{ isset($settings['about_eq_m1_img']) ? asset($settings['about_eq_m1_img']) : '' }}" class="h-10 w-10 object-cover rounded-full border border-gray-200 {{ isset($settings['about_eq_m1_img']) ? '' : 'hidden' }}">
                                        <input type="file" name="about_eq_m1_img" onchange="previewImage(this, 'preview-about_eq_m1_img')" class="w-full text-[10px] text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-full file:border-0 file:bg-orange-50 file:text-[#FF7A00]">
                                    </div>
                                </div>
                                <!-- Miembro 2 -->
                                <div class="bg-gray-50 p-4 rounded-3xl border border-gray-100 space-y-2">
                                    <span class="text-[9px] font-black text-[#001144] uppercase">Miembro 2</span>
                                    <input type="text" name="about_eq_m2_name" value="{{ $settings['about_eq_m2_name'] ?? 'Ing. Ana Sola' }}" placeholder="Nombre" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <input type="text" name="about_eq_m2_role" value="{{ $settings['about_eq_m2_role'] ?? 'Dirección Académica' }}" placeholder="Rol/Cargo" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <input type="text" name="about_eq_m2_desc" value="{{ $settings['about_eq_m2_desc'] ?? 'Experticia en calidad y formación técnica superior.' }}" placeholder="Desc" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <div class="flex items-center gap-3 mt-1">
                                        <img id="preview-about_eq_m2_img" src="{{ isset($settings['about_eq_m2_img']) ? asset($settings['about_eq_m2_img']) : '' }}" class="h-10 w-10 object-cover rounded-full border border-gray-200 {{ isset($settings['about_eq_m2_img']) ? '' : 'hidden' }}">
                                        <input type="file" name="about_eq_m2_img" onchange="previewImage(this, 'preview-about_eq_m2_img')" class="w-full text-[10px] text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-full file:border-0 file:bg-orange-50 file:text-[#FF7A00]">
                                    </div>
                                </div>
                                <!-- Miembro 3 -->
                                <div class="bg-gray-50 p-4 rounded-3xl border border-gray-100 space-y-2">
                                    <span class="text-[9px] font-black text-[#001144] uppercase">Miembro 3</span>
                                    <input type="text" name="about_eq_m3_name" value="{{ $settings['about_eq_m3_name'] ?? 'Ing. Carlos V.' }}" placeholder="Nombre" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <input type="text" name="about_eq_m3_role" value="{{ $settings['about_eq_m3_role'] ?? 'Arquitectura Tecnológica' }}" placeholder="Rol/Cargo" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <input type="text" name="about_eq_m3_desc" value="{{ $settings['about_eq_m3_desc'] ?? 'Garantizando la mejor experiencia en nuestra plataforma.' }}" placeholder="Desc" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <div class="flex items-center gap-3 mt-1">
                                        <img id="preview-about_eq_m3_img" src="{{ isset($settings['about_eq_m3_img']) ? asset($settings['about_eq_m3_img']) : '' }}" class="h-10 w-10 object-cover rounded-full border border-gray-200 {{ isset($settings['about_eq_m3_img']) ? '' : 'hidden' }}">
                                        <input type="file" name="about_eq_m3_img" onchange="previewImage(this, 'preview-about_eq_m3_img')" class="w-full text-[10px] text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-full file:border-0 file:bg-orange-50 file:text-[#FF7A00]">
                                    </div>
                                </div>
                                <!-- Miembro 4 -->
                                <div class="bg-gray-50 p-4 rounded-3xl border border-gray-100 space-y-2">
                                    <span class="text-[9px] font-black text-[#001144] uppercase">Miembro 4</span>
                                    <input type="text" name="about_eq_m4_name" value="{{ $settings['about_eq_m4_name'] ?? 'Lic. Maria L.' }}" placeholder="Nombre" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <input type="text" name="about_eq_m4_role" value="{{ $settings['about_eq_m4_role'] ?? 'Éxito Estudiantil' }}" placeholder="Rol/Cargo" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <input type="text" name="about_eq_m4_desc" value="{{ $settings['about_eq_m4_desc'] ?? 'Acompañando a nuestros alumnos en cada paso.' }}" placeholder="Desc" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                    <div class="flex items-center gap-3 mt-1">
                                        <img id="preview-about_eq_m4_img" src="{{ isset($settings['about_eq_m4_img']) ? asset($settings['about_eq_m4_img']) : '' }}" class="h-10 w-10 object-cover rounded-full border border-gray-200 {{ isset($settings['about_eq_m4_img']) ? '' : 'hidden' }}">
                                        <input type="file" name="about_eq_m4_img" onchange="previewImage(this, 'preview-about_eq_m4_img')" class="w-full text-[10px] text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-full file:border-0 file:bg-orange-50 file:text-[#FF7A00]">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Nosotros -->
                        <div class="space-y-6 pt-6 border-t border-gray-50">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] border-l-4 border-[#FF7A00] pl-3">CTA Final Nosotros</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Título CTA</label>
                                    <input type="text" name="about_cta_title" value="{{ $settings['about_cta_title'] ?? '¿Listo para transformar tu futuro?' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Descripción CTA</label>
                                    <input type="text" name="about_cta_desc" value="{{ $settings['about_cta_desc'] ?? 'Únete a los miles de profesionales que ya están impulsando su carrera con CapacitaPerú.' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Botón 1: Texto</label>
                                    <input type="text" name="about_cta_btn1" value="{{ $settings['about_cta_btn1'] ?? 'Ver Programas' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Botón 2: Texto</label>
                                    <input type="text" name="about_cta_btn2" value="{{ $settings['about_cta_btn2'] ?? 'Contactar' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PASO 6: PÁGINA CONTACTO -->
                    <div id="step-content-6" class="step-content hidden bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl shadow-blue-900/5 border border-gray-100 space-y-10">
                        <div class="border-b border-gray-50 pb-6">
                            <h3 class="text-2xl font-black text-[#001144] tracking-tight">Página de Contacto</h3>
                            <p class="text-gray-400 text-sm font-medium">Configura los canales y títulos del formulario de contacto</p>
                        </div>

                        <!-- Hero Contacto -->
                        <div class="space-y-6">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] border-l-4 border-[#FF7A00] pl-3">Hero de Contacto</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Imagen de Fondo Hero</label>
                                    <div class="flex flex-col gap-3">
                                        <img id="preview-contact_hero_bg" src="{{ isset($settings['contact_hero_bg']) ? asset($settings['contact_hero_bg']) : '' }}" class="h-20 w-auto object-cover rounded-xl border border-gray-100 max-w-[200px] {{ isset($settings['contact_hero_bg']) ? '' : 'hidden' }}">
                                        <input type="file" name="contact_hero_bg" onchange="previewImage(this, 'preview-contact_hero_bg')" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-orange-50 file:text-[#FF7A00]">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Etiqueta Hablemos</label>
                                    <input type="text" name="contact_hero_tag" value="{{ $settings['contact_hero_tag'] ?? 'Hablemos' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2 col-span-1 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Título de Cabecera (HTML)</label>
                                    <input type="text" name="contact_hero_title" value="{{ $settings['contact_hero_title'] ?? '¿Cómo podemos <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FF7A00] to-orange-300">Ayudarte Hoy?</span>' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2 col-span-1 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Descripción</label>
                                    <input type="text" name="contact_hero_desc" value="{{ $settings['contact_hero_desc'] ?? 'Nuestro equipo de asesores está listo para brindarte la información que necesitas para dar el siguiente paso en tu carrera.' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                            </div>
                        </div>

                        <!-- Canales de Contacto Directo -->
                        <div class="space-y-6 pt-6 border-t border-gray-50">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] border-l-4 border-[#FF7A00] pl-3">Canales de Contacto</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2 col-span-1 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Título de Sección Conéctate</label>
                                    <input type="text" name="contact_info_title" value="{{ $settings['contact_info_title'] ?? 'Conéctate con Nosotros' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2 col-span-1 md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Descripción Conéctate</label>
                                    <input type="text" name="contact_info_desc" value="{{ $settings['contact_info_desc'] ?? 'Ya sea que tengas una duda sobre un curso, necesites capacitación corporativa o simplemente quieras saludarnos, estamos a un clic de distancia.' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <!-- WhatsApp Card -->
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 space-y-2">
                                    <span class="text-[8px] font-black text-gray-400 uppercase">Canal 1: WhatsApp Oficial</span>
                                    <input type="text" name="contact_info_c1_title" value="{{ $settings['contact_info_c1_title'] ?? 'WhatsApp Oficial' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144] mb-1">
                                    <input type="text" name="contact_info_c1_val" value="{{ $settings['contact_info_c1_val'] ?? '+51 972 539 712' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144] mb-1">
                                    <input type="text" name="contact_info_c1_lnk" value="{{ $settings['contact_info_c1_lnk'] ?? '51972539712' }}" placeholder="Número Link (ej. 51972539712)" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144] mb-1">
                                    <input type="text" name="contact_info_c1_desc" value="{{ $settings['contact_info_c1_desc'] ?? 'Respuesta Inmediata' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                </div>
                                <!-- Email Card -->
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 space-y-2">
                                    <span class="text-[8px] font-black text-gray-400 uppercase">Canal 2: Correo</span>
                                    <input type="text" name="contact_info_c2_title" value="{{ $settings['contact_info_c2_title'] ?? 'Correo Electrónico' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144] mb-1">
                                    <input type="text" name="contact_info_c2_val" value="{{ $settings['contact_info_c2_val'] ?? 'capacitaperu08@gmail.com' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144] mb-1">
                                    <input type="text" name="contact_info_c2_desc" value="{{ $settings['contact_info_c2_desc'] ?? 'Soporte Académico' }}" class="w-full px-3 py-2 rounded-xl bg-white border border-gray-200 text-xs font-bold text-[#001144]">
                                </div>
                            </div>
                        </div>

                        <!-- Banner de Beneficios Formulario -->
                        <div class="space-y-6 pt-6 border-t border-gray-50">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] border-l-4 border-[#FF7A00] pl-3">Banner lateral "¿Por qué contactarnos?"</h4>
                            <div class="grid grid-cols-1 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Título Banner</label>
                                    <input type="text" name="contact_blue_title" value="{{ $settings['contact_blue_title'] ?? '¿Por qué contactarnos?' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Viñetas/Lista de Beneficios (Un elemento por línea)</label>
                                    <textarea rows="4" name="contact_blue_bullets" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144] text-xs resize-none">{{ $settings['contact_blue_bullets'] ?? "Asesoría personalizada sobre tu plan de estudios.\nCotizaciones a medida para empresas y grupos.\nResolución de dudas técnicas y acceso a plataforma." }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario de Correo -->
                        <div class="space-y-6 pt-6 border-t border-gray-50">
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] border-l-4 border-[#FF7A00] pl-3">Formulario de Envío</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Título Formulario</label>
                                    <input type="text" name="contact_form_title" value="{{ $settings['contact_form_title'] ?? 'Envíanos un Mensaje' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Texto del Botón</label>
                                    <input type="text" name="contact_form_btn" value="{{ $settings['contact_form_btn'] ?? 'Enviar Mensaje Ahora' }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 font-bold text-xs text-[#001144]">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BOTÓN FLOTANTE DE GUARDAR CAMBIOS -->
                    <div class="flex justify-end pt-4">
                        <button type="submit" onclick="submitLandingForm(event)" class="bg-[#FF7A00] text-white px-12 py-5 rounded-[2rem] font-black shadow-2xl shadow-[#FF7A00]/30 hover:scale-105 active:scale-95 transition-all flex items-center gap-3 group">
                            <i id="save-icon" class="ph-bold ph-floppy-disk text-2xl group-hover:rotate-12 transition-transform"></i>
                            <span id="save-text">Guardar Cambios</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    <!-- SCRIPTS -->
    <script>
        function previewImage(input, previewId) {
            const file = input.files[0];
            if (file) {
                const preview = document.getElementById(previewId);
                if (preview) {
                    preview.src = URL.createObjectURL(file);
                    preview.classList.remove('hidden');
                }
            }
        }

        let currentStep = 1;

        document.addEventListener('DOMContentLoaded', () => {
            // Ir al paso 1 por defecto
            goToStep(1);
        });

        function goToStep(stepNumber) {
            currentStep = stepNumber;

            // Ocultar todos los contenidos de pasos
            document.querySelectorAll('.step-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Mostrar el paso activo
            document.getElementById(`step-content-${stepNumber}`).classList.remove('hidden');

            // Actualizar botones de los pasos superiores
            document.querySelectorAll('.step-tab-btn').forEach(btn => {
                btn.classList.remove('bg-[#001144]', 'shadow-xl', 'shadow-blue-900/10');
                const numDiv = btn.querySelector('.step-num');
                if (numDiv) {
                    numDiv.classList.remove('bg-[#FF7A00]', 'text-white', 'shadow-lg');
                    numDiv.classList.add('bg-gray-50', 'text-gray-400');
                }
                const labelSpan = btn.querySelector('.step-label');
                if (labelSpan) {
                    labelSpan.classList.remove('text-white');
                    labelSpan.classList.add('text-gray-400');
                }
            });

            const activeBtn = document.getElementById(`step-tab-${stepNumber}`);
            if (activeBtn) {
                activeBtn.classList.add('bg-[#001144]', 'shadow-xl', 'shadow-blue-900/10');
                const numDiv = activeBtn.querySelector('.step-num');
                if (numDiv) {
                    numDiv.classList.remove('bg-gray-50', 'text-gray-400');
                    numDiv.gradientClass = "";
                    numDiv.classList.add('bg-[#FF7A00]', 'text-white', 'shadow-lg');
                }
                const labelSpan = activeBtn.querySelector('.step-label');
                if (labelSpan) {
                    labelSpan.classList.remove('text-gray-400');
                    labelSpan.classList.add('text-white');
                }
            }
        }

        function submitLandingForm(event) {
            event.preventDefault();
            
            Swal.fire({
                title: '¿Guardar Cambios de la Landing Page?',
                text: 'Esto actualizará los textos e imágenes en la web pública de CapacitaPerú de forma inmediata.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#FF7A00',
                cancelButtonColor: '#EF4444',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'rounded-[2.5rem]',
                    confirmButton: 'rounded-xl font-black uppercase tracking-widest text-[10px] px-8 py-4',
                    cancelButton: 'rounded-xl font-black uppercase tracking-widest text-[10px] px-8 py-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const icon = document.getElementById('save-icon');
                    const text = document.getElementById('save-text');
                    
                    icon.className = "ph-bold ph-spinner animate-spin text-2xl";
                    text.innerText = "Guardando...";
                    icon.parentElement.disabled = true;
                    icon.parentElement.classList.add('opacity-75', 'cursor-not-allowed');

                    document.getElementById('landing-editor-form').submit();
                }
            });
        }
    </script>
</x-layouts.admin>
