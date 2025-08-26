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
                    Pembeli
                </div>
                <div class="col-8">
                    : {{$pembeli}}
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    Tanggal
                </div>
                <div class="col-8">
                    : {{$tanggal}}
                </div>
            </div>
        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i>
            Tutup</button>
        <button wire:click="destroy({{$suratjalan_id}})" type="button" class="btn btn-sm btn-danger">
            <i class="fas fa-trash mr-1"></i>
            Hapus</button>
      </div>
    </div>
  </div>
</div>