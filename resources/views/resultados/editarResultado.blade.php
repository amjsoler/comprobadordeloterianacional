<form method="POST" action="{{ route("modificarresultado", $resultado->id) }}">
    {{ csrf_field() }}
    @method("PUT")
    <div>
        Número premiado
        <input type="text" id="numero" name="numero" value="{{$resultado->numero}}"/>
    </div>

    <div>
        Reintegro
        <input type="text" id="reintegro" name="reintegro" value="{{$resultado->reintegro}}" />
    </div>

    <div>
        Serie
        <input type="text" id="serie" name="serie" value="{{$resultado->serie}}" />
    </div>

    <div>
        Fracción
        <input type="text" id="fraccion" name="fraccion" value="{{$resultado->fraccion}}" />
    </div>

    <div>
        Sorteo
        <select name="sorteo" id="sorteo">
            @foreach($sorteos as $sorteo)
                @if($sorteo->id == $resultado->sorteo)
                    <option selected="selected" value="{{$sorteo->id}}">({{$sorteo->fecha}}) {{ $sorteo->nombre }}</option>
                @else
                    <option value="{{$sorteo->id}}">({{$sorteo->fecha}}) {{ $sorteo->nombre }}</option>
                @endif
            @endforeach
        </select>
    </div>
    <input type="submit" value="Crear resultado">
</form>
