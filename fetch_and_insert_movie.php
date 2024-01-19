<?php
// Include the necessary functions
require_once 'functions.php';

// Function to fetch movie details and insert them into the database
function fetchAndInsertMovieDetails($conn, $link) {
    $page = file_get_contents($link);

    $filmDetails = explode('filmDetails = {', $page);
    $filmDetails = $filmDetails[1];

    $filmDetails = explode('var', $filmDetails);
    $filmDetails = $filmDetails[0];

    $filmDetails = explode('"', $filmDetails);

    $titlu = trim($filmDetails[7]);
    $regizor = trim($filmDetails[45]);

    $durata = explode("DURATĂ", $page);
    $durata = $durata[1];

    $durata = explode("min", $durata);
    $durata = trim($durata[0]);

    $clasificare = explode("CLASIFICARE", $page);
    $clasificare = $clasificare[1];

    $clasificare = explode("</p>", $clasificare);
    $clasificare = trim($clasificare[0]);

    $genuri = explode("cats =", $page);
    $genuri = $genuri[1];

    $genuri = explode('"', $genuri);
    $genuri = $genuri[1];

    $genuri = explode(',', $genuri);

    $details = [
        'titlu' => $titlu,
        'regizor' => $regizor,
        'durata' => $durata,
        'clasificare' => $clasificare,
        'genuri' => $genuri,
    ];

    // Process and insert movie details into the database
    processMovieDetails($conn, $details);
}



// URL of the movie details page on Cinemacity
$link = 'https://www.cinemacity.ro/films/familia-addams-2/4522d2r#/buy-tickets-by-film?for-movie=4522d2r&view-mode=list';

// Fetch and insert movie details into the database if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fetchMovies'])) {
    fetchAndInsertMovieDetails($conn, $link);
}
?>
