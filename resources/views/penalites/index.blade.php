<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <h2 class="text-2xl font-bold mb-6">Liste des pénalités</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border text-left">ID</th>
                        <th class="px-4 py-2 border text-left">Emprunt</th>
                        <th class="px-4 py-2 border text-left">Montant (€)</th>
                        <th class="px-4 py-2 border text-left">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penalites as $penalite)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border">{{ $penalite->id }}</td>
                            <td class="px-4 py-2 border">{{ $penalite->emprunt_id }}</td>
                            <td class="px-4 py-2 border">{{ $penalite->montant }}</td>
                            <td class="px-4 py-2 border">
                                @if($penalite->payee)
                                    <span class="text-green-600 font-semibold">Payée</span>
                                @else
                                    <span class="text-red-600 font-semibold">Non payée</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                Aucune pénalité trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
