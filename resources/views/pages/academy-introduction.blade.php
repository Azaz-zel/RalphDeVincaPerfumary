@extends('layouts.app')

@section('title', 'Introduction to Perfume')

@section('content')

    @include('components.academy-introduction.hero')
    @include('components.academy-introduction.about')
    @include('components.academy-introduction.history')
    @include('components.academy-introduction.perfume-structure')
    @include('components.academy-introduction.creation-process')
    @include('components.academy-introduction.featured-lessons')

@endsection