@extends('layouts.app')

@section('title', 'Data Surat Jalan')
@section('menuSuratJalan', 'active')

@section('content')
    @livewire('suratJalan.index')
@endsection