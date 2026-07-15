@extends('layouts.main')

@section('content')

  {{-- Add Material Modal --}}
  <div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title btn btn-outline-secondary">
            Add User
          </h5>

          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          @include('stocks.users.create')
        </div>

      </div>
    </div>
  </div>

  {{-- Main Content --}}
  <main class="dashboard-content">

    <div class="container-fluid px-3 px-lg-4 py-4">

      <div class="page-heading">

        <div class="page-heading-copy">

          <span class="page-icon">
            <i class="bi bi-box-seam"></i>
          </span>

          <div>
            <p class="eyebrow mb-1">Setting</p>
            <h1 class="h3 mb-1">Users</h1>
          </div>

        </div>

        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#userModal">

          <i class="bi bi-plus-circle"></i>
          Add User

        </button>

      </div>

      <section class="panel">

        <div class="panel-header">

          <div class="d-flex align-items-center gap-3">

            <input class="form-control form-control-sm table-search" type="search" placeholder="Search User"
              data-table-search="usersTable">

          </div>
          {{-- <a href="#" class="btn btn-outline-secondary btn-sm ">
            <i class="bi bi-download"></i> Export PDF
          </a> --}}


        </div>


        <div class="table-responsive">
          <table class="table align-middle mb-0" id="usersTable" data-searchable-table>
            <thead>
              <tr>
                <th>#</th>
                <th>User Name</th>
                <th>User Email</th>
                <th>User Role</th>
                {{-- <th>Status</th> --}}
                <th>Created At</th>
                <th>Action</th>
              </tr>
            </thead>

            <tbody>
              @foreach($users as $user)
                <tr>

                  <td>{{ $loop->iteration }}</td>

                  {{-- User Name --}}
                  <td>{{ $user->name }}</td>

                  {{-- User Email --}}
                  <td>{{ $user->email }}</td>

                  {{-- User Role --}}
                  <td>
                    @if($user->roles->isNotEmpty())
                      <span class="badge bg-primary">
                        {{ $user->roles->first()->name }}
                      </span>
                    @else
                      <span class="badge bg-secondary">
                        No Role
                      </span>
                    @endif
                  </td>

                  {{-- Status
                  <td>
                    <span class="badge {{ $user->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                      {{ $user->status }}
                    </span>
                  </td> --}}
                  
                  {{-- Created At --}}
                  <td style="white-space: nowrap;">
                    <i class="bi bi-calendar3 text-primary me-2"></i>
                    {{ $user->created_at->format('M d, Y') }}
                  </td>

                  {{-- Action --}}
                  <td style="white-space: nowrap;">

                    <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                      data-bs-target="#editUserModal{{ $user->id }}">
                      <i class="bi bi-pencil-square"></i>
                    </button>

                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')

                      <button type="submit" class="btn btn-outline-danger btn-sm delete-btn">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>

                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-outline-info btn-sm">
                      <i class="bi bi-eye"></i>
                    </a>

                  </td>

                </tr>

                @include('stocks.users.edit', ['user' => $user])

              @endforeach
            </tbody>
          </table>
        </div>

        {{-- Pagination controls --}}
        <div class="d-flex justify-content-end mt-3">
          {{ $users->links('pagination::bootstrap-4') }}
        </div>
      </section>
    </div>
  </main>


  <script>
    document.querySelectorAll('.delete-form').forEach(form => {

      form.addEventListener('submit', function (e) {

        e.preventDefault();

        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to recover this record!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Delete it!'
        }).then((result) => {

          if (result.isConfirmed) {
            form.submit();
          }

        });

      });

    });
  </script>


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



  @if ($errors->any())
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal(document.getElementById('userModal'));
        modal.show();
      });
    </script>
  @endif


  @if ($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = new bootstrap.Modal(document.getElementById('userModal'));
    modal.show();

    document.getElementById('cancelUserBtn').addEventListener('click', function () {
        window.location.href = "{{ route('users.index') }}";
    });

});
</script>
@endif

@endsection