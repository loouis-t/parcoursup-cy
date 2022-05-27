<?php
    date_default_timezone_set('Europe/Paris');
    $user = false;
    if (isset($_POST['dest'])) {
        // check si le destinataire fait partie de la liste des élèves
        if(($handle_users = fopen("../../backend/db/identifiants.csv", "r")) !== FALSE) {
            while (($data_users = fgetcsv($handle_users, 1000, ",")) !== FALSE) {
                if (strtolower($data_users[2]) === strtolower($_POST['dest'])) {
                    $user = true;
                    break;
                }
            }
            fclose($handle_users);
        }

        // ajouter message s'il est post
        if (
            isset($_POST['message']) 
            && $_POST['message'] !== ''
        ) {
            if(($handle = fopen("../../backend/db/messagerie.csv", "a+")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $message = [ date("Y-m-d"), date("H:i:s"), strtolower($_SESSION['mail']), strtolower($_POST['dest']), htmlspecialchars($_POST['message']) ];
                    fputcsv($handle, $message);
                }
                fclose($handle);
            }
        }

        // si l'utilisateur n'existe pas, on affiche un message
        if ($user === false) {
            header('Location: /accueil/accueil.php?page=messagerie&err=dest&dest=' . strtolower($_POST['dest']));
            exit();
        } else {
            header('Location: /accueil/accueil.php?page=messagerie&dest=' . strtolower($_POST['dest']));
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
                                    array_push($unique_conv, $destinataire);                                                // ne pas afficher deux fois le même destinataire
                                    if ($destinataire === $_GET['dest']) { $destinataire = '• ' . $destinataire; }          // afficher conversation en cours
                                    echo '<input class="button" type="submit" name="dest" value="' . $destinataire . '">';  // afficher le destinataire
                                }
                            }
                            fclose($handle);

                            // entamer une conversation: afficher le nouveau destinataire dans la barre latérale
                            // même si pas de message pour l'instant
                            if (
                                isset($_GET['dest'])
                                && $_GET['dest'] !== ''
                                && !in_array($_GET['dest'], $unique_conv)
                            ) {
                                echo '<input class="button" type="submit" name="dest" value="• ' . $_GET['dest'] . '">';
                            }
                        }
                    ?>
                </form>
                <form class="messagerie__form" action="/accueil/accueil.php?page=messagerie" method="post">
                    <input type="text" name="dest" placeholder="Destinataire" autocomplete="off" required>
                    <input class="button" type="submit" value="+">
                </form>
            </div>
            <section class="messagerie__conversation">
                <div class="messagerie__conversation__messages">
                    <?php
                        $dest_bloque = false;
                        $classe_bloque = "";

                        // récupérer les messages de la conversation
                        if (isset($_GET['dest'])) {

                            // vérifier si l'utilisateur n'est pas bloqué
                            if(($handle = fopen("../../backend/db/utilisateurs_bloques.csv", "r")) !== FALSE) {
                                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                    if (
                                        $data[2] === $_SESSION['mail']
                                        && $data[3] === $_GET['dest']
                                    ) {
                                        // le signaler
                                        echo '<p class="error" style="align-self: center;">Vous avez bloqué cet utilisateur.</p>';
                                        $dest_bloque = true;                    // ne pas afficher si destinataire est inscrit sur la plateforme
                                        $classe_bloque = "utilisateur_bloque";  // blur les messages
                                        break;
                                    }
                                }
                                fclose($handle);
                            }

                            // préciser si destinataire est inscrit sur la plateforme
                            // uniquement s'il n'est pas bloqué
                            if (
                                !$dest_bloque
                                && isset($_GET['err'])
                                && $_GET['err'] === 'dest'
                            ) {
                                echo '<p class="error" style="align-self: center;">Cet utilisateur n\'a pas encore créé de compte sur la platefrome.</p>';
                            } else { 
                                // créer un élément vide pour afficher un message si on bloque destinataire par la suite
                                echo '<p class="error" style="align-self: center;"></p>';
                            }


                            // afficher la conversation
                            if(($handle = fopen("../../backend/db/messagerie.csv", "r")) !== FALSE) {
                                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                    if(
                                        strtolower($_SESSION['mail']) == strtolower($data[2])
                                        && strtolower($_GET['dest']) == strtolower($data[3])
                                    ) {
                                        // messages que j'ai envoyé
                                        echo "<p class='right $classe_bloque' onclick='options(this)''>"
                                            . htmlspecialchars_decode($data[4]) .
                                            "<span>" . $data[1] . "</span>
                                            <span>
                                                <attr title='Supprimer le message'>
                                                <a onclick='message_supprimer(this)'><img src='https://img.icons8.com/ios-glyphs/16/ffffff/delete-forever.png'/></a>
                                                </attr>
                                            <span>
                                        </p>";
                                    } else if (
                                        strtolower($_GET['dest']) == strtolower($data[2])
                                        && strtolower($_SESSION['mail']) == strtolower($data[3])
                                    ) {
                                        // messages que j'ai reçu
                                        echo "<p class='left $classe_bloque' onclick='options(this)'>"
                                            . $data[4] .
                                            "<span>" . $data[1] . "</span>
                                            <span>
                                                <attr title='Signaler le message'>
                                                <a class='signaler' onclick='message_signaler(this)'><img src='https://img.icons8.com/pastel-glyph/16/ffffff/error--v5.png'/></a>
                                                </attr>
                                            </span>
                                        </p>";
                                    }
                                }
                                fclose($handle);
                            }
                        }
                    ?>
                </div>
                <?php
                    // form pour envoyer un message
                    if(isset($_GET['dest'])) {
                        echo '
                            <form class="messagerie__conversation__form" action="/accueil/accueil.php?page=messagerie" method="post">
                                <input type="hidden" name="dest" value="' . htmlspecialchars(strtolower($_GET['dest'])) . '">
                                <input class="message_input_field toHide ' . $classe_bloque . '" type="text" name="message" placeholder="Message" autocomplete="off">
                                <div>
                                    <input class="button toHide ' . $classe_bloque . '" type="submit" name="envoyer" value="Envoyer">
                                    <a class="button bloquer" onclick="bloquer(this)" name="' . $_SESSION['mail'] . '"><abbr title="Bloquer le destinataire"><img src="https://img.icons8.com/ios-glyphs/16/ffffff/no-entry.png"/></abbr></a>
                                </div>
                            </form>';
                    }
                ?>

            </section>
        </article>
    </body>
</html>
