<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - CapacitaPerú</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        
        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .animate-gradient {
            background: linear-gradient(-45deg, #001144, #0033CC, #FF7A00, #001144);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .floating-input:focus ~ label, .floating-input:not(:placeholder-shown) ~ label {
            transform: translateY(-24px) scale(0.85);
            color: #FF7A00;
        }

        .tab-content { display: none; animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .tab-content.active { display: block; }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-premium {
            background: linear-gradient(90deg, #001144 0%, #0033CC 100%);
            transition: all 0.5s ease;
        }
        .btn-premium:hover {
            background: linear-gradient(90deg, #FF7A00 0%, #ff9500 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 122, 0, 0.3);
        }
    </style>
</head>
<body class="bg-[#001144] h-screen w-full overflow-hidden flex items-center justify-center relative">

    <!-- Fondo Animado Detrás -->
    <div class="absolute inset-0 z-0 animate-gradient opacity-40"></div>
    <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-[#FF7A00] opacity-10 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-[-10%] left-[-10%] w-[400px] h-[400px] bg-[#0033CC] opacity-20 rounded-full blur-[100px]"></div>

    <div class="max-w-6xl w-full mx-4 flex bg-white/5 backdrop-blur-xl rounded-[3rem] overflow-hidden shadow-[0_50px_100px_rgba(0,0,0,0.4)] border border-white/10 relative z-10">
        
        <!-- Lado Izquierdo: Visual Branding -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-[#001144] items-center justify-center p-20 overflow-hidden">
            <div class="absolute inset-0">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=2000&auto=format&fit=crop" class="w-full h-full object-cover opacity-30 grayscale hover:grayscale-0 transition-all duration-1000 scale-110">
                <div class="absolute inset-0 bg-gradient-to-br from-[#001144] to-transparent opacity-80"></div>
            </div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-10 group">
                    <div class="w-16 h-16 rounded-2xl bg-[#FF7A00] flex items-center justify-center text-white shadow-2xl shadow-orange-500/30 group-hover:rotate-12 transition-transform">
                        <i class="ph-bold ph-student text-3xl"></i>
                    </div>
                    <span class="font-black text-4xl text-white tracking-tighter">CAPACITAPERU</span>
                </div>
                <h2 class="text-5xl font-black text-white mb-8 leading-[1.1]">
                    Eleva tu <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FF7A00] to-orange-300">Potencial Profesional.</span>
                </h2>
                <p class="text-gray-400 text-xl font-medium leading-relaxed max-w-sm">
                    Accede a una experiencia educativa de primer nivel diseñada para el mercado laboral actual.
                </p>
                
                <!-- Badge de Confianza -->
                <div class="mt-16 flex items-center gap-4 p-4 rounded-3xl bg-white/5 border border-white/10 backdrop-blur-md w-fit">
                    <div class="flex -space-x-3">
                        <img src="https://i.pravatar.cc/100?u=1" class="w-10 h-10 rounded-full border-2 border-[#001144]">
                        <img src="https://i.pravatar.cc/100?u=2" class="w-10 h-10 rounded-full border-2 border-[#001144]">
                        <img src="https://i.pravatar.cc/100?u=3" class="w-10 h-10 rounded-full border-2 border-[#001144]">
                    </div>
                    <p class="text-white text-xs font-black uppercase tracking-widest">+5k Estudiantes</p>
                </div>
            </div>
        </div>

        <!-- Lado Derecho: Formulario Moderno -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 md:p-16 relative glass">
            <div class="w-full max-w-md">
                
                <div class="text-center mb-10">
                    <div class="lg:hidden flex items-center justify-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-[#FF7A00] flex items-center justify-center text-white">
                            <i class="ph-bold ph-student text-xl"></i>
                        </div>
                        <span class="font-black text-2xl text-[#001144] tracking-tighter uppercase">CapacitaPerú</span>
                    </div>
                    <h3 class="text-3xl font-black text-[#001144] mb-2">¡Bienvenido de nuevo!</h3>
                    <p class="text-gray-500 font-medium">Inicia sesión para continuar tu aprendizaje.</p>
                </div>

                <!-- TABS MODERNOS -->
                <div class="flex p-1.5 bg-gray-100 rounded-[1.5rem] mb-10 relative">
                    <div id="tab-bg" class="absolute left-1.5 top-1.5 bottom-1.5 w-[calc(50%-6px)] bg-white rounded-2xl shadow-xl transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)]"></div>
                    
                    <button onclick="switchTab('password')" class="flex-1 py-3.5 text-xs font-black uppercase tracking-[0.2em] text-[#001144] relative z-10 transition-colors" id="btn-password">
                        Contraseña
                    </button>
                    <button onclick="switchTab('pin')" class="flex-1 py-3.5 text-xs font-black uppercase tracking-[0.2em] text-gray-400 relative z-10 transition-colors" id="btn-pin">
                        PIN Rápido
                    </button>
                </div>

                @if ($errors->any())
                    <div class="mb-8 p-4 rounded-2xl bg-red-50 border border-red-100 flex gap-3 text-red-600 text-sm animate-shake">
                        <i class="ph-fill ph-warning-circle text-xl shrink-0"></i>
                        <div class="font-bold">{{ $errors->first() }}</div>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="login_type" id="login_type" value="password">

                    <!-- Email -->
                    <div class="relative group">
                        <input type="email" name="email" value="{{ old('email') }}" class="floating-input peer w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-6 py-4 text-[#001144] font-bold focus:outline-none focus:border-[#FF7A00] transition-all placeholder-transparent" placeholder="Email" required>
                        <label class="absolute left-6 top-4 text-gray-400 text-sm font-bold transition-all pointer-events-none peer-placeholder-shown:text-base peer-placeholder-shown:top-4 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-[#FF7A00] bg-white px-2">Correo Electrónico</label>
                        <i class="ph-bold ph-envelope absolute right-6 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-[#FF7A00] transition-colors"></i>
                    </div>

                    <!-- PASSWORD -->
                    <div id="tab-password" class="tab-content active relative group">
                        <input type="password" id="input-password" name="password" class="floating-input peer w-full bg-gray-50 border-2 border-gray-100 rounded-2xl pl-6 pr-20 py-4 text-[#001144] font-bold focus:outline-none focus:border-[#FF7A00] transition-all placeholder-transparent" placeholder="Pass">
                        <label class="absolute left-6 top-4 text-gray-400 text-sm font-bold transition-all pointer-events-none peer-placeholder-shown:text-base peer-placeholder-shown:top-4 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-[#FF7A00] bg-white px-2">Contraseña</label>
                        <button type="button" onclick="togglePasswordVisibility('input-password', 'eye-password')" class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#FF7A00] transition-colors focus:outline-none z-10">
                            <i id="eye-password" class="ph-bold ph-eye text-xl"></i>
                        </button>
                        <i class="ph-bold ph-lock-key absolute right-14 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-[#FF7A00] transition-colors"></i>
                    </div>

                    <!-- PIN -->
                    <div id="tab-pin" class="tab-content relative group">
                        <input type="password" id="input-pin" maxlength="4" class="floating-input peer w-full bg-gray-50 border-2 border-gray-100 rounded-2xl pl-6 pr-14 py-4 text-[#001144] focus:outline-none focus:border-[#FF7A00] transition-all placeholder-transparent tracking-[1.5em] text-center font-black text-xl" placeholder="PIN">
                        <label class="absolute left-0 right-0 text-center top-4 text-gray-400 text-sm font-bold transition-all pointer-events-none peer-placeholder-shown:text-base peer-placeholder-shown:top-4 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-[#FF7A00] bg-white w-fit mx-auto px-2">PIN Maestro (4 dígitos)</label>
                        <button type="button" onclick="togglePasswordVisibility('input-pin', 'eye-pin')" class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#FF7A00] transition-colors focus:outline-none z-10">
                            <i id="eye-pin" class="ph-bold ph-eye text-xl"></i>
                        </button>
                    </div>

                    <div class="flex justify-between items-center text-xs">
                        <label class="flex items-center gap-2 cursor-pointer text-gray-500 font-bold hover:text-[#001144] transition-colors">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-[#FF7A00] border-gray-200 rounded-lg focus:ring-[#FF7A00]"> Recordarme
                        </label>
                        <a href="https://wa.me/51972539712" target="_blank" class="text-[#FF7A00] font-black uppercase tracking-widest hover:underline">¿Necesitas Ayuda?</a>
                    </div>

                    <button type="submit" class="w-full py-5 rounded-2xl font-black text-white text-xs uppercase tracking-[0.3em] btn-premium shadow-2xl">
                        Ingresar Ahora
                    </button>
                    
                    <div class="pt-6 text-center">
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">
                            ¿No tienes cuenta? <a href="/nosotros" class="text-[#001144] hover:text-[#FF7A00]">Contáctanos</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(inputId, eyeId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.remove('ph-eye');
                eye.classList.add('ph-eye-slash');
            } else {
                input.type = 'password';
                eye.classList.remove('ph-eye-slash');
                eye.classList.add('ph-eye');
            }
        }

        function switchTab(type) {
            document.getElementById('login_type').value = type;
            const bg = document.getElementById('tab-bg');
            bg.style.transform = type === 'password' ? 'translateX(0)' : 'translateX(100%)';

            const btnPass = document.getElementById('btn-password');
            const btnPin = document.getElementById('btn-pin');
            
            if(type === 'password') {
                btnPass.classList.add('text-[#001144]'); btnPass.classList.remove('text-gray-400');
                btnPin.classList.add('text-gray-400'); btnPin.classList.remove('text-[#001144]');
                document.getElementById('tab-password').classList.add('active');
                document.getElementById('tab-pin').classList.remove('active');
                document.getElementById('input-password').name = 'password';
                document.getElementById('input-pin').name = '';
                document.getElementById('input-password').required = true;
                document.getElementById('input-pin').required = false;
            } else {
                btnPin.classList.add('text-[#001144]'); btnPin.classList.remove('text-gray-400');
                btnPass.classList.add('text-gray-400'); btnPass.classList.remove('text-[#001144]');
                document.getElementById('tab-pin').classList.add('active');
                document.getElementById('tab-password').classList.remove('active');
                document.getElementById('input-pin').name = 'password';
                document.getElementById('input-password').name = '';
                document.getElementById('input-pin').required = true;
                document.getElementById('input-password').required = false;
            }
        }
    </script>
</body>
</html>