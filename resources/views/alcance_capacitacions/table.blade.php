<div class="table-responsive">
    <table class="table" id="alcanceCapacitacions-table">
        <thead>
            <tr>
                <th>Codigo</th>
        <th>Descripcion</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($alcanceCapacitacions as $alcanceCapacitacion)
            <tr>
                <td>{{ $alcanceCapacitacion->codigo }}</td>
            <td>{{ $alcanceCapacitacion->descripcion }}</td>
                <td>
                    {!! Form::open(['route' => ['alcanceCapacitacions.destroy', $alcanceCapacitacion->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('alcanceCapacitacions.show', [$alcanceCapacitacion->id]) }}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{{ route('alcanceCapacitacions.edit', [$alcanceCapacitacion->id]) }}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
