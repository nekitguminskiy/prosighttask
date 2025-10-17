<?php

// Проверка настройки проекта
echo "=== Проверка настройки проекта ===\n";

// 1. Проверка файлов
$requiredFiles = [
    'app/Http/Controllers/Api/SalesmanController.php',
    'app/Http/Controllers/Api/CodelistController.php',
    'routes/api.php',
    'bootstrap/app.php',
    'app/Models/Salesman.php',
    'app/Services/SalesmanService.php',
];

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "✓ $file\n";
    } else {
        echo "✗ $file - НЕ НАЙДЕН\n";
    }
}

// 2. Проверка namespace и классов
echo "\n=== Проверка классов ===\n";

$classes = [
    'App\\Http\\Controllers\\Api\\SalesmanController',
    'App\\Http\\Controllers\\Api\\CodelistController',
    'App\\Models\\Salesman',
    'App\\Services\\SalesmanService',
];

foreach ($classes as $class) {
    if (class_exists($class)) {
        echo "✓ $class\n";
    } else {
        echo "✗ $class - НЕ НАЙДЕН\n";
    }
}

echo "\n=== Проверка завершена ===\n";

