# Parcoursup-EISTI

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

## Développement

```
git clone git@gitlab.etude.eisti.fr:loouis-t/parcoursup-eisti.git   (pour récup le dossier)
cd parcoursup-eisti
```

**RQ :**```bash start.sh``` (ou ```./start.sh``` si bash présent sur le pc) fait le ```checkout``` et le ```pull``` expliqués ci-dessous pour vous.

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

**Note :**

Les branches sont désignées par le nom du développeur par simplicité, mais elles correspondent (plus ou moins) au développement (ou au débugage) d'une fonction.  
Attention: une branche est supprimée (localement et à distance) dès lors que vous acceptez de suivre la branche 'main' (choix proposé à l'exécution de ```start.sh```).

## Production

Démarrer un serveur en mode production (sur la branche 'main' : théoriquement stable)

```
./start.sh    --prod  (si bash par defaut)
bash start.sh --prod  (sinon)
```

**Note :** ```-p est un raccourci de --prod``` fonctionne aussi.

Le serveur est lancé à l'adresse [localhost:8080](http://localhost:8080)
