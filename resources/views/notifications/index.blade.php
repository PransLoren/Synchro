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
                            <ul class="list-group">
                                @forelse($notifications as $notification)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6>{{ $notification->title }}</h6>
                                            <p>{{ $notification->message }}</p>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                        @if(!$notification->is_read)
                                            <form action="{{ route('notifications.read', ['notificationId' => $notification->id]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">Mark as Read</button>
                                            </form>
                                        @endif
                                    </li>
                                @empty
                                    <li class="list-group-item">No notifications found.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div> <!-- content-wrapper -->
@endsection
