<div class="px-2 px-md-3">

    <form action="{{ route('wastages.store') }}" method="POST">

        @csrf

        <div class="row">

            {{-- Material --}}
            <div class="col-md-12 mb-3">

                <label class="form-label">
                    Material <span class="text-danger">*</span>
                </label>

                <select name="material_dispatch_item_id" id="material_dispatch_item_id"
                    class="form-select @error('material_dispatch_item_id') is-invalid @enderror">

                    <option value="">-- Select Material --</option>

                    @foreach($dispatchItems as $item)

                        @if($item->remaining_qty > 0)

                            <option value="{{ $item->id }}" data-stock="{{ $item->remaining_qty }}"
                                data-unit="{{ $item->material->unit }}" data-material="{{ $item->material_id }}">

                                {{ $item->material->material_name }}
                                (Remaining :
                                {{ number_format($item->remaining_qty, 3) }}
                                {{ $item->material->unit }})

                            </option>

                        @endif

                    @endforeach

                </select>

                @error('material_dispatch_item_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <input type="hidden" name="material_id" id="material_id">

            <input type="hidden" name="material_id" id="material_id">

            {{-- Available Stock --}}
            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Remaining Quantity
                </label>

                <input type="text" id="available_stock" class="form-control" readonly>

                <small class="text-muted">
                    Available quantity for wastage.
                </small>

            </div>

            {{-- Unit --}}
            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Unit
                </label>

                <input type="text" id="unit" class="form-control" readonly>

            </div>

            {{-- Quantity --}}
            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Wastage Quantity <span class="text-danger">*</span>
                </label>

                <input type="number" id="quantity" name="quantity" step="0.001" min="0.001"
                    value="{{ old('quantity') }}" class="form-control @error('quantity') is-invalid @enderror"
                    placeholder="Enter Quantity">

                @error('quantity')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Date --}}
            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Wastage Date <span class="text-danger">*</span>
                </label>

                <input type="text" id="wastage_date" name="wastage_date"
                    value="{{ old('wastage_date', now()->format('Y-m-d')) }}"
                    class="form-control @error('wastage_date') is-invalid @enderror" placeholder="Select Wastage Date">

                @error('wastage_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Reason --}}
            <div class="col-md-12 mb-3">

                <label class="form-label">
                    Reason <span class="text-danger">*</span>
                </label>

                <textarea name="reason" rows="4" class="form-control @error('reason') is-invalid @enderror"
                    placeholder="Enter wastage reason...">{{ old('reason') }}</textarea>

                @error('reason')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>

        <hr>

        <div class="d-flex justify-content-end gap-2">

            <button type="button" class="btn btn-outline-secondary" id="cancelWastageBtn" data-bs-dismiss="modal">

                Cancel

            </button>

            <button type="submit" class="btn btn-primary">

                <i class="bi bi-save"></i>

                Save Wastage

            </button>

        </div>

    </form>

</div>

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const materialSelect = document.getElementById('material_dispatch_item_id');
        const materialId = document.getElementById('material_id');
        const stockInput = document.getElementById('available_stock');
        const unitInput = document.getElementById('unit');
        const quantityInput = document.getElementById('quantity');

        function updateMaterialDetails() {

            const option = materialSelect.options[materialSelect.selectedIndex];

            if (!option.value) {

                materialId.value = '';
                stockInput.value = '';
                unitInput.value = '';
                quantityInput.value = '';
                quantityInput.removeAttribute('max');

                return;
            }

            materialId.value = option.dataset.materialId;

            stockInput.value = option.dataset.stock;

            unitInput.value = option.dataset.unit;

            quantityInput.max = option.dataset.stock;
        }

        materialSelect.addEventListener('change', updateMaterialDetails);

        quantityInput.addEventListener('input', function () {

            const max = parseFloat(this.max);

            if (!isNaN(max) && parseFloat(this.value) > max) {

                this.value = max;
            }
        });

        updateMaterialDetails();

    });
</script>