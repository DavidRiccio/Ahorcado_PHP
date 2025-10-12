<?php
declare(strict_types=1);

use App\Presentation\Controllers\GameController;

require __DIR__ . '/../src/Infrastructure/Autoload/Autoloader.php';
\App\Infrastructure\Autoload\Autoloader::register('App\\', __DIR__ . '/../src');

$config = require __DIR__ . '/../config/config.php';
$controller = new GameController($config);
$controller->handle();

use App\Domain\Entity\Game;
use App\Domain\Entity\WordProvider;
use App\Domain\Entity\Storage;
use App\Domain\Entity\Renderer;

$storage = new Storage();
$renderer = new Renderer();

// Manejar cambio de categoría
if (isset($_POST['category'])) {
    $storage->reset(); // Reiniciar el juego al cambiar de categoría
}

// Obtener o establecer la categoría
$category = $storage->get('category') ?? 'programming';
if (isset($_POST['category'])) {
    $category = $_POST['category'];
    $storage->set('category', $category);
}

$provider = new WordProvider($category);
$state = $storage->get('state');
$word  = $storage->get('word');

if (!$word) {
    $word = $provider->randomWord();
    $storage->set('word', $word);
}

$maxAttempts = 6;
$game = new Game($word, $maxAttempts, $state);

// Procesar entrada del usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['letter'])) {
        // Procesar intento de letra
        $game->guessLetter($_POST['letter']);
        
        // Guardar estado del juego
        $storage->set('state', $game->toState());
    }
    
    if (isset($_POST['reset'])) {
        // Reiniciar juego
        $storage->reset();
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Verificar estado del juego
$isWon = $game->isWon();
$isLost = $game->isLost();
$isGameOver = $isWon || $isLost;

// Obtener datos del juego
$maskedWord = $game->getMaskedWord();
$attemptsLeft = $game->getAttemptsLeft();
$usedLetters = $game->getUsedLetters();
$asciiArt = $renderer->ascii($attemptsLeft);

// Alfabeto completo para los botones
$alphabet = range('A', 'Z');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego del Ahorcado</title>
    <link rel="stylesheet" href="css/styles.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fira+Code:wght@400;500;600&display=swap" rel="stylesheet">
    </style>
</head>
<body>
    <div class="container">
        <h1>🎮 El Ahorcado</h1>
        
        <form method="POST" class="category-selector">
            <select name="category" onchange="this.form.submit()">
                <?php foreach (WordProvider::getAvailableCategories() as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>>
                        <?= ucfirst(htmlspecialchars($cat)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <div class="game-info">
            <div class="info-item">
                <strong>Categoría:</strong> <?= ucfirst(htmlspecialchars($category)) ?>
            </div>
            <div class="info-item">
                <strong>Intentos restantes:</strong> <?= $attemptsLeft ?> / <?= $maxAttempts ?>
            </div>
            <div class="info-item">
                <strong>Letras usadas:</strong> <?= count($usedLetters) ?>
            </div>
        </div>
        
        <?php if ($isGameOver): ?>
            <div class="game-over <?= $isWon ? 'won' : 'lost' ?>">
                <?php if ($isWon): ?>
                    <h2>Partida Finalizada</h2>
                    <p>Has completado el desafío con éxito.</p>
                    <div class="word-reveal">
                        Palabra: <?= htmlspecialchars($game->getWord()) ?>
                    </div>
                <?php else: ?>
                    <h2>Partida Finalizada</h2>
                    <p>No has logrado adivinar la palabra en esta ocasión.</p>
                    <div class="word-reveal">
                        Palabra: <?= htmlspecialchars($game->getWord()) ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="game-area">
            <div class="hangman-drawing">
                <?= $asciiArt ?>
            </div>
            
            <div class="word-area">
                <div class="masked-word">
                    <?= htmlspecialchars($maskedWord) ?>
                </div>
            </div>
        </div>
        
        <?php if (!empty($usedLetters)): ?>
            <div class="used-letters">
                <h3>Letras utilizadas:</h3>
                <div class="used-letters-list">
                    <?= htmlspecialchars(implode(' ', $usedLetters)) ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (!$isGameOver): ?>
            <form method="POST" class="keyboard">
                <?php foreach ($alphabet as $letter): ?>
                    <button 
                        type="submit" 
                        name="letter" 
                        value="<?= $letter ?>"
                        class="letter-btn"
                        <?= in_array($letter, $usedLetters) ? 'disabled' : '' ?>
                    >
                        <?= $letter ?>
                    </button>
                <?php endforeach; ?>
            </form>
        <?php endif; ?>
        
        <div class="controls">
            <form method="POST">
                <button type="submit" name="reset" class="reset-btn">
                    <?= $isGameOver ? '🔄 Nueva Partida' : '🔄 Reiniciar' ?>
                </button>
            </form>
        </div>
    </div>
</body>
</html>
