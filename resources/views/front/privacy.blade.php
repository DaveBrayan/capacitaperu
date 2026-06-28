<x-layouts.guest>
    <x-slot name="title">Políticas de Privacidad - {{ $platformSettings['app_name'] ?? 'CAPACITAPERU' }}</x-slot>

    <!-- Header Section -->
    <div class="relative bg-[#001144] pt-32 pb-16 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#FF7A00] opacity-10 rounded-full blur-[120px] pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-[#0033CC] opacity-20 rounded-full blur-[100px] pointer-events-none"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span class="inline-block bg-[#FF7A00] text-white text-[9px] font-black px-3.5 py-1.5 rounded-full uppercase tracking-wider mb-4">
                Protección de Datos
            </span>
            <h1 class="text-4xl md:text-5xl font-black text-white mb-2 tracking-tight">Políticas de Privacidad</h1>
            <p class="text-gray-300 text-sm font-medium">Conoce cómo recopilamos, protegemos y administramos tus datos personales.</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="bg-gray-50 py-16 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-xl border border-gray-100 space-y-8 text-gray-600 text-sm leading-relaxed">
                
                <section class="space-y-3">
                    <h2 class="text-xl font-black text-[#001144] uppercase tracking-wide border-b border-gray-100 pb-2">1. Declaración de Privacidad</h2>
                    <p>
                        En <strong>{{ $platformSettings['app_name'] ?? 'CAPACITAPERU' }}</strong>, nos comprometemos a garantizar la confidencialidad y seguridad de la información personal de nuestros estudiantes y visitantes de acuerdo con la legislación vigente de protección de datos personales.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-xl font-black text-[#001144] uppercase tracking-wide border-b border-gray-100 pb-2">2. Información Recopilada</h2>
                    <p>
                        Recopilamos información personal cuando te registras en nuestra plataforma educativa, realizas una inscripción o nos envías una reclamación. Los datos recopilados incluyen tu nombre, dirección de correo electrónico, número de teléfono, historial académico y registro de tus compras de cursos o diplomados.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-xl font-black text-[#001144] uppercase tracking-wide border-b border-gray-100 pb-2">3. Uso de la Información</h2>
                    <p>
                        Tus datos se utilizan con el único fin de proveer los servicios académicos solicitados, gestionar el acceso a tu aula virtual, procesar y validar tus certificaciones, atender reclamaciones, y enviarte actualizaciones y comunicaciones relativas a tu formación académica. No compartimos ni vendemos tu información a terceros con fines publicitarios.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-xl font-black text-[#001144] uppercase tracking-wide border-b border-gray-100 pb-2">4. Seguridad y Conservación</h2>
                    <p>
                        Implementamos medidas técnicas y organizacionales de seguridad para prevenir la pérdida, mal uso, alteración o acceso no autorizado a tu información. Conservamos tus datos personales durante el tiempo necesario para la prestación del servicio educativo y el cumplimiento de nuestras obligaciones legales (como la verificación a largo plazo de tus certificados emitidos).
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-xl font-black text-[#001144] uppercase tracking-wide border-b border-gray-100 pb-2">5. Tus Derechos ARCO</h2>
                    <p>
                        Tienes derecho a acceder, rectificar, cancelar u oponerte (derechos ARCO) al tratamiento de tus datos personales. Para ejercer estos derechos o realizar consultas sobre nuestras políticas de protección de datos, puedes contactarnos enviando un correo electrónico a nuestro buzón de atención corporativa.
                    </p>
                </section>

                <section class="space-y-3 text-center pt-8 border-t border-gray-50">
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">
                        Última actualización: 28 de Junio de 2026
                    </p>
                </section>

            </div>
        </div>
    </div>
</x-layouts.guest>
