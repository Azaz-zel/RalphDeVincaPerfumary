@extends('layouts.app')

@section('title', 'Explore')

@section('content')

<x-explore-hero />

<x-explore-filter :search="$search" />

<div id="explore-results">

    <x-explore-grid
        :perfumes="$perfumes"
        :search="$search"
    />

</div>

<div
    id="explore-empty"
    class="{{ $perfumes->isEmpty() ? '' : 'hidden' }}"
>
    <x-explore-empty />
</div>

<div id="explore-pagination">

    <x-explore-pagination
        :perfumes="$perfumes"
    />

</div>

@endsection