@extends('layouts.main')

@section('content')

<main class="dashboard-content">

    <div class="container-fluid px-3 px-lg-4 py-4">

        <div class="page-heading">

            <div class="page-heading-copy">

                <span class="page-icon">
                    <i class="bi bi-person-circle"></i>
                </span>

                <div>
                    <p class="eyebrow mb-1">Settings</p>
                    <h1 class="h3 mb-1">
                        User Details
                    </h1>
                </div>

            </div>

            <div class="heading-actions">

                <a href="{{ route('users.index') }}"
                    class="btn btn-outline-secondary btn-sm">

                    <i class="bi bi-arrow-left"></i>

                    Back to Users

                </a>

            </div>

        </div>

        <section class="row g-3">

            <div class="col-lg-4">

                <div class="panel h-100 text-center">

                    <div class="pt-4">

                        <div class="mb-3">

                            <i class="bi bi-person-circle"
                                style="font-size:90px;color:#6c757d;"></i>

                        </div>

                        <h4>

                            {{ $user->name }}

                        </h4>

                        <p class="text-muted">

                            {{ $user->email }}

                        </p>

                        <span class="badge bg-primary">

                            {{ $user->roles->first()?->name ?? 'No Role' }}

                        </span>

                    </div>

                </div>

            </div>

            <div class="col-lg-8">

                <div class="panel">

                    <div class="panel-header">

                        <h5 class="mb-0">

                            User Information

                        </h5>

                    </div>

                    <div class="info-list">

                        <div>

                            <span>Name</span>

                            <strong>{{ $user->name }}</strong>

                        </div>

                        <div>

                            <span>Email</span>

                            <strong>{{ $user->email }}</strong>

                        </div>

                        <div>

                            <span>Role</span>

                            <strong>{{ $user->roles->first()?->name ?? 'No Role' }}</strong>

                        </div>

                        <div>

                            <span>Created At</span>

                            <strong>{{ $user->created_at->format('d M Y') }}</strong>

                        </div>

                        <div>

                            <span>Updated At</span>

                            <strong>{{ $user->updated_at->format('d M Y') }}</strong>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>

</main>

@endsection