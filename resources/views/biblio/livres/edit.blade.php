<x-app-layout>

    <div class="p-6 max-w-xl mx-auto">

        <h1 class="text-2xl font-bold mb-4">Modifier un livre</h1>

        <form method="POST" action="{{ route('livres.update', $livre->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- TITRE -->
            <div class="mb-3">
                <label class="block mb-1">Titre</label>
                <input type="text" name="titre" value="{{ $livre->titre }}"
                       class="w-full border p-2 rounded">
            </div>

            <!-- DESCRIPTION -->
            <div class="mb-3">
                <label class="block mb-1">Description</label>
                <textarea name="description"
                          class="w-full border p-2 rounded">{{ $livre->description }}</textarea>
            </div>
<!-- STOCK -->
<div class="mb-3">
    <label class="block mb-1">Stock</label>
    <input type="number" name="stock"
           value="{{ $livre->stock }}"
           class="w-full border p-2 rounded">
</div>

<!-- NOMBRE D'EXEMPLAIRES -->
<div class="mb-3">
    <label class="block mb-1">Nombre d'exemplaires</label>
    <input type="number" name="nombre_exmp"
           value="{{ $livre->nombre_exmp }}"
           class="w-full border p-2 rounded">
</div>
            <!-- IMAGE ACTUELLE -->
            @if($livre->image)
                <div class="mb-3">
                    <img src="{{ asset('storage/'.$livre->image) }}" class="w-32">
                </div>
            @endif
            <div>
<select name="auteur_id" class="w-full border p-2 rounded">
    @foreach($auteurs as $auteur)
        <option value="{{ $auteur->id }}"
            {{ $livre->auteur_id == $auteur->id ? 'selected' : '' }}>
            {{ $auteur->nom }}
        </option>
    @endforeach
</select>
</div>
<div class="mb-3">
    <label>Catégorie</label>
    <select name="categorie_id" class="w-full border p-2 rounded">
        @foreach($categories as $categorie)
            <option value="{{ $categorie->id }}"
                {{ $livre->categorie_id == $categorie->id ? 'selected' : '' }}>
                {{ $categorie->nom }}
            </option>
        @endforeach
    </select>
</div>
            <!-- UPLOAD IMAGE -->
            <div class="mb-3">
                <input type="file" name="image">
            </div>

            <!-- BOUTON -->
            <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded">
                Modifier
            </button>

        </form>

    </div>

</x-app-layout>
