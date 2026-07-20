@extends('layouts.main')

@section('content')

    {{-- Hidden template of material <option>s, read by the shared "Add Item" JS
        for every form on this page instead of re-rendering per modal. --}}
        <div id="materialOptionsTemplate" class="d-none">
            @foreach($materials as $material)
                <option value="{{ $material->id }}" data-unit="{{ $material->unit }}">
                    {{ $material->material_name }}
                </option>
            @endforeach
    </div>

    {{-- Add Purchase Modal --}}
    <div class="modal fade" id="purchaseModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Purchase</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('stocks.purchase.create')
                </div>
            </div>
        </div>
    </div>

    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">

            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-cart-check"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Transaction</p>
                        <h1 class="h3 mb-1">Purchases</h1>
                    </div>
                </div>

                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#purchaseModal">
                    <i class="bi bi-plus-circle"></i> Add Purchase
                </button>
            </div>

            <section class="panel">
                <div class="panel-header">
                    <div class="d-flex align-items-center gap-3">
                        <input class="form-control form-control-sm table-search" type="search" placeholder="Search Purchase"
                            data-table-search="purchaseTable">
                    </div>
                    {{-- <a href="#" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-download"></i> Export PDF
                    </a> --}}
                </div>

                <div class="table-responsive">
                    {{-- ONLY <tr> content lives inside this table now. No modal
                        markup, no <div>, nothing else - that's what was
                            corrupting the layout before. --}}
                            <table class="table align-middle mb-0" id="purchaseTable" data-searchable-table>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Purchase No</th>
                                        <th>Supplier</th>
                                        <th>Invoice No</th>
                                        <th>Purchase Date</th>
                                        <th>Total Amount</th>
                                        <th>Created By</th>
                                        {{-- <th>Created At</th> --}}
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($purchases as $purchase)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><strong>{{ $purchase->purchase_no }}</strong></td>
                                            <td>{{ $purchase->supplier->name ?? '-' }}</td>
                                            <td>{{ $purchase->invoice_no ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge bg-success">
                                                    ₹ {{ number_format($purchase->total_amount, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($purchase->user && $purchase->user->roles->isNotEmpty())
                                                    <span class="badge bg-primary">
                                                        {{ $purchase->user->roles->first()->name }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        No Role
                                                    </span>
                                                @endif
                                            </td>
                                            {{-- <td style="white-space: nowrap;">
                                                <i class="bi bi-calendar3 text-primary me-2"></i>
                                                {{ $purchase->created_at->format('M d, Y') }}
                                            </td> --}}
                                            <td style="white-space: nowrap;">
                                                <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editPurchaseModal{{ $purchase->id }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                {{-- <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST"
                                                    class="d-inline delete-purchase-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form> --}}

                                                <a href="{{ route('purchases.show', $purchase->id) }}"
                                                    class="btn btn-outline-info btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">No Purchase Found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            {{ $purchases->links('pagination::bootstrap-4') }}
                        </div>
            </section>

        </div>
    </main>

    {{-- Edit modals: rendered ONCE, in a SINGLE loop, completely outside the
    <table>. This is the fix for the broken layout in the screenshot. --}}
        @foreach($purchases as $purchase)
            @include('stocks.purchase.edit', ['purchase' => $purchase])
        @endforeach



        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const modal = new bootstrap.Modal(document.getElementById('purchaseModal'));
                    modal.show();

                    document.getElementById('cancelPurchaseBtn')?.addEventListener('click', function () {
                        window.location.href = "{{ route('purchases.index') }}";
                    });
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
                            window.location.href = "{{ route('purchases.index') }}";
                        }

                    });

                });

            });
            document.addEventListener('DOMContentLoaded', function () {

                document.querySelectorAll('.btn-close').forEach(function (closeButton) {

                    closeButton.addEventListener('click', function () {
                        window.location.href = "{{ route('purchases.index') }}";
                    });

                });

            });
        </script>
@endsection