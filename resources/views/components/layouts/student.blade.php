<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? ($platformSettings['app_name'] ?? 'CapacitaPerú') . ' - Estudiante' }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Outfit', sans-serif; }
        .fade-in-up { animation: fadeInUp 0.5s ease-out forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #FF7A00; }

        .sidebar-item-active {
            background: rgba(255, 122, 0, 0.1);
            color: #FF7A00;
            border-left-color: #FF7A00;
        }
    </style>
</head>
<body class="bg-gray-50 text-slate-800 antialiased selection:bg-[#FF7A00] selection:text-white relative">

    <!-- Overlay para móvil -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 hidden transition-opacity duration-300 md:hidden backdrop-blur-sm"></div>

    <div class="h-screen flex relative overflow-hidden">
        
        <!-- SIDEBAR Premium -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 w-72 bg-[#001144] text-white flex flex-col shadow-2xl z-40 transition-transform duration-500 transform -translate-x-full md:translate-x-0 md:relative border-r border-white/5">
            
            <!-- Logo Branded -->
            <div class="h-28 flex items-center justify-center px-8 shrink-0 relative">
                <a href="{{ route('home') }}" class="flex items-center group">
                    <img src="{{ asset($platformSettings['app_logo_white'] ?? 'img/logo-white.png') }}" alt="{{ $platformSettings['app_name'] ?? 'CapacitaPerú' }}" class="h-14 w-auto group-hover:scale-110 transition-transform">
                </a>
                <button id="sidebar-close" class="absolute right-6 md:hidden text-white/50 hover:text-[#FF7A00] transition-colors">
                    <i class="ph-bold ph-x text-2xl"></i>
                </button>
            </div>

            <!-- Navegación Scrolleable -->
            <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto no-scrollbar">
                <p class="px-4 text-[10px] font-black text-white/30 uppercase tracking-[0.3em] mb-4">Mi Academia</p>
                
                <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 px-4 py-4 rounded-2xl transition-all border-l-4 {{ request()->routeIs('student.dashboard') ? 'sidebar-item-active font-bold' : 'text-white/60 hover:bg-white/5 hover:text-white border-transparent' }}">
                    <i class="ph-fill ph-squares-four text-2xl"></i>
                    <span class="text-sm">Mis Cursos</span>
                </a>

                <p class="px-4 text-[10px] font-black text-white/30 uppercase tracking-[0.3em] mt-10 mb-4">Configuración</p>

                <a href="{{ route('student.profile') }}" class="flex items-center gap-3 px-4 py-4 rounded-2xl transition-all border-l-4 {{ request()->routeIs('student.profile') ? 'sidebar-item-active font-bold' : 'text-white/60 hover:bg-white/5 hover:text-white border-transparent' }}">
                    <i class="ph-fill ph-user-circle text-2xl"></i>
                    <span class="text-sm">Perfil y Seguridad</span>
                </a>
                
                <a href="{{ route('student.billing') }}" class="flex items-center gap-3 px-4 py-4 rounded-2xl transition-all border-l-4 {{ request()->routeIs('student.billing') ? 'sidebar-item-active font-bold' : 'text-white/60 hover:bg-white/5 hover:text-white border-transparent' }}">
                    <i class="ph-fill ph-credit-card text-2xl"></i>
                    <span class="text-sm">Historial de Pagos</span>
                </a>

                <div class="mt-20 px-4">
                    <div class="p-6 bg-gradient-to-br from-[#FF7A00]/20 to-transparent rounded-3xl border border-white/10 relative overflow-hidden">
                        <div class="relative z-10">
                            <p class="text-[10px] font-black text-[#FF7A00] uppercase tracking-widest mb-2">Soporte 24/7</p>
                            <p class="text-xs text-white/60 leading-relaxed mb-4">¿Necesitas ayuda con tus cursos?</p>
                            <a href="https://wa.me/51972539712" target="_blank" class="block w-full py-3 bg-white text-[#001144] rounded-xl text-center text-[10px] font-black uppercase tracking-widest hover:bg-[#FF7A00] hover:text-white transition-all">WhatsApp</a>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Perfil Usuario Mini -->
            @php
                $user = Auth::guard('student')->user();
                $avatar = $user->avatar_url 
                    ? (Str::startsWith($user->avatar_url, 'http') ? $user->avatar_url : asset($user->avatar_url))
                    : 'https://ui-avatars.com/api/?name='.urlencode($user->name ?? 'Estudiante').'&background=FF7A00&color=fff';
            @endphp
            <div class="p-6 border-t border-white/5 bg-[#000d33] shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <img src="{{ $avatar }}" class="w-10 h-10 rounded-2xl border-2 border-white/10 p-0.5 object-cover shrink-0">
                        <div class="overflow-hidden">
                            <h4 class="text-xs font-bold text-white truncate">{{ $user->name ?? 'Estudiante' }}</h4>
                            <p class="text-[9px] text-[#FF7A00] font-black uppercase tracking-widest">Activo</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-white/30 hover:text-red-500 transition-colors p-2 rounded-xl hover:bg-red-500/10" title="Cerrar Sesión">
                            <i class="ph-bold ph-sign-out text-xl"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50">
            <!-- Header Branded -->
            <header class="h-24 bg-white/80 backdrop-blur-xl border-b border-gray-100 flex items-center justify-between px-10 z-10 sticky top-0 shrink-0">
                <div class="flex items-center gap-6">
                    <button id="sidebar-toggle" class="md:hidden w-12 h-12 bg-gray-50 rounded-2xl text-[#001144] hover:text-[#FF7A00] transition-colors flex items-center justify-center">
                        <i class="ph-bold ph-list text-2xl"></i>
                    </button>
                    
                    <h2 class="font-black text-2xl text-[#001144] tracking-tighter">
                        {{ $header ?? 'Panel Académico' }}
                    </h2>
                </div>
                
                <div class="flex items-center gap-6">
                    <!-- Buscador Premium -->
                    <div class="hidden lg:flex relative group">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00] transition-colors"></i>
                        <input type="text" placeholder="Buscar en mi biblioteca..." class="pl-12 pr-6 py-3 rounded-2xl border-2 border-gray-50 bg-gray-50 focus:bg-white focus:border-[#FF7A00] transition-all w-80 text-sm font-bold">
                    </div>
                    
                    <!-- Notificaciones -->
                    <button class="w-12 h-12 rounded-2xl bg-gray-50 text-gray-500 hover:bg-orange-50 hover:text-[#FF7A00] transition-all flex items-center justify-center relative group shadow-sm">
                        <span class="absolute top-3 right-3.5 w-2 h-2 rounded-full bg-red-500 ring-4 ring-white group-hover:animate-ping"></span>
                        <i class="ph-bold ph-bell text-xl"></i>
                    </button>
                </div>
            </header>

            <!-- Contenido Principal con Efectos -->
            <main class="flex-1 p-10 overflow-y-auto no-scrollbar scroll-smooth relative">
                <!-- Decoración de fondo -->
                <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#FF7A00] opacity-[0.03] rounded-full blur-[100px] pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
                
                <div class="max-w-[1600px] mx-auto">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <!-- Script funcional -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebar-toggle');
            const closeBtn = document.getElementById('sidebar-close');
            const overlay = document.getElementById('sidebar-overlay');

            function toggleSidebar() {
                const isClosed = sidebar.classList.contains('-translate-x-full');
                if (isClosed) {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                }
            }

            if(toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
            if(closeBtn) closeBtn.addEventListener('click', toggleSidebar);
            if(overlay) overlay.addEventListener('click', toggleSidebar);
        });
    </script>
</body>
</html>