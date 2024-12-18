<x-app-layout>
    <div class="container mt-5">
        <h6>Notifications</h6>

        <div class="d-flex justify-content-between mb-3">
            <form action="{{ route('notifications.clearAll') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Clear</button>
            </form>
        </div>

        @if ($notifications->isEmpty())
            <p>No notifications found.</p>
        @else
        <ul class="list-group">
    @foreach ($notifications as $notification)
        <li class="list-group-item notification-item {{ $notification->read_at ? '' : 'bg-light' }}" data-id="{{ $notification->id }}">
            @if (!empty($notification->data['asset_name']))
                <strong>{{ $notification->data['asset_name'] }}</strong> - 
            @endif
            {{ $notification->data['message'] }}

            @if (!empty($notification->data['asset_history']))
                <ul>
                    @foreach ($notification->data['asset_history'] as $asset_history)
                        <li>{{ $asset_history['description'] }} at {{ $asset_history['created_at'] }}</li>
                    @endforeach
                </ul>
            @endif
            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>

            <!-- View Button -->
            @if (!empty($notification->data['asset_id']))
                <a href="{{ route('assets.show', $notification->data['asset_id']) }}" class="btn btn-primary btn-sm float-end">View</a>
            @endif
        </li>
    @endforeach
</ul>
        @endif
    </div>
    <br><br><br><br>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mark all notifications as read when the notification icon is clicked
            $('#notificationIcon').on('click', function (e) {
                e.preventDefault();
                markAllNotificationsAsRead();
            });

            // Mark individual notification as read when a notification item is clicked
            $('.notification-item').on('click', function () {
                const notificationId = $(this).data('id');
                markNotificationAsRead(notificationId);
            });

            function markAllNotificationsAsRead() {
                $.ajax({
                    url: '{{ route("notifications.markAsRead") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $('#notificationCount').remove();
                        $('.notification-item').removeClass('bg-light');
                    },
                    error: function (xhr) {
                        console.log('Error:', xhr.responseText);
                    }
                });
            }

            function markNotificationAsRead(notificationId) {
                $.ajax({
                    url: '{{ url("/notifications/markAsRead") }}/' + notificationId,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $('#notificationCount').text(response.unreadCount);
                        $('.notification-item[data-id="' + notificationId + '"]').removeClass('bg-light');
                    },
                    error: function (xhr) {
                        console.log('Error:', xhr.responseText);
                    }
                });
            }
        });
    </script>
</x-app-layout>
