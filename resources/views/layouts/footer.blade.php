<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>

<script src="{{ asset('/assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/assets/js/main.js') }}"></script>

@if(session('message'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: "{{ session('alert-type', 'success') }}",
                title: "{{ session('message') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,

                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

        });
    </script>
@endif

<script>
    window.adminHMDUser = {
        name: @json(auth()->user()->name),
        role: @json(auth()->user()->roles->first()?->name ?? 'Kitchen Staff'),
        avatar: @json(asset('assets/images/avatar/avatar-2.jpg'))
    };
</script>

<!-- flatpickr for date -->

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    flatpickr("#purchase_date", {
        altInput: true,
        altFormat: "d M Y",   // User sees: 15 Jul 2026
        dateFormat: "Y-m-d",  // Laravel receives: 2026-07-15
        minDate: "today",        //  future dates
        defaultDate: "{{ old('purchase_date', now()->format('Y-m-d')) }}"
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        flatpickr("#request_date", {
            altInput: true,
            altFormat: "d M Y",
            dateFormat: "Y-m-d",
            minDate: "today",        // future dates
            defaultDate: "{{ old('request_date') }}"
        });

    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        flatpickr("#wastage_date", {
            altInput: true,
            altFormat: "d M Y",      // Display: 15 Jul 2026
            dateFormat: "Y-m-d",     // Send to Laravel: 2026-07-15
            defaultDate: "{{ old('wastage_date', now()->format('Y-m-d')) }}",
            maxDate: "today",        // Prevent future dates
            allowInput: false
        });

        flatpickr("#from_date", {
            altInput: true,
            altFormat: "d M Y",
            dateFormat: "Y-m-d",
            allowInput: true
        });

        flatpickr("#to_date", {
            altInput: true,
            altFormat: "d M Y",
            dateFormat: "Y-m-d",
            allowInput: true
        });

    });
</script>

<script>

    document.addEventListener('DOMContentLoaded', function () {

        // if (document.querySelector('#material_id')) {
        //     new TomSelect('#material_id', {
        //         create: false,
        //         allowEmptyOption: true,
        //         placeholder: 'Select Material',
        //         maxOptions: 500,
        //         searchField: ['text'],
        //     });
        // }

        if (document.querySelector('#category_id')) {
            new TomSelect('#category_id', {
                create: false,
                allowEmptyOption: true,
                placeholder: 'Select Category',
                maxOptions: 500,
                searchField: ['text'],
            });
        }

    });
</script>

<!-- Pusher Broadcast Listener & Notification Manager -->
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Setup global variables for logged-in user role and ID
        const currentUser = window.adminHMDUser || { name: 'Guest', role: 'Kitchen Staff', avatar: '' };
        const currentUserId = {{ auth()->id() ?? 'null' }};
        const currentUserRole = currentUser.role;

        // Dom elements
        const dot = document.getElementById('notificationDot');
        const badge = document.getElementById('notificationCountBadge');
        const container = document.getElementById('notificationListContainer');

        // Audio helper: Play a subtle notification sound
        function playAlertSound() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioCtx.createOscillator();
                const gainNode = audioCtx.createGain();
                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);
                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(523.25, audioCtx.currentTime); // C5 note
                gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
                oscillator.start();
                oscillator.stop(audioCtx.currentTime + 0.15);
            } catch (e) {
                console.log('Audio Context blocked or not supported: ', e);
            }
        }

        // 2. Fetch notifications from DB on page load
        function fetchNotifications() {
            $.ajax({
                url: '/api/notifications',
                method: 'GET',
                success: function (notifications) {
                    renderNotifications(notifications);
                },
                error: function (xhr) {
                    console.error('Error fetching notifications:', xhr);
                }
            });
        }

        // 3. Render list inside dropdown
        function renderNotifications(notifications) {
            container.innerHTML = '';
            if (notifications.length === 0) {
                dot.classList.add('d-none');
                badge.innerText = '0';
                badge.classList.remove('bg-danger');
                badge.classList.add('bg-secondary');
                container.innerHTML = `
                    <div class="text-center p-4" style="color: var(--admin-muted);">
                        <i class="bi bi-bell-slash fs-4 mb-2 d-block"></i>
                        <span class="small">No new notifications</span>
                    </div>
                `;
                return;
            }

            // Show dot and badge count
            dot.classList.remove('d-none');
            badge.innerText = notifications.length;
            badge.classList.remove('bg-secondary');
            badge.classList.add('bg-danger');

            // Append each notification item
            notifications.forEach(item => {
                const timeAgo = formatTimeAgo(new Date(item.created_at));
                const notificationHtml = `
                    <a class="dropdown-item border-bottom p-3 notification-item" href="javascript:void(0);" data-id="${item.id}" style="white-space: normal; border-color: var(--admin-border) !important;">
                        <div class="d-flex align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold" style="color: var(--admin-text); font-size: 0.85rem;">${item.title}</h6>
                                <p class="mb-1 text-wrap" style="color: var(--admin-muted); font-size: 0.78rem; line-height: 1.35;">${item.message}</p>
                                <small style="color: var(--admin-primary); font-size: 0.72rem; font-weight: 500;">
                                    <i class="bi bi-clock me-1"></i>${timeAgo}
                                </small>
                            </div>
                        </div>
                    </a>
                `;
                $(container).append(notificationHtml);
            });

            // 4. Click event listener to mark as read
            $('.notification-item').on('click', function () {
                const id = $(this).data('id');
                const element = $(this);
                
                $.ajax({
                    url: `/api/notifications/${id}/read`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (res) {
                        if (res.success) {
                            // Smoothly fade out item
                            element.fadeOut(300, function () {
                                element.remove();
                                fetchNotifications(); // Refetch count and list
                            });
                        }
                    },
                    error: function (xhr) {
                        console.error('Error marking notification read:', xhr);
                    }
                });
            });

            // Mark all read button event listener
            $('#markAllReadBtn').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                
                $.ajax({
                    url: '/api/notifications/read-all',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (res) {
                        if (res.success) {
                            fetchNotifications();
                        }
                    },
                    error: function (xhr) {
                        console.error('Error marking all read:', xhr);
                    }
                });
            });
        }

        // Helper function for formatted relative time
        function formatTimeAgo(date) {
            const now = new Date();
            const diffMs = now - date;
            const diffSec = Math.floor(diffMs / 1000);
            const diffMin = Math.floor(diffSec / 60);
            const diffHr = Math.floor(diffMin / 60);

            if (diffSec < 60) return 'Just now';
            if (diffMin < 60) return `${diffMin}m ago`;
            if (diffHr < 24) return `${diffHr}h ago`;
            return date.toLocaleDateString();
        }

        // Initial fetch
        fetchNotifications();

        // 5. Connect to Pusher Websocket Channel
        const pusherKey = '{{ env('PUSHER_APP_KEY') }}';
        const pusherCluster = '{{ env('PUSHER_APP_CLUSTER') ?? 'mt1' }}';

        if (pusherKey) {
            // Pusher logs to console for easier debugging by fresher
            Pusher.logToConsole = true;

            const pusher = new Pusher(pusherKey, {
                cluster: pusherCluster,
                forceTLS: true
            });

            // Subscribe to the public notifications channel
            const channel = pusher.subscribe('notifications');

            // Listen for the 'new-notification' event
            channel.bind('new-notification', function (payload) {
                console.log('Real-time notification payload received:', payload);
                const notification = payload.notification;

                // Check if notification is directed to this user, their role, or if current user is Admin/Super Admin
                const isAdmin = currentUserRole === 'Super Admin' || currentUserRole === 'Admin';
                const isForMe = isAdmin || 
                              (notification.user_id && notification.user_id == currentUserId) || 
                              (notification.target_role && notification.target_role === currentUserRole);

                if (isForMe) {
                    playAlertSound();
                    
                    // Show a beautiful SweetAlert Toast notification in real-time
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: notification.title,
                        text: notification.message,
                        showConfirmButton: false,
                        timer: 6000,
                        timerProgressBar: true
                    });

                    // Reload the notification bell dropdown
                    fetchNotifications();
                }
            });
        } else {
            console.log('Pusher configuration missing in .env. Falling back to dynamic short polling...');
            // Fallback: Poll the server every 15 seconds to simulate real-time updates for local testing without internet
            setInterval(fetchNotifications, 15000);
        }
    });
</script>

</body>

</html>