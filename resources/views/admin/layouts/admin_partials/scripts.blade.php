<!-- jQuery (required by Skodash plugins) -->
<script src="{{ asset('assets/backend') }}/assets/js/jquery.min.js"></script>
<!-- Bootstrap bundle JS -->
<script src="{{ asset('assets/backend') }}/assets/js/bootstrap.bundle.min.js"></script>
<!-- plugins -->
<script src="{{ asset('assets/backend') }}/assets/plugins/simplebar/js/simplebar.min.js"></script>
<script src="{{ asset('assets/backend') }}/assets/plugins/metismenu/js/metisMenu.min.js"></script>
<script src="{{ asset('assets/backend') }}/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
<script src="{{ asset('assets/backend') }}/assets/js/pace.min.js"></script>
<!-- app -->
<script src="{{ asset('assets/backend') }}/assets/js/app.js"></script>

<script>
    function initJewelryShopPlugins() {
        if (typeof bootstrap !== 'undefined') {
            document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach((el) => {
                const existing = bootstrap.Dropdown.getInstance(el);
                if (existing) existing.dispose();
                new bootstrap.Dropdown(el);
            });

            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((el) => {
                const existing = bootstrap.Tooltip.getInstance(el);
                if (existing) existing.dispose();
                new bootstrap.Tooltip(el);
            });
        }

        if (typeof window.jQuery !== 'undefined' && typeof window.jQuery.fn.metisMenu === 'function') {
            window.jQuery('#menu').metisMenu();
        }
    }

    document.addEventListener('DOMContentLoaded', initJewelryShopPlugins);
    document.addEventListener('livewire:navigated', initJewelryShopPlugins);
</script>

<script>
    window.addEventListener('confirm-delete', (event) => {
        const detail = event.detail?.[0] ?? event.detail ?? {};
        Swal.fire({
            title: detail.title || @json(__('messages.confirm_delete_title')),
            text: detail.text || @json(__('messages.confirm_delete_text')),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: detail.confirmText || @json(__('messages.confirm_delete_btn')),
            cancelButtonText: @json(__('messages.cancel')),
        }).then((result) => {
            if (result.isConfirmed && detail.method) {
                Livewire.dispatch(detail.method, detail.params || {});
            }
        });
    });
</script>

@stack('scripts')
