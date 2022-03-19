<?php

namespace Antoineg\Omniscient\Core\CLI;

use Antoineg\Omniscient\Core\Traits\CLITrait;

class layout
{
    
    use CLITrait;

    private $desc = '($layout,$echoResult = true) Génère un fichier de layout $layout.php. Si $echoResult est vrai, la fonction echo le résultat.';

    public function run($layout,$echoResult = true)
    {
        $layoutFile = realpath($this->cli::LAYOUTS_DIR) . "\\$layout.php";
        $dirToCreate = dirname($layoutFile);
        $this->create_dir($dirToCreate);


        $content = <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/styles/css/main.css">
</head>
<body>
    <div id="content" class="<?= \$contentClass ?? '' ?>">
        <?= \$content ?>
    </div>
</body>
</html>
HTML;


    
        if(!file_exists($layoutFile))
        {
            if(file_put_contents($layoutFile,$content))
            {
                if($echoResult)
                {
                    echo $this->string("Le layout $layout.php a bien été généré.\n\n",'light_green');
                }
            }
            else
            {
                if($echoResult)
                {
                    echo $this->string("Un problème est survenu : layout non généré.\n\n",'light_red');
                }
            }
        }
        else
        {
            if($echoResult)
            {
                echo $this->string("Le layout $layout existe déjà et ne peut être écrasé.\n\n",'light_cyan');
            }
        }
    }
    
}