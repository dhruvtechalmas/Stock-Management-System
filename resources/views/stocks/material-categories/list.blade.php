@extends('layouts.main')

@section('content')

{{-- Add Category Modal --}}
<div class="modal fade" id="materialCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title btn btn-outline-secondary">
                    Add Material Category
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                @include('stocks.material-categories.create')
            </div>

        </div>
    </div>
</div>

<main class="dashboard-content">

    <div class="container-fluid px-3 px-lg-4 py-4">

        <div class="page-heading">

            <div class="page-heading-copy">

                <span class="page-icon">
                    <i class="bi bi-tags"></i>
                </span>

                <div>
                    <p class="eyebrow mb-1">Master</p>
                    <h1 class="h3 mb-1">Material Categories</h1>
                </div>

            </div>

            @can('material-category.create')
                
            <button
                class="btn btn-outline-primary btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#materialCategoryModal">

                <i class="bi bi-plus-circle"></i>
                Add Category

            </button>
            @endcan

        </div>

        <section class="panel">

            <div class="panel-header">

                <div class="d-flex align-items-center gap-3">

                    <input
                        class="form-control form-control-sm table-search"
                        type="search"
                        placeholder="Search Category"
                        data-table-search="materialCategoriesTable">

                </div>

                {{-- <a href="#" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-download"></i>
                    Export PDF
                </a> --}}

            </div>

            <div class="table-responsive">

                <table class="table align-middle mb-0"
                    id="materialCategoriesTable"
                    data-searchable-table>

                    <thead>

                        <tr>

                            <th>#</th>
                            <th>Category Name</th>
                            <th>Status</th>
                            {{-- <th>Created At</th> --}}
                            @can('material-category.edit')                              
                            <th>Action</th>
                            @endcan

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($categories as $category)

                            <tr>

                                <td class="fw-semibold">
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $category->category_name }}
                                </td>

                                <td>

                                    <span class="badge {{ $category->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $category->status }}
                                    </span>

                                </td>

                                {{-- <td>

                                    <i class="bi bi-calendar3 text-primary me-2"></i>

                                    {{ $category->created_at->format('M d, Y') }}

                                </td> --}}

                                <td style="white-space: nowrap;">

                                    @can('material-category.edit')
                                    <button
                                        class="btn btn-outline-success btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editMaterialCategoryModal{{ $category->id }}"
                                        title="Edit Category">

                                        <i class="bi bi-pencil-square"></i>

                                    </button>
                                    @endcan

                                    @can('material-category.delete')
                                    <form
                                        action="{{ route('material-category.destroy', $category->id) }}"
                                        method="POST"
                                        class="d-inline delete-material-category-form">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>
                                    @endcan

                                </td>

                            </tr>

                            @include('stocks.material-categories.edit', [
                                'category' => $category
                            ])

                        @empty

                            <tr>

                                <td colspan="5" class="text-center text-muted py-4">

                                    No Material Categories Found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-end mt-3">

                {{ $categories->links('pagination::bootstrap-4') }}

            </div>

        </section>

    </div>

</main>


  @if ($errors->any())
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal(document.getElementById('materialCategoryModal'));
        modal.show();
      });
    </script>
  @endif


  @if ($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = new bootstrap.Modal(document.getElementById('materialCategoryModal'));
    modal.show();

    document.getElementById('cancelMaterialCategoryBtn').addEventListener('click', function () {
        window.location.href = "{{ route('material-category.index') }}";
    });

});
</script>
@endif


<script>
            document.addEventListener('DOMContentLoaded', function () {

                document.querySelectorAll('.btn-close').forEach(function (closeButton) {

                    closeButton.addEventListener('click', function () {
                        window.location.href = "{{ route('material-category.index') }}";
                    });

                });

            });
        </script>

<script>
    document.addEventListener('submit', function (e) {
      if (!e.target.classList.contains('delete-material-category-form')) return;

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
@endsection