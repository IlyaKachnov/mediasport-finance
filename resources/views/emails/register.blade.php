<!DOCTYPE html>
<html>
    <head>
    </head>  
    <body>
        <h3>Уважаемый, {{$user->name}}</h3>
        <p>Вы были добавлены в систему НМФЛ в качестве {{$user->role->caption}}а.</p>
        <p>Ваш логин: {{$user->name}}</p>
        <p>Ваш пароль: {{$password}}</p>
        <p>С уважением, Администратор сайта <a href='http://www.admin.mediasport.center/' target="_blank">admin.mediasport.center</a></p>
    </body>
</html>
