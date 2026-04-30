<x-app-layout>
    <div class="p-6">

        @if(in_array(Auth::user()->role, ['admin', 'bibliothecaire']))
            <a href="{{ route('livres.create') }}"
               class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">
                + Ajouter un livre
            </a>
        @endif

        <x-livre-list :livres="$livres" />

    </div>
</x-app-layout>
