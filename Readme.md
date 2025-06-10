Mise en place un site WordPress complet via Docker Compose à partir des images nginx, mariadb, php.

docker-compose.yml:
    Base de données MariaDB
        image mariadb:11.7.2
        Variables d’environnement stockées dans un fichier séparé
        Healthcheck pour vérifier que MariaDB est bien démarrée
        volume persistant 
        Isolé dans un réseau Docker privé  LaPlateforme-net

    PHP 
        PHP customisé à partir de php:8.3-fpm via un Dockerfile.php 
        Attente que MariaDB soit prête avant de démarrer
        Configuration PHP gérée par un script d’entrée setup-wordpress.sh 
        Variables d’environnement : Pour ajuster la taille des uploads, la mémoire PHP, le temps d’exécution, etc.
        Volume partagé avec Nginx
        Healthcheck
        Isolé dans LaPlateforme-net

    Nginx
        Image nginx:1.27.5
        Mapping du port 80
        Configuration personnalisée montée via volume
        Healthcheck HTTP
        Montée du volume partagé WordPress en lecture seule




Dockerfile.php :
    Base : Image officielle php:8.3-fpm
    Installation des modules recommandés/obligatoires WordPress (mysqli, pdo, pdo_mysql, gd, exif, zip, intl,imagick)
    Configuration de GD pour la compatibilité maximale images
    Installation de procps pour permettre le healthcheck (pgrep)
    Nettoyage des caches apt pour une image plus légère
    Copie et permission d’exécution du script setup-wordpress.sh
    Utilisation de ce script comme point d’entrée du container pour générer dynamiquement la configuration PHP à partir des variables d’environnement



setup-wordpress.sh :
    Crée le fichier uploads.ini dans /usr/local/etc/php/conf.d/ en injectant les valeurs des variables d’environnement pour adapter la configuration de PHP à chaque démarrage du container.
    Applique les bonnes permissions sur /var/www/html (utilisateur et groupe www-data, droits 755) pour éviter les soucis d’accès ou d’écriture.
    Exécute php-fpm comme processus principal du container, afin de servir les requêtes PHP et de rester compatible avec l’architecture Docker



default.conf: 
    Compression GZIP, cache pour les fichiers statiques
    Routage classique pour WordPress (fichier/dossier/index.php)
    Gestion des requêtes PHP (timeouts, buffers)
    Blocage de l’accès à certains fichiers sensibles ou cachés
    Désactivation des logs d’accès/erreurs pour certains endpoints
    Headers de sécurité basiques
    Pages d’erreur personnalisées



.env :
    Attendez, c'est pas censé être ici ça?!