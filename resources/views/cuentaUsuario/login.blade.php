<form action="/login" method="post">
    {{ csrf_field() }}
    <input type="text" id="email" name="email">
    <input type="password" id="password" name="password">
    <input type="submit" value="Entrar">
</form>
