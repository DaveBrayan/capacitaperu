<x-layouts.student>
    <x-slot name="title">Mi Perfil - CapacitaPerú</x-slot>
    <x-slot name="header">Configuración de Cuenta</x-slot>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        .avatar-option {
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .avatar-option.selected { 
            border-color: #FF7A00; 
            transform: scale(1.1); 
            background-color: #FFF5EC; 
            box-shadow: 0 10px 20px rgba(255, 122, 0, 0.2); 
        }

        .glass-card { 
            background: rgba(255, 255, 255, 0.98); 
            backdrop-filter: blur(20px); 
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .gradient-border {
            position: relative;
            border-radius: 2rem;
            padding: 1.5px;
            background: linear-gradient(135deg, #FF7A00, #001144);
        }
        .gradient-border > div {
            background: white;
            border-radius: calc(2rem - 1.5px);
        }
    </style>

    <!-- MENSAJES FLASH FLOTANTES -->
    @if(session('success'))
        <div class="fixed top-24 right-8 z-50 bg-[#001144] text-white px-8 py-5 rounded-[2rem] shadow-2xl flex items-center gap-4 animate-fade-in-up border border-white/10 backdrop-blur-xl">
            <div class="bg-[#FF7A00] p-2.5 rounded-2xl shadow-lg shadow-orange-500/20">
                <i class="ph-bold ph-check text-xl"></i>
            </div>
            <div>
                <p class="font-black text-sm uppercase tracking-widest">¡Actualizado!</p>
                <p class="text-xs text-gray-300 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="h-[calc(100vh-140px)] flex flex-col xl:flex-row gap-8">
        
        <!-- 1. TARJETA DE RESUMEN (IZQUIERDA) -->
        <div class="xl:w-1/4 bg-white rounded-[2.5rem] p-0 shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-gray-100 flex flex-col h-full overflow-hidden relative group">
            
            <!-- Fondo Decorativo Brand -->
            <div class="absolute top-0 w-full h-40 bg-[#001144] overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-[#FF7A00] opacity-20 rounded-full blur-3xl -mr-16 -mt-16"></div>
                <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-[#001144] to-transparent"></div>
            </div>
            
            <!-- Contenido Scrollable -->
            <div class="flex-1 overflow-y-auto no-scrollbar relative z-10 flex flex-col p-8 pt-20 items-center text-center">
                
                <!-- Avatar Premium -->
                <div class="relative mb-6 group/avatar cursor-pointer" onclick="openAvatarModal()">
                    <div class="w-40 h-40 rounded-[2.5rem] p-1.5 bg-white shadow-2xl overflow-hidden mx-auto relative z-10 transform group-hover/avatar:-translate-y-2 transition-all duration-500">
                        <div class="w-full h-full rounded-[2.2rem] overflow-hidden border-4 border-gray-50">
                            <img src="{{ Str::startsWith($student->avatar_url, 'http') ? $student->avatar_url : asset($student->avatar_url) }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover/avatar:scale-110">
                        </div>
                    </div>
                    <div class="absolute -bottom-2 -right-2 z-20 bg-[#FF7A00] text-white p-3 rounded-2xl shadow-xl hover:scale-110 transition-all border-4 border-white">
                        <i class="ph-bold ph-camera text-xl"></i>
                    </div>
                </div>
                
                <h2 class="text-3xl font-black text-[#001144] leading-tight mb-2 tracking-tighter">{{ $student->name }}</h2>
                <div class="flex items-center gap-2 mb-8">
                    <span class="w-2 h-2 rounded-full bg-[#FF7A00] animate-pulse"></span>
                    <span class="text-[10px] text-gray-400 font-black uppercase tracking-[0.2em]">Estudiante Premium</span>
                </div>
                
                <!-- Estadísticas Estilo Moderno -->
                <div class="w-full grid grid-cols-2 gap-4 mb-10">
                    <div class="bg-gray-50 p-5 rounded-[2rem] border border-gray-100 group hover:border-[#FF7A00]/30 transition-all">
                        <span class="block text-3xl font-black text-[#001144] mb-1">{{ $student->enrollments->count() }}</span>
                        <span class="text-[9px] text-gray-400 uppercase font-black tracking-widest">Mis Cursos</span>
                    </div>
                    <div class="bg-[#001144] p-5 rounded-[2rem] shadow-xl shadow-blue-900/10 group hover:scale-105 transition-all">
                        <span class="block text-3xl font-black text-[#FF7A00] mb-1">0</span>
                        <span class="text-[9px] text-white/40 uppercase font-black tracking-widest">Diplomas</span>
                    </div>
                </div>

                <!-- Info Cards Rapidas -->
                <div class="w-full space-y-3">
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100 group/item hover:bg-white transition-all">
                        <div class="w-10 h-10 rounded-xl bg-white text-[#001144] flex items-center justify-center shadow-sm group-hover/item:text-[#FF7A00]">
                            <i class="ph-bold ph-envelope text-lg"></i>
                        </div>
                        <div class="text-left min-w-0">
                            <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">Email</p>
                            <p class="font-bold text-[#001144] text-xs truncate">{{ $student->email }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100 group/item hover:bg-white transition-all">
                        <div class="w-10 h-10 rounded-xl bg-white text-[#001144] flex items-center justify-center shadow-sm group-hover/item:text-[#FF7A00]">
                            <i class="ph-bold ph-whatsapp-logo text-lg"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">Celular</p>
                            <p class="font-bold text-[#001144] text-xs">{{ $student->phone ?? '--' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. DATOS PERSONALES (CENTRO) -->
        <div class="xl:w-2/5 bg-white rounded-[2.5rem] p-10 shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-gray-100 flex flex-col h-full relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-[#001144] via-[#FF7A00] to-[#001144] opacity-80"></div>
            
            <div class="flex justify-between items-end mb-10">
                <div>
                    <span class="text-[#FF7A00] font-black text-[10px] uppercase tracking-[0.3em] mb-2 block">Mi Información</span>
                    <h3 class="text-3xl font-black text-[#001144] flex items-center gap-3 tracking-tighter">
                        Configurar Perfil
                    </h3>
                </div>
            </div>

            <form id="personalForm" action="{{ route('student.profile.update') }}" method="POST" class="flex-1 flex flex-col gap-8 overflow-y-auto pr-4 no-scrollbar">
                @csrf
                @method('PUT')
                <input type="hidden" name="avatar" id="avatar_input_hidden">

                <!-- Nombre -->
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nombre Completo</label>
                    <div class="relative group">
                        <i class="ph-bold ph-user absolute left-5 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-[#FF7A00] transition-colors"></i>
                        <input type="text" name="name" value="{{ old('name', $student->name) }}" class="w-full pl-14 pr-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl focus:bg-white focus:border-[#FF7A00] outline-none transition-all font-bold text-[#001144]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">CI / DNI</label>
                        <div class="relative group">
                            <i class="ph-bold ph-identification-badge absolute left-5 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-[#FF7A00] transition-colors"></i>
                            <input type="text" name="ci_nit" value="{{ old('ci_nit', $student->ci_nit) }}" class="w-full pl-14 pr-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl focus:bg-white focus:border-[#FF7A00] outline-none transition-all font-bold text-[#001144]">
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Celular</label>
                        <div class="relative group">
                            <i class="ph-bold ph-phone absolute left-5 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-[#FF7A00] transition-colors"></i>
                            <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="w-full pl-14 pr-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl focus:bg-white focus:border-[#FF7A00] outline-none transition-all font-bold text-[#001144]">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">País</label>
                        <div class="relative group">
                            <i class="ph-bold ph-globe absolute left-5 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-[#FF7A00] transition-colors"></i>
                            <input type="text" name="country" value="{{ old('country', $student->country ?? 'Perú') }}" class="w-full pl-14 pr-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl focus:bg-white focus:border-[#FF7A00] outline-none transition-all font-bold text-[#001144]">
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Ciudad</label>
                        <div class="relative group">
                            <i class="ph-bold ph-map-pin absolute left-5 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-[#FF7A00] transition-colors"></i>
                            <input type="text" name="city" value="{{ old('city', $student->city) }}" class="w-full pl-14 pr-6 py-4 bg-gray-50 border-2 border-gray-50 rounded-2xl focus:bg-white focus:border-[#FF7A00] outline-none transition-all font-bold text-[#001144]">
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Bio Académica</label>
                    <textarea name="bio" rows="4" class="w-full p-6 bg-gray-50 border-2 border-gray-50 rounded-[2rem] focus:bg-white focus:border-[#FF7A00] outline-none transition-all font-bold text-[#001144] resize-none" placeholder="Cuéntanos un poco sobre tu perfil profesional...">{{ old('bio', $student->bio) }}</textarea>
                </div>

                <div class="mt-auto pt-6">
                    <button type="submit" class="w-full py-5 bg-[#001144] text-white font-black rounded-2xl hover:bg-[#FF7A00] transition-all duration-500 shadow-xl hover:-translate-y-1 uppercase tracking-[0.2em] text-xs">
                        Actualizar Información
                    </button>
                </div>
            </form>
        </div>

        <!-- 3. SEGURIDAD (DERECHA) -->
        <div class="xl:w-1/3 flex flex-col gap-8 h-full overflow-y-auto no-scrollbar">
            
            <!-- Tarjeta Seguridad Brand -->
            <div class="bg-white rounded-[2.5rem] p-10 shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-gray-100 relative overflow-hidden">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-2xl font-black text-[#001144] flex items-center gap-3">
                        Seguridad
                    </h3>
                    <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-[#FF7A00]">
                        <i class="ph-bold ph-shield-check text-2xl"></i>
                    </div>
                </div>

                <form action="{{ route('student.profile.security') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div class="p-6 bg-gray-50 rounded-[2rem] border border-gray-100 space-y-4">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Cambiar Contraseña</p>
                            <input type="password" name="current_password" placeholder="Clave Actual" class="w-full px-6 py-4 rounded-xl border-2 border-white focus:border-[#FF7A00] outline-none bg-white transition-all font-bold text-sm">
                            <input type="password" name="new_password" placeholder="Nueva Clave" class="w-full px-6 py-4 rounded-xl border-2 border-white focus:border-[#FF7A00] outline-none bg-white transition-all font-bold text-sm">
                            <input type="password" name="new_password_confirmation" placeholder="Repetir Nueva" class="w-full px-6 py-4 rounded-xl border-2 border-white focus:border-[#FF7A00] outline-none bg-white transition-all font-bold text-sm">
                        </div>

                        <div class="p-6 bg-[#001144] rounded-[2rem] shadow-xl text-center">
                            <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-4">PIN Maestro (4 Dígitos)</p>
                            <input type="password" name="pin" maxlength="4" class="w-full px-6 py-4 rounded-xl border-2 border-white/10 focus:border-[#FF7A00] outline-none bg-white/5 text-white tracking-[1.5em] text-center font-black text-2xl shadow-inner" placeholder="••••" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-5 border-2 border-[#001144] text-[#001144] hover:bg-[#001144] hover:text-white font-black rounded-2xl transition-all duration-500 uppercase tracking-[0.2em] text-[10px]">
                        Guardar Credenciales
                    </button>
                </form>
            </div>

            <!-- PIN Info Moderno -->
            <div class="bg-gradient-to-br from-[#FF7A00] to-orange-600 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl shadow-orange-500/20 group">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-md">
                            <i class="ph-bold ph-lightning text-2xl"></i>
                        </div>
                        <h4 class="font-black text-xl tracking-tighter uppercase">Llave Rápida</h4>
                    </div>
                    <p class="text-sm text-white/80 font-medium leading-relaxed mb-6">
                        Usa tu **PIN Maestro** para firmar tu asistencia de forma instantánea y acceder sin escribir tu contraseña.
                    </p>
                    <div class="h-1 w-full bg-white/20 rounded-full overflow-hidden">
                        <div class="h-full bg-white w-2/3"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL AVATAR BRANDED -->
    <div id="avatar-modal" class="fixed inset-0 z-50 hidden bg-[#001144]/90 backdrop-blur-xl flex items-center justify-center p-6 animate-fade-in" onclick="closeAvatarModal(event)">
        <div class="bg-white rounded-[3rem] w-full max-w-4xl p-12 shadow-[0_50px_100px_rgba(0,0,0,0.5)] relative transform transition-all scale-100" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-10 border-b border-gray-100 pb-8">
                <div>
                    <h3 class="text-4xl font-black text-[#001144] tracking-tighter">Mi Identidad</h3>
                    <p class="text-gray-400 font-medium mt-1">Selecciona el avatar que mejor te represente</p>
                </div>
                <button onclick="closeAvatarModal()" class="w-14 h-14 rounded-2xl bg-gray-50 hover:bg-red-50 text-gray-400 hover:text-red-500 flex items-center justify-center transition-all">
                    <i class="ph-bold ph-x text-2xl"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-8 gap-6 max-h-[50vh] overflow-y-auto p-2 no-scrollbar">
                @if(isset($localAvatars))
                    @foreach($localAvatars as $path)
                        <div onclick="selectAvatar('{{ asset($path) }}', this)" 
                             class="avatar-option cursor-pointer aspect-square rounded-[2rem] border-4 border-transparent p-1">
                            <img src="{{ asset($path) }}" class="w-full h-full rounded-[1.8rem] object-cover bg-gray-50 shadow-sm">
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="mt-12 flex justify-end">
                <button onclick="submitAvatarChange()" class="px-12 py-5 bg-[#001144] text-white rounded-2xl font-black hover:bg-[#FF7A00] transition-all duration-500 shadow-2xl flex items-center gap-3 uppercase tracking-widest text-xs">
                    Confirmar Cambio
                    <i class="ph-bold ph-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        function openAvatarModal() { document.getElementById('avatar-modal').classList.remove('hidden'); }
        function closeAvatarModal(e) { 
            if (e && e.target !== e.currentTarget && e.target.closest('.bg-white')) return; 
            document.getElementById('avatar-modal').classList.add('hidden'); 
        }
        function selectAvatar(url, element) {
            document.querySelectorAll('.avatar-option').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
            const urlObj = new URL(url);
            document.getElementById('avatar_input_hidden').value = urlObj.pathname.substring(1);
        }
        function submitAvatarChange() { document.getElementById('personalForm').submit(); }
    </script>
</x-layouts.student>