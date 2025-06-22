@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Alcance Capacitacion
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($alcanceCapacitacion, ['route' => ['alcanceCapacitacions.update', $alcanceCapacitacion->id], 'method' => 'patch']) !!}

                        @include('alcance_capacitacions.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection