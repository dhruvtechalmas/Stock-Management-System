<div class="modal fade" id="editMaterialRequestModal{{ $materialRequest->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Material Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="px-2 px-md-3">

                    <form action="{{ route('material-requests.update', $materialRequest->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label">Request No</label>
                                <input type="text" class="form-control" value="{{ $materialRequest->request_no }}" readonly>
                            </div>


                            <div class="col-md-4">
                                <label class="form-label">Request Date</label>

                                <input type="text"
                                    name="request_date"
                                    class="form-control @error('request_date') is-invalid @enderror"
                                    placeholder="dd/mm/yyyy"
                                    value="{{ old('request_date', \Carbon\Carbon::parse($materialRequest->request_date)->format('d M Y')) }}">

                                @error('request_date')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <input type="hidden" name="status" value="{{ $materialRequest->status }}">
                        </div>

                        <hr>
                        <h5 class="mb-3">Materil Request Items</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th>Unit</th>
                                        <th width="150">Request Qty</th>
                                        <th width="80">Action</th>
                                    </tr>
                                </thead>
                                {{-- unique id per purchase; data-purchase-body is what the shared script targets --}}
                                <tbody id="materialRequestItemsBody{{ $materialRequest->id }}" data-materialRequest-body>
                                    @foreach($materialRequest->items as $index => $item)
                                    <tr>
                                        <td>
                                            <select
                                                name="items[{{ $index }}][material_id]"
                                                class="form-select material-select searchable-material">

                                                <option value="">Select Material</option>

                                                @foreach($materials as $material)

                                                    <option
                                                        value="{{ $material->id }}"
                                                        data-unit="{{ $material->unit }}"
                                                        {{ old("items.$index.material_id", $item->material_id) == $material->id ? 'selected' : '' }}>

                                                        {{ $material->material_name }}

                                                    </option>

                                                @endforeach

                                            </select>

                                            @error("items.$index.material_id")
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </td>
                                        <td><input type="text" class="form-control unit" readonly></td>
                                        <td>

                                            <input
                                                type="number"
                                                name="items[{{ $index }}][requested_qty]"
                                                class="form-control quantity @error("items.$index.requested_qty") is-invalid @enderror"
                                                min="0.001"
                                                step="0.001"
                                                value="{{ old("items.$index.requested_qty", number_format($item->requested_qty,2,'.','')) }}">

                                            @error("items.$index.requested_qty")
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-danger remove-row">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-sm mb-3 add-row-btn"
                            data-target-body="materialRequestItemsBody{{ $materialRequest->id }}">
                            <i class="bi bi-plus-circle"></i> Add Item
                        </button>
                        <div class="mt-3">

                            <label class="form-label">Remarks</label>

                            <textarea
                                name="remarks"
                                rows="3"
                                class="form-control @error('remarks') is-invalid @enderror"
                                placeholder="Enter Remarks">{{ old('remarks', $materialRequest->remarks) }}</textarea>

                            @error('remarks')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">
                            {{-- id must be unique per modal, unlike the original --}}
                            <button type="button" id="cancelmaterialRequestBtn{{ $materialRequest->id }}" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Material Request
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
