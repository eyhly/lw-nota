@extends('layouts.app')

@section('title', 'Detail Nota')
@section('menu', 'active')

@section('content')
    @livewire('nota.detail', ['id' => $id])
@endsection