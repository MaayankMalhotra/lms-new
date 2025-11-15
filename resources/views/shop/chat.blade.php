@extends('shop.layouts.app')

@section('title', 'Aromea AI Shop Assistant')

@section('content')
    <div class="mx-auto max-w-5xl px-4">
        <div class="rounded-3xl bg-white shadow-lg ring-1 ring-black/5">
            <div class="border-b border-gray-200 px-6 py-4">
                <p class="text-sm font-semibold text-[#007185]">Aromea AI · Conversational Commerce</p>
                <p class="text-xs text-gray-500">Ask for perfumes, sneakers, ritual kits, gifts, or say "view cart".</p>
            </div>
            <div id="chat-log" class="space-y-6 overflow-y-auto px-6 py-8" style="max-height: 70vh;">
                <div class="flex gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#232f3e] text-sm font-semibold text-white">AI</div>
                    <div class="rounded-2xl bg-gray-100 px-4 py-3 text-sm text-slate-900">
                        Hey there! I'm your personal shopper. Tell me what you're looking for—perfumes, sneakers, ritual kits, gifts—and I'll drop the best options right here.
                    </div>
                </div>
            </div>
            <form id="chat-form" class="border-t border-gray-200 px-6 py-4 flex gap-3">
                <input type="text" id="chat-input" class="flex-1 rounded-full border border-gray-300 px-4 py-2 text-sm focus:border-[#007185] focus:outline-none" placeholder="Type your request..." autocomplete="off">
                <button type="submit" class="rounded-full bg-[#ffd814] px-5 py-2 text-sm font-semibold text-[#111]">Send</button>
            </form>
        </div>
    </div>

    <template id="product-card-template">
        <div class="rounded-2xl border border-gray-200 p-4 text-sm shadow-sm">
            <img class="h-32 w-full rounded-lg object-cover">
            <p class="mt-3 text-xs uppercase tracking-wide text-gray-500 category"></p>
            <p class="text-base font-semibold text-slate-900 name"></p>
            <p class="text-xs text-gray-500 description"></p>
            <p class="mt-2 text-lg font-semibold text-slate-900 price"></p>
            <div class="mt-3 flex gap-2">
                <form method="POST" action="{{ route('cart.store') }}" data-add-to-cart class="flex-1">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="product_id">
                    <button type="submit" class="w-full rounded-full border border-[#111] px-3 py-1 text-xs font-semibold text-[#111] hover:bg-[#111] hover:text-white">Add to cart</button>
                </form>
                <a class="flex-1 rounded-full border border-transparent bg-[#ffd814] px-3 py-1 text-center text-xs font-semibold text-[#111] hover:bg-[#f7ca00] view-link" target="_blank">View</a>
            </div>
        </div>
    </template>

    <script>
        const chatLog = document.getElementById('chat-log');
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');
        const template = document.getElementById('product-card-template');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const appendMessage = (role, text, products = []) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'flex gap-3 ' + (role === 'user' ? 'justify-end' : '');

            const bubble = document.createElement('div');
            bubble.className = role === 'user'
                ? 'rounded-2xl bg-[#007185] px-4 py-3 text-sm text-white max-w-[75%]'
                : 'rounded-2xl bg-gray-100 px-4 py-3 text-sm text-slate-900 max-w-[75%]';

            bubble.innerHTML = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            wrapper.appendChild(bubble);

            if (role === 'bot' && products.length) {
                const grid = document.createElement('div');
                grid.className = 'mt-4 grid gap-4 sm:grid-cols-2';

                products.forEach(product => {
                    const node = template.content.cloneNode(true);
                    node.querySelector('img').src = product.image;
                    node.querySelector('.category').textContent = product.category || 'Collection';
                    node.querySelector('.name').textContent = product.name;
                    node.querySelector('.description').textContent = product.description || '';
                    node.querySelector('.price').textContent = `₹${product.price}`;
                    node.querySelector('input[name="product_id"]').value = product.id;
                    node.querySelector('.view-link').href = `{{ url('/shop/product') }}/${product.slug}`;
                    grid.appendChild(node);
                });

                bubble.appendChild(grid);
            }

            chatLog.appendChild(wrapper);
            chatLog.scrollTop = chatLog.scrollHeight;
        };

        chatForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const message = chatInput.value.trim();
            if (!message) return;

            appendMessage('user', message);
            chatInput.value = '';

            try {
                const response = await fetch('{{ route('shop.chat') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message })
                });

                if (!response.ok) throw new Error('Assistant is busy. Try again.');

                const payload = await response.json();
                appendMessage('bot', payload.message, payload.products || []);
            } catch (error) {
                appendMessage('bot', 'Sorry, I had trouble responding. Please try again.');
            }
        });
    </script>
@endsection
