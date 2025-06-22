<!-- Resolucion Field -->
<div class="form-group col-sm-6">
    {!! Form::label('resolucion', 'Resolucion:') !!}
    {!! Form::text('resolucion', null, ['class' => 'form-control']) !!}
</div>

<!-- Razon Field -->
<div class="form-group col-sm-6">
    {!! Form::label('razon', 'Razon:') !!}
    {!! Form::text('razon', null, ['class' => 'form-control']) !!}
</div>

<!-- Evento Nombre Field -->
<div class="form-group col-sm-6">
    {!! Form::label('evento_nombre', 'Evento Nombre:') !!}
    {!! Form::text('evento_nombre', null, ['class' => 'form-control']) !!}
</div>

<!-- Evento Lugar Field -->
<div class="form-group col-sm-6">
    {!! Form::label('evento_lugar', 'Evento Lugar:') !!}
    {!! Form::text('evento_lugar', null, ['class' => 'form-control']) !!}
</div>

<!-- Fecha Evento Inicio Field -->
<div class="form-group col-sm-6">
    {!! Form::label('fecha_evento_inicio', 'Fecha Evento Inicio:') !!}
    {!! Form::text('fecha_evento_inicio', null, ['class' => 'form-control','id'=>'fecha_evento_inicio']) !!}
</div>

@push('scripts')
    <script type="text/javascript">
        $('#fecha_evento_inicio').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Fecha Evento Final Field -->
<div class="form-group col-sm-6">
    {!! Form::label('fecha_evento_final', 'Fecha Evento Final:') !!}
    {!! Form::text('fecha_evento_final', null, ['class' => 'form-control','id'=>'fecha_evento_final']) !!}
</div>

@push('scripts')
    <script type="text/javascript">
        $('#fecha_evento_final').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Idcaracter Field -->
<div class="form-group col-sm-6">
    {!! Form::label('idCaracter', 'Idcaracter:') !!}
    {!! Form::number('idCaracter', null, ['class' => 'form-control']) !!}
</div>

<!-- Idtipoevento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('idTipoEvento', 'Idtipoevento:') !!}
    {!! Form::number('idTipoEvento', null, ['class' => 'form-control']) !!}
</div>

<!-- Idalcancecapacitacion Field -->
<div class="form-group col-sm-6">
    {!! Form::label('idAlcanceCapacitacion', 'Idalcancecapacitacion:') !!}
    {!! Form::number('idAlcanceCapacitacion', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('capacitacions.index') }}" class="btn btn-default">Cancel</a>
</div>
