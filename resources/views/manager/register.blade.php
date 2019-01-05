<!DOCTYPE HTML>
<head>
</head>
<body>
<h3>Регистрация</h3>

<form action="/register" method="POST">
    {{ csrf_field() }}

    Логин<br>
    <input type="text" name="login" value=""><br><br>

    E-mail<br>
    <input type="text" name="email" value=""><br><br>

    Пароль<br>
    <input type="text" name="password" value=""><br><br>

    ФИО<br>
    <input type="text" name="full_name" value=""><br><br>

    <input type="submit" name="Подтвердить">
</form>

</body>
