<?php

require_once __DIR__ . '/../config/config.php';

$sqlCss = "SELECT * FROM `css` WHERE `name` LIKE 'product'";

echo render(TEMPLATES_DIR . 'signUp.tpl', [
    'style' => generateCss($sqlCss),
]);