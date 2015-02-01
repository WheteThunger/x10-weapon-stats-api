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

@foreach($attributes as $attribute)
    <div class="list-item">
        <div class="left-section">
            <img src="{{ $attribute->image_url }}">
        </div>
        <div class="right-section">
            <div class="title">
                <a href="{{ route('admin.attributes.show', ['id' => $attribute->defindex]) }}">
                    {{ $attribute->name }}
                </a>
            </div>
            @foreach(['defindex','name','attribute_class'] as $prop)
            <div class="field">
                <div class="field-name">
                    {{ $prop }}
                </div>
                <div class="field-value">
                    {{ $attribute->$prop }}
                </div>
            </div>
        @endforeach
        </div>
    </div>
@endforeach

@stop