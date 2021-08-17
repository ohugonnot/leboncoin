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

## Todo
- Ajouter un système de création de Token crypté (JWT) avec un delais de validité et ne plus utiliser uniqement l'adresse mail comme token
- Ajouter un event listener sur les erreurs pour les transformer en JSON et améliorer les infos de retour et gérer les retours sensibles pour la sécurité
- Ajouter les marques et les modèles pour la catégorie Automobile
- Ajouter un système de recherche avec un % de match sur les modèles
- Ajouter un système de cache pour l'API avec Doctrine Cache pour améliorer les perfs
- Ajouter les tests unitaires et fonctionnels sur les parties sensibles (route POST, PUT, DELETE, GET et la recherche)
- Monter le projet avec docker

### Problèmes et questionnements rencontrés
- Architecture de la BDD, une annonce globale avec des champs dynamiques VS plusieurs tables avec les types d'annonce (Automobile, Emploi, Immobilier) à débattre     
Je suis parti sur une seul table Annonce en BDD avec un formulaire et des assertions dynamique poru vérifier la cohérence de l'annonce mais je suis pas forcément convaincu
Après réflexion je suis partie sur de l'inhéritance Mapping vu que doctrine semble gérer se genre de dilème plutot bien
- Je suis parti sur un firewall et provider partagé et les nouvelles normes d'authentification de Symfony 5 qui sont en test actuellement a vérifier que cela fonctionne bien