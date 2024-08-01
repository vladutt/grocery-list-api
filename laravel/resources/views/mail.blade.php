@extends('layout')

@section('title', 'Welcome to Our Service')

@section('header')
    Welcome to Our Service
@endsection

@section('content')
    <p>Hi yo,</p>
    <p>Thank you for signing up for our service. We are excited to have you on board.</p>
    <p>Feel free to explore and let us know if you have any questions.</p>
    @endsection

    @section('footer')
        &copy; {{ date('Y') }} Our Service. All rights reserved.
@endsection
