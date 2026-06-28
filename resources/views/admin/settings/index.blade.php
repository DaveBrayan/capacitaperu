<x-layouts.admin title="Configuraciones">
    <x-slot:header>Configuración de Plataforma</x-slot:header>

    <div class="max-w-5xl mx-auto space-y-8 fade-in-up">
        
        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-2xl flex items-center gap-3 shadow-sm">
                <i class="ph-bold ph-check-circle text-emerald-500 text-2xl"></i>
                <p class="text-emerald-800 font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            
            <!-- SECCIÓN: Identidad -->
            <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl shadow-blue-900/5 border border-gray-100">
                <div class="flex items-center gap-4 mb-10 border-b border-gray-50 pb-8">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="ph-fill ph-identification-badge text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-[#001144] tracking-tight">Identidad Visual</h3>
                        <p class="text-gray-400 text-sm font-medium">Gestiona el nombre y los logos de tu academia</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nombre de la Plataforma</label>
                        <input type="text" name="app_name" value="{{ $settings['app_name'] ?? '' }}" 
                            class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144]">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">País Sede</label>
                        <input type="text" name="app_country" value="{{ $settings['app_country'] ?? '' }}" 
                            class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144]">
                    </div>

                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Logo Principal (Color)</label>
                        <div class="flex flex-col gap-4">
                            @if(isset($settings['app_logo']))
                                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-center">
                                    <img src="{{ asset($settings['app_logo']) }}" class="h-12 object-contain">
                                </div>
                            @endif
                            <input type="file" name="app_logo" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Logo Secundario (Blanco)</label>
                        <div class="flex flex-col gap-4">
                            @if(isset($settings['app_logo_white']))
                                <div class="p-4 bg-[#001144] rounded-2xl border border-white/5 flex items-center justify-center">
                                    <img src="{{ asset($settings['app_logo_white']) }}" class="h-12 object-contain">
                                </div>
                            @endif
                            <input type="file" name="app_logo_white" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN: Regional y Moneda -->
            <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl shadow-blue-900/5 border border-gray-100">
                <div class="flex items-center gap-4 mb-10 border-b border-gray-50 pb-8">
                    <div class="w-14 h-14 rounded-2xl bg-orange-50 flex items-center justify-center text-[#FF7A00]">
                        <i class="ph-fill ph-coins text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-[#001144] tracking-tight">Regional y Moneda</h3>
                        <p class="text-gray-400 text-sm font-medium">Configura la zona horaria y moneda de cobro</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Moneda del Sistema (Símbolo)</label>
                        <input type="text" name="app_currency" value="{{ $settings['app_currency'] ?? 'Bs' }}" 
                            class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144]"
                            placeholder="Ej: S/, $, Bs">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Zona Horaria</label>
                        <select name="app_timezone" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-[#FF7A00] transition-all font-bold text-[#001144]">
                            @foreach(timezone_identifiers_list() as $timezone)
                                <option value="{{ $timezone }}" {{ ($settings['app_timezone'] ?? '') == $timezone ? 'selected' : '' }}>
                                    {{ $timezone }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-[#FF7A00] text-white px-12 py-5 rounded-[2rem] font-black shadow-2xl shadow-[#FF7A00]/30 hover:scale-105 active:scale-95 transition-all flex items-center gap-3 group">
                    <i class="ph-bold ph-floppy-disk text-2xl group-hover:rotate-12 transition-transform"></i>
                    Guardar Configuración
                </button>
            </div>
        </form>

        @if(auth()->user()->role === 'superadmin')
            <!-- SECCIÓN: Herramientas de Superadministrador (Consola y Base de Datos) -->
            <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl shadow-blue-900/5 border border-gray-100 space-y-8">
                <div class="flex items-center gap-4 mb-10 border-b border-gray-50 pb-8">
                    <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center text-red-600">
                        <i class="ph-fill ph-terminal text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-[#001144] tracking-tight">Herramientas de Superadministrador</h3>
                        <p class="text-gray-400 text-sm font-medium">Mantenimiento de servidor y ejecución de consultas directas a la base de datos</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Comandos del Servidor -->
                    <div class="bg-gray-50 p-6 md:p-8 rounded-3xl border border-gray-100 space-y-6">
                        <div>
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] mb-1">Mantenimiento y Caché</h4>
                            <p class="text-gray-500 text-xs font-medium">Ejecuta tareas básicas de mantenimiento y actualización de enlaces simbólicos sin requerir consola SSH.</p>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <form action="{{ route('admin.settings.run_command') }}" method="POST">
                                @csrf
                                <input type="hidden" name="command" value="storage_link">
                                <button type="submit" class="w-full bg-[#001144] hover:bg-[#001144]/90 text-white px-6 py-4 rounded-2xl font-bold text-xs transition-all flex items-center justify-center gap-2 shadow-sm">
                                    <i class="ph-bold ph-link text-lg"></i>
                                    Crear Storage Link
                                </button>
                            </form>

                            <form action="{{ route('admin.settings.run_command') }}" method="POST">
                                @csrf
                                <input type="hidden" name="command" value="clear_cache">
                                <button type="submit" class="w-full bg-[#FF7A00] hover:bg-[#FF7A00]/90 text-white px-6 py-4 rounded-2xl font-bold text-xs transition-all flex items-center justify-center gap-2 shadow-sm">
                                    <i class="ph-bold ph-trash-simple text-lg"></i>
                                    Limpiar Caché
                                </button>
                            </form>
                        </div>

                        @if(session('success_command'))
                            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-2xl">
                                <p class="text-emerald-800 font-bold text-xs mb-2">Comando ejecutado con éxito:</p>
                                <pre class="text-[10px] text-emerald-950 font-mono overflow-x-auto p-2 bg-emerald-950/5 rounded-xl whitespace-pre-wrap">{{ session('success_command') }}</pre>
                            </div>
                        @endif

                        @if(session('error_command'))
                            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-2xl">
                                <p class="text-rose-800 font-bold text-xs mb-2">Error al ejecutar comando:</p>
                                <pre class="text-[10px] text-rose-950 font-mono overflow-x-auto p-2 bg-rose-950/5 rounded-xl whitespace-pre-wrap">{{ session('error_command') }}</pre>
                            </div>
                        @endif
                    </div>

                    <!-- Ejecutor SQL -->
                    <div class="bg-gray-50 p-6 md:p-8 rounded-3xl border border-gray-100 space-y-6">
                        <div>
                            <h4 class="text-xs font-black text-[#FF7A00] uppercase tracking-[0.2em] mb-1">Ejecutor de Consultas SQL</h4>
                            <p class="text-gray-500 text-xs font-medium">Ejecuta sentencias SQL directamente. Úsalo con extrema precaución para inspecciones rápidas.</p>
                        </div>
                        
                        <form action="{{ route('admin.settings.run_query') }}" method="POST" class="space-y-4">
                            @csrf
                            <textarea name="sql" rows="4" 
                                class="w-full p-4 rounded-2xl bg-white border border-gray-200 focus:border-[#FF7A00] outline-none font-mono text-xs text-[#001144] resize-none" 
                                placeholder="SELECT * FROM settings LIMIT 5;">{{ session('query_sql') ?? old('sql') }}</textarea>
                            
                            <div class="flex justify-end">
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-8 py-3.5 rounded-2xl font-bold text-xs transition-all flex items-center gap-2 shadow-lg shadow-red-600/10">
                                    <i class="ph-bold ph-play text-sm"></i>
                                    Ejecutar Consulta SQL
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Resultados de Consultas -->
                @if(session('success_query') || session('error_query') || session('query_results'))
                    <div class="border-t border-gray-100 pt-8 space-y-4">
                        <h4 class="text-xs font-black text-[#001144] uppercase tracking-widest border-l-4 border-[#FF7A00] pl-3">Resultado de la Consulta</h4>
                        
                        @if(session('success_query'))
                            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-2xl flex items-center gap-2 text-emerald-800 font-bold text-xs">
                                <i class="ph-bold ph-check-circle text-emerald-500 text-lg"></i>
                                {{ session('success_query') }}
                            </div>
                        @endif

                        @if(session('error_query'))
                            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-2xl flex items-center gap-2 text-rose-800 font-bold text-xs">
                                <i class="ph-bold ph-warning-circle text-rose-500 text-lg"></i>
                                {{ session('error_query') }}
                            </div>
                        @endif

                        @if(session('query_results'))
                            <div class="overflow-x-auto rounded-2xl border border-gray-100 bg-white max-h-96 shadow-sm">
                                <table class="w-full text-left border-collapse text-xs">
                                    <thead>
                                        <tr class="bg-gray-50 border-b border-gray-100 sticky top-0 z-10">
                                            @foreach(array_keys(session('query_results')[0] ?? []) as $header)
                                                <th class="p-4 font-black text-[#001144] uppercase tracking-wider text-[10px]">{{ $header }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @forelse(session('query_results') as $row)
                                            <tr class="hover:bg-gray-50/40 transition-colors">
                                                @foreach($row as $val)
                                                    <td class="p-4 text-gray-600 font-medium whitespace-nowrap overflow-hidden max-w-xs text-ellipsis" title="{{ is_array($val) || is_object($val) ? json_encode($val) : $val }}">
                                                        {{ is_array($val) || is_object($val) ? json_encode($val) : $val }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="p-8 text-center text-gray-400 font-medium">La consulta no retornó registros.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-layouts.admin>
