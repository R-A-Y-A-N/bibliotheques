<nav x-data="{ open: false, notifOpen: false, date: '', time: '' }"
     x-init="setInterval(() => {
        let d = new Date();

        date = d.toLocaleDateString('fr-FR', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        time = d.toLocaleTimeString('fr-FR', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });

     }, 1000)"
     class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">

    @php $role = Auth::user()->role; @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- MENU -->
            <div class="flex items-center space-x-8">

                @if($role === 'admin')
                    <x-nav-link :href="url('/dashboard')">Dashboard</x-nav-link>
                    <x-nav-link :href="route('livres.index')">Gérer Livres</x-nav-link>
                    <x-nav-link :href="route('penalites.index')">Pénalités</x-nav-link>
                @endif

                @if($role === 'bibliothecaire')
                    <x-nav-link :href="route('livres.index')">Livres</x-nav-link>
                    <x-nav-link :href="url('/admin/emprunts')">Bibliothécaire</x-nav-link>
                    <x-nav-link :href="route('penalites.index')">Pénalités</x-nav-link>
                @endif

                @if($role === 'adherent')
                    <x-nav-link :href="route('livres.index')">Catalogue</x-nav-link>
                    <x-nav-link :href="url('/users/' . auth()->id() . '/emprunts')">Mes emprunts</x-nav-link>
                @endif

            </div>

            <!-- RIGHT SIDE -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-6">

                <!-- 📅 DATE + ⏰ HEURE (STYLE PRO) -->
                <div class="text-right">
                    <div class="text-xs text-gray-500 dark:text-gray-400 capitalize" x-text="date"></div>
                    <div class="text-lg font-semibold text-gray-700 dark:text-white" x-text="time"></div>
                </div>

                <!-- 🔔 NOTIFICATIONS -->
                <div class="relative">

                    <button @click="notifOpen = !notifOpen"
                            class="relative text-gray-600 hover:text-gray-800 dark:text-gray-300">

                        🔔

                        @if(auth()->user()->unreadNotifications->count())
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1 rounded-full">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <div x-show="notifOpen"
                         @click.away="notifOpen = false"
                         class="absolute right-0 mt-2 w-72 bg-white dark:bg-gray-700 border rounded shadow-lg z-50">

                        <div class="p-2 font-bold border-b dark:text-white">
                            Notifications
                        </div>

                        @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                            <div class="p-2 border-b text-sm dark:text-gray-200">
                                {{ $notification->data['message'] }}
                                <br>
                                <span class="text-gray-500 text-xs">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                        @empty
                            <div class="p-2 text-gray-500">
                                Aucune notification
                            </div>
                        @endforelse

                        <div class="p-2 text-center">
                            <form method="POST" action="{{ route('notifications.read') }}">
                                @csrf
                                <button class="text-blue-500 text-sm">
                                    Tout marquer comme lu
                                </button>
                            </form>
                        </div>

                    </div>
                </div>

                <!-- USER -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm text-gray-500 bg-white dark:bg-gray-800 rounded-md hover:text-gray-700">
                            <div>{{ Auth::user()->name }}</div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Déconnexion
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

            </div>

            <!-- MOBILE -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="p-2 rounded-md text-gray-400 hover:bg-gray-100">
                    ☰
                </button>
            </div>

        </div>
    </div>

</nav>
