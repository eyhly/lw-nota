@extends('layouts.app')

@section('title', 'Tambah Nota')
@section('menuCreate', 'active')

@section('content')
    @livewire('nota.create', ['id' => $id ?? null])
@endsection