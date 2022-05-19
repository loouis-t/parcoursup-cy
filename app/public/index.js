function dark(){
   if (document.body.classList.contains('dark')) {
      document.body.classList.remove('dark');
      localStorage.removeItem('theme');
   } else {
      document.body.classList.add('dark');
      localStorage.setItem('theme','dark');
   }
}

if (localStorage.getItem('theme') === 'dark') {
   document.body.classList.add('dark');
   document.querySelector('.checkbox').checked = 'true';
}
