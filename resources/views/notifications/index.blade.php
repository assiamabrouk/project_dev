@extends('layouts.app')

@section('title', 'Mes Notifications')

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Mes Notifications</h2>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div style="background-color: #dcfce7; color: #15803d; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif

        @if($notifications->isEmpty())
            <p>Aucune notification.</p>
        @else
            <ul style="list-style: none; padding: 0;">
                @foreach($notifications as $notification)
                    <li style="padding: 1rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; background-color: {{ $notification->lu ? 'transparent' : '#f0f9ff' }};">
                        <div>
                            <p style="margin-bottom: 0.25rem;">{{ $notification->message }}</p>
                            <small style="color: #6b7280;">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            @if(!$notification->lu)
                                <form action="{{ route('notifications.update', $notification->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm" style="color: #0284c7; background: none; border: none; cursor: pointer;" title="Marquer comme lu">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="color: #ef4444; background: none; border: none; cursor: pointer;" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection