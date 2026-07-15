@extends('layouts.main')

@section('content')

    <main class="dashboard-content">

        <div class="container-fluid px-3 px-lg-4 py-4">

            {{-- ================= PAGE HEADING ================= --}}
            <div class="page-heading">

                <div class="page-heading-copy">

                    <span class="page-icon">
                        <i class="bi bi-box-arrow-down"></i>
                    </span>

                    <div>
                        <p class="eyebrow mb-1">Stock</p>
                        <h1 class="h3 mb-1">Material Consumption</h1>
                    </div>

                </div>

            </div>


            {{-- ================= SUCCESS MESSAGE ================= --}}
            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show" role="alert">

                    <i class="bi bi-check-circle me-2"></i>

                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            {{-- ================= AVAILABLE MATERIALS ================= --}}
            <section class="panel mb-4">

                <div class="panel-header">

                    <div class="d-flex align-items-center gap-3">

                        <input class="form-control form-control-sm table-search" type="search"
                            placeholder="Search Available Material" data-table-search="availableMaterialsTable">

                    </div>

                    <span class="badge bg-primary">
                        {{ $dispatchItems->count() }} Available
                    </span>

                </div>


                <div class="table-responsive">

                    <table class="table align-middle mb-0" id="availableMaterialsTable" data-searchable-table>

                        <thead>

                            <tr>
                                <th>#</th>
                                <th>Dispatch No.</th>
                                <th>Material</th>
                                <th>Received Qty</th>
                                <th>Consumed Qty</th>
                                <th>Remaining Qty</th>
                                <th>Action</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($dispatchItems as $item)

                                @php
                                    // Total quantity already consumed
                                    $consumedQty = (float) (
                                        $item->consumptions_sum_consumed_qty ?? 0
                                    );

                                    // Only actually received quantity can be consumed
                                    $receivedQty = (float) (
                                        $item->received_qty ?? 0
                                    );

                                    // Quantity still available
                                    $remainingQty = max(
                                        0,
                                        $receivedQty - $consumedQty
                                    );
                                @endphp

                                <tr>

                                    {{-- Serial Number --}}
                                    <td class="fw-semibold">
                                        {{ $loop->iteration }}
                                    </td>


                                    {{-- Dispatch Number --}}
                                    <td>

                                        <span class="fw-semibold">
                                            {{ $item->dispatch->dispatch_no ?? '-' }}
                                        </span>

                                    </td>


                                    {{-- Material --}}
                                    <td>

                                        <div class="fw-semibold">
                                            {{ $item->material->material_name ?? '-' }}
                                        </div>

                                    </td>


                                    {{-- Received Quantity --}}
                                    <td>

                                        <span class="badge bg-info">

                                            {{ number_format($receivedQty, 3) }}

                                        </span>

                                    </td>


                                    {{-- Consumed Quantity --}}
                                    <td>

                                        <span class="badge bg-warning text-dark">

                                            {{ number_format($consumedQty, 3) }}

                                        </span>

                                    </td>


                                    {{-- Remaining Quantity --}}
                                    <td>

                                        @if($remainingQty > 0)

                                            <span class="badge bg-success">

                                                {{ number_format($remainingQty, 3) }}

                                            </span>

                                        @else

                                            <span class="badge bg-secondary">

                                                Fully Consumed

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Action --}}
                                    <td style="white-space: nowrap;">

                                        @if($remainingQty > 0)

                                            @can('material-consumption.create')

                                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#consumeModal{{ $item->id }}">

                                                    <i class="bi bi-box-arrow-down me-1"></i>
                                                    Consume

                                                </button>

                                            @else

                                                <span class="text-muted">
                                                    View Only
                                                </span>

                                            @endcan

                                        @else

                                            <button type="button" class="btn btn-outline-secondary btn-sm" disabled>

                                                <i class="bi bi-check-circle me-1"></i>
                                                Completed

                                            </button>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="text-center text-muted py-4">

                                        <i class="bi bi-inbox fs-4 d-block mb-2"></i>

                                        No Materials Available for Consumption.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </section>


            {{-- ================= CONSUMPTION HISTORY ================= --}}
            <section class="panel">

                <div class="panel-header">

                    <div class="d-flex align-items-center gap-3">

                        <input class="form-control form-control-sm table-search" type="search"
                            placeholder="Search Consumption History" data-table-search="consumptionHistoryTable">

                    </div>

                    <a href="#" class="btn btn-outline-secondary btn-sm">

                        <i class="bi bi-download"></i>

                        Export PDF

                    </a>

                </div>


                <div class="table-responsive">

                    <table class="table align-middle mb-0" id="consumptionHistoryTable" data-searchable-table>

                        <thead>

                            <tr>
                                <th>#</th>
                                <th>Dispatch No.</th>
                                <th>Material</th>
                                <th>Consumed Qty</th>
                                <th>Consumption Date</th>
                                <th>Recorded By</th>
                            </tr>

                        </thead>

                <tbody>

            @forelse($consumptions as $consumption)

                                <tr>

                                    {{-- Serial Number --}}
                                    <td class="fw-semibold">

                                        {{ $loop->iteration }}

                                    </td>


                                    {{-- Dispatch Number --}}
                                    <td>

                                        <span class="fw-semibold">

                                            {{
                                                $consumption
                                                    ->dispatchItem
                                                    ?->dispatch
                                                        ?->dispatch_no ?? '-'
                                            }}

                                        </span>

                                    </td>


                                    {{-- Material --}}
                                    <td>

                                        {{
                                            $consumption
                                                ->material
                                                    ?->material_name ?? '-'
                                            }}

                                    </td>


                                    {{-- Consumed Quantity --}}
                                    <td>

                                        <span class="badge bg-primary">

                                            {{
                                            number_format(
                                                $consumption->consumed_qty,
                                                3
                                            )
                                                }}

                                        </span>

                                    </td>


                                    {{-- Consumption Date --}}
                                    <td>

                                        <i class="bi bi-calendar3 text-primary me-2"></i>

                                        {{
                $consumption
                    ->consumption_date
                        ?->format('M d, Y') ?? '-'
                                            }}

                                    </td>


                                    {{-- Recorded By --}}
                                    <td>

                                        <i class="bi bi-person text-primary me-2"></i>

                                        {{
                $consumption
                    ->recordedBy
                        ?->name ?? '-'
                                            }}

                                    </td>

                                </tr>

            @empty

                                <tr>

                                    <td colspan="6" class="text-center text-muted py-4">

                                        <i class="bi bi-inbox fs-4 d-block mb-2"></i>

                                        No Consumption History Found.

                                    </td>

                                </tr>

                            @endforelse

                </tbody>

                    </table>

                </div>

            </section>

        </div>

    </main>


    {{-- ========================================================== --}}
    {{-- CONSUMPTION MODALS --}}
    {{-- Keep modals outside the table --}}
    {{-- ========================================================== --}}

    @foreach($dispatchItems as $item)

        @php
            $consumedQty = (float) (
                $item->consumptions_sum_consumed_qty ?? 0
            );

            $receivedQty = (float) (
                $item->received_qty ?? 0
            );

            $remainingQty = max(
                0,
                $receivedQty - $consumedQty
            );
        @endphp


        @if($remainingQty > 0)

            <div class="modal fade" id="consumeModal{{ $item->id }}" tabindex="-1"
                aria-labelledby="consumeModalLabel{{ $item->id }}" aria-hidden="true">

                <div class="modal-dialog modal-lg modal-dialog-centered">

                    <div class="modal-content">


                        {{-- ================= FORM ================= --}}
                        <form action="{{ route('material-consumption.store') }}" method="POST">

                            @csrf


                            {{-- Hidden Dispatch Item --}}
                            <input type="hidden" name="material_dispatch_item_id" value="{{ $item->id }}">


                            {{-- Hidden Material --}}
                            <input type="hidden" name="material_id" value="{{ $item->material_id }}">


                            {{-- ================= MODAL HEADER ================= --}}
                            <div class="modal-header">

                                <h5 class="modal-title" id="consumeModalLabel{{ $item->id }}">

                                    <i class="bi bi-box-arrow-down me-2"></i>

                                    Record Material Consumption

                                </h5>


                                <button type="button" class="btn-close consumption-modal-close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                </button>

                            </div>


                            {{-- ================= MODAL BODY ================= --}}
                            <div class="modal-body">

                                <div class="row g-3">


                                    {{-- Dispatch Number --}}
                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Dispatch Number
                                        </label>

                                        <input type="text" class="form-control" value="{{ $item->dispatch->dispatch_no ?? '-' }}"
                                            readonly>

                                    </div>


                                    {{-- Material --}}
                                    <div class="col-md-6">

                                        <label class="form-label">
                                            Material
                                        </label>

                                        <input type="text" class="form-control" value="{{ $item->material->material_name ?? '-' }}"
                                            readonly>

                                    </div>


                                    {{-- Received Quantity --}}
                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Received Quantity
                                        </label>

                                        <input type="text" class="form-control" value="{{ number_format($receivedQty, 3) }}"
                                            readonly>

                                    </div>


                                    {{-- Already Consumed --}}
                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Already Consumed
                                        </label>

                                        <input type="text" class="form-control" value="{{ number_format($consumedQty, 3) }}"
                                            readonly>

                                    </div>


                                    {{-- Available Quantity --}}
                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Available Quantity
                                        </label>

                                        <input type="text" class="form-control" value="{{ number_format($remainingQty, 3) }}"
                                            readonly>

                                    </div>


                                    {{-- Consumed Quantity --}}
                                    <div class="col-md-6">

                                        <label for="consumed_qty_{{ $item->id }}" class="form-label">
                                            Consumed Quantity
                                            <span class="text-danger">*</span>
                                        </label>
                                        
                                        <input
                                            type="number"
                                            id="consumed_qty_{{ $item->id }}"
                                            name="consumed_qty"
                                            value="{{ old('material_dispatch_item_id') == $item->id ? old('consumed_qty') : '' }}"
                                            class="form-control @error('consumed_qty') @if(old('material_dispatch_item_id') == $item->id) is-invalid @endif @enderror"
                                            step="0.001"
                                            min="0.001"
                                            max="{{ $remainingQty }}"
                                            placeholder="Enter Consumed Quantity"
                                            required>

                                        @error('consumed_qty')
                                            @if(old('material_dispatch_item_id') == $item->id)
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @endif
                                        @enderror

                                        <small class="text-muted">
                                            Maximum Available:
                                            <strong>{{ number_format($remainingQty, 3) }}</strong>
                                        </small>

                                    </div>


                                    {{-- Consumption Date --}}
                                    <div class="col-md-6">

                                        <label for="consumption_date_{{ $item->id }}" class="form-label">

                                            Consumption Date

                                            <span class="text-danger">*</span>

                                        </label>


                                        <input type="date" id="consumption_date_{{ $item->id }}" name="consumption_date" class="form-control
                                                        @if(
                                                                $errors->has('consumption_date')
                                                                &&
                                                                old('material_dispatch_item_id')
                                                                == $item->id
                                                            )
                                                                is-invalid
                                                        @endif" value="{{
                        old('material_dispatch_item_id')
                        == $item->id
                        ? old(
                            'consumption_date',
                            now()->format('Y-m-d')
                        )
                        : now()->format('Y-m-d')
                                                    }}" max="{{ now()->format('Y-m-d') }}" required>


                                        @if(
                                                            $errors->has('consumption_date')
                                                            &&
                                                            old('material_dispatch_item_id')
                                                            == $item->id
                                                        )

                                                        <div class="invalid-feedback">

                                                            {{
                                            $errors
                                                ->first('consumption_date')
                                                                        }}

                                                        </div>

                                        @endif

                                    </div>

                                </div>

                            </div>


                            {{-- ================= MODAL FOOTER ================= --}}
                            <div class="modal-footer">

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary consumption-modal-cancel"
                                    data-bs-dismiss="modal">

                                    <i class="bi bi-x-circle me-1"></i>
                                    Cancel

                                </button>


                                <button type="submit" class="btn btn-outline-primary">

                                    <i class="bi bi-check-circle me-1"></i>

                                    Save Consumption

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        @endif

    @endforeach


{{-- Reopen correct modal when validation fails --}}
@if($errors->any() && old('material_dispatch_item_id'))

<script>
document.addEventListener('DOMContentLoaded', function () {

    const modalElement = document.getElementById(
        'consumeModal{{ old('material_dispatch_item_id') }}'
    );

    if (modalElement) {

        const modal = new bootstrap.Modal(modalElement);

        modal.show();

    }

});
</script>

@endif


{{-- Refresh page when Cancel or X is clicked --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const consumptionIndexUrl =
        "{{ route('material-consumption.index') }}";


    document
        .querySelectorAll(
            '.consumption-modal-cancel, .consumption-modal-close'
        )
        .forEach(function (button) {

            button.addEventListener('click', function () {

                window.location.href = consumptionIndexUrl;

            });

        });

});
</script>

@endsection