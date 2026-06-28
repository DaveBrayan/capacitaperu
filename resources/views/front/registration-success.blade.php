<x-layouts.guest>
    <div class="min-h-screen pt-32 pb-20 bg-gray-50 flex items-center justify-center">
        <div class="max-w-lg w-full px-4 text-center">
            
            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-[#213A57]/10 p-10 relative overflow-hidden border border-gray-100">
                <!-- Decoración de fondo -->
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-[#80ED99]/20 rounded-full blur-3xl"></div>
                
                <!-- Icono de Éxito Animado -->
                <div class="mb-8 relative inline-block">
                    <div class="absolute inset-0 bg-[#0AD1C8]/20 rounded-full animate-ping scale-150 opacity-20"></div>
                    <div class="relative w-24 h-24 bg-gradient-to-tr from-[#0B6477] to-[#0AD1C8] rounded-full flex items-center justify-center text-white shadow-xl shadow-[#0AD1C8]/30">
                        <i class="ph-fill ph-check-circle text-5xl"></i>
                    </div>
                </div>

                <h1 class="text-3xl font-extrabold text-[#213A57] mb-4">¡Registro Exitoso!</h1>
                
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Hola <span class="font-bold text-[#0B6477]">{{ $data['name'] }}</span>, tu cuenta ha sido creada correctamente. 
                    Hemos recibido tu comprobante para el curso <br> 
                    <strong class="text-[#213A57] italic">"{{ $data['course'] }}"</strong>.
                </p>

                <!-- Credenciales Temporales de Acceso -->
                <div class="bg-[#FFFDF5] border border-amber-200 rounded-3xl p-6 mb-8 text-left relative overflow-hidden">
                    <div class="absolute -top-12 -right-12 w-24 h-24 bg-amber-100/30 rounded-full blur-2xl"></div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                            <i class="ph-bold ph-key text-xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-black text-[#213A57] uppercase tracking-wider mb-1">Credenciales de Acceso</p>
                            <p class="text-[11px] text-gray-500 mb-4">Úsalas para iniciar sesión por primera vez. Deberás cambiar tu contraseña y definir tu PIN maestro en el primer ingreso.</p>
                            
                            <div class="space-y-3 bg-white border border-gray-100 rounded-2xl p-4 shadow-sm relative">
                                <div class="flex items-center justify-between gap-2 border-b border-gray-50 pb-2">
                                    <div class="min-w-0">
                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Usuario (Email)</span>
                                        <span id="copy-email" class="text-xs font-mono font-bold text-[#213A57] break-all">{{ $data['email'] }}</span>
                                    </div>
                                    <button type="button" onclick="copyText('copy-email', this)" class="text-gray-400 hover:text-[#0AD1C8] transition-colors p-1.5 hover:bg-gray-50 rounded-lg">
                                        <i class="ph-bold ph-copy text-base"></i>
                                    </button>
                                </div>
                                <div class="flex items-center justify-between gap-2">
                                    <div class="min-w-0">
                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Contraseña Temporal</span>
                                        <span id="copy-pass" class="text-xs font-mono font-bold text-amber-600 break-all">{{ $data['password'] }}</span>
                                    </div>
                                    <button type="button" onclick="copyText('copy-pass', this)" class="text-gray-400 hover:text-[#0AD1C8] transition-colors p-1.5 hover:bg-gray-50 rounded-lg">
                                        <i class="ph-bold ph-copy text-base"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-[#F0FDFD] border border-[#0AD1C8]/20 rounded-2xl p-6 mb-8 text-left space-y-4">
                    <div class="flex gap-4">
                        <i class="ph-bold ph-shield-check text-[#0AD1C8] text-2xl shrink-0"></i>
                        <div>
                            <p class="text-sm font-bold text-[#213A57]">Pago en Revisión</p>
                            <p class="text-xs text-gray-500">Nuestro equipo validará tu boleta en un plazo máximo de 24 horas.</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <a href="{{ route('login') }}" class="w-full py-4 bg-[#213A57] text-white font-bold rounded-2xl shadow-lg shadow-[#213A57]/20 hover:bg-[#0B6477] transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2 text-lg">
                        Ir al Inicio de Sesión
                        <i class="ph-bold ph-sign-in"></i>
                    </a>
                </div>

                <script>
                    function copyText(elementId, btn) {
                        const text = document.getElementById(elementId).innerText;
                        navigator.clipboard.writeText(text).then(() => {
                            const icon = btn.querySelector('i');
                            icon.className = "ph-bold ph-check text-[#0AD1C8]";
                            setTimeout(() => {
                                icon.className = "ph-bold ph-copy text-base";
                            }, 2000);
                        });
                    }
                </script>

                <p class="mt-8 text-xs text-gray-400">
                    ¿Tienes dudas? <a href="{{ route('contact') }}" class="text-[#0AD1C8] font-bold hover:underline">Contáctanos vía WhatsApp</a>
                </p>
            </div>
            
            <p class="mt-6 text-sm text-gray-400 font-medium">CapacitaPerú • Aprende sin límites</p>
        </div>
    </div>
</x-layouts.guest>