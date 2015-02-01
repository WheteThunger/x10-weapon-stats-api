@extends('admin.layout') 
    
@section('content') 
    <h1>Create weapon form</h1>
    {!! Form::model($person, [
        'route' => ['admin.people.' . (isset($person->id) ? 'update' : 'store'), $person->id],
        'method' => (isset($person->id) ? 'PUT' : 'POST') 
    ]) !!}
    
        @foreach($person->getFillable() as $prop)
        <div class="form-field">
            {!! Form::label($prop); !!}
            {!! Form::text($prop); !!}
        </div>
        @endforeach 
        {!! Form::submit('Submit'); !!} 
    
    {!! Form::close() !!} 
@stop




