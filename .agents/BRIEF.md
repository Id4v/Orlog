# Orlog - BRIEF Produit v1.1

## 1) Vue d'ensemble
**Orlog** est une application web de gestion de projet orientée **tracking de temps** et **pré-facturation** pour freelances et petites équipes.

**Objectif produit (MVP)** : transformer des entrées de temps en montants facturables fiables, avec historique et export.

---

## 2) Stack et contraintes techniques (réelles)
Ce brief s'applique à ce dépôt :
- **Backend** : Symfony 8, PHP 8.4+
- **ORM** : Doctrine ORM + Migrations
- **Frontend** : Twig + Vite 6 + Tailwind CSS v4
- **Base** : PostgreSQL en production (SQLite possible en local)

Hors scope technique pour le MVP actuel : Next.js, Prisma, Supabase, JWT custom, app mobile.

---

## 3) Périmètre MVP (in scope)
1. Authentification utilisateur (email/password) et isolation stricte des données par utilisateur.
2. CRUD **Clients**.
3. CRUD **Projets** (rattachés à un client).
4. CRUD **Types d'activité** (avec tarif par défaut).
5. CRUD **Entrées de temps** (saisie manuelle uniquement).
6. Calcul automatique du montant facturable.
7. Filtres et listing des entrées de temps.
8. Export CSV des entrées filtrées.

### Hors scope MVP (backlog)
- Timer start/stop en temps réel
- Facture PDF complète
- Multi-utilisateurs avec rôles avancés
- Intégrations externes

---

## 4) Modèle de données MVP
Entités minimales :
- `User`
- `Client` (belongs to `User`)
- `Project` (belongs to `Client` + `User`)
- `ActivityType` (belongs to `User`)
- `TimeEntry` (belongs to `User`, `Project`, optional `Task` plus tard)

Statuts `TimeEntry` : `draft`, `validated`, `exported`, `invoiced`.

Champs essentiels `TimeEntry` :
- date
- durée en minutes (`durationMinutes`, entier > 0)
- description (optionnelle)
- `isBillable` (bool)
- `hourlyRate` (snapshot à la création)
- montant calculé = `durationMinutes / 60 * hourlyRate`

---

## 5) Règles métier (décisionnelles)
### Hiérarchie de tarif (priorité)
1. Tarif saisi sur l'entrée de temps (si override manuel)
2. Tarif du projet (si défini)
3. Tarif du client (si défini)
4. Tarif du type d'activité (défaut)

### Règles de calcul
- Stocker `durationMinutes` (pas de flottants pour la durée).
- Affichage en heures décimales à 2 décimales.
- Montants arrondis à 2 décimales (arrondi commercial standard).
- Si `isBillable = false`, montant facturable = 0 dans les totaux de facturation.

### Règles de transition de statut
Transitions autorisées :
- `draft -> validated`
- `validated -> exported`
- `exported -> invoiced`
- Retour arrière autorisé seulement vers l'état précédent par un utilisateur propriétaire.

---

## 6) Workflows MVP
### A. Saisie manuelle de temps
1. Choisir date, projet, type d'activité
2. Saisir durée + description
3. Calcul et proposition du tarif selon la hiérarchie
4. Enregistrer en `draft`

### B. Validation des entrées
1. Filtrer les entrées `draft`
2. Vérifier durée/tarif/facturable
3. Passage en `validated`

### C. Export CSV
1. Filtrer par période/client/projet/statut
2. Export des colonnes métier principales
3. Marquage optionnel en `exported`

---

## 7) UI/UX (MVP)
- Interface responsive desktop/mobile.
- Tailwind v4 uniquement.
- Cohérence visuelle du thème Orlog (vert/bronze) sans bloquer la livraison fonctionnelle.
- Accessibilité minimale : labels explicites, navigation clavier, contrastes lisibles.

---

## 8) Sécurité et conformité
- Chaque requête doit être scoped par utilisateur connecté.
- Validation serveur systématique (Symfony Validator).
- CSRF sur formulaires.
- Rate limiting sur auth et endpoints sensibles.
- Pas de secrets en dépôt (`.env.local` uniquement).

---

## 9) Critères d'acceptation MVP
1. Un utilisateur crée clients, projets, types d'activité et entrées de temps sans erreur bloquante.
2. Les montants calculés correspondent aux règles de tarif et d'arrondi.
3. Les entrées non facturables sont exclues des totaux facturables.
4. Les filtres et l'export CSV fonctionnent sur des jeux de données réalistes (> 1 000 entrées).
5. Aucune fuite de données entre utilisateurs.

---

## 10) Roadmap post-MVP
- **Phase 2** : timer temps réel + tâches
- **Phase 3** : facturation PDF + suivi de statut facture
- **Phase 4** : dashboard analytics avancé

---

**Version** : 1.1
**Statut** : aligné avec le dépôt Symfony actuel
