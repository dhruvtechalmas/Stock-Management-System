@extends('layouts.main')

@section('content')

    {{-- Add Supplier Modal --}}
    <div class="modal fade" id="supplierModal" tabindex="-1">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title btn btn-outline-secondary">
                        Add Supplier
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    @include('stocks.suppliers.create')
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
                        <i class="bi bi-truck"></i>
                    </span>

                    <div>
                        <p class="eyebrow mb-1">Master</p>
                        <h1 class="h3 mb-1">Suppliers</h1>
                    </div>

                </div>

                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#supplierModal">

                    <i class="bi bi-plus-circle"></i>
                    Add Supplier

                </button>

            </div>

            <section class="panel">

                <div class="panel-header">

                    <div class="d-flex align-items-center gap-3">

                        <input class="form-control form-control-sm table-search" type="search" placeholder="Search Supplier"
                            data-table-search="suppliersTable">

                    </div>

                    <a href="#" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-download"></i>
                        Export PDF
                    </a>

                </div>

                <div class="table-responsive">

                    <table class="table align-middle mb-0" id="suppliersTable" data-searchable-table>

                        <thead>

                            <tr>

                                <th>#</th>
                                <th>Supplier Name</th>
                                <th>Contact Person</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($suppliers as $supplier)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        {{ $supplier->name }}
                                    </td>

                                    <td>
                                        {{ $supplier->contact_person ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $supplier->phone ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $supplier->email ?? '-' }}
                                    </td>

                                    <td>

                                        <span class="badge {{ $supplier->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                                        </span>

                                    </td>

                                    <td style="white-space: nowrap;">

                                        <i class="bi bi-calendar3 text-primary me-2"></i>

                                        {{ $supplier->created_at->format('M d, Y') }}

                                    </td>

                                    <td style="white-space: nowrap;">

                                        <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editSupplierModal{{ $supplier->id }}">

                                            <i class="bi bi-pencil-square"></i>

                                        </button>

                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                                            class="d-inline delete-form">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-outline-danger btn-sm">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                        <a href="{{ route('suppliers.show', $supplier->id) }}"
                                            class="btn btn-outline-info btn-sm">

                                            <i class="bi bi-eye"></i>

                                        </a>

                                    </td>

                                </tr>

                                @include('stocks.suppliers.edit', [
                                    'supplier' => $supplier
                                ])

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="d-flex justify-content-end mt-3">

                    {{ $suppliers->links('pagination::bootstrap-4') }}

                </div>

            </section>

        </div>

    </main>
    {{-- Delete Confirmation --}}
        <script>
        document.querySelectorAll('.delete-form').forEach(form => {

            form.addEventListener('submit', function(e) {

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
    @if ($errors->any())
        <script>
          document.addEventListener('DOMContentLoaded', function () {
          const modal = new bootstrap.Modal(document.getElementById('supplierModal'));
        modal.show();
        });
        </script>
      @endif

             @if ($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function () {

                    const modal = new bootstrap.Modal(document.getElementById('supplierModal'));
                        modal.show();

                        document.getElementById('cancelSupplierBtn').addEventListener('click', function () {
                        window.location.href = "{{ route('suppliers.index') }}";
                        });

                });
                </script>
            @endif

@endsection