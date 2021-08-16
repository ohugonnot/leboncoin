## Documentation

### Installer les dépendances
```shell
git clone git@github.com:ohugonnot/leboncoin.git
composer install
yarn install
encore dev --watch
```

#### Connecter a une base de donnée en modifiant le .env
```dotenv
DATABASE_URL="mysql://root:@127.0.0.1:3306/leboncoin?serverVersion=5.7"
```
Dans le repertoire du projet
```shell
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
// Charger les fixtures (fake data)
php bin/console doctrine:fixtures:load
```

### Monter le serveur
```shell
// avec le symfony executeur
symfony server:start
// sinon
composer require symfony/web-server-bundle --dev
php bin/console server:start
```

### Pour se connecter
http://localhost:8000/      
login : test1@test.test      
mdp : test