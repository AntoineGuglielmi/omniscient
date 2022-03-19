<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/styles/css/main.css">
</head>
<body>
    <div id="content" class="bienvenue">
        <div class="fond">
            <div class="rond clair"></div>
            <!-- <div class="rond fonce"></div> -->
        </div>
        <div class="flou"></div>
        <div class="bienvenue__content">
            <header>
                <h1>Omniscient</h1>
                <small>Version dev.1.0</small>
            </header>
            <p>Cette page s'affiche car aucune route n'a été définie.</p>
            <br>
            <p>Rendez-vous dans les fichiers <code><?= APP_PATH . 'Routes' . DS . 'app.php' ?></code> et <code><?= APP_PATH . 'Routes' . DS . 'api.php' ?></code> pour définir les routes.</p>
        </div>
    </div>
</body>
</html>