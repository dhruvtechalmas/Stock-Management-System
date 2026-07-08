<div class="px-2 px-md-3">

    <form action="{{ route('material-requests.store') }}" method="POST" id="materialRequestForm">
        @csrf

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Request No</label>
                <input type="text" class="form-control" value="Auto Generated" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label">Requested By</label>
                <input type="text"
                    class="form-control"
                    value="{{ auth()->user()->name }}"
                    readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label">Request Date</label>

                <input type="text"
                    id="request_date"
                    name="request_date"
                    class="form-control @error('request_date') is-invalid @enderror"
                    value="{{ old('request_date') }}"
                    placeholder="dd/mm/yyyy">

                @error('request_date')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>

        {{-- Material-Request Items table  --}}
        <hr>
        <h5 class="mb-3">Material-Request Items</h5>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th width="150">Material</th>
                        <th width="150">Unit</th>
                        <th width="150">Request Qty</th>
                        <th width="80">Action</th>
                    </tr>
                </thead>
                {{-- data-material-Request-body is what the shared script looks for --}}
                <tbody id="materialRequestItemsBody" data-materialRequest-body>
                    @php $oldItems = old('items', [[]]); @endphp

                    @foreach($oldItems as $index => $item)
                    <tr>
                        <td>
                            <select
                                name="items[{{ $index }}][material_id]"
                                class="form-select material-select searchable-material 
                                @error("items.$index.material_id") is-invalid @enderror">

                                <option value="">Select Material</option>

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
                        <td>
                           <input type="text"
                                class="form-control unit"
                                readonly>
                        </td>
                        <td>
                          <input
                                type="number"
                                name="items[{{ $index }}][requested_qty]"
                                class="form-control quantity @error("items.$index.requested_qty") is-invalid @enderror""
                                min="0.001"
                                step="0.001"
                                value="{{ old("items.$index.requested_qty", $item['requested_qty'] ?? '') }}">

                                @error("items.$index.requested_qty")
                                    <div class="invalid-feedback d-block">
                                    {{ $message }}
                                    </div>
                                @enderror
                        </td>
                        <td>
                            <button
                                type="button"
                                class="btn btn-outline-danger remove-row">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- data-target-body tells the shared script which tbody to append to --}}
        <button type="button" class="btn btn-outline-primary btn-sm mb-3 add-row-btn" data-target-body="materialRequestItemsBody">
            <i class="bi bi-plus-circle"></i> Add Item
        </button>

        {{-- Hidden template of material <option>s, read by the shared "Add Item" JS
            for every form on this page instead of re-rendering per modal. --}}

            <div class="mt-3">

                <label class="form-label">
                    Remarks
                </label>

                <textarea
                    name="remarks"
                    rows="3"
                    class="form-control @error('remarks') is-invalid @enderror"
                    placeholder="Enter Remarks">{{ old('remarks') }}</textarea>

                @error('remarks')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">
            <button type="button" id="cancelMaterialRequestBtn" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                Cancel
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Save Material Request
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

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.material-select').forEach(updateUnit);
    });

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('material-select')) {
            updateUnit(e.target);
        }
    });


    document.addEventListener('click', function (e) {

        // Remove a row
        if (e.target.closest('.remove-row')) {
            const tbody = e.target.closest('[data-materialRequest-body]');
            const rows = tbody.querySelectorAll('tr');
            if (rows.length > 1) {
                e.target.closest('tr').remove();
            }
        }


        if (e.target.closest('.add-row-btn')) {
            const btn = e.target.closest('.add-row-btn');
            const tbody = document.getElementById(btn.dataset.targetBody);
            const rowCount = tbody.querySelectorAll('tr').length;
            const materialOptionsHtml = document.getElementById('materialOptionsTemplate').innerHTML;

            const row = document.createElement('tr');
            row.innerHTML = `
                <tr>

                <td>

                <select
                name="items[${rowCount}][material_id]"
                class="form-select material-select searchable-material">

                <option value="">Select Material</option>

                ${materialOptionsHtml}

                </select>

                </td>

                <td>

                <input
                type="text"
                class="form-control unit"
                readonly>

                </td>

                <td>

                <input
                type="number"
                name="items[${rowCount}][requested_qty]"
                class="form-control quantity"
                min="0.001"
                step="0.001">

                </td>

                <td>

                <button
                type="button"
                class="btn btn-outline-danger remove-row">

                <i class="bi bi-trash"></i>

                </button>

                </td>

                </tr>
                `;
            tbody.appendChild(row);
        }
    });

    </script>

    <script> 

    // Delete confirmation - works for every delete form on the page, present or future
    document.addEventListener('submit', function (e) {
        if (!e.target.classList.contains('delete-materialRequest-form')) return;

        e.preventDefault();
        const form = e.target;

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to recover this Material Request!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!',
        }).then(function (result) {
            if (result.isConfirmed) form.submit();
        });
    });

   

$(document).ready(function () {

   tbody.appendChild(row);

$(row).find('.searchable-material').select2({
    placeholder:"Search Material",
    width:"100%"
});

});


$(row).find('.searchable-material').select2({
    placeholder: "Search Material",
    width: '100%'
});

</script>


