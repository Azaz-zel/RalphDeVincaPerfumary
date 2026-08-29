@extends('layouts.app')

@section('title', 'Introduction to Perfume')

@section('content')

    @include('components.academy-notes.hero')
    @include('components.academy-notes.about')
    @include('components.academy-notes.top-notes')
    @include('components.academy-notes.middle-notes')
    @include('components.academy-notes.base-notes')
    @include('components.academy-notes.pyramid')
    @include('components.academy-notes.examples')
    @include('components.academy-notes.featured-lessons')

@endsection