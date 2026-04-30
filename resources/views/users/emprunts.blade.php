<x-app-layout>
    <div class="max-w-6xl mx-auto py-6">

        <h2 class="text-2xl font-bold mb-6 text-gray-800">
            Mes emprunts
        </h2>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                            Livre
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                            Date emprunt
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                            Date retour prevue
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                            Statut
                        </th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">

                    @foreach($user->emprunts as $emprunt)
                        <tr class="hover:bg-gray-50">

                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $emprunt->livre->titre }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $emprunt->created_at->format('d/m/Y') }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $emprunt->created_at->addDays(10)->format('d/m/Y') }}
                            </td>

                            <td class="px-6 py-4">
                                @if($emprunt->date_retour)
                                    <span class="px-2 py-1 text-sm rounded bg-green-100 text-green-700">
                                        Terminé
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-sm rounded bg-yellow-100 text-yellow-700">
                                        En cours
                                    </span>
                                @endif
                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
