<body>

    @yield('content')

    <!-- Data notifikasi dari Laravel -->
    <div
        id="travel-alert"
        hidden
        data-success="{{ session('success') }}"
        data-error="{{ session('error') }}"
        data-warning="{{ session('warning') }}"
        data-validation="{{ $errors->any() ? implode("\n", $errors->all()) : '' }}">
    </div>

    <!-- Library SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alertData = document.getElementById('travel-alert');

            if (!alertData || typeof Swal === 'undefined') {
                return;
            }

            let icon = '';
            let title = '';
            let message = '';

            if (alertData.dataset.success) {
                icon = 'success';
                title = 'Berhasil!';
                message = alertData.dataset.success;
            } else if (alertData.dataset.error) {
                icon = 'error';
                title = 'Gagal!';
                message = alertData.dataset.error;
            } else if (alertData.dataset.warning) {
                icon = 'warning';
                title = 'Peringatan!';
                message = alertData.dataset.warning;
            } else if (alertData.dataset.validation) {
                icon = 'error';
                title = 'Periksa Formulir!';
                message = alertData.dataset.validation;
            }

            if (message) {
                Swal.fire({
                    icon: icon,
                    title: title,
                    text: message,
                    confirmButtonColor: '#3285EB'
                });
            }
        });
    </script>

</body>

</html>