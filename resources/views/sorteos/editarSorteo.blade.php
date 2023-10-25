<form method="POST" action="{{ route("modificarsorteo", $sorteo->id) }}">
    @method("PUT")
    {{ csrf_field() }}
    <div>
        Nombre del sorteo
        <input type="text" id="nombre" name="nombre" value="{{$sorteo->nombre}}" />
    </div>
    <div>
        Fecha del sorteo
        <input type="date" id="fecha" name="fecha" value="{{ $sorteo->fecha }}" />
    </div>
    <div>
        Número de sorteo
        <input type="number" id="numero_sorteo" name="numero_sorteo" value="{{$sorteo->numero_sorteo}}" />
    </div>
    <input type="submit" value="Modificar sorteo">
</form>
