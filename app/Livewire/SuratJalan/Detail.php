<?php

namespace App\Livewire\SuratJalan;

use App\Models\SuratJalan;
use Livewire\Component;

class Detail extends Component
{
    public $suratjalan;
    public $title = 'Detail SuratJalan';    

    public function mount($id)
    {
        $this->suratjalan = SuratJalan::with('detailsj')->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.suratjalan.detail')->layout('layouts.app');
    }
}
