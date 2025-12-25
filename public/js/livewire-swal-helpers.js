/**
 * Livewire SweetAlert Global Handlers
 * Compatible with Livewire v3
 */

function initLivewireSwalHandlers(options = {}) {
    const config = {
        selectAllCheckboxId: 'selectAllCheckboxId',
        individualCheckboxSelector: 'input[type="checkbox"][wire\\:model\\.live="selectedIds"]',
        redirectUrl: null,
        deleteRedirectUrl: null,
        ...options
    };

    // =============================
    // DELETE MODAL
    // =============================
    document.addEventListener('livewire:init', () => {
        Livewire.on('closeDeleteModal', (event) => {
            $('#deleteModal').modal('hide');

            Swal.fire({
                title: 'Berhasil!',
                text: 'Data berhasil dihapus',
                icon: 'success'
            }).then((result) => {
                if (result.isConfirmed && config.deleteRedirectUrl) {
                    window.location.href = config.deleteRedirectUrl;
                }
            });
        });

        // =============================
        // ALERT (SUCCESS / ERROR)
        // =============================
        Livewire.on('alert', (event) => {
            // Handle data dari Livewire v3
            const data = Array.isArray(event) ? event[0] : event;

            // Reset checkbox
            const selectAll = document.getElementById(config.selectAllCheckboxId);
            if (selectAll) {
                selectAll.checked = false;
            }

            // Uncheck individual checkboxes
            document
                .querySelectorAll(config.individualCheckboxSelector)
                .forEach(cb => {
                    cb.checked = false;
                });

            // Close loading jika ada
            Swal.close();

            // Tampilkan alert
            Swal.fire({
                title: data.type === 'success' ? 'Berhasil!' : 'Gagal!',
                html: data.message || data.text,
                icon: data.type,
            }).then(() => {
                if (data.type === 'success' && config.redirectUrl) {
                    window.location.href = config.redirectUrl;
                }
            });
        });

        // =============================
        // BULK ACTION CONFIRMATION
        // =============================
        Livewire.on('confirm-bulk-action', (event) => {
            const data = Array.isArray(event) ? event[0] : event;
            const action = data.action || 'aksi ini';

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
                    // Reset bulkAction via Alpine/Livewire
                    if (window.Livewire) {
                        // Cari component yang aktif
                        const components = window.Livewire.all();
                        components.forEach(component => {
                            if (component.get && typeof component.get('bulkAction') !== 'undefined') {
                                component.set('bulkAction', '');
                            }
                        });
                    }
                    return;
                }

                // Tampilkan loading
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                Livewire.dispatch('run-bulk-action');

            });
        });

        // =============================
        // SUCCESS ALERT (untuk create/update)
        // =============================
        Livewire.on('showSuccessAlert', (event) => {
            const data = Array.isArray(event) ? event[0] : event;

            Swal.fire({
                title: data.title || 'Berhasil!',
                text: data.message || 'Data berhasil disimpan!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed && config.redirectUrl) {
                    window.location.href = config.redirectUrl;
                }
            });
        });

        // =============================
        // NOTA UPDATED (untuk detail page)
        // =============================
        Livewire.on('notaUpdated', (event) => {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Nota berhasil diperbarui.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed && config.redirectUrl) {
                    window.location.href = config.redirectUrl;
                }
            });
        });
    });
}

// Helper untuk simple alerts
const SwalHelper = {
    success: (title, text) => {
        return Swal.fire({
            title: title || 'Berhasil!',
            text: text,
            icon: 'success'
        });
    },

    error: (title, text) => {
        return Swal.fire({
            title: title || 'Gagal!',
            text: text,
            icon: 'error'
        });
    },

    confirm: (title, text, confirmText = 'Ya', cancelText = 'Batal') => {
        return Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
        });
    },

    loading: (title = 'Memproses...', text = 'Mohon tunggu') => {
        Swal.fire({
            title: title,
            text: text,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    },
    close: () => {
        Swal.close();
    }
};