@extends('layouts.app')

@section('title','Articles')

{{-- Still in progress: keep it out of search results for now. --}}
@section('robots', 'noindex, nofollow')

@section('content')

<x-articles.hero/>
<x-articles.featured/>
<x-articles.latest/>
<x-articles.categories/>
<x-articles.editor-picks/>
<x-articles.cta/>

@endsection