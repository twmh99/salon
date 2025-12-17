@php
    $activeTask ??= '';
    $taskItems = [
        ['key' => 'view_schedule', 'label' => 'Lihat Jadwal Reservasi', 'href' => route('admin.schedules', ['tab' => 'view'])],
        ['key' => 'add_schedule', 'label' => 'Kelola Jadwal', 'href' => route('admin.schedules', ['tab' => 'add'])],
        ['key' => 'history_reservation', 'label' => 'Lihat Histori Reservasi', 'href' => route('admin.history')],
    ];
@endphp
<aside class="admin-taskbar">
    <div class="taskbar-header">
        <p class="mb-1 text-muted small">Admin</p>
        <strong>{{ $currentAdmin?->username }}</strong>
    </div>
    <div class="taskbar-menu mt-4">
        @foreach ($taskItems as $item)
            <a class="btn taskbar-btn {{ $activeTask === $item['key'] ? 'active' : '' }}" href="{{ $item['href'] }}">
                {{ $item['label'] }}
            </a>
        @endforeach
    </div>
    <div class="mt-4 pt-3 border-top border-light">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="btn btn-outline-rose w-100" type="submit">Log Out</button>
        </form>
    </div>
</aside>
