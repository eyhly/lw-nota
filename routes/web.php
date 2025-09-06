<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Nota\Detail;
use App\Livewire\SuratJalan\Detail as DetailSurat;
use App\Livewire\Nota\Create;
use App\Models\Nota;

Route::get('/', function () {
    return view('welcome');
});

//nota
Route::view('nota/', 'nota.index')->name('nota.index');
Route::view('create', 'nota.create')->name('nota.create');
// Buat nota dari surat
Route::get('/nota/create/from-surat/{id}', function ($id) {
    return view('nota.create', ['id' => $id]);
})->name('nota.create.fromSurat');

Route::get('/nota/{id}/detail', function ($id) {return view('nota.detail', ['id' => $id]);})->name('nota.detail');

//pdf
// Route::get('/print/{id}', function ($id) {return view('pdf.index', ['id' => $id]);})->name('pdf.index');
Route::get('/nota/print/{id}', [Detail::class, 'pdf'])->name('pdf.index');
Route::get('/surat/print/{id}', [DetailSurat::class, 'pdf'])->name('pdf.surat');

// //surat jalan
Route::view('surat-jalan/', 'suratjalan.index')->name('suratjalan.index');
Route::view('surat-jalan/create', 'suratjalan.create')->name('suratjalan.create');
Route::view('surat-jalan/{id}/edit', 'suratjalan.edit')->name('suratjalan.edit');
Route::get('/surat-jalan/{id}/detail', function ($id) {return view('suratjalan.detail', ['id' => $id]);})->name('suratjalan.detail');


Route::view('superadmin/user/', 'superadmin.user.index')->name('superadmin.user.index');

Route::view('superadmin/kategori/index', 'superadmin.kategori.index')->name('superadmin.kategori.index');

Route::view('superadmin/barang/index', 'superadmin.barang.index')->name('superadmin.barang.index');
