@extends('layouts.app')

@section('title', 'Find Your Scent')

@section('description', 'Answer five short questions and discover which fragrances from our catalogue suit your taste, your season, and the way you live.')

@section('content')

<x-quiz-hero />

<x-quiz-form :questions="$questions" />

@endsection
