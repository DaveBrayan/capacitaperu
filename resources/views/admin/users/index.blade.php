<x-layouts.admin>
    <x-slot name="title">Estudiantes - Gestión</x-slot>
    <x-slot name="header">Gestión de Estudiantes</x-slot>

    <!-- BARRA DE HERRAMIENTAS -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8 fade-in-up" style="animation-delay: 0.1s">
        
        <!-- Buscador y Filtros -->
        <div class="flex gap-3 w-full md:w-auto">
            <div class="relative w-full md:w-80">
                <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Buscar por nombre, CI o correo..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-[#0AD1C8] focus:ring-2 focus:ring-[#0AD1C8]/20 outline-none transition-all text-sm">
            </div>
            <select class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-600 text-sm focus:border-[#0AD1C8] outline-none cursor-pointer">
                <option>Todos los Estados</option>
                <option>Activos</option>
                <option>Inactivos</option>
                <option>Suspendidos</option>
            </select>
        </div>

        <!-- Botones de Acción Global -->
        <div class="flex gap-3">
            <button class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition-colors text-sm font-medium">
                <i class="ph-bold ph-export"></i> Exportar CSV
            </button>
            <button class="flex items-center gap-2 px-6 py-2.5 bg-[#213A57] text-white rounded-xl hover:bg-[#0B6477] transition-all shadow-lg shadow-[#213A57]/20 text-sm font-bold">
                <i class="ph-bold ph-plus"></i> Nuevo Estudiante
            </button>
        </div>
    </div>

    <!-- TABLA DE ESTUDIANTES -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden fade-in-up" style="animation-delay: 0.2s">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4">Estudiante</th>
                        <th class="px-6 py-4">Datos Personales</th>
                        <th class="px-6 py-4">Ubicación</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4">Progreso Reciente</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    
                    <!-- Fila 1 -->
                    <tr class="hover:bg-[#F0FDFD] transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-[#E0F9F6] p-0.5 border border-[#0AD1C8]/30">
                                    <img src="https://ui-avatars.com/api/?name=Juan+Perez&background=0AD1C8&color=fff" class="w-full h-full rounded-full object-cover">
                                </div>
                                <div>
                                    <p class="font-bold text-[#213A57]">Juan Pérez</p>
                                    <p class="text-xs text-gray-500">juan.perez@email.com</p>
                                </div>
                            </div>
                        </td>
                        <!-- DATOS PERSONALES -->
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                    <i class="ph-bold ph-identification-card text-[#0AD1C8]"></i>
                                    <span class="font-mono font-medium">8345120 LP</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                    <i class="ph-bold ph-whatsapp-logo text-green-500"></i>
                                    <span>+591 76543210</span>
                                </div>
                            </div>
                        </td>
                        <!-- UBICACIÓN -->
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                    <i class="ph-bold ph-map-pin text-gray-400"></i>
                                    <span>La Paz, Bolivia</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Activo
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="w-40">
                                <p class="text-xs font-medium text-[#213A57] mb-1 truncate">Supervisor SSOMA</p>
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-[#0AD1C8] rounded-full" style="width: 75%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-[#0AD1C8]">75%</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <!-- Botón Ver -->
                                <button class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-[#0AD1C8] hover:bg-[#E0F9F6] transition-colors" title="Ver Detalles">
                                    <i class="ph-bold ph-eye text-lg"></i>
                                </button>
                                <!-- Botón Editar -->
                                <button class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-amber-500 hover:bg-amber-50 transition-colors" title="Editar">
                                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                                </button>
                                <!-- Botón Restablecer (Llave) -->
                                <button class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-blue-500 hover:bg-blue-50 transition-colors" title="Restablecer Contraseña">
                                    <i class="ph-bold ph-key text-lg"></i>
                                </button>
                                <!-- Botón Eliminar -->
                                <button class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Eliminar">
                                    <i class="ph-bold ph-trash text-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Fila 2 -->
                    <tr class="hover:bg-[#F0FDFD] transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-amber-50 p-0.5 border border-amber-200">
                                    <img src="https://ui-avatars.com/api/?name=Maria+Lopez&background=F59E0B&color=fff" class="w-full h-full rounded-full object-cover">
                                </div>
                                <div>
                                    <p class="font-bold text-[#213A57]">Maria Lopez</p>
                                    <p class="text-xs text-gray-500">maria.lo@email.com</p>
                                </div>
                            </div>
                        </td>
                        <!-- DATOS PERSONALES -->
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                    <i class="ph-bold ph-identification-card text-[#0AD1C8]"></i>
                                    <span class="font-mono font-medium">1239876 SC</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                    <i class="ph-bold ph-whatsapp-logo text-green-500"></i>
                                    <span>+591 60504030</span>
                                </div>
                            </div>
                        </td>
                        <!-- UBICACIÓN -->
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                    <i class="ph-bold ph-map-pin text-gray-400"></i>
                                    <span>Santa Cruz, Bolivia</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pendiente
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="w-40">
                                <p class="text-xs font-medium text-[#213A57] mb-1 truncate">Auditor ISO 14001</p>
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-amber-400 rounded-full" style="width: 10%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-amber-500">10%</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-[#0AD1C8] hover:bg-[#E0F9F6] transition-colors" title="Ver Detalles">
                                    <i class="ph-bold ph-eye text-lg"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-amber-500 hover:bg-amber-50 transition-colors" title="Editar">
                                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-blue-500 hover:bg-blue-50 transition-colors" title="Restablecer Contraseña">
                                    <i class="ph-bold ph-key text-lg"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Eliminar">
                                    <i class="ph-bold ph-trash text-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Fila 3 -->
                    <tr class="hover:bg-[#F0FDFD] transition-colors group opacity-60">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-100 p-0.5 border border-gray-300 grayscale">
                                    <img src="https://ui-avatars.com/api/?name=Carlos+V&background=random" class="w-full h-full rounded-full object-cover">
                                </div>
                                <div>
                                    <p class="font-bold text-gray-500">Carlos Vargas</p>
                                    <p class="text-xs text-gray-400">carlos.v@email.com</p>
                                </div>
                            </div>
                        </td>
                        <!-- DATOS PERSONALES -->
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                    <i class="ph-bold ph-identification-card text-gray-400"></i>
                                    <span class="font-mono font-medium">9988776 CB</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                    <i class="ph-bold ph-whatsapp-logo text-gray-400"></i>
                                    <span>+591 70011223</span>
                                </div>
                            </div>
                        </td>
                        <!-- UBICACIÓN -->
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                    <i class="ph-bold ph-map-pin text-gray-400"></i>
                                    <span>Cochabamba, Bolivia</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactivo
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="w-40">
                                <p class="text-xs font-medium text-gray-500 mb-1 truncate">Ninguno activo</p>
                                <div class="h-1.5 bg-gray-100 rounded-full w-full"></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-[#0AD1C8] hover:bg-[#E0F9F6] transition-colors" title="Ver Detalles">
                                    <i class="ph-bold ph-eye text-lg"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-amber-500 hover:bg-amber-50 transition-colors" title="Editar">
                                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-blue-500 hover:bg-blue-50 transition-colors" title="Restablecer Contraseña">
                                    <i class="ph-bold ph-key text-lg"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Eliminar">
                                    <i class="ph-bold ph-trash text-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
        
        <!-- PAGINACIÓN -->
        <div class="p-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs text-gray-500">Mostrando <span class="font-bold text-[#213A57]">1-10</span> de <span class="font-bold text-[#213A57]">1,240</span> estudiantes</p>
            <div class="flex gap-2">
                <button class="px-3 py-1 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 text-xs font-medium disabled:opacity-50">
                    Anterior
                </button>
                <button class="px-3 py-1 rounded-lg bg-[#213A57] text-white text-xs font-bold shadow-md shadow-[#213A57]/20">
                    1
                </button>
                <button class="px-3 py-1 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 text-xs font-medium">
                    2
                </button>
                <button class="px-3 py-1 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 text-xs font-medium">
                    Siguiente
                </button>
            </div>
        </div>
    </div>

</x-layouts.admin>