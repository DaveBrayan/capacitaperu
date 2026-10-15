<?php

namespace App\Http\Controllers;

use App\Models\CertificateTemplate;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminCertificateTemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = CertificateTemplate::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $templates = $query->paginate(12)->withQueryString();

        return view('admin.certificate-templates.index', compact('templates'));
    }

    public function create()
    {
        $template = new CertificateTemplate();
        
        // Cargar ejemplo predeterminado del certificado de Agua Potable y Saneamiento
        $defaultCert = \App\Models\IndividualCertificate::find(46);
        if ($defaultCert && !empty($defaultCert->certificate_template)) {
            $template->title = $defaultCert->title;
            $template->template_data = $defaultCert->certificate_template;
        }

        $executive_signature = Setting::where('key', 'individual_cert_executive_signature')->value('value');
        $academic_signature  = Setting::where('key', 'individual_cert_academic_signature')->value('value');
        $executive_seal       = Setting::where('key', 'individual_cert_executive_seal')->value('value');
        $academic_seal        = Setting::where('key', 'individual_cert_academic_seal')->value('value');

        return view('admin.certificate-templates.create', compact(
            'template',
            'executive_signature',
            'academic_signature',
            'executive_seal',
            'academic_seal'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ], [
            'title.required' => 'El nombre de la plantilla es obligatorio.',
        ]);

        try {
            $template = new CertificateTemplate();
            $template->title = $request->title;
            $template->description = $request->description;
            $template->user_id = auth()->id();

            if ($request->filled('certificate_data')) {
                $decoded = json_decode($request->certificate_data, true);
                if (is_array($decoded)) {
                    $template->template_data = $decoded;
                    $template->preview_image = $decoded['background'] ?? null;
                }
            }

            $template->save();

            return redirect()->route('admin.certificate-templates.index')
                ->with('success', '¡Plantilla "' . $template->title . '" guardada exitosamente!');
        } catch (\Exception $e) {
            Log::error('Error guardando plantilla de certificado: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al guardar la plantilla: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $template = CertificateTemplate::findOrFail($id);

        $executive_signature = Setting::where('key', 'individual_cert_executive_signature')->value('value');
        $academic_signature  = Setting::where('key', 'individual_cert_academic_signature')->value('value');
        $executive_seal       = Setting::where('key', 'individual_cert_executive_seal')->value('value');
        $academic_seal        = Setting::where('key', 'individual_cert_academic_seal')->value('value');

        return view('admin.certificate-templates.create', compact(
            'template',
            'executive_signature',
            'academic_signature',
            'executive_seal',
            'academic_seal'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ], [
            'title.required' => 'El nombre de la plantilla es obligatorio.',
        ]);

        try {
            $template = CertificateTemplate::findOrFail($id);
            $template->title = $request->title;
            $template->description = $request->description;

            if ($request->filled('certificate_data')) {
                $decoded = json_decode($request->certificate_data, true);
                if (is_array($decoded)) {
                    $template->template_data = $decoded;
                    $template->preview_image = $decoded['background'] ?? null;
                }
            }

            $template->save();

            return redirect()->route('admin.certificate-templates.index')
                ->with('success', '¡Plantilla "' . $template->title . '" actualizada exitosamente!');
        } catch (\Exception $e) {
            Log::error('Error actualizando plantilla de certificado: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al actualizar la plantilla: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $template = CertificateTemplate::findOrFail($id);
            $title = $template->title;
            $template->delete();

            return redirect()->route('admin.certificate-templates.index')
                ->with('success', 'La plantilla "' . $title . '" fue eliminada correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la plantilla: ' . $e->getMessage());
        }
    }

    public function duplicate($id)
    {
        try {
            $source = CertificateTemplate::findOrFail($id);
            $clone = $source->replicate();
            $clone->title = 'Copia de ' . $source->title;
            $clone->user_id = auth()->id();
            $clone->save();

            return redirect()->route('admin.certificate-templates.index')
                ->with('success', 'Plantilla duplicada como "' . $clone->title . '".');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al duplicar la plantilla: ' . $e->getMessage());
        }
    }

    /**
     * API JSON para el modal de "Importar de Plantilla" en los diseñadores
     */
    public function getTemplatesJson()
    {
        $templates = CertificateTemplate::orderBy('title', 'asc')
            ->get(['id', 'title', 'description', 'template_data', 'preview_image', 'created_at']);

        return response()->json([
            'success' => true,
            'templates' => $templates
        ]);
    }
}
