## Start
J'utilise Docker et adminer pour la Base de donnee.

1 - Creer la Base de donnee a l'aide de l'invite de commande avec les commande :
    - symfony console doctrine:database:create
    -  symfony console doctrine:schema:create


## Admin :
1 Creer un utiliseur dans la bdd, pour crypter le mot de passe utiliser la commande sercurity:hash-password


## Test
l faut faire les commandes suivantes :
```shell
symfony console doctrine:database:create --env=test
symfony console doctrine:schema:create --env=test

ATTENTION pour les test l'images sera utiliser une fois, donc penser a remettre l'image dans le dossier tests a la racine. Vous trouverez l'image dans e dossier image a la racine si besoin.

## Main : 
Pour les mail il faut activer l'extention curl dans le php.ini, un fichier doit etre passer au php.ini le fichier ce trouve dans le dossier "file" a la racine du projet. passer le chemin avec le nom du fichier dans le php.ini dans "curl.cainfo="