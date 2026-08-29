@extends('layouts.app')

@section('title', 'Fragrance Families')

@section('content')

    @include('components.academy-apply.hero')
    @include('components.academy-apply.about')
    @include('components.academy-apply.where-to-apply')
    @include('components.academy-apply.how-much')
    @include('components.academy-apply.spraying-technique')
    @include('components.academy-apply.pulse-points')
    @include('components.academy-apply.common-mistakes')
    @include('components.academy-apply.next-level')

@endsection