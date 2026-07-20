@extends('layouts.main')

@section('content')

  {{-- Add Material Modal --}}
  <div class="modal fade" id="materialModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title btn btn-outline-secondary">
            Add Material
          </h5>

          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          @include('stocks.materials.create')
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
            <p class="eyebrow mb-1">Master</p>
            <h1 class="h3 mb-1">Materials</h1>
          </div>

        </div>

        @can('material.create')

          <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#materialModal">

            <i class="bi bi-plus-circle"></i>
            Add Material

          </button>
        @endcan

      </div>

      <section class="panel">

        <div class="panel-header">

          <div class="d-flex align-items-center gap-3">

            <input class="form-control form-control-sm table-search" type="search" placeholder="Search Material"
              data-table-search="materialsTable">

          </div>
          {{-- <a href="#" class="btn btn-outline-secondary btn-sm ">
            <i class="bi bi-download"></i> Export PDF
          </a> --}}


        </div>


        <div class="table-responsive">
          <table class="table align-middle mb-0" id="materialsTable" data-searchable-table>
            <thead>
              <tr>
                <th>#</th>
                <th>Image</th>
                <th>Material Name</th>
                <th>Material Category</th>
                <th>Unit</th>
                <th>Current Stock</th>
                <th>Minimum Stock</th>
                <th>Status</th>
                {{-- <th>Created At</th> --}}
                <th>Action</th>
              </tr>
            </thead>

            <tbody>
              @foreach($materials as $material)
                <tr>

                  <td>{{ $loop->iteration }}</td>

                  {{-- Image --}}
                  <td>
                    @if($material->image)
                      <img src="{{ asset('storage/' . $material->image) }}" width="60" height="60"
                        class="rounded object-fit-cover" alt="{{ $material->material_name }}">
                    @else
                      <img src="{{ asset('images/no-image.png') }}" width="60" height="60" class="rounded object-fit-cover"
                        alt="No Image">
                    @endif
                  </td>

                  {{-- Material Name --}}
                  <td>{{ $material->material_name }}</td>

                  {{-- Category --}}
                  <td>{{ $material->category->category_name ?? '-' }}</td>

                  {{-- Unit --}}
                  <td>{{ $material->unit }}</td>

                  {{-- Current Stock --}}
                  <td>
                    <span class="badge bg-primary">
                      {{ $material->current_stock }}
                    </span>
                  </td>

                  {{-- Minimum Stock --}}
                  <td>{{ $material->minimum_stock }}</td>

                  {{-- Status --}}
                  <td>
                    <span class="badge {{ $material->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                      {{ $material->status }}
                    </span>
                  </td>

                  {{-- Created At --}}
                  {{-- <td style="white-space: nowrap;">
                    <i class="bi bi-calendar3 text-primary me-2"></i>
                    {{ $material->created_at->format('M d, Y') }}
                  </td> --}}

                  {{-- Action --}}
                  <td style="white-space: nowrap;">

                    @can('material.edit')
                      <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                        data-bs-target="#editMaterialModal{{ $material->id }}">
                        <i class="bi bi-pencil-square"></i>
                      </button>
                    @endcan

                    @can('material.delete')
                      <form action="{{ route('materials.destroy', $material->id) }}" method="POST"
                        class="d-inline delete-material-form">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-danger btn-sm delete-btn">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    @endcan

                    <a href="{{ route('materials.show', $material->id) }}" class="btn btn-outline-info btn-sm">
                      <i class="bi bi-eye"></i>
                    </a>

                  </td>

                </tr>

                @include('stocks.materials.edit', ['material' => $material])

              @endforeach
            </tbody>
          </table>
        </div>

        {{-- Pagination controls --}}
        <div class="d-flex justify-content-end mt-3">
          {{ $materials->links('pagination::bootstrap-4') }}
        </div>
      </section>
    </div>
  </main>


  <script>
    document.addEventListener('submit', function (e) {
      if (!e.target.classList.contains('delete-material-form')) return;

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
  </script>

  {{-- In show only model form error --}}
  @if ($errors->any())
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal(document.getElementById('materialModal'));
        modal.show();
      });
    </script>
  @endif

  {{-- when cancle form then refresh --}}
  @if ($errors->any())
    <script>
      document.addEventListener('DOMContentLoaded', function () {

        const modal = new bootstrap.Modal(document.getElementById('materialModal'));
        modal.show();

        document.getElementById('cancelMaterialBtn').addEventListener('click', function () {
          window.location.href = "{{ route('materials.index') }}";
        });

      });
    </script>
  @endif

  <script>

    document.addEventListener('DOMContentLoaded', function () {

      document.querySelectorAll('.modal').forEach(function (modal) {

        modal.addEventListener('hidden.bs.modal', function () {

          if (
            window.location.search ||
            document.querySelector('.invalid-feedback')
          ) {
            window.location.href = "{{ route('materials.index') }}";
          }

        });

      });

    });
    document.addEventListener('DOMContentLoaded', function () {

      document.querySelectorAll('.btn-close').forEach(function (closeButton) {

        closeButton.addEventListener('click', function () {
          window.location.href = "{{ route('materials.index') }}";
        });

      });

    });
  </script>
@endsection