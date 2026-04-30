@php
    use Illuminate\Support\Str;
    $role = Auth::user()->role;
@endphp

<div class="border rounded-lg shadow-md p-4 m-2 w-64 h-[420px] flex flex-col justify-between">

    <!-- IMAGE -->
    @if($livre->image)
        <img src="{{ asset('storage/'.$livre->image) }}"
             class="w-full h-40 object-cover rounded">
    @else
        <div class="w-full h-40 bg-gray-200 flex items-center justify-center rounded">
            Pas d'image
        </div>
    @endif

    <!-- INFOS -->
    <div class="flex-1">
        <h2 class="text-lg font-bold mt-2">
            {{ $livre->titre }}
        </h2>

        <p class="text-sm text-gray-600">
            {{ Str::limit($livre->description, 80) }}
        </p>

        <div class="mt-2 text-sm">
            <p><strong>Stock:</strong> {{ $livre->stock }}</p>
            <p><strong>Exemplaires:</strong> {{ $livre->nombre_exmp }}</p>
        </div>
    </div>

    <!-- BOUTONS -->
    <div class="mt-3">

        {{-- 🔥 ADMIN / BIBLIOTHECAIRE --}}
        @if(in_array($role, ['admin', 'bibliothecaire']))

            <div class="flex gap-2">

                <a href="{{ route('livres.edit', $livre->id) }}"
                   class="bg-yellow-500 text-white px-3 py-1 rounded text-sm w-1/2 text-center">
                    Modifier
                </a>

                <form action="{{ route('livres.destroy', $livre->id) }}" method="POST"
                      class="w-1/2"
                      onsubmit="return confirm('Supprimer ce livre ?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="bg-red-500 text-white px-3 py-1 rounded text-sm w-full">
                        Supprimer
                    </button>
                </form>

            </div>

        {{-- 👤 ADHERENT --}}
        @else

            <form action="{{ route('emprunts.store') }}" method="POST">
    @csrf
    <input type="hidden" name="livre_id" value="{{ $livre->id }}">

    <button type="submit"
        {{ $livre->stock <= 0 ? 'disabled' : '' }}
        class="w-full px-3 py-2 rounded text-sm text-white
               {{ $livre->stock > 0 ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-400 cursor-not-allowed' }}">

        {{ $livre->stock > 0 ? 'Emprunter' : 'Indisponible' }}

    </button>
</form>

        @endif

    </div>

</div>
