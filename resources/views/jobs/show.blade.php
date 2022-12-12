<x-layout>
    <main>
        <a href="/" class="inline-block text-black ml-4 mb-4">
            <i class="fa-solid fa-arrow-left"></i> Back to the Home page
        </a>

        <div class="mx-4">
            <x-show-job :job="$job" />
        </div>
    </main>
</x-layout>
