@extends('admin.layout') 
    
@section('content')

<style>
.list-item {
    background-color: rgb(240, 240, 240);
    padding: 0.25em;
    border-radius: 1em;
    margin: 0.25em;
    display: flex;
    flex-direction: row;
}

.left-section {
	min-width: 128px;
}

.title {
    font-size: 1.4em;
}

.field {
    display: flex;
}

.field-name {
    width: 9em;
}

</style>

@foreach($weapons as $weapon)
    <div class="list-item">
        <div class="left-section">
            <img src="{{ $weapon->image_url }}">
        </div>
        <div class="right-section">
            <div class="title">
                <a href="{{ route('admin.weapons.show', ['id' => $weapon->defindex]) }}">
                    {{ $weapon->item_name }}
                </a>
            </div>
            @foreach(['defindex','item_class','item_type_name'] as $prop)
            <div class="field">
                <div class="field-name">
                    {{ $prop }}
                </div>
                <div class="field-value">
                    {{ $weapon->$prop }}
                </div>
            </div>
        @endforeach
        </div>
    </div>
@endforeach

@stop