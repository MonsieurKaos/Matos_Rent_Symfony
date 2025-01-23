# Matos Rent



## Installation
l'application necessite l'installation de :
- [ ] [php](https://www.php.net/)
- [ ] [Symfony](https://symfony.com/)
- [ ] [Composer](https://getcomposer.org/)
- [ ] [MariaDB](https://mariadb.org/download/?t=mariadb)

### MariaDB
Dans un premier temps, il convient d'installer et de configurer correctement MariaDB.

Pour permettre à PHP de correctement créer la base de données lorsque nous allons vouloir importer la base de données il faut activer des plugins dans le fichier 
/etc/php/php.ini

```
nano /etc/php/php.ini
```

Décommentez ensuite les lignes suivantes : 

```
[...]
extension=curl
[...]
extension=iconv
extension=intl
[...]
extension=mysqli
[...]
extension=pdo_mysql
```
Une fois fais il sera possible de correctement importer les migrations plus tard.

### Créer la base et l'utilisateur
Sous mariadb créer la base

```
CREATE DATABASE materiel;
```

Créer ensuite l'utilisteur en lui donnant les droits sur la base :
```
CREATE USER 'materiel'@'localhost' IDENTIFIED BY 'li8Rgvzz';
GRANT ALL PRIVILEGES ON materiel.* TO 'materiel'@'localhost';
FLUSH PRIVILEGES;
```

### L'application

cloner le repository dans un répertoire sur votre PC :
```
git clone https://github.com/MonsieurKaos/Matos_Rent_Symfony.git matos_rent
```
descendre dans le répertoire du projet et lancer la configuration projet 
```
cd mmatos_rent
composer install
```
Vous aurez peut être besoin de modifier la ligne DATABASE_URL dans le fichier .env selon votre installation :

```
DATABASE_URL="mysql://materiel:li8Rgvzz@127.0.0.1:3306/materiel?serverVersion=11.6.2-MariaDB&charset=utf8mb4"
```


Initialiser les données de base de l'application :
```
php bin/console doctrine:migrations:migrate  
```

### Lancement de l'application

Pour lancer l'application, se positionner dans le repertoire applicatif
```
symfony server:start
```
- [ ] [http://127.0.0.1:8000/initAppli](http://127.0.0.1:8000/initAppli)

Se connecter une première fois avec mael@morin.fr et son mot de passe "toto"

### Lancement des tests Cypress

Pour lancer les tests cypress il faut dans un premier temps s'assurer que Nodejs et npm sont bien installer sur l'ordinateur.

```
npm -v
```

Si c'est le cas on peut lancer cypress avec la commande suivante : 

```
npm run cypress:open
```