<?php

namespace App\Livewire\Nota;

use App\Models\Nota;
use Livewire\Component;

class Detail extends Component
{
    public $nota;
    public $title = 'Detail Nota';    

    public function mount($id)
    {
        $this->nota = Nota::with('details')->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.nota.detail')->layout('layouts.app');
    }
}
