@extends('layouts.main')

@section('content')

    <main class="dashboard-content">

        <div class="container-fluid px-3 px-lg-4 py-4">

            {{-- Page Heading --}}
            <div class="page-heading">

                <div class="page-heading-copy">

                    <span class="page-icon">
                        <i class="bi bi-box-seam"></i>
                    </span>

                    <div>

                        <p class="eyebrow mb-1">
                            Reports
                        </p>

                        <h1 class="h3 mb-0">
                            Current Stock Report
                        </h1>

                    </div>

                </div>

            </div>

            {{-- Filter --}}
            <section class="panel mb-4">

                <div class="panel-header">

                    <h5 class="mb-0">

                        Filter Report

                    </h5>

                </div>

                <div class="panel-body">

                    <form method="GET">

                        <div class="row align-items-end">

                            <div class="col-lg-3 col-md-6 mb-3">

                                <label class="form-label">
                                    Material
                                </label>

                                <select name="material_id" class="form-select">

                                    <option value="">All Materials</option>

                                    @foreach($materials as $material)

                                        <option value="{{ $material->id }}" {{ request('material_id') == $material->id ? 'selected' : '' }}>

                                            {{ $material->material_name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-lg-3 col-md-6 mb-3">

                                <label class="form-label">
                                    Category
                                </label>

                                <select name="category_id" class="form-select">

                                    <option value="">All Categories</option>

                                    @foreach($categories as $category)

                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>

                                            {{ $category->category_name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-lg-2 col-md-6 mb-3">

                                <label class="form-label">
                                    From Date
                                </label>

                                <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}" class="form-control" placeholder="Search Date">

                            </div>

                            <div class="col-lg-2 col-md-6 mb-3">

                                <label class="form-label">
                                    To Date
                                </label>

                                <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}" class="form-control" placeholder="Search Date">

                            </div>

                            <div class="col-lg-2 col-md-12 mb-3">

                                <label class="form-label invisible">
                                    Action
                                </label>

                                <div class="d-flex gap-2">

                                    <button type="submit" class="btn btn-primary">

                                        <i class="bi bi-search"></i>
                                        Filter

                                    </button>

                                    <a href="{{ route('current-stock-report.index') }}" class="btn btn-outline-secondary">

                                        <i class="bi bi-arrow-clockwise"></i>

                                    </a>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

            </section>

            {{-- Table --}}
            <section class="panel">

                {{-- <div class="panel-header">

                    <div class="d-flex align-items-center gap-3">

                        <input class="form-control form-control-sm table-search" type="search" placeholder="Search Material"
                            data-table-search="currentStockTable">

                    </div>

                </div> --}}

                <div class="table-responsive">

                    <table class="table align-middle mb-0" id="currentStockTable" data-searchable-table>

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Material</th>

                                <th>Category</th>

                                <th>Unit</th>

                                <th class="text-success">
                                    Opening
                                </th>

                                <th class="text-primary">
                                    Purchased
                                </th>

                                <th class="text-warning">
                                    Dispatched
                                </th>

                                <th class="text-info">
                                    Consumed
                                </th>

                                <th class="text-danger">
                                    Wastage
                                </th>

                                <th class="text-dark">
                                    Closing
                                </th>

                                <th>Status</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($materials as $material)

                                <tr>

                                    <td>

                                        {{ $loop->iteration }}

                                    </td>

                                    <td class="fw-semibold">

                                        {{ $material->material_name }}

                                    </td>

                                    <td>

                                        {{ $material->category?->category_name }}

                                    </td>

                                    <td>

                                        {{ $material->unit }}

                                    </td>

                                    <td class="text-success fw-bold">

                                        {{ number_format($material->opening_stock, 3) }}

                                    </td>

                                    <td>

                                        {{ number_format($material->purchased, 3) }}

                                    </td>

                                    <td>

                                        {{ number_format($material->dispatched, 3) }}

                                    </td>

                                    <td>

                                        {{ number_format($material->consumed, 3) }}

                                    </td>

                                    <td class="text-danger">

                                        {{ number_format($material->wastage, 3) }}

                                    </td>

                                    <td class="fw-bold">

                                        {{ number_format($material->closing_stock, 3) }}

                                    </td>

                                    <td>

                                        @if($material->current_stock <= $material->minimum_stock)

                                            <span class="badge bg-danger">

                                                Low Stock

                                            </span>

                                        @else

                                            <span class="badge bg-success">

                                                Available

                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="11" class="text-center py-5 text-muted">

                                        No Current Stock Report Found.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </section>

        </div>

    </main>

@endsection