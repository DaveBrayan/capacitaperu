<x-layouts.admin>
    <x-slot name="title">Certificados Individuales - Gestión</x-slot>
    <x-slot name="header">Certificados Individuales</x-slot>

    <!-- MENSAJES FLASH -->
    @if(session('success'))
        <div class="mb-8 p-4 rounded-[1.5rem] bg-green-50 border border-green-100 text-green-700 flex items-center gap-3 animate-bounce-in shadow-sm">
            <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center shadow-lg shadow-green-500/20">
                <i class="ph-bold ph-check text-sm"></i>
            </div>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <!-- BARRA DE HERRAMIENTAS -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-10 fade-in-up" style="animation-delay: 0.1s">
        
        <!-- Buscador y Filtros -->
        <div class="flex flex-wrap gap-4 w-full md:w-auto">
            <div class="relative w-full md:w-96">
                <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Buscar certificados..." class="w-full pl-12 pr-4 py-3.5 rounded-[1.2rem] border border-gray-100 focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/5 outline-none transition-all text-sm font-medium bg-white shadow-sm">
            </div>
        </div>

        <!-- Botones de Acción Global -->
        <div class="flex gap-3 w-full md:w-auto">
            @if(Auth::user()->hasPermission('individual-certificates.edit'))
            <button onclick="openSignaturesModal()" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-6 py-3.5 bg-white border-2 border-[#001144] text-[#001144] rounded-[1.2rem] hover:bg-gray-50 transition-all text-sm font-black">
                <i class="ph-bold ph-signature"></i> Configurar Firmas
            </button>
            @endif
            @if(Auth::user()->hasPermission('individual-certificates.create'))
            <a href="{{ route('admin.individual-certificates.create') }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-8 py-3.5 bg-[#001144] text-white rounded-[1.2rem] hover:bg-[#002266] transition-all shadow-xl shadow-[#001144]/20 text-sm font-black">
                <i class="ph-bold ph-plus"></i> Nuevo Certificado Individual
            </a>
            @endif
        </div>
    </div>

    <!-- TABLA DE CERTIFICADOS -->
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-100/50 border border-gray-50 overflow-hidden fade-in-up" style="animation-delay: 0.2s">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/30 text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] border-b border-gray-50">
                        <th class="px-8 py-5 w-16 text-center">ID</th>
                        <th class="px-8 py-5">Información</th>
                        <th class="px-8 py-5">Categoría / Estado</th>
                        <th class="px-8 py-5 text-center">Carga Horaria</th>
                        <th class="px-8 py-5">Fechas</th>
                        <th class="px-8 py-5 text-right">Gestión</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    
                    @forelse($certificates as $cert)
                    <tr class="hover:bg-gray-50/50 transition-all duration-300 group"
                        data-title="{{ $cert->title }}"
                        data-hours="{{ $cert->hours ?? 0 }}"
                        data-reg="{{ $cert->registration_number ?? 'S/N' }}"
                        data-status-text="{{ $cert->status === 'published' ? 'Publicado' : 'Borrador' }}"
                        data-status-class="{{ $cert->status === 'published' ? 'text-green-600' : 'text-orange-500' }}">
                        <!-- NUMERACIÓN -->
                        <td class="px-8 py-6 text-center font-black text-gray-300">
                            {{ $loop->iteration + ($certificates->currentPage() - 1) * $certificates->perPage() }}
                        </td>

                        <!-- INFORMACIÓN -->
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#001144] flex items-center justify-center shrink-0 border border-white shadow-sm ring-2 ring-gray-50">
                                    <i class="ph-fill ph-certificate text-2xl"></i>
                                </div>
                                <div class="max-w-[300px]">
                                    <p class="font-black text-[#001144] text-base leading-tight mb-1 group-hover:text-[#FF7A00] transition-colors line-clamp-1" title="{{ $cert->title }}">{{ $cert->title }}</p>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] text-gray-400 font-black uppercase tracking-wider">Reg: {{ $cert->registration_number ?? 'S/N' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>


                        <!-- CATEGORÍA / ESTADO -->
                        <td class="px-8 py-6">
                            <div class="flex flex-col gap-2">
                                <div class="flex flex-wrap gap-1">
                                    @if($cert->category)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-50 text-[#001144] border border-blue-100">
                                            <i class="ph-fill {{ $cert->category->icon ?? 'ph-tag' }}"></i> {{ $cert->category->name }}
                                        </span>
                                    @else
                                        <span class="text-[10px] text-gray-300 font-bold uppercase">General</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full {{ $cert->status === 'published' ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.4)]' : 'bg-orange-400 shadow-[0_0_8px_rgba(251,146,60,0.4)]' }}"></div>
                                    <span class="text-[10px] font-black uppercase tracking-widest {{ $cert->status === 'published' ? 'text-green-600' : 'text-orange-500' }}">
                                        {{ $cert->status === 'published' ? 'Publicado' : 'Borrador' }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <!-- CARGA HORARIA -->
                        <td class="px-8 py-6 text-center">
                            <div class="inline-flex flex-col items-center">
                                <span class="font-black text-[#001144] text-xl tracking-tighter">{{ $cert->hours ?? 0 }}</span>
                                <span class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Horas</span>
                            </div>
                        </td>

                        <!-- FECHAS -->
                        <td class="px-8 py-6">
                            <div class="flex flex-col text-[10px] font-black uppercase tracking-wider text-gray-500">
                                <span>I: {{ $cert->start_date ? $cert->start_date->format('d/m/Y') : '-' }}</span>
                                <span>F: {{ $cert->end_date ? $cert->end_date->format('d/m/Y') : '-' }}</span>
                            </div>
                        </td>

                        <!-- ACCIONES -->
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(Auth::user()->hasPermission('individual-certificates.view'))
                                <button onclick="openDetailsModal({{ $cert->id }}, this)" class="w-10 h-10 rounded-[0.8rem] flex items-center justify-center text-gray-400 hover:text-blue-500 hover:bg-blue-50 transition-all duration-300" title="Ver Detalles">
                                    <i class="ph-bold ph-magnifying-glass text-xl"></i>
                                </button>
                                @endif

                                @if(Auth::user()->hasPermission('individual-certificates.edit'))
                                <a href="{{ route('admin.individual-certificates.edit', $cert->id) }}" class="w-10 h-10 rounded-[0.8rem] flex items-center justify-center text-gray-400 hover:text-[#FF7A00] hover:bg-orange-50 transition-all duration-300" title="Editar">
                                    <i class="ph-bold ph-pencil-simple text-xl"></i>
                                </a>
                                @endif
                                
                                @if(Auth::user()->hasPermission('individual-certificates.delete'))
                                <form action="{{ route('admin.individual-certificates.destroy', $cert->id) }}" method="POST" onsubmit="return confirm('¿Seguro que desea eliminar este certificado?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-10 h-10 rounded-[0.8rem] flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all duration-300" title="Eliminar">
                                        <i class="ph-bold ph-trash text-xl"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-24 h-24 bg-gray-50 rounded-[2rem] flex items-center justify-center mb-6 ring-8 ring-gray-50/50">
                                    <i class="ph-duotone ph-certificate text-5xl text-gray-200"></i>
                                </div>
                                <h3 class="text-xl font-black text-gray-600 mb-2">No se encontraron certificados</h3>
                                <p class="text-sm text-gray-400 font-medium mb-8 max-w-sm">Aún no hay certificados individuales registrados.</p>
                                <a href="{{ route('admin.individual-certificates.create') }}" class="px-8 py-3.5 bg-[#FF7A00] text-white rounded-[1.2rem] font-black text-sm hover:bg-[#e66e00] transition-all shadow-xl shadow-[#FF7A00]/20">
                                    Crear mi primer certificado
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
        
        <!-- PAGINACIÓN -->
        <div class="px-8 py-6 bg-gray-50/30 border-t border-gray-50">
            {{ $certificates->links() }}
        </div>
    </div>

    <!-- MODAL DE DETALLES -->
    <div id="detailsModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 sm:p-6">
        <div class="absolute inset-0 bg-[#001144]/40 backdrop-blur-sm transition-opacity" onclick="closeDetailsModal()"></div>
        <div class="relative w-full max-w-2xl bg-white shadow-2xl rounded-2xl flex flex-col transform scale-95 opacity-0 transition-all duration-300 ease-out max-h-[90vh]" id="detailsModalPanel">
            <!-- Header -->
            <div class="p-8 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div>
                    <h3 class="text-xl font-black text-[#001144]" id="modal_cert_title">Detalles del Certificado</h3>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Información Completa y Estudiantes</p>
                </div>
                <button onclick="closeDetailsModal()" class="w-10 h-10 rounded-full bg-white shadow-sm text-gray-400 flex items-center justify-center hover:bg-gray-50 transition-all border border-gray-100">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto p-8 no-scrollbar space-y-8">
                <!-- Info Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Horas</span>
                        <p class="text-sm font-bold text-[#001144]" id="modal_cert_hours">0</p>
                    </div>
                    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Registro</span>
                        <p class="text-sm font-bold text-[#001144]" id="modal_cert_reg">-</p>
                    </div>
                    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Estado</span>
                        <p class="text-sm font-bold" id="modal_cert_status">-</p>
                    </div>
                </div>

                <!-- Students Table -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-black text-[#001144] uppercase tracking-widest">Estudiantes Registrados</h4>
                        <span class="px-3 py-1 bg-blue-50 text-[#001144] rounded-full text-[10px] font-black" id="modal_cert_student_count">0</span>
                    </div>
                    
                    <div class="border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Estudiante</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">DNI / CI</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="modal_students_table" class="divide-y divide-gray-50 text-xs">
                                <!-- JS Load -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DE FIRMAS -->
    <div id="signaturesModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-[#001144]/40 backdrop-blur-sm transition-opacity" onclick="closeSignaturesModal()"></div>
        <div class="absolute right-0 top-0 bottom-0 w-full max-w-md bg-white shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300 ease-out" id="signaturesModalPanel">
            <!-- Header -->
            <div class="p-8 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-black text-[#001144]">Configurar Firmas</h3>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Firmas Globales para Certificados</p>
                </div>
                <button onclick="closeSignaturesModal()" class="w-10 h-10 rounded-full bg-gray-50 text-gray-400 flex items-center justify-center hover:bg-gray-100 transition-all">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto p-8 no-scrollbar">
                <form id="signaturesForm" class="space-y-8">
                    @csrf
                    <!-- Firma Director Ejecutivo -->
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Director Ejecutivo</label>
                        <div class="relative group">
                            <div id="executivePreview" class="w-full h-32 rounded-2xl bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden mb-2">
                                <p class="text-[10px] font-black text-gray-300 uppercase">Sin firma cargada</p>
                            </div>
                            <input type="file" name="executive_signature" accept="image/*" class="absolute inset-0 w-full h-32 opacity-0 cursor-pointer z-10" onchange="previewSignature(this, 'executivePreview')">
                            <div class="text-center">
                                <span class="text-[10px] font-bold text-[#FF7A00] uppercase tracking-wider">Click para cambiar</span>
                            </div>
                        </div>
                    </div>

                    <!-- Firma Director Académico -->
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Director Académico</label>
                        <div class="relative group">
                            <div id="academicPreview" class="w-full h-32 rounded-2xl bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden mb-2">
                                <p class="text-[10px] font-black text-gray-300 uppercase">Sin firma cargada</p>
                            </div>
                            <input type="file" name="academic_signature" accept="image/*" class="absolute inset-0 w-full h-32 opacity-0 cursor-pointer z-10" onchange="previewSignature(this, 'academicPreview')">
                            <div class="text-center">
                                <span class="text-[10px] font-bold text-[#FF7A00] uppercase tracking-wider">Click para cambiar</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="p-8 border-t border-gray-100 bg-gray-50/50">
                <button type="button" onclick="saveSignatures()" id="saveSignaturesBtn" class="w-full py-4 bg-[#001144] text-white rounded-[1.2rem] font-black text-sm hover:bg-[#002266] transition-all shadow-xl shadow-[#001144]/20 flex items-center justify-center gap-2">
                    <i class="ph-bold ph-floppy-disk"></i> Guardar Configuración
                </button>
            </div>
        </div>
    </div>

    <script>
        const userPermissions = {
            view: {{ Auth::user()->hasPermission('individual-certificates.view') ? 'true' : 'false' }},
            edit: {{ Auth::user()->hasPermission('individual-certificates.edit') ? 'true' : 'false' }},
            delete: {{ Auth::user()->hasPermission('individual-certificates.delete') ? 'true' : 'false' }},
            students: {{ Auth::user()->hasPermission('individual-certificates.students') ? 'true' : 'false' }}
        };

        function openDetailsModal(certId, btn) {
            const modal = document.getElementById('detailsModal');
            const panel = document.getElementById('detailsModalPanel');
            const row = btn.closest('tr');
            
            // Poblar datos básicos inmediatamente desde los atributos data de la fila
            document.getElementById('modal_cert_title').innerText = row.dataset.title;
            document.getElementById('modal_cert_hours').innerText = row.dataset.hours + ' Horas';
            document.getElementById('modal_cert_reg').innerText = row.dataset.reg;
            
            const modalStatus = document.getElementById('modal_cert_status');
            modalStatus.innerText = row.dataset.statusText;
            modalStatus.className = 'text-sm font-bold ' + row.dataset.statusClass;

            // Mostrar el modal
            modal.classList.remove('hidden');
            setTimeout(() => {
                panel.classList.remove('scale-95', 'opacity-0');
                panel.classList.add('scale-100', 'opacity-100');
            }, 10);
            
            // Cargar estudiantes
            fetch(`/admin/individual-certificates/${certId}/estudiantes`)
                .then(res => res.json())
                .then(students => {
                    const tbody = document.getElementById('modal_students_table');
                    document.getElementById('modal_cert_student_count').innerText = students.length;
                    
                    if(students.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="3" class="px-6 py-10 text-center text-gray-400 italic">No hay estudiantes registrados</td></tr>';
                    } else {
                        tbody.innerHTML = students.map(s => `
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-bold text-[#001144]">${s.fullname}</td>
                                <td class="px-6 py-4 text-gray-500 font-medium">${s.dni}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="/certificado/ver/${s.uuid}" target="_blank" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center hover:bg-blue-500 hover:text-white transition-all" title="Ver Certificado">
                                            <i class="ph-bold ph-eye"></i>
                                        </a>
                                        \${userPermissions.students ? `
                                        <button onclick="deleteModalStudent(\${s.id}, this)" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all" title="Eliminar Estudiante">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                        ` : ''}
                                    </div>
                                </td>
                            </tr>
                        `).join('');
                    }
                })
                .catch(err => {
                    console.error("Error cargando estudiantes:", err);
                    document.getElementById('modal_students_table').innerHTML = '<tr><td colspan="3" class="px-6 py-10 text-center text-red-400 italic">Error al cargar estudiantes</td></tr>';
                });
        }

        function deleteModalStudent(studentId, btn) {
            if(!confirm('¿Seguro que desea eliminar a este estudiante?')) return;
            
            const row = btn.closest('tr');
            btn.disabled = true;
            btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i>';

            fetch(`/admin/individual-certificates/estudiantes/${studentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    row.remove();
                    const countEl = document.getElementById('modal_cert_student_count');
                    countEl.innerText = parseInt(countEl.innerText) - 1;
                    if(parseInt(countEl.innerText) === 0) {
                        document.getElementById('modal_students_table').innerHTML = '<tr><td colspan="3" class="px-6 py-10 text-center text-gray-400 italic">No hay estudiantes registrados</td></tr>';
                    }
                } else {
                    alert('Error al eliminar estudiante');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ph-bold ph-trash"></i>';
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error de conexión al eliminar');
                btn.disabled = false;
                btn.innerHTML = '<i class="ph-bold ph-trash"></i>';
            });
        }

        function closeDetailsModal() {
            const modal = document.getElementById('detailsModal');
            const panel = document.getElementById('detailsModalPanel');
            panel.classList.remove('scale-100', 'opacity-100');
            panel.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        function openSignaturesModal() {
            const modal = document.getElementById('signaturesModal');
            const panel = document.getElementById('signaturesModalPanel');
            modal.classList.remove('hidden');
            setTimeout(() => {
                panel.classList.remove('translate-x-full');
            }, 10);

            // Cargar firmas actuales
            fetch("{{ route('admin.individual-certificates.get-signatures') }}")
                .then(res => res.json())
                .then(data => {
                    if(data.executive) {
                        document.getElementById('executivePreview').innerHTML = `<img src="${data.executive}" class="h-full object-contain">`;
                    }
                    if(data.academic) {
                        document.getElementById('academicPreview').innerHTML = `<img src="${data.academic}" class="h-full object-contain">`;
                    }
                });
        }

        function closeSignaturesModal() {
            const modal = document.getElementById('signaturesModal');
            const panel = document.getElementById('signaturesModalPanel');
            panel.classList.add('translate-x-full');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function previewSignature(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(previewId).innerHTML = `<img src="${e.target.result}" class="h-full object-contain">`;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        async function saveSignatures() {
            const btn = document.getElementById('saveSignaturesBtn');
            const form = document.getElementById('signaturesForm');
            const formData = new FormData(form);

            btn.disabled = true;
            btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Guardando...';

            try {
                const response = await fetch("{{ route('admin.individual-certificates.update-signatures') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const result = await response.json();
                if(result.success) {
                    alert('Firmas actualizadas correctamente');
                    closeSignaturesModal();
                } else {
                    alert('Error al guardar las firmas');
                }
            } catch (error) {
                console.error(error);
                alert('Error de conexión');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="ph-bold ph-floppy-disk"></i> Guardar Configuración';
            }
        }
    </script>
</x-layouts.admin>
