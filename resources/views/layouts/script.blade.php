<!-- jQuery -->
<script data-navigate-once src="{{ asset('adminlte3/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script data-navigate-once src="{{ asset('adminlte3/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script data-navigate-once src="{{ asset('adminlte3/dist/js/adminlte.min.js')}}"></script>

<!-- sweatalert2 -->
<script src="{{ asset('sweatalert2/dist/sweetalert2.all.min.js') }}"></script>
   
<!-- sweatalert helpers -->
<script src="{{ asset('js/livewire-swal-helpers.js') }}"></script>

<script>
    // Init saat pertama load
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof initLivewireSwalHandlers === 'function') {
            initLivewireSwalHandlers();
        }
    });

    // Re-init saat navigasi Livewire (jika pakai wire:navigate)
    document.addEventListener('livewire:navigated', function() {
        if (typeof initLivewireSwalHandlers === 'function') {
            initLivewireSwalHandlers();
        }
    });
</script>


<!-- bulk action -->
 <script>
document.getElementById('checkAll').addEventListener('change', function () {
document.querySelectorAll('.item-checkbox').forEach(cb => {
        cb.checked = this.checked;
    });
});
</script>

<script>
document.getElementById('bulkForm').addEventListener('submit', function(e) {
    if (document.querySelectorAll('.item-checkbox:checked').length === 0) {
        e.preventDefault();
        alert('Pilih minimal satu data!');
    }
});
</script>
