<?php

function renderItems($array)
{
    $result = '';

    foreach ($array as $item) {
        $str = strlen($item);
        if ($str < 5) {
            continue;
        }

        if (strpos($item, '.jpg')) {
            $src = 'img/' . $item;
            $alt = str_replace(['_', '.jpg'], [' ', ''], $item);
            $result .= "<img src='$src' alt='$alt'>";
        } elseif (strpos($item, '.css')) {
            $href = 'css/' . $item;
            $result .= "<link rel='stylesheet' href='$href'>";
        } elseif (strpos($item, '.js')) {
            $src = 'js/' . $item;
            $result .= "<script src='$src'></script>";
        }
    }
    return $result;
}

function render($file, $variables = [])
{
    if (!is_file($file)) {
        echo 'Template file "' . $file . '" not found';
        exit();
    }

    if (filesize($file) === 0) {
        echo 'Template file "' . $file . '" is empty';
        exit();
    }

    $templateContent = file_get_contents($file);

    if (empty($variables)) {
        return $templateContent;
    }

    foreach ($variables as $key => $value) {
        if (is_array($value)) {
            $value = renderItems($value);
        }

        if (empty($value) || !is_string($value)) {
            continue;
        }

        $key = '{{' . strtoupper($key) . '}}';

        $templateContent = str_replace($key, $value, $templateContent);
    }

    return $templateContent;
}

function generateGallery($array)
{
    $result = '';

    foreach ($array as $image) {
        if (is_file($image['url'])) {
            $result .= render(TEMPLATES_DIR . 'galleryItem.tpl', [
                'id' => $image['id'],
                'src' => $image['url'],
                'alt' => $image['title'],
                'views' => ($image['views'] > 0) ? $image['views'] : 'no views',
            ]);
        }
    }
    return $result;
}

function generateCss($sql)
{
    $result = '';
    $array = getAssocResult($sql);
    foreach ($array as $cssItem) {
        if (is_file(WWW_DIR . $cssItem['url'])) {
            $result .= render(TEMPLATES_DIR . 'cssItem.tpl', [
                'href' => '/' . $cssItem['url'],
            ]);
        }
    }
    return $result;
}

function generateJs($sql)
{
    $result = '';
    $array = getAssocResult($sql);
    foreach ($array as $jsItem) {
        if (is_file(WWW_DIR . $jsItem['url'])) {
            $result .= render(TEMPLATES_DIR . 'jsItem.tpl', [
                'src' => $jsItem['url'],
            ]);
        }
    }
    return $result;
}

function generateProduct($sql)
{
    $result = '';
    $array = getAssocResult($sql);

    foreach ($array as $product) {
        $id = $product['id'];
        $result .= render(TEMPLATES_DIR . 'productItem.tpl', [
            'id' => $id,
            'src' => PRODUCT_IMG_DIR . $product['image'],
            'name' => $product['name'],
            'price' => $product['price'],
            'text' => $product['description'],
            'button' => empty($_SESSION['user'])
                ? '<h5>Sign in to shop</h5>'
                : "<button class='add-btn' data-id='$id'>Add to Cart</button>",
        ]);
    }
    return $result;
}

function generateCart($sql)
{
    $result = '';
    $array = getAssocResult($sql);

    foreach ($array as $product) {
        $result .= render(TEMPLATES_DIR . 'cartItem.tpl', [
            'id' => $product['product_id'],
            'src' => PRODUCT_IMG_DIR . $product['image'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $product['quantity'],
        ]);
    }
    return $result;
}

function getCartSubtotal($id)
{
    $result = 0;
    $sql = "SELECT * FROM `cart` WHERE `user_id`=$id";
    $cartItems = getAssocResult($sql);

    foreach ($cartItems as $item) {
        $result += $item['price'] * $item['quantity'];
    }

    return "$result$";
}

function getOrderGrandTotal($id)
{
    $result = 0;
    $sql = "SELECT * FROM `users_orders` AS uo JOIN `products` AS p ON `uo`.`product_id`=`p`.`id` WHERE `order_id`=$id";
    $orderItems = getAssocResult($sql);
    $sqlStocks = "SELECT * FROM `stocks` WHERE `active`= 1";
    $stocks = getAssocResult($sqlStocks);

    foreach ($orderItems as $item) {
        $price = (int)$item['price'];
        if (!empty($stocks)) {
            foreach ($stocks as $stock) {
                $price *= 1 - ($stock['discount'] / 100);
            }
        }
        $result += $price * $item['quantity'];
    }
    return "$result$";
}

function uploadFile($filedName)
{
    if (!empty($_FILES[$filedName]) && !$_FILES[$filedName]['error']) {
        $file = $_FILES[$filedName];
        $uploadDir = WWW_DIR . PRODUCT_IMG_DIR;
        $uploadFile = $uploadDir . basename($file['name']);
        return move_uploaded_file($file['tmp_name'], $uploadFile);
    }
    return false;
}

function generateSISuccess($user, $massage, $role = 0)
{
    $link = '/product.php';

    if ($role === 1) {
        $link = '/admin.php';
    }

    $result = render(TEMPLATES_DIR . 'signSuccess.tpl', [
        'name' => $user['name'],
        'login' => $user['login'],
        'massage' => $massage,
        'link' => $link,
    ]);
    return $result;
}

function generateSIError($massage)
{
    return "<h3 class='signError'>$massage</h3>";
}

function ajaxResult($type, $data, $subtotal = '', $error = false)
{
    header('Content-type: application/json');
    echo json_encode([
        'error' => $error,
        'type' => $type,
        'data' => $data,
        'subtotal' => $subtotal,
    ]);
}

function generateOrderItem($id)
{
    $result = '';
    $sql = "SELECT * FROM `users_orders` AS uo JOIN `products` AS p ON `uo`.`product_id`=`p`.`id` WHERE `order_id`=$id";
    $sqlStocks = "SELECT * FROM `stocks` WHERE `active`= 1";
    $orderItems = getAssocResult($sql);
    $stocks = getAssocResult($sqlStocks);

    foreach ($orderItems as $item) {
        $price = (int)$item['price'];
        if (!empty($stocks)) {
            foreach ($stocks as $stock) {
                $price *= 1 - ($stock['discount'] / 100);
            }
        }
        $result .= render(TEMPLATES_DIR . 'myOrdersItem.tpl', [
            'src' => PRODUCT_IMG_DIR . $item['image'],
            'name' => $item['name'],
            'price' => "$price",
            'quantity' => $item['quantity'],
            'total' => (string)($item['quantity'] * $price),
        ]);
    }

    return $result;
}

function generateOrderList($userId)
{
    $result = '';
    $role = (int)$_SESSION['user']['role'];
    $sql = "SELECT * FROM `orders` WHERE `user_id` = $userId";

    if ($role === 1) {
        $sql = "SELECT * FROM `orders`";
    }
    $orders = getAssocResult($sql);

    foreach ($orders as $order) {
        $orderId = $order['order_id'];
        $orderStatus = (int)$order['status'];
        $status = 'accepted';

        if ($role === 1) {
            $btn = "<button class='status-btn' data-id='$orderId'>Update status</button>";
            $status = "<input type='number' value='$orderStatus' min='0' max='4'>";
        } else {
            $btn = "<button class='cancel-btn' data-id='$orderId'>Cancel</button>";

            if ($orderStatus === 1) {
                $status = 'preparing';
            } elseif ($orderStatus === 2) {
                $status = 'ready';
            } elseif ($orderStatus === 3) {
                $status = 'completed';
                $btn = '<p>Order completed!</p>';
            } elseif ($orderStatus === 4) {
                $status = 'cancelled';
                $btn = '<p>Order cancelled!</p>';
            }
        }
        $result .= render(TEMPLATES_DIR . 'myOrdersList.tpl', [
            'dataid' => $orderId,
            'id' => $role !== 1 ? $orderId : "$orderId | Ordered by user with ID: " . $order['user_id'],
            'status' => $status,
            'button' => $btn,
            'grandtotal' => getOrderGrandTotal($orderId),
            'content' => generateOrderItem($orderId),
        ]);
    }
    return $result;
}