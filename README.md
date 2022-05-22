# Parcoursup-EISTI

*(si vous avez des meilleures idées pour le nom...)*

Répartition des options suivant choix ING2

## Structure

```
racine/
    |_ app/
        |_ backend/
            |_ api/     --> tous les .php pour tout le AJAX
            |_ db/      --> tous les csv
        |_ public/
            |_ connexion/
            |_ inscription/
            |_ accueil/
            |_ index.php, main.css, main.js
            |_ assets   --> images/logos/...
    |_ README.md        --> tah la documentation
```



## Développer

```
git clone git@gitlab.etude.eisti.fr:loouis-t/parcoursup-eisti.git   (pour récup le dossier)
cd parcoursup-eisti
```

**RQ :**```./start.sh``` fait le ```checkout``` et le ```pull``` ci-dessous pour vous.

```
git checkout -b <nom de ta branche>                                 (pour créer ta branche en local)
git pull origin <nom de ta branche distante>                        (pour récup ta branche distante sur ta branche locale)
```

**Voir le site :** ```ctrl + clic``` sur ```localhost:8080``` dans le terminal (ou [ici](http://localhost:8080)).

**Pour publier :**

```
git add .
git commit -m "<commentaire>"
git push origin <nom de ta branche>
```

## Démarrer un serveur

```
./start.sh      (si bash par defaut)
bash start.sh   (sinon)
```

Le serveur est lancé à l'adresse [localhost:8080](http://localhost:8080)
  
**On s'occupe de tout fusionner avec @Baptiste**