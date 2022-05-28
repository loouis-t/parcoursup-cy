function chercher(e) {
    let eleve = e.parentNode.children[0].value;

    if (eleve.length > 0) {
        window.location.href = "/accueil/accueil.php?page=attributions#" + eleve.toLowerCase();
    }
}

function entrer(e, event) {
    if (event.keyCode === 13) {
        chercher(e.parentNode.children[0]);
    }
}

function modifier_option(e, nouvelle_option, ancienne_option, mail) {
    console.log(nouvelle_option);
    console.log(ancienne_option);
    console.log(mail);

    // requete ajax
    let xhr = new XMLHttpRequest();
    xhr.open("GET", `/accueil/attributions/ajax/modifier_attribution.php?mail=${mail}&ancienne_option=${ancienne_option}&nouvelle_option=${nouvelle_option}`, true);
    
    xhr.onreadystatechange = function () {
        if (
            xhr.readyState === 4
            && xhr.status === 200
            && xhr.responseText === "success"
        ) {
            window.location.reload();
        }
    }

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send();
}