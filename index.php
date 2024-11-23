<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\ArticleController;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (!isset($_ENV['API_BASE_URL']) || !isset($_ENV['CACHE_DIR'])) {
    die("Assicurati di aver configurato il file .env con API_BASE_URL e CACHE_DIR.");
}

try {
    echo "Ciao! Questo script ti permette di cercare articoli per autore." . PHP_EOL;
    echo "Inserisci il nome dell'autore: ";
    $author = trim(fgets(STDIN));
    while (empty($author)) {
        echo "Il nome dell'autore è obbligatorio. Riprova: ";
        $author = trim(fgets(STDIN));
    }
    echo "Autore inserito: {$author}. Vediamo cosa trovo....." . PHP_EOL;

    $articleController = new ArticleController();
    $articles = $articleController->getArticlesTitlesByAuthor(strtolower($author));

    if (empty($articles)) {
        $exampleAuthors = ["saintamh", "panny", "epaga", "olalonde", "wisnorcan"];
        echo "Nessun articolo trovato per l'autore '{$author}'. Spiaze. La prossima volta prova con '{$exampleAuthors[array_rand($exampleAuthors)]}'" . PHP_EOL;
    } else {
        echo count($articles)." articoli trovati per l'autore '{$author}':" . PHP_EOL;
        foreach ($articles as $article) {
            echo "- {$article}" . PHP_EOL;
        }
    }
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
}