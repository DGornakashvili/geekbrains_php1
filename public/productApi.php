<?php

require_once __DIR__ . '/../config/config.php';

$userId = $_SESSION['user']['id'] ?? '';
$id = $_POST['id'] ?? '';
$method = $_POST['method'] ?? '';
$uName = $_POST['uName'] ?? '';
$uLogin = $_POST['uLogin'] ?? '';
$uPass = $_POST['uPass'] ?? '';

if ($id && ($method === 'remove' || $method === 'add')) {
    if ($method === 'add') {
        $sqlCart = "SELECT * FROM `cart` WHERE `user_id`=$userId AND `product_id`=$id";
        $product = show($sqlCart);

        if (!empty($product)) {
            $quantity = $product['quantity'] + 1;
            $sqlUpdate = "UPDATE `cart` SET `quantity`=$quantity WHERE `user_id`=$userId AND `product_id`=$id";
            execQuery($sqlUpdate);
            $subtotal = getCartSubtotal($userId);
            ajaxResult('update', $quantity, $subtotal);
        } else {
            $sqlGet = "SELECT * FROM `products` WHERE `id`=$id";
            $product = show($sqlGet);
            $id = $product['id'];
            $name = $product['name'];
            $price = $product['price'];
            $image = $product['image'];
            $sqlInsert =
                "INSERT INTO `cart` (`user_id`, `product_id`, `name`, `price`, `image`) VALUES ($userId, $id, '$name', '$price', '$image')";
            $result = execQuery($sqlInsert);
            $subtotal = getCartSubtotal($userId);

            if ($result) {
                $data = generateCart($sqlCart);
                ajaxResult('create', $data, $subtotal);
            }
        }
    } elseif ($method === 'remove') {
        $sqlDelete = "DELETE FROM `cart` WHERE `user_id`=$userId AND `product_id`=$id";
        execQuery($sqlDelete);
        $subtotal = getCartSubtotal($userId);
        ajaxResult('delete', $id, $subtotal);
    }
    exit();
}

if ($uLogin && $uPass && ($method === 'signIn' || $method === 'signUp')) {
    $db = createConnection();
    $uName = escapeString($db, $uName);
    $uLogin = escapeString($db, $uLogin);
    $uPass = escapeString($db, $uPass);
    $uPass = md5($uPass);

    if ($method === 'signIn') {
        $sqlFindUser = "SELECT * FROM `users` WHERE `login` LIKE '$uLogin' AND `password` LIKE '$uPass'";
        $user = show($sqlFindUser);

        if (!empty($user)) {
            $_SESSION['user'] = $user;
            $data = generateSISuccess($user, "You are welcome!", (int)$user['role']);
            ajaxResult('signIn', $data);
        } else {
            $data = generateSIError('Incorrect Login or Password');
            ajaxResult('signIn', $data, 0, true);
        }
    } elseif ($method === 'signUp') {
        if (!$uName) {
            $data = generateSIError("Please, enter your name");
            ajaxResult('signUp', $data, 0, true);
            exit();
        }

        $sqlFindUser = "SELECT * FROM `users` WHERE `login` LIKE '$uLogin'";
        $user = show($sqlFindUser);

        if (!empty($user)) {
            $data = generateSIError("Login $uLogin is already occupied");
            ajaxResult('signUp', $data, 0, true);
            exit();
        }

        $sqlAddUser = "INSERT INTO `users` (`name`, `login`, `password`) VALUES ('$uName', '$uLogin', '$uPass')";
        $result = execQuery($sqlAddUser);

        if ($result) {
            $sqlFindUser = "SELECT * FROM `users` WHERE `login` LIKE '$uLogin' AND `password` LIKE '$uPass'";
            $user = show($sqlFindUser);
            $_SESSION['user'] = $user;
            $data = generateSISuccess($user, "Your account created!");
            ajaxResult('signUp', $data);
        } else {
            $data = generateSIError('Incorrect values, try again');
            ajaxResult('signUp', $data, 0, true);
        }
    }
} elseif (!($uLogin || $uPass) && ($method === 'signIn' || $method === 'signUp')) {
    $data = generateSIError('No Login or Password');
    ajaxResult('signIn', $data, 0, true);
    exit();
}

if ($method === 'order') {
    $sqlCart = "SELECT * FROM `cart` WHERE `user_id`=$userId";
    $userCart = getAssocResult($sqlCart);

    if (empty($userCart)) {
        exit();
    }

    $sqlNewOrder = "INSERT INTO `orders` (`user_id`) VALUES ($userId)";
    $orderId = insert($sqlNewOrder);

    $values = [];

    foreach ($userCart as $product) {
        $productId = $product['product_id'];
        $quantity = $product['quantity'];
        $values[] = "($orderId, $userId, $productId, $quantity)";
    }

    $values = implode(', ', $values);

    $sqlUserOrder = "INSERT INTO `users_orders` (`order_id`, `user_id`, `product_id`, `quantity`) VALUES $values";
    $result = execQuery($sqlUserOrder);
    emptyUserCart($userId);

    if ($result) {
        $subtotal = getCartSubtotal($userId);
        ajaxResult('orderAdded', '', $subtotal);
    }
    exit();
}

if ($method === 'cancel' && $id) {

    $sqlChangeStatus = "UPDATE `orders` SET `status` = '4' WHERE `order_id`=$id";
    $result = execQuery($sqlChangeStatus);

    if ($result) {
        $data = "<p>Order cancelled!</p>";
        ajaxResult('cancelled', $data);
    }
    exit();
}

if ($method === 'update-status' && $id) {
    $status = (int)$_POST['status'];

    $sqlChangeStatus = "UPDATE `orders` SET `status` = $status WHERE `order_id`=$id";
    $result = execQuery($sqlChangeStatus);

    if ($result) {
        $data = "Status successfully updated";
        ajaxResult('statusUpdated', $data);
    }
    exit();
}
