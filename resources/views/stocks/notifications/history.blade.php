@extends('layouts.main')

@section('content')

  <main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">
      <div class="page-heading">
        <div class="page-heading-copy">
          <span class="page-icon">
            <i class="bi bi-bell"></i>
          </span>
          <div>
            <p class="eyebrow mb-1">Alerts</p>
            <h1 class="h3 mb-1">Notification History</h1>
            <p class="text-muted mb-0">
              Review all system alerts and updates.
            </p>
          </div>
        </div>
      </div>

      <section class="panel mt-3">
        <div class="panel-header d-flex justify-content-between align-items-center">
          <div>
            <h2 class="h5 mb-1 section-title">All Notifications</h2>
            <p class="text-muted small mb-0">Showing paginated historical notification logs.</p>
          </div>
          @if($notifications->contains('is_read', false))
            <button id="historyMarkAllReadBtn" class="btn btn-outline-primary btn-sm">
              <i class="bi bi-check2-all me-1"></i> Mark All as Read
            </button>
          @endif
        </div>

        <div class="table-responsive p-3">
          <table class="table align-middle mb-0">
            <thead>
              <tr>
                <th scope="col" style="width: 10%">Status</th>
                <th scope="col" style="width: 25%">Title</th>
                <th scope="col" style="width: 40%">Message</th>
                @if(auth()->user()->hasAnyRole(['Super Admin', 'Admin']))
                  <th scope="col" style="width: 10%">Audience</th>
                @endif
                <th scope="col" style="width: 15%">Received</th>
              </tr>
            </thead>
            <tbody>
              @forelse($notifications as $item)
                <tr style="{{ $item->is_read ? 'opacity: 0.65;' : 'font-weight: 600;' }}">
                  <td>
                    @if($item->is_read)
                      <span class="badge bg-secondary" style="font-size: 0.75rem;">Read</span>
                    @else
                      <span class="badge bg-danger" style="font-size: 0.75rem;">Unread</span>
                    @endif
                  </td>
                  <td>
                    <span style="color: var(--admin-text);">{{ $item->title }}</span>
                  </td>
                  <td>
                    <span class="small" style="color: var(--admin-muted); font-size: 0.8rem;">{{ $item->message }}</span>
                  </td>
                  @if(auth()->user()->hasAnyRole(['Super Admin', 'Admin']))
                    <td>
                      @if($item->user)
                        <span class="badge bg-info text-wrap" style="font-size: 0.75rem;">User: {{ $item->user->name }}</span>
                      @elseif($item->target_role)
                        <span class="badge bg-primary text-wrap" style="font-size: 0.75rem;">Role: {{ $item->target_role }}</span>
                      @else
                        <span class="badge bg-secondary" style="font-size: 0.75rem;">All</span>
                      @endif
                    </td>
                  @endif
                  <td style="white-space: nowrap; color: var(--admin-text); font-size: 0.8rem;">
                    <i class="bi bi-clock me-1" style="color: var(--admin-primary);"></i>
                    {{ $item->created_at->format('M d, Y h:i A') }}
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">
                    <i class="bi bi-bell-slash fs-4 d-block mb-2 text-secondary"></i>
                    <span>No notifications found in history.</span>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="d-flex justify-content-end p-3">
          {{ $notifications->links('pagination::bootstrap-4') }}
        </div>
      </section>
    </div>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const btn = document.getElementById('historyMarkAllReadBtn');
      if (btn) {
        btn.addEventListener('click', function () {
          $.ajax({
            url: "{{ route('api.notifications.read-all') }}",
            method: 'POST',
            data: {
              _token: "{{ csrf_token() }}"
            },
            success: function (res) {
              if (res.success) {
                location.reload();
              }
            },
            error: function (xhr) {
              console.error('Error marking all read:', xhr);
            }
          });
        });
      }
    });
  </script>

@endsection
