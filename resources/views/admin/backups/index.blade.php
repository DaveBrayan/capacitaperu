<x-layouts.admin>
    <x-slot name="title">Backup y Copias de Seguridad - CapacitaPerú</x-slot>
    <x-slot name="header">Copias de Seguridad (Backup)</x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="max-w-5xl mx-auto space-y-8 fade-in-up">
        
        <!-- Alertas de éxito o error -->
        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-2xl flex items-center gap-3 shadow-sm">
                <i class="ph-bold ph-check-circle text-emerald-500 text-2xl"></i>
                <p class="text-emerald-800 font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-2xl flex items-center gap-3 shadow-sm">
                <i class="ph-bold ph-warning-circle text-red-500 text-2xl"></i>
                <p class="text-red-800 font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <!-- TARJETAS DE RESUMEN Y GENERACIÓN -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Tarjeta 1: Total Copias -->
            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-[#001144]">
                    <i class="ph-fill ph-archive text-3xl"></i>
                </div>
                <div>
                    <h4 class="text-gray-400 text-[10px] font-black uppercase tracking-widest leading-none mb-1">Copia Total</h4>
                    <p class="text-2xl font-black text-[#001144]">{{ $backups->total() }} registradas</p>
                </div>
            </div>

            <!-- Tarjeta 2: Última Generación -->
            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-orange-50 flex items-center justify-center text-[#FF7A00]">
                    <i class="ph-fill ph-calendar text-3xl"></i>
                </div>
                <div>
                    <h4 class="text-gray-400 text-[10px] font-black uppercase tracking-widest leading-none mb-1">Última Copia</h4>
                    <p class="text-sm font-bold text-[#001144] truncate">
                        {{ $backups->first() ? $backups->first()->created_at->format('d/m/Y H:i') : 'Nunca' }}
                    </p>
                </div>
            </div>

            <!-- Tarjeta 3: Acción Generar -->
            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 flex flex-col justify-center">
                <form id="generate-backup-form" action="{{ route('admin.backups.generate') }}" method="POST">
                    @csrf
                    <button type="submit" onclick="submitBackupForm(event)" class="w-full bg-[#FF7A00] text-white py-4 rounded-[1.2rem] font-black shadow-xl shadow-[#FF7A00]/20 hover:scale-102 active:scale-98 transition-all flex items-center justify-center gap-3 group text-sm uppercase tracking-wider">
                        <i id="backup-icon" class="ph-bold ph-database text-lg group-hover:rotate-12 transition-transform"></i>
                        <span id="backup-text">Generar Backup</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- SECCIÓN: Historial de Copias de Seguridad -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 md:p-10 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl font-black text-[#001144] tracking-tight">Historial de Resguardos</h3>
                    <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mt-1">Descarga copias de seguridad anteriores en formato Excel (.xls)</p>
                </div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-orange-50/50 text-[10px] font-black text-[#FF7A00] uppercase tracking-widest border border-orange-100 shadow-sm">
                    <i class="ph-bold ph-file-xls text-xs"></i> 10 Pestañas / Hojas
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-0">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Archivo</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Fecha y Hora</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Registros</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-50">
                        @forelse($backups as $backup)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                                            <i class="ph-fill ph-file-xls text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-black text-[#001144] tracking-tight text-sm leading-none mb-1">{{ $backup->filename }}</p>
                                            <p class="text-[10px] text-gray-400 font-mono font-medium">{{ $backup->path }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="font-bold text-[#001144]">{{ $backup->created_at->format('d/m/Y') }}</span>
                                    <span class="text-gray-400 text-xs font-semibold block">{{ $backup->created_at->format('H:i:s') }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-blue-50 text-[#001144] border border-blue-100 uppercase tracking-widest">
                                        {{ number_format($backup->records_count) }} filas
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.backups.download', $backup->id) }}" class="w-10 h-10 rounded-xl flex items-center justify-center text-emerald-600 hover:bg-emerald-600 hover:text-white border border-gray-100 transition-all shadow-sm" title="Descargar Copia">
                                            <i class="ph-bold ph-download-simple text-lg"></i>
                                        </a>
                                        
                                        <form id="form-delete-backup-{{ $backup->id }}" action="{{ route('admin.backups.destroy', $backup->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDeleteBackup('form-delete-backup-{{ $backup->id }}')" class="w-10 h-10 rounded-xl flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white border border-gray-100 transition-all shadow-sm" title="Eliminar Registro">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-[1.5rem] flex items-center justify-center mx-auto mb-4">
                                        <i class="ph-duotone ph-database text-4xl text-gray-200"></i>
                                    </div>
                                    <h3 class="text-lg font-black text-[#001144] mb-1 tracking-tight">Sin copias de seguridad</h3>
                                    <p class="text-gray-400 font-semibold max-w-xs mx-auto text-xs uppercase tracking-wider">No se han generado copias de seguridad en el sistema todavía.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        @if($backups->hasPages())
            <div class="mt-8">
                {{ $backups->links() }}
            </div>
        @endif

    </div>

    <!-- SCRIPTS -->
    <script>
        function submitBackupForm(event) {
            event.preventDefault();
            
            Swal.fire({
                title: '¿Generar Copia de Seguridad?',
                text: 'Esto consultará todas las tablas del sistema (Staff, Asesores, Promociones, Certificados, Inscritos, Categorías, Docentes, Estudiantes, Cursos y Diplomados) y creará un único archivo Excel con pestañas independientes.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#FF7A00',
                cancelButtonColor: '#EF4444',
                confirmButtonText: 'Sí, generar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'rounded-[2.5rem]',
                    confirmButton: 'rounded-xl font-black uppercase tracking-widest text-[10px] px-8 py-4',
                    cancelButton: 'rounded-xl font-black uppercase tracking-widest text-[10px] px-8 py-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Cambiar estado del botón
                    const btnIcon = document.getElementById('backup-icon');
                    const btnText = document.getElementById('backup-text');
                    
                    btnIcon.className = "ph-bold ph-spinner animate-spin text-lg";
                    btnText.innerText = "Procesando...";
                    btnIcon.parentElement.disabled = true;
                    btnIcon.parentElement.classList.add('opacity-75', 'cursor-not-allowed');

                    // Mostrar pantalla de carga
                    Swal.fire({
                        title: 'Generando Copia de Seguridad',
                        text: 'Extrayendo registros y estructurando el archivo Excel. Por favor, no cierres esta ventana.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        customClass: {
                            popup: 'rounded-[2.5rem]'
                        }
                    });

                    document.getElementById('generate-backup-form').submit();
                }
            });
        }

        function confirmDeleteBackup(formId) {
            Swal.fire({
                title: '¿Eliminar Copia de Seguridad?',
                text: 'Esta acción eliminará de forma permanente el archivo del servidor y el registro de la base de datos.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#001144',
                cancelButtonColor: '#EF4444',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'rounded-[2.5rem]',
                    confirmButton: 'rounded-xl font-black uppercase tracking-widest text-[10px] px-8 py-4',
                    cancelButton: 'rounded-xl font-black uppercase tracking-widest text-[10px] px-8 py-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
</x-layouts.admin>
