@extends('layouts.main')

@section('content')

    {{-- Hidden template of material <option>s, read by the shared "Add Item" JS
        for every form on this page instead of re-rendering per modal. --}}
        <div id="materialOptionsTemplate" class="d-none">
            @foreach($materials as $material)
                <option value="{{ $material->id }}" data-unit="{{ $material->unit }}">
                    {{ $material->material_name }}
                </option>
            @endforeach
    </div>

    
    {{-- Add Purchase Modal --}}
    <div class="modal fade" id="materialRequestModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Material Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('stocks.material-request.create')
                </div>
            </div>
        </div>
    </div>
  

    <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">

            <div class="page-heading">
                <div class="page-heading-copy">
                    <span class="page-icon"><i class="bi bi-cart-check"></i></span>
                    <div>
                        <p class="eyebrow mb-1">Transaction</p>
                        <h1 class="h3 mb-1">Material Requests</h1>
                    </div>
                </div>

                @hasrole('Kitchen Staff')
                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#materialRequestModal">
                    <i class="bi bi-plus-circle"></i> Add Material Request
                </button>
                  @endhasrole
            </div>

            <section class="panel">
                <div class="panel-header">
                    <div class="d-flex align-items-center gap-3">
                        <input class="form-control form-control-sm table-search" type="search" placeholder="Search Material Request"
                            data-table-search="materialRequestTable">
                    </div>
                    <a href="#" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-download"></i> Export PDF
                    </a>
                </div>

                <div class="table-responsive">
                    {{-- ONLY <tr> content lives inside this table now. No modal
                        markup, no <div>, nothing else - that's what was
                            corrupting the layout before. --}}
                            <table class="table align-middle mb-0" id="materialRequestTable" data-searchable-table>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Request No</th>
                                        <th>Material Name</th>
                                        <th>Requested By</th>
                                        <th>Request Date</th>
                                        <th>Request Qty</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($materialRequests as $materialRequest)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><strong>{{ $materialRequest->request_no }}</strong></td>                                           
                                            <td>
                                                @foreach($materialRequest->items as $item)
                                                    {{ $item->material->material_name }}<br>
                                                @endforeach
                                            </td>                                           
                                            <td>{{ $materialRequest->user->name ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($materialRequest->request_date)->format('d M Y') }}</td>
                                            <td>
                                                {{ number_format($materialRequest->items->sum('requested_qty'), 2) }}
                                            </td>
                                             <td>
                                                <span class="badge {{ $materialRequest->status == 'pending' ? 'bg-success' : ($materialRequest->status == 'approved' ? 'bg-primary' : 'bg-danger') }}">
                                                    {{ $materialRequest->status }}
                                                </span>
                                            </td>
                   
                                            <td style="white-space: nowrap;">
                                                <i class="bi bi-calendar3 text-primary me-2"></i>
                                                {{ $materialRequest->created_at->format('M d, Y') }}
                                            </td>
                                            <td style="white-space: nowrap;">

                                                @hasrole('Kitchen Staff')
                                                <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editMaterialRequestModal{{ $materialRequest->id }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                @endhasrole

                                                @hasrole('Kitchen Staff')
                                                <form action="{{ route('material-requests.destroy', $materialRequest->id) }}" method="POST"
                                                    class="d-inline delete-materialRequest-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                                @endhasrole

                                                <a href="{{ route('material-requests.show', $materialRequest->id) }}"
                                                    class="btn btn-outline-info btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">No Material Request Found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            {{ $materialRequests->links('pagination::bootstrap-4') }}
                        </div>
            </section>

        </div>
    </main>

    {{-- Edit modals: rendered ONCE, in a SINGLE loop, completely outside the
    <table>. This is the fix for the broken layout in the screenshot. --}}
        @foreach($materialRequests as $materialRequest)
            @include('stocks.material-request.edit', ['materialRequest' => $materialRequest])
        @endforeach


        
            {{-- Open Create Modal --}}
            @if($errors->create->any())

            <script>

            document.addEventListener('DOMContentLoaded',function(){

                new bootstrap.Modal(
                    document.getElementById('materialRequestModal')
                ).show();

            });

            </script>

            @endif


            {{-- Open Edit Modal --}}
            @if(session()->has('edit_material_request_id') && $errors->edit->any())

            <script>

            document.addEventListener('DOMContentLoaded',function(){

                new bootstrap.Modal(

                    document.getElementById(
                        'editMaterialRequestModal{{ session("edit_material_request_id") }}'
                    )

                ).show();

            });

            </script>

            @endif

        
            <script>

document.addEventListener('DOMContentLoaded',function(){

    document.querySelectorAll('.modal').forEach(function(modal){

        modal.addEventListener('hidden.bs.modal',function(){

            if(
                window.location.search ||
                document.querySelector('.invalid-feedback')
            ){
                window.location.href="{{ route('material-requests.index') }}";
            }

        });

    });

});


document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.btn-close').forEach(function (closeButton) {

        closeButton.addEventListener('click', function () {
            window.location.href = "{{ route('material-requests.index') }}";
        });

    });

});

</script>
{{-- 
        @if ($errors->any())


        <script>

            document.addEventListener('DOMContentLoaded', function () {

                document.querySelectorAll('[id^="cancelMaterialRequestBtn"]').forEach(function(btn){

                    btn.addEventListener('click', function(){

                        window.location.href = "{{ route('material-requests.index') }}";

                    });

                });

            });

            </script>
        @endif --}}

        
@endsection