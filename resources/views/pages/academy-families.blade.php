@extends('layouts.app')

@section('title', 'Fragrance Families')

@section('content')

    @include('components.academy-families.hero')
    @include('components.academy-families.about')
    @include('components.academy-families.main-families')
    @include('components.academy-families.sub-families')
    @include('components.academy-families.fragrance-wheel')
    @include('components.academy-families.common-notes')
    @include('components.academy-families.personality')
    @include('components.academy-families.featured-lessons')

@endsection