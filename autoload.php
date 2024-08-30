<?php
// Função de autoload personalizada para carregar classes automaticamente
spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/'; // Diretório base onde estão os arquivos de classe
    $file = $baseDir . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});
