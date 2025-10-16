<?php
// Lógica de tu clase Renderer, ahora como una función para la vista
function render_hangman_ascii(int $attemptsLeft): string {
    $stages = [
        '<pre>  ┌────┐<br>  │    │<br>       │<br>       │<br>       │<br>       │<br>═══════╧═</pre>', // 6 intentos
        '<pre>  ┌────┐<br>  │    │<br>  ◯    │<br>       │<br>       │<br>       │<br>═══════╧═</pre>', // 5
        '<pre>  ┌────┐<br>  │    │<br>  ◯    │<br>  │    │<br>       │<br>       │<br>═══════╧═</pre>', // 4
        '<pre>  ┌────┐<br>  │    │<br>  ◯    │<br> ╱│    │<br>       │<br>       │<br>═══════╧═</pre>', // 3
        '<pre>  ┌────┐<br>  │    │<br>  ◯    │<br> ╱│╲   │<br>       │<br>       │<br>═══════╧═</pre>', // 2
        '<pre>  ┌────┐<br>  │    │<br>  ◯    │<br> ╱│╲   │<br> ╱     │<br>       │<br>═══════╧═</pre>', // 1
        '<pre>  ┌────┐<br>  │    │<br>  ◯    │<br> ╱│╲   │<br> ╱ ╲   │<br>       │<br>═══════╧═</pre>'  // 0
    ];
    $failedAttempts = 6 - $attemptsLeft;
    $index = max(0, min(6, $failedAttempts));
    return $stages[$index];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Juego del Ahorcado</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Juego del Ahorcado  hanged</h1>

    <?php if ($game): ?>
        <div><?php echo render_hangman_ascii($game->getAttemptsLeft()); ?></div>
        <div class="masked-word"><?php echo implode(' ', str_split($game->getMaskedWord())); ?></div>
        
        <?php if ($game->isWon()): ?>
            <p class="result won">¡GANASTE! La palabra era: <?php echo $game->getWord(); ?></p>
        <?php elseif ($game->isLost()): ?>
            <p class="result lost">¡PERDISTE! La palabra era: <?php echo $game->getWord(); ?></p>
        <?php else: ?>
            <p>Intentos restantes: <?php echo $game->getAttemptsLeft(); ?></p>
            <p>Letras usadas: <?php echo implode(', ', $game->getUsedLetters()); ?></p>
            <form method="post" action="index.php?action=guess">
                <input type="text" name="letter" maxlength="1" required autofocus pattern="[A-Za-zñÑ]" title="Introduce una sola letra">
                <button type="submit">Probar</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>

    <hr>
    <div class="start-game">
        <h3>Empezar un nuevo juego</h3>
        <form method="post" action="index.php?action=start">
            <label for="category">Elige una categoría:</label>
            <select name="category" id="category">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars(ucfirst($cat)); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Empezar</button>
        </form>
    </div>
</body>
</html>