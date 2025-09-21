@extends('admin.layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .card-grid { display:grid; grid-template-columns: repeat(auto-fill, minmax(280px,1fr)); gap:1rem; }
    .card { background:#fff; border-radius:14px; box-shadow:0 6px 16px rgba(0,0,0,.1); overflow:hidden; }
    .logo { width:100%; height:150px; object-fit:contain; background:#f9fafb; }
    .body { padding:1rem; }
    .title { font-weight:700; font-size:1.1rem; color:#111827; }
    .meta { margin-top:.5rem; color:#6b7280; font-size:.9rem; }
    .badge { display:inline-block; background:#eef2ff; color:#4f46e5; padding:.15rem .5rem; border-radius:999px; font-size:.75rem; margin-top:.4rem; }
    .price { margin-top:.6rem; color:#2563eb; font-weight:700; }
    .actions { display:flex; gap:.75rem; margin-top:.8rem; }
    .icon-btn { border:none; background:none; cursor:pointer; font-size:1.05rem; }
    .icon-edit{ color:#3b82f6 } .icon-edit:hover{ color:#1d4ed8 }
    .icon-del { color:#ef4444 } .icon-del:hover{ color:#b91c1c }
    /* Modal */
    .modal-backdrop{ position:fixed; inset:0; background:rgba(0,0,0,.4); display:none; align-items:center; justify-content:center; z-index:60; }
    .modal{ width:100%; max-width:700px; background:#fff; border-radius:16px; overflow:hidden; transform:translateY(8px); }
    .modal.show{ display:flex; }
</style>

<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-briefcase mr-2 text-blue-500"></i>Internships</h1>
                <p class="text-gray-500 text-sm">Manage internship programs</p>
            </div>
            <a href="{{ route('admin.internship.add') }}" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                <i class="fas fa-plus-circle mr-2"></i>Add Internship
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
        @endif

        <div class="card-grid">
            @forelse($internships as $internship)
                <div class="card" id="card-{{ $internship->id }}" data-id="{{ $internship->id }}">
                    @if($internship->logo)
                        <img src="{{ asset($internship->logo) }}" alt="{{ $internship->name }}" class="logo" id="card-logo-{{ $internship->id }}">
                    @else
                        <div class="logo flex items-center justify-center text-gray-300"><i class="fas fa-image text-2xl"></i></div>
                    @endif

                    <div class="body">
                        <div class="title" id="card-name-{{ $internship->id }}">{{ $internship->name }}</div>

                        <div class="meta">
                            <div><i class="fas fa-clock mr-1"></i> <span id="card-duration-{{ $internship->id }}">{{ $internship->duration }}</span></div>
                            <div><i class="fas fa-project-diagram mr-1"></i> Projects: <span id="card-project-{{ $internship->id }}">{{ $internship->project }}</span></div>
                            <div><i class="fas fa-users mr-1"></i> <span id="card-applicant-{{ $internship->id }}">{{ $internship->applicant }}</span></div>
                            <div class="badge" id="card-certified-{{ $internship->id }}">{{ $internship->certified_button }}</div>
                        </div>

                        <div class="price">₹<span id="card-price-{{ $internship->id }}">{{ number_format($internship->price, 2) }}</span></div>

                        <div class="actions">
                            <button class="icon-btn icon-edit"
                                onclick="openEditModal('{{ route('admin.internship.edit', $internship->id) }}', '{{ route('admin.internship.update', $internship->id) }}')"
                                title="Edit"><i class="fas fa-edit"></i></button>

                            @if($internship->has_details)
                                <a class="icon-btn text-green-600 hover:text-green-700" title="Has Details">
                                    <i class="fas fa-book-open"></i>
                                </a>
                            @endif

                            <form method="POST" action="{{ route('admin.internship.destroy', $internship->id) }}"
                                  onsubmit="return confirm('Delete this internship?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="icon-btn icon-del" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-600">No internships found.</div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($internships->hasPages())
            <div class="mt-6">{{ $internships->links() }}</div>
        @endif
    </div>
</div>

<!-- EDIT MODAL -->
<div id="editModal" class="modal-backdrop">
    <div class="modal">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h3 class="text-lg font-semibold"><i class="fas fa-edit mr-2 text-blue-600"></i>Edit Internship</h3>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
        </div>

        <form id="editForm" class="p-6 space-y-4" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <!-- dynamic action set by JS -->

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="f-name" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Duration</label>
                    <input type="text" name="duration" id="f-duration" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Projects</label>
                    <input type="text" name="project" id="f-project" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Applicant Stat</label>
                    <input type="text" name="applicant" id="f-applicant" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Certification Badge</label>
                    <input type="text" name="certified_button" id="f-certified" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Price (₹)</label>
                    <input type="number" name="price" id="f-price" step="0.01" min="0" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Logo (optional)</label>
                    <input type="file" name="logo" id="f-logo" accept="image/*" class="w-full border rounded px-3 py-2">
                    <div class="mt-2">
                        <img id="f-logo-preview" src="" alt="Logo preview" class="h-20 object-contain hidden">
                    </div>
                </div>
            </div>

            <div id="editErrors" class="text-sm text-red-600"></div>

            <div class="pt-3 flex items-center justify-end gap-2 border-t">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded border">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700" id="saveBtn">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let CURRENT_UPDATE_URL = null;
let CURRENT_EDIT_ID = null;

async function openEditModal(editUrl, updateUrl){
    CURRENT_UPDATE_URL = updateUrl;
    const backdrop = document.getElementById('editModal');
    const form = document.getElementById('editForm');
    const errs = document.getElementById('editErrors');
    errs.textContent = '';

    // Clear file input + preview
    document.getElementById('f-logo').value = '';
    const prev = document.getElementById('f-logo-preview');
    prev.classList.add('hidden'); prev.src = '';

    try{
        const res = await fetch(editUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await res.json();
        if(!res.ok){ throw new Error(data.message || 'Failed to load internship'); }

        CURRENT_EDIT_ID = data.id;
        // fill fields
        document.getElementById('f-name').value      = data.name ?? '';
        document.getElementById('f-duration').value  = data.duration ?? '';
        document.getElementById('f-project').value   = data.project ?? '';
        document.getElementById('f-applicant').value = data.applicant ?? '';
        document.getElementById('f-certified').value = data.certified_button ?? '';
        document.getElementById('f-price').value     = data.price ?? '';

        if (data.logo_url){
            prev.src = data.logo_url; prev.classList.remove('hidden');
        }

        form.setAttribute('action', updateUrl);
        backdrop.classList.add('show');
    }catch(e){
        alert(e.message || 'Error loading data');
    }
}

function closeEditModal(){
    document.getElementById('editModal').classList.remove('show');
    CURRENT_UPDATE_URL = null;
    CURRENT_EDIT_ID = null;
}

document.getElementById('f-logo').addEventListener('change', function(e){
    const file = e.target.files?.[0];
    const prev = document.getElementById('f-logo-preview');
    if(file){
        const reader = new FileReader();
        reader.onload = (evt)=>{ prev.src = evt.target.result; prev.classList.remove('hidden'); }
        reader.readAsDataURL(file);
    } else {
        prev.classList.add('hidden'); prev.src = '';
    }
});

document.getElementById('editForm').addEventListener('submit', async function(e){
    e.preventDefault();
    const saveBtn = document.getElementById('saveBtn');
    saveBtn.disabled = true; saveBtn.textContent = 'Saving...';

    const errs = document.getElementById('editErrors');
    errs.textContent = '';

    try{
        const fd = new FormData(this);
        // method spoofing for Laravel
        fd.set('_method', 'PUT');

        const res = await fetch(CURRENT_UPDATE_URL, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
            body: fd
        });

        const data = await res.json().catch(()=> ({}));

        if(!res.ok || !data.success){
            // show validation errors if present
            if(data?.errors){
                errs.innerHTML = Object.values(data.errors).flat().map(e => `<div>• ${e}</div>`).join('');
            }else{
                errs.textContent = data?.message || 'Update failed';
            }
            saveBtn.disabled = false; saveBtn.textContent = 'Save Changes';
            return;
        }

        // Update card UI
        const it = data.internship;
        if (it){
            document.getElementById(`card-name-${it.id}`).textContent      = it.name ?? '';
            document.getElementById(`card-duration-${it.id}`).textContent  = it.duration ?? '';
            document.getElementById(`card-project-${it.id}`).textContent   = it.project ?? '';
            document.getElementById(`card-applicant-${it.id}`).textContent = it.applicant ?? '';
            document.getElementById(`card-certified-${it.id}`).textContent = it.certified_button ?? '';
            document.getElementById(`card-price-${it.id}`).textContent     = Number(it.price ?? 0).toFixed(2);

            if (it.logo_url){
                const img = document.getElementById(`card-logo-${it.id}`);
                if (img) { img.src = it.logo_url; }
            }
        }

        closeEditModal();
    }catch(err){
        errs.textContent = 'Unexpected error. Check console.';
        console.error(err);
    }finally{
        saveBtn.disabled = false; saveBtn.textContent = 'Save Changes';
    }
});
</script>
@endsection
