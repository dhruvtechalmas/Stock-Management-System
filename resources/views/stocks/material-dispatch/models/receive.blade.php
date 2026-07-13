@foreach($dispatched as $dispatch)
    <div class="modal fade" id="receiveModal{{ $dispatch->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('material-dispatch.receive') }}" method="POST">
                @csrf

                <input type="hidden" name="material_dispatch_id" value="{{ $dispatch->id }}">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Receive Materials - {{ $dispatch->request->request_no }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th>Dispatched Qty</th>
                                        <th>Received Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dispatch->items as $item)
                                        <tr>
                                            <td>{{ $item->material->material_name }}</td>
                                            <td>{{ $item->dispatched_qty }}</td>
                                            <td>
                                                <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                                                <input type="number" class="form-control" name="items[{{ $loop->index }}][received_qty]" min="0" max="{{ $item->dispatched_qty }}" step="0.01" value="{{ $item->dispatched_qty }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <label class="form-label">Remarks</label>
                        <textarea class="form-control" name="remarks" rows="3"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-dark">Receive</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach
