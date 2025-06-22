@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Tipo Evento
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($tipoEvento, ['route' => ['tipoEventos.update', $tipoEvento->id], 'method' => 'patch']) !!}

                        @include('tipo_eventos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection