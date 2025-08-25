<div>
    <div class="content-wrapper px-3">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $title }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">
                                    <i class="fas fa-home mr-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                <i class="fas fa-edit mr-1"></i> {{ $title }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <div class="container">
            <form wire:submit.prevent="update">
                <div class="mb-3">
                    <label>Coly</label>
                    <input type="number" class="form-control" wire:model="coly">
                    @error('coly') <span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="mb-3">
                    <label>Isi</label>
                    <input type="text" class="form-control" wire:model="isi">
                    @error('isi') <span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="mb-3">
                    <label>Nama Barang</label>
                    <input type="text" class="form-control" wire:model="nama_barang">
                    @error('nama_barang') <span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="mb-3">
                    <label>Total Coly</label>
                    <input type="number" class="form-control" wire:model="total_coly">
                    @error('total_coly') <span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <input type="text" class="form-control" wire:model="status">
                    @error('status') <span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('suratjalan.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
