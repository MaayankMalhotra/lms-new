@extends('admin.layouts.app')

@section('title', 'Staff Cleanup')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="bg-white/90 shadow rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-gray-900">Staff Cleanup</h1>
        <p class="text-sm text-gray-700 mt-2">
            Calls the remote API for staff IDs {{ $rangeLabel }}: <code class="text-xs bg-gray-100 px-1 py-0.5 rounded">/staff/delete_row</code>
            with the provided <code class="text-xs bg-gray-100 px-1 py-0.5 rounded">ci_session</code> cookie.
        </p>
        <form method="POST" action="{{ route('admin.staff-cleanup.run') }}" class="mt-6 space-y-4">
            @csrf
            <div class="grid md:grid-cols-2 gap-4">
                <label class="block text-sm text-gray-800">
                    Base URL
                    <input
                        type="text"
                        name="base_url"
                        required
                        value="{{ old('base_url', $defaults['base_url'] ?? '') }}"
                        placeholder="https://example.com/index.php"
                        class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    >
                </label>
                <label class="block text-sm text-gray-800">
                    ci_session cookie
                    <input
                        type="text"
                        name="session"
                        required
                        value="{{ old('session', $defaults['session'] ?? '') }}"
                        placeholder="paste your ci_session value"
                        class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    >
                </label>
            </div>

            @if ($errors->any())
                <div class="text-sm text-red-700 bg-red-50 border border-red-200 rounded p-3">
                    {{ implode(', ', $errors->all()) }}
                </div>
            @endif

            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Run cleanup
                </button>
                <span class="text-xs text-gray-600">
                    Sends one POST per ID; this may take a while. Keep the page open until the log appears.
                </span>
            </div>
        </form>
    </div>

    <div class="bg-white/90 shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Run log</h2>
                <p class="text-sm text-gray-600">Range: {{ $rangeLabel }}</p>
            </div>
            @if($stats)
                <div class="text-sm text-gray-700">
                    <div>Processed: {{ $stats['processed'] ?? 0 }}</div>
                    <div>Successful (2xx): {{ $stats['successful'] ?? 0 }}</div>
                </div>
            @endif
        </div>

        @if(!empty($logs))
            <pre class="mt-4 bg-gray-900 text-green-200 rounded p-4 text-sm overflow-x-auto whitespace-pre-wrap">{{ implode("\n", $logs) }}</pre>
        @else
            <p class="mt-4 text-sm text-gray-600">No run yet. Click "Run cleanup" to execute the script.</p>
        @endif
    </div>
</div>
@endsection
