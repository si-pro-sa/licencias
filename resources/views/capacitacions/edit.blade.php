@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Capacitacion
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($capacitacion, ['route' => ['capacitacions.update', $capacitacion->id], 'method' => 'patch']) !!}

                        @include('capacitacions.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection