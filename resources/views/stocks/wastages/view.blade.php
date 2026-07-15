@extends('layouts.main')

@section('content')

<main class="dashboard-content">

    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- Page Heading --}}
        <div class="page-heading">

            <div class="page-heading-copy">

                <span class="page-icon">
                    <i class="bi bi-exclamation-triangle"></i>
                </span>

                <div>

                    <p class="eyebrow mb-1">Transactions</p>

                    <h1 class="h3 mb-1">
                        Wastage Details
                    </h1>

                </div>

            </div>

            <a href="{{ route('wastages.index') }}"
                class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>

                Back

            </a>

        </div>

        <section class="panel">

            <div class="panel-header">

                <h5 class="mb-0">
                    Wastage Information
                </h5>

            </div>

            <div class="panel-body">

                <div class="row">

                    <div class="col-md-6 mb-4">

                        <label class="form-label text-muted">
                            Wastage No.
                        </label>

                        <div class="fw-semibold">

                            {{ $wastage->wastage_no }}

                        </div>

                    </div>

                    <div class="col-md-6 mb-4">

                        <label class="form-label text-muted">
                            Recorded By
                        </label>

                        <div class="fw-semibold">

                            {{ $wastage->recordedBy->name ?? '-' }}

                        </div>

                    </div>

                    <div class="col-md-6 mb-4">

                        <label class="form-label text-muted">
                            Material
                        </label>

                        <div class="fw-semibold">

                            {{ $wastage->material->material_name ?? '-' }}

                        </div>

                    </div>

                    <div class="col-md-6 mb-4">

                        <label class="form-label text-muted">
                            Quantity
                        </label>

                        <div>

                            <span class="badge bg-danger fs-6">

                                {{ number_format($wastage->quantity,3) }}
                                {{ $wastage->material->unit ?? '' }}

                            </span>

                        </div>

                    </div>

                    <div class="col-md-6 mb-4">

                        <label class="form-label text-muted">
                            Wastage Date
                        </label>

                        <div class="fw-semibold">

                            {{ \Carbon\Carbon::parse($wastage->wastage_date)->format('d M Y') }}

                        </div>

                    </div>

                    <div class="col-md-6 mb-4">

                        <label class="form-label text-muted">
                            Created At
                        </label>

                        <div class="fw-semibold">

                            {{ $wastage->created_at->format('d M Y h:i A') }}

                        </div>

                    </div>

                    <div class="col-md-12">

                        <label class="form-label text-muted">

                            Reason

                        </label>

                        <div class="border rounded p-3">

                            {{ $wastage->reason }}

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>

</main>

@endsection