@extends('admin.layouts.app')

@section('title', 'Student Leads')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h1 class="text-3xl font-bold mb-6">📋 Student Leads</h1>

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
</script>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
