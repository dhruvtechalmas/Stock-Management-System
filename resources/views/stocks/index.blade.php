@extends('layouts.main')

@section('content')

  {{-- main content area --}}
  <main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
      <div class="page-heading">
        <div class="page-heading-copy">
          <span class="page-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
          <div>
            <p class="eyebrow mb-1">Overview</p>
            <h1 class="h3 mb-1">Dashboard</h1>
            <p class="text-muted mb-0">Recent Events, Upcoming Events, Participants, and Task from one clean workspace.
            </p>
          </div>
        </div>
        {{-- <div class="heading-actions"><button class="btn btn-outline-secondary btn-sm" type="button"><i
              class="bi bi-download" aria-hidden="true"></i> Export</button><button class="btn btn-primary btn-sm"
            type="button"><i class="bi bi-file-earmark-plus" aria-hidden="true"></i> Create Report</button></div> --}}
      </div>

      <section class="row g-3 mt-1" aria-label="Dashboard metrics">
        <div class="col-12 col-sm-6 col-lg">
          <article class="metric-card metric-primary">
            <div class="metric-top">
              <span class="metric-label">Total Events</span>
              <span class="metric-icon"><i class="bi-calendar-event" aria-hidden="true"></i></span>
            </div>
            <div class="metric-value">#</div>
            <div class="metric-meta">
              <span>Total Events</span>
            </div>
          </article>
        </div>

        <div class="col-12 col-sm-6 col-lg">
          <article class="metric-card metric-success">
            <div class="metric-top">
              <span class="metric-label">Upcoming Events</span>
              <span class="metric-icon"><i class="bi-calendar2-week" aria-hidden="true"></i></span>
            </div>
            <div class="metric-value">#
            </div>
            <div class="metric-meta">
              <span>Upcoming Events</span>
            </div>
          </article>
        </div>

        <div class="col-12 col-sm-6 col-lg">
          <article class="metric-card metric-warning">
            <div class="metric-top">
              <span class="metric-label">Total Participants</span>
              <span class="metric-icon"><i class="bi-people" aria-hidden="true"></i></span>
            </div>
            <div class="metric-value">#</div>
            <div class="metric-meta">
              <span>Total Participants</span>
            </div>
          </article>
        </div>

        <div class="col-12 col-sm-6 col-lg">
          <article class="metric-card metric-danger">
            <div class="metric-top">
              <span class="metric-label">Total Tasks</span>
              <span class="metric-icon"><i class="bi-list-task" aria-hidden="true"></i></span>
            </div>
            <div class="metric-value">#</div>
            <div class="metric-meta">
              <span>Total Tasks</span>
            </div>
          </article>
        </div>

        <div class="col-12 col-sm-6 col-lg">
          <article class="metric-card metric-info">
            <div class="metric-top">
              <span class="metric-label">Completed Tasks</span>
              <span class="metric-icon"><i class="bi-check-circle" aria-hidden="true"></i></span>
            </div>
            <div class="metric-value">#</div>
            <div class="metric-meta">
              <span>Completed Tasks</span>
            </div>
          </article>
        </div>
      </section>


      <!-- ================= Row 2 ================= -->

      <section class="row g-3 mt-3">

        <!-- Recent Events -->
        <div class="col-12 col-xl-6">
          <div class="panel h-100">

            <div class="panel-header">
              <div>
                <h2 class="h5 mb-1 section-title">
                  <i class="bi bi-calendar-event me-2"></i>
                  <span>Recent Events</span>
                </h2>

                <p class="text-muted mb-0">
                  Latest created events in the system.
                </p>
              </div>

              <a href="#" class="btn btn-light btn-sm">
                View All
              </a>
            </div>


          </div>
        </div>

        <!-- Upcoming Events -->
        <div class="col-12 col-xl-6">
          <div class="panel h-100">

            <div class="panel-header">
              <div>
                <h2 class="h5 mb-1 section-title">
                  <i class="bi bi-calendar2-week me-2"></i>
                  <span>Upcoming Events</span>
                </h2>

                <p class="text-muted mb-0">
                  Events scheduled for upcoming days.
                </p>
              </div>

              <a href="#" class="btn btn-light btn-sm">
                View All
              </a>
            </div>
          </div>
        </div>

      </section>

      <!-- ================= Row 3 ================= -->

      <section class="row g-3 mt-3">

        <!-- Pending Tasks -->
        <div class="col-12 col-xl-6">
          <div class="panel h-100">

            <div class="panel-header">
              <div>
                <h2 class="h5 mb-1 section-title">
                  <i class="bi bi-list-task me-2"></i>
                  <span>Pending Tasks</span>
                </h2>

                <p class="text-muted mb-0">
                  Tasks waiting for completion.
                </p>
              </div>

              <a href="#" class="btn btn-light btn-sm">
                View All
              </a>
            </div>

          </div>
        </div>

        <!-- Recent Notifications -->
        <div class="col-12 col-xl-6">
          <div class="panel h-100">

            <div class="panel-header">
              <div>
                <h2 class="h5 mb-1 section-title">
                  <i class="bi bi-bell me-2"></i>
                  <span>Recent Notifications</span>
                </h2>

                <p class="text-muted mb-0">
                  Latest activities and updates.
                </p>
              </div>

              <a href="#" class="btn btn-light btn-sm">
                View All
              </a>
            </div>

          </div>
        </div>

      </section>


      {{-- <section class="row g-3 mt-1">
        <div class="col-12 col-xl-8">
          <div class="panel">
            <div class="panel-header">
              <div>
                <h2 class="h5 mb-1 section-title"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i><span>Sales
                    Performance</span></h2>
                <p class="text-muted mb-0">Monthly revenue compared with operational targets.</p>
              </div>
              <a class="btn btn-light btn-sm" href="charts">View Details</a>
            </div>

            <div class="chart-bars" aria-label="Sales performance chart">
              <div class="chart-column bar-42"><span></span><small>Jan</small></div>
              <div class="chart-column bar-58"><span></span><small>Feb</small></div>
              <div class="chart-column bar-51"><span></span><small>Mar</small></div>
              <div class="chart-column bar-72"><span></span><small>Apr</small></div>
              <div class="chart-column bar-66"><span></span><small>May</small></div>
              <div class="chart-column bar-83"><span></span><small>Jun</small></div>
            </div>
          </div>
        </div>

        <div class="col-12 col-xl-4">
          <div class="panel h-100">
            <div class="panel-header">
              <div>
                <h2 class="h5 mb-1 section-title"><i class="bi bi-activity" aria-hidden="true"></i><span>Team
                    Activity</span></h2>
                <p class="text-muted mb-0">Recent operational updates.</p>
              </div>
            </div>

            <div class="activity-list">
              <div class="activity-item"><span class="activity-dot bg-primary"></span>
                <div>
                  <p class="mb-1 fw-semibold">New campaign launched</p>
                  <p class="text-muted small mb-0">Marketing team published the May offer.</p>
                </div>
              </div>
              <div class="activity-item"><span class="activity-dot bg-success"></span>
                <div>
                  <p class="mb-1 fw-semibold">Payment batch cleared</p>
                  <p class="text-muted small mb-0">246 invoices were processed successfully.</p>
                </div>
              </div>
              <div class="activity-item"><span class="activity-dot bg-warning"></span>
                <div>
                  <p class="mb-1 fw-semibold">Support queue rising</p>
                  <p class="text-muted small mb-0">Average first response time is 18 minutes.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section> --}}

      <section class="panel mt-3">
        <div class="panel-header">
          <div>
            <h2 class="h5 mb-1 section-title"><i class="bi bi-people" aria-hidden="true"></i><span>Recent Users</span>
            </h2>
            <p class="text-muted mb-0">Latest account activity across the workspace.</p>
          </div>
          @can('user.index')
            <a class="btn btn-outline-secondary btn-sm" href="users">Manage Users</a>
          @endcan
        </div>
        <div class="table-responsive">
          <table class="table align-middle mb-0" id="usersTable" data-searchable-table>
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">User Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">Role</th>
                <th scope="col">Joined</th>
                <th scope="col" class="text-dark">Action</th>
              </tr>
            </thead>
          </table>
        </div>
      </section>
    </div>
  </main>

@endsection