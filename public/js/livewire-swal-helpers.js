/**
 * Livewire SweetAlert Global Handlers
 */

function initLivewireSwalHandlers(options = {}) {
    const config = {
        selectAllCheckboxId: 'selectAllCheckbox',
        individualCheckboxSelector: 'input[type="checkbox"][wire\\:model\\.live="selectedIds"]',
        ...options
    };

    // =============================
    // DELETE MODAL
    // =============================
    Livewire.on('closeDeleteModal', () => {
        $('#deleteModal').modal('hide');

        Swal.fire({
            title: 'Berhasil!',
            text: 'Data berhasil dihapus',
            icon: 'success'
        });
    });

    // =============================
    // ALERT (SUCCESS / ERROR)
    // =============================
    Livewire.on('alert', (payload) => {
        const data = payload[0] || payload;

        // reset checkbox
        const selectAll = document.getElementById(config.selectAllCheckboxId);
        if (selectAll) selectAll.checked = false;

        document
            .querySelectorAll(config.individualCheckboxSelector)
            .forEach(cb => cb.checked = false);

        Swal.fire({
            title: data.type === 'success' ? 'Berhasil!' : 'Gagal!',
            text: data.message,
            icon: data.type,
        });
    });

    // =============================
    // BULK ACTION CONFIRMATION
    // =============================
    Livewire.on('confirm-bulk-action', (payload) => {
        const action = payload.action || payload[0]?.action || 'aksi ini';

        Swal.fire({
            title: 'Konfirmasi',
            html: `Apakah ingin melakukan aksi <b>${action}</b>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, lanjutkan',
            cancelButtonText: 'Batal',
            allowOutsideClick: false,
        }).then((result) => {

            if (!result.isConfirmed) {
                Livewire.find(payload.componentId)
                    ?.set('bulkAction', '');
                return;
            }

            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            Livewire.dispatch('runBulkAction');
        });
    });
}
