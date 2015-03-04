@extends('admin.layout')

@section('content')
    {!! Form::model($weaponInstance, [
        'route' => ['admin.weapon-instance.update', $weaponInstance->id],
        'method' => 'POST'
    ]) !!}

    <h1>Edit for {{ $weaponInstance->weapon->name }}</h1>

    <div class="form-field">
        {!! $configSelect !!}
    </div>

    <div class="form-field">
        {!! Form::label('person group'); !!}
        {!! $personSelect !!}
    </div>

    {!! Form::close() !!}
@stop