<x-layout>
    <main>
        <x-search />

        <div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">
            @unless(count($jobs) === 0)
                @foreach($jobs as $job)
                    <x-index-job :job="$job" />
                @endforeach
            @else
                <p>No jobs found.</p>
            @endunless
        </div>

        <div class="mt-6 p-4">
            @if(request('tag'))
                {{ $jobs->appends(['tag' => request('tag')])->links() }}
            @elseif(request('search'))
                {{ $jobs->appends(['search' => request('search')])->links() }}
            @else
                {{ $jobs->links() }}
            @endif
        </div>
    </main>
</x-layout>
