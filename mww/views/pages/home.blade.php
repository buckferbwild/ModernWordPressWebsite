@extends('layouts.main')

@section('content')
    <h1>Modern WordPress Website (MWW) is working!</h1>
    <p>You are in: Home</p>

    <hr>

    @forelse ($home_posts as $post)
        {!! $post !!}
    @empty
        <p>{{ __('No posts to show at the moment.') }}</p>
    @endforelse
@endsection
