

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Fureur de Lire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">La Fureur de Lire</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 text-center">
         
        <h1>Bienvenue à la bibliothèque La Fureur de Lire</h1>
        <p class="lead">Gérez vos livres, emprunts et pénalités facilement avec notre application Laravel.</p>
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Se connecter</a>
    </div>
</body>
</html>
