@extends('layouts.app')

@section('title','Home')

@section('content')

<x-hero
    :hero-perfume="$heroPerfume"
    :families="$families"
    :stats="$stats"
/>

<x-featured-categories :stats="$stats" />

<x-trending-perfumes :trending="$trending" />

<x-newest-perfumes :newest="$newest" />

<x-daily-spotlight
    :brand="$featuredBrand"
    :note="$featuredNote"
/>

<x-fragrance-academy />

<x-call-to-action :stats="$stats" />

@endsection
