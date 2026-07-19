@extends('layouts.home', ['title' => 'Smart Digital Land Record System'])

@section('content')
    @include('home.partials.hero')
    @include('home.partials.search')
    @include('home.partials.services')
    @include('home.partials.notices')
    @include('home.partials.workflow')
    @include('home.partials.contact')
@endsection
