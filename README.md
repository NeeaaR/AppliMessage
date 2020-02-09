# AppliMessage

> git clone ...

> composer install

> php bin/console doctrine:database:create

> php bin/console make:migration

> php bin/console doctrine:migrations:migrate

> php bin/console doctrine:fixtures:load

Ne pas oublier de modifier l'url de la base de données dans .env !

# Membres du groupe :

> Classe B2A

- Alexis Morin

- Yohan Stoeckle

- Eléa Carton

# Nos remarques : 

Fixtures fonctionnelles

Problèmes rencontrés :

- l’upload de l’image pour le groupe fonctionne mais une erreur s’affiche lors de la création d’un groupe si on rajoute une image
error : Serialization of 'Symfony\Component\HttpFoundation\File\UploadedFile' is not allowed

Nous n'avons pas réussi à comprendre la source de cette erreur 
- Les fixtures sont fonctionnel
- Le statut des utilisateurs n’a pas pu être fait
