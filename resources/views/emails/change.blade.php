<!DOCTYPE html>
<html>
    <head>
    </head>  
    <body>
        <h3>Уважаемый, {{$user->name}}</h3>
        <p>Ваш пароль был изменен.</p>
        <p>Новый пароль: {{$password}}</p>
        <p>С уважением, Администратор сайта <a href='http://www.admin.mediasport.center/' target="_blank">admin.mediasport.center</a></p>
    </body>
</html>
