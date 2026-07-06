@extends('layouts.main')

@section('content')

<main class="dashboard-content">

    <div class="container-fluid px-3 px-lg-4 py-4">

        <div class="page-heading">

            <div class="page-heading-copy">

                <span class="page-icon">
                    <i class="bi bi-truck"></i>
                </span>

                <div>
                    <p class="eyebrow mb-1">Master</p>
                    <h1 class="h3 mb-1">
                        Supplier Details
                    </h1>
                </div>

            </div>

            <div class="heading-actions">

                <a href="{{ route('suppliers.index') }}"
                    class="btn btn-outline-secondary btn-sm">

                    <i class="bi bi-arrow-left"></i>

                    Back to Suppliers

                </a>

            </div>

        </div>

        <section class="row g-3">

            <div class="col-lg-4">

                <div class="panel h-100 text-center">

                    <div class="pt-4">

                        <div class="mb-3">

                            <i class="bi bi-truck"
                                style="font-size:90px;color:#6c757d;"></i>

                        </div>

                        <h4>

                            {{ $supplier->name }}

                        </h4>

                        <p class="text-muted">

                            {{ $supplier->email ?: 'No Email' }}

                        </p>

                        <span class="badge {{ $supplier->is_active ? 'bg-success' : 'bg-danger' }}">

                            {{ $supplier->is_active ? 'Active' : 'Inactive' }}

                        </span>

                    </div>

                </div>

            </div>

            <div class="col-lg-8">

                <div class="panel">

                    <div class="panel-header">

                        <h5 class="mb-0">

                            Supplier Information

                        </h5>

                    </div>

                    <div class="info-list">

                        <div>

                            <span>Supplier Name</span>

                            <strong>{{ $supplier->name }}</strong>

                        </div>

                        <div>

                            <span>Contact Person</span>

                            <strong>{{ $supplier->contact_person ?: '-' }}</strong>

                        </div>

                        <div>

                            <span>Phone Number</span>

                            <strong>{{ $supplier->phone ?: '-' }}</strong>

                        </div>

                        <div>

                            <span>Email Address</span>

                            <strong>{{ $supplier->email ?: '-' }}</strong>

                        </div>

                        <div>

                            <span>Address</span>

                            <strong>{{ $supplier->address ?: '-' }}</strong>

                        </div>

                        <div>

                            <span>Status</span>

                            <strong>

                                {{ $supplier->is_active ? 'Active' : 'Inactive' }}

                            </strong>

                        </div>

                        <div>

                            <span>Created At</span>

                            <strong>{{ $supplier->created_at->format('d M Y') }}</strong>

                        </div>

                        <div>

                            <span>Updated At</span>

                            <strong>{{ $supplier->updated_at->format('d M Y') }}</strong>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>

</main>

@endsection