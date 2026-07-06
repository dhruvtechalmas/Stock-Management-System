<div class="px-2 px-md-3">

    <form action="{{ route('material-category.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf

        <div class="row g-3">

            {{-- Category Name --}}
            <div class="col-md-12">
                <label class="form-label">Category Name</label>

                <input type="text"
                    name="category_name"
                    class="form-control @error('category_name') is-invalid @enderror"
                    value="{{ old('category_name') }}"
                    placeholder="Enter Category Name">

                @error('category_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="col-md-12">
                <label class="form-label">Status</label>

                <select
                    name="status"
                    class="form-select @error('status') is-invalid @enderror">

                    <option value="">Select Status</option>

                    <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>
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

            <button type="button" id="cancelMaterialCategoryBtn" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                Cancel
            </button>

            <button
                type="submit"
                class="btn btn-primary">

                <i class="bi bi-check-circle"></i>
                Create Category

            </button>

        </div>

    </form>

</div>