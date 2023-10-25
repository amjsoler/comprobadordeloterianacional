@if($response["code"] == 0)
    <form action="{{route("recuperarcuentapost")}}" method="post">
        {{ csrf_field() }}
        <input type="hidden" value="{{ $response["data"]->token }}" name="token" id="token">
        <input type="password" id="password" name="password">
        <input type="password" id="password_confirmation" name="password_confirmation">
        <input type="submit" value="Guardar">
    </form>
@else
    <p>Parece ser que el enlace no es válido o ha caducado. Intenta solicitar un nuevo correo desde la app</p>
@endif

<!-- TODO: Dar estilos a esta página y manejar errores de validación -->
