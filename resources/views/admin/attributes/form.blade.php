@extends('admin.layout') 
    
@section('content') 
    <h1>Create weapon form</h1>
    {!! Form::model($attribute, [
        'route' => ['admin.attributes.' . (isset($attribute->defindex) ? 'update' : 'store'), $attribute->defindex],
        'method' => (isset($attribute->defindex) ? 'PUT' : 'POST') 
    ]) !!}
    
        @foreach($attribute->getFillable() as $prop)
        <div class="form-field">
            {!! Form::label($prop); !!}
            {!! Form::text($prop); !!}
        </div>
        @endforeach 
        {!! Form::submit('Submit'); !!} 
    
    {!! Form::close() !!} 
@stop




