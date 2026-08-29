@extends('layouts.app')

@section('title', $note->name)

@section('description', Str::limit($note->name.' in perfumery — '.$note->description, 155))

@section('content')

<x-note-detail-hero :note="$note" />

<x-note-detail-about :note="$note" />

<x-note-detail-profile :note="$note" />

<x-note-detail-characteristics :note="$note" />

<x-note-detail-featured :note="$note" />

@endsection