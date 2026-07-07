<div class="modal fade" id="editMaterialModal{{ $material->id }}" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="px-2 px-md-3">

                    <form action="{{ route('materials.update', $material->id) }}" method="POST"
                        enctype="multipart/form-data" class="needs-validation" novalidate>

                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            <!-- Material Name -->
                            <div class="col-md-12">
                                <label class="form-label">Material Name</label>

                                <input type="text" name="material_name"
                                    class="form-control @error('material_name') is-invalid @enderror"
                                    value="{{ old('material_name', $material->material_name) }}"
                                    placeholder="Enter Material Name">

                                {{-- @error('material_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror --}}
                            </div>

                            <!-- Material Category -->
                            <div class="col-md-12">
                                <label class="form-label">Category</label>

                                <select name="material_category_id"
                                    class="form-select @error('material_category_id') is-invalid @enderror">

                                    <option value="">Select Category</option>

                                    @foreach ($categories as $category)

                                        <option value="{{ $category->id }}" {{ old('material_category_id', $material->material_category_id) == $category->id ? 'selected' : '' }}>

                                            {{ $category->category_name }}

                                        </option>

                                    @endforeach

                                </select>

                                {{-- @error('material_category_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror --}}
                            </div>

                            <!-- Material Image -->
                            <div class="col-md-12">
                                <label class="form-label">Material Image</label>

                                @if($material->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $material->image) }}" width="80" height="80"
                                            class="rounded object-fit-cover">
                                    </div>
                                @endif

                                <input type="file" name="image"
                                    class="form-control @error('image') is-invalid @enderror"
                                    accept=".jpg,.jpeg,.png,.webp">

                                {{-- @error('image')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror --}}
                            </div>

                            <!-- Unit -->
                            <div class="col-md-6">
                                <label class="form-label">Unit</label>

                                <select name="unit" class="form-select @error('unit') is-invalid @enderror">

                                    <option value="">Select Unit</option>

                                    <option value="Kg" {{ old('unit', $material->unit) == 'Kg' ? 'selected' : '' }}>
                                        Kg
                                    </option>

                                    <option value="Liter" {{ old('unit', $material->unit) == 'Liter' ? 'selected' : '' }}>
                                        Liter
                                    </option>

                                    <option value="Piece" {{ old('unit', $material->unit) == 'Piece' ? 'selected' : '' }}>
                                        Piece
                                    </option>

                                    <option value="Gram" {{ old('unit', $material->unit) == 'Gram' ? 'selected' : '' }}>
                                        Gram
                                    </option>

                                    <option value="Milligram" {{ old('unit', $material->unit) == 'Milligram' ? 'selected' : '' }}>
                                        Milligram
                                    </option>

                                    <option value="Box" {{ old('unit', $material->unit) == 'Box' ? 'selected' : '' }}>
                                        Box
                                    </option>

                                    <option value="Set" {{ old('unit', $material->unit) == 'Set' ? 'selected' : '' }}>
                                        Set
                                    </option>

                                    <option value="Pack" {{ old('unit', $material->unit) == 'Pack' ? 'selected' : '' }}>
                                        Pack
                                    </option>

                                </select>

                                {{-- @error('unit')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror --}}
                            </div>

                            <!-- Current Stock -->
                            <div class="col-md-6">
                                <label class="form-label">Current Stock</label>

                                <input type="number" name="current_stock"
                                    class="form-control @error('current_stock') is-invalid @enderror"
                                    value="{{ old('current_stock', $material->current_stock) }}" min="0" step="0.01" placeholder="Enter Current Stock">

                                {{-- @error('current_stock')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror --}}
                            </div>

                            <!-- Minimum Stock -->
                            <div class="col-md-6">
                                <label class="form-label">Minimum Stock</label>

                                <input type="number" name="minimum_stock"
                                    class="form-control @error('minimum_stock') is-invalid @enderror"
                                    value="{{ old('minimum_stock', $material->minimum_stock) }}" min="0" step="0.01" placeholder="Enter Minimum Stock">

                                {{-- @error('minimum_stock')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror --}}
                            </div>

                            <!-- Description -->
                            <div class="col-md-12">
                                <label class="form-label">Description</label>

                                <textarea name="description" rows="3"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Enter Description">{{ old('description', $material->description) }}</textarea>

                                {{-- @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror --}}
                            </div>

                            <!-- Status -->
                            <div class="col-md-12">
                                <label class="form-label">Status</label>

                                <select name="status" class="form-select @error('status') is-invalid @enderror">

                                    <option value="">Select Status</option>

                                    <option value="Active" {{ old('status', $material->status ?? '') == 'Active' ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="Inactive" {{ old('status', $material->status ?? '') == 'Inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>

                                </select>

                                {{-- @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror --}}
                            </div>

                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">

                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <button type="submit" class="btn btn-primary">

                                <i class="bi bi-check-circle"></i>
                                Update Material

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>
</div>