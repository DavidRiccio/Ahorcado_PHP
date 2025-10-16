<?php
require __DIR__ . '/../src/Infrastructure/Autoload/Autoloader.php';

$gameRepository = new \Infrastructure\Persistence\SessionGameRepository();
$wordRepository = new \Infrastructure\Persistence\JsonWordRepository();

$controller = new \Presentation\Controllers\GameController($gameRepository, $wordRepository);


$action = $_GET['action'] ?? 'show';

if ($action === 'start') {
    $controller->start($category);
    exit;
}

if ($action === 'guess' && isset($_POST['letter']) && ctype_alpha($_POST['letter'])) {
    $controller->guess($_POST['letter']);
    exit;
}

$game = $controller->getCurrentGame();
$categories = $controller->getCategories();

if (!$game && !empty($categories)) {
    $controller->start($categories[0]);
    $game = $controller->getCurrentGame();
}

include __DIR__ . '/../src/Presentation/view.php';