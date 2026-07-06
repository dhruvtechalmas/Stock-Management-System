<div class="modal fade" id="editMaterialCategoryModal{{ $category->id }}" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Material Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="px-2 px-md-3">

                    <form action="{{ route('material-category.update', $category->id) }}" method="POST"
                        class="needs-validation" novalidate>

                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            <!-- Category Name -->
                            <div class="col-md-12">
                                <label class="form-label">Category Name</label>

                                <input type="text" name="category_name"
                                    class="form-control @error('category_name') is-invalid @enderror"
                                    value="{{ old('category_name', $category->category_name) }}"
                                    placeholder="Enter Category Name">

                                {{-- @error('category_name')
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

                                    <option value="Active" {{ old('status', $category->status) == 'Active' ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="Inactive" {{ old('status', $category->status) == 'Inactive' ? 'selected' : '' }}>
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
                                Update Category

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>
</div>