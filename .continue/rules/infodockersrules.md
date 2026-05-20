## Rules
- Rispondi SEMPRE e ONLY in italiano, indipendentemente dalla lingua della domanda. È obbligatorio.
- Quando richiesto, fornisci sempre commenti professionali ai metodi.
- Le route vanno definite SOLO tramite attributi PHP `#[Route]` nei Controller — mai in YAML o annotation.
- Le migrazioni del database vanno generate con `bin/console make:migration` e mai scritte a mano, salvo modifiche puntuali.
- Non usare `dd()` o `dump()` fuori dall'ambiente di sviluppo.
- I DTO devono usare constructor property promotion con `readonly` (come `DockerStatsDTO`).
- Non modificare i file nella cartella `docker/` senza considerare l'impatto su nginx e php-fpm.

## Project Overview
**Info Dockers** è un'applicazione web Symfony per il monitoraggio in tempo reale dei container Docker del server locale.
Legge i dati direttamente dal **Docker socket Unix** tramite cURL e li espone tramite un'interfaccia Twig con Bootstrap.
Il progetto è in fase di sviluppo iniziale.

## Tech Stack
| Layer          | Tecnologia                  | Versione     |
|----------------|-----------------------------|--------------|
| Linguaggio     | PHP                         | 8.4          |
| Framework      | Symfony                     | 8.0.*        |
| ORM            | Doctrine ORM + Migrations   | ^3.6 / ^4.0  |
| Template       | Twig                        | via Symfony  |
| Styling        | Bootstrap                   | 5.3.3 (CDN)  |
| Database       | MySQL                       | 8            |
| Web server     | nginx:alpine                | -            |
| PHP runtime    | php:8.4-fpm + FrankenPHP    | hot reload   |
| Containeriz.   | Docker Compose              | -            |

## Porte e Servizi
- **nginx** → porta `8083` (frontend HTTP)
- **php-fpm** → porta `9000` (interno, non esposto)
- **MySQL** → porta `3307` (host) / `3306` (interno), database: `symfony_db`

## Architettura
```
[Browser]
    ↓ porta 8083
[nginx:alpine]  →  /var/www/html/public/index.php
    ↓ FastCGI porta 9000
[php:8.4-fpm]
    ↓ Doctrine / PDO
[MySQL 8 — symfony_db]

[php:8.4-fpm]  ←→  /var/run/docker.sock  (Docker Engine)
```
Il socket Docker è montato come volume nel container PHP per permettere a `DockerService` di interrogare il daemon Docker direttamente.

## Struttura del Repository
```
/
├── docker-compose.yml
├── docker/
│   ├── nginx/default.conf         # Config nginx → fastcgi pass a php:9000
│   └── php/Dockerfile             # php:8.4-fpm + estensioni + Composer
└── app/                           # Applicazione Symfony
    ├── src/
    │   ├── Controller/            # Controller Symfony (AbstractController)
    │   ├── Entity/                # Entità Doctrine (mapping via attributi PHP)
    │   ├── Repository/            # Repository Doctrine (estendono ServiceEntityRepository)
    │   ├── Service/               # Business logic e integrazione Docker socket
    │   └── DTO/                   # Data Transfer Object (readonly, constructor promotion)
    ├── templates/                 # Template Twig
    │   ├── base.html.twig         # Layout base con Bootstrap CDN e FrankenPHP hot reload
    │   └── {controller}/          # Una cartella per controller (es. home/, test/)
    ├── config/
    │   ├── packages/              # Config Doctrine, cache, routing, Twig, ecc.
    │   └── services.yaml          # Autowiring e autoconfigure abilitati globalmente
    ├── migrations/                # Migrazioni Doctrine (generate con make:migration)
    └── public/
        ├── index.php              # Front controller Symfony
        └── css/style.css          # CSS custom (integrativo a Bootstrap)
```
La struttura interna può evolvere. Fai sempre riferimento ai file presenti nel contesto della conversazione per conoscere la struttura aggiornata.

## Modello dei Dati (Entità)
```
Server          → serverName, ipAddress, totalRam (int), totalCpu (int)
Project         → projectName, path | OneToMany → Container
ContainerType   → label
Container       → dockerId, name, state, duration, health
                  ManyToOne → Project
                  ManyToOne → ContainerType
```
Il naming DB segue la strategia `underscore` di Doctrine (es. `projectName` → `project_name`).

## Convenzioni Backend (Symfony)

### Controller
- Estendono `AbstractController`
- Route definite tramite attributo `#[Route('/path', name: 'app_nome')]`
- Dichiarati `final`
- I service vengono iniettati come parametri del metodo (method injection), non nel costruttore

### Entity
- Mapping Doctrine tramite attributi PHP `#[ORM\...]` — mai XML o YAML
- Getter/Setter con return type `static` per i setter (fluent interface)
- Nessuna logica di business nelle Entity

### Service
- Contengono la business logic e l'integrazione con sistemi esterni (Docker socket)
- Registrati automaticamente tramite autowiring
- `DockerService::getDockerStats()` interroga il Docker daemon via Unix socket cURL su `/var/run/docker.sock`

### DTO
- Constructor property promotion con proprietà `readonly`
- Usati per trasportare dati tra Service e Controller senza esporre le Entity

### Templates Twig
- Ogni controller ha la propria sottocartella in `templates/`
- Il layout base è `base.html.twig` con i blocchi: `title`, `stylesheets`, `javascripts`, `body`
- Bootstrap 5.3.3 caricato da CDN nel base template

## Comandi Utili
```bash
# Avviare l'ambiente
docker compose up -d

# Creare una migration dopo aver modificato le Entity
docker compose exec php bin/console make:migration

# Eseguire le migration
docker compose exec php bin/console doctrine:migrations:migrate

# Creare una nuova Entity
docker compose exec php bin/console make:entity

# Creare un nuovo Controller
docker compose exec php bin/console make:controller
```