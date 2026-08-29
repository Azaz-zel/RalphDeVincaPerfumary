@extends('layouts.app')

@section('title', $perfume->name)

@section('description', Str::limit($perfume->brand?->name.' '.$perfume->name.' — '.$perfume->description, 155))

@section('content')

    <x-perfume-detail-hero :perfume="$perfume" />

    <x-perfume-detail-overview :perfume="$perfume" />

    <x-perfume-detail-scent-profile :perfume="$perfume" />

    <x-perfume-detail-perfect-for :perfume="$perfume" />

    <x-perfume-detail-notes :perfume="$perfume" />

    <x-perfume-detail-pros-cons :perfume="$perfume" />

    <x-perfume-detail-about :perfume="$perfume" />

    <x-perfume-detail-similar
    :perfume="$perfume"
    :similar-perfumes="$similarPerfumes"
    />

@endsection