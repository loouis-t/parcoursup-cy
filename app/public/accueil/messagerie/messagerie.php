<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../../root.css">
        <link rel="stylesheet" href="/accueil/messagerie/messagerie.css">
        <title>Accueil</title>

        <!-- fonts depuis le cdn de google (coucou @Baptiste) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet"> 
    </head>
    <body>
        <h2>Messagerie</h2>
        <article class="messagerie">
            <form class="messagerie__form" action="/accueil/accueil.php?page=messagerie" method="post">
                <?php
                    $unique_conv = [];
                    if(($handle = fopen("../../backend/db/messagerie.csv", "r")) !== FALSE) {
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            if(strtolower($data[2]) == strtolower($_SESSION['mail']) && !in_array($data[3], $unique_conv)) {
                                array_push($unique_conv, $data[3]);
                                echo '<input class="button" type="submit" name="dest" value="' . strtolower($data[3]) . '">';
                            }
                        }
                        fclose($handle);
                    }
                ?>
            </form>
            <section class="messagerie__conversation">
                <?php
                    if (isset($_POST['dest'])) {
                        if(($handle = fopen("../../backend/db/messagerie.csv", "r")) !== FALSE) {
                            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                if(
                                    strtolower($_SESSION['mail']) == strtolower($data[2])
                                    && strtolower($_POST['dest']) == strtolower($data[3])
                                ) {
                                    echo "<p class='right'>".$data[4]."</p>";
                                } else if (
                                    strtolower($_POST['dest']) == strtolower($data[2])
                                    && strtolower($_SESSION['mail']) == strtolower($data[3])
                                ) {
                                    echo "<p class='left'>".$data[4]."</p>";
                                }
                            }
                            fclose($handle);
                        }
                    }
                ?>
            </section>
        </article>
    </body>
</html>