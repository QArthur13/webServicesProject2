# webServicesProject2

*Projet en construction*

*commandes utiles*
# ws-authjwt
# composer create-project symfony/website-skeleton ws-authjwt
# php -S localhost:8000 -t public/
# composer require api
# composer require symfony/orm-pack
# php bin/console doctrine:database:create
# php bin/console make:entity
# php bin/console make:migration
# php bin/console doctrine:migration:migrate   
# composer require --dev doctrine/doctrine-fixtures-bundle
# composer require fakerphp/faker
# php bin/console doctrine:fixtures:load
# php bin/console debug:route

Pour create JWT:
Installation:
- composer require lexik/jwt-authentication-bundle
- mkdir config/jwt
- Créer deux clees, depuis un console openssl, exemble:
    * genrsa -out C:\Users\thanh\Desktop\YNOV\YNOV-M2\WEB-SERVICE\webServicesProject2/config/jwt/private.pem -aes256 4096
    * pkey -in C:\Users\thanh\Desktop\YNOV\YNOV-M2\WEB-SERVICE\webServicesProject2/config/jwt/private.pem -out C:\Users\thanh\Desktop\YNOV\YNOV-M2\WEB-SERVICE\webServicesProject2/config/jwt/public.pem -pubout
Entrée un mot de passe du clé privée pour le clé publique

Saisir le mot de passe dans .env

###> lexik/jwt-authentication-bundle ###
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PASSPHRASE=Thanh
###< lexik/jwt-authentication-bundle ###

The lazy anonymous mode prevents the session from being started if there is no need for authorization (i.e. explicit check for a user privilege). This is important to keep requests cacheable (see HTTP Cache).
composer require --dev symfony/profiler-pack