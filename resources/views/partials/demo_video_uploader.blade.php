@auth
    @if(auth()->user()->role === 1)
        <div id="demoVideoModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4">
            <div class="relative w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl">
                <button type="button" onclick="closeDemoVideoModal()" class="absolute right-4 top-4 text-2xl font-bold text-gray-500 hover:text-red-600">&times;</button>
                <h3 class="text-xl font-bold mb-3">Attach Demo Videos</h3>
                <form id="demoVideoForm" class="space-y-4" novalidate>
                    <input type="hidden" name="detail_type" id="demo_detail_type">
                    <input type="hidden" name="detail_id" id="demo_detail_id">
                    <input type="hidden" name="module_index" id="demo_module_index">
                    <div>
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-semibold text-gray-600">Demo Video Links</label>
                            <button type="button" id="demo_add_video_input" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                                + Add link
                            </button>
                        </div>
                        <div id="demo_video_inputs" class="mt-2 space-y-2"></div>
                        <p class="text-xs text-gray-500 mt-2">Paste full secure URLs (https://). The first link becomes the primary demo video.</p>
                        <div id="demo_video_errors" class="text-xs text-red-600 mt-2"></div>
                    </div>
                    <label class="flex items-start gap-2 text-sm text-gray-600">
                        <input type="checkbox" id="demo_replace_checkbox" class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span>Replace existing links instead of appending new ones.</span>
                    </label>
                    <div class="text-right space-x-3">
                        <button type="button" onclick="closeDemoVideoModal()" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-600 hover:border-gray-400">Cancel</button>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <template id="demo_video_input_template">
            <div class="flex items-center gap-2">
                <input type="text" name="video_urls[]" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none" placeholder="https://www.youtube.com/watch?v=..." inputmode="url" autocomplete="off">
                <button type="button" class="rounded-lg border border-gray-200 px-2 py-2 text-gray-500 hover:text-red-600" data-remove-video>
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </template>

        <script>
            const demoVideoModal = document.getElementById('demoVideoModal');
            const demoVideoForm = document.getElementById('demoVideoForm');
            const videoInputsContainer = document.getElementById('demo_video_inputs');
            const addVideoButton = document.getElementById('demo_add_video_input');
            const videoInputTemplate = document.getElementById('demo_video_input_template');
            const detailTypeInput = document.getElementById('demo_detail_type');
            const detailIdInput = document.getElementById('demo_detail_id');
            const moduleIndexInput = document.getElementById('demo_module_index');
            const replaceCheckbox = document.getElementById('demo_replace_checkbox');
            const errorContainer = document.getElementById('demo_video_errors');
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfTokenValue = csrfMeta ? csrfMeta.getAttribute('content') : '';

            function openDemoVideoModal(detailType, detailId, moduleIndex, currentVideos = []) {
                detailTypeInput.value = detailType;
                detailIdInput.value = detailId;
                moduleIndexInput.value = moduleIndex;
                replaceCheckbox.checked = false;
                setDemoVideoErrors([]);
                const normalizedVideos = Array.isArray(currentVideos)
                    ? currentVideos
                    : (currentVideos ? [currentVideos] : []);
                renderDemoVideoInputs(normalizedVideos);
                demoVideoModal.classList.remove('hidden');
            }

            function closeDemoVideoModal() {
                setDemoVideoErrors([]);
                demoVideoModal.classList.add('hidden');
            }

            function setDemoVideoErrors(messages) {
                if (!errorContainer) return;
                errorContainer.innerHTML = '';
                if (!Array.isArray(messages)) return;
                messages.filter(Boolean).forEach((msg) => {
                    const item = document.createElement('div');
                    item.textContent = msg;
                    errorContainer.appendChild(item);
                });
            }

            function isValidHttpUrl(value) {
                try {
                    const url = new URL(value);
                    return url.protocol === 'http:' || url.protocol === 'https:';
                } catch (error) {
                    return false;
                }
            }

            function renderDemoVideoInputs(videoList) {
                if (!videoInputsContainer || !videoInputTemplate) return;
                videoInputsContainer.innerHTML = '';
                const list = videoList && videoList.length ? videoList : [''];
                list.forEach((url) => addDemoVideoInput(url));
            }

            function addDemoVideoInput(value = '') {
                if (!videoInputTemplate || !videoInputsContainer) return;
                const fragment = videoInputTemplate.content.firstElementChild.cloneNode(true);
                const input = fragment.querySelector('input[name="video_urls[]"]');
                const removeBtn = fragment.querySelector('[data-remove-video]');
                if (input) {
                    input.value = value || '';
                }
                if (removeBtn) {
                    removeBtn.addEventListener('click', () => {
                        fragment.remove();
                        if (!videoInputsContainer.children.length) {
                            addDemoVideoInput('');
                        }
                    });
                }
                videoInputsContainer.appendChild(fragment);
                if (input) {
                    input.focus();
                }
            }

            if (addVideoButton) {
                addVideoButton.addEventListener('click', () => {
                    setDemoVideoErrors([]);
                    addDemoVideoInput('');
                });
            }

            demoVideoForm.addEventListener('submit', async function (event) {
                event.preventDefault();
                setDemoVideoErrors([]);

                const detailType = detailTypeInput.value;
                const detailId = detailIdInput.value;
                const moduleIndex = moduleIndexInput.value;
                const videoUrls = Array.from(videoInputsContainer.querySelectorAll('input[name="video_urls[]"]'))
                    .map(input => input.value.trim())
                    .filter(value => value !== '');

                if (!detailType || !detailId || moduleIndex === '' || !videoUrls.length) {
                    setDemoVideoErrors(['Please enter at least one valid video link.']);
                    return;
                }

                const invalidUrls = videoUrls.filter((url) => !isValidHttpUrl(url));
                if (invalidUrls.length) {
                    setDemoVideoErrors([
                        'Some links are not valid URLs. Please use full links like https://www.youtube.com/watch?v=...',
                        ...invalidUrls,
                    ]);
                    return;
                }

                const endpoint = detailType === 'internship'
                    ? `/internship-details/${detailId}/demo-video`
                    : `/course-details/${detailId}/demo-video`;

                try {
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfTokenValue
                        },
                        body: JSON.stringify({
                            module_index: parseInt(moduleIndex, 10),
                            video_urls: videoUrls,
                            replace_existing: replaceCheckbox.checked
                        })
                    });

                    const data = await response.json();
                    if (!response.ok) {
                        const messages = [];
                        if (data.message) {
                            messages.push(data.message);
                        }
                        if (data.errors) {
                            Object.values(data.errors).forEach((errorList) => {
                                if (Array.isArray(errorList)) {
                                    messages.push(...errorList);
                                }
                            });
                        }
                        throw new Error(messages.length ? messages.join(' ') : 'Unable to save video');
                    }

                    alert(data.message || 'Video saved');
                    location.reload();
                } catch (error) {
                    setDemoVideoErrors([error.message || 'Something went wrong']);
                }
            });
        </script>
    @endif
@endauth
