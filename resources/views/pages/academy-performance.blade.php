@extends('layouts.app')

@section('title', 'Introduction to Perfume')

@section('content')

    @include('components.academy-performance.hero')
    @include('components.academy-performance.about')
    @include('components.academy-performance.performance-pillars')
    @include('components.academy-performance.longevity')
    @include('components.academy-performance.projection')
    @include('components.academy-performance.sillage')
    @include('components.academy-performance.factors')
    @include('components.academy-performance.tips')
    @include('components.academy-performance.next-level')

@endsection