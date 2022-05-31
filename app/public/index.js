// fonction pour toggle le theme (light/dark)
function dark(){
   if (document.body.classList.contains('dark')) {
      document.body.classList.remove('dark');
      localStorage.removeItem('theme');
   } else {
      document.body.classList.add('dark');
      localStorage.setItem('theme','dark');
   }
}

// placer le theme courant dans le stockage de session (non transmis au serveur)
if (localStorage.getItem('theme') === 'dark') {
   document.body.classList.add('dark');
   document.querySelector('.checkbox').checked = 'true';
}

window.onload = () => {
   // focus sur le premier champ du formulaire (connexion/inscription)
   if (document.querySelector('.focus') !== null) {
      document.querySelector(".focus").focus();
   }
}