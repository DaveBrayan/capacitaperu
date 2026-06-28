<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? ($platformSettings['app_name'] ?? 'CAPACITAPERU') . ' - Formación y Actualización Profesional' }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #FF7A00;
            --primary-dark: #994700;
            --secondary: #0033CC;
            --secondary-light: #284ade;
            --tertiary: #A0A0A0;
            --background: #fff8f5;
            --surface: #ffffff;
            --text-main: #27180d;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Montserrat', sans-serif;
        }

        .text-gradient {
            background: linear-gradient(to right, var(--secondary), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #FF9500 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 12px 24px -10px rgba(255, 122, 0, 0.5);
        }

        .nav-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0, 51, 204, 0.05);
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="bg-[#fff8f5] text-[#27180d] antialiased selection:bg-[#FF7A00] selection:text-white">

    <nav class="fixed w-full z-50 nav-glass transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                <!-- Logotipo -->
                <a href="{{ route('home') }}" class="flex items-center group">
                    <img src="{{ asset($platformSettings['app_logo'] ?? 'img/logo.png') }}" alt="{{ $platformSettings['app_name'] ?? 'CapacitaPerú' }}" class="h-16 w-auto group-hover:scale-105 transition-transform">
                </a>

                <!-- Menú Central (Desktop) -->
                <div class="hidden md:flex items-center space-x-2">
                    <a href="{{ route('home') }}"
                        class="px-4 py-2 rounded-xl text-gray-700 hover:text-[#0033CC] hover:bg-[#0033CC]/5 font-bold transition-all flex items-center gap-2 group">
                        <i class="ph-bold ph-house group-hover:scale-110 transition-transform"></i>
                        Inicio
                    </a>
                    <!-- Dropdown Programas -->
                    <div class="relative group">
                        <button class="px-4 py-2 rounded-xl text-gray-700 hover:text-[#0033CC] hover:bg-[#0033CC]/5 font-bold transition-all flex items-center gap-2">
                            <i class="ph-bold ph-graduation-cap group-hover:scale-110 transition-transform"></i>
                            Programas
                            <i class="ph-bold ph-caret-down text-xs"></i>
                        </button>
                        <!-- Menú Desplegable -->
                        <div class="absolute left-0 mt-2 w-48 bg-white border border-gray-100 rounded-2xl shadow-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                            <a href="{{ route('catalog') }}" class="block px-4 py-3 text-sm font-bold text-gray-700 hover:bg-[#0033CC]/5 hover:text-[#0033CC] transition-colors rounded-xl mx-2">
                                <i class="ph-bold ph-book-open mr-2"></i> Cursos
                            </a>
                            <a href="{{ route('diplomados') }}" class="block px-4 py-3 text-sm font-bold text-gray-700 hover:bg-[#0033CC]/5 hover:text-[#0033CC] transition-colors rounded-xl mx-2">
                                <i class="ph-bold ph-scroll mr-2"></i> Diplomados
                            </a>
                        </div>
                    </div>
                    <a href="{{ route('about') }}"
                        class="px-4 py-2 rounded-xl text-gray-700 hover:text-[#0033CC] hover:bg-[#0033CC]/5 font-bold transition-all flex items-center gap-2 group">
                        <i class="ph-bold ph-users group-hover:scale-110 transition-transform"></i>
                        Nosotros
                    </a>
                    <a href="{{ route('contact') }}"
                        class="px-4 py-2 rounded-xl text-gray-700 hover:text-[#0033CC] hover:bg-[#0033CC]/5 font-bold transition-all flex items-center gap-2 group">
                        <i class="ph-bold ph-paper-plane-tilt group-hover:scale-110 transition-transform"></i>
                        Contacto
                    </a>
                </div>

                <!-- Botones Dinámicos (Desktop) -->
                <div class="hidden md:flex items-center gap-4">
                    <!-- Carrito Icono -->
                    <a href="{{ route('cart') }}" class="relative w-12 h-12 bg-white border border-gray-100 hover:border-[#FF7A00]/30 rounded-2xl flex items-center justify-center text-[#001144] hover:text-[#FF7A00] transition-all shadow-md group shrink-0">
                        <i class="ph-bold ph-shopping-cart text-xl group-hover:scale-110 transition-transform"></i>
                        <span id="cart-badge" class="absolute -top-1.5 -right-1.5 bg-[#FF7A00] text-white text-[9px] font-black w-5 h-5 rounded-full flex items-center justify-center border-2 border-white shadow-md hidden">0</span>
                    </a>

                    @auth('web')
                        <a href="{{ route('admin.dashboard') }}"
                            class="bg-[#0033CC] text-white px-6 py-3 rounded-2xl font-black shadow-xl shadow-[#0033CC]/20 hover:bg-[#FF7A00] transition-all flex items-center gap-2">
                            <i class="ph-bold ph-layout"></i>
                            Panel Admin
                        </a>
                    @elseauth('student')
                        <a href="{{ route('student.dashboard') }}"
                            class="bg-[#0033CC] text-white px-6 py-3 rounded-2xl font-black shadow-xl shadow-[#0033CC]/20 hover:bg-[#FF7A00] transition-all flex items-center gap-2">
                            <i class="ph-bold ph-student"></i>
                            Mi Aula
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-[#001144] text-white px-8 py-3 rounded-2xl font-black shadow-2xl shadow-[#001144]/20 hover:bg-[#FF7A00] transition-all transform hover:-translate-y-1 flex items-center gap-2">
                            <i class="ph-bold ph-user"></i>
                            {{ $platformSettings['nav_btn_text'] ?? 'Ingresar' }}
                        </a>
                    @endauth
                </div>

                <!-- Botón Móvil -->
                <div class="md:hidden flex items-center gap-4">
                    <!-- Carrito Icono Móvil -->
                    <a href="{{ route('cart') }}" class="relative text-[#001144] hover:text-[#FF7A00] text-2xl p-1.5">
                        <i class="ph-bold ph-shopping-cart"></i>
                        <span id="cart-badge-mobile" class="absolute -top-1.5 -right-1.5 bg-[#FF7A00] text-white text-[9px] font-black w-5 h-5 rounded-full flex items-center justify-center border-2 border-white shadow-md hidden">0</span>
                    </a>

                    @auth('student')
                        <a href="{{ route('student.dashboard') }}" class="text-[#0AD1C8] text-2xl"><i
                                class="ph-bold ph-user-circle"></i></a>
                    @endauth
                    <!-- ID para el script JS -->
                    <button id="mobile-menu-btn" class="text-[#213A57] text-2xl focus:outline-none p-2">
                        <i class="ph ph-list"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Menú Desplegable Móvil -->
        <div id="mobile-menu"
            class="hidden md:hidden bg-white border-t border-gray-100 absolute w-full shadow-lg left-0 top-20 z-40">
            <div class="px-4 py-6 space-y-4 flex flex-col">
                <a href="{{ route('home') }}"
                    class="text-gray-600 hover:text-[#0B6477] font-medium block py-2">Inicio</a>
                
                <div class="py-2 border-t border-b border-gray-100">
                    <span class="text-gray-400 text-xs font-bold uppercase tracking-widest block mb-2 px-2">Programas</span>
                    <a href="{{ route('catalog') }}"
                        class="text-gray-600 hover:text-[#0B6477] font-medium block py-2 pl-4 flex items-center gap-2">
                        <i class="ph-bold ph-book-open text-xs"></i> Cursos
                    </a>
                    <a href="{{ route('diplomados') }}"
                        class="text-gray-600 hover:text-[#0B6477] font-medium block py-2 pl-4 flex items-center gap-2">
                        <i class="ph-bold ph-scroll text-xs"></i> Diplomados
                    </a>
                </div>

                <a href="{{ route('about') }}"
                    class="text-gray-600 hover:text-[#0B6477] font-medium block py-2">Nosotros</a>
                <a href="{{ route('contact') }}"
                    class="text-gray-600 hover:text-[#0B6477] font-medium block py-2">Contacto</a>

                <div class="border-t border-gray-100 pt-4 mt-2">
                    @auth('web')
                        <a href="{{ route('admin.dashboard') }}" class="text-[#0B6477] font-bold block mb-3">Panel Admin</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-red-500 font-medium w-full text-left">Cerrar Sesión</button>
                        </form>
                    @elseauth('student')
                        <a href="{{ route('student.dashboard') }}" class="text-[#0B6477] font-bold block mb-3">Mi
                            Aprendizaje</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-red-500 font-medium w-full text-left">Cerrar Sesión</button>
                        </form>
                    @else
                        <!-- En móvil también cambiamos el texto a Iniciar Sesión -->
                        <a href="{{ route('login') }}" class="text-[#213A57] font-bold block py-2">{{ $platformSettings['nav_btn_text'] ?? 'Iniciar Sesión' }}</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="min-h-screen pt-20">
        {{ $slot }}
    </main>

    <footer class="bg-[#001144] text-white pt-24 pb-12 border-t border-white/5 relative overflow-hidden">
        <!-- Decoración de Fondo -->
        <div
            class="absolute top-0 left-1/4 w-96 h-96 bg-[#FF7A00]/10 rounded-full blur-[120px] -translate-y-1/2 pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 right-0 w-64 h-64 bg-blue-600/10 rounded-full blur-[100px] translate-x-1/2 translate-y-1/2 pointer-events-none">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 mb-20">

                <!-- Información de Marca -->
                <div class="lg:col-span-4 space-y-8">
                    <a href="{{ route('home') }}" class="flex items-center group">
                        <img src="{{ asset($platformSettings['app_logo_white'] ?? 'img/logo-white.png') }}" alt="{{ $platformSettings['app_name'] ?? 'CapacitaPerú' }}" class="h-20 w-auto group-hover:scale-105 transition-transform">
                    </a>
                    <p class="text-gray-400 text-lg leading-relaxed font-medium">
                        {{ $platformSettings['footer_desc'] ?? 'Líderes en capacitación técnica y profesional. Potenciamos tu talento con certificación de alto impacto nacional y respaldo institucional.' }}
                    </p>
                    
                    @if(!empty($platformSettings['footer_razon_social']) || !empty($platformSettings['footer_ruc']))
                        <div class="text-gray-500 text-xs font-semibold space-y-1 bg-white/2 p-4.5 rounded-2xl border border-white/5">
                            @if(!empty($platformSettings['footer_razon_social']))
                                <p class="flex items-center gap-2"><i class="ph-bold ph-buildings text-sm text-[#FF7A00]"></i> Razón Social: <span class="text-gray-400 font-bold">{{ $platformSettings['footer_razon_social'] }}</span></p>
                            @endif
                            @if(!empty($platformSettings['footer_ruc']))
                                <p class="flex items-center gap-2"><i class="ph-bold ph-identification-card text-sm text-[#FF7A00]"></i> RUC: <span class="text-gray-400 font-bold">{{ $platformSettings['footer_ruc'] }}</span></p>
                            @endif
                        </div>
                    @endif

                    <div class="flex items-center gap-4">
                        @php
                            $socialIcons = [
                                'facebook' => 'ph-facebook-logo',
                                'instagram' => 'ph-instagram-logo',
                                'linkedin' => 'ph-linkedin-logo',
                                'youtube' => 'ph-youtube-logo',
                                'tiktok' => 'ph-tiktok-logo',
                                'twitter' => 'ph-twitter-logo',
                                'whatsapp' => 'ph-whatsapp-logo'
                            ];
                            $hasNewSocials = false;
                            for($i = 1; $i <= 5; $i++) {
                                if(!empty($platformSettings['social_net_' . $i . '_type']) && !empty($platformSettings['social_net_' . $i . '_url'])) {
                                    $hasNewSocials = true;
                                    break;
                                }
                            }
                        @endphp
                        
                        @if($hasNewSocials)
                            @for($i = 1; $i <= 5; $i++)
                                @php
                                    $type = $platformSettings['social_net_' . $i . '_type'] ?? '';
                                    $url = $platformSettings['social_net_' . $i . '_url'] ?? '';
                                    if (!empty($url) && $url !== '#' && !\Illuminate\Support\Str::startsWith($url, ['http://', 'https://', '//'])) {
                                        $url = 'https://' . $url;
                                    }
                                @endphp
                                @if(!empty($type) && !empty($url) && $url !== '#')
                                    <a href="{{ $url }}" target="_blank"
                                        class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-[#FF7A00] hover:text-white transition-all transform hover:-translate-y-1">
                                        <i class="ph-fill {{ $socialIcons[$type] ?? 'ph-link' }} text-xl"></i>
                                    </a>
                                @endif
                            @endfor
                        @else
                            @if(!empty($platformSettings['social_facebook']) && $platformSettings['social_facebook'] !== '#')
                                @php
                                    $fbUrl = $platformSettings['social_facebook'];
                                    if (!\Illuminate\Support\Str::startsWith($fbUrl, ['http://', 'https://', '//'])) {
                                        $fbUrl = 'https://' . $fbUrl;
                                    }
                                @endphp
                                <a href="{{ $fbUrl }}" target="_blank"
                                    class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-[#FF7A00] hover:text-white transition-all transform hover:-translate-y-1">
                                    <i class="ph-fill ph-facebook-logo text-xl"></i>
                                </a>
                            @endif
                            @if(!empty($platformSettings['social_instagram']) && $platformSettings['social_instagram'] !== '#')
                                @php
                                    $igUrl = $platformSettings['social_instagram'];
                                    if (!\Illuminate\Support\Str::startsWith($igUrl, ['http://', 'https://', '//'])) {
                                        $igUrl = 'https://' . $igUrl;
                                    }
                                @endphp
                                <a href="{{ $igUrl }}" target="_blank"
                                    class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-[#FF7A00] hover:text-white transition-all transform hover:-translate-y-1">
                                    <i class="ph-fill ph-instagram-logo text-xl"></i>
                                </a>
                            @endif
                            @if(!empty($platformSettings['social_linkedin']) && $platformSettings['social_linkedin'] !== '#')
                                @php
                                    $liUrl = $platformSettings['social_linkedin'];
                                    if (!\Illuminate\Support\Str::startsWith($liUrl, ['http://', 'https://', '//'])) {
                                        $liUrl = 'https://' . $liUrl;
                                    }
                                @endphp
                                <a href="{{ $liUrl }}" target="_blank"
                                    class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-[#FF7A00] hover:text-white transition-all transform hover:-translate-y-1">
                                    <i class="ph-fill ph-linkedin-logo text-xl"></i>
                                </a>
                            @endif
                            @if(!empty($platformSettings['social_youtube']) && $platformSettings['social_youtube'] !== '#')
                                @php
                                    $ytUrl = $platformSettings['social_youtube'];
                                    if (!\Illuminate\Support\Str::startsWith($ytUrl, ['http://', 'https://', '//'])) {
                                        $ytUrl = 'https://' . $ytUrl;
                                    }
                                @endphp
                                <a href="{{ $ytUrl }}" target="_blank"
                                    class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-[#FF7A00] hover:text-white transition-all transform hover:-translate-y-1">
                                    <i class="ph-fill ph-youtube-logo text-xl"></i>
                                </a>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Secciones de Navegación -->
                <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-3 gap-12">
                    <!-- Sección 1: Formación -->
                    <div class="space-y-6">
                        <h4
                            class="text-white font-black uppercase tracking-widest text-xs border-l-4 border-[#FF7A00] pl-4">
                            {{ $platformSettings['footer_col1_title'] ?? 'Formación' }}</h4>
                        <ul class="space-y-4">
                            <li><a href="{{ $platformSettings['footer_col1_l1_url'] ?? route('catalog') }}"
                                    class="text-gray-400 hover:text-[#FF7A00] transition-colors text-sm font-bold flex items-center gap-2 group">
                                    <i
                                        class="ph-bold ph-caret-right text-[10px] opacity-0 group-hover:opacity-100 transition-all -ml-4 group-hover:ml-0"></i>
                                    {{ $platformSettings['footer_col1_l1_text'] ?? 'Explorar Cursos' }}
                                </a></li>
                            <li><a href="{{ $platformSettings['footer_col1_l2_url'] ?? route('catalog') }}"
                                    class="text-gray-400 hover:text-[#FF7A00] transition-colors text-sm font-bold flex items-center gap-2 group">
                                    <i
                                        class="ph-bold ph-caret-right text-[10px] opacity-0 group-hover:opacity-100 transition-all -ml-4 group-hover:ml-0"></i>
                                    {{ $platformSettings['footer_col1_l2_text'] ?? 'Especializaciones' }}
                                </a></li>
                            <li><a href="{{ $platformSettings['footer_col1_l3_url'] ?? route('contact') }}"
                                    class="text-gray-400 hover:text-[#FF7A00] transition-colors text-sm font-bold flex items-center gap-2 group">
                                    <i
                                        class="ph-bold ph-caret-right text-[10px] opacity-0 group-hover:opacity-100 transition-all -ml-4 group-hover:ml-0"></i>
                                    {{ $platformSettings['footer_col1_l3_text'] ?? 'Para Empresas' }}
                                </a></li>
                        </ul>
                    </div>

                    <!-- Sección 2: Recursos -->
                    <div class="space-y-6">
                        <h4
                            class="text-white font-black uppercase tracking-widest text-xs border-l-4 border-[#FF7A00] pl-4">
                            {{ $platformSettings['footer_col2_title'] ?? 'Recursos' }}</h4>
                        <ul class="space-y-4">
                            <li><a href="{{ $platformSettings['footer_col2_l1_url'] ?? route('about') }}"
                                    class="text-gray-400 hover:text-[#FF7A00] transition-colors text-sm font-bold flex items-center gap-2 group">
                                    <i
                                        class="ph-bold ph-caret-right text-[10px] opacity-0 group-hover:opacity-100 transition-all -ml-4 group-hover:ml-0"></i>
                                    {{ $platformSettings['footer_col2_l1_text'] ?? 'Nuestra Historia' }}
                                </a></li>
                            <li><a href="{{ $platformSettings['footer_col2_l2_url'] ?? route('front.certificate.individual.search') }}"
                                    class="text-gray-400 hover:text-[#FF7A00] transition-colors text-sm font-bold flex items-center gap-2 group">
                                    <i
                                         class="ph-bold ph-caret-right text-[10px] opacity-0 group-hover:opacity-100 transition-all -ml-4 group-hover:ml-0"></i>
                                    {{ $platformSettings['footer_col2_l2_text'] ?? 'Verificar Certificado' }}
                                </a></li>
                        </ul>
                    </div>

                    <!-- Sección 3: Atención -->
                    <div class="space-y-6">
                        <h4
                            class="text-white font-black uppercase tracking-widest text-xs border-l-4 border-[#FF7A00] pl-4">
                            {{ $platformSettings['footer_col3_title'] ?? 'Atención' }}</h4>
                        <ul class="space-y-5">
                            <li class="flex items-start gap-4">
                                <div
                                    class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-[#FF7A00] shrink-0">
                                    <i class="ph-bold ph-envelope-simple text-xl"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[10px] text-gray-500 font-black uppercase tracking-widest">Email
                                        Corporativo</span>
                                    <span class="text-sm font-bold text-gray-300">{{ $platformSettings['footer_col3_email'] ?? 'capacitaperu08@gmail.com' }}</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div
                                    class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-[#25D366] shrink-0">
                                    <i class="ph-bold ph-whatsapp-logo text-xl"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span
                                        class="text-[10px] text-gray-500 font-black uppercase tracking-widest">WhatsApp
                                        Soporte</span>
                                    <span class="text-sm font-bold text-gray-300">{{ $platformSettings['footer_col3_phone'] ?? '+51 972 539 712' }}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Barra Inferior -->
            <div class="pt-10 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex flex-col items-center md:items-start gap-2">
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-widest italic">
                        Desarrollado por Deivid Chipana
                    </p>
                    <p class="text-[10px] text-gray-600 font-medium text-center md:text-left">
                        {{ $platformSettings['footer_copyright'] ?? '© 2026 CAPACITAPERU. Centro de Formación y Actualización Profesional. Todos los derechos reservados.' }}
                    </p>
                </div>

                <div class="flex items-center gap-8">
                    <a href="{{ route('terms') }}"
                        class="text-[10px] text-gray-500 hover:text-white font-black uppercase tracking-widest transition-colors">Términos</a>
                    <a href="{{ route('privacy') }}"
                        class="text-[10px] text-gray-500 hover:text-white font-black uppercase tracking-widest transition-colors">Privacidad</a>
                    <button onclick="openClaimsModal()"
                        class="text-[10px] text-gray-500 hover:text-white font-black uppercase tracking-widest transition-colors cursor-pointer bg-transparent border-0 p-0 outline-none">Reclamaciones</button>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating System -->
    <div class="fixed bottom-8 right-8 z-[60] flex flex-col items-end gap-4">
        <!-- Contact Menu (Hidden by default) -->
        <div id="whatsapp-menu"
            class="hidden w-72 bg-white rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-gray-100 overflow-hidden transform transition-all duration-300 origin-bottom-right scale-95 opacity-0">
            <div class="bg-[#25D366] p-6 text-white">
                <h3 class="font-bold text-lg">Soporte CapacitaPerú</h3>
                <p class="text-xs opacity-90">Selecciona un asesor para chatear</p>
            </div>
            <div class="p-4 space-y-3 max-h-[400px] overflow-y-auto">
                @forelse($globalAdvisors as $advisor)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $advisor->phone) }}" target="_blank"
                        class="flex items-center gap-4 p-3 rounded-2xl hover:bg-gray-50 transition-colors group">
                        <div class="relative">
                            @if($advisor->avatar_url && Str::startsWith($advisor->avatar_url, 'http'))
                                <img src="{{ $advisor->avatar_url }}"
                                    class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                            @elseif($advisor->avatar_url)
                                <img src="{{ asset($advisor->avatar_url) }}"
                                    class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($advisor->name) }}&background=25D366&color=fff&size=128"
                                    class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                            @endif
                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-[#25D366] rounded-full border-2 border-white">
                            </div>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm group-hover:text-[#25D366] transition-colors">
                                {{ $advisor->name }}</h4>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                                {{ $advisor->position }}</p>
                            <div class="flex items-center gap-1 mt-1">
                                <div class="w-1.5 h-1.5 bg-[#25D366] rounded-full animate-pulse"></div>
                                <span class="text-[10px] text-[#25D366] font-bold">EN LÍNEA</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-6 text-center">
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Sin asesores en línea</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Trigger Button -->
        <button id="whatsapp-trigger"
            class="bg-[#25D366] text-white w-16 h-16 rounded-full flex items-center justify-center shadow-[0_10px_30px_rgba(37,211,102,0.4)] hover:scale-110 transition-all group z-10">
            <i id="whatsapp-icon" class="ph-fill ph-whatsapp-logo text-3xl"></i>
            <i id="close-icon" class="ph-bold ph-x text-2xl hidden"></i>
            <span
                class="absolute right-full mr-4 bg-white text-gray-800 px-4 py-2 rounded-xl text-sm font-bold opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-xl border border-gray-100 pointer-events-none">
                ¿Necesitas ayuda?
            </span>
        </button>
    </div>

    <!-- Script para el menú móvil y carrito -->
    <script>
        // Funciones del Carrito Globales
        window.getCart = function() {
            try {
                return JSON.parse(localStorage.getItem('capacitaperu_cart')) || [];
            } catch(e) {
                return [];
            }
        };

        window.saveCart = function(cart) {
            localStorage.setItem('capacitaperu_cart', JSON.stringify(cart));
            window.updateCartBadges();
            if (typeof window.updateCatalogCartButtons === 'function') {
                window.updateCatalogCartButtons();
            }
        };

        window.updateCatalogCartButtons = function() {
            const cart = window.getCart();
            document.querySelectorAll('[data-course-btn]').forEach(btn => {
                const courseId = parseInt(btn.dataset.courseId);
                const isDetail = btn.dataset.isDetail === "true";
                
                if (cart.some(item => item.id === courseId)) {
                    btn.disabled = true;
                    if (isDetail) {
                        btn.innerHTML = '<i class="ph-bold ph-check text-base"></i> Ya en tu Carrito';
                        btn.className = 'block w-full py-4 bg-gray-100 text-gray-400 font-black rounded-2xl text-center uppercase tracking-widest text-xs cursor-not-allowed';
                    } else {
                        btn.innerHTML = '<i class="ph-bold ph-check text-base"></i> En tu Carrito';
                        btn.className = 'bg-gray-100 text-gray-400 text-center py-3 rounded-xl font-black text-[9px] uppercase tracking-widest cursor-not-allowed flex items-center justify-center gap-1.5 w-full';
                    }
                } else {
                    btn.disabled = false;
                    if (isDetail) {
                        btn.innerHTML = 'Añadir al Carrito';
                        btn.className = 'block w-full py-4 bg-[#001144] text-white font-black rounded-2xl hover:bg-[#FF7A00] transition-all text-center uppercase tracking-widest text-xs cursor-pointer';
                    } else {
                        btn.innerHTML = '<i class="ph-bold ph-shopping-cart-simple text-base"></i> + Carrito';
                        btn.className = 'bg-[#25D366] text-white text-center py-3 rounded-xl font-black text-[9px] hover:bg-[#1fb355] transition-all shadow-xl shadow-green-200/30 flex items-center justify-center gap-1.5 uppercase tracking-widest cursor-pointer w-full';
                    }
                }
            });
        };

        window.addToCart = function(item, redirect = true) {
            let cart = window.getCart();
            if (!cart.some(c => c.id === item.id)) {
                cart.push(item);
                window.saveCart(cart);
            }
            if (redirect) {
                window.location.href = "{{ route('cart') }}";
            }
        };

        window.animateFlyToCart = function(button, item) {
            // Add silently
            window.addToCart(item, false);

            // Find image to animate
            const card = button.closest('.group');
            if (!card) return;
            const img = card.querySelector('img');
            if (!img) return;

            // Target badge
            let target = document.getElementById('cart-badge');
            if (!target || target.offsetParent === null) {
                target = document.getElementById('cart-badge-mobile');
            }
            if (!target) return;

            const imgRect = img.getBoundingClientRect();
            const targetRect = target.getBoundingClientRect();

            // Create flyer
            const flyer = document.createElement('img');
            flyer.src = img.src;
            flyer.style.position = 'fixed';
            flyer.style.left = `${imgRect.left}px`;
            flyer.style.top = `${imgRect.top}px`;
            flyer.style.width = `${imgRect.width}px`;
            flyer.style.height = `${imgRect.height}px`;
            flyer.style.borderRadius = '1rem';
            flyer.style.zIndex = '9999';
            flyer.style.pointerEvents = 'none';
            flyer.style.transition = 'all 0.8s cubic-bezier(0.25, 1, 0.5, 1)';
            flyer.style.boxShadow = '0 10px 25px rgba(255, 122, 0, 0.4)';
            flyer.style.opacity = '1';

            document.body.appendChild(flyer);

            // Animate
            requestAnimationFrame(() => {
                flyer.style.left = `${targetRect.left + (targetRect.width / 2) - 15}px`;
                flyer.style.top = `${targetRect.top + (targetRect.height / 2) - 15}px`;
                flyer.style.width = '30px';
                flyer.style.height = '30px';
                flyer.style.opacity = '0.3';
                flyer.style.transform = 'rotate(360deg) scale(0.1)';
            });

            // Cleanup & visual feedback
            setTimeout(() => {
                flyer.remove();
                const cartParent = target.closest('a');
                if (cartParent) {
                    cartParent.classList.add('scale-110', 'border-[#FF7A00]', 'bg-orange-50/10');
                    setTimeout(() => {
                        cartParent.classList.remove('scale-110', 'border-[#FF7A00]', 'bg-orange-50/10');
                    }, 300);
                }
            }, 800);

            // Button feedback
            const originalHtml = button.innerHTML;
            const originalClass = button.className;
            
            button.disabled = true;
            button.innerHTML = '<i class="ph-bold ph-check-circle text-base"></i> ¡Agregado!';
            button.classList.remove('bg-[#25D366]');
            button.classList.add('bg-[#0033CC]');

            setTimeout(() => {
                button.disabled = false;
                button.innerHTML = originalHtml;
                button.className = originalClass;
            }, 2000);
        };

        window.removeFromCart = function(id) {
            let cart = window.getCart();
            cart = cart.filter(c => c.id !== id);
            window.saveCart(cart);
            window.dispatchEvent(new Event('cartUpdated'));
        };

        window.updateCartBadges = function() {
            const cart = window.getCart();
            const count = cart.length;
            
            const badge = document.getElementById('cart-badge');
            const badgeMobile = document.getElementById('cart-badge-mobile');
            
            if (badge) {
                if (count > 0) {
                    badge.innerText = count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
            
            if (badgeMobile) {
                if (count > 0) {
                    badgeMobile.innerText = count;
                    badgeMobile.classList.remove('hidden');
                } else {
                    badgeMobile.classList.add('hidden');
                }
            }
        };

        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar badges de carrito
            window.updateCartBadges();
            window.updateCatalogCartButtons();
            
            // Escuchar cambios
            window.addEventListener('cartUpdated', window.updateCatalogCartButtons);

            // Lógica WhatsApp
            const trigger = document.getElementById('whatsapp-trigger');
            const menu = document.getElementById('whatsapp-menu');
            const waIcon = document.getElementById('whatsapp-icon');
            const closeIcon = document.getElementById('close-icon');

            trigger.addEventListener('click', () => {
                const isHidden = menu.classList.contains('hidden');

                if (isHidden) {
                    menu.classList.remove('hidden');
                    setTimeout(() => {
                        menu.classList.remove('scale-95', 'opacity-0');
                        menu.classList.add('scale-100', 'opacity-100');
                    }, 10);
                    waIcon.classList.add('hidden');
                    closeIcon.classList.remove('hidden');
                } else {
                    menu.classList.remove('scale-100', 'opacity-100');
                    menu.classList.add('scale-95', 'opacity-0');
                    waIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                    setTimeout(() => menu.classList.add('hidden'), 300);
                }
            });

            // Cerrar al hacer click fuera
            document.addEventListener('click', (e) => {
                if (!trigger.contains(e.target) && !menu.contains(e.target) && !menu.classList.contains('hidden')) {
                    trigger.click();
                }
            });

            // Menú móvil
            const btn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');

            if (btn && mobileMenu) {
                btn.addEventListener('click', function () {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>

    <!-- MODAL DE RECLAMACIONES -->
    <div id="claims-modal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-[#001144]/60 backdrop-blur-md transition-opacity" onclick="closeClaimsModal()"></div>
        
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md border border-white/20">
                    
                    <!-- Header -->
                    <div class="bg-[#001144] px-8 py-6 text-white flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-[#FF7A00]">
                                <i class="ph-bold ph-warning-octagon text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black tracking-tight">Reclamaciones</h3>
                                <p class="text-[10px] text-gray-300 font-bold uppercase tracking-widest">Libro de Reclamaciones Virtual</p>
                            </div>
                        </div>
                        <button onclick="closeClaimsModal()" class="w-10 h-10 flex items-center justify-center text-gray-300 hover:text-white transition-colors rounded-full hover:bg-white/10 cursor-pointer">
                            <i class="ph-bold ph-x text-xl"></i>
                        </button>
                    </div>

                    <!-- Form -->
                    <form id="claims-form" onsubmit="submitClaimForm(event)">
                        @csrf
                        <div class="p-8 space-y-5">
                            
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Nombre Completo <span class="text-[#FF7A00]">*</span></label>
                                <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none text-xs font-bold text-[#001144] transition-all" placeholder="Ej: Juan Pérez">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Email <span class="text-[#FF7A00]">*</span></label>
                                    <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none text-xs font-bold text-[#001144] transition-all" placeholder="Ej: juan@mail.com">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Teléfono</label>
                                    <input type="text" name="phone" class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none text-xs font-bold text-[#001144] transition-all" placeholder="Ej: 999888777">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Tipo de Problema <span class="text-[#FF7A00]">*</span></label>
                                <select name="issue_type" required class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-xs font-bold text-[#001144] transition-all cursor-pointer">
                                    <option value="Acceso al Aula">Acceso al Aula Virtual</option>
                                    <option value="Certificados">Problemas con Certificados</option>
                                    <option value="Pagos y Matrículas">Dificultad con Pagos o Matrículas</option>
                                    <option value="Soporte y Atención">Atención de Asesores o Docentes</option>
                                    <option value="Otros">Otros (Especificar en descripción)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Descripción de la Reclamación <span class="text-[#FF7A00]">*</span></label>
                                <textarea name="description" required rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-xs font-medium text-[#001144] resize-none transition-all" placeholder="Describe detalladamente el inconveniente..."></textarea>
                            </div>

                            <div id="claims-alert" class="hidden p-4 rounded-xl text-xs font-bold"></div>

                            <div class="pt-4 border-t border-gray-50 flex justify-end gap-3">
                                <button type="button" onclick="closeClaimsModal()" class="px-5 py-3 bg-gray-100 hover:bg-gray-200 text-[#001144] font-black rounded-xl text-[10px] uppercase tracking-widest transition-all cursor-pointer">
                                    Cancelar
                                </button>
                                <button type="submit" id="claims-submit-btn" class="px-6 py-3 bg-[#001144] hover:bg-[#FF7A00] text-white font-black rounded-xl text-[10px] uppercase tracking-widest shadow-xl shadow-blue-900/10 transition-all cursor-pointer">
                                    Enviar Reclamación
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openClaimsModal() {
            document.getElementById('claims-modal').classList.remove('hidden');
            document.getElementById('claims-alert').className = 'hidden p-4 rounded-xl text-xs font-bold';
            document.getElementById('claims-form').reset();
        }
        function closeClaimsModal() {
            document.getElementById('claims-modal').classList.add('hidden');
        }

        async function submitClaimForm(e) {
            e.preventDefault();
            const form = document.getElementById('claims-form');
            const btn = document.getElementById('claims-submit-btn');
            const alertBox = document.getElementById('claims-alert');
            
            btn.disabled = true;
            btn.innerText = 'Enviando...';
            
            const formData = new FormData(form);
            
            try {
                const response = await fetch("{{ route('claims.store') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alertBox.innerText = data.message;
                    alertBox.className = "p-4 rounded-xl text-xs font-bold bg-green-50 text-green-600 border border-green-200 block";
                    setTimeout(() => {
                        closeClaimsModal();
                    }, 2500);
                } else {
                    alertBox.innerText = data.message || 'Ocurrió un error inesperado.';
                    alertBox.className = "p-4 rounded-xl text-xs font-bold bg-red-50 text-red-600 border border-red-200 block";
                    btn.disabled = false;
                    btn.innerText = 'Enviar Reclamación';
                }
            } catch (err) {
                alertBox.innerText = 'Error al conectar con el servidor.';
                alertBox.className = "p-4 rounded-xl text-xs font-bold bg-red-50 text-red-600 border border-red-200 block";
                btn.disabled = false;
                btn.innerText = 'Enviar Reclamación';
            }
        }
    </script>
</body>

</html>