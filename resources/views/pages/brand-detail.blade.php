@extends('layouts.app')

@section('title', $brand->name)

@section('description', Str::limit($brand->name.' — '.$brand->about_intro, 155))

@section('content')

<x-brand-detail-hero :brand="$brand" />

<x-brand-detail-about :brand="$brand" />

<x-brand-detail-history :brand="$brand" />

<x-brand-detail-philosophy :brand="$brand" />

<x-brand-detail-featured :brand="$brand" :perfumes="$perfumes" />

@endsection
