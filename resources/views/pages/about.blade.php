@extends('layouts.app')

@section('title', 'About')

@section('content')

    @include('components.about.hero')
    @include('components.about.story')
    @include('components.about.philosophy')
    @include('components.about.what-youll-find')
    @include('components.about.approach')
    @include('components.about.future')
    @include('components.about.cta')

@endsection