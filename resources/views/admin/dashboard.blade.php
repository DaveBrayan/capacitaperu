<x-layouts.admin>
    <x-slot name="title">Dashboard - CapacitaPerú</x-slot>
    <x-slot name="header">Resumen General</x-slot>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- HEADER CON FILTROS -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-4 mb-8 fade-in-up" style="animation-delay: 0.1s">
        <div>
            <h1 class="text-3xl font-black text-[#001144] tracking-tight">
                Hola, <span class="text-[#FF7A00]">{{ Auth::user()->name }}</span> 👋
            </h1>
            <p class="text-gray-500 mt-1 font-medium">Reporte de rendimiento: <span class="text-[#001144] font-bold">{{ $periodLabel }}</span></p>
        </div>

        <!-- FILTROS -->
        <div class="bg-white p-1 rounded-2xl border border-gray-100 flex shadow-sm">
            <a href="{{ route('admin.dashboard', ['filter' => 'today']) }}" 
               class="px-5 py-2 rounded-xl text-xs font-black transition-all {{ $filter === 'today' ? 'bg-[#001144] text-white shadow-lg shadow-[#001144]/20' : 'text-gray-400 hover:bg-gray-50' }}">
               Hoy
            </a>
            <a href="{{ route('admin.dashboard', ['filter' => '7_days']) }}" 
               class="px-5 py-2 rounded-xl text-xs font-black transition-all {{ $filter === '7_days' ? 'bg-[#001144] text-white shadow-lg shadow-[#001144]/20' : 'text-gray-400 hover:bg-gray-50' }}">
               7 Días
            </a>
            <a href="{{ route('admin.dashboard', ['filter' => 'month']) }}" 
               class="px-5 py-2 rounded-xl text-xs font-black transition-all {{ $filter === 'month' ? 'bg-[#001144] text-white shadow-lg shadow-[#001144]/20' : 'text-gray-400 hover:bg-gray-50' }}">
               Este Mes
            </a>
        </div>
    </div>

    <!-- TARJETAS DE MÉTRICAS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-10">
        
        <!-- CARD: ESTUDIANTES -->
        <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-gray-100/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 border border-gray-50 group fade-in-up" style="animation-delay: 0.2s">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] mb-2">Estudiantes</p>
                    <h3 class="text-2xl font-black text-[#001144] tracking-tighter">+{{ $newStudentsCurrent }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-[#001144] group-hover:bg-[#001144] group-hover:text-white transition-all duration-500">
                    <i class="ph-fill ph-users-three text-xl"></i>
                </div>
            </div>
            <div class="mt-5 flex items-center text-[10px] font-bold">
                @if($studentGrowth >= 0)
                    <span class="text-green-500 bg-green-50 px-2 py-0.5 rounded-lg flex items-center gap-1">
                        <i class="ph-bold ph-trend-up"></i> +{{ round($studentGrowth, 0) }}%
                    </span>
                @else
                    <span class="text-red-500 bg-red-50 px-2 py-0.5 rounded-lg flex items-center gap-1">
                        <i class="ph-bold ph-trend-down"></i> {{ round($studentGrowth, 0) }}%
                    </span>
                @endif
                <span class="text-gray-400 ml-2 font-medium uppercase tracking-wider opacity-60">vs previo</span>
            </div>
        </div>

        <!-- CARD: CURSOS ACTIVOS -->
        <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-gray-100/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 border border-gray-50 group fade-in-up" style="animation-delay: 0.3s">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] mb-2">Cursos</p>
                    <h3 class="text-2xl font-black text-[#001144] tracking-tighter">{{ $activeCourses }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-[#FF7A00] group-hover:bg-[#FF7A00] group-hover:text-white transition-all duration-500">
                    <i class="ph-fill ph-book-open text-xl"></i>
                </div>
            </div>
            <div class="mt-5 flex items-center text-[10px] font-bold">
                <span class="text-[#FF7A00] bg-orange-50 px-2 py-0.5 rounded-lg">
                    +{{ $newCoursesPeriod }} nuevos
                </span>
                <span class="text-gray-400 ml-2 font-medium uppercase tracking-wider opacity-60">en el periodo</span>
            </div>
        </div>

        <!-- CARD: DIPLOMADOS ACTIVOS -->
        <div class="bg-gradient-to-br from-[#001144] to-[#002266] rounded-[2rem] p-6 shadow-xl shadow-[#001144]/20 hover:-translate-y-1 transition-all duration-500 text-white fade-in-up" style="animation-delay: 0.35s">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-[#FF7A00] uppercase tracking-[0.15em] mb-2">Diplomados</p>
                    <h3 class="text-2xl font-black tracking-tighter">{{ $activeDiplomas }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-[#FF7A00]">
                    <i class="ph-fill ph-graduation-cap text-xl"></i>
                </div>
            </div>
            <div class="mt-5 flex items-center text-[10px] font-bold">
                <span class="text-white font-black">
                    +{{ $newDiplomasPeriod }} nuevos
                </span>
                <span class="text-white/40 ml-2 font-medium uppercase tracking-wider">en el periodo</span>
            </div>
        </div>

        <!-- CARD: CERTIFICADOS EMITIDOS -->
        <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-gray-100/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 border border-gray-50 group fade-in-up" style="animation-delay: 0.4s">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] mb-2">Certificados Indiv.</p>
                    <h3 class="text-2xl font-black text-[#001144] tracking-tighter">{{ $totalIndividualCertificates }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-all duration-500">
                    <i class="ph-fill ph-certificate text-xl"></i>
                </div>
            </div>
            <div class="mt-5 flex items-center text-[10px] font-bold">
                <span class="text-purple-600 bg-purple-50 px-2 py-0.5 rounded-lg">
                    +{{ $newIndividualCertificatesPeriod }} nuevos
                </span>
                <span class="text-gray-400 ml-2 font-medium uppercase tracking-wider opacity-60">en el periodo</span>
            </div>
        </div>

        <!-- CARD: INGRESOS -->
        <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-gray-100/50 hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 border border-gray-50 group fade-in-up" style="animation-delay: 0.45s">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] mb-2">Ingresos</p>
                    <h3 class="text-xl font-black text-[#001144] tracking-tight truncate">{{ $platformSettings['app_currency'] ?? 'Bs' }} {{ number_format($currentRevenue, 1) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition-all duration-500">
                    <i class="ph-fill ph-wallet text-xl"></i>
                </div>
            </div>
            <div class="mt-5 flex items-center text-[10px] font-bold">
                @if($revenueGrowth >= 0)
                    <span class="text-green-500 bg-green-50 px-2 py-0.5 rounded-lg flex items-center gap-1">
                        <i class="ph-bold ph-trend-up"></i> +{{ round($revenueGrowth, 0) }}%
                    </span>
                @else
                    <span class="text-red-500 bg-red-50 px-2 py-0.5 rounded-lg flex items-center gap-1">
                        <i class="ph-bold ph-trend-down"></i> {{ round($revenueGrowth, 0) }}%
                    </span>
                @endif
                <span class="text-gray-400 ml-2 font-medium uppercase tracking-wider opacity-60">vs previo</span>
            </div>
        </div>

        <!-- CARD: TASA FINALIZACIÓN -->
        <div class="bg-gradient-to-br from-[#FF7A00] to-[#e66e00] rounded-[2rem] p-6 shadow-xl shadow-[#FF7A00]/20 hover:-translate-y-1 transition-all duration-500 text-white fade-in-up" style="animation-delay: 0.5s">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black text-[#001144] uppercase tracking-[0.15em] mb-2">Tasa de Éxito</p>
                    <h3 class="text-2xl font-black tracking-tighter">{{ $completionRate }}%</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-[#001144]">
                    <i class="ph-fill ph-medal text-xl"></i>
                </div>
            </div>
            <div class="mt-5 w-full bg-black/10 rounded-full h-1.5">
                <div class="bg-white h-1.5 rounded-full shadow-lg shadow-white/20" style="width: {{ $completionRate }}%"></div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE GRÁFICOS SECUNDARIOS (CURSOS, DIPLOMADOS, CERTIFICADOS) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-10 fade-in-up" style="animation-delay: 0.6s">
        
        <!-- CHART: CURSOS -->
        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-50 shadow-xl shadow-gray-100/50 flex flex-col relative overflow-hidden group">
            <div class="flex justify-between items-center mb-6 relative z-10">
                <div>
                    <h4 class="font-black text-lg text-[#001144] tracking-tight">Actividad de Cursos</h4>
                    <p class="text-[11px] text-gray-400 font-medium">Nuevas inscripciones en el periodo</p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-orange-50 flex items-center justify-center text-[#FF7A00] group-hover:scale-110 transition-transform">
                    <i class="ph-bold ph-book-open text-lg"></i>
                </div>
            </div>
            <div class="h-56 relative w-full z-10">
                <canvas id="coursesActivityChart"></canvas>
            </div>
        </div>

        <!-- CHART: DIPLOMADOS -->
        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-50 shadow-xl shadow-gray-100/50 flex flex-col relative overflow-hidden group">
            <div class="flex justify-between items-center mb-6 relative z-10">
                <div>
                    <h4 class="font-black text-lg text-[#001144] tracking-tight">Actividad de Diplomados</h4>
                    <p class="text-[11px] text-gray-400 font-medium">Nuevas inscripciones en el periodo</p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center text-[#001144] group-hover:scale-110 transition-transform">
                    <i class="ph-bold ph-graduation-cap text-lg"></i>
                </div>
            </div>
            <div class="h-56 relative w-full z-10">
                <canvas id="diplomasActivityChart"></canvas>
            </div>
        </div>

        <!-- CHART: CERTIFICADOS -->
        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-50 shadow-xl shadow-gray-100/50 flex flex-col relative overflow-hidden group">
            <div class="flex justify-between items-center mb-6 relative z-10">
                <div>
                    <h4 class="font-black text-lg text-[#001144] tracking-tight">Certificados Emitidos</h4>
                    <p class="text-[11px] text-gray-400 font-medium">Emisiones de certificados individuales</p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform">
                    <i class="ph-bold ph-certificate text-lg"></i>
                </div>
            </div>
            <div class="h-56 relative w-full z-10">
                <canvas id="certificatesActivityChart"></canvas>
            </div>
        </div>

    </div>

    <div class="w-full mt-10 fade-in-up" style="animation-delay: 0.7s">
        
        <!-- TABLA: ÚLTIMAS INSCRIPCIONES -->
        <div class="bg-white rounded-[2.5rem] p-10 shadow-xl shadow-gray-100/50 border border-gray-50">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="font-black text-2xl text-[#001144] tracking-tight">Últimas Inscripciones</h3>
                    <p class="text-xs text-gray-400 font-medium mt-1">Actividad reciente de alumnos en la plataforma</p>
                </div>
                <a href="{{ route('admin.enrollments.index') }}" class="px-5 py-2.5 rounded-xl bg-gray-50 text-[#001144] hover:bg-[#001144] hover:text-white text-xs font-black transition-all duration-300">
                    Ver Historial
                </a>
            </div>

            <div class="overflow-x-auto no-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-[10px] uppercase font-black tracking-[0.15em] border-b border-gray-50">
                            <th class="pb-5 pl-4">Estudiante</th>
                            <th class="pb-5">Programa / Curso</th>
                            <th class="pb-5">Registro</th>
                            <th class="pb-5 text-center">Estado</th>
                            <th class="pb-5"></th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($recentEnrollments as $enrollment)
                        <tr class="hover:bg-gray-50/80 transition-all duration-300 group">
                            <td class="py-5 pl-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-2xl bg-blue-50 overflow-hidden shrink-0 border border-white shadow-sm ring-2 ring-gray-50">
                                        <img src="{{ $enrollment->student->avatar_url ? (Str::startsWith($enrollment->student->avatar_url, 'http') ? $enrollment->student->avatar_url : asset($enrollment->student->avatar_url)) : 'https://ui-avatars.com/api/?name='.urlencode($enrollment->student->name).'&background=001144&color=fff' }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <div class="font-black text-[#001144] leading-none mb-1">{{ $enrollment->student->name }}</div>
                                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Inscrito</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-5">
                                <div class="font-bold text-gray-600 max-w-[200px] truncate" title="{{ $enrollment->course->title }}">
                                    {{ $enrollment->course->title }}
                                </div>
                            </td>
                            <td class="py-5">
                                <div class="text-xs font-bold text-gray-400 uppercase tracking-tighter">
                                    {{ $enrollment->created_at->translatedFormat('d M, H:i') }}
                                </div>
                            </td>
                            <td class="py-5">
                                <div class="flex justify-center">
                                    @if($enrollment->is_completed)
                                        <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.1em] bg-green-50 text-green-500 border border-green-100">
                                            Finalizado
                                        </span>
                                    @else
                                        <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.1em] bg-orange-50 text-[#FF7A00] border border-orange-100">
                                            Activo
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-5 text-right pr-4">
                                <a href="{{ route('admin.students.index', ['search' => $enrollment->student->email]) }}" class="w-9 h-9 rounded-xl flex items-center justify-center text-gray-300 hover:text-[#FF7A00] hover:bg-orange-50 transition-all duration-300">
                                    <i class="ph-bold ph-caret-right text-xl"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                                        <i class="ph ph-mask-sad text-3xl text-gray-200"></i>
                                    </div>
                                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Sin datos recientes</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script para Chart.js -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const labels = @json($chartLabels);

            // 2. CHART: CURSOS ACTIVITY
            const ctxCourses = document.getElementById('coursesActivityChart').getContext('2d');
            const coursesData = @json($courseChartData);
            const coursesGradient = ctxCourses.createLinearGradient(0, 0, 0, 200);
            coursesGradient.addColorStop(0, 'rgba(255, 122, 0, 0.15)');
            coursesGradient.addColorStop(1, 'rgba(255, 122, 0, 0.0)');

            new Chart(ctxCourses, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Inscripciones Cursos',
                        data: coursesData,
                        borderColor: '#FF7A00',
                        backgroundColor: coursesGradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#FF7A00',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        fill: true,
                        tension: 0.35
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1500,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f8faff', borderDash: [5, 5] },
                            ticks: {
                                color: '#94a3b8',
                                font: { size: 9, weight: 'bold' },
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: '#94a3b8',
                                font: { size: 9, weight: 'bold' }
                            }
                        }
                    }
                }
            });

            // 3. CHART: DIPLOMAS ACTIVITY
            const ctxDiplomas = document.getElementById('diplomasActivityChart').getContext('2d');
            const diplomasData = @json($diplomaChartData);
            const diplomasGradient = ctxDiplomas.createLinearGradient(0, 0, 0, 200);
            diplomasGradient.addColorStop(0, 'rgba(0, 51, 204, 0.15)');
            diplomasGradient.addColorStop(1, 'rgba(0, 51, 204, 0.0)');

            new Chart(ctxDiplomas, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Inscripciones Diplomados',
                        data: diplomasData,
                        borderColor: '#0033CC',
                        backgroundColor: diplomasGradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#0033CC',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        fill: true,
                        tension: 0.35
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1500,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f8faff', borderDash: [5, 5] },
                            ticks: {
                                color: '#94a3b8',
                                font: { size: 9, weight: 'bold' },
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: '#94a3b8',
                                font: { size: 9, weight: 'bold' }
                            }
                        }
                    }
                }
            });

            // 4. CHART: CERTIFICATES ACTIVITY
            const ctxCertificates = document.getElementById('certificatesActivityChart').getContext('2d');
            const certificatesData = @json($certificateChartData);

            new Chart(ctxCertificates, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Certificados Emitidos',
                        data: certificatesData,
                        borderColor: '#9333EA',
                        backgroundColor: '#9333EA',
                        borderRadius: 6,
                        borderWidth: 0,
                        maxBarThickness: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1500,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f8faff', borderDash: [5, 5] },
                            ticks: {
                                color: '#94a3b8',
                                font: { size: 9, weight: 'bold' },
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: '#94a3b8',
                                font: { size: 9, weight: 'bold' }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-layouts.admin>