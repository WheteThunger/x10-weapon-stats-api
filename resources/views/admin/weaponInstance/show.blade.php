@extends('admin.layout')

@section('content')
    <div>
        {!! \HTML::displayField('Config Name', $weaponInstance->config->name) !!}

        {!! \HTML::displayField('Person Group Name', $weaponInstance->person->name) !!}

        {!! \HTML::displayField('Weapon Name', $weaponInstance->weapon->name) !!}

        <br>
        <br>
        <div>Attributes</div>
        <br>
        @foreach($weaponInstance->weaponInstanceAttributes as $weaponInstanceAttribute)
            <div>
                {{ $weaponInstanceAttribute->attribute->name }}
            </div>
            <div>
                {{ $weaponInstanceAttribute->attribute_value }}
            </div>
        @endforeach


    </div>
@stop