<div class="table-responsive">
    <table class="table" id="capacitacions-table">
        <thead>
            <tr>
                <th>Resolucion</th>
        <th>Razon</th>
        <th>Evento Nombre</th>
        <th>Evento Lugar</th>
        <th>Fecha Evento Inicio</th>
        <th>Fecha Evento Final</th>
        <th>Idcaracter</th>
        <th>Idtipoevento</th>
        <th>Idalcancecapacitacion</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($capacitacions as $capacitacion)
            <tr>
                <td>{{ $capacitacion->resolucion }}</td>
            <td>{{ $capacitacion->razon }}</td>
            <td>{{ $capacitacion->evento_nombre }}</td>
            <td>{{ $capacitacion->evento_lugar }}</td>
            <td>{{ $capacitacion->fecha_evento_inicio }}</td>
            <td>{{ $capacitacion->fecha_evento_final }}</td>
            <td>{{ $capacitacion->idCaracter }}</td>
            <td>{{ $capacitacion->idTipoEvento }}</td>
            <td>{{ $capacitacion->idAlcanceCapacitacion }}</td>
                <td>
                    {!! Form::open(['route' => ['capacitacions.destroy', $capacitacion->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('capacitacions.show', [$capacitacion->id]) }}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{{ route('capacitacions.edit', [$capacitacion->id]) }}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
