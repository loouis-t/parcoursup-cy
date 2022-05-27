const page = document.querySelector(".messagerie");
const messagerie = document.querySelector(".messagerie__conversation__messages");
const inputArea = document.querySelector('.message_input_field');
let nb_clics = 0;                                       // cliquer n'importe où pour sortir mode 'options'

messagerie.scrollTo(0, messagerie.scrollHeight);        // toujours aller au dernier message

if (
    inputArea != null
    && !inputArea.classList.contains('utilisateur_bloque')
) {
    inputArea.focus();
}


// fonction pour afficher les options du message (et masquer autres messages)
function options(e) {
    e.classList.add("options__active");                 // ajout class active au bouton
    let position_element = e.getBoundingClientRect();   // caractéristiques géographiques du bouton

    // blur tout le reste (mettre en valeur le message (e))
    e.parentNode.childNodes.forEach((child, index) => {
        if (
            // uniquement les messages:
            // pas les deux 'pseudo' éléments (rajoutés par php?)
            index != 0 
            && index != e.parentNode.childNodes.length - 1
            && !child.classList.contains("options__active")
            && nb_clics == 0
        ) {
            child.classList.add("options__inactive");
        }
    });

    handle_exit();
}

// permettre la sortie du mode 'options'
function handle_exit() {
    page.onclick = () => {
        nb_clics++; // compter nombre de clics (onclick="options(this)" compte pour 1)
        
        // retirer les class active et inactive (partout)
        messagerie.childNodes.forEach((child, index) => {
            if (
                index != 0 
                && index != messagerie.childNodes.length - 1
                && nb_clics == 2
            ) {
                child.classList.remove("options__active", "options__inactive");
            }
        });

        // sortir de la fonction si on a cliqué autre part
        if (nb_clics == 2) { nb_clics = 0; return; }
    }
}

// option de message: supprimer
function message_supprimer(e) {
    const message = e.parentNode.parentNode.parentNode.innerHTML.split('<span>')[0];   // message
    const heure = e.parentNode.parentNode.childNodes[1].innerHTML;          // heure

    window.location.search.split('&').forEach((element) => {
        if (
            element.split('=')[0] == 'dest'
            && element.split('=')[1] != ''
        ) {
            const destinataire = element.split('=')[1];

            // envoyer requête ajax pour supprimer le message
            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    e.parentNode.parentNode.remove();
                    return;
                }
            }
            
            xhr.open("GET", `/accueil/messagerie/ajax/suppr_msg.php?dest=${destinataire}&message=${message}&heure=${heure}`, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send();
        }
    });
}

// option de message: signaler
function message_signaler(e) {
    const message = e.parentNode.parentNode.parentNode.innerHTML.split('<span>')[0];   // message
    const heure = e.parentNode.parentNode.parentNode.childNodes[1].innerHTML;          // heure

    window.location.search.split('&').forEach((element) => {
        if (
            element.split('=')[0] == 'dest'
            && element.split('=')[1] != ''
        ) {
            const destinataire = element.split('=')[1];

            // envoyer requête ajax pour supprimer le message
            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    if (this.responseText == "success") {
                        alert(
                            `Nous avons bien pris en compte votre signalement.\nNous vous remercions de votre contribution.\n\nPersonne signalée : ${destinataire}.
                            `);
                    } else { alert(this.responseText); }
                    return;
                }
            }
            
            xhr.open("GET", `/accueil/messagerie/ajax/signaler_msg.php?dest=${destinataire}&message=${message}&heure=${heure}`, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send();
        }
    });
}

// fonction pour bloquer le destinataire
function bloquer(e) {
    let xhr, destinataire, signaleur;

    window.location.search.split('&').forEach((element) => {
        if (
            element.split('=')[0] == 'dest'
            && element.split('=')[1] != ''
        ) {
            destinataire = element.split('=')[1];
            signaleur = e.name;
            xhr = new XMLHttpRequest();
        } else { return; }
    });

    // si le destinataire n'est pas bloqué, on le bloque
    if (!inputArea.classList.contains('utilisateur_bloque')) {
        let res = confirm("Etes vous sûr de vouloir bloquer cet utilisateur?");
        if (res) {
            const motif = prompt("Veuillez indiquer un motif de blocage : ");
            
            // envoyer requête ajax pour bloquer le destinataire
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // blur les messages (droite et gauche)
                    document.querySelectorAll('.right, .left, .toHide').forEach((child) => {
                        child.classList.add('utilisateur_bloque');
                    });
                
                    // préciser que l'utilisateur est bloqué
                    document.querySelector('.error').innerHTML = "Vous avez bloqué cet utilisateur.";

                    return;
                }
            }
            
            xhr.open("GET", `/accueil/messagerie/ajax/bloquer_user.php?dest=${destinataire}&bloqueur=${signaleur}&motif=${motif}`, true);
        } else { return; }
    } else {
        // sinon (si il est bloqué) on le débloque
        let res = confirm("Etes vous sûr de vouloir débloquer cet utilisateur?");
        if (res) {
            // envoyer requête ajax pour débloquer le destinataire
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    window.location.href =`/accueil/accueil.php?page=messagerie&dest=${destinataire}`;
                }
            }

            xhr.open("GET", `/accueil/messagerie/ajax/bloquer_user.php?dest=${destinataire}&bloqueur=${signaleur}&debloquer=true`, true);
        } else { return; }
    }

    // envoyer requête ajax
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send();
    return;
}