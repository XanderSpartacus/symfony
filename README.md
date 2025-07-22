# 🌐 Projet Portail - Symfony 7.3

~~Portail sécurisé avec rôles `user`, `admin` et `partner`~~, utilisant Symfony 7.3, PHP 8.2+, et une stack Docker pour la base de données, l'interface d'administration et les mails.

## ⚙️ Démarrage rapide

### Prérequis

- PHP ≥ 8.2
- Symfony CLI
- Docker + Docker Compose
- Composer

### Installation

```bash
# Cloner le repo
git clone https://github.com/XanderSpartacus/symfony.git
cd symfony

# Lancer les conteneurs
docker compose up -d

# Créer le projet Symfony dans le dossier portail
symfony new portail --version=7.3 --webapp

# Se déplacer dans le dossier portail
cd portail
```
### Accès à l'application

Une fois le serveur Symfony lancé :

```bash
# Lancer le serveur local Symfony
symfony server:start -d
```
➡️ L'application est accessible à l'adresse : http://localhost:8000

### Arrêt du serveur

```bash
symfony server:stop
```

### 🐳 Stack Docker

Le fichier [`docker-compose.yml`](./docker-compose.yml) configure :

- `database` : MariaDB avec volume persistant `db_data`
- `phpmyadmin` : accessible sur [http://localhost:8082](http://localhost:8082)
- `mailer` : Mailpit (SMTP : 1025, UI : [http://localhost:8025](http://localhost:8025))

```code
services:
  - database (MariaDB 10.x)
  - phpmyadmin (port 8082)
  - mailer (Mailpit, ports 1025/8025)
```

#### Volume persistant :
db_data (base de données)

### Rappels Docker utiles

```bash
docker compose up -d          # Démarrer les conteneurs
docker ps                     # Voir les conteneurs actifs
docker compose down           # Stop + suppression conteneurs
docker compose down -v        # Stop + suppression totale (incl. volume)
docker compose logs -f [svc]  # Logs en live
docker exec -it [container] bash # Accès shell conteneur
```

### Bundles installés
```bash
# Fixtures pour données de test
composer require --dev orm-fixtures

# Pagination
composer require knplabs/knp-paginator-bundle
```

### 🔐 Sécurité à venir

- 🔐 Pare-feu par rôles (user, admin, partner)

- 🔑 Authentification + gestion des accès

- 🛂 Zone publique / privée

- 🗂 Gestion des utilisateurs et des permissions

### 🗂 Structure du projet
```code
symfony/
├── docker-compose.yml
└── portail/
    ├── bin/
    ├── config/
    ├── public/
    ├── src/
    ├── templates/
    ├── translations/
    ├── var/
    └── vendor/
```

### 📌 À faire

- [ ] Implémentation du système de rôles
- [ ] Design des pages

### 📜 Licence

Projet personnel / pédagogique. Libre d’inspiration.
