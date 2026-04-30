<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Livre;
use App\Models\Auteur;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Penalite;
class LivreController extends Controller
{

    public function index()
    {
        $livres = Livre::with(['auteur', 'categorie'])->paginate(8);

        return view('biblio.livres.index', compact('livres'));
    }

    public function create()
    {
        $auteurs = Auteur::all();
        $categories = Categorie::all();

        return view('biblio.livres.create', compact('auteurs', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'nombre_exmp' => 'required|integer|min:0',
            'auteur_id' => 'required|exists:auteurs,id',
            'categorie_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // 📸 upload image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('livres', 'public');
        }

        Livre::create($data);

        return redirect()->route('livres.index')
            ->with('success', 'Livre ajouté avec succès');
    }

    public function show($id)
    {
        $livre = Livre::with(['auteur', 'categorie'])->findOrFail($id);

        return view('biblio.livres.show', compact('livre'));
    }

    public function edit($id)
    {
        $livre = Livre::findOrFail($id);
        $auteurs = Auteur::all();
        $categories = Categorie::all();

        return view('biblio.livres.edit', compact('livre', 'auteurs', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $livre = Livre::findOrFail($id);

        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'nombre_exmp' => 'required|integer|min:0',
            'auteur_id' => 'required|exists:auteurs,id',
            'categorie_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // 📸 nouvelle image
        if ($request->hasFile('image')) {

            // if ($livre->image) {
            //     Storage::disk('public')->delete($livre->image);
            // }

            $data['image'] = $request->file('image')->store('livres', 'public');
        }

        $livre->update($data);

        return redirect()->route('livres.index')
            ->with('success', 'Livre modifié avec succès');
    }

    public function destroy($id)
    {
        $livre = Livre::findOrFail($id);

        // 🔥 supprimer image aussi (important)
        // if ($livre->image) {
        //     Storage::disk('public')->delete($livre->image);
        // }

        $livre->delete();

        return redirect()->route('livres.index')
            ->with('success', 'Livre supprimé avec succès');
    }
    public function indexc()
{
    $penalites = Penalite::with('emprunt')->get();

    return view('penalites.index', compact('penalites'));
}
}
