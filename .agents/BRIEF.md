# Orlog - Project Brief

## 📋 Vue d'ensemble

**Orlog** (du vieux norrois "ørlög" signifiant "destin tissé" ou "loi primordiale") est une application web de gestion de projet avec tracking de temps et facturation, destinée aux freelances et petites équipes.

**Vision :** Transformer le temps passé sur les projets en données exploitables pour la facturation, tout en offrant une expérience utilisateur inspirée de la mythologie nordique.

---

## 🎯 Fonctionnalités principales

### 1. Gestion de Clients
- Création, édition, suppression de clients
- Informations : nom, email, adresse, informations de facturation
- Association de tarifs horaires par défaut par client
- Statut actif/inactif

### 2. Gestion de Projets
- Création de projets liés à un client
- Informations : nom, description, dates début/fin, budget estimé
- Association de tarifs horaires par projet (override du tarif client)
- Statut : en cours, terminé, archivé, en attente

### 3. Gestion de Tâches
- Tâches associées à un projet
- Informations : titre, description, priorité, statut
- Assignation à des utilisateurs (si équipe)

### 4. Tracking de Temps
**Fonctionnalité centrale de l'application**

#### Création d'entrées de temps
- Timer en temps réel (start/stop) OU saisie manuelle
- Association obligatoire : projet + tâche (optionnelle)
- Sélection du type d'activité
- Description de l'activité
- Date et durée

#### Types d'activité
- Exemples : Développement, Design, Réunion, Support, Documentation, etc.
- Chaque type a un tarif horaire par défaut
- Tarif modifiable par :
    1. Type d'activité (défaut global)
    2. Client (override global)
    3. Projet (override client)
    4. Entrée de temps individuelle (override projet)

#### Statuts d'une entrée de temps
- **Brouillon** : non validée
- **Validée** : prête à être facturée
- **Exportée** : incluse dans un export
- **Facturée** : incluse dans une facture émise

#### Marqueurs
- **Facturable** / **Non facturable**
- Les entrées non facturables ne sont pas incluses dans les calculs de facturation

### 5. Facturation

#### Export de temps
- Filtrer les entrées par client, projet, période
- Sélectionner les entrées à inclure
- Marquer les entrées comme "exportées"
- Générer un récapitulatif :
    - Total heures par type d'activité
    - Total montant par type d'activité
    - Total général

#### Génération de factures
- Créer une facture à partir d'entrées de temps exportées
- Informations : numéro, date d'émission, date d'échéance
- Regroupement des lignes par projet et/ou type d'activité
- Calcul automatique des totaux
- Export PDF
- Marquer les entrées comme "facturées"

### 6. Rapports et Analyses
- Temps passé par client, projet, type d'activité
- Revenus générés (entrées facturées)
- Temps facturable vs non facturable
- Évolution dans le temps (graphiques)
- Export CSV/Excel

---

## 🎨 Identité visuelle - Thème Orlog

### Palette de couleurs

**Mode clair :**
- Primary (Vert Forêt Nordique) : `#3d6b3a`
- Accent (Bronze) : `#b87333`
- Background : `#fafaf9`
- Foreground : `#1f2937`

**Mode sombre :**
- Primary (Vert Clair) : `#4a7c59`
- Accent (Bronze Doré) : `#cd7f32`
- Background : `#1a1f2e`
- Foreground : `#f2f2f2`

### Typographie
- **Display/Headings :** Sora (géométrique, moderne, bold)
- **Body/Interface :** Manrope (arrondi, lisible)
- **Monospace/Data :** JetBrains Mono

### Design System
- Utilise shadcn/ui comme base de composants
- Tailwind CSS V4 pour le styling
- Thème personnalisé "Orlog" (voir fichier `assets/front.css`)

### Inspiration
- Mythologie nordique (runes, symboles)
- Minimalisme scandinave
- Artefacts en bronze ancien
- Forêts nordiques

### Symboles
- Logo : lettre "Ø" (scandinave) représentant un cycle
- Runes en arrière-plan (subtiles)
- Gradients vert → bronze pour les éléments importants

---

## 🏗️ Architecture technique

### Stack recommandé

**Frontend :**
- Framework : Next.js 14+ (App Router)
- UI : React 18+ avec TypeScript
- Styling : Tailwind CSS V4
- Composants : shadcn/ui
- État : Zustand ou Jotai (léger)
- Forms : React Hook Form + Zod
- Date/Time : date-fns ou Day.js
- Charts : Recharts

**Backend :**
- Option 1 (Recommandé) : Next.js API Routes + Prisma + PostgreSQL
- Option 2 : Supabase (Backend as a Service)
- Option 3 : Node.js + Express + Prisma + PostgreSQL

**Base de données :**
- PostgreSQL (recommandé pour relations complexes)
- Alternative : SQLite (développement) ou Supabase

**Authentification :**
- NextAuth.js (si Next.js)
- Supabase Auth (si Supabase)
- Support login email/password + OAuth (Google, GitHub)

**Génération PDF :**
- @react-pdf/renderer ou Puppeteer

**Déploiement :**
- Vercel (Next.js)
- Railway ou Render (PostgreSQL)

---

## 📊 Modèle de données

### User
```typescript
{
  id: string
  email: string
  name: string
  password_hash: string
  avatar_url?: string
  created_at: datetime
  updated_at: datetime
}
```

### Client
```typescript
{
  id: string
  user_id: string (FK)
  name: string
  email?: string
  phone?: string
  address?: string
  default_hourly_rate?: decimal
  is_active: boolean
  created_at: datetime
  updated_at: datetime
}
```

### Project
```typescript
{
  id: string
  client_id: string (FK)
  user_id: string (FK)
  name: string
  description?: text
  start_date?: date
  end_date?: date
  estimated_budget?: decimal
  hourly_rate?: decimal (override client rate)
  status: enum (active, completed, archived, on_hold)
  created_at: datetime
  updated_at: datetime
}
```

### Task
```typescript
{
  id: string
  project_id: string (FK)
  title: string
  description?: text
  priority: enum (low, medium, high)
  status: enum (todo, in_progress, completed)
  created_at: datetime
  updated_at: datetime
}
```

### ActivityType
```typescript
{
  id: string
  user_id: string (FK)
  name: string (ex: "Développement", "Design", "Réunion")
  default_hourly_rate: decimal
  color?: string (hex color for UI)
  created_at: datetime
  updated_at: datetime
}
```

### TimeEntry
```typescript
{
  id: string
  user_id: string (FK)
  project_id: string (FK)
  task_id?: string (FK, optional)
  activity_type_id: string (FK)
  description: text
  date: date
  start_time?: time (if using timer)
  end_time?: time (if using timer)
  duration_minutes: integer (calculated or manual)
  hourly_rate: decimal (snapshot at creation)
  is_billable: boolean
  status: enum (draft, validated, exported, invoiced)
  created_at: datetime
  updated_at: datetime
}
```

### Invoice
```typescript
{
  id: string
  user_id: string (FK)
  client_id: string (FK)
  invoice_number: string (unique)
  issue_date: date
  due_date: date
  subtotal: decimal
  tax_rate?: decimal
  tax_amount?: decimal
  total: decimal
  status: enum (draft, sent, paid, overdue, cancelled)
  notes?: text
  created_at: datetime
  updated_at: datetime
}
```

### InvoiceLineItem
```typescript
{
  id: string
  invoice_id: string (FK)
  time_entry_id?: string (FK, optional)
  description: text
  quantity: decimal (hours)
  unit_price: decimal (hourly rate)
  amount: decimal (quantity * unit_price)
  created_at: datetime
}
```

---

## 🔄 Workflows clés

### Workflow 1 : Tracking de temps avec timer
1. User clique "Démarrer timer"
2. Sélectionne projet (+ optionnel : tâche)
3. Sélectionne type d'activité
4. Timer compte en temps réel
5. User clique "Stop"
6. Popup : ajouter description, ajuster durée si besoin
7. Entrée sauvegardée avec statut "draft"

### Workflow 2 : Saisie manuelle de temps
1. User clique "Ajouter temps"
2. Formulaire : date, projet, tâche, type d'activité, durée, description
3. Calcul automatique du montant (durée × tarif horaire)
4. Sauvegarde avec statut "draft"

### Workflow 3 : Facturation
1. User va dans "Facturation"
2. Filtre les entrées de temps (client, projet, période)
3. Liste des entrées "validées" et "facturables" non encore facturées
4. Sélectionne les entrées à inclure
5. Clique "Créer facture"
6. Formulaire : numéro facture, dates, regroupement
7. Preview de la facture
8. Génération PDF
9. Entrées marquées comme "facturées"

### Workflow 4 : Gestion des tarifs
**Hiérarchie (du plus général au plus spécifique) :**
1. Type d'activité : tarif par défaut global
2. Client : peut override le tarif du type d'activité
3. Projet : peut override le tarif du client
4. Entrée de temps : peut être modifié manuellement (cas exceptionnel)

**Lors de la création d'une entrée de temps :**
- Le système applique automatiquement le tarif le plus spécifique disponible
- User peut voir et modifier le tarif avant validation

---

## 🚀 Phases de développement recommandées

### Phase 1 - MVP (Minimum Viable Product)
**Objectif :** Version fonctionnelle de base utilisable par un freelance solo

✅ Fonctionnalités :
- Authentification (email/password)
- CRUD Clients
- CRUD Projets
- CRUD Types d'activité
- Tracking de temps (saisie manuelle uniquement)
- Calcul automatique des montants
- Liste et filtres des entrées de temps
- Export CSV basique

🎨 UI/UX :
- Pages essentielles avec navigation
- Thème Orlog appliqué
- Composants shadcn/ui de base
- Responsive mobile-friendly

### Phase 2 - Amélioration UX
✅ Fonctionnalités :
- Timer en temps réel (start/stop)
- CRUD Tâches
- Dashboard avec statistiques basiques
- Filtres avancés
- Gestion des statuts d'entrées
- Marqueurs facturable/non facturable

🎨 UI/UX :
- Animations et transitions
- Loading states
- Empty states
- Error handling amélioré

### Phase 3 - Facturation
✅ Fonctionnalités :
- Génération de factures
- Export PDF
- Tracking du statut des factures
- Historique de facturation
- Rapports de revenus

### Phase 4 - Analytics & Rapports
✅ Fonctionnalités :
- Dashboard avancé avec graphiques
- Rapports détaillés (temps, revenus, clients, projets)
- Comparaisons période sur période
- Export Excel avancé

### Phase 5 - Fonctionnalités avancées (optionnelles)
- Support multi-utilisateurs (équipes)
- Permissions et rôles
- Intégrations (Google Calendar, Slack, etc.)
- API publique
- Mobile app (React Native)
- Récurrence de projets
- Templates de factures personnalisables
- Multi-devises

---

## 📝 Exemples d'utilisation

### Exemple 1 : Freelance développeur web
**Contexte :** Jean est développeur freelance, il travaille pour 3 clients en parallèle.

**Usage :**
1. Jean configure ses types d'activité :
    - Développement frontend : 80€/h
    - Développement backend : 90€/h
    - Réunion client : 70€/h
    - Support : 60€/h

2. Il crée un client "Startup XYZ" avec tarif spécial 75€/h pour tout

3. Pour le projet "Refonte site web", il crée des tâches :
    - Design system
    - Page d'accueil
    - Page produits
    - etc.

4. Chaque jour, il track son temps :
    - 9h-11h : Développement frontend sur "Page d'accueil" → 2h × 75€ = 150€
    - 14h-16h : Réunion avec client (non facturable) → marqué non facturable
    - 16h-18h : Développement backend sur API → 2h × 75€ = 150€

5. En fin de mois, il exporte les temps du client "Startup XYZ", génère une facture de 3 250€ pour 43h facturables.

### Exemple 2 : Agence de design (petite équipe)
**Contexte :** Une agence de 4 designers qui gèrent plusieurs clients.

**Usage :**
1. Chaque designer a son compte
2. Les clients et projets sont partagés
3. Chaque designer track son temps sur les projets communs
4. Les tarifs varient par type d'activité :
    - Direction artistique : 120€/h
    - Design UI/UX : 90€/h
    - Intégration : 70€/h

5. Certains clients ont des tarifs négociés différents
6. En fin de mois, le gérant génère les factures regroupées par client

---

## 🎨 Éléments d'interface clés

### 1. Dashboard
- Carte : Heures trackées aujourd'hui
- Carte : Heures trackées cette semaine
- Carte : Revenus du mois (facturés)
- Carte : Temps en attente de facturation
- Graphique : Évolution heures/semaine (4 dernières semaines)
- Graphique : Répartition par type d'activité (donut)
- Liste : Projets actifs avec progression

### 2. Timer (composant flottant ou page dédiée)
- Affichage temps écoulé (format HH:MM:SS)
- Sélecteur projet (dropdown avec recherche)
- Sélecteur tâche (optionnel, filtre par projet)
- Sélecteur type d'activité
- Bouton Start/Stop (gros, vert nordique)
- Champ description (optionnel, ajouté après stop)

### 3. Liste des entrées de temps
- Filtres : période (aujourd'hui, cette semaine, ce mois, custom), projet, client, type d'activité, statut, facturable
- Tri : date, durée, montant
- Colonnes : Date, Projet, Tâche, Type, Description, Durée, Tarif, Montant, Statut, Actions
- Actions : Éditer, Dupliquer, Supprimer, Changer statut
- Sélection multiple pour actions groupées

### 4. Formulaire d'entrée de temps
- Date (date picker)
- Projet (select avec recherche)
- Tâche (select, filtré par projet)
- Type d'activité (select)
- Durée (input heures + minutes OU début/fin)
- Tarif horaire (calculé auto, éditable)
- Description (textarea)
- Facturable (checkbox, par défaut oui)

### 5. Page facturation
- Étape 1 : Sélection des entrées
    - Filtres (client, projet, période)
    - Liste avec checkboxes
    - Totaux dynamiques en bas
- Étape 2 : Configuration facture
    - Numéro (auto-incrémenté, éditable)
    - Dates (émission, échéance)
    - Regroupement (par projet, par type d'activité, ou détaillé)
    - Notes additionnelles
- Étape 3 : Preview
    - Aperçu PDF
    - Possibilité de revenir en arrière
- Étape 4 : Confirmation
    - Génération PDF
    - Download
    - Marquer comme "facturée"

---

## 🔐 Considérations de sécurité

- Authentification sécurisée (hash bcrypt, JWT tokens)
- Autorisation : users ne peuvent voir que leurs propres données
- Validation côté serveur de toutes les entrées
- Protection CSRF
- Rate limiting sur les endpoints sensibles
- HTTPS obligatoire en production
- Backup automatique de la base de données

---

## 📱 Responsive Design

L'application doit être entièrement utilisable sur :
- Desktop (1920px+) : Vue complète
- Laptop (1366px-1920px) : Vue standard
- Tablet (768px-1366px) : Navigation adaptée, sidebar collapsible
- Mobile (320px-768px) : Navigation bottom bar ou hamburger, formulaires simplifiés, timer en fullscreen

---

## 🎯 Objectifs de performance

- Chargement initial < 2s
- Interactions < 100ms
- Recherche/filtres < 300ms
- Génération PDF < 3s
- Support de 10 000+ entrées de temps sans ralentissement

---

## 📚 Ressources fournies

- `orlog-theme.css` : Fichier de thème Tailwind V4 complet
- `orlog-moodboard.html` : Planche de tendance visuelle
- `orlog-login.html` : Exemple de page login/signup
- Documentation shadcn/ui : https://ui.shadcn.com
- Palette Orlog : Vert nordique (#3d6b3a) + Bronze (#b87333)

---

## 💡 Principes de développement

1. **Mobile-first** : Commencer par le design mobile
2. **Composants réutilisables** : Maximiser la réutilisation
3. **Type-safety** : TypeScript strict mode
4. **Tests** : Au minimum tests unitaires sur la logique métier
5. **Documentation** : Commenter le code complexe
6. **Git workflow** : Branches feature, PR reviews
7. **Performance** : Lazy loading, optimisation images, memoization
8. **Accessibilité** : Support clavier, ARIA labels, contraste suffisant

---

Respecte les convention TailwindCSS V4. Compatibilité avec ShadCN.


---

**Version du brief :** 1.0
**Dernière mise à jour :** Février 2026
**Contact projet :** [Ton email/GitHub]
