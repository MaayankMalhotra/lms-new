@extends('admin.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Enrollment Report</h1>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <!-- NEW: Global Batch & Course selection -->
    <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4 bg-white p-4 rounded shadow">
        <div>
            <label class="block text-sm font-medium text-gray-700">Batch</label>
            <select id="global_batch_id" class="mt-1 block w-full border rounded p-2 bg-white">
                <option value="">-- Select Batch --</option>
                @foreach($batches as $b)
                    @php $sd = \Carbon\Carbon::parse($b->start_date); @endphp
                    <option value="{{ $b->id }}">
                        {{ $b->batch_name }} — starts {{ $sd->format('d M Y') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Course</label>
            <select id="global_course_id" class="mt-1 block w-full border rounded p-2 bg-white">
                <option value="">-- Select Course --</option>
                @foreach($courses as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end text-xs text-gray-500">
            Select a Batch and Course — each “Send Offer” will include these.
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">User</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Phone</th>
                    <th class="px-4 py-3 text-center">Internship</th>
                    <th class="px-4 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($enrollments as $row)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $row->name }}</td>
                        <td class="px-4 py-3">{{ $row->email }}</td>
                        <td class="px-4 py-3">{{ $row->phone }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($row->internship)
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Sent</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700">Not Sent</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($row->internship)
                                <button class="px-3 py-2 rounded bg-gray-200 text-gray-500 cursor-not-allowed" disabled>
                                    Offer Sent
                                </button>
                            @else
                                <!-- Fallback form (kept, and JS will inject batch/course before submit) -->
                                <form method="POST" action="{{ route('enrollment.send-offer') }}" class="inline offer-fallback-form">
                                    @csrf
                                    <input type="hidden" name="user_id"  value="{{ $row->user_id }}">
                                    <input type="hidden" name="email"    value="{{ $row->email }}">
                                    <input type="hidden" name="name"     value="{{ $row->name }}">
                                    <input type="hidden" name="batch_id" value="">
                                    <input type="hidden" name="course_id" value="">
                                    <button type="submit" class="hidden px-3 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">
                                        Send Offer
                                    </button>
                                </form>

                                <!-- AJAX button -->
                                <button
                                    class="send-offer-btn px-3 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white"
                                    data-user-id="{{ $row->user_id }}"
                                    data-email="{{ $row->email }}"
                                    data-name="{{ $row->name }}"
                                >
                                    Send Offer
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">No enrollments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
(function() {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const batchSelect  = document.getElementById('global_batch_id');
    const courseSelect = document.getElementById('global_course_id');

    function requireSelections() {
        const b = batchSelect.value;
        const c = courseSelect.value;
        if (!b || !c) {
            alert('Please select both Batch and Course before sending the offer.');
            return null;
        }
        return { batch_id: b, course_id: c };
    }

    function setLoading(btn, isLoading) {
        if (isLoading) {
            btn.dataset.originalText = btn.textContent;
            btn.textContent = 'Sending...';
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
        } else {
            btn.textContent = btn.dataset.originalText || 'Send Offer';
            btn.disabled = false;
            btn.classList.remove('opacity-75', 'cursor-not-allowed');
        }
    }

    async function sendOffer(btn) {
        const sel = requireSelections();
        if (!sel) return;

        try {
            setLoading(btn, true);

            const payload = {
                user_id:  btn.dataset.userId,
                email:    btn.dataset.email,
                name:     btn.dataset.name,
                batch_id: sel.batch_id,
                course_id: sel.course_id
            };

            const res = await fetch("{{ route('enrollment.send-offer') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify(payload),
                credentials: 'same-origin'
            });

            const data = await res.json();

            if (!res.ok || !data.success) {
                alert('Error: ' + (data?.message || 'Failed to send.'));
                setLoading(btn, false);
                return;
            }

            // Success UI update
            const statusCell = btn.closest('tr').querySelector('td:nth-child(4)');
            statusCell.innerHTML = '<span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Sent</span>';

            btn.replaceWith((() => {
                const disabled = document.createElement('button');
                disabled.className = 'px-3 py-2 rounded bg-gray-200 text-gray-500 cursor-not-allowed';
                disabled.textContent = 'Offer Sent';
                disabled.disabled = true;
                return disabled;
            })());

        } catch (err) {
            console.error(err);
            alert('Unexpected error. Check console/network tab.');
            setLoading(btn, false);
        }
    }

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.send-offer-btn');
        if (!btn) return;
        e.preventDefault();
        sendOffer(btn);
    });

    // Make the non-JS fallback include the selected batch/course (for users who click the hidden submit via devtools)
    document.querySelectorAll('.offer-fallback-form').forEach(form => {
        form.addEventListener('submit', () => {
            const sel = requireSelections();
            if (!sel) { event.preventDefault(); return; }
            form.querySelector('input[name="batch_id"]').value = sel.batch_id;
            form.querySelector('input[name="course_id"]').value = sel.course_id;
        });
    });
})();
</script>
@endsection
