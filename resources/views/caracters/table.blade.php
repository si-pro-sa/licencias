<div class="table-responsive">
    <table class="table" id="caracters-table">
        <thead>
            <tr>
                <th>Codigo</th>
        <th>Descripcion</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($caracters as $caracter)
            <tr>
                <td>{{ $caracter->codigo }}</td>
            <td>{{ $caracter->descripcion }}</td>
                <td>
                    {!! Form::open(['route' => ['caracters.destroy', $caracter->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('caracters.show', [$caracter->id]) }}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{{ route('caracters.edit', [$caracter->id]) }}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
