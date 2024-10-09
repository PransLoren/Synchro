@extends('layout.app')

@section('content')
<div class="container mt-4">
    <h3>Your Notifications</h3>
    @foreach($notifications as $notification)
        <div class="alert alert-{{ $notification->is_read ? 'secondary' : 'primary' }}">
            <p>{{ $notification->message }}</p>
            <small>{{ $notification->created_at->format('d-m-Y H:i') }}</small>
            @if(!$notification->is_read)
                <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success">Mark as Read</button>
                </form>
            @endif
        </div>
    @endforeach
</div>
@endsection
