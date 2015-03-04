@extends('admin.layout') 
    
@section('content') 
    <h1>Create Weapon Instance</h1>
    {!! Form::model($weaponInstance, [
        'route' => ['admin.weapon-instance.show', $weaponInstance->id],
        'method' => 'POST'
    ]) !!}

        <div class="form-field">
            {!! Form::label('config'); !!}
            {!! $configSelect !!}
        </div>
        
        <div class="form-field">
            {!! Form::label('person group'); !!}
            {!! $personSelect !!}
        </div>
        
        <div class="form-field">
            {!! Form::label('weapon'); !!}
            {!! $weaponSelect !!}
        </div>
        
        @foreach($attributeSelectArr as $output)
            <div class="form-field">
                {!! $output !!}
            </div>
        @endforeach
        
    
        {{--@foreach($weaponInstance->getFillable() as $prop)
        <div class="form-field">
            {!! Form::label($prop); !!}
            {!! Form::text($prop); !!}
        </div>
        @endforeach --}}
        {!! Form::submit('Submit'); !!} 
    
    {!! Form::close() !!} 
@stop