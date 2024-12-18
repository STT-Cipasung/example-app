<x-layout>
    <x-breadcrumbs class="mb-4" :links="['My Job Applications' => '#']"/>

    @forelse($applications as $application)
        <x-job-card :job="$application->job"></x-job-card>
    @empty
        <div class="rounded-md border border-dashed border-slate-300 p-8">
            <div class="text-center font-medium">No job applications yet</div>
            <div class="text-center">
                Go find some jobs <a class="text-indigo-500 hover:underline" href="{{ route('jobs.index') }}">here!</a>
            </div>
        </div>
    @endforelse
</x-layout>
