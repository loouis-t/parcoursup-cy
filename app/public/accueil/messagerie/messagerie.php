<?php
    date_default_timezone_set('Europe/Paris');
    $user = false;
    if (isset($_POST['dest'])) {
        // check si le destinataire fait partie de la liste des élèves
        if(($handle_users = fopen("../../backend/db/identifiants.csv", "r")) !== FALSE) {
            while (($data_users = fgetcsv($handle_users, 1000, ",")) !== FALSE) {
                if (strtolower($data_users[2]) === strtolower($_POST['dest'])) {
                    $user = true;
                    // poster le message si user existe
                }
            }
            fclose($handle_users);
        }
        if (isset($_POST['message'])) {
           if(($handle = fopen("../../backend/db/messagerie.csv", "a+")) !== FALSE) {
             while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $message = [ date("Y-m-d"), date("H:i:s"), strtolower($_SESSION['mail']), strtolower($_POST['dest']), htmlspecialchars($_POST['message']) ];
                fputcsv($handle, $message);
             }
             fclose($handle);
          }
       }
       
        if ($user === false) {
            header('Location: /accueil/accueil.php?page=messagerie&err=dest&dest=' . $_POST['dest']);
            exit();
        }
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../../root.css">
        <link rel="stylesheet" href="/accueil/messagerie/messagerie.css">
        <script src="/accueil/messagerie/messagerie.js" defer></script>
        <title>Accueil</title>

        <!-- fonts depuis le cdn de google (coucou @Baptiste) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    </head>
    <body>
        <h2>Messagerie</h2>
        <article class="messagerie">
            <div class="messagerie__destinataires">
                <form class="messagerie__form" action="/accueil/accueil.php?page=messagerie" method="post">
                <?php
                        $unique_conv = [];
                        if(($handle = fopen("../../backend/db/messagerie.csv", "r")) !== FALSE) {
                            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                // afficher toutes les conversations qui me concernent
                                if (
                                    (
                                        // là où je suis expéditeur
                                        strtolower($data[2]) == strtolower($_SESSION['mail'])
                                        && !in_array($data[3], $unique_conv)
                                    ) || (
                                        // là où je suis destinataire
                                        strtolower($data[3]) == strtolower($_SESSION['mail'])
                                        && !in_array($data[2], $unique_conv)
                                    )
                                ) {
                                    if ($data[2] === strtolower($_SESSION['mail'])) {
                                        $destinataire = $data[3];
                                    } else {
                                        $destinataire = $data[2];
                                    }
                                    array_push($unique_conv, $destinataire);
                                    echo '<input class="button" type="submit" name="dest" value="' . $destinataire . '">';
                                }
                            }
                            fclose($handle);

                            if (
                               isset($_POST['dest'])
                               && !in_array($_POST['dest'], $unique_conv)
                            ) {
                                echo '<input class="button" type="submit" name="dest" value="' . $_POST['dest'] . '">';
                            } else if (
                               isset($_GET['dest'])
                               && !in_array($_GET['dest'], $unique_conv)
                            ) {
                                echo '<input class="button" type="submit" name="dest" value="' . $_GET['dest'] . '">';
                            }
                        }
                    ?>
                </form>
                <form class="messagerie__form" action="/accueil/accueil.php?page=messagerie" method="post">
                    <input type="text" name="dest" placeholder="Destinataire" autocomplete="off">
                    <input class="button" type="submit" value="+">
                </form>
            </div>
            <section class="messagerie__conversation">
                <div class="messagerie__conversation__messages">
                    <?php
                        if(isset($_GET['err']) && $_GET['err'] === 'dest') {
                            echo '<p class="error" style="align-self: center;">Cet utilisateur n\'a pas encore créé de compte sur la platefrome.</p>';
                        }
                        // récupérer les messages de la conversation
                        if (
                            isset($_POST['dest'])
                            || isset($_GET['dest'])
                        ) {
                            $destinataire = (isset($_POST['dest'])) ? $_POST['dest'] : $_GET['dest'];
                            if(($handle = fopen("../../backend/db/messagerie.csv", "r")) !== FALSE) {
                                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                    if(
                                        strtolower($_SESSION['mail']) == strtolower($data[2])
                                        && strtolower($destinataire) == strtolower($data[3])
                                    ) {
                                        // messages que j'ai envoyé
                                        echo "<p class='right'>" . htmlspecialchars_decode($data[4]) . "<span>" . $data[1] . "</span></p>";
                                    } else if (
                                        strtolower($destinataire) == strtolower($data[2])
                                        && strtolower($_SESSION['mail']) == strtolower($data[3])
                                    ) {
                                        // messages que j'ai reçu
                                        echo "<p class='left'>".$data[4]."<span>".$data[1]."</span></p>";
                                    }
                                }
                                fclose($handle);
                            }
                        }
                    ?>
                </div>
                <?php
                    if(
                        isset($_POST['dest'])
                        || isset($_GET['dest'])
                    ) {
                        $destinataire = isset($_POST['dest']) ? $_POST['dest'] : $_GET['dest'];
                        echo '
                            <form class="messagerie__conversation__form" action="/accueil/accueil.php?page=messagerie" method="post">
                                <input type="hidden" name="dest" value="' . htmlspecialchars(strtolower($destinataire)) . '">
                                <input type="text" name="message" placeholder="Message" autocomplete="off">
                                <input class="button" type="submit" name="envoyer" value="Envoyer">
                            </form>';
                    }
                ?>

            </section>
        </article>
    </body>
</html>
