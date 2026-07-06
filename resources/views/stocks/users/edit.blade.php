<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Edit User
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <div class="px-2 px-md-3">

                    <form action="{{ route('users.update', $user->id) }}" method="POST" class="needs-validation"
                        novalidate>

                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            {{-- User Name --}}
                            <div class="col-md-12">

                                <label class="form-label">
                                    User Name
                                </label>

                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" placeholder="Enter User Name">

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Email --}}
                            <div class="col-md-12">

                                <label class="form-label">
                                    Email Address
                                </label>

                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" placeholder="Enter Email Address">

                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Password --}}
                            <div class="col-md-12">
                                <label class="form-label">Password</label>

                                <div class="input-group">
                                    <input type="password" id="edit_password{{ $user->id }}" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Leave blank to keep current password">

                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('edit_password{{ $user->id }}', this)">
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
                                <label class="form-label">Confirm Password</label>

                                <div class="input-group">
                                    <input type="password" id="edit_password_confirmation{{ $user->id }}"
                                        name="password_confirmation" class="form-control"
                                        placeholder="Confirm Password">

                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('edit_password_confirmation{{ $user->id }}', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Role --}}
                            <div class="col-md-6">
                                <label class="form-label">Role</label>

                                <input type="text" class="form-control"
                                    value="{{ $user->roles->first()?->name ?? 'Kitchen Staff' }}" readonly>

                                <input type="hidden" name="role"
                                    value="{{ $user->roles->first()?->name ?? 'Kitchen Staff' }}">
                            </div>

                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">

                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <button type="submit" class="btn btn-primary">

                                <i class="bi bi-check-circle"></i>
                                Update User

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>
</div>

<script>
    function togglePassword(fieldId, button) {

        const input = document.getElementById(fieldId);
        const icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>