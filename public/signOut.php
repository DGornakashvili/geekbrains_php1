<?php

require_once __DIR__ . '/../config/config.php';

$sqlCss = "SELECT * FROM `css` WHERE `name` LIKE 'product'";

echo render(TEMPLATES_DIR . 'signOut.tpl', [
    'class' => 'products',
    'content' => '<h2>Goodbye!</h2>',
    'style' => generateCss($sqlCss),
]);
session_destroy();