@extends('layouts.app')

@section('title', trans('default.payment_method'))

@section('contents')

    {{--    @if(isset($id)) selected-url="{{'posts/'.$id}}" @endif--}}
    <payment-view @if(session()->has('message')) message="{{session()->get('message')}}" @endif></payment-view>
@endsection
