<nav class="flex justify-between items-center mb-4">
    <a href="/">
        <img class="w-24" src="{{ asset('images/logo.png') }}" alt="" class="logo"/>
    </a>

    <ul class="flex space-x-6 mr-6 text-lg">
        @auth
            <li class="hover:text-laravel font-bold">
                Welcome, {{ auth()->user()->name }}
            </li>

            <li>
                <a href="/users/jobs" class="hover:text-laravel">
                    <i class="fa-solid fa-gear"></i>
                    Manage Your Jobs
                </a>
            </li>

            <li>
                <form class="hover:text-laravel" method="POST" action="/logout">
                    @csrf

                    <button type="submit">
                        <i class="fa-solid fa-door-closed"></i> Logout
                    </button>
                </form>
            </li>
        @else
            <li>
                <a href="/register" class="hover:text-laravel">
                    <i class="fa-solid fa-user-plus"></i> Register
                </a>
            </li>

            <li>
                <a href="/login" class="hover:text-laravel">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> Login
                </a>
            </li>
        @endauth
    </ul>
</nav>
