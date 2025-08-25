@extends('layouts.app')

@section('title', 'Edit Surat Jalan')

@section('content')
    @livewire('suratjalan.edit', ['id' => $id])
@endsection