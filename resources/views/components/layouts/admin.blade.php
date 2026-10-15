<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? ($platformSettings['app_name'] ?? 'CapacitaPerú') . ' - Plataforma Educativa' }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Outfit', sans-serif; }
        .fade-in-up { animation: fadeInUp 0.5s ease-out forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #FF7A00; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #e66e00; }
        
        .sidebar-item-active {
            background: #FF7A00;
            color: white;
            box-shadow: 0 10px 20px -5px rgba(255, 122, 0, 0.3);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-[#f8faff] text-[#001144] antialiased selection:bg-[#FF7A00] selection:text-white relative">

    <!-- Overlay para móvil -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-[#001144]/40 z-30 hidden transition-opacity duration-300 md:hidden backdrop-blur-sm"></div>

    <div class="min-h-screen flex relative overflow-hidden">
        
        <!-- SIDEBAR -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 w-72 bg-[#001144] text-white flex flex-col z-40 shadow-2xl transition-transform duration-300 transform -translate-x-full md:translate-x-0">
            <div class="h-24 flex items-center px-8 justify-center relative">
                <a href="{{ route('home') }}" class="flex items-center group">
                    <img src="{{ asset($platformSettings['app_logo_white'] ?? 'img/logo-white.png') }}" alt="{{ $platformSettings['app_name'] ?? 'CapacitaPerú' }}" class="h-14 w-auto group-hover:scale-110 transition-transform">
                </a>
                <button id="sidebar-close" class="absolute right-6 md:hidden text-white/50 hover:text-white">
                    <i class="ph ph-x text-2xl"></i>
                </button>
            </div>

            <nav class="flex-1 px-4 pt-2 pb-6 space-y-1.5 overflow-y-auto no-scrollbar">
                
                <p class="px-4 text-[10px] font-black text-white/30 uppercase tracking-[0.2em] mb-3 mt-0">Principal</p>
                
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'sidebar-item-active' : 'hover:bg-white/5 text-white/60 hover:text-white' }}">
                    <i class="ph-fill ph-house-line text-xl"></i>
                    <span class="font-bold text-sm">Dashboard</span>
                </a>

                @if(Auth::user()->hasPermission('courses.view'))
                    <p class="px-4 text-[10px] font-black text-white/30 uppercase tracking-[0.2em] mb-3 mt-8">Programas</p>
                    
                    <a href="{{ route('admin.courses.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.courses.*') ? 'sidebar-item-active' : 'hover:bg-white/5 text-white/60 hover:text-white' }}">
                        <i class="ph-fill ph-book-open text-xl"></i>
                        <span class="font-bold text-sm">Cursos</span>
                    </a>

                    <a href="{{ route('admin.diplomas.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.diplomas.*') ? 'sidebar-item-active' : 'hover:bg-white/5 text-white/60 hover:text-white' }}">
                        <i class="ph-fill ph-graduation-cap text-xl"></i>
                        <span class="font-bold text-sm">Diplomados</span>
                    </a>
                @endif

                @if(Auth::user()->hasPermission('students.view') || Auth::user()->hasPermission('instructors.view') || Auth::user()->hasPermission('categories.view') || Auth::user()->hasPermission('enrollments.view'))
                    <p class="px-4 text-[10px] font-black text-white/30 uppercase tracking-[0.2em] mb-3 mt-8">Gestión Académica</p>

                    @if(Auth::user()->hasPermission('students.view'))
                    <a href="{{ route('admin.students.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.students.index') ? 'sidebar-item-active' : 'hover:bg-white/5 text-white/60 hover:text-white' }}">
                        <i class="ph-fill ph-users text-xl"></i>
                        <span class="font-bold text-sm">Estudiantes</span>
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('instructors.view'))
                    <a href="{{ route('admin.instructors.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.instructors.index') ? 'sidebar-item-active' : 'hover:bg-white/5 text-white/60 hover:text-white' }}">
                        <i class="ph-fill ph-user-circle text-xl"></i>
                        <span class="font-bold text-sm">Docentes</span>
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('categories.view'))
                    <a href="{{ route('admin.categories.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.categories.index') ? 'sidebar-item-active' : 'hover:bg-white/5 text-white/60 hover:text-white' }}">
                        <i class="ph-fill ph-tag text-xl"></i>
                        <span class="font-bold text-sm">Categorías</span>
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('enrollments.view'))
                    <a href="{{ route('admin.enrollments.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.enrollments.*') ? 'sidebar-item-active' : 'hover:bg-white/5 text-white/60 hover:text-white' }}">
                        <i class="ph-fill ph-identification-card text-xl"></i>
                        <span class="font-bold text-sm">Inscritos</span>
                    </a>
                    @endif
                @endif

                @if(Auth::user()->hasPermission('individual-certificates.view'))
                    <p class="px-4 text-[10px] font-black text-white/30 uppercase tracking-[0.2em] mb-3 mt-8">Certificación</p>
                    <a href="{{ route('admin.individual-certificates.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.individual-certificates.*') ? 'sidebar-item-active' : 'hover:bg-white/5 text-white/60 hover:text-white' }}">
                        <i class="ph-fill ph-certificate text-xl"></i>
                        <span class="font-bold text-sm">Individual</span>
                    </a>
                    <a href="{{ route('admin.certificate-templates.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.certificate-templates.*') ? 'sidebar-item-active' : 'hover:bg-white/5 text-white/60 hover:text-white' }}">
                        <i class="ph-fill ph-layout text-xl"></i>
                        <span class="font-bold text-sm">Plantillas</span>
                    </a>
                @endif

                @if(Auth::user()->hasPermission('promotions.view'))
                    <p class="px-4 text-[10px] font-black text-white/30 uppercase tracking-[0.2em] mb-3 mt-8">Marketing</p>
                    <a href="{{ route('admin.promotions.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.promotions.*') ? 'sidebar-item-active' : 'hover:bg-white/5 text-white/60 hover:text-white' }}">
                        <i class="ph-fill ph-ticket text-xl"></i>
                        <span class="font-bold text-sm">Promociones</span>
                    </a>
                @endif

                @if(Auth::user()->hasPermission('advisors.view') || Auth::user()->hasPermission('claims.view'))
                    <p class="px-4 text-[10px] font-black text-white/30 uppercase tracking-[0.2em] mb-3 mt-8">Contacto</p>
                    @if(Auth::user()->hasPermission('advisors.view'))
                        <a href="{{ route('admin.advisors.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.advisors.*') ? 'sidebar-item-active' : 'hover:bg-white/5 text-white/60 hover:text-white' }}">
                            <i class="ph-fill ph-whatsapp-logo text-xl"></i>
                            <span class="font-bold text-sm">Asesores</span>
                        </a>
                    @endif
                    @if(Auth::user()->hasPermission('claims.view'))
                        <a href="{{ route('admin.claims.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.claims.*') ? 'sidebar-item-active' : 'hover:bg-white/5 text-white/60 hover:text-white' }}">
                            <i class="ph-fill ph-warning-octagon text-xl"></i>
                            <span class="font-bold text-sm">Reclamaciones</span>
                        </a>
                    @endif
                @endif

                @if(Auth::user()->hasPermission('users.view'))
                    <p class="px-4 text-[10px] font-black text-white/30 uppercase tracking-[0.2em] mb-3 mt-8">Administración</p>
                    <a href="{{ route('admin.staff.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.staff.index') ? 'sidebar-item-active' : 'hover:bg-white/5 text-white/60 hover:text-white' }}">
                        <i class="ph-fill ph-shield-check text-xl"></i>
                        <span class="font-bold text-sm">Equipo / Staff</span>
                    </a>
                @endif

                @if(Auth::user()->hasPermission('downloads.view') || Auth::user()->hasPermission('backups.view') || Auth::user()->hasPermission('landing.edit') || Auth::user()->hasPermission('settings.view'))
                    <p class="px-4 text-[10px] font-black text-white/30 uppercase tracking-[0.2em] mb-3 mt-8">Sistema</p>
                    @if(Auth::user()->hasPermission('downloads.view') || Auth::user()->hasPermission('backups.view') || Auth::user()->hasPermission('settings.view'))
                        <a href="{{ route('admin.downloads.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.downloads.*') ? 'sidebar-item-active' : 'hover:bg-white/5 text-white/60 hover:text-white' }}">
                            <i class="ph-fill ph-download-simple text-xl"></i>
                            <span class="font-bold text-sm">Centro de Descargas</span>
                        </a>
                    @endif
                    @if(Auth::user()->hasPermission('backups.view'))
                        <a href="{{ route('admin.backups.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.backups.*') ? 'sidebar-item-active' : 'hover:bg-white/5 text-white/60 hover:text-white' }}">
                            <i class="ph-fill ph-database text-xl"></i>
                            <span class="font-bold text-sm">Backup</span>
                        </a>
                    @endif
                    @if(Auth::user()->hasPermission('landing.edit'))
                        <a href="{{ route('admin.landing.edit') }}" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.landing.edit') ? 'sidebar-item-active' : 'hover:bg-white/5 text-white/60 hover:text-white' }}">
                            <i class="ph-fill ph-layout text-xl"></i>
                            <span class="font-bold text-sm">Editar Landing</span>
                        </a>
                    @endif
                    @if(Auth::user()->hasPermission('settings.view'))
                        <a href="{{ route('admin.settings.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.settings.index') ? 'sidebar-item-active' : 'hover:bg-white/5 text-white/60 hover:text-white' }}">
                            <i class="ph-fill ph-gear text-xl"></i>
                            <span class="font-bold text-sm">Configuración</span>
                        </a>
                    @endif
                @endif
            </nav>

            <!-- Perfil Footer -->
            <div class="p-6 bg-white/5 border-t border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full border-2 border-[#FF7A00] overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=001144&color=fff" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-black text-white truncate uppercase">{{ Auth::user()->name ?? 'Invitado' }}</h4>
                        <p class="text-[10px] text-white/40 font-bold uppercase">{{ Auth::user()->role ?? 'Usuario' }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-8 h-8 flex items-center justify-center text-white/40 hover:text-[#FF7A00] transition-colors">
                            <i class="ph-bold ph-sign-out text-xl"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- CONTENIDO -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden z-10 relative md:ml-72">
            
            <!-- HEADER -->
            <header class="h-20 glass-effect border-b border-gray-100 flex items-center justify-between px-8 z-20 sticky top-0">
                <div class="flex items-center gap-4">
                    <button id="sidebar-toggle" class="md:hidden text-[#001144] hover:text-[#FF7A00] transition-colors">
                        <i class="ph ph-list text-2xl"></i>
                    </button>
                    <h2 class="font-black text-xl text-[#001144] tracking-tight">{{ $header ?? 'Panel de Control' }}</h2>
                </div>

                <div class="flex items-center gap-4">
                    <button class="w-10 h-10 rounded-xl flex items-center justify-center text-[#001144]/60 hover:text-[#FF7A00] hover:bg-orange-50 transition-all">
                        <i class="ph-bold ph-bell text-xl"></i>
                    </button>
                    <div class="h-8 w-[1px] bg-gray-100 mx-2"></div>
                    <div class="hidden sm:flex flex-col items-end">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ now()->translatedFormat('l, d M Y') }}</span>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 md:p-10 bg-[#f8faff] no-scrollbar">
                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- Modals portadas al body para evitar conflicto con overflow-hidden --}}
    @stack('modals')

    <!-- Script para funcionalidad del sidebar móvil -->
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