<x-layouts.guest>
    <x-slot name="title">Términos y Condiciones - {{ $platformSettings['app_name'] ?? 'CAPACITAPERU' }}</x-slot>

    <!-- Header Section -->
    <div class="relative bg-[#001144] pt-32 pb-16 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#FF7A00] opacity-10 rounded-full blur-[120px] pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-[#0033CC] opacity-20 rounded-full blur-[100px] pointer-events-none"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span class="inline-block bg-[#FF7A00] text-white text-[9px] font-black px-3.5 py-1.5 rounded-full uppercase tracking-wider mb-4">
                Información Legal
            </span>
            <h1 class="text-4xl md:text-5xl font-black text-white mb-2 tracking-tight">Términos y Condiciones</h1>
            <p class="text-gray-300 text-sm font-medium">Por favor, lee detalladamente los términos de servicio de nuestra plataforma.</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="bg-gray-50 py-16 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-xl border border-gray-100 space-y-8 text-gray-600 text-sm leading-relaxed">
                
                <section class="space-y-3">
                    <h2 class="text-xl font-black text-[#001144] uppercase tracking-wide border-b border-gray-100 pb-2">1. Introducción y Aceptación</h2>
                    <p>
                        Bienvenido a <strong>{{ $platformSettings['app_name'] ?? 'CAPACITAPERU' }}</strong>. Al acceder y utilizar nuestro sitio web, catálogo de cursos, diplomados y aula virtual, aceptas cumplir y estar sujeto a los siguientes términos y condiciones de servicio.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-xl font-black text-[#001144] uppercase tracking-wide border-b border-gray-100 pb-2">2. Propiedad Intelectual</h2>
                    <p>
                        Todo el contenido disponible en esta plataforma, incluyendo textos, gráficos, logotipos, videos de clases, material didáctico descargable, y diseños de software, es propiedad exclusiva de {{ $platformSettings['app_name'] ?? 'CAPACITAPERU' }} o de sus respectivos docentes, y está protegido por las leyes de derechos de autor y propiedad intelectual aplicables a nivel nacional e internacional. Queda prohibida la reproducción parcial o total sin consentimiento previo por escrito.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-xl font-black text-[#001144] uppercase tracking-wide border-b border-gray-100 pb-2">3. Cuentas de Estudiante</h2>
                    <p>
                        Al registrarte como estudiante, te comprometes a proporcionar información verídica, exacta y completa. Eres responsable de mantener la confidencialidad de tu contraseña, tu PIN de seguridad y los accesos a tu cuenta. No está permitido compartir credenciales de acceso con terceros. Cualquier uso indebido de la cuenta puede resultar en la suspensión permanente del servicio sin derecho a reembolso.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-xl font-black text-[#001144] uppercase tracking-wide border-b border-gray-100 pb-2">4. Proceso de Inscripción y Pagos</h2>
                    <p>
                        La inscripción en nuestros cursos y diplomados se realiza a través de los canales oficiales. Al seleccionar un programa y proceder al pago por WhatsApp con uno de nuestros asesores autorizados, recibirás las instrucciones necesarias para validar tu pago y obtener tus credenciales. Los precios mostrados incluyen los impuestos correspondientes vigentes en el país.
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-xl font-black text-[#001144] uppercase tracking-wide border-b border-gray-100 pb-2">5. Certificaciones</h2>
                    <p>
                        Las certificaciones se otorgan únicamente a los estudiantes que completen satisfactoriamente los módulos de formación, evaluaciones correspondientes y proyectos finales según el criterio de calificación definido para cada curso o programa. Todas las firmas de acreditación y logotipos institucionales mostrados están debidamente respaldados.
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
