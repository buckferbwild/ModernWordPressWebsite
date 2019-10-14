@extends('layouts.main')

@section('content')
    @loop
    <h1>{{ get_the_title() }}</h1>
    {!! get_the_content() !!}
    @endloop
@endsection
