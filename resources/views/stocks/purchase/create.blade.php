<div class="px-2 px-md-3">

    <form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
        @csrf

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Purchase No</label>
                <input type="text" class="form-control" value="Auto Generated" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label">Supplier
                     <span>
                        <small class="text-danger">*</small>
                    </span>
                </label>
                <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror">
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Invoice No</label>
                <input type="text" name="invoice_no" value="{{ old('invoice_no') }}" class="form-control"
                    placeholder="Enter Invoice Number">
                @error('invoice_no')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Purchase Date
                     <span>
                        <small class="text-danger">*</small>
                    </span>
                </label>
                <input type="text" id="purchase_date" name="purchase_date" class="form-control"
                    placeholder="dd/mm/yyyy" value="{{ old('purchase_date') }}">
                @error('purchase_date')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

        </div>

        <hr>
        <h5 class="mb-3">Purchase Items</h5>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Material
                            <span>
                                <small class="text-danger">*</small>
                            </span>
                        </th>
                        <th>Unit</th>
                        <th width="150">Qty
                            <span>
                                <small class="text-danger">*</small>
                            </span>
                        </th>
                        <th width="150">Unit Price
                            <span>
                                <small class="text-danger">*</small>
                            </span>
                        </th>
                        <th width="150">Total</th>
                        <th width="80">Action</th>
                    </tr>
                </thead>
                {{-- data-purchase-body is what the shared script looks for --}}
                <tbody id="purchaseItemsBody" data-purchase-body>
                    @php $oldItems = old('items', [[]]); @endphp

                    @foreach($oldItems as $index => $item)
                    <tr>
                       <td>
                            <select
                                name="items[{{ $index }}][material_id]"
                                class="form-select material-select searchable-material">

                                <option value="">Select Material
                                     <span>
                        <small class="text-danger">*</small>
                    </span>
                                </option>

                                @foreach($materials as $material)
                                    <option
                                        value="{{ $material->id }}"
                                        data-unit="{{ $material->unit }}"
                                        {{ old("items.$index.material_id", $item['material_id'] ?? '') == $material->id ? 'selected' : '' }}>

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
                                name="items[{{ $index }}][quantity]"
                                class="form-control quantity"
                                min="0.001"
                                step="0.001"
                                value="{{ old("items.$index.quantity", $item['quantity'] ?? '') }}">

                            @error("items.$index.quantity")
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input
                                type="number"
                                name="items[{{ $index }}][unit_price]"
                                class="form-control price"
                                min="0.01"
                                step="0.01"
                                value="{{ old("items.$index.unit_price", $item['unit_price'] ?? '') }}">
                                
                            @error("items.$index.unit_price")
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </td>
                        <td><input type="text" class="form-control total" value="0.00" readonly></td>
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

        {{-- data-target-body tells the shared script which tbody to append to --}}
        <button type="button" class="btn btn-outline-primary btn-sm mb-3 add-row-btn" data-target-body="purchaseItemsBody">
            <i class="bi bi-plus-circle"></i> Add Item
        </button>

        <div class="row">
            <div class="col-md-4 ms-auto">
                <label class="form-label">Grand Total</label>
                <input type="text" class="form-control fw-bold" readonly value="0.00" data-grand-total>
            </div>
        </div>

        <div class="mt-3">
            <label class="form-label">Remarks</label>
            <textarea name="remarks" rows="3" class="form-control" placeholder="Enter Remarks">{{ old('remarks') }}</textarea>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">
            <button type="button" id="cancelPurchaseBtn" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                Cancel
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Save Purchase
            </button>
        </div>

    </form>
</div>

{{-- Include this ONCE in list.blade.php, never inside create.blade.php or edit.blade.php.
     Because it uses event delegation + data attributes instead of hardcoded
     per-purchase IDs, it works correctly no matter how many edit modals exist
     on the page — nothing gets redefined or overwritten. --}}
<script>
    function updateUnit(select) {
        if (!select.value) return;
        const row = select.closest('tr');
        const unit = select.options[select.selectedIndex]?.dataset.unit || '';
        row.querySelector('.unit').value = unit;
    }

    function recalcBody(tbody) {
        let grandTotal = 0;

        tbody.querySelectorAll('tr').forEach(function (row) {
            const qty = parseFloat(row.querySelector('.quantity')?.value) || 0;
            const price = parseFloat(row.querySelector('.price')?.value) || 0;
            const total = qty * price;

            const totalField = row.querySelector('.total');
            if (totalField) totalField.value = total.toFixed(2);

            grandTotal += total;
        });

        const form = tbody.closest('form');
        const grandTotalField = form?.querySelector('[data-grand-total]');
        if (grandTotalField) grandTotalField.value = grandTotal.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.material-select').forEach(updateUnit);
        document.querySelectorAll('[data-purchase-body]').forEach(recalcBody);
    });

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('material-select')) {
            updateUnit(e.target);
        }
    });

    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('quantity') || e.target.classList.contains('price')) {
            const tbody = e.target.closest('[data-purchase-body]');
            if (tbody) recalcBody(tbody);
        }
    });

    document.addEventListener('click', function (e) {

        // Remove a row
        if (e.target.closest('.remove-row')) {
            const tbody = e.target.closest('[data-purchase-body]');
            const rows = tbody.querySelectorAll('tr');
            if (rows.length > 1) {
                e.target.closest('tr').remove();
                recalcBody(tbody);
            }
        }


        if (e.target.closest('.add-row-btn')) {
            const btn = e.target.closest('.add-row-btn');
            const tbody = document.getElementById(btn.dataset.targetBody);
            const rowCount = tbody.querySelectorAll('tr').length;
            const materialOptionsHtml = document.getElementById('materialOptionsTemplate').innerHTML;

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <select name="items[${rowCount}][material_id]" class="form-select material-select">
                        <option value="">Select Material</option>
                        ${materialOptionsHtml}
                    </select>
                </td>
                <td><input type="text" class="form-control unit" readonly></td>
                <td><input type="number" name="items[${rowCount}][quantity]" class="form-control quantity" min="0" step="0.01"></td>
                <td><input type="number" name="items[${rowCount}][unit_price]" class="form-control price" min="0" step="0.01"></td>
                <td><input type="text" class="form-control total" value="0.00" readonly></td>
                <td><button type="button" class="btn btn-outline-danger remove-row"><i class="bi bi-trash"></i></button></td>
            `;
            tbody.appendChild(row);
        }
    });

    // Delete confirmation - works for every delete form on the page, present or future
    // document.addEventListener('submit', function (e) {
    //     if (!e.target.classList.contains('delete-purchase-form')) return;

    //     e.preventDefault();
    //     const form = e.target;

    //     Swal.fire({
    //         title: 'Are you sure?',
    //         text: "You won't be able to recover this purchase!",
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Yes, Delete it!',
    //     }).then(function (result) {
    //         if (result.isConfirmed) form.submit();
    //     });
    // });

   

$(document).ready(function () {

    $('.searchable-material').select2({
        placeholder: "Search Material",
        width: '100%'
    });

});


$(row).find('.searchable-material').select2({
    placeholder: "Search Material",
    width: '100%'
});

</script>


