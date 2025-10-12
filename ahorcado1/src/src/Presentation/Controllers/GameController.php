<?php
declare(strict_types=1);

namespace App\Presentation\Controllers;

class GameController
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function handle(): void
    {
        // Inicializaremos el juego aquí
        // La vista se encargará de mostrar la interfaz
    }
}