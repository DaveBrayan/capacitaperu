<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar Cuenta - CapacitaPerú</title>
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

        .avatar-option {
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .avatar-option.selected {
            border-color: #FF7A00;
            background-color: #FFF5EC;
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 20px 40px rgba(255, 122, 0, 0.2);
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
    </style>
</head>
<body class="bg-[#001144] h-screen w-full overflow-hidden flex items-center justify-center relative">

    {{-- LÓGICA DE ESTADO --}}
    @php
        $user = Auth::user() ?? Auth::guard('student')->user();
        $isStudent = Auth::guard('student')->check();
        $missingProfile = $isStudent && (empty($user->ci_nit) || empty($user->phone));
        $missingPin = is_null($user->pin);
        $forcePassReset = $user->force_pass_reset ?? false;

        $showProfile = $missingProfile;
        $showPin = $missingPin;
        $showPassword = $forcePassReset || $missingProfile;

        $titleText = "Configura tu cuenta";
        $subtitleText = "Actualiza tus credenciales para continuar.";
        
        if ($showProfile) {
            $titleText = "Completa tu Perfil";
            $subtitleText = "Personaliza tu identidad y valida tus datos.";
        } elseif ($showPin && !$showPassword) {
            $titleText = "Configura tu PIN";
            $subtitleText = "Tu llave de acceso rápido y segura.";
        } elseif ($showPassword && !$showPin) {
            $titleText = "Cambia tu Contraseña";
            $subtitleText = "Protege tu cuenta con una clave robusta.";
        }
    @endphp

    <!-- Fondo Animado -->
    <div class="absolute inset-0 z-0 animate-gradient opacity-30"></div>

    <div class="max-w-5xl w-full mx-4 flex bg-white/5 backdrop-blur-xl rounded-[3rem] overflow-hidden shadow-[0_50px_100px_rgba(0,0,0,0.4)] border border-white/10 relative z-10 h-[90vh]">
        
        <!-- Lado Izquierdo: Contexto -->
        <div class="hidden lg:flex lg:w-1/3 relative bg-[#001144] items-center justify-center p-12 overflow-hidden border-r border-white/5">
            <div class="absolute inset-0">
                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=2000&auto=format&fit=crop" class="w-full h-full object-cover opacity-20">
                <div class="absolute inset-0 bg-gradient-to-br from-[#001144] to-transparent opacity-90"></div>
            </div>
            
            <div class="relative z-10 text-center">
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-[#FF7A00] to-orange-400 flex items-center justify-center text-white mx-auto mb-10 shadow-2xl">
                    <i class="ph-bold ph-shield-check text-4xl"></i>
                </div>
                <h2 class="text-3xl font-black text-white mb-6 leading-tight">
                    {!! str_replace(['Perfil', 'PIN', 'Contraseña'], ['<span class="text-[#FF7A00]">Perfil</span>', '<span class="text-[#FF7A00]">PIN</span>', '<span class="text-[#FF7A00]">Contraseña</span>'], $titleText) !!}
                </h2>
                <p class="text-gray-400 font-medium leading-relaxed">
                    {{ $subtitleText }}
                </p>
                
                <div class="mt-12 pt-12 border-t border-white/10">
                    <p class="text-[10px] text-gray-500 font-black uppercase tracking-[0.3em]">Seguridad CapacitaPerú</p>
                </div>
            </div>
        </div>

        <!-- Lado Derecho: Formulario Scrolleable -->
        <div class="w-full lg:w-2/3 flex flex-col bg-white overflow-y-auto no-scrollbar glass p-8 md:p-16">
            <div class="max-w-xl mx-auto w-full">
                
                <div class="mb-12">
                    <h3 class="text-3xl font-black text-[#001144] mb-2">Hola, {{ explode(' ', $user->name)[0] }} 👋</h3>
                    <p class="text-gray-500 font-medium italic">Estamos preparando todo para tu ingreso.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-10 p-5 rounded-3xl bg-red-50 border border-red-100 flex gap-3 text-red-600 text-sm animate-shake">
                        <i class="ph-fill ph-warning-circle text-2xl shrink-0"></i>
                        <ul class="font-bold list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('auth.setup.store') }}" method="POST" class="space-y-12 pb-10">
                    @csrf

                    <!-- SECCIÓN 1: IDENTIDAD -->
                    @if($showProfile)
                    <div class="animate-fade-in-up">
                        <div class="flex items-center gap-3 mb-8">
                            <span class="w-8 h-8 rounded-xl bg-orange-100 text-[#FF7A00] flex items-center justify-center font-black">1</span>
                            <h4 class="text-xl font-black text-[#001144]">Identidad Visual</h4>
                        </div>

                        <p class="text-xs text-gray-400 font-black uppercase tracking-widest mb-6 ml-11">Selecciona tu Avatar</p>
                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-4 ml-11 mb-12">
                            <input type="hidden" name="avatar" id="selected_avatar_input">
                            @if(isset($localAvatars))
                                @foreach($localAvatars as $path)
                                    <div onclick="selectAvatar('{{ asset($path) }}', this)" 
                                         class="avatar-option cursor-pointer aspect-square rounded-[1.5rem] border-2 border-transparent p-1">
                                        <img src="{{ asset($path) }}" class="w-full h-full rounded-[1.2rem] object-cover bg-gray-50">
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="flex items-center gap-3 mb-8">
                            <span class="w-8 h-8 rounded-xl bg-blue-100 text-[#0033CC] flex items-center justify-center font-black">2</span>
                            <h4 class="text-xl font-black text-[#001144]">Datos de Validación</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 ml-11">
                            <div class="relative group">
                                <input type="text" name="ci_nit" value="{{ old('ci_nit') }}" class="floating-input peer w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-6 py-4 text-[#001144] font-bold focus:outline-none focus:border-[#FF7A00] transition-all placeholder-transparent" placeholder="CI/DNI" required>
                                <label class="absolute left-6 top-4 text-gray-400 text-sm font-bold transition-all pointer-events-none peer-placeholder-shown:text-base peer-placeholder-shown:top-4 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-[#FF7A00] bg-white px-2">CI / DNI</label>
                            </div>

                            <div class="relative group">
                                <select name="country" id="country_select" class="peer w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-6 py-4 text-[#001144] font-bold focus:outline-none focus:border-[#FF7A00] appearance-none" required onchange="handleCountryChange()">
                                    <option value="" disabled selected>País</option>
                                </select>
                                <label class="absolute left-6 -top-2.5 text-xs text-[#FF7A00] font-bold bg-white px-2 transition-all">País</label>
                                <i class="ph-bold ph-globe-hemisphere-west absolute right-6 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            </div>

                            <div class="relative group">
                                <select name="city" id="state_select" class="peer w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-6 py-4 text-[#001144] font-bold focus:outline-none focus:border-[#FF7A00] appearance-none" required disabled>
                                    <option value="" disabled selected>Departamento</option>
                                </select>
                                <input type="text" id="state_input" class="hidden w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-6 py-4 text-[#001144] font-bold focus:outline-none focus:border-[#FF7A00]" placeholder="Escribe tu ciudad">
                                <label class="absolute left-6 -top-2.5 text-xs text-[#FF7A00] font-bold bg-white px-2 transition-all">Ciudad / Departamento</label>
                                <i class="ph-bold ph-caret-down absolute right-6 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            </div>

                            <div class="relative group">
                                <input type="text" name="phone" id="phone_input" value="{{ old('phone') }}" class="floating-input peer w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-6 py-4 text-[#001144] font-bold focus:outline-none focus:border-[#FF7A00] transition-all placeholder-transparent" placeholder="Celular" required>
                                <label class="absolute left-6 top-4 text-gray-400 text-sm font-bold transition-all pointer-events-none peer-placeholder-shown:text-base peer-placeholder-shown:top-4 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-[#FF7A00] bg-white px-2">WhatsApp</label>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- SECCIÓN 2: SEGURIDAD -->
                    @if($showPin || $showPassword)
                    <div class="animate-fade-in-up" style="animation-delay: 0.2s">
                        <div class="flex items-center gap-3 mb-8">
                            <span class="w-8 h-8 rounded-xl bg-orange-100 text-[#FF7A00] flex items-center justify-center font-black">3</span>
                            <h4 class="text-xl font-black text-[#001144]">Seguridad de Acceso</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 ml-11">
                            @if($showPin)
                            <div class="md:col-span-2 space-y-4">
                                <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100 relative">
                                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.2em] mb-4 text-center">Define tu PIN Maestro (4 Dígitos)</p>
                                    <div class="relative max-w-xs mx-auto">
                                        <input type="password" id="input-setup-pin" name="pin" maxlength="4" class="w-full bg-white border-2 border-gray-100 rounded-2xl pl-6 pr-14 py-4 text-[#001144] focus:outline-none focus:border-[#FF7A00] transition-all tracking-[2em] text-center font-black text-2xl shadow-inner" placeholder="----" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        <button type="button" onclick="togglePasswordVisibility('input-setup-pin', 'eye-setup-pin')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#FF7A00] transition-colors focus:outline-none z-10">
                                            <i id="eye-setup-pin" class="ph-bold ph-eye text-xl"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($showPassword)
                            <div class="relative group">
                                <input type="password" id="input-setup-password" name="password" class="floating-input peer w-full bg-gray-50 border-2 border-gray-100 rounded-2xl pl-6 pr-14 py-4 text-[#001144] font-bold focus:outline-none focus:border-[#FF7A00] transition-all placeholder-transparent" placeholder="Pass" required>
                                <label class="absolute left-6 top-4 text-gray-400 text-sm font-bold transition-all pointer-events-none peer-placeholder-shown:text-base peer-placeholder-shown:top-4 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-[#FF7A00] bg-white px-2">Nueva Contraseña</label>
                                <button type="button" onclick="togglePasswordVisibility('input-setup-password', 'eye-setup-password')" class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#FF7A00] transition-colors focus:outline-none z-10">
                                    <i id="eye-setup-password" class="ph-bold ph-eye text-xl"></i>
                                </button>
                            </div>

                            <div class="relative group">
                                <input type="password" id="input-setup-password-confirm" name="password_confirmation" class="floating-input peer w-full bg-gray-50 border-2 border-gray-100 rounded-2xl pl-6 pr-14 py-4 text-[#001144] font-bold focus:outline-none focus:border-[#FF7A00] transition-all placeholder-transparent" placeholder="Confirm" required>
                                <label class="absolute left-6 top-4 text-gray-400 text-sm font-bold transition-all pointer-events-none peer-placeholder-shown:text-base peer-placeholder-shown:top-4 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-[#FF7A00] bg-white px-2">Confirmar</label>
                                <button type="button" onclick="togglePasswordVisibility('input-setup-password-confirm', 'eye-setup-password-confirm')" class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#FF7A00] transition-colors focus:outline-none z-10">
                                    <i id="eye-setup-password-confirm" class="ph-bold ph-eye text-xl"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <button type="submit" class="w-full py-5 rounded-2xl font-black text-white text-xs uppercase tracking-[0.3em] bg-[#001144] hover:bg-[#FF7A00] transition-all duration-500 shadow-2xl flex items-center justify-center gap-3 transform hover:-translate-y-1">
                        Finalizar Configuración
                        <i class="ph-bold ph-check-circle text-xl"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function selectAvatar(url, element) {
            const urlObj = new URL(url);
            const relativePath = urlObj.pathname.substring(1); 
            document.getElementById('selected_avatar_input').value = relativePath;
            document.querySelectorAll('.avatar-option').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
        }

        const fallbackCountries = [
            { name: "Perú", dial_code: "+51" },
            { name: "Bolivia", dial_code: "+591" },
            { name: "Argentina", dial_code: "+54" },
            { name: "Chile", dial_code: "+56" },
            { name: "Colombia", dial_code: "+57" }
        ];

        document.addEventListener('DOMContentLoaded', async () => {
            const select = document.getElementById('country_select');
            if (select) await initLocationLogic(select);
        });

        async function initLocationLogic(select) {
            try {
                const response = await fetch('https://countriesnow.space/api/v0.1/countries/codes');
                const data = await response.json();
                if(!data.error && data.data) populateCountries(data.data, select);
                else throw new Error("API Error");
            } catch (error) {
                populateCountries(fallbackCountries, select);
            }
            await detectUserCountry(select);
        }

        function populateCountries(countriesData, select) {
            select.innerHTML = '<option value="" disabled selected>Selecciona tu país</option>';
            const peru = countriesData.find(c => c.name.toLowerCase().includes('peru'));
            if(peru) createOption(select, peru, 'Perú');
            
            countriesData.sort((a, b) => a.name.localeCompare(b.name)).forEach(c => {
                if(!c.name.toLowerCase().includes('peru')) createOption(select, c);
            });
        }

        function createOption(select, data, overrideName = null) {
            const opt = document.createElement('option');
            opt.value = overrideName || data.name;
            opt.innerText = overrideName || data.name;
            opt.setAttribute('data-dial', data.dial_code);
            select.appendChild(opt);
        }

        async function detectUserCountry(select) {
            try {
                const response = await fetch('https://ipapi.co/json/');
                const data = await response.json();
                select.value = data.country_name || 'Perú';
                handleCountryChange();
            } catch (e) {
                select.value = 'Perú';
                handleCountryChange();
            }
        }

        async function handleCountryChange() {
            const countrySelect = document.getElementById('country_select');
            const stateSelect = document.getElementById('state_select');
            const phoneInput = document.getElementById('phone_input');
            const country = countrySelect.value;
            const selectedOpt = countrySelect.options[countrySelect.selectedIndex];

            if(selectedOpt && selectedOpt.getAttribute('data-dial')) {
                const code = selectedOpt.getAttribute('data-dial');
                phoneInput.value = code + ' ';
            }

            stateSelect.disabled = true;
            stateSelect.innerHTML = '<option disabled selected>Cargando...</option>';

            try {
                const response = await fetch('https://countriesnow.space/api/v0.1/countries/states', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ country: country })
                });
                const data = await response.json();
                stateSelect.innerHTML = '<option value="" disabled selected>Departamento</option>';
                stateSelect.disabled = false;

                if (!data.error && data.data.states.length > 0) {
                    data.data.states.forEach(s => {
                        const opt = document.createElement('option');
                        opt.value = s.name; opt.innerText = s.name;
                        stateSelect.appendChild(opt);
                    });
                } else { toggleManualInput(true); }
            } catch (e) { toggleManualInput(true); }
        }

        function toggleManualInput(isManual) {
            const stateSelect = document.getElementById('state_select');
            const stateInput = document.getElementById('state_input');
            if (isManual) {
                stateSelect.classList.add('hidden'); 
                stateSelect.removeAttribute('name');
                stateSelect.removeAttribute('required');
                
                stateInput.classList.remove('hidden'); 
                stateInput.setAttribute('name', 'city');
                stateInput.setAttribute('required', 'required');
            } else {
                stateSelect.classList.remove('hidden'); 
                stateSelect.setAttribute('name', 'city');
                stateSelect.setAttribute('required', 'required');
                
                stateInput.classList.add('hidden'); 
                stateInput.removeAttribute('name');
                stateInput.removeAttribute('required');
            }
        }

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
    </script>
</body>
</html>