<x-app-layout>
    <div class="min-h-screen bg-gray-100 text-gray-900">

        <div class="max-w-3xl mx-auto px-6 py-12">

            {{-- HEADER --}}
            <div class="text-center mb-10">
                <div class="text-5xl mb-3">📚</div>
                <h1 class="text-3xl font-bold">
                    Résumé de livre
                </h1>
                <p class="text-gray-500 mt-2">
                    Entrez un titre pour générer un résumé
                </p>
            </div>

            {{-- FORM --}}
            <form id="resumeForm"
                  action="{{ route('resume.generer') }}"
                  method="POST"
                  class="bg-white shadow-md rounded-xl p-6 border">

                @csrf

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Titre du livre
                </label>

                <div class="flex gap-3">
                    <input
                        type="text"
                        name="titre"
                        id="titre"
                        placeholder="Ex : Le Petit Prince"
                        class="flex-1 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
                    >

                    <button
                        type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition">
                        Générer
                    </button>
                </div>

                @error('titre')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </form>

            {{-- ERROR --}}
            @if(session('error'))
                <div class="mt-6 bg-red-100 text-red-700 p-4 rounded-lg border">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('error_ollama'))
                <div class="mt-6 bg-red-100 text-red-700 p-4 rounded-lg border">
                    {{ session('error_ollama') }}
                </div>
            @endif

            {{-- RESULT --}}
            @isset($livre, $resume)
                <div class="mt-8 bg-white shadow-md rounded-xl p-6 border">

                    {{-- BOOK INFO --}}
                    <div class="border-b pb-4 mb-4">
                        <h2 class="text-2xl font-bold">
                            {{ $livre->titre }}
                        </h2>

                        <p class="text-gray-600 mt-1">
                            Auteur : {{ $livre->auteur->nom ?? 'Inconnu' }}


                        </p>

                        <p class="text-gray-600">
                            Catégorie : {{ $livre->categorie->nom ?? 'Non définie' }}
                        </p>
                    </div>

                    {{-- RESUME --}}
                    <div>
                        <h3 class="text-lg font-semibold mb-2">
                            Résumé
                        </h3>

                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $resume }}
                        </p>
                    </div>

                    <div class="mt-6 text-right">
                        <a href="{{ route('resume.index') }}"
                           class="text-blue-600 hover:underline">
                            Nouveau résumé
                        </a>
                    </div>

                </div>
            @endisset

        </div>
    </div>
</x-app-layout>