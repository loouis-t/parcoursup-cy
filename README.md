# Parcoursup-CY

Plateforme de répartition des options d'ING3 suivant les choix et les moyennes des ING2.  
Une sorte de parcoursup, mais en moins bien.

## Production

Démarrer un serveur en mode production (sur la branche 'main' : théoriquement stable)

```
./start.sh    --prod  (si bash par defaut)
bash start.sh --prod  (sinon)
```

**Note :** ```-p est un raccourci de --prod``` fonctionne aussi.

Le serveur est lancé à l'adresse [localhost:8080](http://localhost:8080)
  
## Développement

```
git clone git@gitlab.etude.eisti.fr:loouis-t/parcoursup-eisti.git   (pour récup le dossier)
cd parcoursup-eisti
./start.sh                                                          (ou: bash start.sh)
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

**Note :** Les branches sont désignées par le nom du développeur par simplicité, mais elles correspondent (plus ou moins) au développement (ou au débugage) d'une fonction.  
*Attention:* une branche est supprimée (localement et à distance) dès lors que vous acceptez de suivre la branche 'main' (choix proposé à l'exécution de ```start.sh```).
  
## Structure

```
racine/
    |_ app/
        |_ backend/
            |_ api/                                     --> les php appelés par le frontend
            |_ db/                                      --> tous les csv
                |_ placesFinales/                       --> options attribuées
        |_ public/
            |_ accueil/
                |_ attributions/                        --> attribution d'option :      admission
                    |_ ajax                             --> php appelées depuis js
                |_ inscriptions/                        --> inscriptions en masse :     admin
                |_ logs/                                --> logs actions importantes :  admin
                |_ messagerie/
                    |_ ajax                             --> php appelées depuis js
                |_ profil/
                |_ scolarite/                           --> données de chaque éleve :   eleve
            |_ assets                                   --> images/logos/...
                |_ pdps/                                --> photos de profil
            |_ connexion/                               --> connexion utilisateur
            |_ inscription/                             --> inscription individuelle
            |_ index.php, main.css, main.js, root.css   --> point d'entrée serveur
    |_ README.md
    |_ start.sh                                         --> lancement serveur
```

## Note au correcteur : 

**Utilisateurs principaux :** 

- admin
- admission

**Remarque :** Le mot de passe par défaut de ces utilisateurs est **admin**

**Utilisateurs ajoutés en masse :** mot de passe généré sur 10 caractères à partir de l'alphabet (minuscule et majuscule), des chiffres de 0 à 9 et de 15 caractères spéciaux différents.
  

**Blocage et signalements :**
Les utilisateurs bloqués sont notés dans le csv 'utilisateurs_bloques.csv'.
Les signalements dans 'signalements.csv'.
Seul l'administrateur (admin) a accès à ces données (page *logs*).
  

**Photos de profil :**
Les photos de profil (placées dans le répertoire 'assets/pdps/') sont nommées selon l'adresse mail de l'utilisateur.
Ex : ```pdps/travauxlou@cy-tech.fr.jpg```
Si l'utilisateur n'a pas upload sa photo, une photo par défaut est utilisée (le fameux *lama*).
Cette photo par défaut est appelée : ```pdps/pdp__inconnue.jpg```

---

© 2022, parcoursup-eisti  
Louis Travaux, Baptiste Hennuy, Ugo Latry, Yan Arresseguet, Edouard Calzado  
CY-Tech Pau, P2