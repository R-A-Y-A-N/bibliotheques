<x-app-layout>
    <div class="max-w-6xl mx-auto py-6">


        <table class="w-full border bg-white shadow">

            <thead>
                <tr class="bg-gray-100">
                    <th>Utilisateur</th>
                    <th>Livre</th>
                    <th>Date emprunt</th>
                    <th>Retour prevu</th>
                    <th>Statut</th>
                </tr>
            </thead>

            <tbody>
                @foreach($emprunts as $emprunt)
                    <tr class="border-t">

                        <td>{{ $emprunt->user->name }}</td>

                        <td>{{ $emprunt->livre->titre }}</td>

                        <td>{{ $emprunt->created_at->format('d/m/Y') }}</td>

                        <td>
                            {{ $emprunt->created_at->format('d/m/Y') }}
                        </td>

                        <td>
                            @if($emprunt->date_retour)
                                Terminé
                            @else
                                En cours
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>
</x-app-layout>
