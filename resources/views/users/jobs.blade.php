<x-layout>
    <x-card class="p-10">
        <header>
            <h1 class="text-3xl text-center font-bold my-6 uppercase">
                Manage Your Jobs
            </h1>
        </header>

        <x-user-search/>

        <table class="w-full table-auto rounded-sm">
            <tbody>
                @unless($jobs->isEmpty())
                    @foreach($jobs as $job)
                        <tr class="border-gray-300">
                            <td class="px-4 py-8 border-t border-b border-gray-300 text-lg">
                                <a href="/jobs/{{ $job->id }}" class="hover:text-laravel">
                                    {{ $job->title }}
                                </a>
                            </td>
                            <td class="px-4 py-8 border-t border-b border-gray-300 text-lg">
                                <a
                                    href="/jobs/{{ $job->id }}/edit"
                                    class="text-blue-400 px-6 py-2 rounded-xl"
                                >
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                            </td>
                            <td class="px-4 py-8 border-t border-b border-gray-300 text-lg">
                                <form method="POST" action="/jobs/{{ $job->id }}">
                                    @csrf

                                    @method('DELETE')

                                    <button class="text-red-600">
                                        <i class="fa-solid fa-trash-can"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="border-gray-300">
                        <td class="px-4 py-8 border-t border-b border-gray-300 text-lg">
                            <p class="text-center">
                                No Listings Found
                            </p>
                        </td>
                    </tr>
                @endunless
            </tbody>
        </table>
    </x-card>

    <div class="mt-6 p-4">
        @if(request('search'))
            {{ $jobs->appends(['search' => request('search')])->links() }}
        @else
            {{ $jobs->links() }}
        @endif
    </div>
</x-layout>
