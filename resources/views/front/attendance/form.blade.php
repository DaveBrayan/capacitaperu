<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Asistencia - CapacitaPerú</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-center p-4">

    <!-- Tarjeta Principal -->
    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 relative">
        
        <!-- Header -->
        <div class="bg-[#213A57] p-8 text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <div class="relative z-10">
                <span class="inline-block px-3 py-1 rounded-full bg-[#0AD1C8] text-[#213A57] text-xs font-bold uppercase tracking-wider mb-3">
                    Control de Asistencia
                </span>
                <h1 class="text-white text-xl font-bold mb-1">{{ $courseTitle }}</h1>
                <p class="text-blue-200 text-sm flex items-center justify-center gap-2">
                    <i class="ph-bold ph-calendar-check"></i> {{ $moduleTitle }}
                </p>
            </div>
        </div>

        <!-- Mensajes Flash -->
        @if(session('success'))
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ph-fill ph-check-circle text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-[#213A57] mb-2">¡Registrado!</h3>
                <p class="text-gray-500 text-sm">{{ session('success') }}</p>
                @if(Auth::guard('student')->check())
                <div class="mt-6">
                    <a href="{{ route('student.dashboard') }}" class="text-[#0AD1C8] font-bold text-sm hover:underline">Ir a mi Panel</a>
                </div>
                @endif
            </div>
        @elseif(session('info'))
             <div class="p-6 text-center">
                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ph-fill ph-info text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-[#213A57] mb-2">Ya Registrado</h3>
                <p class="text-gray-500 text-sm">{{ session('info') }}</p>
            </div>
        @else

            <!-- Formulario -->
            <form action="{{ route('attendance.store', ['courseSlug' => $course->slug, 'moduleId' => $topic->id]) }}" method="POST" class="p-8 space-y-6">
                @csrf
                
                @if($errors->any())
                    <div class="p-3 bg-red-50 text-red-600 text-xs rounded-lg border border-red-100">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Renderizado Dinámico de Preguntas -->
                <div class="space-y-5">
                    @if(isset($questions) && count($questions) > 0)
                        @foreach($questions as $index => $q)
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                <label class="block text-sm font-bold text-[#213A57] mb-3">
                                    {{ $index + 1 }}. {{ $q['title'] }}
                                </label>

                                @if($q['type'] === 'text')
                                    <textarea name="answers[{{ $index }}]" rows="2" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#0AD1C8] focus:ring-2 focus:ring-[#0AD1C8]/20 outline-none text-sm transition-all" placeholder="Tu respuesta..." required></textarea>
                                @elseif($q['type'] === 'choice')
                                    <div class="space-y-2">
                                        @foreach(explode(',', $q['options']) as $option)
                                            <label class="flex items-center gap-3 cursor-pointer group">
                                                <input type="radio" name="answers[{{ $index }}]" value="{{ trim($option) }}" class="w-5 h-5 text-[#0AD1C8] border-gray-300 focus:ring-[#0AD1C8]" required>
                                                <span class="text-sm text-gray-600 group-hover:text-[#213A57] transition-colors">{{ trim($option) }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-6 text-gray-400">
                            <i class="ph-duotone ph-clipboard-text text-4xl mb-2"></i>
                            <p class="text-sm">Solo registra tu identificación abajo para confirmar asistencia.</p>
                        </div>
                    @endif
                </div>

                <hr class="border-gray-100">

                <!-- Identificación -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Documento de Identidad (CI / DNI)</label>
                    <div class="relative">
                        <i class="ph-bold ph-identification-card absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input type="text" name="dni" required class="w-full pl-12 pr-4 py-4 rounded-xl border-2 border-gray-200 focus:border-[#0AD1C8] focus:ring-0 outline-none text-lg font-bold text-[#213A57] placeholder-gray-300 tracking-wide transition-colors" placeholder="Ej: 8345120">
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2 text-center">
                        <i class="ph-fill ph-lock-key"></i> Tus datos se registran de forma segura.
                    </p>
                </div>

                <button type="submit" class="w-full py-4 bg-[#213A57] hover:bg-[#0B6477] text-white font-bold rounded-xl shadow-lg shadow-[#213A57]/20 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                    Enviar Asistencia <i class="ph-bold ph-paper-plane-right"></i>
                </button>

            </form>
        @endif
    </div>
    
    <p class="mt-8 text-gray-400 text-xs">© {{ date('Y') }} CapacitaPerú</p>

</body>
</html>