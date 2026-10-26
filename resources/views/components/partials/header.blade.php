<header class="bg-base-100 border-b border-base-300 h-16 flex items-center px-6 gap-4 sticky top-0 z-30 shadow-sm">

    {{-- Mobile Hamburger --}}
    <button onclick="toggleSidebar()" class="btn btn-square btn-ghost lg:hidden">
        <i data-lucide="menu" class="w-5 h-5"></i>
    </button>

    {{-- Breadcrumb --}}
    <div class="flex-1">
        <div class="flex items-center gap-2 text-sm">
            <span class="text-base-content/50">Poliklinik</span>
            <i data-lucide="chevron-right" class="w-4 h-4 text-base-content/30"></i>
            <span class="font-semibold text-base-content">
                {{ $title ?? 'Dashboard' }}
            </span>
        </div>
    </div>

    {{-- Fullscreen --}}
    <button onclick="toggleFullscreen()" class="btn btn-square btn-ghost">
        <i id="fsIcon" class="fas fa-expand w-5 h-5"></i>
    </button>

    {{-- Lonceng Notifikasi --}}
    <div class="dropdown dropdown-end">
        <label tabindex="0" class="btn btn-ghost btn-circle relative">
            <i class="fas fa-bell text-lg text-slate-600"></i>
            @if (auth()->user()->unreadNotifications->count() > 0)
                <span class="badge badge-error badge-xs absolute top-2 right-2 w-2.5 h-2.5 rounded-full"></span>
            @endif
        </label>

        <div tabindex="0"
            class="dropdown-content z-[50] menu p-0 shadow-lg bg-base-100 rounded-2xl w-80 border border-slate-200 mt-2">
            <div class="p-3 border-b border-slate-100 flex items-center justify-between">
                <span class="font-bold text-sm text-slate-800">Notifikasi</span>
                @if (auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.markAllRead') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-blue-600 hover:underline">Tandai Semua Dibaca</button>
                    </form>
                @endif
            </div>

            <div class="max-h-80 overflow-y-auto divide-y divide-slate-100">
                @forelse (auth()->user()->notifications->take(5) as $notification)
                    <a href="{{ route('notifications.read', $notification->id) }}"
                        class="block p-3 hover:bg-slate-50 transition {{ $notification->read_at ? 'opacity-60' : 'bg-blue-50/40' }}">
                        <p class="text-xs font-bold text-slate-800">{{ $notification->data['title'] ?? 'Notifikasi' }}</p>
                        <p class="text-xs text-slate-600 mt-0.5 line-clamp-2">{{ $notification->data['message'] ?? '' }}</p>
                        <span
                            class="text-[10px] text-slate-400 mt-1 block">{{ $notification->created_at->diffForHumans() }}</span>
                    </a>
                @empty
                    <div class="p-4 text-center text-xs text-slate-400">Tidak ada notifikasi.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- User Info --}}
    <a href="{{ route('profile.index') }}" class="flex items-center gap-3 hover:opacity-80 transition cursor-pointer">

        <div class="text-right hidden sm:block">
            <div class="text-sm font-semibold leading-tight text-base-content">
                {{ auth()->user()->nama ?? auth()->user()->name ?? 'Pengguna' }}
            </div>
            <div class="text-xs text-base-content/50 leading-tight capitalize">
                {{ auth()->user()->role ?? 'Admin Sistem' }}
            </div>
        </div>

        <div class="avatar">
            <div
                class="w-10 h-10 rounded-full bg-primary text-primary-content flex items-center justify-center shadow-sm">
                <span class="text-sm font-semibold leading-none">
                    {{ strtoupper(substr(auth()->user()->nama ?? auth()->user()->name ?? 'U', 0, 1)) }}
                </span>
            </div>
        </div>

    </a>

</header>

<script>
    function toggleFullscreen() {
        const icon = document.getElementById('fsIcon');

        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
            icon.classList.remove('fa-expand');
            icon.classList.add('fa-compress');
        } else {
            document.exitFullscreen();
            icon.classList.remove('fa-compress');
            icon.classList.add('fa-expand');
        }
    }
</script>