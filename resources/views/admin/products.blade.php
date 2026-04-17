@php
    $groupedProducts = $categories->map(function ($category) use ($products) {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'products' => $products->where('category_id', $category->id)->values(),
        ];
    })->filter(fn ($group) => $group['products']->isNotEmpty())->values();
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.35em] text-amber-600">Admin Products</p>
                <h2 class="font-display text-2xl font-semibold text-slate-900">Menu management</h2>
            </div>
            <button
                type="button"
                @click="$dispatch('open-product-modal', { mode: 'create' })"
                class="primary-action"
            >
                + Add Menu
            </button>
        </div>
    </x-slot>

    <div
        class="px-5 py-5 sm:px-7 sm:py-7 lg:px-8"
        x-data="adminProductsPage({
            groupedProducts: {{ Js::from($groupedProducts) }},
            categories: {{ Js::from($categories->map(fn ($category) => ['id' => $category->id, 'name' => $category->name])->values()) }},
            editingProduct: {{ Js::from($editingProduct) }},
            initialForm: {{ Js::from($productFormData) }},
            hasErrors: {{ $errors->any() ? 'true' : 'false' }},
            oldFormMode: @js(old('form_mode', $editingProduct ? 'edit' : 'create')),
            oldProductId: @js(old('product_id', $editingProduct?->id)),
        })"
        x-init="initialize()"
        @open-product-modal.window="openModal($event.detail.mode, $event.detail.product ?? null)"
    >
        @if (session('status'))
            <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
        @endif

        <div class="grid gap-4 md:grid-cols-2">
            <article class="metric-card">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Menus</p>
                <h3 class="mt-3 font-display text-3xl font-semibold text-slate-900">{{ $products->count() }}</h3>
            </article>
            <article class="metric-card">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Categories</p>
                <h3 class="mt-3 font-display text-3xl font-semibold text-slate-900">{{ $categories->count() }}</h3>
            </article>
        </div>

        <div class="panel-card mt-6 rounded-[30px]">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="section-kicker">Categories</p>
                    <h3 class="mt-2 font-display text-2xl font-semibold text-slate-900">Browse menu by section</h3>
                </div>
                <div class="flex flex-wrap gap-2">
                    <template x-for="group in groupedProducts" :key="group.id">
                        <button
                            type="button"
                            class="pill-tab"
                            :class="activeCategoryId === group.id ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600'"
                            @click="activeCategoryId = group.id"
                            x-text="group.name"
                        ></button>
                    </template>
                </div>
            </div>

            <template x-for="group in groupedProducts" :key="group.id">
                <section x-show="activeCategoryId === group.id" x-cloak class="mt-6">
                    <div class="mb-4">
                        <h4 class="font-display text-xl font-semibold text-slate-900" x-text="group.name"></h4>
                        <p class="text-sm text-slate-500" x-text="`${group.products.length} menu items`"></p>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-2 2xl:grid-cols-3">
                        <template x-for="product in group.products" :key="product.id">
                            <article class="rounded-[24px] border border-slate-200 bg-slate-50 p-4 shadow-sm">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h5 class="font-semibold text-slate-900" x-text="product.name"></h5>
                                            <span
                                                class="rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em]"
                                                :class="product.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600'"
                                                x-text="product.is_active ? 'Active' : 'Inactive'"
                                            ></span>
                                        </div>
                                        <p class="mt-2 line-clamp-2 text-sm text-slate-500" x-text="product.description || 'No description yet.'"></p>
                                    </div>
                                    <p class="shrink-0 text-base font-semibold text-slate-900" x-text="formatCurrency(product.base_price)"></p>
                                </div>
                                <div class="mt-4 flex items-center justify-between gap-3">
                                    <span class="text-xs uppercase tracking-[0.2em] text-slate-400" x-text="product.supports_customization ? 'Customizable' : 'Quick add'"></span>
                                    <button type="button" class="primary-action px-4 py-2" @click="openModal('edit', product)">
                                        Edit
                                    </button>
                                </div>
                            </article>
                        </template>
                    </div>
                </section>
            </template>
        </div>

        <div
            x-show="modalOpen"
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/45 p-3 sm:p-4 backdrop-blur-[2px]"
            x-cloak
        >
            <div
                @click.outside="closeModal()"
                class="max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-[30px] border border-slate-300 bg-[#f7f9fc] p-5 shadow-[0_25px_80px_rgba(15,23,42,0.24)] sm:p-6"
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400" x-text="modalMode === 'edit' ? 'Edit Product' : 'Add Product'"></p>
                        <h3 class="mt-2 font-display text-2xl font-semibold text-slate-900" x-text="modalMode === 'edit' ? (selectedProduct?.name ?? 'Edit menu item') : 'Add menu item'"></h3>
                        <p class="mt-2 text-sm text-slate-500">Keep the list clean here, and open the full customization setup only when you are editing or creating.</p>
                    </div>
                    <button type="button" @click="closeModal()" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-300 bg-white text-slate-700">
                        <svg class="h-4 w-4" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" :action="modalMode === 'edit' && selectedProduct ? `/admin/products/${selectedProduct.id}` : '{{ route('admin.products.store') }}'" class="mt-6 space-y-5">
                    @csrf
                    <template x-if="modalMode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <input type="hidden" name="form_mode" :value="modalMode">
                    <input type="hidden" name="product_id" :value="selectedProduct?.id ?? ''">
                    <input type="hidden" name="is_active" :value="form.is_active ? 1 : 0">
                    <input type="hidden" name="supports_customization" :value="form.supports_customization ? 1 : 0">

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-slate-900">Menu name</label>
                            <input type="text" name="name" x-model="form.name" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-slate-900 focus:outline-none" placeholder="Brown Sugar Boba">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-900">Category</label>
                            <select name="category_id" x-model="form.category_id" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-slate-900 focus:outline-none">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-slate-900">Base price</label>
                            <input type="number" step="0.01" min="0" name="base_price" x-model="form.base_price" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-slate-900 focus:outline-none" placeholder="75.00">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-900">Description</label>
                            <textarea name="description" rows="3" x-model="form.description" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-slate-900 focus:outline-none" placeholder="Short menu description"></textarea>
                        </div>
                    </div>

                    <div class="grid gap-3 md:grid-cols-2">
                        <label class="flex items-center gap-3 rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-700">
                            <input type="checkbox" x-model="form.is_active" class="rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                            Active on cashier POS
                        </label>
                        <label class="flex items-center gap-3 rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-700">
                            <input type="checkbox" x-model="form.supports_customization" class="rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                            Allow drink customization
                        </label>
                    </div>

                    <div class="rounded-[24px] border border-slate-200 bg-slate-50 p-4">
                        <p class="text-sm font-semibold text-slate-900">Customization details</p>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-slate-700">Sizes</label>
                                <input type="text" name="available_sizes" x-model="form.available_sizes" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-slate-900 focus:outline-none" placeholder="Small, Medium, Large">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-700">Sugar levels</label>
                                <input type="text" name="sugar_levels" x-model="form.sugar_levels" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-slate-900 focus:outline-none" placeholder="0%, 25%, 50%, 75%, 100%">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-700">Ice levels</label>
                                <input type="text" name="ice_levels" x-model="form.ice_levels" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-slate-900 focus:outline-none" placeholder="No Ice, Less Ice, Normal Ice, Extra Ice">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-700">Add-ons and prices</label>
                                <textarea name="add_on_options" rows="4" x-model="form.add_on_options" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-slate-900 focus:outline-none" placeholder="Pearls:20&#10;Cheese Foam:30"></textarea>
                            </div>
                        </div>
                        <p class="mt-3 text-xs text-slate-500">Use one add-on per line with the format <span class="font-semibold">name:price</span>. These details stay inside the add/edit flow instead of cluttering the main list.</p>
                    </div>

                    @if ($errors->any())
                        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
                    @endif

                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="text-sm text-slate-500" x-show="modalMode === 'edit' && selectedProduct">
                            Editing: <span class="font-semibold text-slate-900" x-text="selectedProduct?.name"></span>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" @click="closeModal()" class="secondary-action">Cancel</button>
                            <button type="submit" class="primary-action" x-text="modalMode === 'edit' ? 'Save changes' : 'Add menu item'"></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function adminProductsPage(config) {
            return {
                groupedProducts: config.groupedProducts,
                categories: config.categories,
                editingProduct: config.editingProduct,
                initialForm: config.initialForm,
                hasErrors: config.hasErrors,
                oldFormMode: config.oldFormMode,
                oldProductId: config.oldProductId,
                activeCategoryId: config.groupedProducts[0]?.id ?? null,
                modalOpen: false,
                modalMode: 'create',
                selectedProduct: null,
                form: {},
                initialize() {
                    if (this.hasErrors) {
                        const product = this.findProduct(this.oldProductId);
                        this.openModal(this.oldFormMode === 'edit' ? 'edit' : 'create', product, true);
                        return;
                    }

                    if (this.editingProduct) {
                        this.openModal('edit', this.editingProduct);
                    } else {
                        this.form = this.emptyForm();
                    }
                },
                emptyForm() {
                    return {
                        name: '',
                        category_id: this.categories[0]?.id?.toString() ?? '',
                        base_price: '',
                        description: '',
                        available_sizes: 'Small, Medium, Large',
                        sugar_levels: '0%, 25%, 50%, 75%, 100%',
                        ice_levels: 'No Ice, Less Ice, Normal Ice, Extra Ice',
                        add_on_options: 'Pearls:20\nCrystal Boba:15\nCheese Foam:30',
                        is_active: true,
                        supports_customization: true,
                    };
                },
                formFromProduct(product) {
                    return {
                        name: product?.name ?? '',
                        category_id: product?.category_id?.toString?.() ?? this.categories[0]?.id?.toString() ?? '',
                        base_price: product?.base_price?.toString?.() ?? '',
                        description: product?.description ?? '',
                        available_sizes: (product?.available_sizes ?? []).join(', '),
                        sugar_levels: (product?.sugar_levels ?? []).join(', '),
                        ice_levels: (product?.ice_levels ?? []).join(', '),
                        add_on_options: (product?.add_on_options ?? []).map(addOn => `${addOn.name}:${addOn.price}`).join('\n'),
                        is_active: Boolean(product?.is_active ?? true),
                        supports_customization: Boolean(product?.supports_customization ?? true),
                    };
                },
                findProduct(productId) {
                    if (!productId) return null;
                    for (const group of this.groupedProducts) {
                        const match = group.products.find(product => Number(product.id) === Number(productId));
                        if (match) return match;
                    }
                    return null;
                },
                openModal(mode, product = null, preserveForm = false) {
                    this.modalMode = mode;
                    this.selectedProduct = product;
                    this.modalOpen = true;
                    this.form = preserveForm
                        ? { ...this.emptyForm(), ...this.initialForm }
                        : (mode === 'edit' && product ? this.formFromProduct(product) : this.emptyForm());
                    if (product?.category_id) {
                        this.activeCategoryId = product.category_id;
                    }
                },
                closeModal() {
                    this.modalOpen = false;
                    this.selectedProduct = null;
                },
                formatCurrency(value) {
                    return `PHP ${Number(value || 0).toFixed(2)}`;
                },
            }
        }
    </script>
</x-app-layout>
