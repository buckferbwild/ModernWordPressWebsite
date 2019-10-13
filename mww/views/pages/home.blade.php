@extends('layouts.main')

@section('content')
    <h1>Modern WordPress Website (MWW) is working!</h1>
    <p>You are in: Home</p>
    
    <hr>

    @loop
        <h3>{{ get_the_title() }}</h3>
        {!! get_the_content() !!}
    @endloop
@endsection
