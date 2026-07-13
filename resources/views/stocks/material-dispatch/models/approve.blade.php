{{-- ========================================================= --}}
{{-- APPROVE & DISPATCH MODALS --}}
{{-- ========================================================= --}}

@foreach($pendingRequests as $request)

    <div class="modal fade"
         id="approveModal{{ $request->id }}"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                <form action="{{ route('material-dispatch.approve') }}"
                      method="POST">

                    @csrf

                    <input type="hidden"
                           name="material_request_id"
                           value="{{ $request->id }}">

                    <div class="modal-header">

                        <h5 class="modal-title">
                            Approve & Dispatch - {{ $request->request_no }}
                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="table-responsive">

                            <table class="table table-bordered align-middle">

                                <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th>Requested Qty</th>
                                        <th>Current Stock</th>
                                        <th>Dispatch Qty</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach($request->items as $item)

                                        @php
                                            $requestedQty = (float) $item->requested_qty;
                                            $currentStock = (float) ($item->material?->current_stock ?? 0);
                                            $maxDispatch = min($requestedQty, $currentStock);
                                        @endphp

                                        <tr>

                                            <td>
                                                {{ $item->material?->material_name ?? '-' }}
                                            </td>

                                            <td>
                                                {{ number_format($requestedQty, 2) }}
                                            </td>

                                            <td>
                                                {{ number_format($currentStock, 2) }}
                                            </td>

                                            <td>

                                                <input type="hidden"
                                                       name="items[{{ $loop->index }}][request_item_id]"
                                                       value="{{ $item->id }}">

                                                <input type="number"
                                                       name="items[{{ $loop->index }}][dispatch_qty]"
                                                       class="form-control"
                                                       min="0"
                                                       max="{{ $maxDispatch }}"
                                                       step="0.01"
                                                       value="{{ $maxDispatch }}"
                                                       required>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit"
                                class="btn btn-success">

                            <i class="bi bi-check-circle"></i>
                            Approve & Dispatch

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endforeach


{{-- ========================================================= --}}
{{-- REJECT MODALS --}}
{{-- ========================================================= --}}

@foreach($pendingRequests as $request)

    <div class="modal fade"
         id="rejectModal{{ $request->id }}"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <form action="{{ route('material-dispatch.reject') }}"
                      method="POST">

                    @csrf

                    <input type="hidden"
                           name="material_request_id"
                           value="{{ $request->id }}">

                    <div class="modal-header">

                        <h5 class="modal-title">
                            Reject Request - {{ $request->request_no }}
                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <label class="form-label">
                            Reject Reason
                        </label>

                        <textarea name="reject_reason"
                                  class="form-control"
                                  rows="4"
                                  maxlength="500"
                                  placeholder="Enter reject reason"
                                  required></textarea>

                    </div>

                    <div class="modal-footer">

                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit"
                                class="btn btn-danger">

                            <i class="bi bi-x-circle"></i>
                            Reject

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endforeach