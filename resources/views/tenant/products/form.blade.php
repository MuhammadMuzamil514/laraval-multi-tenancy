@php($product = $product ?? null)

<div>
    <label class="block text-sm font-semibold" for="name">Name</label>
    <input class="mt-1 w-full rounded border-slate-300" id="name" name="name" required value="{{ old('name', $product?->name) }}">
    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label class="block text-sm font-semibold" for="description">Description</label>
    <textarea class="mt-1 w-full rounded border-slate-300" id="description" name="description" rows="3">{{ old('description', $product?->description) }}</textarea>
    @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
</div>
<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label class="block text-sm font-semibold" for="price">Price</label>
        <input class="mt-1 w-full rounded border-slate-300" id="price" min="0" name="price" required step="0.01" type="number" value="{{ old('price', $product?->price) }}">
        @error('price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-semibold" for="stock">Stock</label>
        <input class="mt-1 w-full rounded border-slate-300" id="stock" min="0" name="stock" required type="number" value="{{ old('stock', $product?->stock) }}">
        @error('stock')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
</div>
