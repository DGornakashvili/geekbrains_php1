<?php

require_once __DIR__ . '/../../config/config.php';

if (isset($_POST['name'], $_POST['description'], $_POST['price'])) {
    $db = createConnection();
    $name = escapeString($db, $_POST['name']);
    $text = escapeString($db, $_POST['description']);
    $price = $_POST['price'];
    $image = basename($_FILES['image']['name']);
    $sql = "INSERT INTO `products` (`name`, `description`, `price`, `image`) VALUES ('$name', '$text', '$price', '$image')";

    uploadFile('image');
    $result = execQuery($sql);

    echo $result ? "<h1>Product was successfully created</h1>" : "<h1>Something has gone wrong</h1>";
} else {
    echo render(TEMPLATES_DIR . 'createForm.tpl', [
        'title' => 'Create',
    ]);
}