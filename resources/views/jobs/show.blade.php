<x-layout>
    <main>
        <x-search/>

        <a href="/" class="inline-block text-black ml-4 mb-4">
            <i class="fa-solid fa-arrow-left"></i> Back to the Home page
        </a>

        <div class="mx-4">
            <x-show-job :job="$job" />
        </div>
    </main>
</x-layout>

<x-card class="mt-4 p-2 flex space-x-6">
    <a href="/jobs/{{ $job->id }}/edit">
        <i class="fa-solid fa-pencil"></i> Edit
    </a>

    <form method="POST" action="/jobs/{{ $job->id }}">
        @csrf
        @method('delete')
        <button class="text-red-500">
            <i class="fa-solid fa-trash"></i> Delete
        </button>
    </form>
</x-card>
