<!-- MODAL BIBLIOTECA DE MEDIOS MEJORADO (AMPLIO, 2 COLUMNAS, DESENFOQUE TOTAL, VISTA PREVIA GIGANTE, RENOMBRAR, REORDENAR POR ARRASTRE Y TEMA NARANJA) -->
<style>
    /* Efecto de desenfoque al sidebar y resto del sistema cuando el modal está abierto */
    body.media-modal-open #sidebar {
        filter: blur(10px) brightness(0.65) !important;
        pointer-events: none !important;
        transition: filter 0.25s ease;
    }
    body.media-modal-open header,
    body.media-modal-open main {
        filter: blur(8px) brightness(0.75);
        pointer-events: none;
        transition: filter 0.25s ease;
    }
    /* Estilo de arrastre para reordenar como iconos */
    .media-card-dragging {
        opacity: 0.45 !important;
        transform: scale(0.95);
        border: 2px dashed #FF7A00 !important;
    }
    .media-card-dragover {
        border-color: #FF7A00 !important;
        box-shadow: 0 0 0 3px rgba(255, 122, 0, 0.3) !important;
        transform: scale(1.02);
    }
</style>

<div id="media-modal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center p-2 sm:p-4 md:p-6"
     ondragover="handleModalGlobalDragOver(event)"
     ondragleave="handleModalGlobalDragLeave(event)"
     ondrop="handleModalGlobalDrop(event)">
    
    <!-- Backdrop oscuro con blur que cubre toda la pantalla incluyendo el sidebar -->
    <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-md transition-opacity" onclick="closeMediaManager()"></div>

    <!-- Contenedor Principal del Modal (Agrandado a lo largo: max-w-[1560px], 92vh) -->
    <div class="relative z-10 bg-white rounded-[2.5rem] shadow-2xl w-full max-w-[1560px] h-[92vh] max-h-[960px] flex flex-col overflow-hidden border border-gray-100 transition-all animate-fade-in">
        
        <!-- HEADER DEL MODAL -->
        <div class="px-8 py-4 bg-white border-b border-gray-100 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-[#FF7A00] flex items-center justify-center font-black shadow-sm">
                    <i class="ph-duotone ph-images text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-black text-xl text-[#001144] tracking-tight">Biblioteca de Medios</h3>
                    <p class="text-xs text-gray-400 font-medium" id="media-modal-subtitle">
                        Selecciona o sube imágenes para el fondo, sellos o firmas del certificado
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <button type="button" onclick="loadMediaLibrary()" title="Recargar galería" 
                        class="w-10 h-10 rounded-xl border border-gray-200 text-gray-500 hover:text-[#FF7A00] hover:border-[#FF7A00]/40 hover:bg-orange-50/50 flex items-center justify-center transition-all">
                    <i class="ph-bold ph-arrow-clockwise text-lg"></i>
                </button>
                <button type="button" onclick="closeMediaManager()" title="Cerrar modal" 
                        class="w-10 h-10 rounded-xl border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50 flex items-center justify-center transition-all">
                    <i class="ph-bold ph-x text-xl"></i>
                </button>
            </div>
        </div>

        <!-- CUERPO DEL MODAL (2 COLUMNAS: IZQUIERDA VISUALIZACIÓN MÁS GRANDE / DERECHA GRILLA Y SUBIDA) -->
        <div class="flex-1 flex flex-col md:flex-row overflow-hidden relative">

            <!-- COLUMNA IZQUIERDA: VISUALIZACIÓN MÁS GRANDE, RENOMBRAR Y ACCIÓN -->
            <div class="w-full md:w-[460px] lg:w-[500px] xl:w-[540px] flex-shrink-0 bg-slate-50/90 border-r border-gray-200/80 flex flex-col p-6 overflow-y-auto justify-between select-none">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-black uppercase tracking-widest text-gray-400 flex items-center gap-1.5">
                            <i class="ph-bold ph-eye text-[#FF7A00]"></i> Vista Previa
                        </span>
                        <span id="media-preview-badge" class="hidden text-[10px] font-black px-2.5 py-0.5 rounded-full bg-orange-100 text-[#FF7A00]">
                            Seleccionada
                        </span>
                    </div>

                    <!-- ESTADO VACÍO (NINGUNA IMAGEN SELECCIONADA) -->
                    <div id="media-preview-empty" class="border-2 border-dashed border-gray-200 rounded-3xl p-8 text-center flex flex-col items-center justify-center min-h-[380px] bg-white/70">
                        <div class="w-20 h-20 rounded-3xl bg-orange-50/60 text-[#FF7A00]/50 flex items-center justify-center mb-4">
                            <i class="ph-duotone ph-image-square text-5xl"></i>
                        </div>
                        <h4 class="text-base font-black text-gray-700 mb-1.5">Sin imagen seleccionada</h4>
                        <p class="text-xs text-gray-400 max-w-xs leading-relaxed">
                            Haz clic en cualquier imagen de la biblioteca para previsualizarla en tamaño grande, renombrarla y utilizarla.
                        </p>
                    </div>

                    <!-- ESTADO ACTIVO (IMAGEN SELECCIONADA CON VISUALIZACIÓN GRANDE) -->
                    <div id="media-preview-active" class="hidden space-y-4 animate-fade-in">
                        <!-- Marco de visualización grande (h-80 a h-96) -->
                        <div class="w-full h-80 xl:h-96 bg-white rounded-3xl border border-gray-200 p-3.5 flex items-center justify-center overflow-hidden relative shadow-inner"
                             style="background-image: radial-gradient(#d1d5db 1.2px, transparent 1.2px); background-size: 16px 16px;">
                            <img id="media-preview-img" src="" alt="Vista previa" 
                                 class="max-h-full max-w-full object-contain rounded-xl transition-transform duration-200 hover:scale-105">
                            <div id="media-preview-dims" class="absolute bottom-3 right-3 px-2.5 py-1 rounded-lg bg-black/70 backdrop-blur-xs text-[11px] font-black text-white tracking-wide shadow-md">
                                -- × --
                            </div>
                        </div>

                        <!-- SECCIÓN PARA RENOMBRAR LA IMAGEN DEBAJO DE LA VISTA PREVIA -->
                        <div class="bg-white rounded-2xl border border-gray-200 p-4 space-y-3 shadow-sm">
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-wider text-gray-400 block mb-1">Nombre del Archivo</label>
                                <div class="flex items-center gap-2">
                                    <input type="text" id="media-rename-input" 
                                           class="flex-1 px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-[#001144] focus:bg-white focus:border-[#FF7A00] focus:ring-2 focus:ring-[#FF7A00]/20 outline-none transition-all"
                                           placeholder="Nombre de la imagen"
                                           onkeydown="if(event.key==='Enter'){ event.preventDefault(); saveMediaRename(); }">
                                    <button type="button" id="media-btn-rename" onclick="saveMediaRename()" 
                                            class="px-4 py-2.5 bg-[#FF7A00] hover:bg-[#E66E00] text-white rounded-xl text-xs font-black flex items-center gap-1.5 shadow-sm shadow-[#FF7A00]/25 transition-all whitespace-nowrap active:scale-95">
                                        <i class="ph-bold ph-floppy-disk text-sm"></i>
                                        <span>Guardar Nombre</span>
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 pt-2.5 border-t border-gray-100 text-[11px]">
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-gray-400 block">Tamaño</span>
                                    <span id="media-preview-size" class="font-bold text-gray-600">--</span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-gray-400 block">Modificado</span>
                                    <span id="media-preview-date" class="font-bold text-gray-600">--</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BOTÓN UTILIZAR IMAGEN (EN NARANJA #FF7A00 COMO EL TEMA) -->
                <div class="mt-4 pt-4 border-t border-gray-200 space-y-2">
                    <button type="button" id="media-btn-use" onclick="confirmSelectedMedia()" disabled
                            class="w-full py-4 px-5 bg-[#FF7A00] hover:bg-[#E66E00] text-white rounded-2xl font-black text-sm flex items-center justify-center gap-2 shadow-xl shadow-[#FF7A00]/30 disabled:opacity-40 disabled:cursor-not-allowed disabled:shadow-none transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                        <i class="ph-bold ph-check-circle text-xl"></i>
                        <span>Utilizar Imagen</span>
                    </button>
                    
                    <button type="button" id="media-btn-delete" onclick="handleDeleteCurrentPreview()" 
                            class="w-full py-2 px-3 text-red-500 hover:bg-red-50 rounded-xl font-bold text-xs flex items-center justify-center gap-1.5 transition-colors hidden">
                        <i class="ph-bold ph-trash"></i>
                        <span>Eliminar de la biblioteca</span>
                    </button>
                </div>
            </div>

            <!-- COLUMNA DERECHA: BÚSQUEDA, DROPZONE Y GRILLA DE 4 COLUMNAS (REORDENABLE POR ARRASTRE) -->
            <div class="flex-1 flex flex-col overflow-hidden bg-white">
                
                <!-- BARRA SUPERIOR: BÚSQUEDA Y MENSAJE DE REORDENAMIENTO (SIN SELECT DE ORDENAR) -->
                <div class="p-5 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4 bg-white flex-shrink-0">
                    <div class="flex items-center gap-3.5 flex-1 min-w-[240px]">
                        <div class="relative w-full max-w-md">
                            <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" id="media-search-input" oninput="renderFilteredMedia()" placeholder="Buscar por nombre..." 
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-700 focus:bg-white focus:border-[#FF7A00] focus:ring-2 focus:ring-[#FF7A00]/15 outline-none transition-all">
                        </div>
                        <span id="media-count-badge" class="text-xs font-black text-gray-400 whitespace-nowrap bg-gray-100 px-3 py-1.5 rounded-lg">0 imágenes</span>
                    </div>

                    <div class="flex items-center gap-2 text-xs font-bold text-gray-400">
                        <i class="ph-bold ph-hand-grabbing text-[#FF7A00] text-sm"></i>
                        <span>Arrastra las imágenes para organizarlas</span>
                    </div>
                </div>

                <!-- ZONA DE SUBIDA / DRAG & DROP -->
                <div class="p-5 pb-2 flex-shrink-0">
                    <div id="media-dropzone" 
                         ondragover="handleMediaDragOver(event)" 
                         ondragleave="handleMediaDragLeave(event)" 
                         ondrop="handleMediaDrop(event)"
                         onclick="triggerMediaFileInput()"
                         class="border-2 border-dashed border-gray-200 hover:border-[#FF7A00] rounded-2xl p-4 bg-slate-50/60 hover:bg-orange-50/30 transition-all flex items-center justify-between gap-4 cursor-pointer group">
                        
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-white border border-gray-200 group-hover:border-[#FF7A00] text-[#FF7A00] flex items-center justify-center flex-shrink-0 shadow-sm group-hover:scale-105 transition-all">
                                <i class="ph-duotone ph-cloud-arrow-up text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-[#001144] group-hover:text-[#FF7A00] transition-colors">
                                    Arrastra tu imagen aquí o <span class="text-[#FF7A00] underline">haz clic para examinar</span>
                                </h4>
                                <p class="text-[11px] text-gray-400 font-medium">PNG, JPG, WEBP, SVG (Máximo 2MB)</p>
                            </div>
                        </div>

                        <button type="button" class="px-5 py-2.5 bg-[#FF7A00] text-white rounded-xl text-xs font-black flex items-center gap-1.5 shadow-md shadow-[#FF7A00]/25 pointer-events-none group-hover:bg-[#E66E00] transition-colors">
                            <i class="ph-bold ph-upload-simple text-sm"></i> Subir Imagen
                        </button>
                        <input type="file" id="media-file-input" class="hidden" accept="image/*" onchange="uploadMediaFile(this)">
                    </div>
                    
                    <!-- Feedback visual al subir -->
                    <div id="media-upload-loader" class="hidden mt-2 p-3 bg-orange-50 border border-orange-200 rounded-xl flex items-center gap-3 animate-fade-in">
                        <div class="w-5 h-5 border-2 border-[#FF7A00] border-t-transparent rounded-full animate-spin"></div>
                        <span class="text-xs font-bold text-[#FF7A00]">Subiendo y procesando imagen a la biblioteca...</span>
                    </div>
                </div>

                <!-- GRILLA SCROLLEABLE DE 4 COLUMNAS (COMPACTAS, LO DEMÁS VA ABAJO) -->
                <div class="flex-1 overflow-y-auto p-5 pt-2">
                    <div id="media-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3.5"></div>
                    
                    <div id="media-empty-msg" class="hidden py-16 text-center text-gray-400 flex flex-col items-center justify-center">
                        <i class="ph-duotone ph-magnifying-glass text-5xl mb-2 text-gray-300"></i>
                        <p class="font-bold text-sm text-gray-600">No se encontraron imágenes</p>
                        <p class="text-xs text-gray-400 mt-1">Prueba con otro término de búsqueda o arrastra una imagen aquí para subirla.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // ESTADO DE LA BIBLIOTECA DE MEDIOS
    let allMediaFiles = [];
    let selectedMediaItemData = null;
    let isInternalCardDrag = false;
    let draggedCardIndex = null;

    // Asegurar que mediaRoutes exista
    window.mediaRoutes = window.mediaRoutes || {
        list: "{{ route('admin.media.list') }}",
        upload: "{{ route('admin.media.upload') }}",
        rename: "{{ route('admin.media.rename') }}",
        delete: "{{ route('admin.media.delete') }}"
    };

    /**
     * Abre el modal de la biblioteca de medios
     */
    function openMediaManager(ctx) {
        mediaContext = ctx;
        const modal = document.getElementById('media-modal');
        if (modal) {
            // Mover el modal a document.body para que cubra toda la ventana y el sidebar sin estar atrapado
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
            modal.classList.remove('hidden');
        }

        // Aplicar desenfoque al sidebar y al contenido detrás
        document.body.classList.add('media-modal-open');

        // Subtítulo dinámico según contexto
        const subtitle = document.getElementById('media-modal-subtitle');
        if (subtitle) {
            subtitle.innerText = (ctx === 'background') 
                ? 'Selecciona una imagen para el fondo de la hoja actual del certificado' 
                : 'Selecciona una imagen, sello o firma para añadir al certificado';
        }

        // Limpiar búsqueda y resetear preview
        const searchInput = document.getElementById('media-search-input');
        if (searchInput) searchInput.value = '';
        resetMediaPreview();

        loadMediaLibrary();
    }

    /**
     * Cierra el modal
     */
    function closeMediaManager() {
        const modal = document.getElementById('media-modal');
        if (modal) modal.classList.add('hidden');
        // Quitar desenfoque del sidebar
        document.body.classList.remove('media-modal-open');
    }

    /**
     * Carga las imágenes desde el backend y aplica el orden personalizado
     */
    function loadMediaLibrary(autoSelectUrl = null) {
        const grid = document.getElementById('media-grid');
        const emptyMsg = document.getElementById('media-empty-msg');
        if (emptyMsg) emptyMsg.classList.add('hidden');
        if (grid) {
            grid.innerHTML = '<div class="col-span-full py-16 text-center"><div class="inline-block w-8 h-8 border-4 border-gray-200 border-t-[#FF7A00] rounded-full animate-spin"></div><p class="text-xs text-gray-400 font-bold mt-3">Cargando biblioteca...</p></div>';
        }

        fetch(window.mediaRoutes.list)
            .then(r => r.json())
            .then(files => {
                let items = (files || []).map(f => {
                    if (typeof f === 'string') {
                        return {
                            url: f,
                            name: f.split('/').pop(),
                            size: 0,
                            last_modified: 0
                        };
                    }
                    return {
                        url: f.url || '',
                        name: f.name || (f.url ? f.url.split('/').pop() : 'imagen'),
                        size: f.size || 0,
                        last_modified: f.last_modified || 0
                    };
                });

                // Cargar orden personalizado de localStorage si existe
                const savedOrder = getCustomMediaOrder();
                if (savedOrder && savedOrder.length > 0) {
                    items.sort((a, b) => {
                        const idxA = savedOrder.indexOf(a.url);
                        const idxB = savedOrder.indexOf(b.url);
                        if (idxA === -1 && idxB === -1) return 0;
                        if (idxA === -1) return -1; // Nuevos al principio
                        if (idxB === -1) return 1;
                        return idxA - idxB;
                    });
                }

                allMediaFiles = items;
                renderFilteredMedia();

                if (autoSelectUrl) {
                    const found = allMediaFiles.find(item => item.url === autoSelectUrl);
                    if (found) selectMediaForPreview(found);
                } else if (selectedMediaItemData) {
                    const found = allMediaFiles.find(item => item.url === selectedMediaItemData.url);
                    if (found) selectMediaForPreview(found);
                }
            })
            .catch(err => {
                console.error("Error al cargar medios:", err);
                if (grid) grid.innerHTML = '<div class="col-span-full py-12 text-center text-red-500 font-bold text-xs">Error al conectar con la biblioteca de medios.</div>';
            });
    }

    /**
     * Filtra las imágenes según el input de búsqueda
     */
    function getFilteredFiles() {
        const searchVal = (document.getElementById('media-search-input')?.value || '').trim().toLowerCase();
        if (!searchVal) return allMediaFiles;
        return allMediaFiles.filter(item => {
            const name = (item.name || '').toLowerCase();
            return name.includes(searchVal);
        });
    }

    function renderFilteredMedia() {
        const list = getFilteredFiles();
        const countBadge = document.getElementById('media-count-badge');
        if (countBadge) {
            countBadge.innerText = `${list.length} ${list.length === 1 ? 'imagen' : 'imágenes'}`;
        }
        renderMediaGrid(list);
    }

    /**
     * Guarda el orden en localStorage
     */
    function saveCustomMediaOrder() {
        try {
            const urls = allMediaFiles.map(i => i.url);
            localStorage.setItem('cape_media_custom_order', JSON.stringify(urls));
        } catch(e) {}
    }

    function getCustomMediaOrder() {
        try {
            const str = localStorage.getItem('cape_media_custom_order');
            return str ? JSON.parse(str) : null;
        } catch(e) { return null; }
    }

    /**
     * Dibuja los elementos en la grilla derecha con soporte de arrastre para reordenar
     */
    function renderMediaGrid(items) {
        const grid = document.getElementById('media-grid');
        const emptyMsg = document.getElementById('media-empty-msg');
        if (!grid) return;

        grid.innerHTML = '';

        if (!items || items.length === 0) {
            if (emptyMsg) emptyMsg.classList.remove('hidden');
            return;
        }

        if (emptyMsg) emptyMsg.classList.add('hidden');

        items.forEach((item, index) => {
            const isSelected = selectedMediaItemData && selectedMediaItemData.url === item.url;
            const card = document.createElement('div');
            card.draggable = true;
            card.className = `group aspect-square bg-white rounded-2xl overflow-hidden border transition-all p-2 relative flex flex-col justify-between cursor-grab active:cursor-grabbing select-none ${
                isSelected 
                    ? 'border-[#FF7A00] ring-4 ring-[#FF7A00]/20 shadow-md bg-orange-50/15' 
                    : 'border-gray-200 hover:border-[#FF7A00]/60 hover:shadow-md hover:-translate-y-0.5'
            }`;

            // ARRASTRE PARA REORGANIZAR COMO ICONOS DE ESCRITORIO
            card.ondragstart = (e) => {
                isInternalCardDrag = true;
                draggedCardIndex = index;
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', String(index));
                card.classList.add('media-card-dragging');
            };

            card.ondragend = () => {
                isInternalCardDrag = false;
                draggedCardIndex = null;
                card.classList.remove('media-card-dragging');
                document.querySelectorAll('.media-card-dragover').forEach(el => el.classList.remove('media-card-dragover'));
            };

            card.ondragover = (e) => {
                if (!isInternalCardDrag) return;
                e.preventDefault();
                e.stopPropagation();
                e.dataTransfer.dropEffect = 'move';
                card.classList.add('media-card-dragover');
            };

            card.ondragleave = () => {
                card.classList.remove('media-card-dragover');
            };

            card.ondrop = (e) => {
                if (!isInternalCardDrag) return;
                e.preventDefault();
                e.stopPropagation();
                card.classList.remove('media-card-dragover');

                if (draggedCardIndex !== null && draggedCardIndex !== index) {
                    // Mover elemento en el array
                    const itemToMove = allMediaFiles.splice(draggedCardIndex, 1)[0];
                    allMediaFiles.splice(index, 0, itemToMove);
                    saveCustomMediaOrder();
                    renderFilteredMedia();
                }
                isInternalCardDrag = false;
                draggedCardIndex = null;
            };

            // Doble clic: aplicar directamente
            card.ondblclick = () => {
                selectMediaForPreview(item);
                confirmSelectedMedia();
            };

            // Clic simple: previsualizar en la columna izquierda
            card.onclick = () => selectMediaForPreview(item);

            const checkBadge = isSelected 
                ? '<div class="absolute top-2 left-2 w-6 h-6 rounded-full bg-[#FF7A00] text-white flex items-center justify-center shadow z-10"><i class="ph-bold ph-check text-xs"></i></div>' 
                : '';

            card.innerHTML = `
                ${checkBadge}
                <div class="flex-1 w-full flex items-center justify-center overflow-hidden rounded-xl bg-gray-50/60 p-1 pointer-events-none">
                    <img src="${item.url}" alt="${item.name}" loading="lazy" class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-200">
                </div>
                <div class="pt-1.5 flex items-center justify-between text-[11px] text-gray-600 font-bold truncate pointer-events-none">
                    <span class="truncate pr-1" title="${item.name}">${item.name}</span>
                    <span class="text-[9px] text-gray-400 font-semibold flex-shrink-0">${item.size ? formatBytesHelper(item.size) : ''}</span>
                </div>
                <button type="button" 
                        class="absolute top-2 right-2 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow opacity-0 group-hover:opacity-100 transition-opacity z-10" 
                        onclick="confirmDeleteMedia(event, '${item.url}')" title="Eliminar Imagen">
                    <i class="ph-bold ph-trash text-xs"></i>
                </button>
            `;

            grid.appendChild(card);
        });
    }

    /**
     * Muestra la imagen seleccionada en la columna izquierda en tamaño grande
     */
    function selectMediaForPreview(item) {
        selectedMediaItemData = item;

        const emptyView = document.getElementById('media-preview-empty');
        const activeView = document.getElementById('media-preview-active');
        const badge = document.getElementById('media-preview-badge');
        const img = document.getElementById('media-preview-img');
        const dims = document.getElementById('media-preview-dims');
        const renameInput = document.getElementById('media-rename-input');
        const sizeEl = document.getElementById('media-preview-size');
        const dateEl = document.getElementById('media-preview-date');
        const btnUse = document.getElementById('media-btn-use');
        const btnDel = document.getElementById('media-btn-delete');

        if (emptyView) emptyView.classList.add('hidden');
        if (activeView) activeView.classList.remove('hidden');
        if (badge) badge.classList.remove('hidden');

        if (img) {
            dims.innerText = 'Calculando...';
            img.src = item.url;
            img.onload = function() {
                if (dims) dims.innerText = `${this.naturalWidth} × ${this.naturalHeight} px`;
            };
        }

        if (renameInput) {
            renameInput.value = item.name;
        }
        if (sizeEl) sizeEl.innerText = formatBytesHelper(item.size);
        if (dateEl) dateEl.innerText = formatDateHelper(item.last_modified);

        if (btnUse) btnUse.disabled = false;
        if (btnDel) btnDel.classList.remove('hidden');

        // Refrescar el borde activo en la grilla
        renderFilteredMedia();
    }

    /**
     * Resetea el panel de vista previa
     */
    function resetMediaPreview() {
        selectedMediaItemData = null;
        const emptyView = document.getElementById('media-preview-empty');
        const activeView = document.getElementById('media-preview-active');
        const badge = document.getElementById('media-preview-badge');
        const btnUse = document.getElementById('media-btn-use');
        const btnDel = document.getElementById('media-btn-delete');

        if (emptyView) emptyView.classList.remove('hidden');
        if (activeView) activeView.classList.add('hidden');
        if (badge) badge.classList.add('hidden');
        if (btnUse) btnUse.disabled = true;
        if (btnDel) btnDel.classList.add('hidden');
    }

    /**
     * Guarda el nuevo nombre de la imagen
     */
    function saveMediaRename() {
        if (!selectedMediaItemData || !selectedMediaItemData.url) return;
        const renameInput = document.getElementById('media-rename-input');
        const btnRename = document.getElementById('media-btn-rename');
        const newName = (renameInput?.value || '').trim();

        if (!newName) {
            Swal.fire({
                icon: 'warning',
                title: 'Nombre requerido',
                text: 'Por favor, introduce un nombre para el archivo.',
                confirmButtonColor: '#FF7A00'
            });
            return;
        }

        const originalBtnHtml = btnRename ? btnRename.innerHTML : '';
        if (btnRename) {
            btnRename.innerHTML = '<div class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>';
            btnRename.disabled = true;
        }

        const formData = new FormData();
        formData.append('url', selectedMediaItemData.url);
        formData.append('new_name', newName);
        formData.append('_token', '{{ csrf_token() }}');

        fetch(window.mediaRoutes.rename, {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (btnRename) {
                btnRename.innerHTML = originalBtnHtml;
                btnRename.disabled = false;
            }

            if (data.success && data.url) {
                const oldUrl = selectedMediaItemData.url;
                const newUrl = data.url;

                // Actualizar si está en uso en el diseño actual del canvas
                if (certState.background === oldUrl) certState.background = newUrl;
                if (certState.background2 === oldUrl) certState.background2 = newUrl;
                if (certState.elements) {
                    certState.elements.forEach(el => { if(el.src === oldUrl) el.src = newUrl; });
                }
                if (certState.elements2) {
                    certState.elements2.forEach(el => { if(el.src === oldUrl) el.src = newUrl; });
                }
                if (typeof renderCanvas === 'function') renderCanvas();

                // Actualizar item actual
                selectedMediaItemData.name = data.name;
                selectedMediaItemData.url = data.url;
                if (renameInput) renameInput.value = data.name;

                // Actualizar en el array
                const idx = allMediaFiles.findIndex(f => f.url === oldUrl);
                if (idx !== -1) {
                    allMediaFiles[idx].name = data.name;
                    allMediaFiles[idx].url = data.url;
                }
                saveCustomMediaOrder();
                renderFilteredMedia();

                Swal.fire({
                    icon: 'success',
                    title: '¡Nombre actualizado!',
                    text: `El archivo ahora se llama "${data.name}"`,
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'No se pudo renombrar',
                    text: data.error || 'Ocurrió un error al renombrar el archivo.',
                    confirmButtonColor: '#FF7A00'
                });
            }
        })
        .catch(err => {
            if (btnRename) {
                btnRename.innerHTML = originalBtnHtml;
                btnRename.disabled = false;
            }
            Swal.fire({
                icon: 'error',
                title: 'Error de red',
                text: 'No se pudo comunicar con el servidor.',
                confirmButtonColor: '#FF7A00'
            });
        });
    }

    /**
     * Confirma la selección de la imagen (Botón "Utilizar Imagen")
     */
    function confirmSelectedMedia() {
        if (!selectedMediaItemData || !selectedMediaItemData.url) return;
        selectMediaItem(selectedMediaItemData.url);
    }

    /**
     * Aplica la imagen al certificado
     */
    function selectMediaItem(url) {
        if (mediaContext === 'background') {
            if (activePage === 1) {
                certState.background = url;
            } else {
                certState.background2 = url;
            }
        } else {
            // Si hay un elemento seleccionado y es imagen o sello, cambiar su src
            if (selectedElementId) {
                const els = (activePage === 1) ? certState.elements : certState.elements2;
                const el = els.find(e => e.id === selectedElementId);
                if (el && (el.type === 'image' || el.type === 'seal')) {
                    el.src = url;
                    renderCanvas();
                    closeMediaManager();
                    return;
                }
            }
            // De lo contrario agregar un nuevo elemento tipo imagen
            addElement('image', url);
        }

        renderCanvas();
        closeMediaManager();
    }

    /**
     * Elimina la imagen actualmente mostrada en la vista previa
     */
    function handleDeleteCurrentPreview() {
        if (!selectedMediaItemData || !selectedMediaItemData.url) return;
        confirmDeleteMedia(null, selectedMediaItemData.url);
    }

    /**
     * Confirmación y eliminación de imagen
     */
    function confirmDeleteMedia(e, url) {
        if (e) {
            e.stopPropagation();
            e.preventDefault();
        }

        // 1. Verificar si la imagen está en el diseño actual del canvas
        let isUsed = false;
        if (certState.background === url || certState.background2 === url) {
            isUsed = true;
        }
        if (!isUsed && certState.elements) {
            isUsed = certState.elements.some(el => el.src === url);
        }
        if (!isUsed && certState.elements2) {
            isUsed = certState.elements2.some(el => el.src === url);
        }

        if (isUsed) {
            Swal.fire({
                icon: 'error',
                title: 'No se puede eliminar',
                text: 'Esta imagen no se puede eliminar porque se está usando en el diseño actual del certificado.',
                confirmButtonColor: '#FF7A00'
            });
            return;
        }

        // 2. Confirmación con SweetAlert2
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¿Deseas eliminar esta imagen de la biblioteca de medios?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('url', url);
                formData.append('_token', '{{ csrf_token() }}');

                fetch(window.mediaRoutes.delete, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: 'La imagen ha sido eliminada con éxito.',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        if (selectedMediaItemData && selectedMediaItemData.url === url) {
                            resetMediaPreview();
                        }
                        loadMediaLibrary();
                    } else {
                        if (data.used) {
                            Swal.fire({
                                icon: 'error',
                                title: 'No se puede eliminar',
                                text: 'Esta imagen no se puede eliminar porque se está usando en una plantilla de certificado guardada en el sistema.',
                                confirmButtonColor: '#FF7A00'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.error || 'No se pudo eliminar la imagen.',
                                confirmButtonColor: '#FF7A00'
                            });
                        }
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo conectar con el servidor.',
                        confirmButtonColor: '#FF7A00'
                    });
                });
            }
        });
    }

    /**
     * Dispara el input file oculto
     */
    function triggerMediaFileInput() {
        const fileInput = document.getElementById('media-file-input');
        if (fileInput) fileInput.click();
    }

    /**
     * Sube archivo desde <input type="file">
     */
    function uploadMediaFile(input) {
        if (!input.files || !input.files[0]) return;
        uploadMediaFileBlob(input.files[0]);
        input.value = '';
    }

    /**
     * Sube un Blob/File al servidor
     */
    function uploadMediaFileBlob(file) {
        if (!file) return;

        // Validar tipo imagen
        if (!file.type.match(/^image\//)) {
            Swal.fire({
                icon: 'warning',
                title: 'Formato no válido',
                text: 'Por favor, selecciona solo archivos de imagen (PNG, JPG, JPEG, WEBP, SVG).',
                confirmButtonColor: '#FF7A00'
            });
            return;
        }

        // Validar 2MB máx
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'warning',
                title: 'Archivo demasiado pesado',
                text: 'El tamaño máximo de imagen permitido es de 2 MB.',
                confirmButtonColor: '#FF7A00'
            });
            return;
        }

        const loader = document.getElementById('media-upload-loader');
        if (loader) loader.classList.remove('hidden');

        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');

        fetch(window.mediaRoutes.upload, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (loader) loader.classList.add('hidden');
            if (data.success || data.url) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Imagen subida con éxito!',
                    text: 'Se ha añadido a la biblioteca de medios.',
                    timer: 1400,
                    showConfirmButton: false
                });
                loadMediaLibrary(data.url);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al subir',
                    text: data.error || 'No se pudo subir la imagen.',
                    confirmButtonColor: '#FF7A00'
                });
            }
        })
        .catch(() => {
            if (loader) loader.classList.add('hidden');
            Swal.fire({
                icon: 'error',
                title: 'Error de red',
                text: 'No se pudo subir la imagen al servidor.',
                confirmButtonColor: '#FF7A00'
            });
        });
    }

    // MANEJO DE DRAG & DROP EN EL DROPZONE (SOLO ARCHIVOS EXTERNOS DEL OS)
    function handleMediaDragOver(e) {
        if (isInternalCardDrag) return;
        e.preventDefault();
        e.stopPropagation();
        const dropzone = document.getElementById('media-dropzone');
        if (dropzone) {
            dropzone.classList.add('border-[#FF7A00]', 'bg-orange-50/50', 'ring-4', 'ring-[#FF7A00]/15');
        }
    }

    function handleMediaDragLeave(e) {
        if (isInternalCardDrag) return;
        e.preventDefault();
        e.stopPropagation();
        const dropzone = document.getElementById('media-dropzone');
        if (dropzone) {
            dropzone.classList.remove('border-[#FF7A00]', 'bg-orange-50/50', 'ring-4', 'ring-[#FF7A00]/15');
        }
    }

    function handleMediaDrop(e) {
        // Ignorar completamente si es arrastre interno de una tarjeta
        if (isInternalCardDrag) return;
        e.preventDefault();
        e.stopPropagation();
        handleMediaDragLeave(e);
        if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            uploadMediaFileBlob(e.dataTransfer.files[0]);
        }
    }

    // DRAG & DROP GLOBAL DENTRO DEL MODAL (SOLO ARCHIVOS EXTERNOS)
    function handleModalGlobalDragOver(e) {
        if (isInternalCardDrag) return;
        e.preventDefault();
        const dropzone = document.getElementById('media-dropzone');
        if (dropzone) {
            dropzone.classList.add('border-[#FF7A00]', 'bg-orange-50/40');
        }
    }

    function handleModalGlobalDragLeave(e) {
        if (isInternalCardDrag) return;
        e.preventDefault();
        if (e.target.id === 'media-modal') {
            const dropzone = document.getElementById('media-dropzone');
            if (dropzone) {
                dropzone.classList.remove('border-[#FF7A00]', 'bg-orange-50/40');
            }
        }
    }

    function handleModalGlobalDrop(e) {
        if (isInternalCardDrag) return;
        e.preventDefault();
        const dropzone = document.getElementById('media-dropzone');
        if (dropzone) {
            dropzone.classList.remove('border-[#FF7A00]', 'bg-orange-50/40');
        }
        if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            uploadMediaFileBlob(e.dataTransfer.files[0]);
        }
    }

    // AYUDANTES DE FORMATO
    function formatBytesHelper(bytes) {
        if (!bytes || bytes === 0) return '';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    function formatDateHelper(ts) {
        if (!ts || ts === 0) return 'Reciente';
        const d = new Date(ts * 1000);
        return d.toLocaleDateString('es-PE', { day: '2-digit', month: 'short', year: 'numeric' });
    }
</script>
