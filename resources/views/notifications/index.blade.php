@extends('layout.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Notifications</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- Mark all as read button -->
                            <div class="text-right mb-3">
                                <form action="{{ route('notifications.markAllAsRead') }}" method="POST" id="mark-all-read-form">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">Mark All as Read</button>
                                </form>
                            </div>

                            <ul class="list-group">
                                @forelse($notifications as $notification)
                                    <li class="list-group-item d-flex justify-content-between align-items-center" id="notification-{{ $notification->id }}" style="{{ $notification->is_read ? 'background-color: #e0e0e0;' : '' }}">
                                        <div>
                                            <p>{{ $notification->message }}</p>

                                            <!-- Handle case where created_at might be null -->
                                            <small class="text-muted">
                                                {{ $notification->created_at ? $notification->created_at->diffForHumans() : 'Time not available' }}
                                            </small>
                                        </div>

                                        <!-- Show "Read" badge for read notifications -->
                                        @if($notification->is_read)
                                            <span class="badge badge-success">Read</span>
                                        @endif
                                    </li>
                                @empty
                                    <li class="list-group-item">No notifications found.</li>
                                @endforelse
                            </ul>

                            <!-- Add pagination links if using pagination -->
                            @if($notifications instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                <div class="mt-3">
                                    {{ $notifications->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div> <!-- content-wrapper -->
@endsection
