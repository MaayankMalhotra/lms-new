@auth
    @if(auth()->user()->role === 1)
        <div id="demoVideoModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4">
            <div class="relative w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
                <button onclick="closeDemoVideoModal()" class="absolute right-4 top-4 text-2xl font-bold text-gray-500 hover:text-red-600">&times;</button>
                <h3 class="text-xl font-bold mb-3">Attach YouTube Video</h3>
                <form id="demoVideoForm" class="space-y-4">
                    <input type="hidden" name="detail_type" id="demo_detail_type">
                    <input type="hidden" name="detail_id" id="demo_detail_id">
                    <input type="hidden" name="module_index" id="demo_module_index">

                    <div>
                        <label class="text-sm font-semibold text-gray-600">YouTube Video URL</label>
                        <input type="url" name="video_url" id="demo_video_url" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none" placeholder="https://www.youtube.com/watch?v=..." required>
                    </div>
                    <div class="text-right space-x-3">
                        <button type="button" onclick="closeDemoVideoModal()" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-600 hover:border-gray-400">Cancel</button>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            const demoVideoModal = document.getElementById('demoVideoModal');
            const demoVideoForm = document.getElementById('demoVideoForm');
            const demoVideoUrlInput = document.getElementById('demo_video_url');
            const detailTypeInput = document.getElementById('demo_detail_type');
            const detailIdInput = document.getElementById('demo_detail_id');
            const moduleIndexInput = document.getElementById('demo_module_index');
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfTokenValue = csrfMeta ? csrfMeta.getAttribute('content') : '';

            function openDemoVideoModal(detailType, detailId, moduleIndex, currentUrl = '') {
                detailTypeInput.value = detailType;
                detailIdInput.value = detailId;
                moduleIndexInput.value = moduleIndex;
                demoVideoUrlInput.value = currentUrl ?? '';
                demoVideoModal.classList.remove('hidden');
            }

            function closeDemoVideoModal() {
                demoVideoModal.classList.add('hidden');
            }

            demoVideoForm.addEventListener('submit', async function (event) {
                event.preventDefault();

                const detailType = detailTypeInput.value;
                const detailId = detailIdInput.value;
                const moduleIndex = moduleIndexInput.value;
                const videoUrl = demoVideoUrlInput.value.trim();

                if (!detailType || !detailId || moduleIndex === '' || !videoUrl) {
                    alert('All fields are required.');
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
                            video_url: videoUrl
                        })
                    });

                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Unable to save video');
                    }

                    alert(data.message || 'Video saved');
                    location.reload();
                } catch (error) {
                    alert(error.message || 'Something went wrong');
                }
            });
        </script>
    @endif
@endauth
