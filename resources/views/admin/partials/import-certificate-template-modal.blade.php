<!-- MODAL IMPORTAR PLANTILLA DE CERTIFICADO -->
<div id="modal-import-template" class="fixed inset-0 z-[150] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4 animate-fade-in">
    <div class="bg-white rounded-3xl max-w-4xl w-full max-h-[90vh] flex flex-col shadow-2xl border border-gray-100 overflow-hidden transform transition-all duration-300">
        
        <!-- HEADER -->
        <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-[#001144] text-[#FF7A00] flex items-center justify-center font-black">
                    <i class="ph-bold ph-layout text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-[#001144]">Importar Plantilla de Certificado</h3>
                    <p class="text-xs text-gray-400 font-medium">Selecciona una plantilla predefinida para cargar su diseño, sellos, firmas y disposición.</p>
                </div>
            </div>
            <button type="button" onclick="closeImportTemplateModal()" class="w-9 h-9 rounded-xl text-gray-400 hover:text-gray-700 hover:bg-gray-100 flex items-center justify-center transition-colors">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>

        <!-- SEARCH / FILTER BAR -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between gap-4">
            <div class="relative flex-1 max-w-md">
                <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="import-template-search" oninput="filterImportTemplates(this.value)" 
                       placeholder="Buscar plantilla por título..." 
                       class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:outline-none focus:border-[#FF7A00] transition-colors">
            </div>
            <a href="{{ route('admin.certificate-templates.index') }}" target="_blank" class="text-xs font-bold text-[#FF7A00] hover:underline flex items-center gap-1">
                <span>Administrar Plantillas</span>
                <i class="ph-bold ph-arrow-square-out text-sm"></i>
            </a>
        </div>

        <!-- LISTA / GRID DE PLANTILLAS -->
        <div class="flex-1 overflow-y-auto p-6 custom-scrollbar" id="import-template-container">
            <div id="import-template-loading" class="flex flex-col items-center justify-center py-16 text-gray-400 gap-3">
                <div class="w-10 h-10 border-4 border-[#FF7A00]/20 border-t-[#FF7A00] rounded-full animate-spin"></div>
                <span class="text-xs font-bold">Cargando plantillas disponibles...</span>
            </div>

            <div id="import-template-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 hidden">
                <!-- Se rellena vía JS -->
            </div>

            <div id="import-template-empty" class="hidden flex-col items-center justify-center py-16 text-center text-gray-400 gap-3">
                <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-400 mb-2">
                    <i class="ph-duotone ph-layout text-3xl"></i>
                </div>
                <h4 class="text-sm font-black text-gray-700">No se encontraron plantillas</h4>
                <p class="text-xs text-gray-400 max-w-sm">No hay plantillas guardadas aún o ninguna coincide con tu búsqueda.</p>
                <a href="{{ route('admin.certificate-templates.create') }}" target="_blank" class="mt-2 px-4 py-2 bg-[#FF7A00] text-white text-xs font-bold rounded-xl shadow">
                    Crear Nueva Plantilla
                </a>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="p-4 border-t border-gray-100 bg-gray-50 flex items-center justify-end gap-3">
            <button type="button" onclick="closeImportTemplateModal()" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl text-xs font-bold hover:bg-gray-100 transition-colors">
                Cerrar
            </button>
        </div>
    </div>
</div>

<script>
    let cachedTemplatesList = [];

    window.openImportTemplateModal = async function() {
        const modal = document.getElementById('modal-import-template');
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        const searchInput = document.getElementById('import-template-search');
        if (searchInput) searchInput.value = '';

        const loading = document.getElementById('import-template-loading');
        const grid = document.getElementById('import-template-grid');
        const empty = document.getElementById('import-template-empty');

        if (loading) loading.classList.remove('hidden');
        if (grid) grid.classList.add('hidden');
        if (empty) empty.classList.add('hidden');

        try {
            const resp = await fetch("{{ route('admin.certificate-templates.api.list') }}");
            const data = await resp.json();
            if (data && data.success && Array.isArray(data.templates)) {
                cachedTemplatesList = data.templates;
                renderImportTemplates(cachedTemplatesList);
            } else {
                showEmptyTemplates();
            }
        } catch (e) {
            console.error('Error cargando plantillas:', e);
            showEmptyTemplates();
        }
    };

    window.closeImportTemplateModal = function() {
        const modal = document.getElementById('modal-import-template');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    function showEmptyTemplates() {
        const loading = document.getElementById('import-template-loading');
        const grid = document.getElementById('import-template-grid');
        const empty = document.getElementById('import-template-empty');
        if (loading) loading.classList.add('hidden');
        if (grid) grid.classList.add('hidden');
        if (empty) empty.classList.remove('hidden');
    }

    function renderImportTemplates(list) {
        const loading = document.getElementById('import-template-loading');
        const grid = document.getElementById('import-template-grid');
        const empty = document.getElementById('import-template-empty');

        if (loading) loading.classList.add('hidden');

        if (!list || list.length === 0) {
            if (grid) grid.classList.add('hidden');
            if (empty) empty.classList.remove('hidden');
            return;
        }

        if (empty) empty.classList.add('hidden');
        if (grid) {
            grid.classList.remove('hidden');
            grid.innerHTML = '';

            list.forEach(tpl => {
                const data = tpl.template_data || {};
                const pages = data.pages || 1;
                const elCount = (data.elements ? data.elements.length : 0) + (data.elements2 ? data.elements2.length : 0);
                const bg = data.background ? (data.background.startsWith('/') || data.background.startsWith('http') ? data.background : '/storage/' + data.background) : null;

                const card = document.createElement('div');
                card.className = 'bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-lg transition-all flex flex-col group hover:border-[#FF7A00]';

                card.innerHTML = `
                    <div class="h-32 bg-slate-100 relative overflow-hidden flex items-center justify-center border-b border-gray-100">
                        ${bg ? `<img src="${bg}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">` : `
                            <div class="flex flex-col items-center text-slate-400 gap-1">
                                <i class="ph-duotone ph-certificate text-3xl"></i>
                                <span class="text-[9px] font-bold uppercase">Sin Fondo</span>
                            </div>
                        `}
                        <div class="absolute top-2 left-2 flex gap-1">
                            <span class="px-2 py-0.5 bg-[#001144]/90 text-white text-[9px] font-black rounded shadow uppercase">
                                ${pages == 2 ? '2 Hojas' : '1 Hoja'}
                            </span>
                        </div>
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-0.5 bg-white/90 text-[#FF7A00] text-[9px] font-bold rounded shadow">
                                ${elCount} elementos
                            </span>
                        </div>
                    </div>
                    <div class="p-4 flex-1 flex flex-col justify-between gap-3">
                        <div>
                            <h4 class="font-black text-xs text-[#001144] line-clamp-1 group-hover:text-[#FF7A00] transition-colors" title="${tpl.title}">
                                ${tpl.title}
                            </h4>
                            ${tpl.description ? `<p class="text-[10px] text-gray-400 line-clamp-1 mt-0.5">${tpl.description}</p>` : ''}
                        </div>
                        <button type="button" onclick="applyImportTemplate(${tpl.id})" 
                                class="w-full py-2 px-3 bg-[#001144] hover:bg-[#FF7A00] text-white rounded-xl text-xs font-black transition-all flex items-center justify-center gap-1.5 shadow-sm">
                            <i class="ph-bold ph-check"></i>
                            <span>Usar Plantilla</span>
                        </button>
                    </div>
                `;
                grid.appendChild(card);
            });
        }
    }

    function filterImportTemplates(query) {
        if (!cachedTemplatesList) return;
        const q = (query || '').toLowerCase().trim();
        if (!q) {
            renderImportTemplates(cachedTemplatesList);
            return;
        }
        const filtered = cachedTemplatesList.filter(t => (t.title || '').toLowerCase().includes(q) || (t.description || '').toLowerCase().includes(q));
        renderImportTemplates(filtered);
    }

    window.applyImportTemplate = async function(tplId) {
        const tpl = cachedTemplatesList.find(t => t.id === tplId);
        if (!tpl || !tpl.template_data) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudieron leer los datos de la plantilla seleccionada.' });
            } else {
                alert('No se pudieron leer los datos de la plantilla seleccionada.');
            }
            return;
        }

        // Si ya hay elementos en el diseño, confirmar reemplazo
        const currentElementsCount = (certState.elements ? certState.elements.length : 0) + (certState.elements2 ? certState.elements2.length : 0);
        if (currentElementsCount > 0) {
            if (typeof Swal !== 'undefined') {
                const result = await Swal.fire({
                    title: '¿Reemplazar diseño actual?',
                    text: `Se aplicará la plantilla "${tpl.title}" con sus posiciones, elementos, fuentes, colores y fondos.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#FF7A00',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Sí, aplicar plantilla',
                    cancelButtonText: 'Cancelar'
                });
                if (!result.isConfirmed) return;
            } else {
                if (!confirm(`¿Deseas reemplazar el diseño actual con la plantilla "${tpl.title}"?`)) return;
            }
        }

        const data = tpl.template_data;
        const resolveUrl = (u) => {
            if (!u) return null;
            return typeof fixUrl === 'function' ? fixUrl(u) : u;
        };

        // 1. Clonar estado de la plantilla
        certState.pages = parseInt(data.pages) || 1;
        certState.background = resolveUrl(data.background);
        certState.background2 = resolveUrl(data.background2);
        certState.elements = JSON.parse(JSON.stringify(data.elements || []));
        certState.elements2 = JSON.parse(JSON.stringify(data.elements2 || []));

        // 2. Actualizar selector de páginas en UI
        const pagesSelect = document.getElementById('cert-pages-select');
        if (pagesSelect) pagesSelect.value = certState.pages;
        if (typeof changePageCount === 'function') {
            changePageCount(certState.pages);
        }

        // 3. Adaptar tags dinámicos a los metadatos de este certificado si la función existe
        if (typeof window.updateDynamicElements === 'function') {
            window.updateDynamicElements();
        }

        // 4. Renderizar canvas y actualizar input oculto
        deselectAll();
        setActivePage(1);
        renderCanvas();
        if (typeof window.updateCertificateInput === 'function') {
            window.updateCertificateInput();
        }

        closeImportTemplateModal();

        // 5. Notificación de éxito
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: '¡Plantilla aplicada!',
                text: `Se cargó la plantilla "${tpl.title}" con éxito. Puedes personalizarla y guardar el certificado cuando estés listo.`,
                timer: 3000,
                showConfirmButton: false
            });
        } else if (typeof showToast === 'function') {
            showToast(`¡Plantilla "${tpl.title}" importada con éxito!`);
        }
    };
</script>
