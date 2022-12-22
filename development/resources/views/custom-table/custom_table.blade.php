@extends('layouts.app')

@section('title', $customTable->name)

@section('contents')
    <app-custom-table-dynamic :custom-table="{{ $customTable }}"></app-custom-table-dynamic>
@endsection