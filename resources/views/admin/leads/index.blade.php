@extends('admin.layouts.app')

@section('title', 'Student Leads')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-3xl font-bold">📋 Student Leads</h1>
        <div class="flex gap-3">
            <a href="{{ route('leads.export') }}"
               class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-900 text-white font-semibold text-sm shadow transition">
                📥 Export CSV
            </a>
            <button id="openBulkModal" class="px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm shadow">
                ✉ Send Email to All Leads
            </button>
        </div>
    </div>

    @if($leads->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($leads as $lead)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all p-5">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800">{{ $lead->name }}</h2>
                        <span class="text-xs text-gray-500">{{ $lead->created_at->format('d M Y') }}</span>
                    </div>

                    <p class="text-sm text-gray-600 mt-2"><strong>Email:</strong> {{ $lead->email ?? '—' }}</p>
                    <p class="text-sm text-gray-600"><strong>Phone:</strong> {{ $lead->phone ?? '—' }}</p>

                    <button 
                        onclick="openEmailModal('{{ $lead->id }}', '{{ $lead->email }}')" 
                        class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-sm font-bold">
                        ✉ Send Email
                    </button>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $leads->links() }}
        </div>
    @else
        <div class="text-center py-12 text-gray-500">
            🚫 No leads found
        </div>
    @endif
</div>

{{-- ============ EMAIL MODAL ============ --}}
<div id="emailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-[95%] max-w-2xl p-6 relative">
        <button onclick="closeEmailModal()" class="absolute top-3 right-3 text-gray-500 hover:text-red-600 text-2xl">&times;</button>
        
        <h2 class="text-2xl font-bold mb-4">✉ Send Email</h2>
        <form id="emailForm">
            @csrf
            <input type="hidden" name="lead_id" id="lead_id">

            <div class="mb-4">
                <label class="block text-sm font-medium">To</label>
                <input type="text" id="lead_email" class="w-full mt-1 p-3 border rounded-lg bg-gray-100" readonly>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Subject</label>
                <input type="text" name="subject" class="w-full mt-1 p-3 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Message</label>
                <textarea id="editor" name="message" rows="6" class="w-full border rounded-lg"></textarea>
            </div>
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-bold">
                🚀 Send
            </button>
        </form>
    </div>
</div>

{{-- ============ BULK EMAIL MODAL ============ --}}
<div id="bulkEmailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-[95%] max-w-2xl p-6 relative">
        <button id="closeBulkModal" class="absolute top-3 right-3 text-gray-500 hover:text-red-600 text-2xl">&times;</button>

        <h2 class="text-2xl font-bold mb-4">📢 Send Email to All Leads</h2>
        <form id="bulkEmailForm">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium">Subject</label>
                <input type="text" name="bulk_subject" class="w-full mt-1 p-3 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Message</label>
                <textarea name="bulk_message" rows="6" class="w-full border rounded-lg p-3" required></textarea>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-bold">
                🚀 Send to All
            </button>
        </form>
    </div>
</div>

{{-- ============ BULK LOADER OVERLAY ============ --}}
<div id="bulkLoader" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-[60]">
    <div class="bg-white rounded-2xl shadow-2xl p-6 w-80 text-center space-y-3">
        <div class="w-12 h-12 border-4 border-blue-500 border-dotted rounded-full animate-spin mx-auto"></div>
        <p id="bulkLoaderPercent" class="text-2xl font-bold">0%</p>
        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
            <div id="bulkProgressBar" class="h-2 bg-blue-600 rounded-full" style="width:0"></div>
        </div>
        <p id="bulkLoaderText" class="text-sm text-gray-600">0 / 0 leads</p>
        <p class="text-xs text-gray-500">Processing… please keep this tab open.</p>
    </div>
</div>

{{-- CKEditor 5 --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
let ckEditorInstance;
ClassicEditor.create(document.querySelector('#editor'), {
    toolbar: ['undo', 'redo', '|', 'bold', 'italic', 'underline', '|', 'bulletedList', 'numberedList', '|', 'link', 'blockQuote']
})
.then(editor => { ckEditorInstance = editor; })
.catch(error => { console.error(error); });

// Modal handling
function openEmailModal(id, email){
    document.getElementById('lead_id').value = id;
    document.getElementById('lead_email').value = email;
    document.getElementById('emailModal').classList.remove('hidden');
}
function closeEmailModal(){
    document.getElementById('emailModal').classList.add('hidden');
}

// Handle form
document.getElementById('emailForm').addEventListener('submit', async function(e){
    e.preventDefault();
    const formData = new FormData(this);
    formData.set('message', ckEditorInstance.getData());

    let res = await fetch(`/admin/leads/${formData.get('lead_id')}/send-email`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
            'Accept': 'application/json'
        },
        body: formData
    });
    let data = await res.json();

    if(res.ok){
        Swal.fire("✅ Success", data.message, "success");
        closeEmailModal();
    } else {
        Swal.fire("❌ Error", data.message || 'Something went wrong', "error");
    }
});

const bulkModal = document.getElementById('bulkEmailModal');
const openBulkModalButton = document.getElementById('openBulkModal');
const closeBulkModalButton = document.getElementById('closeBulkModal');
const bulkForm = document.getElementById('bulkEmailForm');
const bulkLoader = document.getElementById('bulkLoader');
const bulkPercent = document.getElementById('bulkLoaderPercent');
const bulkProgressBar = document.getElementById('bulkProgressBar');
const bulkLoaderText = document.getElementById('bulkLoaderText');
const csrfToken = document.querySelector('input[name=_token]').value;

openBulkModalButton.addEventListener('click', () => {
    bulkModal.classList.remove('hidden');
});

closeBulkModalButton.addEventListener('click', () => {
    bulkModal.classList.add('hidden');
});

bulkForm.addEventListener('submit', async function(e){
    e.preventDefault();
    const subject = this.querySelector('input[name=bulk_subject]').value.trim();
    const message = this.querySelector('textarea[name=bulk_message]').value.trim();

    if(!subject || !message){
        Swal.fire("🚫 Missing info", "Please provide both subject and message.", "warning");
        return;
    }

    try {
        bulkLoader.classList.remove('hidden');
        showBulkProgress(0, 0);

        const listRes = await fetch(`/admin/leads/list`, {
            headers: { 'Accept': 'application/json' }
        });
        const listData = await listRes.json();
        const leads = Array.isArray(listData.leads) ? listData.leads.filter(l => l.email) : [];
        const total = leads.length;

        if (!total) {
            Swal.fire("🚫 No Leads", "There are no leads with a valid email to send.", "info");
            bulkLoader.classList.add('hidden');
            return;
        }

        let processed = 0;
        const failures = [];

        for (const lead of leads) {
            try {
                await sendLeadEmail(lead.id, subject, message);
            } catch (error) {
                failures.push(lead);
            } finally {
                processed++;
                showBulkProgress(processed, total);
            }
        }

        if (failures.length) {
            Swal.fire("Partial Success", `${processed - failures.length} / ${total} emails were sent successfully. ${failures.length} failed.`, "warning");
        } else {
            Swal.fire("📬 Done", `Emails sent to ${total} lead(s).`, "success");
        }
    } catch (error) {
        Swal.fire("❌ Error", error.message || "Unable to send bulk email.", "error");
    } finally {
        bulkLoader.classList.add('hidden');
        bulkModal.classList.add('hidden');
        bulkForm.reset();
    }
});

function showBulkProgress(done, total) {
    const percent = total ? Math.round((done / total) * 100) : 0;
    bulkPercent.textContent = `${percent}%`;
    bulkProgressBar.style.width = `${percent}%`;
    bulkLoaderText.textContent = `${done} / ${total} leads`;
}

async function sendLeadEmail(id, subject, message) {
    const formData = new FormData();
    formData.append('_token', csrfToken);
    formData.append('subject', subject);
    formData.append('message', message);

    const res = await fetch(`/admin/leads/${id}/send-email`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json'
        },
        body: formData
    });

    if (!res.ok) {
        const err = await res.json().catch(() => ({}));
        throw new Error(err.message || 'Failed to send to lead '+id);
    }

    return res.json();
}
</script>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
