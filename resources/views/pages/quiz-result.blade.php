@extends('layouts.app')

@section('title', 'Your Scent Matches')

@section('description', 'Fragrances from the Ralph de Vinca catalogue matched to your taste, season, and the way you wear perfume.')

{{-- Results are personal to one visitor, so there is nothing to index. --}}
@section('robots', 'noindex, follow')

@section('content')

<x-quiz-result-hero :summary="$summary" :top-match="$topMatch" />

<x-quiz-result-matches :matches="$matches" />

@endsection
