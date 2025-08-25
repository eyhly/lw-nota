<?php

namespace App\Livewire\Superadmin\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    use withPagination;
    protected $paginationTheme='bootstrap';
    public $paginate='10';
    public $search='';

    public $nama,$email,$role,$password,$password_confirmation,$user_id;

    public function render()
    {
        $data = array(
            'title' => 'Data User',
            'user' => User::where('nama', 'like','%'.$this->search.'%')
            ->orWhere('email', 'like','%'.$this->search.'%')
            ->orderBy('role', 'asc')->paginate($this->paginate),
        );
        return view('livewire.superadmin.user.index', $data);
    }

    public function create(){
        $this->resetValidation();
        $this->reset([
            'nama',
            'email',
            'role',
            'password',
            'password_confirmation',
        ]);
    }

    public function store(){
        $this->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'role' => 'required',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required'
        ],
    [
        'nama.required' => 'Nama Tidak Boleh Kosong',
        'email.required' => 'Email Tidak Boleh Kosong',
        'email.email' => 'Email Tidak Valid',
        'email.unique' => 'Email Sudah Terdaftar',
        'role.required' => 'Role Tidak Boleh Kosong',
        'password.required' => 'Password Tidak Boleh Kosong',
        'password.min' => 'Password Minimal 8 Karakter',
        'password.confirmed' => 'Password Konfirmasi Harus Sama',
        'password_confirmation.required' => 'Password Tidak Boleh Kosong',
    ]);

    $user = new User;
    $user->nama = $this->nama;
    $user->email = $this->email;
    $user->role = $this->role;
    $user->password = Hash::make($this->password);
    $user->save();

    $this->dispatch('closeCreateModal'); 

    }

    public function edit($id){
        $this->resetValidation();
        $user = User::findOrFail($id);
        $this->nama = $user->nama;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = '';
        $this->password_confirmation = '';
        $this->user_id = $user->id;
    }

    public function update($id){
        $user = User::findOrFail($id);

        $this->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required',
            'password' => 'nullable|min:8|confirmed',
        ],
    [
        'nama.required' => 'Nama Tidak Boleh Kosong',
        'email.required' => 'Email Tidak Boleh Kosong',
        'email.email' => 'Email Tidak Valid',
        'email.unique' => 'Email Sudah Terdaftar',
        'role.required' => 'Role Tidak Boleh Kosong',
        'password.min' => 'Password Minimal 8 Karakter',
        'password.confirmed' => 'Password Konfirmasi Harus Sama',

    ]);

    $user->nama = $this->nama;
    $user->email = $this->email;
    $user->role = $this->role;
    if ($this->password){
        $user->password = Hash::make($this->password);
    };
    $user->save();

    $this->dispatch('closeEditModal'); 

    }

    public function confirm($id){
        $user = User::findOrFail($id);

        $this->nama = $user->nama;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->user_id = $user->id;
    }

    public function destroy($id){
        $user = User::findOrFail($id);
        $user->delete();
        $this->dispatch('closeDeleteModal'); 
    }
    
}
