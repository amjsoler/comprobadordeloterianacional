<h1>Sorteos</h1>

<form method="POST" action="{{ route("crearsorteo") }}">
    {{ csrf_field() }}
    <div>
        Nombre del sorteo
        <input type="text" id="nombre" name="nombre" />
    </div>
    <div>
        Fecha del sorteo
        <input type="date" id="fecha" name="fecha" />
    </div>
    <div>
        Número de sorteo
        <input type="number" id="numero_sorteo" name="numero_sorteo" />
    </div>
    <input type="submit" value="Crear sorteo">
</form>

<table>
    <tr>
        <td>ID</td>
        <td>Nombre</td>
        <td>Fecha</td>
        <td>#Sorteo</td>
        <td>Acciones</td>
    </tr>
    @foreach($sorteos as $sorteo)
        <tr>
            <td>{{ $sorteo->id }}</td>
            <td>{{ $sorteo->nombre }}</td>
            <td>{{ $sorteo->fecha }}</td>
            <td>{{ $sorteo->numero_sorteo }}</td>
            <td>
                <a href="{{ route("editarsorteo", $sorteo->id) }}">Editar</a>
                <a href="{{ route("eliminarsorteo", $sorteo->id) }}">Eliminar</a>
            </td>
        </tr>
    @endforeach
</table>

