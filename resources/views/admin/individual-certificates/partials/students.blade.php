<div class="space-y-8">
    <div class="flex items-center justify-between bg-gray-50 p-6 rounded-[2rem] border border-gray-100">
        <div>
            <h3 class="text-xl font-black text-[#001144] tracking-tight">Estudiantes Asignados</h3>
            <p class="text-xs text-gray-400 font-medium mt-1">Gestiona los beneficiarios de este certificado individual.</p>
        </div>
        @if(Auth::user()->hasPermission('individual-certificates.students'))
        <button type="button" onclick="openStudentModal()" 
                class="flex items-center gap-3 px-6 py-3 bg-[#FF7A00] text-white rounded-2xl hover:bg-[#001144] transition-all duration-500 shadow-lg shadow-orange-200">
            <i class="ph-bold ph-plus text-lg"></i>
            <span class="font-black text-[10px] uppercase tracking-widest">Añadir Estudiante</span>
        </button>
        @endif
    </div>

    <!-- TABLA DE ESTUDIANTES -->
    <div class="overflow-hidden bg-white rounded-[2rem] border border-gray-100 shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100 w-16">N°</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100">Nombre Completo</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100">DNI / CI</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100">Tipo</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100">Reg. N°</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100">Detalles</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100">Registro / Auditoría</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody id="students-table-body">
                <!-- Se carga vía JS -->
            </tbody>
        </table>
    </div>
</div>

<script>
    const hasStudentsPermission = {{ Auth::user()->hasPermission('individual-certificates.students') ? 'true' : 'false' }};

    // Utilidad robusta para formatear fechas a YYYY-MM-DD independientemente de si es un String o un Objeto Carbon
    function formatDateString(val) {
        if (!val) return '';
        if (typeof val === 'string') {
            return val.includes('T') ? val.split('T')[0] : val.split(' ')[0];
        }
        if (val.date && typeof val.date === 'string') {
            return val.date.split(' ')[0];
        }
        return '';
    }

    // Inicializar con estudiantes existentes de la BD o con los datos enviados anteriormente en caso de error de validación
    (function() {
        try {
            console.log("--> Inicializando variable global window.studentsList");
            let rawList = @json(
                old('students_json') 
                    ? (is_array(json_decode(old('students_json'), true)) ? array_values(json_decode(old('students_json'), true)) : []) 
                    : ($certificate->exists ? $certificate->students->values() : [])
            );
            if (rawList && typeof rawList === 'object' && !Array.isArray(rawList)) {
                window.studentsList = Object.values(rawList);
            } else {
                window.studentsList = Array.isArray(rawList) ? rawList : [];
            }
            console.log("Estado inicial de window.studentsList:", window.studentsList);
        } catch (e) {
            console.error("Error al inicializar la lista de estudiantes:", e);
            window.studentsList = [];
        }
    })();

    document.addEventListener('DOMContentLoaded', () => {
        renderStudentsTable();

        // Recalcular y re-renderizar la tabla si se modifican los campos base en la primera pestaña
        const baseRegInput = document.querySelector('input[name="registration_number"]');
        const certEndDateInput = document.querySelector('input[name="end_date"]');

        if (baseRegInput) {
            baseRegInput.addEventListener('input', () => {
                if (window.studentsList && window.studentsList.length > 0) {
                    renderStudentsTable();
                }
            });
        }

        if (certEndDateInput) {
            certEndDateInput.addEventListener('change', () => {
                if (window.studentsList && window.studentsList.length > 0) {
                    renderStudentsTable();
                }
            });
        }
    });

    // Calcular números de registro en el cliente idéntico al servidor
    function calculateRegistrationNumbers() {
        try {
            const baseInput = document.querySelector('input[name="registration_number"]');
            const baseReg = baseInput ? baseInput.value.trim() : '';
            
            let baseNum = 0;
            let prefix = baseReg;
            let suffix = '';
            
            const matches = baseReg.match(/^(.*?)(\d+)([^\d]*)$/);
            if (matches) {
                prefix = matches[1];
                baseNum = parseInt(matches[2], 10);
                suffix = matches[3];
            }
            
            let standardCount = 0;
            const customDateGroups = {};
            let groupCount = 0;
            
            if (!window.studentsList || !Array.isArray(window.studentsList)) {
                let rawList = window.studentsList;
                if (rawList && typeof rawList === 'object') {
                    window.studentsList = Object.values(rawList);
                } else {
                    window.studentsList = [];
                }
            }
            
            window.studentsList.forEach(s => {
                const isCustom = !!s.is_custom;
                if (!isCustom) {
                    standardCount++;
                    const computedNum = baseNum + standardCount;
                    s.registration_number = prefix + computedNum + suffix;
                } else {
                    let issueDate = formatDateString(s.issue_date) || formatDateString(s.end_date) || '';
                    if (!issueDate) {
                        const certEndDateInput = document.querySelector('input[name="end_date"]');
                        issueDate = certEndDateInput ? certEndDateInput.value : 'default';
                    }
                    
                    if (!customDateGroups[issueDate]) {
                        groupCount++;
                        customDateGroups[issueDate] = {
                            group_index: groupCount,
                            student_count: 0
                        };
                    }
                    
                    customDateGroups[issueDate].student_count++;
                    const groupIndex = customDateGroups[issueDate].group_index;
                    const studentInGroupIndex = customDateGroups[issueDate].student_count;
                    
                    const computedNum = baseNum + (groupIndex * 100) + studentInGroupIndex;
                    s.registration_number = prefix + computedNum + suffix;
                }
            });
        } catch (e) {
            console.error("Error calculating registration numbers:", e);
        }
    }

    function renderStudentsTable() {
        console.log("--> Ejecutando renderStudentsTable()");
        const tbody = document.getElementById('students-table-body');
        if (!tbody) {
            console.error("Error: No se encontró el elemento tbody 'students-table-body'");
            return;
        }
        
        try {
            if (!window.studentsList || !Array.isArray(window.studentsList)) {
                let rawList = window.studentsList;
                if (rawList && typeof rawList === 'object') {
                    window.studentsList = Object.values(rawList);
                } else {
                    window.studentsList = [];
                }
            }

            console.log(`Renderizando tabla con ${window.studentsList.length} estudiantes`);

            if (window.studentsList.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300 mb-4">
                                    <i class="ph-duotone ph-users text-4xl"></i>
                                </div>
                                <p class="text-sm text-gray-400 font-medium">No hay estudiantes en la lista.</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            console.log("Calculando números de registro...");
            calculateRegistrationNumbers();
            console.log("Números calculados. Generando HTML de filas...");

            tbody.innerHTML = window.studentsList.map((s, index) => {
                const isCustom = !!s.is_custom;
                
                const typeBadge = isCustom 
                    ? `<span class="bg-[#FF7A00]/10 text-[#FF7A00] px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">Personalizado</span>`
                    : `<span class="bg-blue-50 text-[#001144] px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">Heredado</span>`;
                    
                let details = '';
                if (isCustom) {
                    details = `<div class="text-[10px] font-bold text-gray-500 space-y-0.5">
                        <div><span class="text-gray-400">Horas:</span> ${s.hours || '---'}</div>
                        <div><span class="text-gray-400">Emisión:</span> ${formatDateString(s.issue_date) || '---'}</div>
                        <div><span class="text-gray-400">Fechas:</span> ${formatDateString(s.start_date) || '---'} a ${formatDateString(s.end_date) || '---'}</div>
                    </div>`;
                } else {
                    details = `<span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Hereda datos base</span>`;
                }

                // Auditoría: Fecha de creación/actualización y Usuario
                let createdDate = s.created_at ? formatDateString(s.created_at) : 'Hoy';
                let creatorName = s.user && s.user.name ? s.user.name : (window.currentAdminName || 'Sistema');
                
                // Si no tiene ID en la base de datos es un registro nuevo
                let isNew = !s.id;
                let isUpdated = s.updated_at && s.created_at && (formatDateString(s.created_at) !== formatDateString(s.updated_at));
                
                let auditDetails = `<div class="text-[10px] font-bold text-gray-500 space-y-0.5">
                    <div><span class="text-gray-400">Por:</span> <span class="text-[#001144]">${creatorName}</span></div>
                    <div><span class="text-gray-400">Fecha:</span> ${createdDate}</div>
                    ${isNew ? `<span class="inline-block bg-orange-50 text-[#FF7A00] text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md mt-0.5 border border-[#FF7A00]/20">Por Guardar</span>` : ''}
                    ${(!isNew && isUpdated) ? `<div class="text-[9px] text-[#FF7A00] font-black uppercase tracking-wider mt-0.5 flex items-center gap-1"><i class="ph-bold ph-pencil-simple text-[10px]"></i> Modificado</div>` : ''}
                </div>`;

                return `
                <tr class="group hover:bg-gray-50/50 transition-colors">
                    <td class="px-8 py-5 border-b border-gray-100">
                        <span class="text-xs font-black text-gray-400">${index + 1}</span>
                    </td>
                    <td class="px-8 py-5 border-b border-gray-100">
                        <span class="text-sm font-bold text-[#001144]">${s.fullname}</span>
                    </td>
                    <td class="px-8 py-5 border-b border-gray-100">
                        <span class="text-xs font-medium text-gray-500">${s.dni}</span>
                    </td>
                    <td class="px-8 py-5 border-b border-gray-100">
                        ${typeBadge}
                    </td>
                    <td class="px-8 py-5 border-b border-gray-100">
                        <span class="text-xs font-mono font-bold text-[#001144]">${s.registration_number || '---'}</span>
                    </td>
                    <td class="px-8 py-5 border-b border-gray-100">
                        ${details}
                    </td>
                    <td class="px-8 py-5 border-b border-gray-100">
                        ${auditDetails}
                    </td>
                    <td class="px-8 py-5 border-b border-gray-100 text-right">
                        ${hasStudentsPermission ? `
                        <div class="flex items-center justify-end gap-2">
                            <button type="button" onclick="editStudentFromList(${index})" 
                                    class="w-9 h-9 rounded-xl bg-orange-50 text-[#FF7A00] hover:bg-[#FF7A00] hover:text-white transition-all duration-300 flex items-center justify-center"
                                    title="Editar Estudiante">
                                <i class="ph-bold ph-pencil-simple"></i>
                            </button>
                            <button type="button" onclick="removeStudentFromList(${index})" 
                                    class="w-9 h-9 rounded-xl bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition-all duration-300 flex items-center justify-center"
                                    title="Eliminar Estudiante">
                                <i class="ph-bold ph-trash"></i>
                            </button>
                        </div>
                        ` : ''}
                    </td>
                </tr>
                `;
            }).join('');
        } catch (e) {
            console.error("Error rendering students table:", e);
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="px-8 py-10 text-center text-red-500 font-bold bg-red-50 rounded-2xl">
                        Error al renderizar la tabla: ${e.message}
                    </td>
                </tr>
            `;
        }
    }

    function editStudentFromList(index) {
        try {
            const student = window.studentsList[index];
            window.editingStudentIndex = index;
            
            const modal = document.getElementById('student-modal');
            const title = modal.querySelector('h3');
            const desc = modal.querySelector('p');
            const btn = modal.querySelector('button[onclick="addStudentToList()"]');
            
            document.getElementById('modal_full_name').value = student.fullname;
            document.getElementById('modal_id_number').value = student.dni;
            
            // Cargar campos personalizados de manera segura usando el formateador robusto
            document.getElementById('modal_start_date').value = formatDateString(student.start_date);
            document.getElementById('modal_end_date').value = formatDateString(student.end_date);
            document.getElementById('modal_hours').value = student.hours || '';
            document.getElementById('modal_issue_date').value = formatDateString(student.issue_date);
            
            if (typeof setStudentCustom === 'function') {
                setStudentCustom(!!student.is_custom);
            }
            
            title.innerText = 'Editar Estudiante';
            desc.innerText = 'Modifica los datos del beneficiario.';
            btn.innerText = 'Actualizar Estudiante';
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } catch (e) {
            console.error("Error editing student:", e);
            alert("Error al cargar datos del estudiante: " + e.message);
        }
    }

    function removeStudentFromList(index) {
        if(!confirm('¿Seguro que desea quitar a este estudiante de la lista?')) return;
        try {
            window.studentsList.splice(index, 1);
            renderStudentsTable();
        } catch (e) {
            console.error("Error removing student:", e);
        }
    }

    // Sincronizar el array con el input oculto antes de enviar el form principal
    window.syncStudentsJSON = function() {
        try {
            const input = document.getElementById('students_json');
            if (input) {
                if (!window.studentsList || !Array.isArray(window.studentsList)) {
                    let rawList = window.studentsList;
                    if (rawList && typeof rawList === 'object') {
                        window.studentsList = Object.values(rawList);
                    } else {
                        window.studentsList = [];
                    }
                }
                
                // Clean dates in JavaScript to ensure YYYY-MM-DD
                const cleanedList = window.studentsList.map(s => {
                    const cleanStudent = { ...s };
                    cleanStudent.start_date = formatDateString(cleanStudent.start_date) || null;
                    cleanStudent.end_date = formatDateString(cleanStudent.end_date) || null;
                    cleanStudent.issue_date = formatDateString(cleanStudent.issue_date) || null;
                    return cleanStudent;
                });
                
                input.value = JSON.stringify(cleanedList);
            }
        } catch (e) {
            console.error("Error syncing students JSON:", e);
        }
    };
</script>
