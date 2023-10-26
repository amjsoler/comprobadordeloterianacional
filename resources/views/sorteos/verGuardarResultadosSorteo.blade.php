<h1>Resultados para el sorteo: {{ $sorteo->nombre }} del {{ $sorteo->fecha }}</h1>

<form method="post" action="{{ route("resultadossorteoguardar", $sorteo->id) }}">
    {{ csrf_field() }}
    <input type="submit" value="Guardar resultados" />
    <div>
        <textarea id="resultados" name="resultados">
        {{ $sorteo->resultados }}
    </textarea>
    </div>
</form>


<h2>Ejemplo de estructura para un especial</h2>
    nombre del premio;numeros (separados por & si hay más de uno);premio
<pre>
    especial;09098&2&4;14870000
    primero;09098;130000
    segundo;89378;25000
    4cifras;0181&0471&2965&4992&7010;375
    3cifras;031&095&175&206&261&314&337&424&472&615&655&667&766&893&895;75
    2cifras;20&99;30
    aproximacionesprimero;09097&09099;2400
    aproximacionessegundo;89378&89380;1532
    centenaprimero;09000&09099;75
    centenasegundo;89300&89399;75
    3terminacionesprimero;098;75
    2terminacionesprimero;98;75
    1terminacionprimero;8;15
    reintegros;2&5;15
</pre>
