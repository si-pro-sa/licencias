@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Caracter
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($caracter, ['route' => ['caracters.update', $caracter->id], 'method' => 'patch']) !!}

                        @include('caracters.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection