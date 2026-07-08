@extends('layouts.main')

@section('content')

    <main class="dashboard-content">

        <div class="container-fluid px-3 px-lg-4 py-4">

            <div class="page-heading">

                <div class="page-heading-copy">

                    <span class="page-icon">
                        <i class="bi bi-cart-check"></i>
                    </span>

                    <div>
                        <p class="eyebrow mb-1">Transaction</p>
                        <h1 class="h3 mb-1">
                            Material Request Details
                        </h1>
                    </div>

                </div>

                <div class="heading-actions">

                    <a href="{{ route('material-requests.index') }}" class="btn btn-outline-secondary btn-sm">

                        <i class="bi bi-arrow-left"></i>

                        Back to Material Requests

                    </a>

                </div>

            </div>

            <section class="row g-3">

                {{-- Left Card --}}
                <div class="col-lg-4">

                    <div class="panel h-100 text-center">

                        <div class="pt-4">

                            <div class="mb-3">

                                <i class="bi bi-cart-check" style="font-size:90px;color:#6c757d;"></i>

                            </div>

                            <h4>

                                {{ $materialRequest->request_no }}

                            </h4>

                            <p class="text-muted">
                                Requested By :
                                {{ $materialRequest->user->name ?? '-' }}
                            </p>

                            @if($materialRequest->status == 'pending')

                                <span class="badge bg-warning">
                                    Pending
                                </span>

                            @elseif($materialRequest->status == 'approved')

                                <span class="badge bg-primary">
                                    Approved
                                </span>

                            @elseif($materialRequest->status == 'completed')

                                <span class="badge bg-success">
                                    Completed
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Rejected
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

                {{-- Right Details --}}
                <div class="col-lg-8">

                    <div class="panel">

                        <div class="panel-header">

                            <h5 class="mb-0">

                                Material Request Information

                            </h5>

                        </div>

                        <div class="info-list">

                            <div>
                                <span>Request No</span>
                                <strong>{{ $materialRequest->request_no }}</strong>
                            </div>

                            <div>
                                <span>Requested By</span>
                                <strong>{{ $materialRequest->user->name ?? '-' }}</strong>
                            </div>

                            <div>
                                <span>Request Date</span>
                                <strong>{{ \Carbon\Carbon::parse($materialRequest->request_date)->format('d M Y') }}</strong>
                            </div>

                            <div>
                                <span>Status</span>

                                @if($materialRequest->status == 'pending')

                                    <span class="badge bg-warning">
                                        Pending
                                    </span>

                                @elseif($materialRequest->status == 'approved')

                                    <span class="badge bg-primary">
                                        Approved
                                    </span>

                                @elseif($materialRequest->status == 'completed')

                                    <span class="badge bg-success">
                                        Completed
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Rejected
                                    </span>

                                @endif

                            </div>

                            <div>
                                <span>Remarks</span>
                                <strong>{{ $materialRequest->remarks ?? '-' }}</strong>
                            </div>

                            <div>
                                <span>Approved By</span>
                                <strong>{{ $materialRequest->approvedBy->name ?? '-' }}</strong>
                            </div>

                            <div>
                                <span>Approved At</span>

                                <strong>

                                    {{ $materialRequest->approved_at ? \Carbon\Carbon::parse($materialRequest->approved_at)->format('d M Y h:i A') : '-' }}

                                </strong>

                            </div>

                            <div>
                                <span>Created At</span>
                                <strong>{{ $materialRequest->created_at->format('d M Y h:i A') }}</strong>
                            </div>

                            <div>
                                <span>Updated At</span>
                                <strong>{{ $materialRequest->updated_at->format('d M Y h:i A') }}</strong>
                            </div>

                        </div>

                    </div>

                </div>

            </section>

            {{-- Requested Materials --}}
            <section class="panel mt-4">

                <div class="panel-header">

                    <h5 class="mb-0">

                        Requested Materials

                    </h5>

                </div>

                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead>

                            <tr>

                                <th>#</th>
                                <th>Material</th>
                                <th>Unit</th>
                                <th>Requested Quantity</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($materialRequest->items as $item)

                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    <td>{{ $item->material->material_name }}</td>

                                    <td>{{ $item->material->unit }}</td>

                                    <td>{{ number_format($item->requested_qty, 2) }}</td>

                                </tr>

                                @empty

                            @endforelse

                        </tbody>

                    </table>

                    @if(auth()->user()->hasRole('Admin') && $materialRequest->status == 'pending')

                        <div class="d-flex justify-content-end gap-2 mt-4">

                            <form action="{{ route('material-requests.approve', $materialRequest->id) }}"
                                method="POST"
                                class="d-inline">

                                @csrf
                                @method('PATCH')

                                <button type="submit" class="btn btn-success">
                                    Approve
                                </button>

                            </form>

                            <form action="{{ route('material-requests.reject', $materialRequest->id) }}"
                                method="POST"
                                class="d-inline">

                                @csrf
                                @method('PATCH')

                                <button type="submit" class="btn btn-danger">
                                    Reject
                                </button>

                            </form>

                        </div>

                    @endif
                </div>

            </section>

        </div>

    </main>

@endsection