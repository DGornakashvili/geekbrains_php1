<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{TITLE}}</title>
    {{STYLE}}
</head>
<body>
<div class="{{CLASS}}">
    <div class="product-crud">
        <a href="/productCRUD/createProduct.php" target="_blank">Create</a>
        <a href="/productCRUD/showProduct.php" target="_blank">Read</a>
        <a href="/productCRUD/updateProduct.php" target="_blank">Update</a>
        <a href="/productCRUD/deleteProduct.php" target="_blank">Delete</a>
    </div>
    <h1>{{NAME}}</h1>
    <p>Price: {{PRICE}}$</p>
    <p>{{TEXT}}</p>
    <img src="{{SRC}}" alt="{{NAME}}">
</div>
</body>
</html>