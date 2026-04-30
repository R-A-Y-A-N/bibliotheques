<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Tableau de Bord</h1>
                <p class="text-gray-600 dark:text-gray-400">Bienvenue dans votre espace de gestion de bibliothèque</p>
            </div>

            <!-- Cartes Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Carte 1 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Total Livres</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalLivres }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Carte 2 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Livres Disponibles</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $livresDisponibles }}</p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Carte 3 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Retards en Cours</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $retardsEnCours }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Carte 4 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Pénalités Impayées</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($totalPenalitesNonPayees, 2) }} €</p>
                        </div>
                        <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deuxième ligne de cartes -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Adhérents</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $nombreAdherents }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Emprunts du Mois</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $empruntsCeMois }}</p>
                        </div>
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-full">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Taux de Retour</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $tauxRetourDansDelai }}%</p>
                        </div>
                        <div class="p-3 bg-teal-100 dark:bg-teal-900 rounded-full">
                            <svg class="w-6 h-6 text-teal-600 dark:text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Pénalité Moyenne</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($penalitesMoyenne, 2) }} €</p>
                        </div>
                        <div class="p-3 bg-pink-100 dark:bg-pink-900 rounded-full">
                            <svg class="w-6 h-6 text-pink-600 dark:text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            


            <!-- Tableaux -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Emprunts en retard -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Emprunts en Retard</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Utilisateur</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Livre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Retard</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($empruntsEnRetard as $emprunt)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $emprunt->user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $emprunt->livre->titre }}</td>
                                    <td class="px-6 py-4 text-sm text-red-600 font-semibold">{{ \Carbon\Carbon::parse($emprunt->date_retour_prevue)->diffInDays(now()) }} jours</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">Aucun emprunt en retard</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top livres -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Top 5 Livres les Plus Empruntés</h3>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($topLivres as $livre)
                        <div class="px-6 py-4 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $livre->livre->titre }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $livre->livre->auteur->nom }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ $livre->total }} emprunts
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Graphique des emprunts
            const empruntsData = @json($empruntsParMois);
            if(empruntsData.length > 0) {
                new Chart(document.getElementById('empruntsChart'), {
                    type: 'line',
                    data: {
                        labels: empruntsData.map(item => item.mois),
                        datasets: [{
                            label: 'Nombre d\'emprunts',
                            data: empruntsData.map(item => item.total),
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        }
                    }
                });
            }

            // Graphique des catégories
            const categoriesData = @json($statsParCategorie);
            if(categoriesData.length > 0) {
                new Chart(document.getElementById('categoriesChart'), {
                    type: 'doughnut',
                    data: {
                        labels: categoriesData.map(item => item.categorie?.nom || 'Sans catégorie'),
                        datasets: [{
                            data: categoriesData.map(item => item.total),
                            backgroundColor: [
                                'rgb(59, 130, 246)',
                                'rgb(16, 185, 129)',
                                'rgb(245, 158, 11)',
                                'rgb(239, 68, 68)',
                                'rgb(139, 92, 246)',
                                'rgb(236, 72, 153)'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true
                    }
                });
            }

            // Graphique des retards
            const retardsData = @json($retardsParMois);
            if(retardsData.length > 0) {
                new Chart(document.getElementById('retardsChart'), {
                    type: 'bar',
                    data: {
                        labels: retardsData.map(item => item.mois),
                        datasets: [{
                            label: 'Nombre de retards',
                            data: retardsData.map(item => item.total),
                            backgroundColor: 'rgb(239, 68, 68)',
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true
                    }
                });
            }

            // Graphique des auteurs
            const auteursData = @json($topAuteurs);
            if(auteursData.length > 0) {
                new Chart(document.getElementById('auteursChart'), {
                    type: 'bar',
                    data: {
                        labels: auteursData.map(item => item.auteur?.nom || 'Auteur inconnu'),
                        datasets: [{
                            label: 'Nombre de livres',
                            data: auteursData.map(item => item.total),
                            backgroundColor: 'rgb(16, 185, 129)',
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        indexAxis: 'y'
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
