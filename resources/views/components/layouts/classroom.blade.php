<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $courseTitle ?? 'Aula Virtual' }} | CapacitaPerú</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #000814; color: #e2e8f0; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #001144; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #FF7A00; border-radius: 10px; }
        
        .premium-shadow { box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5); }
        .nav-active { border-left: 4px solid #FF7A00; background: rgba(255, 122, 0, 0.1); color: white !important; }
        
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.6s ease-out forwards; }
    </style>
</head>
<body class="h-full overflow-hidden">
    <div class="flex h-full" x-data="{ mobileMenu: false }">
        <!-- SIDEBAR -->
        <aside class="hidden lg:flex flex-col w-96 bg-[#000d2b] border-r border-white/5 relative z-40 premium-shadow">
            <div class="p-8 border-b border-white/5 bg-[#001144]/50 backdrop-blur-md sticky top-0">
                <div class="flex items-center gap-4 mb-6">
                    <a href="{{ route('student.dashboard') }}" class="w-10 h-10 bg-white/10 hover:bg-[#FF7A00] text-white rounded-xl flex items-center justify-center transition-all group">
                        <i class="ph-bold ph-arrow-left text-xl group-hover:scale-110"></i>
                    </a>
                    <a href="{{ route('home') }}" class="flex items-center group">
                        <img src="{{ asset($platformSettings['app_logo_white'] ?? 'img/logo-white.png') }}" alt="{{ $platformSettings['app_name'] ?? 'CapacitaPerú' }}" class="h-14 w-auto group-hover:scale-110 transition-transform">
                    </a>
                </div>

                <div class="bg-black/20 p-4 rounded-2xl border border-white/5">
                    <h2 class="text-sm font-black text-white leading-snug line-clamp-2">{{ $courseTitle }}</h2>
                </div>
            </div>

            <div class="flex-1 overflow-hidden flex flex-col">
                {{ $sidebar }}
            </div>

            <!-- FOOTER SIDEBAR -->
            <div class="p-6 border-t border-white/5 bg-black/20">
                <div class="flex items-center gap-3">
                    <img src="{{ auth('student')->user()->avatar_url ? asset(auth('student')->user()->avatar_url) : 'https://ui-avatars.com/api/?name='.urlencode(auth('student')->user()->name).'&background=FF7A00&color=fff' }}" class="w-10 h-10 rounded-xl object-cover border border-white/10">
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-white truncate">{{ auth('student')->user()->name }}</p>
                        <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest">Estudiante</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col h-full overflow-hidden bg-black relative">
            <!-- BACKGROUND GLOW EFFECTS -->
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#FF7A00]/5 blur-[150px] -z-10 rounded-full"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-[#001144]/10 blur-[150px] -z-10 rounded-full"></div>

            <div class="flex-1 overflow-hidden relative">
                {{ $slot }}
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>