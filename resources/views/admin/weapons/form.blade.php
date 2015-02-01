@extends('admin.layout') 
    
@section('content') 
    <h1>Create weapon form</h1>
    {!! Form::model($weapon, [
        'route' => ['admin.weapons.' . (isset($weapon->defindex) ? 'update' : 'store'), $weapon->defindex],
        'method' => (isset($weapon->defindex) ? 'PUT' : 'POST') 
    ]) !!}
    
        @foreach($weapon->getFillable() as $prop)
        <div class="form-field">
            {!! Form::label($prop); !!}
            {!! Form::text($prop); !!}
        </div>
        @endforeach 
        {!! Form::submit('Submit'); !!} 
    
    {!! Form::close() !!} 
@stop




