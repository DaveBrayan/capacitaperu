<x-layouts.guest>
    <div class="py-24 bg-[#fff8f5] min-h-[80vh]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Encabezado -->
            <div class="text-center mb-16">
                <span class="text-[#FF7A00] font-black tracking-[0.2em] text-xs uppercase mb-3 block">Consulta de Certificados</span>
                <h1 class="text-4xl md:text-5xl font-black text-[#001144] mb-6">Busca tu Certificado</h1>
                <p class="text-gray-500 text-lg max-w-2xl mx-auto">
                    Ingresa tu número de documento (DNI o CI) para encontrar y visualizar tus certificados emitidos por CAPACITAPERU.
                </p>
            </div>

            <!-- Caja de Búsqueda -->
            <div class="bg-white rounded-[3rem] shadow-2xl p-8 md:p-12 border border-gray-100 mb-12 relative overflow-hidden">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-[#FF7A00]/5 rounded-full blur-3xl"></div>
                
                <form action="{{ route('front.certificate.individual.search') }}" method="GET" class="relative z-10">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 relative group">
                            <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none text-gray-400 group-focus-within:text-[#FF7A00] transition-colors">
                                <i class="ph-bold ph-identification-card text-2xl"></i>
                            </div>
                            <input type="text" name="dni" value="{{ request('dni') }}" required
                                class="w-full bg-gray-50 border-2 border-gray-100 focus:border-[#FF7A00] focus:ring-0 rounded-2xl py-5 pl-16 pr-6 text-lg font-bold text-[#001144] transition-all placeholder:text-gray-300"
                                placeholder="Ingresa tu DNI o CI...">
                        </div>
                        <button type="submit" 
                            class="bg-[#001144] hover:bg-[#FF7A00] text-white px-10 py-5 rounded-2xl font-black text-lg transition-all shadow-xl shadow-[#001144]/20 hover:shadow-[#FF7A00]/40 flex items-center justify-center gap-3 group">
                            <span>Buscar</span>
                            <i class="ph-bold ph-magnifying-glass group-hover:scale-110 transition-transform"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Resultados -->
            @if(request()->has('dni'))
                <div class="fade-in-up">
                    @if($certificates->isNotEmpty())
                        <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
                            <div class="bg-[#0033CC] p-6 text-white flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                                        <i class="ph-bold ph-check-circle text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-black text-lg leading-none">{{ $studentName }}</h3>
                                        <p class="text-xs text-white/60 font-bold uppercase tracking-widest mt-1">Certificados Encontrados ({{ $certificates->count() }})</p>
                                    </div>
                                </div>
                                <div class="hidden md:block">
                                    <span class="bg-white/10 px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest">Documento: {{ request('dni') }}</span>
                                </div>
                            </div>
                            
                            <div class="p-8">
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead>
                                            <tr class="text-left border-b border-gray-50">
                                                <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Certificado / Programa</th>
                                                <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Horas</th>
                                                <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">
                                            @foreach($certificates as $cert)
                                                <tr>
                                                    <td class="py-6">
                                                        <div class="flex items-center gap-4">
                                                            <div class="w-12 h-12 bg-[#FF7A00]/10 rounded-xl flex items-center justify-center text-[#FF7A00]">
                                                                <i class="ph-bold ph-certificate text-2xl"></i>
                                                            </div>
                                                            <div>
                                                                <p class="font-black text-[#001144]">{{ $cert->title }}</p>
                                                                <p class="text-xs text-gray-400 font-medium">{{ $cert->category }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="py-6 text-center">
                                                        <span class="bg-gray-100 px-3 py-1 rounded-lg text-xs font-black text-gray-600">
                                                            {{ $cert->hours ?? '---' }} Hrs.
                                                        </span>
                                                    </td>
                                                    <td class="py-6 text-right">
                                                        <a href="{{ $cert->view_route }}" 
                                                            class="inline-flex items-center justify-center w-11 h-11 bg-[#001144] hover:bg-[#FF7A00] text-white rounded-xl transition-all shadow-lg hover:shadow-[#FF7A00]/30 group"
                                                            title="Ver Certificado">
                                                            <i class="ph-bold ph-eye text-xl group-hover:scale-110 transition-transform"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-[2.5rem] p-12 text-center border border-dashed border-gray-200">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300">
                                <i class="ph-bold ph-file-search text-4xl"></i>
                            </div>
                            <h3 class="text-2xl font-black text-[#001144] mb-2">No se encontraron resultados</h3>
                            <p class="text-gray-400 font-medium mb-8">No tenemos registros de un certificado para el documento: <span class="text-[#FF7A00] font-bold">{{ request('dni') }}</span></p>
                            <a href="{{ route('contact') }}" class="text-[#0033CC] font-black text-sm uppercase tracking-widest border-b-2 border-[#0033CC] hover:text-[#FF7A00] hover:border-[#FF7A00] transition-all pb-1">
                                ¿Crees que hay un error? Contáctanos
                            </a>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-layouts.guest>
