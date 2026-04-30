@props(['livres'])

<div x-data="{ search: '' }" class="p-4">

    <!-- 🔍 BARRE DE RECHERCHE -->
    <input
        type="text"
        placeholder="Rechercher un livre..."
        class="border p-2 w-full mb-4 rounded"
        x-model="search"
    >

    <!-- 📚 LISTE DES LIVRES -->
    <div class="flex flex-wrap gap-4">
        @foreach($livres as $livre)
            <div x-show="'{{ strtolower($livre->titre) }}'.includes(search.toLowerCase())">
                <x-livre-card :livre="$livre" />
            </div>
        @endforeach
    </div>

    <!-- 🔢 PAGINATION -->
    <div class="mt-6">
        {{ $livres->links() }}
    </div>

</div>
