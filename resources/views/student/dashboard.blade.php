<x-layouts.student>
    <x-slot name="title">Mi Aprendizaje - CapacitaPerú</x-slot>
    <x-slot name="header">Mis Cursos</x-slot>

    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        [x-cloak] { display: none !important; }
        .course-locked { filter: grayscale(1); opacity: 0.6; pointer-events: none; }
        
        .progress-glow {
            box-shadow: 0 0 15px rgba(255, 122, 0, 0.3);
        }

        .premium-card {
            background: white;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .premium-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0, 17, 68, 0.1);
        }
    </style>

    <!-- MENSAJES FLASH PREMIUM -->
    @if(session('success') || session('warning') || session('error'))
        @php
            $type = session('success') ? 'success' : (session('warning') ? 'warning' : 'error');
            $colors = [
                'success' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-100', 'icon' => 'ph-check-circle'],
                'warning' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-100', 'icon' => 'ph-warning'],
                'error' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-100', 'icon' => 'ph-x-circle']
            ][$type];
        @endphp
        <div class="mb-10 {{ $colors['bg'] }} {{ $colors['border'] }} {{ $colors['text'] }} p-6 rounded-[2rem] border-2 shadow-sm flex items-center gap-4 animate-fade-in-up">
            <i class="ph-bold {{ $colors['icon'] }} text-3xl"></i>
            <span class="font-bold text-sm tracking-tight">{{ session($type) }}</span>
        </div>
    @endif

    <div x-data="{ tab: 'all' }">

        <div class="mb-12 flex flex-col md:flex-row justify-between items-end gap-6 fade-in-up" style="animation-delay: 0.1s">
            <div>
                <span class="text-[#FF7A00] font-black text-[10px] uppercase tracking-[0.3em] mb-3 block">Mi Biblioteca Virtual</span>
                <h1 class="text-4xl font-black text-[#001144] tracking-tighter">
                    ¡Hola, {{ explode(' ', $student->name)[0] }}! 👋
                </h1>
                <p class="text-gray-500 mt-3 flex items-center gap-2 font-medium">
                    <i class="ph-bold ph-lightning text-[#FF7A00]"></i>
                    @if($inProgressCount > 0)
                        Tienes <span class="text-[#001144] font-bold">{{ $inProgressCount }} cursos</span> activos en tu formación.
                    @else
                        ¡Excelente! Has completado tus cursos actuales. 🎉
                    @endif
                </p>
            </div>

            <!-- TABS PREMIUM -->
            <div class="flex p-1.5 bg-white rounded-2xl shadow-sm border border-gray-100">
                <button @click="tab = 'all'" :class="tab === 'all' ? 'bg-[#001144] text-white shadow-lg' : 'text-gray-400 hover:text-[#001144]'" class="px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">Todo</button>
                <button @click="tab = 'progress'" :class="tab === 'progress' ? 'bg-[#001144] text-white shadow-lg' : 'text-gray-400 hover:text-[#001144]'" class="px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">En Curso</button>
                <button @click="tab = 'completed'" :class="tab === 'completed' ? 'bg-[#001144] text-white shadow-lg' : 'text-gray-400 hover:text-[#001144]'" class="px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all">Finalizados</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 pb-20">
            
            @forelse($enrollments as $enrollment)
                @php
                    $course = $enrollment->course;
                    $instructor = $course->instructor;
                    $isPending = $enrollment->status === 'pending';
                    $progress = $enrollment->calculated_progress;
                    $isCompleted = $enrollment->is_really_completed; 
                    $completedLessons = $enrollment->completed_lessons_count;
                    $totalLessons = $enrollment->total_lessons_count;
                    $statusKey = $isCompleted ? 'completed' : 'progress';
                    $myCertificate = $enrollment->my_certificate;
                @endphp

                <div x-show="tab === 'all' || tab === '{{ $statusKey }}'" x-cloak
                     class="premium-card rounded-[2.5rem] border border-gray-100 overflow-hidden flex flex-col {{ $isPending ? 'opacity-70 grayscale' : '' }}">
                    
                    <!-- IMAGEN CON OVERLAY -->
                    <div class="relative h-60 overflow-hidden">
                        @if($course->cover_image)
                            <img src="{{ asset($course->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center"><i class="ph-bold ph-image text-4xl text-gray-300"></i></div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-[#001144] via-[#001144]/20 to-transparent"></div>
                        
                        @if($isPending)
                            <div class="absolute top-6 right-6 bg-[#FF7A00] text-white text-[9px] font-black px-4 py-1.5 rounded-full shadow-2xl flex items-center gap-2 animate-pulse uppercase tracking-widest">
                                <i class="ph-bold ph-hourglass-high"></i> En Validación
                            </div>
                        @endif

                        <div class="absolute bottom-6 left-8 right-8">
                            <h3 class="text-white font-black text-xl leading-tight tracking-tighter line-clamp-2">{{ $course->title }}</h3>
                        </div>
                    </div>
                    
                    <div class="p-8 flex-1 flex flex-col">
                        @if($isPending)
                            <div class="mb-8 p-5 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200 text-center">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Acceso en Proceso</p>
                                <p class="text-xs text-gray-500 font-medium leading-relaxed">Estamos validando tu inscripción. Tendrás acceso muy pronto.</p>
                            </div>
                            <button disabled class="w-full py-5 bg-gray-100 text-gray-400 font-black rounded-2xl flex items-center justify-center gap-3 cursor-not-allowed text-[10px] uppercase tracking-widest">
                                <i class="ph-bold ph-lock-key text-lg"></i> Acceso Pendiente
                            </button>
                        @else
                            <div class="flex items-center gap-3 mb-8">
                                @if($instructor)
                                    <img src="{{ $instructor->avatar_url ? asset($instructor->avatar_url) : 'https://ui-avatars.com/api/?name='.urlencode($instructor->name).'&background=001144&color=fff' }}" class="w-10 h-10 rounded-xl object-cover border-2 border-gray-100 shadow-sm">
                                    <div class="overflow-hidden">
                                        <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest leading-none mb-1">Especialista</p>
                                        <p class="text-xs font-bold text-[#001144] truncate">{{ $instructor->name }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-8">
                                <div class="flex justify-between items-end mb-3">
                                    @if($isCompleted)
                                        <span class="text-emerald-600 font-black text-[10px] uppercase tracking-widest flex items-center gap-1.5"><i class="ph-fill ph-check-circle text-lg"></i> Finalizado</span>
                                        <span class="text-emerald-600 font-black text-xs">100%</span>
                                    @else
                                        <span class="text-[#001144] font-black text-[10px] uppercase tracking-widest">{{ $progress }}% Completado</span>
                                        <span class="text-[#FF7A00] font-black text-xs">{{ $completedLessons }}/{{ $totalLessons }}</span>
                                    @endif
                                </div>
                                <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden p-0.5">
                                    <div class="h-full rounded-full transition-all duration-1000 ease-out relative {{ $isCompleted ? 'bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.4)]' : 'bg-[#FF7A00] progress-glow' }}" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                            
                            <div class="mt-auto">
                                @if($isCompleted)
                                    <div class="grid grid-cols-2 gap-4">
                                        <a href="{{ route('classroom.show', ['course' => $course->slug]) }}" class="py-4 bg-gray-50 hover:bg-gray-100 text-[#001144] font-black rounded-2xl transition-all text-[10px] uppercase tracking-widest text-center shadow-sm">
                                            Repasar
                                        </a>
                                        @php
                                            $certConfig = $course->certificate_template ?? [];
                                            $releaseDate = isset($certConfig['release_date']) ? \Carbon\Carbon::parse($certConfig['release_date']) : null;
                                            $isReleased = ($certConfig['release_mode'] ?? 'completion') === 'completion' || ($releaseDate && now()->greaterThanOrEqualTo($releaseDate));
                                        @endphp

                                        @if(!$isReleased)
                                            <div class="py-3 px-2 bg-white border border-gray-100 text-gray-400 font-bold rounded-2xl text-[8px] uppercase tracking-tighter flex flex-col items-center justify-center leading-none text-center">
                                                <span>Disponible</span>
                                                <span class="mt-1 text-[#FF7A00]">{{ $releaseDate->format('d/m/Y') }}</span>
                                            </div>
                                        @elseif($myCertificate)
                                            <a href="{{ route('certificate.verify', $myCertificate->uuid) }}" target="_blank" class="py-4 bg-[#001144] text-white font-black rounded-2xl transition-all text-[10px] uppercase tracking-widest text-center shadow-lg shadow-blue-500/20">
                                                Ver Diploma
                                            </a>
                                        @else
                                            <a href="{{ route('student.certificate.claim', $course->id) }}" class="py-4 bg-[#FF7A00] text-white font-black rounded-2xl transition-all text-[10px] uppercase tracking-widest text-center shadow-lg shadow-orange-500/20 hover:bg-[#001144]">
                                                Certificado
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <a href="{{ route('classroom.show', ['course' => $course->slug]) }}" class="w-full py-5 bg-[#001144] hover:bg-[#FF7A00] text-white font-black rounded-2xl transition-all duration-500 shadow-xl hover:-translate-y-1 flex items-center justify-center gap-3 text-[10px] uppercase tracking-[0.2em]">
                                        {{ $progress > 0 ? 'Continuar Formación' : 'Iniciar Curso' }}
                                        <i class="ph-bold ph-arrow-right text-lg"></i>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 text-center bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
                    <div class="w-24 h-24 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-gray-300">
                        <i class="ph-duotone ph-student text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-[#001144] mb-3 tracking-tight">Aún no tienes cursos inscritos</h3>
                    <p class="text-gray-400 font-medium mb-10">Explora nuestra biblioteca y comienza tu desarrollo hoy.</p>
                    <a href="{{ route('catalog') }}" class="px-12 py-5 bg-[#FF7A00] text-white font-black rounded-2xl hover:bg-[#001144] transition-all duration-500 shadow-2xl uppercase tracking-widest text-xs">Ver Catálogo de Cursos</a>
                </div>
            @endforelse

        </div>
    </div>
</x-layouts.student>