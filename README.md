# Gestion de l'Emploi du Temps – Projet XML

Projet académique : étude comparative XML, JSON et YAML, et réalisation d'une application web de gestion d'emploi du temps en PHP, conforme au cadre du cours.

---

## 1. Objectifs pédagogiques

Ce projet vise à démontrer la maîtrise des points suivants :

- **XML** : stockage structuré des données, indépendant de toute logique applicative.
- **DTD (Document Type Definition)** : définition de la structure générale et des règles de composition du document XML.
- **XSD (XML Schema Definition)** : validation stricte des types de données, des contraintes et de la cohérence du document.
- **PHP** : lecture du document XML, validation au moyen du schéma XSD, et génération d'une page HTML pour l'affichage.

L'application produite affiche un emploi du temps à partir d'un fichier XML validé, sans recourir à XSLT.

---

## 2. Architecture logique du projet

L'architecture respecte une **séparation stricte des responsabilités** : données, définition de structure, validation, logique applicative, présentation.

### 2.1 Rôle de chaque composant

| Composant | Rôle | Fichier(s) concerné(s) |
|-----------|------|-------------------------|
| **XML** | Stocker uniquement les données de l'emploi du temps (cours, horaires, enseignants, salles, etc.). Aucune logique, aucun formatage. | `data/emploi_du_temps.xml` |
| **DTD** | Définir la structure générale du document : éléments autorisés, hiérarchie, cardinalités. Syntaxe déclarative, lisible. | `data/emploi_du_temps.dtd` |
| **XSD** | Assurer une validation stricte : types (chaînes, dates, entiers), contraintes (min/max, motifs), cohérence globale. Plus expressif que la DTD. | `data/emploi_du_temps.xsd` |
| **PHP** | Logique applicative : charger le XML, valider avec le schéma XSD, extraire les données, générer une page HTML complète. Pas de transformation XSLT. | `src/index.php`, `src/functions.php` |
| **CSS** | Mise en forme de la page HTML générée par PHP. Présentation uniquement. | `assets/style.css` |

### 2.2 Séparation des couches

- **Couche données** : `data/` — contenu XML et ses schémas (DTD, XSD). Modifiable sans toucher au code PHP.
- **Couche validation** : définie dans les schémas (DTD, XSD) ; exécutée par PHP au chargement (validation XSD).
- **Couche logique** : `src/` — lecture, validation, construction du modèle en mémoire, génération HTML.
- **Couche présentation** : HTML produit par PHP + `assets/style.css`. Aucune logique dans le CSS.

### 2.3 Choix technique : pas de XSLT

La transformation XML → HTML est réalisée **en PHP** (parcours du DOM ou SimpleXML, puis émission de HTML), et non via XSLT. Ce choix est assumé pour les raisons suivantes :

- Aligner l'implémentation sur le périmètre du cours (XML, DTD, XSD, PHP).
- Centraliser dans PHP toute la logique : validation puis affichage, ce qui simplifie le suivi et le débogage.
- Garder un flux unique (PHP lit, valide, affiche) plus lisible pour un projet pédagogique de taille limitée.

XSLT reste une solution valide pour d'autres contextes (transformation pure, réutilisation de feuilles de style) ; il n'est simplement pas utilisé ici.

---

## 3. Structure physique du projet

```
projet-xml-emploi-du-temps/
│
├── data/
│   ├── emploi_du_temps.xml     # Données (contenu de l'emploi du temps)
│   ├── emploi_du_temps.dtd     # Structure générale du document
│   └── emploi_du_temps.xsd     # Validation stricte (types, contraintes)
│
├── src/
│   ├── index.php               # Point d'entrée : lecture, validation XSD, affichage HTML
│   └── functions.php           # Fonctions de chargement XML, validation, construction HTML
│
├── assets/
│   └── style.css               # Mise en forme de la page
│
├── docs/
│   ├── rapport.pdf              # Rapport écrit du projet
│   └── presentation.pptx       # Présentation orale
│
├── README.md
└── .gitignore
```

- **data/** : données et schémas uniquement. Le XML référence le DTD et/ou le XSD pour validation.
- **src/** : tout le code PHP. `index.php` orchestre ; `functions.php` factorise lecture XML, validation et génération HTML.
- **assets/** : feuilles de style. Aucune logique.
- **docs/** : livrables académiques (rapport, support de soutenance).

---

## 4. Parcours de réalisation recommandé

Cette architecture peut être suivie pas à pas pour le **développement**, le **rapport écrit** et la **présentation** :

1. **Données** : concevoir et rédiger `emploi_du_temps.xml` (exemple d’emploi du temps cohérent).
2. **Structure** : écrire la DTD (`emploi_du_temps.dtd`) pour refléter la structure du document.
3. **Validation** : écrire le schéma XSD (`emploi_du_temps.xsd`) pour types et contraintes ; lier le XML au XSD.
4. **Logique** : implémenter dans `functions.php` le chargement du XML et la validation XSD ; dans `index.php`, l’orchestration et la génération HTML.
5. **Présentation** : rédiger `style.css` pour la page produite.
6. **Documentation** : rédiger le rapport et la présentation en s’appuyant sur cette même décomposition (données, DTD, XSD, PHP, présentation).

---

## 5. Exécution du projet

1. Cloner le dépôt.
2. Placer le projet sur un serveur web local (XAMPP, WAMP) ou lancer le serveur PHP intégré à la racine du projet : `php -S localhost:8000`.
3. Ouvrir dans le navigateur l’URL correspondant à `src/index.php` (par exemple `http://localhost:8000/src/index.php`).

---

## 6. Équipe et contexte

**Membres du groupe :**

- Abdoulaye Guene  
- Aissatou Billy Sall  

**Année académique :** 2024 – 2025  

---

*Ce README décrit l’architecture et les choix techniques du projet. Il sert de référence pour le développement, le rapport et la soutenance.*
