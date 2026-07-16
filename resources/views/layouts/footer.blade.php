<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>

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
        minDate: "today",        //  future dates
        defaultDate: "{{ old('purchase_date', now()->format('Y-m-d')) }}"
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        flatpickr("#request_date", {
            altInput: true,
            altFormat: "d M Y",
            dateFormat: "Y-m-d",
            minDate: "today",        // future dates
            defaultDate: "{{ old('request_date') }}"
        });

    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        flatpickr("#wastage_date", {
            altInput: true,
            altFormat: "d M Y",      // Display: 15 Jul 2026
            dateFormat: "Y-m-d",     // Send to Laravel: 2026-07-15
            defaultDate: "{{ old('wastage_date', now()->format('Y-m-d')) }}",
            maxDate: "today",        // Prevent future dates
            allowInput: false
        });

        flatpickr("#from_date", {
            altInput: true,
            altFormat: "d M Y",
            dateFormat: "Y-m-d",
            allowInput: true
        });

        flatpickr("#to_date", {
            altInput: true,
            altFormat: "d M Y",
            dateFormat: "Y-m-d",
            allowInput: true
        });

    });
</script>

<script>

    document.addEventListener('DOMContentLoaded', function () {

        if (document.querySelector('#material_id')) {
            new TomSelect('#material_id', {
                create: false,
                allowEmptyOption: true,
                placeholder: 'Select Material',
                maxOptions: 500,
                searchField: ['text'],
            });
        }

        if (document.querySelector('#category_id')) {
            new TomSelect('#category_id', {
                create: false,
                allowEmptyOption: true,
                placeholder: 'Select Category',
                maxOptions: 500,
                searchField: ['text'],
            });
        }

    });
</script>

</body>

</html>