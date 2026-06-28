@php
    $fmtDate = function($date) {
        if(!$date) return '';
        $d = \Carbon\Carbon::parse($date);
        $months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        return $d->day . ' de ' . $months[$d->month - 1] . ' del ' . $d->year;
    };
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Validación de Certificado - {{ config('app.name') }}</title>

    <!-- Fuentes UI principal -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/phosphor-icons"></script>

    <!-- Librerías PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Wrapper: contenedor adaptable que escala el iframe */
        #cert-outer-wrapper {
            width: 100%;
            background-color: #94a3b8;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
            overflow: hidden;
            position: relative;
        }

        @media (max-width: 767px) {
            #cert-outer-wrapper {
                padding: 10px;
            }
        }

        /* Caja que mantiene la proporción A4 landscape */
        #cert-scale-box {
            width: 1123px;
            height: 794px;
            transform-origin: top center;
            flex-shrink: 0;
            position: relative;
            background: white;
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }

        /* El iframe renderiza el certificado en tamaño nativo */
        #cert-iframe {
            width: 1123px;
            height: 794px;
            border: none;
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none;
            overflow: hidden;
        }

        /* Sombra encima del iframe (no afecta el contenido) */
        #cert-shadow {
            position: absolute;
            inset: 0;
            box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.1);
            pointer-events: none;
            border-radius: 2px;
        }

        @media print {
            @page {
                size: landscape;
                margin: 0;
            }

            body * {
                visibility: hidden;
            }

            #cert-outer-wrapper,
            #cert-outer-wrapper * {
                visibility: visible;
            }

            #cert-outer-wrapper {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: white !important;
                padding: 0 !important;
            }

            #cert-scale-box {
                transform: scale(1) !important;
                transform-origin: top left;
            }
        }
    </style>
</head>

<body class="bg-slate-100 min-h-screen text-slate-800">

    <nav class="bg-[#001144] text-white py-4 px-6 shadow-xl sticky top-0 z-50 border-b border-white/10">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-6">
                <a href="/">
                    <img src="{{ asset('img/logo-white.png') }}" class="h-10 w-auto" alt="Capacita Perú"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display:none" class="items-center gap-2">
                        <span class="text-white font-black text-xl tracking-tight">Capacita Perú</span>
                    </div>
                </a>
                <div class="h-8 w-px bg-white/20 hidden md:block"></div>
                <span class="uppercase tracking-widest text-[10px] text-white/50 hidden md:block">Validación de
                    Certificado</span>
            </div>
            <a href="/"
                class="px-5 py-2 bg-[#FF7A00] hover:bg-[#0AD1C8] rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg text-white">
                Regresar al Inicio
            </a>
        </div>
    </nav>

    <main class="max-w-[1300px] mx-auto p-4 lg:p-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- IZQUIERDA: VISUALIZADOR (iframe aislado) -->
            <div class="lg:col-span-9 w-full space-y-4">
                <!-- CONTROLES DE PÁGINA (Solo para certificados de 2 páginas) -->
                <div id="page-controls" class="hidden flex items-center justify-center gap-4 bg-white p-3.5 rounded-3xl shadow-sm border border-slate-100 max-w-sm mx-auto">
                    <button onclick="switchPreviewPage(1)" id="btn-page-1" class="flex-1 py-3 px-6 rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-md bg-[#FF7A00] text-white transform hover:-translate-y-0.5">
                        Hoja 1
                    </button>
                    <button onclick="switchPreviewPage(2)" id="btn-page-2" class="flex-1 py-3 px-6 rounded-2xl text-xs font-black uppercase tracking-widest transition-all bg-slate-100 text-slate-500 hover:bg-slate-200 transform hover:-translate-y-0.5">
                        Hoja 2
                    </button>
                </div>

                <div id="cert-outer-wrapper">
                    <div id="cert-scale-box">
                        <iframe id="cert-iframe" title="Certificado"></iframe>
                        <div id="cert-shadow"></div>
                    </div>
                </div>
            </div>

            <!-- DERECHA: DATOS -->
            <div class="lg:col-span-3 space-y-4">

                <!-- Tarjeta Verificación -->
                <div class="bg-white rounded-3xl shadow-xl border-t-4 border-[#0AD1C8] p-5 relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#0AD1C8]/5 rounded-full"></div>

                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-[#E0F9F6] rounded-xl flex items-center justify-center shrink-0">
                            <i class="ph-fill ph-check-circle text-xl text-[#0AD1C8]"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-[#001144] tracking-tighter leading-none">Certificado
                                Válido</h2>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Verificación
                                Exitosa</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <span
                                class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Estudiante</span>
                            <p class="text-xs font-extrabold text-[#001144] leading-snug">
                                @if(($type ?? 'course') === 'course')
                                    {{ $certificate->student->name }}
                                @elseif($type === 'individual')
                                    {{ $certificate->fullname }}
                                @else
                                    [VISTA PREVIA]
                                @endif
                            </p>
                        </div>
                        <div>
                            <span
                                class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Programa
                                / Curso</span>
                            <p class="text-xs font-extrabold text-[#001144] leading-snug">
                                @if(($type ?? 'course') === 'course')
                                    {{ $certificate->course->title }}
                                @elseif($type === 'individual')
                                    {{ $certificate->certificate->title }}
                                @else
                                    {{ $certificate->title }}
                                @endif
                            </p>
                        </div>
                        <div class="pt-3 border-t border-slate-100">
                            <span
                                class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Fecha
                                de Emisión</span>
                            <p class="text-xs font-bold text-slate-600">
                                @if(($type ?? 'course') === 'course')
                                    {{ $fmtDate($certificate->issued_at) }}
                                @elseif($type === 'individual')
                                    {{ $certificate->is_custom && $certificate->issue_date ? $fmtDate($certificate->issue_date) : ($certificate->is_custom && $certificate->end_date ? $fmtDate($certificate->end_date) : ($certificate->certificate->end_date ? $fmtDate($certificate->certificate->end_date) : '---')) }}
                                @else
                                    {{ $certificate->end_date ? $fmtDate($certificate->end_date) : '---' }}
                                @endif
                            </p>
                        </div>
                        <div class="pt-3 border-t border-slate-100">
                            <span
                                class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Código
                                de Registro</span>
                            <p class="text-xs font-mono font-bold text-[#FF7A00] tracking-tight">
                                @if(($type ?? 'course') === 'course')
                                    {{ $certificate->uuid }}
                                @elseif($type === 'individual')
                                    {{ $certificate->registration_number ?: $certificate->uuid }}
                                @else
                                    [PREVISUALIZACIÓN]
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Botón Descargar PDF -->
                <button onclick="downloadPDF()" id="btn-download"
                    class="w-full py-3.5 bg-[#FF7A00] text-white font-black rounded-2xl shadow-lg shadow-orange-500/30 hover:bg-[#001144] transition-all flex items-center justify-center gap-3 transform hover:-translate-y-1 group mb-3">
                    <i class="ph-bold ph-download-simple text-xl group-hover:animate-bounce"></i>
                    <span id="btn-text" class="uppercase tracking-widest text-xs">Descargar PDF</span>
                </button>

                <!-- Botón Volver al Buscador -->
                <a href="{{ route('front.certificate.individual.search') }}"
                    class="w-full py-2.5 bg-white border-2 border-slate-100 text-slate-400 font-black rounded-2xl hover:bg-slate-50 hover:text-[#001144] hover:border-[#001144] transition-all flex items-center justify-center gap-3 uppercase tracking-widest text-[9px] mb-4">
                    <i class="ph-bold ph-magnifying-glass text-md"></i>
                    Volver al Buscador
                </a>

                <!-- Info Empresa -->
                <div class="bg-[#001144] rounded-2xl shadow-2xl overflow-hidden text-white">
                    <div class="p-5 text-center border-b border-white/5">
                        <img src="{{ asset('img/logo-white.png') }}" class="h-8 mx-auto mb-2 object-contain"
                            alt="Capacita Perú" onerror="this.style.display='none'">
                        <h3 class="text-white font-black text-md tracking-tighter leading-none">Capacita Perú</h3>
                        <p class="text-[8px] font-bold text-white/40 uppercase tracking-[0.3em] mt-1.5">Formación Profesional</p>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="flex items-start gap-3">
                            <i class="ph-fill ph-map-pin text-xl text-[#FF7A00] shrink-0"></i>
                            <div>
                                <span
                                    class="block text-[8px] font-black text-white/30 uppercase tracking-widest mb-0.5">Ubicación</span>
                                <p class="text-xs font-bold text-white/80 leading-relaxed">Lima, Perú</p>
                            </div>
                        </div>

                        <div class="pt-3 text-center border-t border-white/5">
                            <a href="https://capacitaperu.com" target="_blank"
                                class="inline-flex items-center gap-2 text-[#FF7A00] hover:text-white font-black text-xs transition-all uppercase tracking-widest group">
                                <i class="ph-bold ph-globe text-md"></i>
                                <span
                                    class="border-b-2 border-transparent group-hover:border-[#FF7A00]">capacitaperu.com</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script>
        // ====================================================================
        // DATOS DEL SERVIDOR
        // ====================================================================
        @if(($type ?? 'course') === 'course')
            const templateData = @json($certificate->course->certificate_template ?? []);
            @php
                $courseMeta = $certificate->course->metadata ?? [];
            @endphp
            const realData = {
                student_name: @json($certificate->student->name),
                course_title: @json($certificate->course->title),
                instructor_name: @json($certificate->course->instructor->certificate_name ?? $certificate->course->instructor->name ?? ''),
                date: @json($certificate->issued_at->format('d \d\e F \d\e Y')),
                hours: @json($certificate->course->duration ?? ($courseMeta['hours'] ?? '')),
                start_date: @json(isset($courseMeta['start_date']) && $courseMeta['start_date'] ? $fmtDate($courseMeta['start_date']) : ''),
                end_date: @json(isset($courseMeta['end_date']) && $courseMeta['end_date'] ? $fmtDate($courseMeta['end_date']) : ''),
                registration_number: @json($courseMeta['registration_number'] ?? $certificate->uuid),
                verification_url: "{{ route('certificate.verify', ['uuid' => $certificate->uuid]) }}",
                instructor_signature: @json(!empty($certificate->course->instructor->signature_url) ? asset($certificate->course->instructor->signature_url) : null),
                executive_signature: @json(!empty($executive_signature ?? null) ? asset($executive_signature) : null),
                academic_signature: @json(!empty($academic_signature ?? null) ? asset($academic_signature) : null)
            };
        @elseif($type === 'individual')
            const templateData = @json($certificate->certificate->certificate_template ?? []);
            const realData = {
                is_custom: @json($certificate->is_custom),
                student_name: @json($certificate->fullname),
                course_title: @json($certificate->certificate->title),
                instructor_name: @json($certificate->certificate->instructor->certificate_name ?? $certificate->certificate->instructor->name ?? ''),
                
                // Valores personalizados
                date: @json($fmtDate($certificate->issue_date) ?: ($fmtDate($certificate->end_date) ?: $fmtDate($certificate->certificate->end_date))),
                hours: @json($certificate->hours ?: $certificate->certificate->hours),
                start_date: @json($fmtDate($certificate->start_date) ?: $fmtDate($certificate->certificate->start_date)),
                end_date: @json($fmtDate($certificate->end_date) ?: $fmtDate($certificate->certificate->end_date)),
                registration_number: @json($certificate->registration_number ?: $certificate->certificate->registration_number),

                // Valores base (para reemplazo dinámico)
                base_date: @json($fmtDate($certificate->certificate->end_date)),
                base_hours: @json($certificate->certificate->hours),
                base_start_date: @json($fmtDate($certificate->certificate->start_date)),
                base_end_date: @json($fmtDate($certificate->certificate->end_date)),
                base_registration_number: @json($certificate->certificate->registration_number),

                verification_url: "{{ route('front.certificate.individual.view', ['uuid' => $certificate->uuid]) }}",
                instructor_signature: @json(!empty($certificate->certificate->instructor->signature_url) ? asset($certificate->certificate->instructor->signature_url) : null),
                executive_signature: @json(!empty($executive_signature) ? asset($executive_signature) : null),
                academic_signature: @json(!empty($academic_signature) ? asset($academic_signature) : null)
            };
        @elseif($type === 'individual_preview')
            const templateData = @json($certificate->certificate_template ?? []);
            const realData = {
                student_name: "[NOMBRE DEL ESTUDIANTE]",
                course_title: @json($certificate->title),
                instructor_name: @json($certificate->instructor->certificate_name ?? $certificate->instructor->name ?? ''),
                date: @json($certificate->end_date ? $certificate->end_date->format('d \d\e F \d\e Y') : ''),
                hours: @json($certificate->hours),
                start_date: @json($certificate->start_date ? $certificate->start_date->format('d \d\e F \d\e Y') : ''),
                end_date: @json($certificate->end_date ? $certificate->end_date->format('d \d\e F \d\e Y') : ''),
                registration_number: @json($certificate->registration_number),
                verification_url: "#",
                instructor_signature: @json(!empty($certificate->instructor->signature_url) ? asset($certificate->instructor->signature_url) : null),
                executive_signature: @json(!empty($executive_signature) ? asset($executive_signature) : null),
                academic_signature: @json(!empty($academic_signature) ? asset($academic_signature) : null)
            };
        @endif

        // ====================================================================
        // HELPERS
        // ====================================================================
        function fixUrl(url) {
            if (!url || url.startsWith('data:') || url.startsWith('http')) return url;
            if (url.includes('/storage/')) {
                const parts = url.split('/storage/');
                return "{{ asset('storage') }}/" + parts[1];
            }
            return "{{ asset('storage') }}/" + url.replace(/^\//, '');
        }

        function getFontFamily(k) {
            const map = {
                'font-lato': "'Lato', sans-serif",
                'font-merriweather': "'Merriweather', serif",
                'font-pinyon': "'Pinyon Script', cursive",
                'font-greatvibes': "'Great Vibes', cursive",
                'font-montserrat': "'Montserrat', sans-serif",
                'font-opensans': "'Open Sans', sans-serif"
            };
            return map[k] || "'Lato', sans-serif";
        }

        // ====================================================================
        // GENERADOR DEL HTML DEL CERTIFICADO
        // Esta función produce el HTML idéntico al que renderiza el admin.
        // Se usa tanto para el iframe como para la captura en PDF.
        // ====================================================================
        function buildCertificateHTML(forPDF) {
            const pagesCount = templateData.pages || 1;
            let pagesHtml = '';

            for (let i = 1; i <= pagesCount; i++) {
                const bg = i === 1 ? templateData.background : templateData.background2;
                const elements = i === 1 ? (templateData.elements || []) : (templateData.elements2 || []);
                const bgUrl = bg ? fixUrl(bg) : null;
                const displayStyle = (i === 2 && !forPDF) ? 'display: none;' : 'display: block;';

                let elementsHTML = '';

                elements.forEach(el => {
                    // --- Estilos del contenedor del elemento ---
                    let cStyle = `position:absolute; left:${el.x}px; top:${el.y}px; display:flex; flex-direction:column; z-index:10;`;

                    if (el.style) {
                        const ff = getFontFamily(el.style.fontFamily);
                        const fs = el.style.fontSize || '16px';
                        const fc = el.style.color || '#000000';
                        const fw = el.style.fontWeight || 'normal';

                        // Alineación horizontal (igual que admin)
                        let alignItems = 'flex-start';
                        let textAlign = el.style.textAlign || 'left';
                        if (textAlign === 'center') alignItems = 'center';
                        else if (textAlign === 'right') alignItems = 'flex-end';

                        cStyle += `font-family:${ff}; font-size:${fs}; color:${fc}; font-weight:${fw}; align-items:${alignItems}; text-align:${textAlign};`;

                        if (el.style.width && el.style.width !== 'auto') {
                            cStyle += `width:${el.style.width}; white-space:normal;`;
                            if (el.style.height) cStyle += `height:${el.style.height};`;
                        } else {
                            cStyle += `width:auto; white-space:nowrap;`;
                        }
                    }

                    // --- Contenido del elemento ---
                    let innerHTML = '';

                    if (el.type === 'qr') {
                        // Usar exactamente el tamaño guardado por el admin (width Y height)
                        const qw = el.style?.width || '120px';
                        const qh = el.style?.height || qw; // Si no hay alto guardado, usar el ancho
                        // Sobreescribir cualquier dimensión previa en cStyle con las exactas del QR
                        cStyle += `width:${qw}; height:${qh}; overflow:hidden;`;
                        const link = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(realData.verification_url)}`;
                        // La imagen se ajusta exactamente al contenedor sin crecer
                        innerHTML = `<img src="${link}" style="width:${qw}; height:${qh}; max-width:${qw}; max-height:${qh}; object-fit:contain; display:block;">`;
                    }

                    else if (['signature', 'manager_signature', 'executive_signature', 'academic_signature'].includes(el.type)) {
                        cStyle += `width:auto; white-space:nowrap; justify-content:flex-end; align-items:center;`;

                        let sigUrl = null;
                        let sigName = '';
                        let sigRole = '';

                        if (el.type === 'signature') {
                            sigUrl = realData.instructor_signature;
                            sigName = realData.instructor_name;
                            sigRole = el.content?.role || 'INSTRUCTOR';
                        } else if (el.type === 'manager_signature') {
                            sigUrl = el.content?.signature_img ? fixUrl(el.content.signature_img) : null;
                            sigName = el.content?.name || '';
                            sigRole = el.content?.role || '';
                        } else if (el.type === 'executive_signature') {
                            sigUrl = realData.executive_signature;
                            sigName = el.content?.name || 'DIRECTOR EJECUTIVO';
                            sigRole = el.content?.role || 'DIRECTOR EJECUTIVO';
                        } else if (el.type === 'academic_signature') {
                            sigUrl = realData.academic_signature;
                            sigName = el.content?.name || 'DIRECTOR ACADÉMICO';
                            sigRole = el.content?.role || 'DIRECTOR ACADÉMICO';
                        }

                        const size = (el.content && el.content.size) ? el.content.size : 100;
                        const imgHtml = sigUrl
                            ? `<img src="${sigUrl}" style="height:${size}px; max-width:200px; object-fit:contain; display:block;">`
                            : `<div style="height:${size}px; width:100px;"></div>`;

                        const ns = (el.content && el.content.nameStyle) ? el.content.nameStyle : {};
                        const rs = (el.content && el.content.roleStyle) ? el.content.roleStyle : {};

                        const nsStr = `font-family:${getFontFamily(ns.fontFamily)}; font-size:${ns.fontSize || '12px'}; color:${ns.color || '#000'}; font-weight:${ns.fontWeight || 'normal'}; line-height:1.2; text-align:center; display:block;`;
                        const rsStr = `font-family:${getFontFamily(rs.fontFamily)}; font-size:${rs.fontSize || '11px'}; color:${rs.color || '#555'}; line-height:1.2; text-align:center; display:block;`;
                        const lineColor = ns.color || '#000';

                        innerHTML = `
                            ${imgHtml}
                            <div style="border-top:1px solid ${lineColor}; width:100%; height:0; margin:2px 0;"></div>
                            <span style="${nsStr}">${sigName}</span>
                            <span style="${rsStr}">${sigRole}</span>
                        `;
                    }

                    else if (el.type === 'image' || el.type === 'seal') {
                        const src = fixUrl(el.src);
                        innerHTML = `<img src="${src}" style="width:100%; height:100%; object-fit:contain; display:block;">`;
                    }

                    else if (el.type === 'student_name') {
                        innerHTML = `<span>${realData.student_name}</span>`;
                    }

                    else if (el.type === 'course_title') {
                        innerHTML = `<span>${realData.course_title}</span>`;
                    }

                    else if (el.type === 'registration_number') {
                        innerHTML = `<p>Reg. N° ${realData.registration_number || ''}</p>`;
                    }

                    else if (['text', 'certificate_body', 'issue_date'].includes(el.type)) {
                        let content = (el.content || '').toString();
                        
                        // Reemplazos dinámicos estándar
                        content = content.replace(/{student_name}/g, realData.student_name);
                        content = content.replace(/{course_title}/g, realData.course_title);
                        content = content.replace(/{date}/g, realData.date);
                        content = content.replace(/{hours}/g, realData.hours || '');
                        content = content.replace(/{start_date}/g, realData.start_date || '');
                        content = content.replace(/{end_date}/g, realData.end_date || '');
                        content = content.replace(/{registration_number}/g, realData.registration_number || '');
                        content = content.replace(/{instructor}/g, realData.instructor_name || '');
                        
                        // Reemplazo dinámico inteligente si es estudiante personalizado y el contenido guarda valores estáticos base
                        if (realData.is_custom) {
                            // Función auxiliar para crear expresiones regulares flexibles de fechas en español
                            const makeFlexDateRegex = (dateStr) => {
                                if (!dateStr) return null;
                                let escaped = dateStr.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                                escaped = escaped.replace(/\s+/g, '\\s+');
                                escaped = escaped.replace(/\b(?:de|del)\b/gi, '(?:de|del)');
                                return new RegExp(escaped, 'gi');
                            };

                            if (realData.base_start_date && realData.start_date && realData.base_start_date !== realData.start_date) {
                                const regex = makeFlexDateRegex(realData.base_start_date);
                                if (regex) content = content.replace(regex, realData.start_date);
                            }
                            if (realData.base_end_date && realData.end_date && realData.base_end_date !== realData.end_date) {
                                const regex = makeFlexDateRegex(realData.base_end_date);
                                if (regex) content = content.replace(regex, realData.end_date);
                            }
                            if (realData.base_hours && realData.hours && realData.base_hours !== realData.hours) {
                                // Expresión regular robusta para horas, buscando el número de horas base seguido opcionalmente de tags y de la palabra "horas"
                                const regex = new RegExp('\\b' + realData.base_hours + '\\b(?=\\s*(?:<[^>]+>)*\\s*horas)', 'gi');
                                content = content.replace(regex, realData.hours);
                            }
                            if (realData.base_date && realData.date && realData.base_date !== realData.date) {
                                const regex = makeFlexDateRegex(realData.base_date);
                                if (regex) content = content.replace(regex, realData.date);
                            }
                        }
                        
                        innerHTML = content;
                    }

                    elementsHTML += `<div style="${cStyle}">${innerHTML}</div>`;
                });

                pagesHtml += `
                <div id="canvas${i === 1 ? '' : '2'}" class="cert-page" style="${displayStyle}">
                    ${bgUrl ? `<img src="${bgUrl}" style="position:absolute; top:0; left:0; width:1123px; height:794px; object-fit:fill; z-index:0;">` : ''}
                    ${elementsHTML}
                </div>
                `;
            }

            // HTML completo idéntico al editor del admin (sin CSS externo que interfiera)
            return `<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Lato:wght@400;700;900&family=Merriweather:wght@400;700&family=Pinyon+Script&family=Montserrat:wght@400;700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; border: 0; }
    html, body {
        width: 1123px;
        background: white;
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
    html::-webkit-scrollbar, body::-webkit-scrollbar {
        display: none;  /* Chrome, Safari, Opera */
    }
    .cert-page {
        width: 1123px;
        height: 794px;
        position: relative;
        overflow: hidden;
        background: white;
        line-height: 1.15;
        font-size: 16px;
        color: #000;
        text-align: left;
        margin-bottom: 0px;
    }
    .cert-page img { display: block; }
    p { margin: 0; padding: 0; }
    /* Quill output styles (para textos enriquecidos) */
    .ql-align-center { text-align: center; }
    .ql-align-right { text-align: right; }
    .ql-align-left { text-align: left; }
    .ql-align-justify { text-align: justify; }
    strong { font-weight: bold; }
    em { font-style: italic; }
    u { text-decoration: underline; }
</style>
</head>
<body style="margin:0; padding:0; background:white;">
    ${pagesHtml}
</body>
</html>`;
        }

        // ====================================================================
        // RENDERIZADO EN IFRAME (Vista Previa)
        // ====================================================================
        function renderInIframe() {
            const iframe = document.getElementById('cert-iframe');
            const html = buildCertificateHTML(false);
            // Escribir directamente en el documento del iframe para máxima fidelidad
            iframe.contentDocument.open();
            iframe.contentDocument.write(html);
            iframe.contentDocument.close();
        }

        // ====================================================================
        // CAMBIO DE PÁGINA EN VISTA PREVIA
        // ====================================================================
        let activePreviewPage = 1;
        function switchPreviewPage(pageNum) {
            activePreviewPage = pageNum;
            
            const btn1 = document.getElementById('btn-page-1');
            const btn2 = document.getElementById('btn-page-2');
            
            if (pageNum === 1) {
                if (btn1) btn1.className = "flex-1 py-3 px-6 rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-md bg-[#FF7A00] text-white transform hover:-translate-y-0.5";
                if (btn2) btn2.className = "flex-1 py-3 px-6 rounded-2xl text-xs font-black uppercase tracking-widest transition-all bg-slate-100 text-slate-500 hover:bg-slate-200 transform hover:-translate-y-0.5";
            } else {
                if (btn1) btn1.className = "flex-1 py-3 px-6 rounded-2xl text-xs font-black uppercase tracking-widest transition-all bg-slate-100 text-slate-500 hover:bg-slate-200 transform hover:-translate-y-0.5";
                if (btn2) btn2.className = "flex-1 py-3 px-6 rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-md bg-[#FF7A00] text-white transform hover:-translate-y-0.5";
            }

            const iframe = document.getElementById('cert-iframe');
            if (iframe && iframe.contentDocument) {
                const canvas1 = iframe.contentDocument.getElementById('canvas');
                const canvas2 = iframe.contentDocument.getElementById('canvas2');
                
                if (pageNum === 1) {
                    if (canvas1) canvas1.style.display = 'block';
                    if (canvas2) canvas2.style.display = 'none';
                } else {
                    if (canvas1) canvas1.style.display = 'none';
                    if (canvas2) canvas2.style.display = 'block';
                }
            }
        }

        // ====================================================================
        // ESCALADO RESPONSIVO DEL IFRAME
        // ====================================================================
        function fitCertificate() {
            const outer = document.getElementById('cert-outer-wrapper');
            const scaleBox = document.getElementById('cert-scale-box');
            const padding = window.innerWidth < 768 ? 20 : 40; 
            const available = outer.clientWidth - padding;
            const certWidth = 1123;
            const certHeight = 794;
            const totalHeight = certHeight; // Always 1 page height for the preview

            const scale = Math.min(1, available / certWidth);
            scaleBox.style.transform = `scale(${scale})`;
            scaleBox.style.transformOrigin = 'top center';
            
            scaleBox.style.height = totalHeight + 'px';
            document.getElementById('cert-iframe').style.height = totalHeight + 'px';

            // Ajustar el alto del contenedor para que encaje con la escala
            outer.style.height = (totalHeight * scale + padding) + 'px';
        }

        // ====================================================================
        // GENERACIÓN DE PDF
        // La estrategia: construir el HTML nativo del certificado en un
        // contenedor OCULTO (sin escala), capturar con html2canvas
        // a tamaño completo real, y luego insertar en jsPDF.
        // ====================================================================
        async function downloadPDF() {
            const { jsPDF } = window.jspdf;
            const btn = document.getElementById('btn-download');
            const btnText = document.getElementById('btn-text');
            const orig = btnText.innerText;

            btnText.innerText = 'Generando PDF...';
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-wait');

            try {
                // Obtenemos el iframe de vista previa que ya está renderizado perfecto
                const iframe = document.getElementById('cert-iframe');
                const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                const iframeWin = iframe.contentWindow;

                // 1. Inyectar html2canvas DENTRO del iframe si no existe
                if (!iframeWin.html2canvas) {
                    const script = iframeDoc.createElement('script');
                    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';
                    iframeDoc.head.appendChild(script);

                    // Esperar a que la librería cargue dentro del iframe
                    await new Promise((resolve, reject) => {
                        script.onload = resolve;
                        script.onerror = reject;
                        setTimeout(resolve, 3000);
                    });
                }

                // 2. Esperar que las fuentes estén cargadas en el iframe
                try { await iframeDoc.fonts.ready; } catch (e) { }
                await new Promise(r => setTimeout(r, 400)); // Pequeña pausa extra

                const pdf = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
                const pagesCount = templateData.pages || 1;

                // Temporarily isolate each page to render at the top (y: 0) for perfect capture
                const canvas1 = iframeDoc.getElementById('canvas');
                const canvas2 = iframeDoc.getElementById('canvas2');

                // Asegurar que ambas páginas se muestren temporalmente para forzar la carga y renderizado de imágenes
                if (canvas1) canvas1.style.display = 'block';
                if (canvas2) canvas2.style.display = 'block';

                // Esperar a que todas las imágenes en el documento del iframe estén completamente cargadas y decodificadas
                const allImgs = Array.from(iframeDoc.querySelectorAll('img'));
                await Promise.all(allImgs.map(img => {
                    if (img.complete && img.naturalWidth !== 0) return Promise.resolve();
                    return new Promise(resolve => {
                        img.onload = resolve;
                        img.onerror = resolve; // Continuar aunque falle una imagen
                    });
                }));

                // Esperar un momento adicional para el reflujo del DOM y decodificación de texturas
                await new Promise(r => setTimeout(r, 200));

                for (let i = 1; i <= pagesCount; i++) {
                    // Show only the page we are capturing to position it at the top of the document
                    if (canvas1) canvas1.style.display = (i === 1) ? 'block' : 'none';
                    if (canvas2) canvas2.style.display = (i === 2) ? 'block' : 'none';

                    // Wait a short moment for DOM reflow
                    await new Promise(r => setTimeout(r, 150));

                    const canvasId = i === 1 ? 'canvas' : 'canvas2';
                    const certEl = iframeDoc.getElementById(canvasId);
                    
                    if (!certEl) continue;

                    // --- TWEAK TEMPORAL SOLO PARA EL PDF ---
                    // Encogemos el QR unos píxeles solo en los laterales justo antes de la foto
                    const qrs = certEl.querySelectorAll('img[src*="qrserver.com"]');
                    const oldQrStyles = [];
                    qrs.forEach((qr, idx) => {
                        oldQrStyles[idx] = qr.style.cssText;
                        const w = parseInt(qr.parentElement.style.width) || 120;
                        const h = parseInt(qr.parentElement.style.height) || 120;
                        qr.style.width = w + 'px'; 
                        qr.style.height = h + 'px'; 
                        qr.style.marginLeft = '0px';
                        qr.style.marginRight = '0px';
                        qr.style.marginTop = '0px';
                        qr.style.marginBottom = '0px';
                        qr.style.maxWidth = 'none';
                        qr.style.maxHeight = 'none';
                        qr.style.objectFit = 'contain';
                    });

                    const canvas = await iframeWin.html2canvas(certEl, {
                        scale: 3,
                        useCORS: true,
                        allowTaint: false,
                        logging: false,
                        backgroundColor: '#ffffff',
                        width: 1123,
                        height: 794,
                        windowWidth: 1123,
                        windowHeight: 794,
                        scrollX: 0,
                        scrollY: 0,
                        x: 0,
                        y: 0 // Always 0 because other pages are hidden and it sits at the top of the body
                    });

                    // Restaurar el QR para que la vista previa siga intacta
                    qrs.forEach((qr, idx) => {
                        qr.style.cssText = oldQrStyles[idx];
                    });

                    const imgData = canvas.toDataURL('image/jpeg', 0.98);
                    
                    if (i > 1) {
                        pdf.addPage();
                    }
                    pdf.addImage(imgData, 'JPEG', 0, 0, 297, 210);
                }

                // Restore original preview display states using our helper
                switchPreviewPage(activePreviewPage);

                pdf.save(`Certificado-${realData.student_name}.pdf`);

            } catch (err) {
                console.error('Error generando PDF:', err);
                // Fallback robusto a impresión nativa
                try {
                    document.getElementById('cert-iframe').contentWindow.print();
                } catch (e) {
                    window.print();
                }
            } finally {
                btnText.innerText = orig;
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-wait');
            }
        }

        // ====================================================================
        // INICIALIZACIÓN
        // ====================================================================
        document.addEventListener('DOMContentLoaded', () => {
            renderInIframe();
            
            // Show page controls if 2 pages are configured
            if (templateData && templateData.pages === 2) {
                const controls = document.getElementById('page-controls');
                if (controls) controls.classList.remove('hidden');
                // Sync iframe initial display state
                setTimeout(() => switchPreviewPage(1), 100);
            }
            
            fitCertificate();
            window.addEventListener('resize', fitCertificate);
        });
    </script>
</body>

</html>