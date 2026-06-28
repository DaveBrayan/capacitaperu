<x-layouts.guest>
    <div class="min-h-screen pt-32 pb-20 bg-gray-50">
        <div class="max-w-xl mx-auto px-4">
            
            <!-- Encabezado del Curso -->
            <div class="bg-[#213A57] rounded-t-3xl p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-[#0AD1C8] opacity-10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <h1 class="text-2xl font-bold mb-2">Completar Inscripción</h1>
                <p class="text-[#80ED99] text-sm flex items-center gap-2">
                    <i class="ph-bold ph-graduation-cap"></i>
                    {{ $course->title }}
                </p>
            </div>

            <!-- Formulario -->
            <div class="bg-white rounded-b-3xl shadow-xl border-x border-b border-gray-100 p-8">
                
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-xl text-sm border border-red-100 flex items-center gap-2">
                        <i class="ph-bold ph-warning-circle"></i> {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('enrollment.guest.process', $token) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" name="course_id" value="{{ $course->id }}">

                    <div class="space-y-4">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                            {{ $type === 'new' ? 'Información Personal' : 'Validar Cuenta Existente' }}
                        </p>

                        @if($type === 'new')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo</label>
                                <input type="text" name="name" required value="{{ old('name') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#0AD1C8] outline-none" placeholder="Juan Perez">
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                            <input type="email" name="email" required value="{{ old('email') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#0AD1C8] outline-none" placeholder="ejemplo@correo.com">
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-6">
                        <label class="block text-sm font-bold text-[#213A57] mb-3">Subir Comprobante de Pago</label>
                        <div class="relative group">
                            <input type="file" name="receipt_file" required accept="image/*" onchange="handleReceiptChange(this)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div id="receipt-upload-box" class="border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center group-hover:border-[#0AD1C8] transition-all bg-gray-50">
                                <i id="receipt-icon" class="ph-bold ph-cloud-arrow-up text-3xl text-gray-300 group-hover:text-[#0AD1C8]"></i>
                                <p id="receipt-text" class="text-xs text-gray-500 mt-2">Haz clic o arrastra tu foto aquí</p>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2 italic">* Formato JPG o PNG. Máx 4MB.</p>
                    </div>

                    <button type="submit" class="w-full py-4 bg-[#213A57] text-white font-bold rounded-2xl shadow-lg hover:bg-[#0B6477] transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        Finalizar Registro e Inscripción
                        <i class="ph-bold ph-arrow-right"></i>
                    </button>
                </form>

                <script>
                    function handleReceiptChange(input) {
                        const box = document.getElementById('receipt-upload-box');
                        const icon = document.getElementById('receipt-icon');
                        const text = document.getElementById('receipt-text');
                        
                        if (input.files && input.files[0]) {
                            const file = input.files[0];
                            // Mostrar el nombre del archivo seleccionado y cambiar el estado visual
                            text.innerHTML = `<span class="font-bold text-[#0B6477]">¡Archivo cargado!</span><br><span class="text-gray-400 font-mono text-[11px]">${file.name}</span>`;
                            
                            // Cambiar icono y clases de colores a verde/teal de éxito
                            icon.className = "ph-bold ph-check-circle text-3xl text-[#0AD1C8]";
                            box.className = "border-2 border-dashed border-[#0AD1C8] rounded-2xl p-6 text-center transition-all bg-emerald-50/50";
                        } else {
                            // Restaurar estado inicial
                            text.innerText = "Haz clic o arrastra tu foto aquí";
                            icon.className = "ph-bold ph-cloud-arrow-up text-3xl text-gray-300 group-hover:text-[#0AD1C8]";
                            box.className = "border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center group-hover:border-[#0AD1C8] transition-all bg-gray-50";
                        }
                    }
                </script>

                <p class="mt-8 text-center text-xs text-gray-400">
                    Al registrarte, aceptas nuestros <a href="#" class="underline">términos y condiciones</a>.
                </p>
            </div>
        </div>
    </div>
</x-layouts.guest>