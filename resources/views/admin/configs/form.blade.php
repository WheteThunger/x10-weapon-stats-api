@extends('admin.layout') 
    
@section('content') 
    <h1>Create Config form</h1>
    {!! Form::model($config, [
        'route' => ['admin.configs.' . (isset($config->defindex) ? 'update' : 'store'), $config->defindex],
        'method' => (isset($config->defindex) ? 'PUT' : 'POST')
    ]) !!}
    
        @foreach($config->getFillable() as $prop)
        <div class="form-field">
            {!! Form::label($prop); !!}
            {!! Form::text($prop); !!}
        </div>
        @endforeach 
        {!! Form::submit('Submit'); !!} 
    
    {!! Form::close() !!} 
@stop




