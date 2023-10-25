<h1>Resultados</h1>

<form method="POST" action="{{ route("crearresultado") }}">
    {{ csrf_field() }}
    <div>
        Número premiado
        <input type="text" id="numero" name="numero" />
    </div>

    <div>
        Reintegro
        <input type="text" id="reintegro" name="reintegro" />
    </div>

    <div>
        Serie
        <input type="text" id="serie" name="serie" />
    </div>

    <div>
        Fracción
        <input type="text" id="fraccion" name="fraccion" />
    </div>

    <div>
        Sorteo
        <select name="sorteo" id="sorteo">
            @foreach($sorteos as $sorteo)
                <option value="{{$sorteo->id}}">({{$sorteo->fecha}}) {{ $sorteo->nombre }}</option>
            @endforeach
        </select>
    </div>
    <input type="submit" value="Crear resultado">
</form>

<table>
    <tr>
        <td>ID</td>
        <td>Sorteo</td>
        <td>número</td>
        <td>Reintegro</td>
        <td>Serie</td>
        <td>Fracción</td>
    </tr>
    @foreach($resultados as $resultado)
        <tr>
            <td>{{ $resultado->id }}</td>
            <td>{{ $resultado->sorteo }}</td>
            <td>{{ $resultado->numero }}</td>
            <td>{{ $resultado->reintegro }}</td>
            <td>{{ $resultado->serie }}</td>
            <td>{{ $resultado->fraccion }}</td>
            <td>
                <a href="{{ route("editarresultado", $resultado->id) }}">Editar</a>
                <a href="{{ route("eliminarresultado", $resultado->id) }}">Eliminar</a>
            </td>
        </tr>
    @endforeach
</table>

