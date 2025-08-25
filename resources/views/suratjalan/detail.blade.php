@extends('layouts.app')

@section('title', 'Detail Surat Jalan')
@section('menuSuratJalan', 'active')

@section('content')
    @livewire('suratjalan.detail', ['id' => $id])
@endsection