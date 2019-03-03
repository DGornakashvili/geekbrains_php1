<?php

require_once __DIR__ . '/../../config/config.php';

$id = $_POST['id'] ?? false;

if ($id) {
    $sql = "DELETE FROM `products` WHERE `id` = $id";
    $result = execQuery($sql);

    if ($result) {
        echo "<h1>Product with ID $id was successfully deleted</h1>";
    } else {
        echo "<h1>Something has gone wrong</h1>";
    }
} else {
    echo render(TEMPLATES_DIR . 'requestIdForm.tpl', [
        'title' => 'Delete',
    ]);
}