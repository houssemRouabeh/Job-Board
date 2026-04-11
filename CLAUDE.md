# CLAUDE.md — JavaScript / Node.js

> Ce fichier est lu automatiquement par Claude Code à chaque session.
> Il définit les règles, le contexte et les limites pour ce projet.
> À personnaliser selon ton projet avant de commiter.

---



## 📁 Description du projet

<!-- À remplir obligatoirement -->
**Nom du projet** : [NOM_DU_PROJET]
**Description** : [Description courte de ce que fait ce projet en 1-2 phrases]
**Type** : [API REST / Frontend React / CLI tool / Librairie NPM / Application fullstack / autre]
**Statut** : [En développement actif / Maintenance / Stable / Déprécié]
**Audience** : [Usage interne / Open source / Clients / etc.]

---

## 🛠️ Stack technique

**Runtime** : Node.js [VERSION — ex: 20.x LTS]
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

```
[À adapter selon ton projet]
.
├── src/
│   ├── controllers/     # Handlers HTTP (si API)
│   ├── services/        # Logique métier
│   ├── models/          # Modèles de données
│   ├── middleware/      # Middlewares Express/Fastify
│   ├── utils/           # Fonctions utilitaires
│   └── index.js         # Point d'entrée
├── tests/               # Tests unitaires et intégration
├── docs/                # Documentation
├── scripts/             # Scripts utilitaires
├── .env.example         # Variables d'environnement exemple
└── package.json
```

**Conventions de nommage des fichiers** :
- Fichiers : kebab-case (`user-service.js`, `auth-middleware.js`)
- Classes : PascalCase (`UserService`, `AuthMiddleware`)
- Fonctions/variables : camelCase (`getUserById`, `isAuthenticated`)
- Constantes : UPPER_SNAKE_CASE (`MAX_RETRY_COUNT`, `API_TIMEOUT`)
- Tests : même nom que le fichier testé + `.test.js` ou `.spec.js`

---

## 📏 Conventions de code

### Style général
- **Indentation** : 2 espaces (jamais de tabulations)
- **Quotes** : guillemets simples `'` pour les strings (sauf template literals)
- **Semicolons** : [Oui / Non — à choisir et respecter]
- **Longueur de ligne** : 100 caractères maximum
- **Trailing commas** : toujours en fin de liste/objet multiligne

### Fonctions
- Préférer les **arrow functions** pour les callbacks et les fonctions courtes
- Préférer les **fonctions nommées** pour les méthodes de service et les exports
- **Async/await** obligatoire — interdire `.then().catch()` sauf cas exceptionnel documenté
- Toujours gérer les erreurs avec try/catch dans les fonctions async
- Limiter la taille des fonctions à **50 lignes** — si plus, découper

```javascript
// ✅ Bien
const getUserById = async (id) => {
  try {
    const user = await db.users.findOne({ where: { id } });
    if (!user) throw new NotFoundError(`User ${id} not found`);
    return user;
  } catch (error) {
    logger.error('getUserById failed', { id, error });
    throw error;
  }
};

// ❌ Interdit
function getUser(id) {
  return db.users.findOne({ where: { id } }).then(user => {
    return user;
  }).catch(err => console.log(err));
}
```

### Imports / Exports
- Imports groupés dans cet ordre : Node built-ins → dépendances externes → modules internes
- Séparer chaque groupe par une ligne vide
- Préférer les **named exports** aux default exports (sauf pour les composants React)

```javascript
// ✅ Ordre correct
import fs from 'fs';
import path from 'path';

import express from 'express';
import { z } from 'zod';

import { UserService } from '../services/user-service.js';
import { logger } from '../utils/logger.js';
```

### Gestion des erreurs
- Créer des **classes d'erreur custom** dans `src/errors/` pour les erreurs métier
- Toujours logger les erreurs avec contexte avant de les propager
- Ne jamais swallower une erreur silencieusement (`catch (e) {}` interdit)
- Les erreurs HTTP ont un status code explicite

### Variables d'environnement
- Toutes les configs via variables d'environnement — jamais hardcodées
- Centraliser dans `src/config/index.js` avec validation au démarrage
- Documenter chaque variable dans `.env.example`
- Préfixer les variables liées à un service : `DB_HOST`, `REDIS_URL`, `SMTP_HOST`

---

## 🧪 Tests

**Couverture minimale cible** : 70% sur `src/`

**Structure d'un test** :
```javascript
describe('UserService', () => {
  describe('getUserById', () => {
    it('should return user when found', async () => {
      // Arrange
      const mockUser = { id: 1, name: 'Alice' };
      db.users.findOne.mockResolvedValue(mockUser);

      // Act
      const result = await userService.getUserById(1);

      // Assert
      expect(result).toEqual(mockUser);
    });

    it('should throw NotFoundError when user does not exist', async () => {
      db.users.findOne.mockResolvedValue(null);
      await expect(userService.getUserById(99)).rejects.toThrow(NotFoundError);
    });
  });
});
```

**Règles** :
- Un `describe` par module/classe
- Un `it` par comportement à tester (pas par fonction)
- Libellés en anglais, phrase complète, verbe au présent
- Utiliser des mocks pour les dépendances externes (DB, APIs, filesystem)
- Les tests d'intégration dans un dossier séparé `tests/integration/`

**Commandes** :
```bash
npm test              # Tous les tests
npm run test:watch    # Mode watch
npm run test:coverage # Avec couverture
```

---

## 📝 Documentation

### JSDoc obligatoire pour :
- Toutes les fonctions exportées depuis `src/`
- Toutes les classes publiques
- Les types complexes

```javascript
/**
 * Récupère un utilisateur par son identifiant.
 *
 * @param {number} id - L'identifiant unique de l'utilisateur
 * @param {Object} [options] - Options de récupération
 * @param {boolean} [options.includeDeleted=false] - Inclure les utilisateurs supprimés
 * @returns {Promise<User>} L'utilisateur trouvé
 * @throws {NotFoundError} Si l'utilisateur n'existe pas
 * @throws {DatabaseError} En cas d'erreur de base de données
 */
const getUserById = async (id, options = {}) => { ... };
```

### README.md doit toujours contenir :
- Description du projet
- Prérequis d'installation
- Instructions de démarrage local
- Variables d'environnement requises
- Endpoints principaux (si API)
- Comment lancer les tests

---

## 🔒 Sécurité

**Règles absolues — Claude ne doit JAMAIS** :
- Commiter des secrets, clés API, mots de passe ou tokens dans le code
- Désactiver des règles ESLint liées à la sécurité
- Utiliser `eval()`, `new Function()` ou `vm.runInNewContext()`
- Construire des requêtes SQL par concaténation de strings
- Logger des données sensibles (mots de passe, tokens, PII)
- Utiliser `Math.random()` pour générer des tokens de sécurité

**Validation des entrées** :
- Toujours valider et sanitiser les entrées utilisateur
- Utiliser Zod, Joi ou Yup pour la validation de schéma
- Ne jamais faire confiance aux données venant du client

**Dépendances** :
- Vérifier `npm audit` avant d'ajouter une nouvelle dépendance
- Éviter les dépendances avec des vulnérabilités connues
- Pincer les versions dans `package.json` (pas de `^` pour les dépendances critiques)

---

## 🌿 Git et workflow

### Conventions de commits (Conventional Commits)
```
<type>(<scope>): <description courte en français ou anglais>

[corps optionnel : explication du pourquoi, pas du comment]

[footer optionnel : BREAKING CHANGE, closes #issue]
```

**Types autorisés** :
| Type | Usage |
|------|-------|
| `feat` | Nouvelle fonctionnalité |
| `fix` | Correction de bug |
| `docs` | Documentation uniquement |
| `style` | Formatage, espaces (pas de changement logique) |
| `refactor` | Refactorisation sans nouvelle feature ni bug fix |
| `perf` | Amélioration de performance |
| `test` | Ajout ou modification de tests |
| `chore` | Maintenance, dépendances, config |
| `ci` | Modifications CI/CD |
| `revert` | Annulation d'un commit précédent |

**Exemples** :
```
feat(auth): add JWT refresh token rotation
fix(api): handle null values in user profile endpoint
docs(readme): update environment variables section
chore(deps): update express to 4.19.2
refactor(service): extract email validation to utils
```

### Branches
- `main` / `master` : production — **protégée, jamais de commit direct**
- `develop` : intégration (si workflow gitflow)
- `feat/description-courte` : nouvelles fonctionnalités
- `fix/description-courte` : corrections de bugs
- `chore/description-courte` : maintenance
- `docs/description-courte` : documentation

### Pull Requests
- Toujours depuis une branche dédiée vers `main` ou `develop`
- Titre en Conventional Commits format
- Description avec : contexte, changements, tests effectués
- Au moins 1 reviewer si en équipe

---

## 🤖 Instructions spécifiques pour les tâches automatiques Claude

### Ce que Claude PEUT faire automatiquement
- Créer des Issues GitHub avec rapport d'analyse
- Ouvrir des Pull Requests sur des branches `chore/*` ou `docs/*`
- Modifier les fichiers `.md` (README, docs/)
- Ajouter des commentaires JSDoc aux fonctions sans documentation
- Corriger des typos dans les commentaires et strings non-critiques
- Mettre à jour les dépendances en version patch/minor avec tests verts
- Supprimer les imports inutilisés détectés par ESLint

### Ce que Claude NE DOIT PAS faire sans validation humaine
- Modifier la logique métier dans `src/services/`
- Changer les schémas de base de données
- Modifier les fichiers de configuration (`src/config/`, `.env*`)
- Merger une Pull Request
- Supprimer des fichiers (sauf temporaires évidents)
- Modifier `package.json` pour les dépendances majeures
- Toucher aux fichiers de tests existants (sauf ajout de nouveaux tests)
- Faire des commits directs sur `main` ou `master`

### Format des Issues créées automatiquement
Les Issues automatiques doivent :
- Avoir un titre clair avec un emoji de priorité (🔴 haute, 🟡 moyenne, 🟢 basse)
- Mentionner les fichiers et numéros de ligne concernés
- Proposer une solution ou une direction
- Être taguées avec les labels : `automated`, `claude-audit`

---

## 📦 Dépendances notables

<!-- À remplir selon ton projet -->
| Package | Version | Usage |
|---------|---------|-------|
| express | ^4.x | Serveur HTTP |
| [ajouter] | | |

**Dépendances à ne jamais mettre à jour automatiquement** (breaking changes connus) :
- [lister ici les packages sensibles]

---

## 🚀 Commandes importantes

```bash
# Démarrage
npm start              # Production
npm run dev            # Développement avec hot reload

# Tests
npm test               # Suite complète
npm run test:coverage  # Avec rapport de couverture

# Qualité
npm run lint           # Vérification ESLint
npm run lint:fix       # Correction automatique
npm run format         # Formatage Prettier

# Build (si applicable)
npm run build          # Build de production
npm run build:check    # Vérification du build sans écrire

# Base de données (si applicable)
npm run db:migrate     # Lancer les migrations
npm run db:seed        # Insérer les données de test
npm run db:reset       # Reset complet (DEV uniquement)
```

---

## ⚠️ Points d'attention spécifiques à ce projet

<!-- À remplir selon les particularités de ton projet -->
- [Ex: Ce projet utilise des workers Node.js — ne pas modifier worker.js sans tester la concurrence]
- [Ex: L'API est versionnée — tout nouveau endpoint doit aller dans /v2/]
- [Ex: Le cache Redis a un TTL de 5min — penser à invalider le cache après les mutations]

---

*Dernière mise à jour : [DATE]*
*Maintenu par : [TON NOM / ÉQUIPE]*