@csrf
<div class="grid gap-6 md:grid-cols-2">
    <label class="text-sm font-semibold text-slate-700">
        Name
        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="mt-2 w-full rounded-md border border-gray-300 px-3 py-2" required>
    </label>
    <label class="text-sm font-semibold text-slate-700">
        Slug
        <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="mt-2 w-full rounded-md border border-gray-300 px-3 py-2" required>
    </label>
    <label class="text-sm font-semibold text-slate-700">
        SKU
        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="mt-2 w-full rounded-md border border-gray-300 px-3 py-2">
    </label>
    <label class="text-sm font-semibold text-slate-700">
        Price (₹)
        <input type="number" name="price" value="{{ old('price', $product->price) }}" class="mt-2 w-full rounded-md border border-gray-300 px-3 py-2" required step="0.01">
    </label>
    <label class="text-sm font-semibold text-slate-700">
        Compare at price (₹)
        <input type="number" name="compare_at_price" value="{{ old('compare_at_price', $product->compare_at_price) }}" class="mt-2 w-full rounded-md border border-gray-300 px-3 py-2" step="0.01">
    </label>
    <label class="text-sm font-semibold text-slate-700">
        Inventory
        <input type="number" name="inventory" value="{{ old('inventory', $product->inventory) }}" class="mt-2 w-full rounded-md border border-gray-300 px-3 py-2" required>
    </label>
</div>

<label class="mt-6 block text-sm font-semibold text-slate-700">
    Short description
    <textarea name="short_description" rows="2" class="mt-2 w-full rounded-md border border-gray-300 px-3 py-2">{{ old('short_description', $product->short_description) }}</textarea>
</label>

<label class="mt-6 block text-sm font-semibold text-slate-700">
    Full description (HTML allowed)
    <textarea name="description" rows="4" class="mt-2 w-full rounded-md border border-gray-300 px-3 py-2">{{ old('description', $product->description) }}</textarea>
</label>

<div class="mt-6 grid gap-6 md:grid-cols-2">
    <label class="text-sm font-semibold text-slate-700">
        Hero image path/URL
        <input type="text" name="hero_image" value="{{ old('hero_image', $product->hero_image) }}" class="mt-2 w-full rounded-md border border-gray-300 px-3 py-2" placeholder="https://... or storage path">
        <span class="text-xs text-gray-500">Optional if you upload a file below.</span>
    </label>
    <label class="text-sm font-semibold text-slate-700">
        Upload hero image
        <input type="file" name="hero_image_file" class="mt-2 block w-full text-sm text-gray-600" accept="image/*">
        <span class="text-xs text-gray-500">Max 2MB. Stored on public disk.</span>
    </label>
    @if($product->hero_image)
        <div class="md:col-span-2">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-gray-500">Current image</p>
            <img src="{{ asset($product->hero_image) }}" alt="Preview" class="mt-2 h-40 w-40 rounded-md object-cover">
        </div>
    @endif
    <label class="text-sm font-semibold text-slate-700">
        Status
        <select name="status" class="mt-2 w-full rounded-md border border-gray-300 px-3 py-2">
            @foreach(['draft','published','archived'] as $status)
                <option value="{{ $status }}" @selected(old('status', $product->status ?? 'draft') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </label>
</div>

<div class="mt-6 grid gap-6 md:grid-cols-2">
    <label class="text-sm font-semibold text-slate-700">
        Meta title
        <input type="text" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" class="mt-2 w-full rounded-md border border-gray-300 px-3 py-2">
    </label>
    <label class="text-sm font-semibold text-slate-700">
        Meta description
        <textarea name="meta_description" rows="2" class="mt-2 w-full rounded-md border border-gray-300 px-3 py-2">{{ old('meta_description', $product->meta_description) }}</textarea>
    </label>
</div>

<label class="mt-6 block text-sm font-semibold text-slate-700">
    Specifications (JSON)
    <textarea name="specifications" rows="4" class="mt-2 w-full rounded-md border border-gray-300 px-3 py-2">{{ old('specifications', $product->specifications ? json_encode($product->specifications, JSON_PRETTY_PRINT) : '') }}</textarea>
    <span class="text-xs text-gray-500">Example: {"Notes":["vanilla","cacao"],"Volume":"50ml"}</span>
</label>

<label class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))>
    Featured on storefront
</label>

<label class="mt-6 block text-sm font-semibold text-slate-700">
    Categories
    <select name="categories[]" multiple class="mt-2 w-full rounded-md border border-gray-300 px-3 py-2">
        @foreach($categories as $category)
            <option value="{{ $category->id }}"
                @selected(in_array($category->id, old('categories', $product->categories->pluck('id')->toArray())))>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</label>
