<?php

namespace App\Http\Controllers;

use App\Models\Advisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminAdvisorController extends Controller
{
    public function index()
    {
        $advisors = Advisor::orderBy('created_at', 'desc')->paginate(10);

        $avatars = [];
        $path = public_path('img/avatars/admin');
        if (File::exists($path)) {
            foreach (File::files($path) as $file) {
                if (in_array(strtolower($file->getExtension()), ['png', 'jpg', 'jpeg', 'webp'])) {
                    $avatars[] = 'img/avatars/admin/' . $file->getFilename();
                }
            }
        }
        
        if (empty($avatars)) {
            $avatars = [
                'https://ui-avatars.com/api/?name=Asesor&background=213A57&color=fff',
            ];
        }

        return view('admin.advisors.index', compact('advisors', 'avatars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'avatar_url' => 'required|string',
            'status' => 'required|boolean'
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        Advisor::create($data);

        return redirect()->route('admin.advisors.index')->with('success', 'Asesor creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $advisor = Advisor::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'avatar_url' => 'required|string',
            'status' => 'required|boolean'
        ]);

        $advisor->update($request->all());

        return redirect()->route('admin.advisors.index')->with('success', 'Asesor actualizado correctamente.');
    }

    public function destroy($id)
    {
        $advisor = Advisor::findOrFail($id);
        $advisor->delete();

        return redirect()->route('admin.advisors.index')->with('success', 'Asesor eliminado correctamente.');
    }
}
