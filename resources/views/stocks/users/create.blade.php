<div class="px-2 px-md-3">

    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation"
        novalidate>

        @csrf

        <div class="row g-3">

            {{-- User Image --}}
            {{-- <div class="col-md-12">
                <label class="form-label">Profile Image</label>

                <input type="file" name="profile_image"
                    class="form-control @error('profile_image') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp">

                <div class="form-text">
                    Allowed: JPG, JPEG, PNG, WEBP
                </div>

                @error('profile_image')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div> --}}

            {{-- User Name --}}
            <div class="col-md-12">
                <label class="form-label">User Name
                     <span>
                        <small class="text-danger">*</small>
                    </span>
                </label>

                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" placeholder="Enter User Name">

                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="col-md-12">
                <label class="form-label">Email Address
                     <span>
                        <small class="text-danger">*</small>
                    </span>
                </label>

                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="Enter Email Address">

                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Phone --}}
            {{-- <div class="col-md-6">
                <label class="form-label">Phone Number</label>

                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                    value="{{ old('phone') }}" placeholder="Enter Phone Number">

                @error('phone')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div> --}}


            {{-- Password --}}
            <div class="col-md-12">
                <label class="form-label">Password
                     <span>
                        <small class="text-danger">*</small>
                    </span>
                </label>

                <div class="input-group">
                    <input type="password" id="password" name="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Enter Password">

                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)">
                        <i class="bi bi-eye"></i>
                    </button>

                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            {{-- Confirm Password --}}
            <div class="col-md-12">
                <label class="form-label">Confirm Password
                     <span>
                        <small class="text-danger">*</small>
                    </span>
                </label>

                <div class="input-group">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                        placeholder="Confirm Password">

                    <button class="btn btn-outline-secondary" type="button"
                        onclick="togglePassword('password_confirmation', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            {{-- Role --}}
            <div class="col-md-6">
                <label class="form-label">Role</label>

                <input type="text" class="form-control" value="Kitchen Staff" readonly>

                <input type="hidden" name="role" value="Kitchen Staff">
            </div>

            {{-- Status
            <div class="col-md-12">
                <label class="form-label">Status</label>

                <select name="status" class="form-select @error('status') is-invalid @enderror">

                    <option value="">Select Status</option>

                    <option value="Active" {{ old('status')=='Active' ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="Inactive" {{ old('status')=='Inactive' ? 'selected' : '' }}>
                        Inactive
                    </option>

                </select>

                @error('status')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div> --}}

        </div>

        <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">

            <button type="button" id="cancelUserBtn" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                Cancel
            </button>

            <button type="submit" class="btn btn-primary">

                <i class="bi bi-person-plus-fill"></i>
                Create User

            </button>

        </div>

    </form>

</div>