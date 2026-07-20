@extends('layouts.main')

@section('content')

    {{-- Add Material Modal --}}
    <div class="modal fade" id="wastageModal" tabindex="-1">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title btn btn-outline-secondary">
                        Add Wastage
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    @include('stocks.wastages.create')
                </div>

            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <main class="dashboard-content">

        <div class="container-fluid px-3 px-lg-4 py-4">

            <div class="page-heading">

                <div class="page-heading-copy">

                    <span class="page-icon">
                        <i class="bi bi-box-seam"></i>
                    </span>

                    <div>
                        <p class="eyebrow mb-1">Trasactions</p>
                        <h1 class="h3 mb-1">Wastage</h1>
                    </div>

                </div>

                @role('Kitchen Staff')
                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#wastageModal">

                    <i class="bi bi-plus-circle"></i>
                    Add Wastage

                </button>
                @endrole

            </div>

            <section class="panel">

                <div class="panel-header">

                    <div class="d-flex align-items-center gap-3">

                        <input class="form-control form-control-sm table-search" type="search" placeholder="Search Wastage"
                            data-table-search="wastagesTable">

                    </div>
                    {{-- <a href="#" class="btn btn-outline-secondary btn-sm ">
                        <i class="bi bi-download"></i> Export PDF
                    </a> --}}
                </div>


                <div class="table-responsive">
                    <table class="table align-middle mb-0" id="wastagesTable" data-searchable-table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Wastage No.</th>
                                <th>Record By</th>
                                <th>Material</th>
                                <th>Qty</th>
                                <th>Wastsge Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($wastages as $wastage)

                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    {{-- Wastage No --}}
                                    <td>
                                        {{ $wastage->wastage_no ?? 'WS-' . str_pad($wastage->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>

                                    {{-- Recorded By --}}
                                    <td>
                                        {{ $wastage->recordedBy->name ?? '-' }}
                                    </td>

                                    {{-- Material --}}
                                    <td>
                                        {{ $wastage->material->material_name ?? '-' }}
                                    </td>

                                    {{-- Quantity --}}
                                    <td>
                                        <span class="badge bg-danger">
                                            {{ number_format($wastage->quantity, 3) }}
                                            {{ $wastage->material->unit ?? '' }}
                                        </span>
                                    </td>

                                    {{-- Reason --}}
                                    {{-- <td>
                                        {{ $wastage->reason }}
                                    </td> --}}

                                    {{-- Wastage Date --}}
                                    <td style="white-space: nowrap;">
                                        {{ \Carbon\Carbon::parse($wastage->wastage_date)->format('d M Y') }}
                                    </td>

                                    {{-- Created At --}}
                                    {{-- <td style="white-space: nowrap;">
                                        <i class="bi bi-calendar3 text-primary me-2"></i>
                                        {{ $wastage->created_at->format('d M Y') }}
                                    </td> --}}

                                    {{-- Action --}}
                                    <td style="white-space: nowrap;">

                                        @can('wastage.view')
                                            <a href="{{ route('wastages.show', $wastage->id) }}"
                                                class="btn btn-outline-info btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endcan

                                        @role('Kitchen Staff')
                                        <form action="{{ route('wastages.destroy', $wastage->id) }}" method="POST"
                                            class="d-inline delete-form">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-outline-danger btn-sm">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                        @endrole

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="9" class="text-center text-muted py-4">

                                        No wastage records found.

                                    </td>

                                </tr>

                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination controls --}}
                <div class="d-flex justify-content-end mt-3">
                    {{ $wastages->links('pagination::bootstrap-4') }}
                </div>
            </section>
        </div>
    </main>


    <script>
        document.querySelectorAll('.delete-form').forEach(form => {

            form.addEventListener('submit', function (e) {

                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to recover this record!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete it!'
                }).then((result) => {

                    if (result.isConfirmed) {
                        form.submit();
                    }

                });

            });

        });
    </script>

    {{-- In show only model form error --}}
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = new bootstrap.Modal(document.getElementById('wastageModal'));
                modal.show();
            });
        </script>
    @endif

    <script>
         document.addEventListener('DOMContentLoaded', function () {

                document.querySelectorAll('.modal').forEach(function (modal) {

                    modal.addEventListener('hidden.bs.modal', function () {

                        if (
                            window.location.search ||
                            document.querySelector('.invalid-feedback')
                        ) {
                            window.location.href = "{{ route('wastages.index') }}";
                        }

                    });

                });

            });
        document.addEventListener('DOMContentLoaded', function () {

            const wastageIndexUrl = "{{ route('wastages.index') }}";

            document.querySelectorAll('#cancelWastageBtn, #wastageModal .btn-close').forEach(function (button) {

                button.addEventListener('click', function () {

                    window.location.href = wastageIndexUrl;

                });

            });

        });
    </script>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {

            const modal = document.getElementById('wastageModal');

            if (modal) {

                modal.addEventListener('hidden.bs.modal', function () {

                    window.location.href = "{{ route('wastages.index') }}";

                });

            }

        });
    </script> --}}
@endsection