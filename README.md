# Gestion de l'Emploi du Temps – Projet XML

Étude comparative de XML, JSON et YAML et réalisation d'une application en PHP.

---

## Présentation du projet

Ce projet a été réalisé dans un cadre académique et vise à :

- étudier et comparer les formats de données XML, JSON et YAML
- comprendre leurs structures, usages et différences
- appliquer le format XML dans le développement d'une application web simple en PHP

L'application développée permet d'afficher un emploi du temps à partir d'un fichier XML.

---

## Objectifs pédagogiques

- Comprendre la structure et les règles de XML
- Comparer XML avec JSON et YAML
- Manipuler un fichier XML en PHP
- Mettre en œuvre une application web simple
- Produire un rapport et une présentation structurés

---

## Technologies utilisées

- PHP
- XML
- HTML / CSS

---

## Structure du projet

L'architecture sépare les données, le code, les ressources statiques et la documentation pour garder le projet lisible et maintenable.

```
projet-xml-emploi-du-temps/
│
├── data/
│   └── emploi_du_temps.xml      # Données de l'emploi du temps (source unique)
│
├── src/
│   ├── index.php                # Point d'entrée : affichage de l'emploi du temps
│   └── functions.php            # Fonctions de lecture et traitement du XML
│
├── assets/
│   └── style.css                # Mise en forme de l'interface
│
├── docs/
│   ├── rapport.pdf              # Rapport du projet
│   └── presentation.pptx       # Présentation orale
│
├── README.md
└── .gitignore
```

**Rôle des dossiers :**

- **data/** — Contient uniquement les fichiers XML. Les données sont isolées du code pour faciliter la modification du contenu sans toucher au PHP.
- **src/** — Contient toute la logique applicative (point d'entrée et fonctions). `index.php` charge le XML via les fonctions définies dans `functions.php`.
- **assets/** — Ressources statiques (CSS, et éventuellement images ou scripts). Séparation claire entre présentation et logique.
- **docs/** — Documentation livrable du projet : rapport écrit et support de présentation.

---

## Exécution du projet

1. Cloner le dépôt.
2. Placer le projet dans un serveur local (XAMPP, WAMP ou serveur PHP intégré : `php -S localhost:8000`).
3. Ouvrir `src/index.php` dans le navigateur.

---

## Membres du groupe

- Abdoulaye Guene
- Aissatou Billy Sall

---

## Année académique

2024 – 2025
