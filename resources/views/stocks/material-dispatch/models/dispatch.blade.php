@foreach($approvedDispatches->concat($partialDispatches) as $dispatch)
    <div class="modal fade" id="dispatchModal{{ $dispatch->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('material-dispatch.dispatch') }}" method="POST">
                @csrf

                <input type="hidden" name="material_dispatch_id" value="{{ $dispatch->id }}">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Dispatch Materials - {{ $dispatch->request->request_no }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th>Requested</th>
                                        <th>Already Dispatched</th>
                                        <th>Remaining</th>
                                        <th>Current Stock</th>
                                        <th>Dispatch Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dispatch->items as $item)

                                        @php
                                            $requestedQty = (float) $item->requestItem->requested_qty;

                                            $alreadyDispatched = (float) $item->dispatched_qty;

                                            $remainingQty = max(
                                                0,
                                                $requestedQty - $alreadyDispatched
                                            );

                                            $currentStock = (float) $item->material->current_stock;

                                            $availableQty = min(
                                                $remainingQty,
                                                $currentStock
                                            );
                                        @endphp


                                        @if($remainingQty > 0)

                                            <tr>

                                                {{-- Material --}}
                                                <td>
                                                    {{ $item->material->material_name }}
                                                </td>


                                                {{-- Requested --}}
                                                <td>
                                                    {{ $requestedQty }}
                                                </td>


                                                {{-- Already Dispatched --}}
                                                <td>
                                                    {{ $alreadyDispatched }}
                                                </td>


                                                {{-- Remaining --}}
                                                <td>
                                                    {{ $remainingQty }}
                                                </td>


                                                {{-- Current Stock --}}
                                                <td>
                                                    {{ $currentStock }}
                                                </td>


                                                {{-- Dispatch Remaining Qty --}}
                                                <td>

                                                    <input type="hidden" name="items[{{ $loop->index }}][id]"
                                                        value="{{ $item->id }}">


                                                    @if($availableQty > 0)

                                                        <input type="number" name="items[{{ $loop->index }}][dispatch_qty]"
                                                            class="form-control" min="0.01" max="{{ $availableQty }}" step="0.01"
                                                            value="{{ $availableQty }}" required>

                                                    @else

                                                        <input type="number" class="form-control" value="0" disabled>

                                                        <small class="text-danger">
                                                            No stock available
                                                        </small>

                                                    @endif

                                                </td>

                                            </tr>

                                        @endif

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-success">Dispatch</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach