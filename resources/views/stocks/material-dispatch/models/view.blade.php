{{-- ========================================================= --}}
{{-- PENDING MATERIAL REQUEST VIEW MODALS --}}
{{-- Button target:
     #viewRequestModal{{ $request->id }}
--}}
{{-- ========================================================= --}}

@foreach($pendingRequests as $request)

    <div class="modal fade"
        id="viewRequestModal{{ $request->id }}"
        tabindex="-1"
        aria-labelledby="viewRequestModalLabel{{ $request->id }}"
        aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                {{-- Header --}}
                <div class="modal-header">

                    <h5 class="modal-title"
                        id="viewRequestModalLabel{{ $request->id }}">

                        Request Details -
                        {{ $request->request_no }}

                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>

                </div>


                {{-- Body --}}
                <div class="modal-body">

                    {{-- Request Information --}}
                    <div class="row g-3 mb-4">

                        <div class="col-md-4">

                            <strong>Requested By</strong>

                            <div class="mt-1">
                                {{ $request->user?->name ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <strong>Request Date</strong>

                            <div class="mt-1">

                                {{ $request->request_date?->format('d M Y') ?? '-' }}

                            </div>

                        </div>


                        <div class="col-md-4">

                            <strong>Status</strong>

                            <div class="mt-1">

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- Material Items --}}
                    <div class="table-responsive">

                        <table class="table table-bordered align-middle mb-0">

                            <thead>

                                <tr>

                                    <th>Material</th>

                                    <th>Requested Qty</th>

                                    <th>Unit</th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($request->items as $item)

                                    <tr>

                                        <td>

                                            {{ $item->material?->material_name ?? '-' }}

                                        </td>


                                        <td>

                                            {{ number_format(
                                                (float) $item->requested_qty,
                                                2
                                            ) }}

                                        </td>


                                        <td>

                                            {{ $item->material?->unit ?? '-' }}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="3"
                                            class="text-center">

                                            No Material Items Found

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- Remarks --}}
                    @if($request->remarks)

                        <div class="mt-4">

                            <strong>Remarks</strong>

                            <div class="mt-1">

                                {{ $request->remarks }}

                            </div>

                        </div>

                    @endif

                </div>


                {{-- Footer --}}
                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Close

                    </button>

                </div>

            </div>

        </div>

    </div>

@endforeach



{{-- ========================================================= --}}
{{-- MATERIAL DISPATCH VIEW MODALS --}}
{{-- Button target:
     #viewDispatchModal{{ $dispatch->id }}
--}}
{{-- ========================================================= --}}

@foreach(
    $approvedDispatches
        ->concat($partialDispatches)
        ->concat($dispatched)
        ->concat($received)
        ->concat($discrepancy)
        ->concat($rejected)
        ->unique('id')
    as $dispatch
)

    <div class="modal fade"
        id="viewDispatchModal{{ $dispatch->id }}"
        tabindex="-1"
        aria-labelledby="viewDispatchModalLabel{{ $dispatch->id }}"
        aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                {{-- Header --}}
                <div class="modal-header">

                    <h5 class="modal-title"
                        id="viewDispatchModalLabel{{ $dispatch->id }}">

                        Dispatch Details -
                        {{ $dispatch->request?->request_no ?? '-' }}

                    </h5>


                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>

                </div>


                {{-- Body --}}
                <div class="modal-body">

                    {{-- Dispatch Information --}}
                    <div class="row g-3 mb-4">

                        <div class="col-md-3">

                            <strong>Dispatch No</strong>

                            <div class="mt-1">

                                {{ $dispatch->dispatch_no }}

                            </div>

                        </div>


                        <div class="col-md-3">

                            <strong>Requested By</strong>

                            <div class="mt-1">

                                {{ $dispatch->request?->user?->name ?? '-' }}

                            </div>

                        </div>


                        <div class="col-md-3">

                            <strong>Request Date</strong>

                            <div class="mt-1">

                                {{ $dispatch->request?->request_date?->format('d M Y') ?? '-' }}

                            </div>

                        </div>


                        <div class="col-md-3">

                            <strong>Status</strong>

                            <div class="mt-1">

                                @switch($dispatch->status)

                                    @case('pending')

                                        <span class="badge bg-primary">
                                            Approved
                                        </span>

                                        @break


                                    @case('partially_dispatched')

                                        <span class="badge bg-warning text-dark">
                                            Partial Dispatch
                                        </span>

                                        @break


                                    @case('dispatched')

                                        <span class="badge bg-info">
                                            Dispatched
                                        </span>

                                        @break


                                    @case('received_with_discrepancy')

                                        <span class="badge bg-warning text-dark">
                                            Discrepancy
                                        </span>

                                        @break


                                    @case('completed')

                                        <span class="badge bg-success">
                                            Completed
                                        </span>

                                        @break


                                    @case('rejected')

                                        <span class="badge bg-danger">
                                            Rejected
                                        </span>

                                        @break


                                    @default

                                        <span class="badge bg-secondary">

                                            {{ ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $dispatch->status
                                                )
                                            ) }}

                                        </span>

                                @endswitch

                            </div>

                        </div>

                    </div>


                    {{-- Material Details --}}
                    <div class="table-responsive">

                        <table class="table table-bordered align-middle mb-0">

                            <thead>

                                <tr>

                                    <th>Material</th>

                                    <th>Requested</th>

                                    <th>Dispatched</th>

                                    <th>Remaining</th>

                                    <th>Received</th>

                                    <th>Missing</th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($dispatch->items as $item)

                                    @php

                                        $requestedQty = (float) (
                                            $item->requestItem?->requested_qty ?? 0
                                        );

                                        $dispatchedQty = (float) (
                                            $item->dispatched_qty ?? 0
                                        );

                                        $receivedQty = (float) (
                                            $item->received_qty ?? 0
                                        );

                                        $missingQty = (float) (
                                            $item->missing_qty ?? 0
                                        );

                                        /*
                                         * Remaining means:
                                         * quantity still waiting to be dispatched.
                                         *
                                         * Requested 100
                                         * Dispatched 50
                                         * Remaining 50
                                         */
                                        $remainingQty = max(
                                            0,
                                            $requestedQty - $dispatchedQty
                                        );

                                    @endphp


                                    <tr>

                                        {{-- Material --}}
                                        <td>

                                            {{ $item->material?->material_name ?? '-' }}

                                        </td>


                                        {{-- Requested --}}
                                        <td>

                                            {{ number_format(
                                                $requestedQty,
                                                2
                                            ) }}

                                        </td>


                                        {{-- Dispatched --}}
                                        <td>

                                            {{ number_format(
                                                $dispatchedQty,
                                                2
                                            ) }}

                                        </td>


                                        {{-- Remaining to Dispatch --}}
                                        <td>

                                            {{ number_format(
                                                $remainingQty,
                                                2
                                            ) }}

                                        </td>


                                        {{-- Received --}}
                                        <td>

                                            {{ number_format(
                                                $receivedQty,
                                                2
                                            ) }}

                                        </td>


                                        {{-- Missing --}}
                                        <td>

                                            {{ number_format(
                                                $missingQty,
                                                2
                                            ) }}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="6"
                                            class="text-center">

                                            No Dispatch Items Found

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- Remarks --}}
                    @if($dispatch->remarks)

                        <div class="mt-4">

                            <strong>Remarks</strong>

                            <div class="mt-1">

                                {{ $dispatch->remarks }}

                            </div>

                        </div>

                    @endif

                </div>


                {{-- Footer --}}
                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Close

                    </button>

                </div>

            </div>

        </div>

    </div>

@endforeach


