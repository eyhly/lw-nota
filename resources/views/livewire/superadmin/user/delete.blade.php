<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button>

<!-- Modal -->
<div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">
        <i class="fas fa-trash mr-1"></i>    
        Hapus {{$title}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-4">
                    Nama
                </div>
                <div class="col-8">
                    : {{$nama}}
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    Email
                </div>
                <div class="col-8">
                    : {{$email}}
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    Role
                </div>
                <div class="col-8">
                    :
                    @if ($role=='Super Admin')
                        <span class="badge badge-info">
                             {{$role}}
                        </span>
                    @else 
                        <span class="badge badge-dark">
                             {{$role}}
                        </span>
                    @endif
                </div>
            </div>
        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i>
            Tutup</button>
        <button wire:click="destroy({{$user_id}})" type="button" class="btn btn-sm btn-danger">
            <i class="fas fa-trash mr-1"></i>
            Hapus</button>
      </div>
    </div>
  </div>
</div>