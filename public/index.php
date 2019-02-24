<?php

require_once __DIR__ . '/../config/config.php';

echo render(TEMPLATES_DIR . 'index.tpl', [
    'title' => 'Cars',
	'class' => 'gallery',
	'content' => scandir('img/'),
    'style' => scandir('css/'),
    'js' => scandir('js/'),
]);
