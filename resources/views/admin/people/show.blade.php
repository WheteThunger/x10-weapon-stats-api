@extends('admin.layout')

@section('content')
<style>
.item {
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
<div class="item">
    <div class="right-section">
        <div class="title">
            <a href="{{ route('admin.people.show', ['id' => $person->id]) }}">
                {{ $person->item_name }}
            </a>
        </div>

       @foreach($person->getFillable() as $prop)
            <div class="field">
                <div class="field-name">
                    {{ $prop }}
                </div>
                <div class="field-value">
                    {{ $person->$prop }}
                </div>
            </div>
        @endforeach
   		<div>
   			<a href="{{ route('admin.people.edit', ['id' => $person->id]) }}">Edit</a>
        </div>
    </div>
</div>
@stop