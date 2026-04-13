# CLAUDE.md — JavaScript / Node.js

> Ce fichier est lu automatiquement par Claude Code à chaque session.
> Il définit les règles, le contexte et les limites pour ce projet.
> À personnaliser selon ton projet avant de commiter.

---

## 📁 Description du projet

**Nom du projet** : [NOM_DU_PROJET]
**Description** : [Description courte de ce que fait ce projet en 1-2 phrases]
**Type** : [API REST / Frontend React / CLI tool / Librairie NPM / Application fullstack / autre]
**Statut** : [En développement actif / Maintenance / Stable / Déprécié]
**Audience** : [Usage interne / Open source / Clients / etc.]

---

## 🛠️ Stack technique

**Runtime** : Node.js [VERSION]
**Package manager** : [npm / yarn / pnpm]
**Framework principal** : [Express / Fastify / Next.js / NestJS / Koa / aucun]
**Base de données** : [PostgreSQL / MySQL / MongoDB / Redis / SQLite / aucune]
**ORM / Query builder** : [Prisma / Sequelize / TypeORM / Knex / aucun]
**Tests** : [Jest / Vitest / Mocha / aucun]
**Linter** : [ESLint / Biome / Standard]
**Formatter** : [Prettier / Biome]
**TypeScript** : [Oui / Non]
**Bundler** : [Webpack / Vite / esbuild / aucun]

---

## 📂 Structure du projet

**Conventions de nommage** :
- Fichiers : kebab-case
- Classes : PascalCase
- Fonctions/variables : camelCase
- Constantes : UPPER_SNAKE_CASE
- Tests : meme nom + .test.js ou .spec.js

---

## 📏 Conventions de code

- Indentation : 2 espaces
- Quotes : guillemets simples
- Longueur de ligne : 100 caractères maximum
- Async/await obligatoire, pas de .then().catch()
- Toujours try/catch dans les fonctions async
- Fonctions limitées à 50 lignes

---

## 🧪 Tests

Couverture minimale cible : 70% sur src/

Commandes :
- npm test
- npm run test:watch
- npm run test:coverage

---

## 🔒 Sécurité

Claude ne doit JAMAIS :
- Commiter des secrets, clés API, mots de passe ou tokens
- Utiliser eval() ou new Function()
- Construire des requêtes SQL par concaténation
- Logger des données sensibles

---

## 🌿 Git et workflow

Conventional Commits : feat / fix / docs / style / refactor / perf / test / chore / ci

Branches :
- main/master : protégée, jamais de commit direct
- feat/description : nouvelles fonctionnalités
- fix/description : corrections
- chore/description : maintenance
- docs/description : documentation

---

## 🤖 Instructions pour les tâches automatiques Claude

Claude PEUT faire automatiquement :
- Créer des Issues GitHub avec rapport d analyse
- Ouvrir des Pull Requests sur des branches chore/* ou docs/*
- Modifier les fichiers .md
- Ajouter des commentaires JSDoc aux fonctions sans documentation
- Corriger des typos dans les commentaires
- Mettre à jour les dépendances patch/minor

Claude NE DOIT PAS sans validation humaine :
- Modifier la logique métier dans src/services/
- Changer les schémas de base de données
- Modifier les fichiers de configuration
- Merger une Pull Request
- Faire des commits directs sur main ou master

---

## 🚀 Commandes importantes

- npm start
- npm run dev
- npm test
- npm run lint
- npm run build

---

*Maintenu par : Houssem Rouabeh*
