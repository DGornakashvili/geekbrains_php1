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
        {{SIGN}}
    </div>
    {{CONTENT}}
    <div class="cart">
        {{CARTCONTENT}}
        <div class="cart-total-order">
            <p>Total: <span class="cart-subtotal">{{SUBTOTAL}}</span></p>
            {{ORDERBTNS}}
        </div>
    </div>
</div>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/product.js"></script>
</body>
</html>