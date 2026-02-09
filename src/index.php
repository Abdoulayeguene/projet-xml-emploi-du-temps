<?php
/**
 * index.php
 * Point d'entrée : charge le XML, valide avec XSD, affiche l'emploi du temps en HTML.
 * Toute la logique métier est dans functions.php ; ce fichier orchestre et génère le HTML.
 */
require_once __DIR__ . '/functions.php';

// Chargement du document XML
$dom = chargerXml();
if ($dom === false) {
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Erreur</title></head><body>';
    echo '<p>Impossible de charger le fichier XML.</p></body></html>';
    exit;
}

// Validation par le schéma XSD (règle pédagogique : validation stricte côté PHP)
if (!validerAvecXsd($dom)) {
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Erreur de validation</title></head><body>';
    echo '<p>Le document XML n\'est pas valide selon le schéma XSD.</p></body></html>';
    exit;
}

// Récupération des cours et application des filtres optionnels (GET)
$cours = getCours($dom);
$parJour = isset($_GET['jour']) && $_GET['jour'] !== '' ? trim($_GET['jour']) : null;
$parMatiere = isset($_GET['matiere']) && $_GET['matiere'] !== '' ? trim($_GET['matiere']) : null;
if ($parJour !== null || $parMatiere !== null) {
    $cours = filtrerCours($cours, $parJour, $parMatiere);
}
$cours = ordonnerCours($cours);
$coursParJour = grouperCoursParJour($cours);

// Génération de la page HTML
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du temps</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header class="header">
        <h1>Emploi du temps</h1>
        <p class="sous-titre">Données chargées depuis <code>emploi_du_temps.xml</code>, validées par XSD.</p>
    </header>

    <main class="main">
        <?php if (empty($cours)) : ?>
            <p class="message">Aucun cours à afficher.</p>
        <?php else : ?>
            <?php foreach ($coursParJour as $jour => $coursDuJour) : ?>
                <?php if (empty($coursDuJour)) {
                    continue;
                } ?>
            <section class="jour" id="jour-<?php echo htmlspecialchars($jour); ?>">
                <h2><?php echo htmlspecialchars($jour); ?></h2>
                <table class="table-cours">
                    <thead>
                        <tr>
                            <th>Horaire</th>
                            <th>Matière</th>
                            <th>Type</th>
                            <th>Enseignant</th>
                            <th>Salle</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($coursDuJour as $c) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars(formatHeure($c['heure_debut']) . ' – ' . formatHeure($c['heure_fin'])); ?></td>
                            <td><?php echo htmlspecialchars($c['matiere']); ?></td>
                            <td><?php echo htmlspecialchars($c['type']); ?></td>
                            <td><?php echo htmlspecialchars($c['enseignant_prenom'] . ' ' . $c['enseignant_nom']); ?></td>
                            <td><?php echo htmlspecialchars($c['salle']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <p>Projet XML – Gestion de l'emploi du temps. Validation DTD + XSD, affichage PHP.</p>
    </footer>
</body>
</html>
