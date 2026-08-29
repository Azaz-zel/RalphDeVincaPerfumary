@extends('layouts.app')

@section('title', 'Introduction to Perfume')

@section('content')

    @include('components.academy-concentration.hero')
    @include('components.academy-concentration.about')
    @include('components.academy-concentration.comparison')
    @include('components.academy-concentration.concentration-scale')
    @include('components.academy-concentration.misconceptions')
    @include('components.academy-concentration.choosing')
    @include('components.academy-concentration.next-level')

@endsection