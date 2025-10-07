@extends('admin.layouts.app')

@section('title', 'Panel Background Settings')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Panel Background Settings</h1>
        <p class="text-gray-500 mt-2 text-sm md:text-base">Choose a custom background image for the entire admin panel. The selection is stored locally in your browser so you can experiment freely without affecting other users.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white shadow-md rounded-2xl p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Background Preview</h2>
            <div id="preview"
                 class="aspect-video rounded-xl border border-gray-200 flex items-center justify-center text-gray-400 text-sm"
                 style="background: url('https://img.freepik.com/free-vector/education-pattern-background-doodle-style_53876-115365.jpg') center/cover no-repeat;">
                <span id="preview-placeholder">Your background preview will appear here</span>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-2xl p-6 border border-gray-100 space-y-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2" for="image-url">Image URL</label>
                <input id="image-url" type="url" placeholder="https://example.com/background.jpg"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2" for="image-file">Upload image</label>
                <input id="image-file" type="file" accept="image/*"
                       class="w-full border border-dashed border-gray-300 rounded-lg px-3 py-2 cursor-pointer bg-gray-50">
                <p class="text-xs text-gray-400 mt-2">Images are stored in your browser only and never uploaded to the server.</p>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="background-size">Background size</label>
                    <select id="background-size" class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="cover">Cover (recommended)</option>
                        <option value="contain">Contain</option>
                        <option value="auto">Auto</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="background-repeat">Background repeat</label>
                    <select id="background-repeat" class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="no-repeat">No repeat</option>
                        <option value="repeat">Repeat</option>
                        <option value="repeat-x">Repeat X</option>
                        <option value="repeat-y">Repeat Y</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="background-position">Background position</label>
                    <select id="background-position" class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="center center">Center</option>
                        <option value="top center">Top</option>
                        <option value="bottom center">Bottom</option>
                        <option value="center left">Left</option>
                        <option value="center right">Right</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="background-color">Background color (optional)</label>
                    <input id="background-color" type="color" value="#ffffff"
                           class="w-full h-10 border border-gray-200 rounded-lg cursor-pointer">
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <button id="apply-btn"
                        class="flex-1 bg-indigo-600 text-white font-semibold px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                    Apply background
                </button>
                <button id="clear-btn"
                        class="flex-1 bg-gray-100 text-gray-700 font-semibold px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                    Reset to default
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const storageKey = 'adminPanelBg';
    const preview = document.getElementById('preview');
    const previewPlaceholder = document.getElementById('preview-placeholder');
    const imageUrlInput = document.getElementById('image-url');
    const fileInput = document.getElementById('image-file');
    const sizeSelect = document.getElementById('background-size');
    const repeatSelect = document.getElementById('background-repeat');
    const positionSelect = document.getElementById('background-position');
    const colorInput = document.getElementById('background-color');
    const applyBtn = document.getElementById('apply-btn');
    const clearBtn = document.getElementById('clear-btn');

    function setPreview(image, { size, repeat, position, color }) {
        preview.style.backgroundImage = image ? `url('${image}')` : 'none';
        preview.style.backgroundSize = size;
        preview.style.backgroundRepeat = repeat;
        preview.style.backgroundPosition = position;
        preview.style.backgroundColor = color || '#f3f4f6';
        previewPlaceholder.style.display = image ? 'none' : 'block';
    }

    function loadSettings() {
        try {
            const stored = localStorage.getItem(storageKey);
            if (!stored) {
                return null;
            }
            const parsed = JSON.parse(stored);
            return parsed && typeof parsed === 'object' ? parsed : null;
        } catch (error) {
            console.warn('Unable to read background settings', error);
            return null;
        }
    }

    function applyToDocument(settings) {
        if (!settings || !settings.image) {
            return;
        }
        const body = document.body;
        body.style.backgroundImage = `url('${settings.image}')`;
        body.style.backgroundSize = settings.size || 'cover';
        body.style.backgroundRepeat = settings.repeat || 'no-repeat';
        body.style.backgroundPosition = settings.position || 'center center';
        if (settings.color) {
            body.style.backgroundColor = settings.color;
        }
    }

    function storeSettings(settings) {
        localStorage.setItem(storageKey, JSON.stringify(settings));
    }

    const existing = loadSettings();
    if (existing) {
        imageUrlInput.value = existing.image && !existing.image.startsWith('data:') ? existing.image : '';
        sizeSelect.value = existing.size || 'cover';
        repeatSelect.value = existing.repeat || 'no-repeat';
        positionSelect.value = existing.position || 'center center';
        colorInput.value = existing.color || '#ffffff';
        setPreview(existing.image, existing);
    }

    let uploadedDataUrl = existing && existing.image && existing.image.startsWith('data:') ? existing.image : '';

    fileInput.addEventListener('change', (event) => {
        const file = event.target.files && event.target.files[0];
        if (!file) {
            return;
        }
        if (!file.type.startsWith('image/')) {
            alert('Please choose an image file.');
            fileInput.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = (loadEvent) => {
            uploadedDataUrl = loadEvent.target.result;
            setPreview(uploadedDataUrl, {
                size: sizeSelect.value,
                repeat: repeatSelect.value,
                position: positionSelect.value,
                color: colorInput.value
            });
            previewPlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });

    imageUrlInput.addEventListener('input', () => {
        uploadedDataUrl = '';
        const url = imageUrlInput.value.trim();
        setPreview(url, {
            size: sizeSelect.value,
            repeat: repeatSelect.value,
            position: positionSelect.value,
            color: colorInput.value
        });
    });

    function collectSettings() {
        const url = imageUrlInput.value.trim();
        const image = uploadedDataUrl || url;
        if (!image) {
            return null;
        }
        return {
            image,
            size: sizeSelect.value,
            repeat: repeatSelect.value,
            position: positionSelect.value,
            color: colorInput.value
        };
    }

    applyBtn.addEventListener('click', () => {
        const settings = collectSettings();
        if (!settings) {
            alert('Please choose an image first (via URL or upload).');
            return;
        }
        storeSettings(settings);
        applyToDocument(settings);
        alert('Background updated for this device.');
    });

    clearBtn.addEventListener('click', () => {
        localStorage.removeItem(storageKey);
        uploadedDataUrl = '';
        imageUrlInput.value = '';
        sizeSelect.value = 'cover';
        repeatSelect.value = 'no-repeat';
        positionSelect.value = 'center center';
        colorInput.value = '#ffffff';
        setPreview('', {
            size: 'cover',
            repeat: 'no-repeat',
            position: 'center center',
            color: '#ffffff'
        });
        document.body.style.backgroundImage = "";
        document.body.style.backgroundColor = "";
        alert('Background reset to default for this device.');
    });

    if (existing) {
        applyToDocument(existing);
    }
</script>
@endpush
@endsection
