<x-layouts.admin>
    <x-slot name="title">Inscripciones - CapacitaPerú</x-slot>
    <x-slot name="header">Gestión de Inscripciones</x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('error'))
        <div class="mb-8 p-6 rounded-[1.5rem] bg-red-50 border border-red-100 text-red-700 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-red-500 shadow-sm shrink-0">
                <i class="ph-bold ph-warning-circle text-2xl"></i>
            </div>
            <p class="text-xs font-black uppercase tracking-widest">{{ session('error') }}</p>
        </div>
    @endif

    <!-- BARRA DE HERRAMIENTAS -->
    <div class="flex flex-col xl:flex-row justify-between items-center gap-6 mb-10 fade-in-up">
        <!-- BUSCADOR -->
        <form action="{{ route('admin.enrollments.index') }}" method="GET" class="relative w-full xl:w-96 flex gap-3 group">
            <div class="relative flex-1">
                <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00] transition-colors"></i>
                <input type="text" name="search" value="{{ $search ?? '' }}" 
                       placeholder="Buscar por alumno, email o curso..." 
                       class="w-full pl-12 pr-4 py-4 rounded-[1.2rem] border border-gray-100 bg-white shadow-sm focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/10 outline-none text-sm transition-all font-medium">
            </div>
            <button type="submit" class="px-5 bg-[#001144] text-white rounded-[1.2rem] hover:bg-[#FF7A00] transition-all shadow-xl shadow-blue-900/10">
                <i class="ph-bold ph-arrow-right text-lg"></i>
            </button>
            @if($search)
                <a href="{{ route('admin.enrollments.index') }}" class="w-12 flex items-center justify-center bg-gray-50 text-gray-400 rounded-[1.2rem] hover:bg-red-50 hover:text-red-500 transition-all border border-gray-100" title="Limpiar búsqueda">
                    <i class="ph-bold ph-x"></i>
                </a>
            @endif
        </form>
        
        <div class="flex flex-wrap justify-center gap-4 w-full xl:w-auto">
            @if(Auth::user()->hasPermission('enrollments.manage'))
                <button onclick="openEnrollModal()" class="flex items-center gap-3 px-6 py-4 bg-[#001144] text-white rounded-[1.2rem] hover:bg-[#001144]/90 transition-all shadow-xl shadow-blue-900/20 text-[10px] font-black uppercase tracking-widest group transform hover:-translate-y-1">
                    <i class="ph-bold ph-user-plus text-lg group-hover:rotate-12 transition-transform"></i>
                    Nuevo Alumno
                </button>

                <button onclick="openExistingEnrollModal()" class="flex items-center gap-3 px-6 py-4 bg-white border border-gray-100 text-[#001144] rounded-[1.2rem] hover:bg-gray-50 transition-all shadow-sm text-[10px] font-black uppercase tracking-widest transform hover:-translate-y-1">
                    <i class="ph-bold ph-users text-lg"></i>
                    Inscribir Antiguo
                </button>

                <button onclick="openLinkGeneratorModal()" class="flex items-center gap-3 px-6 py-4 bg-orange-50 text-[#FF7A00] rounded-[1.2rem] hover:bg-[#FF7A00] hover:text-white transition-all shadow-sm text-[10px] font-black uppercase tracking-widest border border-orange-100 transform hover:-translate-y-1">
                    <i class="ph-bold ph-link text-lg"></i>
                    Link Registro
                </button>
            @endif
        </div>
    </div>

    <!-- TABLA DE SOLICITUDES -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden fade-in-up" style="animation-delay: 0.2s">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] w-16">#</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Solicitante</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Programa</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Pago</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-center">Fecha</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-center">Estado</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($enrollments as $index => $enrollment)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <span class="font-mono text-xs text-gray-300 font-bold">#{{ $enrollments->firstItem() + $index }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-[1rem] bg-gray-100 p-0.5 border-2 border-white group-hover:border-[#FF7A00] shadow-sm shrink-0 overflow-hidden transition-all rotate-2 group-hover:rotate-0">
                                    <img src="{{ $enrollment->student ? (Str::startsWith($enrollment->student->avatar_url ?? '', 'http') ? $enrollment->student->avatar_url : asset($enrollment->student->avatar_url ?? 'assets/default-avatar.png')) : asset('assets/default-avatar.png') }}" class="w-full h-full object-cover">
                                </div>
                                <div class="truncate max-w-[200px]">
                                    <p class="font-black text-[#001144] tracking-tight text-base leading-none mb-1 truncate">{{ $enrollment->student->name ?? 'Usuario Eliminado' }}</p>
                                    <p class="text-[11px] text-gray-400 font-medium truncate">{{ $enrollment->student->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 shadow-sm border border-blue-100">
                                    <i class="ph-bold ph-graduation-cap"></i>
                                </div>
                                <span class="font-bold text-[#001144] truncate max-w-[200px] text-xs uppercase tracking-wider">{{ $enrollment->course->title ?? 'Curso Eliminado' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @if($enrollment->receipt_url)
                                <div class="group/boleta relative w-12 h-12 rounded-2xl overflow-hidden border-2 border-white shadow-md cursor-pointer hover:scale-110 transition-all rotate-3" 
                                     onclick="verBoleta('{{ asset($enrollment->receipt_url) }}')">
                                    <img src="{{ asset($enrollment->receipt_url) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-[#001144]/60 flex items-center justify-center opacity-0 group-hover/boleta:opacity-100 transition-opacity">
                                        <i class="ph-bold ph-eye text-white text-lg"></i>
                                    </div>
                                </div>
                            @else
                                <div class="w-12 h-12 rounded-2xl bg-gray-50 border border-dashed border-gray-200 flex items-center justify-center">
                                    <i class="ph-bold ph-prohibit text-gray-300"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            <p class="font-black text-[#001144] text-[11px] uppercase tracking-tighter">{{ $enrollment->created_at->format('d M Y') }}</p>
                            <p class="text-[10px] text-gray-400 font-black tracking-widest opacity-60 uppercase">{{ $enrollment->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($enrollment->status === 'approved')
                                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-green-50 text-green-600 border border-green-100 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.5)]"></span> Aprobado
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-orange-50 text-[#FF7A00] border border-orange-100 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#FF7A00] animate-pulse shadow-[0_0_8px_rgba(255,122,0,0.5)]"></span> Pendiente
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end items-center gap-2">
                                @if(Auth::user()->hasPermission('enrollments.manage'))
                                    @if($enrollment->status !== 'approved')
                                        <form action="{{ route('admin.enrollments.approve', $enrollment->id) }}" method="POST" class="inline">
                                            @csrf @method('PUT')
                                            <button type="submit" class="px-5 py-2.5 bg-[#001144] text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-[#FF7A00] transition-all shadow-xl shadow-blue-900/10 transform hover:-translate-y-0.5">
                                                Aprobar
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('admin.enrollments.destroy', $enrollment->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que desea eliminar esta inscripción?');">
                                        @csrf @method('DELETE')
                                        <button class="w-10 h-10 flex items-center justify-center text-gray-300 hover:bg-red-50 hover:text-red-500 rounded-xl transition-all border border-transparent hover:border-red-100">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-8 py-24 text-center">
                            <div class="w-24 h-24 bg-gray-50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6 shadow-inner">
                                <i class="ph-duotone ph-tray text-6xl text-gray-200"></i>
                            </div>
                            <h3 class="text-xl font-black text-[#001144] mb-2 tracking-tight">No hay solicitudes</h3>
                            <p class="text-gray-400 font-medium max-w-xs mx-auto text-sm">No se encontraron inscripciones registradas que coincidan con los criterios.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-8 border-t border-gray-50 bg-gray-50/30">
            {{ $enrollments->links() }}
        </div>
    </div>

    <!-- 1. MODAL: NUEVO ESTUDIANTE -->
    <div id="modal-enroll" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-[#001144]/40 backdrop-blur-md transition-opacity" onclick="closeEnrollModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <form id="enrollForm" action="{{ route('admin.enrollments.store') }}" method="POST" enctype="multipart/form-data" 
                  class="relative transform overflow-hidden rounded-[2.5rem] bg-white shadow-2xl transition-all w-full max-w-2xl border border-white/20">
                @csrf
                <div class="bg-white px-10 py-8 border-b border-gray-50 flex justify-between items-center">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-2xl bg-orange-50 flex items-center justify-center text-[#FF7A00]">
                            <i class="ph-fill ph-user-circle-plus text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-[#001144] tracking-tight">Registro Completo</h3>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Cuenta Nueva e Inscripción</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeEnrollModal()" class="w-12 h-12 rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
                        <i class="ph-bold ph-x text-xl"></i>
                    </button>
                </div>

                <div class="p-10 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Tipo de Programa</label>
                            <div class="relative">
                                <select id="enroll-new-type" required class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] appearance-none cursor-pointer transition-all">
                                    <option value="course">Curso</option>
                                    <option value="diploma">Diplomado</option>
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition-transform"></i>
                            </div>
                        </div>
                        <div class="group md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Programa Académico</label>
                            <div class="relative">
                                <i class="ph-bold ph-graduation-cap absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00] transition-colors"></i>
                                <select id="enroll-new-course" name="course_id" required class="w-full pl-14 pr-10 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] appearance-none cursor-pointer transition-all">
                                    <option value="">Seleccione el programa...</option>
                                    @foreach($courses as $course) 
                                        <option value="{{ $course->id }}" data-type="{{ $course->type }}">{{ $course->title }}</option> 
                                    @endforeach
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none group-focus-within:rotate-180 transition-transform"></i>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Nombres y Apellidos</label>
                            <input type="text" name="name" required class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] transition-all" placeholder="Ej: Maria Garcia">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Email</label>
                            <input type="email" name="email" required class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] transition-all" placeholder="ejemplo@correo.com">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Teléfono / WhatsApp</label>
                            <input type="text" name="phone" class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] transition-all" placeholder="+51 900 000 000">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Contraseña de Acceso</label>
                            <div class="relative group/pass">
                                <input type="text" id="password" name="password" required class="w-full pl-5 pr-14 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-black text-[#001144] tracking-widest transition-all">
                                <button type="button" onclick="generatePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-xl bg-white border border-gray-100 text-[#FF7A00] hover:bg-[#FF7A00] hover:text-white transition-all flex items-center justify-center shadow-sm">
                                    <i class="ph-bold ph-arrows-clockwise text-lg"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Comprobante (JPG/PNG)</label>
                            <label class="flex items-center gap-3 w-full px-5 py-3.5 rounded-[1.2rem] border border-gray-100 bg-gray-50 cursor-pointer hover:bg-gray-100 transition-all group/file">
                                <i class="ph-bold ph-cloud-arrow-up text-xl text-gray-400 group-hover/file:text-[#FF7A00]"></i>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest group-hover/file:text-[#001144]">Subir Boleta</span>
                                <input type="file" name="receipt_file" class="hidden">
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 pt-8 border-t border-gray-50">
                        <button type="button" onclick="closeEnrollModal()" class="px-8 py-4 bg-white text-[#001144] font-black uppercase tracking-widest rounded-[1.2rem] border border-gray-100 hover:bg-gray-50 transition-all text-[10px]">Cancelar</button>
                        <button type="submit" class="px-10 py-4 bg-[#001144] text-white font-black uppercase tracking-widest rounded-[1.2rem] hover:bg-[#FF7A00] transition-all shadow-xl shadow-blue-900/10 text-[10px] flex items-center gap-3 transform hover:-translate-y-1">
                            <i class="ph-bold ph-check text-lg"></i> Finalizar Inscripción
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. MODAL: ESTUDIANTE ANTIGUO -->
    <div id="modal-enroll-existing" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-[#001144]/40 backdrop-blur-md transition-opacity" onclick="closeExistingEnrollModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <form action="{{ route('admin.enrollments.store_existing') }}" method="POST" enctype="multipart/form-data" 
                  class="relative transform overflow-hidden rounded-[2.5rem] bg-white shadow-2xl transition-all w-full max-w-xl border border-white/20">
                @csrf
                <div class="bg-white px-10 py-8 border-b border-gray-50 flex justify-between items-center">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600">
                            <i class="ph-fill ph-users-three text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-[#001144] tracking-tight">Reinscripción</h3>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Matricular Alumno Existente</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeExistingEnrollModal()" class="w-12 h-12 rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
                        <i class="ph-bold ph-x text-xl"></i>
                    </button>
                </div>

                <div class="p-10 space-y-8">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Buscar Alumno</label>
                        <div class="relative group">
                            <i class="ph-bold ph-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00]"></i>
                            <select name="student_id" required class="w-full pl-14 pr-10 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] appearance-none cursor-pointer transition-all">
                                <option value="">Seleccione el estudiante...</option>
                                @foreach($students ?? [] as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Tipo de Programa</label>
                            <div class="relative">
                                <select id="enroll-existing-type" required class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] appearance-none cursor-pointer transition-all">
                                    <option value="course">Curso</option>
                                    <option value="diploma">Diplomado</option>
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition-transform"></i>
                            </div>
                        </div>
                        <div class="group md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Nuevo Programa</label>
                            <div class="relative">
                                <i class="ph-bold ph-graduation-cap absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF7A00]"></i>
                                <select id="enroll-existing-course" name="course_id" required class="w-full pl-14 pr-10 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] appearance-none cursor-pointer transition-all">
                                    <option value="">Seleccione el programa...</option>
                                    @foreach($courses as $course) 
                                        <option value="{{ $course->id }}" data-type="{{ $course->type }}">{{ $course->title }}</option> 
                                    @endforeach
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none group-focus-within:rotate-180 transition-transform"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Comprobante (Opcional)</label>
                        <label class="flex items-center gap-3 w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 cursor-pointer hover:bg-gray-100 transition-all group/file2">
                            <i class="ph-bold ph-cloud-arrow-up text-xl text-gray-400 group-hover/file2:text-[#FF7A00]"></i>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest group-hover/file2:text-[#001144]">Subir boleta de pago</span>
                            <input type="file" name="receipt_file" class="hidden">
                        </label>
                    </div>

                    <div class="flex justify-end gap-4 pt-8 border-t border-gray-50">
                        <button type="button" onclick="closeExistingEnrollModal()" class="px-8 py-4 bg-white text-[#001144] font-black uppercase tracking-widest rounded-[1.2rem] border border-gray-100 hover:bg-gray-50 transition-all text-[10px]">Cancelar</button>
                        <button type="submit" class="px-10 py-4 bg-[#001144] text-white font-black uppercase tracking-widest rounded-[1.2rem] hover:bg-[#FF7A00] transition-all shadow-xl shadow-blue-900/10 text-[10px] flex items-center gap-3 transform hover:-translate-y-1">
                            <i class="ph-bold ph-check text-lg"></i> Matricular
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 3. MODAL: GENERADOR DE LINKS -->
    <div id="modal-link-generator" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-[#001144]/40 backdrop-blur-md transition-opacity" onclick="closeLinkGeneratorModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-[3rem] bg-white shadow-2xl transition-all w-full max-w-md border border-white/20">
                <div class="bg-white px-10 py-8 border-b border-gray-50 flex justify-between items-center">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-2xl bg-orange-50 flex items-center justify-center text-[#FF7A00]">
                            <i class="ph-fill ph-link text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-[#001144] tracking-tight">Auto-Registro</h3>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Generador de Enlace Único</p>
                        </div>
                    </div>
                </div>

                <div class="p-10 space-y-8">
                    <div class="p-6 bg-orange-50/50 rounded-[2rem] border border-orange-100 flex items-center gap-5">
                        <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center text-[#FF7A00] shadow-sm shrink-0">
                            <i class="ph-bold ph-rocket-launch text-2xl"></i>
                        </div>
                        <p class="text-[11px] text-[#001144] font-bold leading-tight uppercase tracking-wider">Permite que el estudiante cree su cuenta de forma autónoma.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Tipo</label>
                            <div class="relative">
                                <select id="link-program-type" class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] appearance-none cursor-pointer transition-all">
                                    <option value="course">Curso</option>
                                    <option value="diploma">Diplomado</option>
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition-transform"></i>
                            </div>
                        </div>
                        <div class="group md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Seleccionar Programa</label>
                            <div class="relative">
                                <select id="link-course-id" class="w-full px-5 py-4 rounded-[1.2rem] border border-gray-100 bg-gray-50 focus:bg-white focus:border-[#FF7A00] outline-none text-sm font-bold text-[#001144] appearance-none cursor-pointer transition-all">
                                    <option value="">¿Para qué programa es el link?</option>
                                    @foreach($courses as $course) 
                                        <option value="{{ $course->id }}" data-type="{{ $course->type }}">{{ $course->title }}</option> 
                                    @endforeach
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition-transform"></i>
                            </div>
                        </div>
                    </div>

                    <div id="link-result" class="hidden space-y-3 animate-fade-in">
                        <label class="block text-[10px] font-black text-[#FF7A00] uppercase tracking-widest ml-1">Enlace Generado Correctamente:</label>
                        <div class="flex gap-3">
                            <input type="text" id="generated-url" readonly 
                                   class="flex-1 bg-gray-50 px-5 py-4 rounded-[1.2rem] border border-gray-100 text-[11px] font-black font-mono text-[#001144] tracking-tight overflow-hidden text-ellipsis">
                            <button onclick="copyGeneratedLink()" class="w-14 h-14 bg-[#001144] text-white rounded-[1.2rem] hover:bg-[#FF7A00] transition-all flex items-center justify-center shadow-xl shadow-blue-900/10">
                                <i class="ph-bold ph-copy text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 pt-6 border-t border-gray-50">
                        <button id="btn-generate-action" onclick="generateEnrollmentLink()" 
                                class="w-full py-5 bg-[#001144] text-white font-black uppercase tracking-[0.2em] rounded-[1.5rem] transition-all shadow-xl shadow-blue-900/10 text-[10px]">
                            Generar Enlace Seguro
                        </button>
                        <button type="button" onclick="closeLinkGeneratorModal()" 
                                class="w-full py-4 text-gray-400 font-black uppercase tracking-widest text-[10px] hover:text-[#001144] transition-colors">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. MODAL: CREDENCIALES (PARA COPIAR) -->
    <div id="modal-credentials" class="fixed inset-0 z-[60] hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-[#001144]/60 backdrop-blur-xl transition-opacity"></div>
        <div class="relative z-10 flex min-h-full items-center justify-center p-4">
            <div class="bg-white rounded-[3rem] shadow-2xl p-10 max-w-md w-full text-center border border-white/20">
                <div class="w-20 h-20 bg-green-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-green-500 shadow-inner">
                    <i class="ph-fill ph-check-circle text-4xl"></i>
                </div>
                <h3 class="text-2xl font-black text-[#001144] mb-2 tracking-tight">¡Proceso Exitoso!</h3>
                <p class="text-gray-400 mb-8 text-[11px] font-medium uppercase tracking-widest">Envia las credenciales de acceso</p>
                
                <div class="bg-gray-50 rounded-[2rem] p-6 text-left border border-gray-100 mb-8 space-y-4 font-mono text-xs shadow-inner">
                    <div class="flex flex-col gap-1 border-b border-gray-100 pb-3">
                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-widest">Usuario Acceso</span>
                        <span class="text-[#001144] font-black break-all" id="cred-email">...</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-widest">Contraseña Temporal</span>
                        <span class="text-[#FF7A00] font-black tracking-widest" id="cred-pass">...</span>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <button onclick="copyToClipboard()" class="w-full py-5 bg-[#25D366] hover:bg-[#1DA851] text-white font-black uppercase tracking-[0.2em] rounded-[1.5rem] transition-all flex items-center justify-center gap-3 shadow-xl shadow-green-500/20 text-[10px]">
                        <i class="ph-fill ph-whatsapp-logo text-xl"></i> Copiar y Enviar
                    </button>
                    <button onclick="document.getElementById('modal-credentials').classList.add('hidden')" class="w-full py-4 bg-gray-50 hover:bg-gray-100 text-gray-400 font-black uppercase tracking-widest rounded-[1.5rem] transition-all text-[10px]">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: VISUALIZADOR DE BOLETAS -->
    <div id="modal-boleta" class="fixed inset-0 z-[100] hidden bg-[#001144]/90 backdrop-blur-md flex items-center justify-center p-8" onclick="this.classList.add('hidden')">
        <div class="relative max-w-2xl w-full flex flex-col items-center">
            <button class="absolute -top-12 right-0 text-white hover:text-[#FF7A00] flex items-center gap-2 font-black uppercase tracking-widest text-[10px]">
                <i class="ph-bold ph-x text-2xl"></i> Cerrar
            </button>
            <img id="boleta-img" src="" class="max-w-full max-h-[80vh] rounded-[2rem] border-8 border-white shadow-2xl object-contain bg-white">
            <p class="mt-6 text-white/60 font-black uppercase tracking-widest text-[10px]">Vista previa del comprobante cargado</p>
        </div>
    </div>

    <script>
        const Toast = Swal.mixin({ 
            toast: true, 
            position: 'top-end', 
            showConfirmButton: false, 
            timer: 3000, 
            timerProgressBar: true,
            customClass: { popup: 'rounded-2xl border border-gray-50 shadow-xl' }
        });

        @if(session('credentials_modal'))
            document.addEventListener('DOMContentLoaded', function() {
                const data = @json(session('credentials_modal'));
                document.getElementById('cred-email').innerText = data.email;
                document.getElementById('cred-pass').innerText = data.password;
                window.tempCredentialName = data.name;
                window.tempCredentialCourse = data.course;
                document.getElementById('modal-credentials').classList.remove('hidden');
            });
        @endif

        // Helper to setup dynamic dependent program filtering
        function setupProgramTypeFilter(typeSelectId, programSelectId) {
            const typeSelect = document.getElementById(typeSelectId);
            const programSelect = document.getElementById(programSelectId);
            
            if (!typeSelect || !programSelect) return;
            
            // Cache original options (skip the first placeholder option)
            const originalOptions = Array.from(programSelect.options).slice(1).map(opt => ({
                value: opt.value,
                text: opt.text,
                type: opt.getAttribute('data-type')
            }));
            
            const placeholderText = programSelect.options[0] ? programSelect.options[0].text : 'Seleccione...';
            
            function filterOptions() {
                const selectedType = typeSelect.value;
                
                // Clear and add placeholder
                programSelect.innerHTML = `<option value="">${placeholderText}</option>`;
                
                // Filter and append matching options
                originalOptions.forEach(opt => {
                    if (opt.type === selectedType) {
                        const newOpt = document.createElement('option');
                        newOpt.value = opt.value;
                        newOpt.textContent = opt.text;
                        newOpt.setAttribute('data-type', opt.type);
                        programSelect.appendChild(newOpt);
                    }
                });
            }
            
            typeSelect.addEventListener('change', filterOptions);
            
            // Execute initial filter
            filterOptions();
        }

        // Initialize filtering on DOMContentLoaded
        document.addEventListener('DOMContentLoaded', () => {
            setupProgramTypeFilter('enroll-new-type', 'enroll-new-course');
            setupProgramTypeFilter('enroll-existing-type', 'enroll-existing-course');
            setupProgramTypeFilter('link-program-type', 'link-course-id');
        });

        function openEnrollModal() { 
            document.getElementById('enrollForm').reset(); 
            // Sync filter type to course on open
            const typeSelect = document.getElementById('enroll-new-type');
            if (typeSelect) {
                typeSelect.value = 'course';
                typeSelect.dispatchEvent(new Event('change'));
            }
            generatePassword(); 
            document.getElementById('modal-enroll').classList.remove('hidden'); 
        }
        function closeEnrollModal() { document.getElementById('modal-enroll').classList.add('hidden'); }
        
        function openExistingEnrollModal() { 
            const existingForm = document.querySelector('#modal-enroll-existing form');
            if (existingForm) existingForm.reset();
            
            // Sync filter type to course on open
            const typeSelect = document.getElementById('enroll-existing-type');
            if (typeSelect) {
                typeSelect.value = 'course';
                typeSelect.dispatchEvent(new Event('change'));
            }
            document.getElementById('modal-enroll-existing').classList.remove('hidden'); 
        }
        function closeExistingEnrollModal() { document.getElementById('modal-enroll-existing').classList.add('hidden'); }
        
        function openLinkGeneratorModal() { 
            document.getElementById('link-result').classList.add('hidden');
            document.getElementById('btn-generate-action').classList.remove('hidden');
            document.getElementById('btn-generate-action').innerText = "Generar Enlace Seguro";
            document.getElementById('btn-generate-action').disabled = false;
            
            // Sync filter type to course on open
            const typeSelect = document.getElementById('link-program-type');
            if (typeSelect) {
                typeSelect.value = 'course';
                typeSelect.dispatchEvent(new Event('change'));
            }
            document.getElementById('modal-link-generator').classList.remove('hidden'); 
        }
        function closeLinkGeneratorModal() { document.getElementById('modal-link-generator').classList.add('hidden'); }

        function copyGeneratedLink() {
            const url = document.getElementById('generated-url');
            url.select(); 
            navigator.clipboard.writeText(url.value).then(() => {
                Toast.fire({ icon: 'success', title: 'Enlace copiado' });
            });
        }

        function generatePassword() {
            const chars = "abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789";
            let pass = ""; for(let i=0; i<8; i++) pass += chars.charAt(Math.floor(Math.random() * chars.length));
            document.getElementById('password').value = pass;
        }

        function verBoleta(url) { 
            document.getElementById('boleta-img').src = url; 
            document.getElementById('modal-boleta').classList.remove('hidden'); 
        }

        function copyToClipboard() {
            const name = window.tempCredentialName || 'Estudiante';
            const email = document.getElementById('cred-email').innerText;
            const pass = document.getElementById('cred-pass').innerText;
            const course = window.tempCredentialCourse || 'el curso';
            const text = `Hola ${name}, tu inscripción al curso *${course}* en CapacitaPerú ha sido procesada con éxito.\n\nAccede a tu aula virtual aquí:\n🔗 ${window.location.origin}/login\n\nUsuario: ${email}\nContraseña: ${pass}\n\n*Recuerda cambiar tu contraseña e iniciar sesión para comenzar.*`;
            navigator.clipboard.writeText(text).then(() => { Toast.fire({ icon: 'success', title: 'Datos copiados' }); });
        }

        async function generateEnrollmentLink() {
            const courseId = document.getElementById('link-course-id').value;
            if(!courseId) { 
                Toast.fire({ icon: 'warning', title: 'Selecciona un programa' }); 
                return; 
            }

            const btn = document.getElementById('btn-generate-action');
            btn.disabled = true;
            btn.innerText = "Procesando...";

            try {
                const response = await fetch('{{ route("admin.enrollments.generate_token") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ course_id: courseId })
                });

                const data = await response.json();

                if (data.url) {
                    document.getElementById('generated-url').value = data.url;
                    document.getElementById('link-result').classList.remove('hidden');
                    btn.classList.add('hidden');
                    Toast.fire({ icon: 'success', title: 'Enlace activo' });
                }
            } catch (error) {
                console.error(error);
                Toast.fire({ icon: 'error', title: 'Error de conexión' });
                btn.disabled = false;
                btn.innerText = "Generar Enlace Seguro";
            }
        }
    </script>
</x-layouts.admin>