<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{TITLE}}</title>
</head>
<body>
<form enctype="multipart/form-data" action="" method="POST">
    <label>ID: <input type="number" name="id" value="{{ID}}" readonly></label><br>
    <label>Name: <input type="text" name="name" value="{{NAME}}"></label><br>
    <label>Description: <textarea name="description">{{TEXT}}</textarea></label><br>
    <label>Price <input type="number" name="price" value="{{PRICE}}"></label><br>
    <label>Image <input type="file" name="image"></label><br>
    <button>Submit</button>
</form>
</body>
</html>