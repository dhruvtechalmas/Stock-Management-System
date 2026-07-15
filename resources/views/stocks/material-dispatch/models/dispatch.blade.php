@foreach($approvedDispatches as $dispatch)

    <div class="modal fade" id="dispatchModal{{ $dispatch->id }}" tabindex="-1">

        <div class="modal-dialog modal-lg">

            <form action="{{ route('material-dispatch.dispatch') }}" method="POST">

                @csrf

                <input type="hidden" name="material_dispatch_id" value="{{ $dispatch->id }}">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5>
                            Dispatch Materials -
                            {{ $dispatch->request->request_no }}
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="table-responsive">

                            <table class="table table-bordered align-middle">

                                <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th>Requested Qty</th>
                                        <th>Already Dispatched</th>
                                        <th>Current Stock</th>
                                        <th>Dispatch Qty</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach($dispatch->items as $item)

                                        @php

                                            $requestedQty = (float) $item->requestItem->requested_qty;

                                            $alreadyDispatched = (float) $item->dispatched_qty;

                                            $currentStock = (float) $item->material->current_stock;

                                            $availableQty = min(
                                                $requestedQty - $alreadyDispatched,
                                                $currentStock
                                            );

                                        @endphp

                                        @if($availableQty > 0)

                                            <tr>

                                                <td>
                                                    {{ $item->material->material_name }}
                                                </td>

                                                <td>
                                                    {{ number_format($requestedQty, 2) }}
                                                </td>

                                                <td>
                                                    {{ number_format($alreadyDispatched, 2) }}
                                                </td>

                                                <td>
                                                    {{ number_format($currentStock, 2) }}
                                                </td>

                                                <td>

                                                    <input type="hidden" name="items[{{ $loop->index }}][id]"
                                                        value="{{ $item->id }}">

                                                    <input type="number" name="items[{{ $loop->index }}][dispatch_qty]"
                                                        class="form-control" min="0.01" max="{{ $availableQty }}"
                                                        value="{{ $availableQty }}" step="0.001" required>

                                                </td>

                                            </tr>

                                        @endif

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                            Cancel

                        </button>

                        <button type="submit" class="btn btn-success">

                            Dispatch

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

@endforeach

@foreach($partialApprovedRequests as $dispatch)

    <div class="modal fade" id="partialApproveModal{{ $dispatch->id }}" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                <form action="{{ route('material-dispatch.dispatch') }}" method="POST">

                    @csrf

                    <input type="hidden" name="material_dispatch_id" value="{{ $dispatch->id }}">

                    <div class="modal-header">

                        <h5 class="modal-title">
                            Dispatch Material - {{ $dispatch->request->request_no }}
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="table-responsive">

                            <table class="table table-bordered align-middle">

                                <thead>

                                    <tr>

                                        <th>Material</th>
                                        <th>Requested Qty</th>
                                        <th>Dispatched Qty</th>
                                        <th>Current Stock</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($dispatch->items as $item)

                                        <tr>

                                            <td>
                                                {{ $item->material->material_name }}
                                            </td>

                                            <td>
                                                {{ number_format($item->requestItem->requested_qty, 2) }}
                                            </td>

                                            <td>
                                                {{ number_format($item->dispatched_qty, 2) }}
                                            </td>

                                            <td>
                                                {{ number_format($item->material->current_stock, 2) }}
                                            </td>

                                            <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                            Cancel

                        </button>

                        <button type="submit" class="btn btn-success">

                            <i class="bi bi-truck"></i>

                            Dispatch

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endforeach