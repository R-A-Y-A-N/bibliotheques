<x-app-layout>

    <div class="p-6 max-w-xl mx-auto">

        <h1 class="text-2xl font-bold mb-4">Ajouter un livre</h1>

        <form method="POST" action="{{ route('livres.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- TITRE -->
            <div class="mb-3">
                <label class="block mb-1">Titre</label>
                <input type="text" name="titre"
                       class="w-full border p-2 rounded"
                       placeholder="Titre">
            </div>

            <!-- AUTEUR -->
            <div class="mb-3">
                <label class="block mb-1">Auteur</label>
                <select name="auteur_id" class="w-full border p-2 rounded">
                    @foreach($auteurs as $auteur)
                        <option value="{{ $auteur->id }}">
                            {{ $auteur->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- DESCRIPTION -->
            <div class="mb-3">
                <label class="block mb-1">Description</label>
                <textarea name="description"
                          class="w-full border p-2 rounded"
                          placeholder="Description"></textarea>
            </div><!-- CATEGORIE -->
<div class="mb-3">
    <label class="block mb-1">Catégorie</label>
    <select name="categorie_id" class="w-full border p-2 rounded">
        @foreach($categories as $categorie)
            <option value="{{ $categorie->id }}">
                {{ $categorie->nom }}
            </option>
        @endforeach
    </select>
</div>
<!-- STOCK -->
<div class="mb-3">
    <label class="block mb-1">Stock</label>
    <input type="number" name="stock"
           class="w-full border p-2 rounded"
           placeholder="Stock">
</div>

<!-- NOMBRE D'EXEMPLAIRES -->
<div class="mb-3">
    <label class="block mb-1">Nombre d'exemplaires</label>
    <input type="number" name="nombre_exmp"
           class="w-full border p-2 rounded"
           placeholder="Exemplaires">
</div>

            <!-- IMAGE -->
            <div class="mb-3">
                <input type="file" name="image">
            </div>

            <!-- BOUTON -->
            <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded">
                Enregistrer
            </button>


        </form>

    </div>

</x-app-layout>
