<x-app-layout>


    <div class="p-6">
        
        <h1>Bonjour {{ auth()->user()->name }} 😊</h1>
        <p>Bienvenue dans votre espace</p>

        <ul>
            <li>📚 Voir les livres</li>
            <li>📦 Voir mes emprunts</li>
        </ul>
    </div>
</x-app-layout>
