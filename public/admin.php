<?php

require_once __DIR__ . '/../config/config.php';

$role = (int)$_SESSION['user']['role'] ?? 0;

if ($role !== 1) {
    echo "<h1>Access dined!</h1>";
    exit();
}

$sqlCss = "SELECT * FROM `css` WHERE `name` LIKE 'product'";

echo render(TEMPLATES_DIR . 'admin.tpl', [
    'title' => 'Admin',
    'class' => 'admin-menu',
    'style' => generateCss($sqlCss),
]);