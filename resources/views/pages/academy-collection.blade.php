@extends('layouts.app')

@section('title', 'Fragrance Families')

@section('content')

    @include('components.academy-collection.hero')
    @include('components.academy-collection.about')
    @include('components.academy-collection.collection-types')
    @include('components.academy-collection.core-collection')
    @include('components.academy-collection.seasonal-rotation')
    @include('components.academy-collection.occasion-selection')
    @include('components.academy-collection.avoiding-duplication')
    @include('components.academy-collection.collection-size')
    @include('components.academy-collection.collection-philosophy')
    @include('components.academy-collection.next-level')

@endsection