@extends('layouts.main')

@section('content')

<main class="dashboard-content">

    <div class="container-fluid px-3 px-lg-4 py-4">

        <div class="page-heading">

            <div class="page-heading-copy">

                <span class="page-icon">
                    <i class="bi bi-cart-check"></i>
                </span>

                <div>
                    <p class="eyebrow mb-1">Transaction</p>
                    <h1 class="h3 mb-1">
                        Purchase Details
                    </h1>
                </div>

            </div>

            <div class="heading-actions">

                <a href="{{ route('purchases.index') }}"
                    class="btn btn-outline-secondary btn-sm">

                    <i class="bi bi-arrow-left"></i>

                    Back to Purchases

                </a>

            </div>

        </div>

        <section class="row g-3">

            {{-- Left Card --}}
            <div class="col-lg-4">

                <div class="panel h-100 text-center">

                    <div class="pt-4">

                        <div class="mb-3">

                            <i class="bi bi-cart-check"
                                style="font-size:90px;color:#6c757d;"></i>

                        </div>

                        <h4>

                            {{ $purchase->purchase_no }}

                        </h4>

                        <p class="text-muted">

                            {{ $purchase->supplier->name }}

                        </p>

                        <span class="badge bg-success">

                            ₹ {{ number_format($purchase->total_amount,2) }}

                        </span>

                    </div>

                </div>

            </div>

            {{-- Right Details --}}
            <div class="col-lg-8">

                <div class="panel">

                    <div class="panel-header">

                        <h5 class="mb-0">

                            Purchase Information

                        </h5>

                    </div>

                    <div class="info-list">

                        <div>

                            <span>Purchase No</span>

                            <strong>{{ $purchase->purchase_no }}</strong>

                        </div>

                        <div>

                            <span>Supplier</span>

                            <strong>{{ $purchase->supplier->name }}</strong>

                        </div>

                        <div>

                            <span>Invoice No</span>

                            <strong>{{ $purchase->invoice_no ?? '-' }}</strong>

                        </div>

                        <div>

                            <span>Purchase Date</span>

                            <strong>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</strong>

                        </div>

                        <div>

                            <span>Total Amount</span>

                            <strong>₹ {{ number_format($purchase->total_amount,2) }}</strong>

                        </div>

                        <div>

                            <span>Created By</span>

                            <strong>{{ $purchase->user->name ?? '-' }}</strong>

                        </div>

                        <div>

                            <span>Remarks</span>

                            <strong>{{ $purchase->remarks ?? '-' }}</strong>

                        </div>

                        <div>

                            <span>Created At</span>

                            <strong>{{ $purchase->created_at->format('d M Y h:i A') }}</strong>

                        </div>

                        <div>

                            <span>Updated At</span>

                            <strong>{{ $purchase->updated_at->format('d M Y h:i A') }}</strong>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        {{-- Purchase Items --}}
        <section class="panel mt-4">

            <div class="panel-header">

                <h5 class="mb-0">

                    Purchase Items

                </h5>

            </div>

            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead>

                        <tr>

                            <th>#</th>
                            <th>Material</th>
                            <th>Unit</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($purchase->items as $item)

                        <tr>

                            <td>

                                {{ $loop->iteration }}

                            </td>

                            <td>

                                {{ $item->material->material_name }}

                            </td>

                            <td>

                                {{ $item->material->unit }}

                            </td>

                            <td>

                                {{ $item->quantity }}

                            </td>

                            <td>

                                ₹ {{ number_format($item->unit_price,2) }}

                            </td>

                            <td>

                                <strong>

                                    ₹ {{ number_format($item->total_price,2) }}

                                </strong>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="6" class="text-center text-muted">

                                No Purchase Items Found.

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                    <tfoot>

                        <tr>

                            <th colspan="5" class="text-end">

                                Grand Total

                            </th>

                            <th>

                                ₹ {{ number_format($purchase->total_amount,2) }}

                            </th>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </section>

    </div>

</main>

@endsection