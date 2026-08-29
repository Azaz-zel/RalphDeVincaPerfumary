@extends('layouts.app')

@section('title', 'Introduction to Perfume')

@section('content')

    @include('components.academy-seasons.hero')
    @include('components.academy-seasons.about')
    @include('components.academy-seasons.season-guide')
    @include('components.academy-seasons.occasion-guide')
    @include('components.academy-seasons.climate')
    @include('components.academy-seasons.recommendations')
    @include('components.academy-seasons.next-level')

@endsection