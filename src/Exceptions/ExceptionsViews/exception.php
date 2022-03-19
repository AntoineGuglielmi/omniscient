<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Omniscient - <?= $type ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/styles/css/main.css">
    <style>
        h1
        {
            padding: 1rem;
            font-size: 3em;
            font-weight: 100;
            background-color: #282c34;
            color: #abb2bf;
        }

        .message,
        .env
        {
            padding: 1rem;
            background-color: #333842;
            color: #abb2bf;
        }

        .env
        {
            font-size: 0.85em;
        }

        .message code
        {
            color: #7b869e;
            font-weight: 600;
        }

        .trace
        {
            margin: 1rem 0;
            padding: 1rem;
            background-color: #e6e6e6;
        }

        .tip
        {
            padding: 1rem;
            background-color: #828997;
        }

        .tip code
        {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <header>
        <p class="env">Page visible en environnement de <b>développement</b></p>
        <h1><?= $type ?></h1>
        <p class="message"><?= $message ?></p>
        <?php if(isset($tip)): ?>
        <p class="tip"><?= $tip ?></p>
        <?php endif; ?>
    </header>
    <?php foreach($trace as $tracePack): ?>
    <section class="trace">
        <?php foreach($tracePack as $k => $v): ?>
            <?php if(!in_array($k, ['args','type'])): ?>
            <p><code class="key"><?= $k ?></code> : <code class="value"><?= $v ?></code></p>
            <?php endif; ?>
        <?php endforeach; ?>
    </section>
    <?php endforeach; ?>
</body>
</html>