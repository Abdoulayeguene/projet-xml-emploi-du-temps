<?php
/**
 * functions.php
 * Fonctions de chargement, validation et exploitation du fichier XML emploi_du_temps.
 * Respecte la séparation des responsabilités : logique uniquement, pas de génération HTML.
 */

/* --------------------------------------------------------------------------
 * Chemins par défaut (relatifs au répertoire du script, donc src/)
 * -------------------------------------------------------------------------- */
define('CHEMIN_XML', __DIR__ . '/../data/emploi_du_temps.xml');
define('CHEMIN_XSD', __DIR__ . '/../data/emploi_du_temps.xsd');

/**
 * Charge le document XML dans un DOMDocument.
 *
 * @param string|null $fichierXml Chemin vers le fichier XML (null = CHEMIN_XML)
 * @return DOMDocument|false Instance de DOMDocument ou false en cas d'échec
 */
function chargerXml($fichierXml = null)
{
    $fichier = $fichierXml ?? CHEMIN_XML;
    if (!is_readable($fichier)) {
        return false;
    }
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    if (@$dom->load($fichier, LIBXML_NOERROR) === false) {
        return false;
    }
    return $dom;
}

/**
 * Valide le document XML par rapport au schéma XSD.
 *
 * @param DOMDocument $dom Document déjà chargé
 * @param string|null $fichierXsd Chemin vers le fichier XSD (null = CHEMIN_XSD)
 * @return bool True si le document est valide, false sinon
 */
function validerAvecXsd(DOMDocument $dom, $fichierXsd = null)
{
    $fichier = $fichierXsd ?? CHEMIN_XSD;
    if (!is_readable($fichier)) {
        return false;
    }
    return @$dom->schemaValidate($fichier);
}

/**
 * Extrait tous les cours du DOM sous forme de tableau associatif.
 * Chaque cours contient : id, matiere, enseignant_nom, enseignant_prenom,
 * jour, heure_debut, heure_fin, salle, type.
 *
 * @param DOMDocument $dom Document XML chargé
 * @return array Liste de tableaux associatifs (un par cours)
 */
function getCours(DOMDocument $dom)
{
    $cours = [];
    $listeCours = $dom->getElementsByTagName('cours');

    foreach ($listeCours as $c) {
        $enseignant = $c->getElementsByTagName('enseignant')->item(0);
        $horaire = $c->getElementsByTagName('horaire')->item(0);

        $cours[] = [
            'id'                  => $c->getAttribute('id'),
            'matiere'             => getTexteEnfant($c, 'matiere'),
            'enseignant_nom'      => $enseignant ? getTexteEnfant($enseignant, 'nom') : '',
            'enseignant_prenom'   => $enseignant ? getTexteEnfant($enseignant, 'prenom') : '',
            'jour'                => $horaire ? getTexteEnfant($horaire, 'jour') : '',
            'heure_debut'         => $horaire ? getTexteEnfant($horaire, 'heure_debut') : '',
            'heure_fin'           => $horaire ? getTexteEnfant($horaire, 'heure_fin') : '',
            'salle'               => getTexteEnfant($c, 'salle'),
            'type'                => getTexteEnfant($c, 'type'),
        ];
    }

    return $cours;
}

/**
 * Retourne le contenu textuel du premier sous-élément $tagName de $element.
 *
 * @param DOMElement $element Élément parent
 * @param string     $tagName Nom du sous-élément
 * @return string Contenu texte ou chaîne vide
 */
function getTexteEnfant(DOMElement $element, $tagName)
{
    $nodes = $element->getElementsByTagName($tagName);
    if ($nodes->length === 0) {
        return '';
    }
    return trim($nodes->item(0)->textContent);
}

/**
 * Filtre la liste des cours par jour et/ou par matière.
 *
 * @param array      $cours       Liste des cours (retour de getCours)
 * @param string|null $parJour     Filtrer par jour (ex. "Lundi"), null = pas de filtre
 * @param string|null $parMatiere  Filtrer par matière (ex. "Réseaux"), null = pas de filtre
 * @return array Liste des cours filtrés
 */
function filtrerCours(array $cours, $parJour = null, $parMatiere = null)
{
    return array_filter($cours, function ($c) use ($parJour, $parMatiere) {
        if ($parJour !== null && $c['jour'] !== $parJour) {
            return false;
        }
        if ($parMatiere !== null && $c['matiere'] !== $parMatiere) {
            return false;
        }
        return true;
    });
}

/**
 * Ordonne les cours par jour puis par heure de début.
 * Jours dans l'ordre : Lundi, Mardi, Mercredi, Jeudi, Vendredi.
 *
 * @param array $cours Liste des cours
 * @return array Liste des cours triée
 */
function ordonnerCours(array $cours)
{
    $ordreJours = ['Lundi' => 1, 'Mardi' => 2, 'Mercredi' => 3, 'Jeudi' => 4, 'Vendredi' => 5];
    usort($cours, function ($a, $b) use ($ordreJours) {
        $ja = $ordreJours[$a['jour']] ?? 99;
        $jb = $ordreJours[$b['jour']] ?? 99;
        if ($ja !== $jb) {
            return $ja - $jb;
        }
        return strcmp($a['heure_debut'], $b['heure_debut']);
    });
    return $cours;
}

/**
 * Regroupe les cours par jour (clé = nom du jour, valeur = liste des cours).
 * Les jours sont dans l'ordre : Lundi, Mardi, Mercredi, Jeudi, Vendredi.
 *
 * @param array $cours Liste des cours (idéalement déjà ordonnée avec ordonnerCours)
 * @return array Tableau associatif [ 'Lundi' => [...], 'Mardi' => [...], ... ]
 */
function grouperCoursParJour(array $cours)
{
    $ordreJours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
    $parJour = array_fill_keys($ordreJours, []);
    foreach ($cours as $c) {
        $parJour[$c['jour']][] = $c;
    }
    return $parJour;
}

/**
 * Formate une heure HH:MM:SS en HH:MM pour l'affichage.
 *
 * @param string $heure Heure au format HH:MM:SS ou HH:MM
 * @return string
 */
function formatHeure($heure)
{
    $parties = explode(':', $heure);
    return ($parties[0] ?? '') . ':' . ($parties[1] ?? '00');
}
