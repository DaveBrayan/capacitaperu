<x-layouts.classroom>
    <x-slot name="courseTitle">{{ $course->title }}</x-slot>
    
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        [x-cloak] { display: none !important; }
        @media (min-width: 1024px) {
            aside { width: 22rem !important; } 
            main { flex: 1; min-width: 0; }
        }
        .prose table { width: 100%; border-collapse: collapse; margin: 1em 0; }
        .prose th, .prose td { border: 1px solid #1e293b; padding: 12px; text-align: left; }
        .prose th { background-color: #001144; color: white; font-weight: 800; text-transform: uppercase; font-size: 0.75rem; }
        .prose tr:nth-child(even) { background-color: rgba(255,255,255,0.02); }
    </style>

    <!-- --- LÓGICA DE CÁLCULO GLOBAL --- -->
    @php
        $evalConfig = $course->evaluation_config ?? [];
        $minScore = $evalConfig['min_score'] ?? 51;
        $attWeight = $evalConfig['attendance_weight'] ?? 20;
        $examWeight = $evalConfig['final_assessment_weight'] ?? 80;
        
        $isExamEnabled = $evalConfig['final_exam_enabled'] ?? false;
        $isProjectEnabled = $evalConfig['final_project_enabled'] ?? false;
        $hasExam = $isExamEnabled || $isProjectEnabled;

        $attPoints = ($attendancePercent * $attWeight) / 100;
        $rawScore = 0;      
        $maxPossible = 100; 

        if ($isProjectEnabled) {
            $rawScore = $projectSubmission->score ?? 0;
            $maxPossible = $evalConfig['final_project_data']['score'] ?? 100;
        } elseif ($isExamEnabled) {
            $rawScore = $finalExamScore ?? 0;
            $maxPossible = $examTotalPoints ?? 100;
        }
        if ($maxPossible <= 0) $maxPossible = 100;

        $examPoints = ($rawScore / $maxPossible) * $examWeight;
        $finalTotalScore = round($attPoints + $examPoints, 2);

        $hasAttempted = ($isExamEnabled && $finalExamScore !== null) || 
                        ($isProjectEnabled && $projectSubmission && $projectSubmission->status !== 'pending');

        $passedScore = $finalTotalScore >= $minScore;
        $isApproved = $passedScore; 

        $canGetCertificate = $hasExam ? $passedScore : ($progress == 100);

        $certificate = \App\Models\Certificate::where('student_id', auth('student')->id())
                        ->where('course_id', $course->id)
                        ->first();
        
        $certUrl = route('student.certificate.claim', $course->id);
        
        $certConfig = $course->certificate_template ?? [];
        $releaseMode = $certConfig['release_mode'] ?? 'completion';
        $releaseDate = isset($certConfig['release_date']) ? \Carbon\Carbon::parse($certConfig['release_date']) : null;
        $isDateReleased = $releaseMode === 'completion' || ($releaseDate && now()->greaterThanOrEqualTo($releaseDate));
        
        $showModal = $canGetCertificate && session('success');
        $canDownload = $canGetCertificate && $isDateReleased;
    @endphp

    <!-- MENSAJES FLASH -->
    @if(session('success'))
        <div class="fixed top-20 right-8 z-50 bg-[#FF7A00] text-white px-8 py-5 rounded-[2rem] shadow-2xl flex items-center gap-4 animate-bounce-in border border-white/20">
            <i class="ph-bold ph-check-circle text-3xl"></i>
            <div><p class="font-black text-xs uppercase tracking-widest">¡Logrado!</p><p class="text-sm font-bold">{{ session('success') }}</p></div>
        </div>
    @endif

    <!-- 1. BARRA DE TÍTULO Y NAVEGACIÓN -->
    <div class="px-8 py-5 border-b border-white/5 bg-[#001144]/80 backdrop-blur-xl flex flex-col md:flex-row justify-between items-center gap-6 sticky top-0 z-30">
        <div class="flex-1">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-6 bg-[#FF7A00] rounded-full"></div>
                <h2 class="text-xl font-black text-white leading-tight tracking-tight">
                    @if(request('mode') === 'planning' || (!request('lesson') && request('mode') !== 'final'))
                        Inicio del Curso
                    @elseif(request('mode') === 'final')
                        Evaluación Final de Certificación
                    @else
                        {{ $currentLesson->title }}
                    @endif
                </h2>
            </div>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.2em] mt-1.5 ml-4">
                @if(request('mode') === 'planning' || (!request('lesson') && request('mode') !== 'final'))
                    CapacitaPerú • Planificación
                @elseif(request('mode') === 'final')
                    CapacitaPerú • Cierre del Curso
                @else
                    {{ $currentLesson->topic->title ?? 'Módulo General' }}
                @endif
            </p>
        </div>
        
        <div class="flex items-center gap-3 shrink-0">
            @if(request('mode') !== 'planning' && request('mode') !== 'final' && request('lesson'))
                @if($prevLesson)
                    <a href="{{ route('classroom.show', ['course' => $course->slug, 'lesson' => $prevLesson->slug ?? $prevLesson->id]) }}" 
                       class="px-5 py-3 rounded-2xl border border-white/10 text-gray-400 hover:text-white hover:bg-white/5 transition-all flex items-center gap-2 text-[10px] font-black uppercase tracking-widest">
                        <i class="ph-bold ph-caret-left text-base"></i> Anterior
                    </a>
                @endif

                @if($nextLesson)
                    <a href="{{ route('classroom.show', ['course' => $course->slug, 'lesson' => $nextLesson->slug ?? $nextLesson->id]) }}" 
                       class="px-6 py-3 rounded-2xl bg-[#FF7A00] hover:bg-[#ff8c24] text-white font-black transition-all flex items-center gap-2 text-[10px] uppercase tracking-widest shadow-lg shadow-[#FF7A00]/20">
                        Siguiente <i class="ph-bold ph-caret-right text-base"></i>
                    </a>
                @else
                    @if($hasExam)
                        <a href="?mode=final" class="px-6 py-3 rounded-2xl bg-[#FF7A00] hover:bg-[#ff8c24] text-white font-black flex items-center gap-2 text-[10px] uppercase tracking-widest shadow-lg">
                            Evaluación Final <i class="ph-bold ph-star"></i>
                        </a>
                    @endif
                @endif
            @endif
        </div>
    </div>

    <!-- 2. CONTENIDO PRINCIPAL -->
    <div class="p-8 lg:p-12 overflow-y-auto h-[calc(100vh-100px)] custom-scrollbar">
        
        <!-- --- MODO: PLANIFICACIÓN --- -->
        @if(request('mode') === 'planning' || (!request('lesson') && request('mode') !== 'final'))
            <div class="max-w-4xl mx-auto space-y-12 animate-fade-in-up">
                
                @if(!empty($course->metadata['whatsapp_group']))
                <div class="bg-gradient-to-br from-[#25D366]/20 to-[#075E54]/20 rounded-[2.5rem] p-10 border border-[#25D366]/30 flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden group shadow-2xl">
                    <div class="absolute -right-12 -top-12 text-[#25D366]/10 group-hover:scale-110 transition-transform duration-1000 rotate-12">
                        <i class="ph-fill ph-whatsapp-logo text-[12rem]"></i>
                    </div>
                    
                    <div class="relative z-10 text-center md:text-left">
                        <h3 class="text-3xl font-black text-white mb-3 flex items-center justify-center md:justify-start gap-3">
                            <i class="ph-bold ph-chats-circle text-[#25D366]"></i> Comunidad WhatsApp
                        </h3>
                        <p class="text-gray-300 text-sm max-w-lg leading-relaxed font-medium">
                            Únete al grupo oficial para interactuar con docentes y compañeros. Recibe material exclusivo y noticias de CapacitaPerú.
                        </p>
                    </div>
                    <a href="{{ $course->metadata['whatsapp_group'] }}" target="_blank" class="relative z-10 px-10 py-5 bg-[#25D366] text-white font-black rounded-2xl shadow-xl hover:bg-[#1ebc5a] hover:shadow-[#25D366]/20 transition-all transform hover:-translate-y-1 flex items-center gap-3 whitespace-nowrap text-xs uppercase tracking-widest">
                        <i class="ph-fill ph-whatsapp-logo text-2xl"></i>
                        Unirme al Grupo
                    </a>
                </div>
                @endif

                <div class="bg-white/5 rounded-[3rem] border border-white/5 p-10 shadow-2xl backdrop-blur-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-10 opacity-5">
                        <i class="ph-bold ph-calendar-check text-[10rem] text-white"></i>
                    </div>
                    
                    <div class="border-b border-white/5 pb-6 mb-10 flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-[#FF7A00]/10 flex items-center justify-center text-[#FF7A00]">
                            <i class="ph-bold ph-calendar-check text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-white tracking-tight">Cronograma de Estudios</h3>
                            <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest mt-1">Organización Académica</p>
                        </div>
                    </div>
                    
                    @if(!empty($course->metadata['planning']))
                        <div class="prose prose-invert prose-lg max-w-none text-gray-300 leading-relaxed font-medium">
                            {!! $course->metadata['planning'] !!}
                        </div>
                    @else
                        <div class="text-center py-20 text-gray-500 bg-white/5 rounded-[2rem] border border-dashed border-white/10">
                            <i class="ph-duotone ph-calendar-x text-5xl mb-4 block opacity-30"></i>
                            <p class="font-bold text-sm uppercase tracking-widest">Contenido en preparación</p>
                        </div>
                    @endif

                    @php
                        $firstTopic = $course->topics->first();
                        $firstLesson = $firstTopic ? $firstTopic->lessons->first() : null;
                    @endphp
                    
                    @if($firstLesson)
                    <div class="mt-12 pt-10 border-t border-white/5 flex justify-center">
                        <a href="{{ route('classroom.show', ['course' => $course->slug, 'lesson' => $firstLesson->slug ?? $firstLesson->id]) }}" 
                           class="px-12 py-6 bg-[#FF7A00] hover:bg-[#001144] text-white font-black text-sm rounded-[2rem] shadow-2xl transition-all transform hover:-translate-y-1 flex items-center gap-3 uppercase tracking-[0.2em]">
                            Iniciar formación ahora <i class="ph-bold ph-arrow-right text-lg"></i>
                        </a>
                    </div>
                    @endif
                </div>
            </div>

        <!-- --- MODO: EVALUACIÓN FINAL --- -->
        @elseif(request('mode') === 'final')

            <div class="max-w-4xl mx-auto space-y-12">
                @if($hasAttempted)
                    <div class="p-16 text-center bg-white/5 rounded-[3.5rem] border border-white/5 shadow-2xl relative overflow-hidden" x-data="{ showCertModal: {{ $showModal ? 'true' : 'false' }} }">
                        <div class="absolute inset-0 bg-gradient-to-b from-[#FF7A00]/5 to-transparent"></div>
                        
                        <div class="w-28 h-28 {{ $isApproved ? 'bg-gradient-to-br from-emerald-400 to-emerald-600 shadow-emerald-500/20' : 'bg-gradient-to-br from-red-400 to-red-600 shadow-red-500/20' }} rounded-full flex items-center justify-center mx-auto mb-8 shadow-2xl border-8 border-black relative z-10">
                            <i class="ph-fill {{ $isApproved ? 'ph-medal' : 'ph-x-circle' }} text-6xl text-white"></i>
                        </div>

                        <h2 class="text-4xl font-black text-white mb-3 tracking-tighter relative z-10">{{ $isApproved ? '¡Curso Finalizado con Éxito!' : 'Evaluación No Superada' }}</h2>
                        <p class="text-gray-400 font-medium mb-10 relative z-10">Tu esfuerzo académico ha sido registrado en CapacitaPerú.</p>
                        
                        <div class="max-w-md mx-auto bg-black/40 rounded-[2.5rem] p-10 border border-white/5 mt-6 mb-12 relative z-10 shadow-xl">
                            <div class="flex justify-between items-center mb-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">
                                <span>Asistencia Académica ({{ $attendancePercent }}%)</span>
                                <span class="text-white">+{{ round($attPoints, 1) }} pts</span>
                            </div>
                            
                            <div class="flex justify-between items-center mb-6 text-[10px] font-black text-gray-500 uppercase tracking-widest border-b border-white/5 pb-4">
                                <span>Evaluación Final ({{ $rawScore }}/{{ $maxPossible }})</span>
                                <span class="text-white">+{{ round($examPoints, 1) }} pts</span>
                            </div>
                            
                            <div class="flex justify-between items-center text-3xl font-black tracking-tighter">
                                <span class="text-gray-400 text-lg">Nota Final</span>
                                <span class="{{ $isApproved ? 'text-[#FF7A00]' : 'text-red-400' }}">{{ round($finalTotalScore) }} <span class="text-xs text-gray-600">/ 100</span></span>
                            </div>
                        </div>

                        <div class="flex flex-col items-center gap-6 relative z-10">
                            @if($canDownload)
                                <a href="{{ $certUrl }}" target="_blank" class="px-12 py-6 bg-[#FF7A00] text-white font-black rounded-2xl shadow-xl shadow-orange-500/20 transition-all flex items-center gap-3 uppercase tracking-widest text-xs hover:-translate-y-1">
                                    <i class="ph-bold ph-certificate text-2xl"></i> Ver Mi Diploma Digital
                                </a>
                            @elseif($isApproved && !$isDateReleased)
                                <div class="px-8 py-4 bg-blue-500/10 border border-blue-500/20 text-blue-300 rounded-2xl text-[10px] font-black uppercase tracking-widest flex items-center gap-3">
                                    <i class="ph-bold ph-calendar-check text-xl text-[#FF7A00]"></i> 
                                    Disponible el {{ $releaseDate->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
                                </div>
                            @endif
                            
                            <a href="{{ route('student.dashboard') }}" class="text-gray-500 hover:text-white font-black text-[10px] uppercase tracking-widest flex items-center gap-2 transition-all">
                                <i class="ph-bold ph-house"></i> Volver al Dashboard Estudiantil
                            </a>
                        </div>

                        <!-- Modal Felicidades (Cerrado por defecto) -->
                        <div x-show="showCertModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-6">
                            <div class="absolute inset-0 bg-black/95 backdrop-blur-md" @click="showCertModal = false" x-transition.opacity></div>
                            <div class="bg-white rounded-[3rem] p-12 max-w-sm w-full relative z-10 text-center shadow-2xl" 
                                 x-transition:enter="transition ease-out duration-500"
                                 x-transition:enter-start="opacity-0 scale-90 translate-y-10"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                                <div class="w-24 h-24 bg-emerald-50 rounded-[2rem] flex items-center justify-center mx-auto mb-8">
                                    <i class="ph-duotone ph-confetti text-6xl text-emerald-500"></i>
                                </div>
                                <h3 class="text-3xl font-black text-[#001144] mb-3 tracking-tighter">¡Lo Lograste!</h3>
                                <p class="text-gray-500 text-sm mb-10 leading-relaxed font-medium">
                                    Has culminado satisfactoriamente tu programa académico en CapacitaPerú.
                                    <br>
                                    <span class="font-black text-[#FF7A00] text-3xl block mt-4 tracking-tighter">{{ round($finalTotalScore) }} pts</span>
                                </p>
                                <div class="space-y-4">
                                    @if($canDownload)
                                        <a href="{{ $certUrl }}" target="_blank" class="block w-full py-5 bg-[#001144] text-white font-black rounded-2xl hover:bg-[#FF7A00] transition-all shadow-xl uppercase tracking-widest text-xs">
                                            Descargar Diploma
                                        </a>
                                    @else
                                        <div class="p-5 bg-gray-50 text-gray-500 text-[10px] font-black uppercase tracking-widest rounded-2xl border border-gray-100 leading-relaxed">
                                            Tu diploma estará listo el <br>
                                            <span class="text-[#001144]">{{ $releaseDate ? $releaseDate->locale('es')->isoFormat('D [de] MMMM') : 'Próximamente' }}</span>
                                        </div>
                                    @endif
                                    <button @click="showCertModal = false" class="block w-full py-4 text-gray-400 font-black text-[10px] uppercase tracking-widest hover:text-[#001144]">
                                        Cerrar Ventana
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    
                    <!-- EXAMEN FINAL -->
                    @if($isExamEnabled)
                        @php
                            $eData = $evalConfig['final_exam_data'] ?? [];
                            $examQuestions = $eData['questions'] ?? [];
                            $eStartDate = isset($eData['start_date']) && $eData['start_date'] ? \Carbon\Carbon::parse($eData['start_date']) : null;
                            $eEndDate = isset($eData['end_date']) && $eData['end_date'] ? \Carbon\Carbon::parse($eData['end_date']) : null;
                            $now = now()->subHours(4);
                            $eIsBefore = $eStartDate && $now->lessThan($eStartDate);
                            $eIsAfter = $eEndDate && $now->greaterThan($eEndDate);
                            $eIsEnabled = !$eIsBefore && !$eIsAfter;
                        @endphp
                        <div class="bg-white/5 rounded-[3.5rem] border border-white/5 overflow-hidden shadow-2xl" x-data="{ examStarted: false }">
                            <div x-show="!examStarted">
                                <div class="bg-gradient-to-br from-[#001144] to-[#000814] p-16 text-center border-b border-white/5">
                                    <div class="w-20 h-20 bg-[#FF7A00] rounded-[1.5rem] flex items-center justify-center mx-auto mb-8 shadow-xl shadow-orange-500/20">
                                        <i class="ph-duotone ph-exam text-4xl text-white"></i>
                                    </div>
                                    <h2 class="text-3xl font-black text-white mb-4 tracking-tighter">Examen Final de Suficiencia</h2>
                                    <p class="text-gray-400 text-sm max-w-xl mx-auto font-medium">Cuestionario oficial de certificación. Tienes <b>un solo intento</b> para registrar tu nota final.</p>
                                </div>
                                <div class="p-16">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                                        <div class="bg-black/20 p-6 rounded-2xl border border-white/5 text-center">
                                            <p class="text-[9px] text-gray-500 uppercase font-black tracking-widest mb-2">Apertura del Portal</p>
                                            <p class="text-white font-bold text-sm">{{ $eStartDate ? $eStartDate->locale('es')->isoFormat('D [de] MMMM YYYY') : 'Inmediato' }}</p>
                                        </div>
                                        <div class="bg-black/20 p-6 rounded-2xl border border-white/5 text-center">
                                            <p class="text-[9px] text-gray-500 uppercase font-black tracking-widest mb-2">Cierre de Convocatoria</p>
                                            <p class="text-white font-bold text-sm">{{ $eEndDate ? $eEndDate->locale('es')->isoFormat('D [de] MMMM YYYY') : 'Sin fecha límite' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-center">
                                        @if($eIsEnabled)
                                            <button @click="examStarted = true" class="px-12 py-6 bg-[#FF7A00] text-white font-black rounded-2xl shadow-xl shadow-orange-500/20 transition-all hover:scale-105 flex items-center gap-3 uppercase tracking-widest text-xs">
                                                Comenzar Evaluación <i class="ph-bold ph-arrow-right"></i>
                                            </button>
                                        @elseif($eIsBefore)
                                            <div class="p-6 bg-[#FF7A00]/10 border border-[#FF7A00]/20 rounded-2xl text-[#FF7A00] font-bold text-sm uppercase tracking-widest">Disponible el {{ $eStartDate->locale('es')->isoFormat('D [de] MMMM') }}</div>
                                        @elseif($eIsAfter)
                                            <div class="p-6 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-400 font-bold text-sm uppercase tracking-widest">Convocatoria Finalizada</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div x-show="examStarted" x-cloak class="bg-white/5 animate-fade-in-up">
                                <div class="bg-black/40 p-8 border-b border-white/5 flex justify-between items-center">
                                    <h3 class="font-black text-white text-lg tracking-tight"><i class="ph-bold ph-pencil-circle text-[#FF7A00]"></i> Resolución de Preguntas</h3>
                                    <span class="bg-[#FF7A00] text-white text-[10px] font-black px-4 py-2 rounded-full uppercase tracking-widest">{{ count($examQuestions) }} Items</span>
                                </div>
                                <form action="{{ route('classroom.final.exam', $course->slug) }}" method="POST" class="p-12 space-y-12">
                                    @csrf
                                    @foreach($examQuestions as $index => $q)
                                        <div class="space-y-6">
                                            <p class="text-white font-bold text-lg leading-relaxed">
                                                <span class="text-[#FF7A00] mr-3">{{ $index + 1 }}.</span> {{ $q['title'] ?? $q['text'] }}
                                                <span class="text-[10px] text-gray-500 block mt-2 uppercase tracking-widest">Valor: {{ $q['points'] ?? 0 }} puntos</span>
                                            </p>
                                            <div class="ml-8 space-y-3">
                                                @if(in_array($q['type'], ['single', 'multiple']))
                                                    @foreach(explode(',', $q['options']) as $opt)
                                                        <label class="flex items-center gap-4 p-5 rounded-2xl border border-white/5 bg-black/20 hover:bg-white/5 hover:border-[#FF7A00]/30 cursor-pointer transition-all group">
                                                            <input type="{{ $q['type'] === 'single' ? 'radio' : 'checkbox' }}" name="q_{{ $index }}{{ $q['type'] === 'multiple' ? '[]' : '' }}" value="{{ trim($opt) }}" class="text-[#FF7A00] focus:ring-[#FF7A00] bg-black border-white/10 w-5 h-5">
                                                            <span class="text-sm text-gray-300 group-hover:text-white font-medium">{{ trim($opt) }}</span>
                                                        </label>
                                                    @endforeach
                                                @else
                                                    <textarea name="q_{{ $index }}" rows="4" class="w-full bg-black/40 border border-white/5 rounded-2xl p-6 text-white focus:border-[#FF7A00] focus:ring-0 transition-all font-medium" placeholder="Escribe tu respuesta analítica aquí..."></textarea>
                                                @endif
                                            </div>
                                        </div>
                                        @if(!$loop->last) <div class="border-t border-white/5"></div> @endif
                                    @endforeach
                                    <div class="pt-10 border-t border-white/5 flex justify-end">
                                        <button type="submit" class="px-12 py-6 bg-gradient-to-r from-[#FF7A00] to-[#ff8c24] text-white font-black rounded-2xl shadow-2xl uppercase tracking-widest text-xs hover:scale-105 transition-all">Finalizar y Entregar Evaluación</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- TRABAJO FINAL -->
                    @if($isProjectEnabled)
                        @php
                            $pData = $evalConfig['final_project_data'] ?? [];
                            $pStartDate = isset($pData['start_date']) && $pData['start_date'] ? \Carbon\Carbon::parse($pData['start_date']) : null;
                            $pEndDate = isset($pData['end_date']) && $pData['end_date'] ? \Carbon\Carbon::parse($pData['end_date']) : null;
                            $now = now()->subHours(4);
                            $pIsBefore = $pStartDate && $now->lessThan($pStartDate);
                            $pIsAfter = $pEndDate && $now->greaterThan($pEndDate);
                            $pIsEnabled = !$pIsBefore && !$pIsAfter;
                            $maxFiles = $pData['max_files'] ?? 1;
                            $maxSize = $pData['max_file_size'] ?? 5;
                            $fileTypes = $pData['file_types'] ?? [];
                            $accept = '';
                            if(in_array('pdf', $fileTypes)) $accept .= '.pdf,';
                            if(in_array('images', $fileTypes)) $accept .= 'image/*,';
                            if(in_array('zip', $fileTypes)) $accept .= '.zip,.rar,';
                            if(in_array('docx', $fileTypes)) $accept .= '.doc,.docx,';
                        @endphp

                        <div class="bg-white/5 rounded-[3.5rem] border border-white/5 overflow-hidden shadow-2xl mt-12">
                            <div class="bg-gradient-to-br from-[#1e293b] to-black p-16 text-center border-b border-white/5">
                                <div class="w-20 h-20 bg-[#001144] rounded-[1.5rem] flex items-center justify-center mx-auto mb-8 shadow-xl border border-white/10">
                                    <i class="ph-duotone ph-briefcase text-4xl text-[#FF7A00]"></i>
                                </div>
                                <h2 class="text-3xl font-black text-white mb-4 tracking-tighter">Entrega de Proyecto Aplicativo</h2>
                                <p class="text-gray-400 text-sm max-w-xl mx-auto font-medium">Sube los archivos que demuestran tus competencias adquiridas en este curso.</p>
                            </div>

                            <div class="p-16">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                                    <div class="bg-black/20 p-8 rounded-2xl border border-white/5 flex items-center justify-between">
                                        <div><p class="text-[9px] text-gray-500 uppercase font-black tracking-widest mb-2">Inicio de Recepción</p><p class="text-white font-bold text-sm">{{ $pStartDate ? $pStartDate->locale('es')->isoFormat('D [de] MMMM YYYY') : 'Abierto' }}</p></div>
                                        <i class="ph-bold ph-calendar-check text-[#FF7A00] text-3xl opacity-50"></i>
                                    </div>
                                    <div class="bg-black/20 p-8 rounded-2xl border border-white/5 flex items-center justify-between">
                                        <div><p class="text-[9px] text-gray-500 uppercase font-black tracking-widest mb-2">Fecha Límite</p><p class="text-white font-bold text-sm">{{ $pEndDate ? $pEndDate->locale('es')->isoFormat('D [de] MMMM YYYY') : 'Indefinido' }}</p></div>
                                        <i class="ph-bold ph-calendar-x text-[#FF7A00] text-3xl opacity-50"></i>
                                    </div>
                                </div>

                                <div class="bg-white/5 rounded-3xl p-10 border border-white/5 mb-12 relative overflow-hidden">
                                    <h4 class="font-black text-white text-lg mb-6 flex items-center gap-3 uppercase tracking-tight"><i class="ph-bold ph-info text-[#FF7A00]"></i> Especificaciones del Proyecto</h4>
                                    <div class="prose prose-invert prose-sm max-w-none text-gray-400 leading-relaxed font-medium">{!! $pData['instructions'] ?? 'No se han definido instrucciones adicionales.' !!}</div>
                                    @if(isset($evalConfig['project_file']))
                                        @php 
                                            $guidePath = str_replace('storage/', '', $evalConfig['project_file']);
                                            $guideUrl = route('media.show', ['filename' => $guidePath]);
                                        @endphp
                                        <div class="mt-8 pt-8 border-t border-white/5">
                                            <a href="{{ $guideUrl }}" target="_blank" class="inline-flex items-center gap-3 px-8 py-4 bg-[#001144] hover:bg-[#FF7A00] text-white text-xs font-black rounded-xl transition-all border border-white/10 uppercase tracking-widest shadow-xl">
                                                <i class="ph-bold ph-file-doc text-xl"></i> Descargar Material de Referencia
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                @if($pIsEnabled)
                                    <form action="{{ route('classroom.final.project', $course->slug) }}" method="POST" enctype="multipart/form-data" class="bg-black/40 rounded-3xl p-10 border border-white/10 shadow-2xl">
                                        @csrf
                                        <label class="block text-xs font-black text-gray-400 mb-6 flex items-center gap-3 uppercase tracking-widest">
                                            <i class="ph-bold ph-upload-simple text-[#FF7A00] text-xl"></i> Área de Carga de Archivos
                                        </label>
                                        <div class="relative group">
                                            <input type="file" name="files[]" 
                                                   class="block w-full text-sm text-gray-400 file:mr-6 file:py-4 file:px-8 file:rounded-2xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-[#FF7A00] file:text-white hover:file:bg-[#001144] cursor-pointer border border-white/10 rounded-2xl bg-black/40 focus:outline-none focus:border-[#FF7A00] transition-all p-2"
                                                   {{ $maxFiles > 1 ? 'multiple' : '' }}
                                                   accept="{{ $accept }}">
                                        </div>
                                        <div class="mt-6 flex flex-wrap gap-4 text-[9px] text-gray-500 font-black uppercase tracking-widest px-4">
                                            <span class="flex items-center gap-2"><i class="ph-bold ph-files text-[#FF7A00]"></i> Máximo {{ $maxFiles }} Archivo(s)</span>
                                            <span class="flex items-center gap-2"><i class="ph-bold ph-hard-drives text-[#FF7A00]"></i> Límite {{ $maxSize }}MB</span>
                                            <span class="flex items-center gap-2"><i class="ph-bold ph-file-text text-[#FF7A00]"></i> Formatos: {{ empty($fileTypes) ? 'Abierto' : implode(', ', $fileTypes) }}</span>
                                        </div>
                                        <div class="mt-10 flex justify-end">
                                            <button type="submit" class="px-12 py-6 bg-[#FF7A00] text-white font-black rounded-2xl shadow-xl transition-all flex items-center gap-3 uppercase tracking-widest text-xs hover:scale-105">
                                                <i class="ph-bold ph-paper-plane-right text-lg"></i> Enviar Proyecto para Calificación
                                            </button>
                                        </div>
                                    </form>
                                @elseif($pIsBefore)
                                    <div class="p-10 bg-white/5 border border-white/5 rounded-[2rem] text-gray-400 text-center font-bold uppercase tracking-widest text-xs"><i class="ph-bold ph-clock text-3xl mb-4 block text-[#FF7A00] opacity-50"></i> Recepción habilitada el {{ $pStartDate->locale('es')->isoFormat('D [de] MMMM') }}.</div>
                                @elseif($pIsAfter)
                                    <div class="p-10 bg-red-500/10 border border-red-500/20 rounded-[2rem] text-red-400 text-center font-bold uppercase tracking-widest text-xs"><i class="ph-bold ph-x-circle text-3xl mb-4 block opacity-50"></i> Periodo de entrega finalizado.</div>
                                @endif
                            </div>
                        </div>
                    @endif

                @endif
            </div>

        <!-- --- MODO: VIDEO --- -->
        @elseif($currentLesson->type === 'video')
             <div class="max-w-6xl mx-auto mb-12">
                <div class="bg-black w-full aspect-video rounded-[2.5rem] shadow-[0_30px_100px_rgba(0,0,0,0.8)] relative overflow-hidden border border-white/5 group ring-8 ring-white/5">
                    @if($currentLesson->video_url)
                        @php
                            $videoId = Str::afterLast($currentLesson->video_url, '/');
                            if(Str::contains($videoId, 'watch?v=')) $videoId = Str::after($videoId, 'watch?v=');
                        @endphp
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $videoId }}?rel=0&modestbranding=1" title="Video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-600 bg-[#001144]/10">
                            <i class="ph-bold ph-video-camera-slash text-6xl mb-4"></i>
                            <p class="font-black uppercase tracking-widest text-xs">Video no disponible en este momento</p>
                        </div>
                    @endif
                </div>
                <div class="mt-8 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-[#FF7A00] animate-pulse"></div>
                        <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">En reproducción</span>
                    </div>
                    @php $isLessonCompleted = in_array($currentLesson->id, $completedLessonIds ?? []); @endphp
                    @if($isLessonCompleted)
                         <span class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full text-[10px] font-black uppercase tracking-widest"><i class="ph-fill ph-check-circle text-lg"></i> Clase Completada</span>
                    @else
                         <span class="inline-flex items-center gap-2 px-6 py-2.5 bg-white/5 text-gray-500 border border-white/5 rounded-full text-[10px] font-black uppercase tracking-widest opacity-60"><i class="ph-bold ph-clock text-lg"></i> Pendiente de Revisión</span>
                    @endif
                </div>
            </div>
            <div class="max-w-6xl mx-auto">
                <div class="border-b border-white/5 mb-8 flex gap-8">
                    <button class="pb-4 border-b-2 border-[#FF7A00] text-white font-black text-xs uppercase tracking-widest">Descripción Académica</button>
                    <button class="pb-4 border-b-2 border-transparent text-gray-500 font-black text-xs uppercase tracking-widest hover:text-gray-300 transition-colors">Notas y Recursos</button>
                </div>
                <div class="prose prose-invert prose-lg max-w-none text-gray-400 leading-relaxed font-medium">
                    {!! $currentLesson->content !!}
                </div>
            </div>

        <!-- --- MODO: QUIZ --- -->
        @elseif($currentLesson->type === 'quiz')
            @php
                $quizData = $currentLesson->attachments ?? [];
                $questions = $quizData['questions'] ?? [];
            @endphp
            <div class="max-w-3xl mx-auto">
                <div class="bg-white/5 rounded-[3rem] border border-white/5 overflow-hidden shadow-2xl backdrop-blur-md">
                    <div class="bg-black/40 p-10 border-b border-white/5 flex justify-between items-center">
                        <h3 class="font-black text-white text-xl tracking-tight"><i class="ph-bold ph-list-checks text-[#FF7A00]"></i> Evaluación de Módulo</h3>
                        <span class="bg-[#FF7A00] text-white text-[10px] font-black px-5 py-2.5 rounded-full uppercase tracking-widest">{{ count($questions) }} Items</span>
                    </div>
                    <form action="{{ route('classroom.quiz.submit', [$course->slug, $currentLesson->slug ?? $currentLesson->id]) }}" method="POST" class="p-10 space-y-10">
                        @csrf
                        @foreach($questions as $index => $q)
                            <div class="space-y-4">
                                <p class="text-white font-bold text-lg leading-relaxed flex gap-4">
                                    <span class="text-[#FF7A00] font-black">{{ $index + 1 }}.</span> {{ $q['title'] ?? $q['text'] }}
                                </p>
                                <div class="ml-10 space-y-3">
                                    @if(in_array($q['type'], ['single', 'multiple']))
                                        @foreach(explode(',', $q['options']) as $opt)
                                            <label class="flex items-center gap-4 p-5 rounded-2xl border border-white/5 bg-black/20 hover:bg-white/10 hover:border-[#FF7A00]/30 cursor-pointer transition-all group">
                                                <input type="{{ $q['type'] === 'single' ? 'radio' : 'checkbox' }}" name="q_{{ $index }}[]" value="{{ trim($opt) }}" class="text-[#FF7A00] bg-black border-white/10 focus:ring-[#FF7A00] w-5 h-5">
                                                <span class="text-sm text-gray-300 font-medium group-hover:text-white">{{ trim($opt) }}</span>
                                            </label>
                                        @endforeach
                                    @else
                                        <textarea name="q_{{ $index }}" rows="4" class="w-full bg-black/40 border border-white/5 rounded-2xl p-6 text-white focus:border-[#FF7A00] focus:ring-0 font-medium placeholder-gray-600" placeholder="Redacta tu respuesta..."></textarea>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <div class="pt-10 border-t border-white/5 flex justify-end">
                            <button type="submit" class="px-12 py-6 bg-[#FF7A00] text-white font-black rounded-2xl shadow-xl uppercase tracking-widest text-xs hover:bg-[#001144] transition-all">Enviar Evaluación</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <!-- 3. SIDEBAR (INYECTADO EN EL LAYOUT) -->
    <x-slot name="sidebar">
        <div class="p-8 border-b border-white/5 bg-[#001144]/30 backdrop-blur-sm relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-20 h-20 bg-[#FF7A00]/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-1000"></div>
            
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Progreso Académico</h4>
                <span class="text-[#FF7A00] font-black text-sm tracking-tighter">{{ $progress }}%</span>
            </div>
            <div class="w-full h-2 bg-white/5 rounded-full overflow-hidden mb-8">
                <div class="h-full bg-gradient-to-r from-[#FF7A00] to-[#ff8c24] rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(255,122,0,0.3)]" style="width: {{ $progress }}%"></div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                @php
                    $matLink = $course->resources['materials'] ?? '#';
                    $presLink = $course->resources['presentations'] ?? '#';
                    $hasMat = !empty($course->resources['materials']);
                    $hasPres = !empty($course->resources['presentations']);
                @endphp
                <a href="{{ $hasMat ? $matLink : '#' }}" target="_blank" class="flex flex-col items-center justify-center gap-2 p-4 rounded-2xl border {{ $hasMat ? 'border-white/10 bg-white/5 hover:bg-[#FF7A00]/10 hover:border-[#FF7A00]/30' : 'border-white/5 bg-black/20 opacity-30 cursor-not-allowed' }} transition-all group">
                    <i class="ph-fill ph-folder-open {{ $hasMat ? 'text-[#FF7A00]' : 'text-gray-600' }} text-2xl group-hover:scale-110 transition-transform"></i>
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Materiales</span>
                </a>
                <a href="{{ $hasPres ? $presLink : '#' }}" target="_blank" class="flex flex-col items-center justify-center gap-2 p-4 rounded-2xl border {{ $hasPres ? 'border-white/10 bg-white/5 hover:bg-[#FF7A00]/10 hover:border-[#FF7A00]/30' : 'border-white/5 bg-black/20 opacity-30 cursor-not-allowed' }} transition-all group">
                    <i class="ph-fill ph-presentation-chart {{ $hasPres ? 'text-purple-400' : 'text-gray-600' }} text-2xl group-hover:scale-110 transition-transform"></i>
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Recursos</span>
                </a>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto custom-scrollbar" x-data="{ activeTopic: {{ $currentLesson->topic_id ?? 'null' }} }">
            @php $isPlanning = request('mode') === 'planning' || (!request('lesson') && request('mode') !== 'final'); @endphp
            <a href="?mode=planning" class="flex items-center gap-4 p-6 border-b border-white/5 transition-all {{ $isPlanning ? 'bg-white/5 border-l-4 border-l-[#FF7A00]' : 'hover:bg-white/5 border-l-4 border-l-transparent' }}">
                <div class="shrink-0 w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center"><i class="ph-fill ph-house {{ $isPlanning ? 'text-[#FF7A00]' : 'text-gray-600' }} text-xl"></i></div>
                <div class="flex-1 overflow-hidden"><p class="text-[10px] font-black uppercase tracking-widest {{ $isPlanning ? 'text-white' : 'text-gray-500' }}">Inducción</p><p class="text-[10px] text-gray-600 font-bold truncate">Bienvenida y Planificación</p></div>
            </a>

            @foreach($course->topics as $topic)
                <div class="border-b border-white/5" x-data="{ open: {{ ($topic->id === ($currentLesson->topic_id ?? 0)) ? 'true' : 'false' }} }">
                    <button type="button" @click="open = !open" class="w-full flex items-center justify-between p-6 bg-black/20 hover:bg-white/5 text-gray-400 text-[10px] font-black transition-all group focus:outline-none">
                        <span class="uppercase tracking-[0.15em] line-clamp-1 pr-4">{{ $topic->title }}</span>
                        <i class="ph-bold ph-caret-down text-gray-600 transition-transform duration-300" :class="{ 'rotate-180 text-[#FF7A00]': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="bg-black/10">
                        @foreach($topic->lessons as $lesson)
                            @php
                                $isActive = !$isPlanning && ($lesson->id === $currentLesson->id && request('mode') !== 'final');
                                $isLessonCompleted = in_array($lesson->id, $completedLessonIds ?? []);
                                $url = route('classroom.show', ['course' => $course->slug, 'lesson' => $lesson->slug ?? $lesson->id]);
                            @endphp
                            <a href="{{ $url }}" class="flex items-center justify-between p-4 border-b border-white/5 transition-all group/item {{ $isActive ? 'bg-[#FF7A00]/10 border-l-4 border-l-[#FF7A00]' : 'hover:bg-white/5 border-l-4 border-l-transparent' }}">
                                <div class="flex items-center gap-4 overflow-hidden">
                                    <div class="shrink-0"><i class="ph-fill {{ $lesson->type == 'quiz' ? 'ph-list-checks' : 'ph-play-circle' }} {{ $isActive ? 'text-[#FF7A00]' : 'text-gray-700 group-hover/item:text-gray-500' }} text-2xl"></i></div>
                                    <div class="flex-1 min-w-0"><p class="text-xs font-bold leading-snug truncate {{ $isActive ? 'text-white' : 'text-gray-500 group-hover/item:text-gray-300' }}">{{ $lesson->title }}</p><span class="text-[9px] font-black text-gray-700 block mt-1 uppercase tracking-widest">{{ $lesson->type == 'quiz' ? 'Cuestionario' : $lesson->duration_minutes . ' min' }}</span></div>
                                </div>
                                @if($isLessonCompleted) <div class="shrink-0 text-[#FF7A00]"><i class="ph-fill ph-check-circle text-lg"></i></div> @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
            
            @if($hasExam)
                <div class="border-t border-white/10 mt-4 bg-black/40">
                    @php 
                        $isFinalActive = request('mode') === 'final'; 
                        $finalTitle = ($isExamEnabled && !$isProjectEnabled) ? 'Examen Final' : ((!$isExamEnabled && $isProjectEnabled) ? 'Proyecto Final' : 'Evaluación Final');
                        
                        $sidebarStatusIcon = '';
                        if ($hasAttempted) {
                            $sidebarStatusIcon = $passedScore 
                                ? '<div class="shrink-0 text-emerald-500"><i class="ph-fill ph-check-circle text-2xl"></i></div>' 
                                : '<div class="shrink-0 text-red-500"><i class="ph-fill ph-x-circle text-2xl"></i></div>';
                        }
                    @endphp
                    <a href="?mode=final" class="flex items-center justify-between p-6 {{ $isFinalActive ? 'bg-[#FF7A00]/10 border-l-4 border-[#FF7A00]' : 'hover:bg-white/5 border-l-4 border-transparent' }} transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl {{ $isFinalActive ? 'bg-[#FF7A00]' : 'bg-white/5' }} flex items-center justify-center text-white shrink-0 shadow-lg transition-all group-hover:scale-110">
                                <i class="ph-fill ph-trophy text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-xs font-black text-white uppercase tracking-widest">{{ $finalTitle }}</p>
                                <p class="text-[9px] text-gray-500 font-black uppercase tracking-[0.2em] mt-1">Requisito Diploma</p>
                            </div>
                        </div>
                        {!! $sidebarStatusIcon !!}
                    </a>
                </div>
            @endif
        </div>
    </x-slot>
</x-layouts.classroom>