@extends('layouts.app')

@section('title', 'Detail Surat Jalan')
@section('menuSuratJalan', 'active')

@section('content')
    @livewire('suratJalan.detail', ['id' => $id])
@endsection