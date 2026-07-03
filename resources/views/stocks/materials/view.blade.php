@extends('layouts.main')

@section('content')

<main class="dashboard-content">

    <div class="container-fluid px-3 px-lg-4 py-4">

        <div class="page-heading">

            <div class="page-heading-copy">
                <span class="page-icon">
                    <i class="bi bi-box-seam"></i>
                </span>

                <div>
                    <p class="eyebrow mb-1">Management</p>
                    <h1 class="h3 mb-1">Material Details</h1>

                    <p class="text-muted mb-0">
                        View complete material information.
                    </p>
                </div>
            </div>

            <div class="heading-actions">
                <a href="{{ route('materials.index') }}"
                    class="btn btn-outline-secondary btn-sm">

                    <i class="bi bi-arrow-left"></i>
                    Back to Materials

                </a>
            </div>

        </div>

        <section class="row g-3">

            <!-- Left Side -->
            <div class="col-12 col-xl-4">

                <div class="panel h-100 text-center profile-card">

                    <div class="profile-hero">

                        <div class="profile-avatar bg-primary">
                            <i class="bi bi-box-seam fs-1"></i>
                        </div>

                        <h2 class="h5 mt-3 mb-1">
                            {{ $material->material_name }}
                        </h2>

                        <span class="badge {{ $material->status ? 'bg-success' : 'bg-danger' }}">
                            {{ $material->status ? 'Active' : 'Inactive' }}
                        </span>

                    </div>

                    <div class="info-list mt-4 text-start">

                        <div>
                            <span>Material Name</span>
                            <strong>{{ $material->material_name }}</strong>
                        </div>

                          <div>
                            <span>Material Category</span>
                            <strong>{{ $material->category->category_name }}</strong>
                        </div>

                        <div>
                            <span>Unit</span>
                            <strong>{{ $material->unit }}</strong>
                        </div>

                        <div>
                            <span>Minimum Stock</span>
                            <strong>{{ $material->minimum_stock }}</strong>
                        </div>

                        <div>
                            <span>Status</span>
                            <strong>
                                {{ $material->status ? 'Active' : 'Inactive' }}
                            </strong>
                        </div>

                    </div>

                </div>

            </div>

            <!-- Right Side -->
            <div class="col-12 col-xl-8">

                <!-- Material Overview -->
                <div class="panel mb-3">

                    <div class="panel-header">
                        <h2 class="h5 mb-0">
                            Material Overview
                        </h2>
                    </div>

                    <div class="row g-3">

                        <div class="col-md-3">
                            <div class="mini-card">
                                <span>Status</span>
                                <strong>
                                    {{ $material->status ? 'Active' : 'Inactive' }}
                                </strong>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mini-card">
                                <span>Material ID</span>
                                <strong>#{{ $material->id }}</strong>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mini-card">
                                <span>Unit</span>
                                <strong>{{ $material->unit }}</strong>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mini-card">
                                <span>Minimum Stock</span>
                                <strong>{{ $material->minimum_stock }}</strong>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Description -->
                <div class="panel mb-3">

                    <div class="panel-header">
                        <h2 class="h5 mb-0">
                            Description
                        </h2>
                    </div>

                    <p>
                        {{ $material->description ?? 'No description available.' }}
                    </p>

                </div>

                <!-- Recent Activity -->
                <div class="panel">

                    <div class="panel-header">
                        <h2 class="h5 mb-0">
                            Recent Activity
                        </h2>
                    </div>

                    <div class="activity-item">

                        <span class="activity-dot bg-primary"></span>

                        <div>

                            <p class="mb-1 fw-semibold">
                                Material Created
                            </p>

                            <p class="text-muted small mb-0">
                                {{ $material->created_at->diffForHumans() }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>

</main>

<style>
.profile-avatar{
    width:100px;
    height:100px;
    border-radius:50%;
    color:#fff;
    font-size:38px;
    font-weight:700;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:auto;
    border:4px solid #fff;
    box-shadow:0 4px 15px rgba(0,0,0,.15);
}
</style>

@endsection