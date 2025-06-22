<div class="table-responsive">
    <table class="table" id="capacitacionAgentes-table">
        <thead>
            <tr>
                <th>Idcapacitacion</th>
        <th>Idagente</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($capacitacionAgentes as $capacitacionAgente)
            <tr>
                <td>{{ $capacitacionAgente->idCapacitacion }}</td>
            <td>{{ $capacitacionAgente->idAgente }}</td>
                <td>
                    {!! Form::open(['route' => ['capacitacionAgentes.destroy', $capacitacionAgente->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('capacitacionAgentes.show', [$capacitacionAgente->id]) }}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{{ route('capacitacionAgentes.edit', [$capacitacionAgente->id]) }}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
