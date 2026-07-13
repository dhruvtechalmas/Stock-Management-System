@foreach($discrepancy as $dispatch)

    <div class="modal fade" id="resolveModal{{ $dispatch->id }}" tabindex="-1">

        <div class="modal-dialog modal-lg">

            <form action="{{ route('material-dispatch.resolve') }}" method="POST">

                @csrf

                <input type="hidden" name="material_dispatch_id" value="{{ $dispatch->id }}">

                <div class="modal-content">

                    <div class="modal-header">
                        <h5>
                            Resolve Discrepancy -
                            {{ $dispatch->request->request_no }}
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>

                    <div class="modal-body">

                        <table class="table align-middle">

                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Dispatched</th>
                                    <th>Already Received</th>
                                    <th>Missing</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($dispatch->items as $item)
                                    <tr>
                                        <td>{{ $item->material->material_name }}</td>

                                        <td>{{ $item->dispatched_qty }}</td>

                                        <td>{{ $item->received_qty }}</td>

                                        <td>
                                            <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">

                                            <input type="number" class="form-control"
                                                name="items[{{ $loop->index }}][missing_qty]" min="0"
                                                max="{{ $item->missing_qty }}" step="0.01" value="{{ $item->missing_qty }}"
                                                required>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-dark">
                            Resolve
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

@endforeach