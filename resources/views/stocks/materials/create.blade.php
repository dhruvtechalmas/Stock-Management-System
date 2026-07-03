{{-- Show Validation Errors --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="px-2 px-md-3">

    <form action="{{ route('materials.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf

        <div class="row g-3">

            <!-- Material Name -->
            <div class="col-md-12">
                <label class="form-label">Material Name</label>

                <input type="text" name="material_name"
                    class="form-control @error('material_name') is-invalid @enderror" value="{{ old('material_name') }}"
                    placeholder="Enter Material Name">

                @error('material_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Material Categrory --}}
            <div class="col-md-12">
                <label class="form-label">Category</label>

                <select name="material_category_id"
                    class="form-select @error('material_category_id') is-invalid @enderror">

                    <option value="">Select Category</option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('material_category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach

                </select>

                @error('material_category_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <!-- Unit -->
            <div class="col-md-6">
                <label class="form-label">Unit</label>

                <select name="unit" class="form-select @error('unit') is-invalid @enderror">

                    <option value="">Select Unit</option>
                    <option value="Kg" {{ old('unit') == 'Kg' ? 'selected' : '' }}>Kg</option>
                    <option value="Liter" {{ old('unit') == 'Liter' ? 'selected' : '' }}>Liter</option>
                    <option value="Piece" {{ old('unit') == 'Piece' ? 'selected' : '' }}>Piece</option>

                </select>

                @error('unit')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Minimum Stock -->
            <div class="col-md-6">
                <label class="form-label">Minimum Stock</label>

                <input type="number" name="minimum_stock"
                    class="form-control @error('minimum_stock') is-invalid @enderror" value="{{ old('minimum_stock') }}"
                    min="0" placeholder="Enter Minimum Stock">

                @error('minimum_stock')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Description -->
            <div class="col-md-12">
                <label class="form-label">Description</label>

                <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror"
                    placeholder="Enter Description">{{ old('description') }}</textarea>

                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Status -->
            <div class="col-md-12">
                <label class="form-label">Status</label>

                <select name="status" class="form-select @error('status') is-invalid @enderror">

                    <option value="">Select Status</option>

                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>
                        Inactive
                    </option>

                </select>

                @error('status')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>

        <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">

            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                Cancel
            </button>

            <button type="submit" class="btn btn-primary">

                <i class="bi bi-check-circle"></i>
                Create Material

            </button>

        </div>

    </form>

</div>