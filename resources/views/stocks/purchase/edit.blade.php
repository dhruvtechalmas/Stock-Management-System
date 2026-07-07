<div class="modal fade" id="editPurchaseModal{{ $purchase->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Purchase</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="px-2 px-md-3">

                    <form action="{{ route('purchases.update', $purchase->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label">Purchase No</label>
                                <input type="text" class="form-control" value="{{ $purchase->purchase_no }}" readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Supplier</label>
                                <select name="supplier_id" class="form-select">
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Invoice No</label>
                                <input type="text" name="invoice_no" value="{{ $purchase->invoice_no }}" class="form-control"
                                    placeholder="Enter Invoice Number">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Purchase Date</label>
                                <input type="text" name="purchase_date" class="form-control" placeholder="dd/mm/yyyy"
                                    value="{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}">
                            </div>

                        </div>

                        <hr>
                        <h5 class="mb-3">Purchase Items</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th>Unit</th>
                                        <th width="150">Qty</th>
                                        <th width="150">Unit Price</th>
                                        <th width="150">Total</th>
                                        <th width="80">Action</th>
                                    </tr>
                                </thead>
                                {{-- unique id per purchase; data-purchase-body is what the shared script targets --}}
                                <tbody id="purchaseItemsBody{{ $purchase->id }}" data-purchase-body>
                                    @foreach($purchase->items as $index => $item)
                                    <tr>
                                        <td>
                                            <select name="items[{{ $index }}][material_id]" class="form-select material-select">
                                                <option value="">Select Material</option>
                                                @foreach($materials as $material)
                                                    <option value="{{ $material->id }}" data-unit="{{ $material->unit }}"
                                                        {{ $item->material_id == $material->id ? 'selected' : '' }}>
                                                        {{ $material->material_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control unit" readonly></td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][quantity]"
                                                value="{{ $item->quantity }}" class="form-control quantity"
                                                min="0" step="0.01">
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][unit_price]"
                                                value="{{ $item->unit_price }}" class="form-control price"
                                                min="0" step="0.01">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control total"
                                                value="{{ number_format($item->total_price, 2, '.', '') }}" readonly>
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
                            data-target-body="purchaseItemsBody{{ $purchase->id }}">
                            <i class="bi bi-plus-circle"></i> Add Item
                        </button>

                        <div class="row">
                            <div class="col-md-4 ms-auto">
                                <label class="form-label">Grand Total</label>
                                <input type="text" class="form-control fw-bold" readonly
                                    value="{{ number_format($purchase->total_amount, 2, '.', '') }}" data-grand-total>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" rows="3" class="form-control"
                                placeholder="Enter Remarks">{{ $purchase->remarks }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">
                            {{-- id must be unique per modal, unlike the original --}}
                            <button type="button" id="cancelPurchaseBtn{{ $purchase->id }}" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Purchase
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
