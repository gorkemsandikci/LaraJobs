<x-layout>
    <x-card class="p-10 text-center">
        <ul class="space-x-6 mr-6 text-lg">
            <li>
                <a class="font-bold">
                    Name:
                </a>

                <a class="hover:text-laravel">
                    {{ auth()->user()->name }}
                </a>
            </li>

            <li>
                <a class="font-bold">
                    Email:
                </a>

                <a class="hover:text-laravel">
                    {{ auth()->user()->email }}
                </a>
            </li>

            <li>
                <a href="" class="hover:text-laravel">
                    <i class="fa-solid fa-user-pen"></i> Edit Your Account
                </a>
            </li>

            <li>
                <a href="/users/jobs" class="hover:text-laravel">
                    <i class="fa-solid fa-newspaper"></i>
                    Manage Jobs You've Posted
                </a>
            </li>
        </ul>
    </x-card>
</x-layout>
