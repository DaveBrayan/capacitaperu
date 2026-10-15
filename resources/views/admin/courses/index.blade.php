<x-layouts.admin>
    <x-slot name="title">{{ $viewType ?? 'Cursos' }} - Gestión</x-slot>
    <x-slot name="header">Gestión de {{ $viewType ?? 'Cursos' }}</x-slot>

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
                       id="course-search-input" 
                       value="{{ request('search') }}" 
                       placeholder="Buscar por título, instructor, staff o categoría..." 
                       autocomplete="off"
                       class="w-full pl-12 pr-10 py-3.5 rounded-[1.2rem] border border-gray-100 focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/5 outline-none transition-all text-sm font-medium bg-white shadow-sm">
                <button type="button" id="course-clear-btn" class="{{ request('search') ? '' : 'hidden' }} absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ph-bold ph-x-circle text-lg"></i>
                </button>
            </div>
            
            <div class="flex gap-2 flex-wrap">
                <select id="course-category-filter" class="px-5 py-3.5 rounded-[1.2rem] border border-gray-100 bg-white text-[#001144] text-xs font-black uppercase tracking-wider focus:border-[#FF7A00] outline-none cursor-pointer shadow-sm hover:bg-gray-50 transition-colors">
                    <option value="all">Todas las Categorías</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <select id="course-status-filter" class="px-5 py-3.5 rounded-[1.2rem] border border-gray-100 bg-white text-[#001144] text-xs font-black uppercase tracking-wider focus:border-[#FF7A00] outline-none cursor-pointer shadow-sm hover:bg-gray-50 transition-colors">
                    <option value="all">Todos los Estados</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publicado</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Borrador</option>
                </select>
            </div>
        </div>

        <!-- Botones de Acción Global -->
        <div class="flex gap-3 w-full md:w-auto">
            <button class="flex-1 md:flex-none flex items-center justify-center gap-2 px-6 py-3.5 bg-white border border-gray-100 text-[#001144] rounded-[1.2rem] hover:bg-gray-50 transition-all text-sm font-black shadow-sm">
                <i class="ph-bold ph-file-arrow-down text-lg"></i> Reporte
            </button>
            
            @if(Auth::user()->hasPermission('courses.create'))
            <a href="{{ route(($routeNamePrefix ?? 'admin.courses.') . 'create') }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-8 py-3.5 bg-[#001144] text-white rounded-[1.2rem] hover:bg-[#002266] transition-all shadow-xl shadow-[#001144]/20 text-sm font-black">
                <i class="ph-bold ph-plus"></i> Nuevo {{ $viewType ?? 'Curso' }}
            </a>
            @endif
        </div>
    </div>

    <!-- TABLA DE CURSOS -->
    <div id="courses-table-container" class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-100/50 border border-gray-50 overflow-hidden fade-in-up transition-opacity duration-200" style="animation-delay: 0.2s">
        @include('admin.courses.partials.table')
    </div>

    <!-- MODAL DETALLES DEL CURSO -->
    <div id="course-details-modal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-[#001144]/80 backdrop-blur-md transition-opacity duration-500" onclick="closeDetailsModal()"></div>
        <div class="fixed inset-0 z-[110] w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-[0_25px_80px_-15px_rgba(0,0,0,0.4)] transition-all w-full max-w-2xl border border-white/20 flex flex-col">
                    <div class="relative h-56 bg-gray-900">
                        <img id="modal-cover" src="" class="w-full h-full object-cover opacity-60">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#001144] to-transparent flex items-end p-8">
                            <div>
                                <span class="px-3 py-1 bg-[#FF7A00] text-white text-[10px] font-black uppercase tracking-widest rounded-full mb-3 inline-block">Vista Previa</span>
                                <h3 id="modal-title" class="text-2xl font-black text-white tracking-tight">Cargando título...</h3>
                            </div>
                        </div>
                        <button onclick="closeDetailsModal()" class="absolute top-6 right-6 w-10 h-10 bg-white/10 hover:bg-white/20 backdrop-blur-md text-white rounded-full flex items-center justify-center transition-all">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>
                    <div class="p-8 space-y-8">
                        <div class="flex justify-between items-center pb-6 border-b border-gray-50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-[#001144]">
                                    <i class="ph-fill ph-user text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-wider leading-none mb-1">Docente</p>
                                    <p id="modal-instructor" class="font-black text-[#001144] text-sm"></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-wider leading-none mb-1">Inversión</p>
                                <p class="font-black text-[#FF7A00] text-xl">{{ $platformSettings['app_currency'] ?? 'Bs' }} <span id="modal-price"></span></p>
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-wider mb-3">Descripción General</p>
                            <p id="modal-desc" class="text-sm text-gray-600 leading-relaxed font-medium"></p>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="p-5 bg-gray-50 rounded-[1.5rem] border border-gray-100 flex flex-col items-center">
                                <span class="text-2xl font-black text-[#001144] mb-1" id="modal-modules">0</span>
                                <span class="text-[9px] text-gray-400 uppercase font-black tracking-widest">Módulos</span>
                            </div>
                            <div class="p-5 bg-gray-50 rounded-[1.5rem] border border-gray-100 flex flex-col items-center">
                                <span class="text-2xl font-black text-[#001144] mb-1" id="modal-lessons">0</span>
                                <span class="text-[9px] text-gray-400 uppercase font-black tracking-widest">Clases</span>
                            </div>
                            <div class="p-5 bg-gray-50 rounded-[1.5rem] border border-gray-100 flex flex-col items-center">
                                <span class="text-2xl font-black text-[#001144] mb-1" id="modal-students">0</span>
                                <span class="text-[9px] text-gray-400 uppercase font-black tracking-widest">Alumnos</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DE REVISIÓN DE TRABAJOS -->
    <div id="grading-modal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-[#001144]/90 backdrop-blur-lg transition-opacity duration-500" onclick="closeGradingModal()"></div>
        <div class="fixed inset-0 z-[110] w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-[3rem] bg-[#f8faff] text-left shadow-[0_25px_80px_-15px_rgba(0,0,0,0.5)] transition-all w-full max-w-6xl border border-white/20 flex flex-col max-h-[90vh]">
                    
                    <div class="bg-[#001144] px-8 py-6 flex justify-between items-center shrink-0">
                        <div>
                            <h3 class="text-xl font-black text-white tracking-tight flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-[#FF7A00] flex items-center justify-center">
                                    <i class="ph-bold ph-check-square-offset text-white"></i>
                                </div>
                                Evaluación Académica
                            </h3>
                            <p class="text-white/40 text-xs font-bold uppercase tracking-wider mt-1">Control de proyectos finales y calificaciones</p>
                        </div>
                        <button onclick="closeGradingModal()" class="w-10 h-10 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center transition-all">
                            <i class="ph-bold ph-x text-xl"></i>
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto p-8 custom-scrollbar" id="submissions-container">
                        <!-- AJAX CONTENT -->
                        <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                            <div class="w-16 h-16 rounded-full border-4 border-gray-100 border-t-[#FF7A00] animate-spin mb-4"></div>
                            <p class="font-black text-xs uppercase tracking-widest">Sincronizando entregas...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        function showCourseDetails(id) {
            fetch(`/admin/cursos/${id}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('modal-title').innerText = data.title;
                    document.getElementById('modal-cover').src = data.cover || 'https://via.placeholder.com/800x400?text=No+Cover';
                    document.getElementById('modal-instructor').innerText = data.instructor;
                    document.getElementById('modal-students').innerText = data.students_count;
                    document.getElementById('modal-price').innerText = data.price;
                    document.getElementById('modal-desc').innerText = data.description || 'Sin descripción disponible.';
                    document.getElementById('modal-modules').innerText = data.modules_count;
                    document.getElementById('modal-lessons').innerText = data.lessons_count;
                    
                    document.getElementById('course-details-modal').classList.remove('hidden');
                });
        }

        function closeDetailsModal() {
            document.getElementById('course-details-modal').classList.add('hidden');
        }

        function showGradeModal(courseId) {
            document.getElementById('grading-modal').classList.remove('hidden');
            loadSubmissions(courseId);
        }

        function closeGradingModal() {
            document.getElementById('grading-modal').classList.add('hidden');
        }

        function loadSubmissions(courseId) {
            const container = document.getElementById('submissions-container');
            container.innerHTML = '<div class="flex flex-col items-center justify-center py-20 text-gray-400"><div class="w-16 h-16 rounded-full border-4 border-gray-100 border-t-[#FF7A00] animate-spin mb-4"></div><p class="font-black text-xs uppercase tracking-widest">Sincronizando entregas...</p></div>';

            fetch(`/admin/cursos/${courseId}/trabajos`)
                .then(res => res.json())
                .then(data => {
                    if(data.length === 0) {
                        container.innerHTML = `
                            <div class="flex flex-col items-center justify-center py-20 bg-white rounded-[2rem] border-2 border-dashed border-gray-100">
                                <div class="w-20 h-20 rounded-full bg-gray-50 flex items-center justify-center mb-6">
                                    <i class="ph-duotone ph-folder-notched text-4xl text-gray-200"></i>
                                </div>
                                <h3 class="text-lg font-black text-gray-600 mb-1">Sin entregas registradas</h3>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Aún no hay alumnos que hayan subido su proyecto final</p>
                            </div>
                        `;
                        return;
                    }

                    let html = `
                        <div class="overflow-x-auto rounded-[2rem] border border-gray-100 shadow-xl shadow-gray-200/20 bg-white">
                            <table class="w-full text-sm text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] border-b border-gray-50">
                                        <th class="px-8 py-5">Alumno</th>
                                        <th class="px-8 py-5">Entregables</th>
                                        <th class="px-8 py-5 text-center">Estado</th>
                                        <th class="px-8 py-5 w-[400px]">Retroalimentación</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                    `;

                    data.forEach(sub => {
                        const statusColor = sub.status === 'approved' ? 'bg-green-50 text-green-500 border-green-100' : 'bg-orange-50 text-[#FF7A00] border-orange-100';
                        const statusText = sub.status === 'approved' ? 'Calificado' : 'Pendiente';
                        
                        let filesHtml = '';
                        sub.files.forEach((f, idx) => {
                            const name = f.split('/').pop(); 
                            const url = sub.file_urls[idx];
                            filesHtml += `
                                <a href="${url}" target="_blank" class="flex items-center gap-3 px-4 py-2.5 rounded-xl bg-gray-50 text-xs font-black text-[#001144] hover:bg-[#001144] hover:text-white transition-all group/file">
                                    <i class="ph-bold ph-download-simple text-base text-[#FF7A00] group-hover/file:text-white"></i>
                                    <span class="truncate max-w-[180px]" title="${name}">${name}</span>
                                </a>
                            `;
                        });

                        html += `
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-6 align-top">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center font-black text-[#001144] text-base border-2 border-white shadow-sm ring-1 ring-gray-100">
                                            ${sub.student_name.charAt(0)}
                                        </div>
                                        <div>
                                            <div class="font-black text-[#001144] mb-1">${sub.student_name}</div>
                                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-2">${sub.student_email}</div>
                                            <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-gray-100 text-[9px] font-black text-gray-500 uppercase tracking-wider">
                                                <i class="ph-bold ph-calendar"></i> ${sub.date}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 align-top">
                                    <div class="flex flex-col gap-2">
                                        ${filesHtml}
                                    </div>
                                </td>
                                <td class="px-8 py-6 align-top text-center">
                                    <span class="inline-flex items-center px-4 py-1.5 text-[10px] uppercase font-black tracking-widest rounded-full border ${statusColor}">
                                        ${statusText}
                                    </span>
                                </td>
                                <td class="px-8 py-6 align-top">
                                    <form onsubmit="submitGrade(event, ${sub.id})" class="flex flex-col gap-4">
                                        <div class="flex gap-3">
                                            <div class="w-28 relative">
                                                <input type="number" name="score" value="${sub.score || ''}" min="0" max="100" placeholder="0" class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-100 text-sm focus:border-[#FF7A00] focus:ring-4 focus:ring-[#FF7A00]/5 outline-none font-black text-[#FF7A00] text-center" required>
                                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-gray-300 font-black pointer-events-none">PTS</span>
                                            </div>
                                            <button type="submit" class="flex-1 px-6 py-3 bg-[#001144] text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-[#002266] transition-all shadow-lg shadow-[#001144]/20">
                                                Publicar Nota
                                            </button>
                                        </div>
                                        <textarea name="feedback" rows="2" class="w-full px-5 py-3 rounded-xl border border-gray-100 text-xs font-medium focus:border-[#FF7A00] outline-none resize-none bg-gray-50 focus:bg-white transition-all placeholder:text-gray-300" placeholder="Escribe un comentario motivador o feedback técnico...">${sub.feedback || ''}</textarea>
                                    </form>
                                </td>
                            </tr>
                        `;
                    });

                    html += `
                                </tbody>
                            </table>
                        </div>
                    `;
                    container.innerHTML = html;
                });
        }

        function submitGrade(e, subId) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const btn = form.querySelector('button');
            const originalText = btn.innerText;
            
            btn.innerText = 'ENVIANDO...';
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');

            fetch(`/admin/trabajos/${subId}/calificar`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    const row = form.closest('tr');
                    const badge = row.querySelector('td:nth-child(3) span');
                    
                    badge.className = 'inline-flex items-center px-4 py-1.5 text-[10px] uppercase font-black tracking-widest rounded-full border bg-green-50 text-green-500 border-green-100';
                    badge.innerText = 'Calificado';
                    
                    btn.innerText = '¡EXITO!';
                    btn.classList.remove('bg-[#001144]');
                    btn.classList.add('bg-green-500');
                    
                    setTimeout(() => {
                        btn.innerText = 'Actualizar Nota';
                        btn.disabled = false;
                        btn.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-green-500');
                        btn.classList.add('bg-[#001144]');
                    }, 3000);
                } else {
                    alert('Error: ' + data.message);
                    btn.innerText = originalText;
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            })
            .catch(err => {
                console.error(err);
                btn.innerText = originalText;
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            });
        }

        // ==========================================
        // FILTRADO Y BÚSQUEDA AJAX EN TIEMPO REAL
        // ==========================================
        let courseSearchTimer = null;
        const courseSearchInput = document.getElementById('course-search-input');
        const courseCategoryFilter = document.getElementById('course-category-filter');
        const courseStatusFilter = document.getElementById('course-status-filter');
        const courseClearBtn = document.getElementById('course-clear-btn');
        const coursesTableContainer = document.getElementById('courses-table-container');

        function fetchCourses(url = null) {
            const searchVal = courseSearchInput ? courseSearchInput.value.trim() : '';
            const categoryVal = courseCategoryFilter ? courseCategoryFilter.value : 'all';
            const statusVal = courseStatusFilter ? courseStatusFilter.value : 'all';

            if (courseClearBtn) {
                courseClearBtn.classList.toggle('hidden', searchVal.length === 0);
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

            if (coursesTableContainer) {
                coursesTableContainer.style.opacity = '0.5';
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
                if (coursesTableContainer) {
                    coursesTableContainer.innerHTML = html;
                    coursesTableContainer.style.opacity = '1';
                }
            })
            .catch(err => {
                console.error('Error al cargar cursos:', err);
                if (coursesTableContainer) {
                    coursesTableContainer.style.opacity = '1';
                }
            });
        }

        if (courseSearchInput) {
            courseSearchInput.addEventListener('input', function() {
                clearTimeout(courseSearchTimer);
                courseSearchTimer = setTimeout(() => {
                    fetchCourses();
                }, 250);
            });
        }

        if (courseClearBtn) {
            courseClearBtn.addEventListener('click', function() {
                courseSearchInput.value = '';
                fetchCourses();
                courseSearchInput.focus();
            });
        }

        if (courseCategoryFilter) {
            courseCategoryFilter.addEventListener('change', () => fetchCourses());
        }

        if (courseStatusFilter) {
            courseStatusFilter.addEventListener('change', () => fetchCourses());
        }

        if (coursesTableContainer) {
            coursesTableContainer.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (link && link.href && (link.closest('.pagination') || link.classList.contains('page-link') || link.href.includes('page='))) {
                    e.preventDefault();
                    fetchCourses(link.href);
                }
            });
        }
    </script>
</x-layouts.admin>