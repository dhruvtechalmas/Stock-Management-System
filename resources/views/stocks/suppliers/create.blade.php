<div class="px-2 px-md-3">

    <form action="{{ route('suppliers.store') }}" method="POST">
        @csrf

        <div class="row g-3">

            {{-- Supplier Name --}}
            <div class="col-md-12">
                <label class="form-label">Company Name
                    <span>
                        <small class="text-danger">*</small>
                    </span>
                </label>

                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" placeholder="Enter Supplier Name">

                @error('name')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Contact Person --}}
            <div class="col-md-12">
                <label class="form-label">Contact Person
                     <span>
                        <small class="text-danger">*</small>
                    </span>
                </label>

                <input type="text" name="contact_person"
                    class="form-control @error('contact_person') is-invalid @enderror"
                    value="{{ old('contact_person') }}" placeholder="Enter Contact Person">

                @error('contact_person')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Phone --}}
            <div class="col-md-12">
                <label class="form-label">Phone Number
                     <span>
                        <small class="text-danger">*</small>
                    </span>
                </label>

                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                    value="{{ old('phone') }}" maxlength="10" inputmode="numeric" pattern="[0-9]{10}"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)"
                    placeholder="Enter 10 Digit Phone Number">

                @error('phone')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="col-md-12">
                <label class="form-label">Email Address</label>

                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="Enter Email Address">

                @error('email')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Address --}}
            <div class="col-md-12">
                <label class="form-label">Address
                     <span>
                        <small class="text-danger">*</small>
                    </span>
                </label>

                <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror"
                    placeholder="Enter Supplier Address">{{ old('address') }}</textarea>

                @error('address')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="col-md-12">
                <label class="form-label">Status
                     <span>
                        <small class="text-danger">*</small>
                    </span>
                </label>

                <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">

                    <option value="">Select Status</option>

                    <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>
                        Inactive
                    </option>

                </select>

                @error('is_active')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>

        <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">

            <button type="button" id="cancelSupplierBtn" class="btn btn-outline-secondary" data-bs-dismiss="modal">

                Cancel

            </button>

            <button type="submit" class="btn btn-primary">

                <i class="bi bi-check-circle"></i>
                Create Supplier

            </button>

        </div>

    </form>

</div>