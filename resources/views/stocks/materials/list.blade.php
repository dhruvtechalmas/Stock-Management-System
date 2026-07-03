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

        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#materialModal">

          <i class="bi bi-plus-circle"></i>
          Add Material

        </button>

      </div>

      <section class="panel">

        <div class="panel-header">

          <div class="d-flex align-items-center gap-3">

            <input class="form-control form-control-sm table-search" type="search" placeholder="Search Material"
              data-table-search="materialsTable">
              
            </div>
              <a href="#" class="btn btn-outline-secondary btn-sm ">
                <i class="bi bi-download"></i> Export PDF
              </a>


        </div>


    <div class="table-responsive">
      <table class="table align-middle mb-0" id="materialsTable" data-searchable-table>
        <thead>
          <tr>
            <th>#</th>
            <th>Material Name</th>
            <th>Material Category</th>
            <th>Unit</th>
            <th>Minimum Stock</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
          @foreach ($materials as $material)
            <tr>

              <td class="fw-semibold">
                {{ $loop->iteration }}
              </td>

              <td>
                {{ $material->material_name }}
              </td>

              <td>
                {{ $material->category->category_name }}
              </td>
              <td>
                {{ $material->unit }}
              </td>

              <td>
                {{ $material->minimum_stock }}
              </td>

              <td>
                <span class="badge {{ $material->status ? 'bg-success' : 'bg-danger' }}">
                  {{ $material->status ? 'Active' : 'Inactive' }}
                </span>
              </td>

              <td style="white-space: nowrap;">
                <i class="bi bi-calendar3 text-primary me-2"></i>
                {{ $material->created_at->format('M d, Y') }}
              </td>

              <td style="white-space: nowrap;">

                <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                  data-bs-target="#editMaterialModal{{ $material->id }}" title="Edit Material">
                  <i class="bi bi-pencil-square"></i>
                </button>

                <form action="{{ route('materials.destroy', $material->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')

                  <button type="submit" class="btn btn-outline-danger btn-sm"
                    onclick="return confirm('Are you sure you want to delete this material?')" title="Delete Material">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>

                <a href="{{ route('materials.show', $material->id) }}" class="btn btn-outline-info btn-sm"
                  title="View Material">
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



@endsection