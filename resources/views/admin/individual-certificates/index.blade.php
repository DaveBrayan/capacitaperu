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
        <div class="flex flex-wrap gap-4 w-full md:w-auto items-center">
            <div class="relative w-full md:w-96">
                <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" 
                       id="cert-search-input" 
                       value="{{ request('search') }}"
                       placeholder="Buscar por título, registro, instructor, staff..." 
                       autocomplete="off"
                       class="w-full pl-12 pr-10 py-3.5 rounded-[1.2rem] border border-gray-100 focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/5 outline-none transition-all text-sm font-medium bg-white shadow-sm">
                <button type="button" id="cert-clear-btn" class="{{ request('search') ? '' : 'hidden' }} absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ph-bold ph-x-circle text-lg"></i>
                </button>
            </div>

            <div class="flex gap-2 flex-wrap">
                <select id="cert-category-filter" class="px-5 py-3.5 rounded-[1.2rem] border border-gray-100 bg-white text-[#001144] text-xs font-black uppercase tracking-wider focus:border-[#FF7A00] outline-none cursor-pointer shadow-sm hover:bg-gray-50 transition-colors">
                    <option value="all">Todas las Categorías</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <select id="cert-status-filter" class="px-5 py-3.5 rounded-[1.2rem] border border-gray-100 bg-white text-[#001144] text-xs font-black uppercase tracking-wider focus:border-[#FF7A00] outline-none cursor-pointer shadow-sm hover:bg-gray-50 transition-colors">
                    <option value="all">Todos los Estados</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publicado</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Borrador</option>
                </select>
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
    <div id="certificates-table-container" class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-100/50 border border-gray-50 overflow-hidden fade-in-up transition-opacity duration-200" style="animation-delay: 0.2s">
        @include('admin.individual-certificates.partials.table')
    </div>

    <!-- MODAL DE DETALLES (se teletransporta a body via JS) -->

    <div id="detailsModal" class="fixed inset-0 z-[9999] items-center justify-center p-4 sm:p-6" style="display:none;">
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

    <!-- MODAL DE FIRMAS Y SELLOS -->
    <div id="signaturesModal" class="fixed inset-0 z-[9999]" style="display:none;">
        <div class="absolute inset-0 bg-[#001144]/40 backdrop-blur-sm transition-opacity" onclick="closeSignaturesModal()"></div>
        <div class="absolute right-0 top-0 bottom-0 w-full max-w-xl bg-white shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300 ease-out" id="signaturesModalPanel">
            <!-- Header -->
            <div class="p-6 md:p-8 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-black text-[#001144]">Configurar Firmas y Sellos</h3>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-0.5">Gerente General & Coordinadora Académica</p>
                </div>
                <button onclick="closeSignaturesModal()" class="w-10 h-10 rounded-full bg-gray-50 text-gray-400 flex items-center justify-center hover:bg-gray-100 transition-all">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto p-6 md:p-8 space-y-8 no-scrollbar">
                <form id="signaturesForm" class="space-y-8">
                    @csrf
                    
                    <!-- BLOQUE 1: GERENTE GENERAL -->
                    <div class="p-5 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-4">
                        <div class="flex items-center gap-2.5 pb-2 border-b border-gray-200/60">
                            <div class="w-7 h-7 rounded-lg bg-[#FF7A00]/10 text-[#FF7A00] flex items-center justify-center font-black">
                                <i class="ph-bold ph-briefcase"></i>
                            </div>
                            <h4 class="text-xs font-black text-[#001144] uppercase tracking-wider">Gerente General</h4>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Firma Gerente General -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Firma en PDF / PNG</label>
                                <div class="relative group">
                                    <div id="executivePreview" class="w-full h-28 rounded-xl bg-white border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden p-2 group-hover:border-[#FF7A00] transition-colors">
                                        <p class="text-[9px] font-black text-gray-300 uppercase">Sin firma</p>
                                    </div>
                                    <input type="file" name="executive_signature" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewSignature(this, 'executivePreview')">
                                </div>
                                <span class="text-[9px] font-bold text-[#FF7A00] uppercase tracking-wider block text-center">Cambiar Firma</span>
                            </div>

                            <!-- Sello Gerente General -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Sello Institucional</label>
                                <div class="relative group">
                                    <div id="executiveSealPreview" class="w-full h-28 rounded-xl bg-white border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden p-2 group-hover:border-[#FF7A00] transition-colors">
                                        <p class="text-[9px] font-black text-gray-300 uppercase">Sin sello</p>
                                    </div>
                                    <input type="file" name="executive_seal" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewSignature(this, 'executiveSealPreview')">
                                </div>
                                <span class="text-[9px] font-bold text-[#FF7A00] uppercase tracking-wider block text-center">Cambiar Sello</span>
                            </div>
                        </div>
                    </div>

                    <!-- BLOQUE 2: COORDINADORA ACADÉMICA -->
                    <div class="p-5 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-4">
                        <div class="flex items-center gap-2.5 pb-2 border-b border-gray-200/60">
                            <div class="w-7 h-7 rounded-lg bg-[#001144]/10 text-[#001144] flex items-center justify-center font-black">
                                <i class="ph-bold ph-graduation-cap"></i>
                            </div>
                            <h4 class="text-xs font-black text-[#001144] uppercase tracking-wider">Coordinadora Académica</h4>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Firma Coordinadora Académica -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Firma en PDF / PNG</label>
                                <div class="relative group">
                                    <div id="academicPreview" class="w-full h-28 rounded-xl bg-white border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden p-2 group-hover:border-[#FF7A00] transition-colors">
                                        <p class="text-[9px] font-black text-gray-300 uppercase">Sin firma</p>
                                    </div>
                                    <input type="file" name="academic_signature" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewSignature(this, 'academicPreview')">
                                </div>
                                <span class="text-[9px] font-bold text-[#FF7A00] uppercase tracking-wider block text-center">Cambiar Firma</span>
                            </div>

                            <!-- Sello Coordinadora Académica -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Sello Institucional</label>
                                <div class="relative group">
                                    <div id="academicSealPreview" class="w-full h-28 rounded-xl bg-white border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden p-2 group-hover:border-[#FF7A00] transition-colors">
                                        <p class="text-[9px] font-black text-gray-300 uppercase">Sin sello</p>
                                    </div>
                                    <input type="file" name="academic_seal" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewSignature(this, 'academicSealPreview')">
                                </div>
                                <span class="text-[9px] font-bold text-[#FF7A00] uppercase tracking-wider block text-center">Cambiar Sello</span>
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
        // Mover modales al body para evitar que overflow-hidden los recorte
        document.addEventListener('DOMContentLoaded', function() {
            var dm = document.getElementById('detailsModal');
            var sm = document.getElementById('signaturesModal');
            if (dm) document.body.appendChild(dm);
            if (sm) document.body.appendChild(sm);
        });
    </script>

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
            modal.style.display = 'flex';
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
                                        ${userPermissions.students ? `
                                        <button onclick="deleteModalStudent(${s.id}, this)" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all" title="Eliminar Estudiante">
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
            setTimeout(() => { modal.style.display = 'none'; }, 300);
        }

        function openSignaturesModal() {
            const modal = document.getElementById('signaturesModal');
            const panel = document.getElementById('signaturesModalPanel');
            modal.style.display = 'block';
            setTimeout(() => {
                panel.classList.remove('translate-x-full');
            }, 10);

            // Cargar firmas y sellos actuales
            fetch("{{ route('admin.individual-certificates.get-signatures') }}")
                .then(res => res.json())
                .then(data => {
                    if(data.executive) {
                        document.getElementById('executivePreview').innerHTML = `<img src="${data.executive}" class="h-full object-contain">`;
                    } else {
                        document.getElementById('executivePreview').innerHTML = `<p class="text-[9px] font-black text-gray-300 uppercase">Sin firma</p>`;
                    }

                    if(data.academic) {
                        document.getElementById('academicPreview').innerHTML = `<img src="${data.academic}" class="h-full object-contain">`;
                    } else {
                        document.getElementById('academicPreview').innerHTML = `<p class="text-[9px] font-black text-gray-300 uppercase">Sin firma</p>`;
                    }

                    if(data.executive_seal) {
                        document.getElementById('executiveSealPreview').innerHTML = `<img src="${data.executive_seal}" class="h-full object-contain">`;
                    } else {
                        document.getElementById('executiveSealPreview').innerHTML = `<p class="text-[9px] font-black text-gray-300 uppercase">Sin sello</p>`;
                    }

                    if(data.academic_seal) {
                        document.getElementById('academicSealPreview').innerHTML = `<img src="${data.academic_seal}" class="h-full object-contain">`;
                    } else {
                        document.getElementById('academicSealPreview').innerHTML = `<p class="text-[9px] font-black text-gray-300 uppercase">Sin sello</p>`;
                    }
                });
        }

        function closeSignaturesModal() {
            const modal = document.getElementById('signaturesModal');
            const panel = document.getElementById('signaturesModalPanel');
            panel.classList.add('translate-x-full');
            setTimeout(() => {
                modal.style.display = 'none';
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

        // ==========================================
        // FILTRADO Y BÚSQUEDA AJAX EN TIEMPO REAL (CERTIFICADOS)
        // ==========================================
        let certSearchTimer = null;
        const certSearchInput = document.getElementById('cert-search-input');
        const certCategoryFilter = document.getElementById('cert-category-filter');
        const certStatusFilter = document.getElementById('cert-status-filter');
        const certClearBtn = document.getElementById('cert-clear-btn');
        const certTableContainer = document.getElementById('certificates-table-container');

        function fetchCertificates(url = null) {
            const searchVal = certSearchInput ? certSearchInput.value.trim() : '';
            const categoryVal = certCategoryFilter ? certCategoryFilter.value : 'all';
            const statusVal = certStatusFilter ? certStatusFilter.value : 'all';

            if (certClearBtn) {
                certClearBtn.classList.toggle('hidden', searchVal.length === 0);
            }

            let targetUrl = url;
            if (!targetUrl) {
                const params = new URLSearchParams();
                if (searchVal) params.set('search', searchVal);
                if (categoryVal && categoryVal !== 'all') params.set('category', categoryVal);
                if (statusVal && statusVal !== 'all') params.set('status', statusVal);
                
                const qs = params.toString();
                targetUrl = window.location.pathname + (qs ? '?' + qs : '');
            }

            window.history.replaceState({}, '', targetUrl);

            if (certTableContainer) {
                certTableContainer.style.opacity = '0.5';
            }

            fetch(targetUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Error al filtrar');
                return res.text();
            })
            .then(html => {
                if (certTableContainer) {
                    certTableContainer.innerHTML = html;
                    certTableContainer.style.opacity = '1';
                }
            })
            .catch(err => {
                console.error('Error al cargar certificados:', err);
                if (certTableContainer) {
                    certTableContainer.style.opacity = '1';
                }
            });
        }

        if (certSearchInput) {
            certSearchInput.addEventListener('input', function() {
                clearTimeout(certSearchTimer);
                certSearchTimer = setTimeout(() => {
                    fetchCertificates();
                }, 250);
            });
        }

        if (certClearBtn) {
            certClearBtn.addEventListener('click', function() {
                certSearchInput.value = '';
                fetchCertificates();
                certSearchInput.focus();
            });
        }

        if (certCategoryFilter) {
            certCategoryFilter.addEventListener('change', () => fetchCertificates());
        }

        if (certStatusFilter) {
            certStatusFilter.addEventListener('change', () => fetchCertificates());
        }

        if (certTableContainer) {
            certTableContainer.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (link && link.href && (link.closest('.pagination') || link.classList.contains('page-link') || link.href.includes('page='))) {
                    e.preventDefault();
                    fetchCertificates(link.href);
                }
            });
        }
    </script>
</x-layouts.admin>
