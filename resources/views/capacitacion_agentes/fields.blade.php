<!-- Idcapacitacion Field -->
<div class="form-group col-sm-6">
    {!! Form::label('idCapacitacion', 'Idcapacitacion:') !!}
    {!! Form::number('idCapacitacion', null, ['class' => 'form-control']) !!}
</div>

<!-- Idagente Field -->
<div class="form-group col-sm-6">
    {!! Form::label('idAgente', 'Idagente:') !!}
    {!! Form::number('idAgente', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('capacitacionAgentes.index') }}" class="btn btn-default">Cancel</a>
</div>
