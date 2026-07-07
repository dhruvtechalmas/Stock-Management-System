<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('/assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/assets/js/main.js') }}"></script>

@if(session('message'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: "{{ session('alert-type', 'success') }}",
                title: "{{ session('message') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,

                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

        });
    </script>
@endif

<script>
    window.adminHMDUser = {
        name: @json(auth()->user()->name),
        role: @json(auth()->user()->roles->first()?->name ?? 'Kitchen Staff'),
        avatar: @json(asset('assets/images/avatar/avatar-2.jpg'))
    };
</script>

<!-- flatpickr for date -->

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    flatpickr("#purchase_date", {
        altInput: true,
        altFormat: "d M Y",   // User sees: 15 Jul 2026
        dateFormat: "Y-m-d",  // Laravel receives: 2026-07-15
        defaultDate: "{{ old('purchase_date', now()->format('Y-m-d')) }}"
    });
</script>

</body>

</html>