<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.35em] text-amber-600">Admin Inventory</p>
                <h2 class="font-display text-2xl font-semibold text-slate-900">Ingredients</h2>
            </div>
            <button
                type="button"
                @click="$dispatch('open-inventory-modal', { mode: 'create' })"
                class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white"
            >
                + Add Ingredient
            </button>
        </div>
    </x-slot>

    <div
        class="px-5 py-5 sm:px-7 sm:py-7 lg:px-8"
        x-data="adminInventoryPage({
            items: {{ Js::from($inventoryItems) }},
            editingItem: {{ Js::from($editingItem) }},
            initialForm: {{ Js::from($inventoryFormData) }},
            hasErrors: {{ $errors->any() ? 'true' : 'false' }},
            oldFormMode: @js(old('form_mode', $editingItem ? 'edit' : 'create')),
            oldItemId: @js(old('inventory_item_id', $editingItem?->id)),
        })"
        x-init="initialize()"
        @open-inventory-modal.window="openModal($event.detail.mode, $event.detail.item ?? null)"
    >
        @if (session('status'))
            <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
        @endif

        <div class="grid gap-4 md:grid-cols-3">
            <article class="rounded-[26px] border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Ingredients</p>
                <h3 class="mt-3 font-display text-3xl font-semibold text-slate-900">{{ $inventoryItems->count() }}</h3>
            </article>
            <article class="rounded-[26px] border border-amber-200 bg-amber-50 p-5 shadow-sm">
                <p class="text-xs uppercase tracking-[0.3em] text-amber-800">Low Stock</p>
                <h3 class="mt-3 font-display text-3xl font-semibold text-slate-900">{{ $inventoryItems->filter(fn ($item) => $item->stock <= $item->reorder_level)->count() }}</h3>
            </article>
            <article class="rounded-[26px] border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Units Tracked</p>
                <h3 class="mt-3 font-display text-3xl font-semibold text-slate-900">{{ $inventoryItems->pluck('unit')->unique()->count() }}</h3>
            </article>
        </div>

        <div class="mt-6 rounded-[30px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Stock List</p>
                    <h3 class="mt-2 font-display text-2xl font-semibold text-slate-900">Manage ingredient levels</h3>
                </div>
                <p class="text-sm text-slate-500">Keep this page focused on ingredients, stock amounts, and reorder points.</p>
            </div>

            <div class="mt-6 space-y-3">
                @foreach ($inventoryItems as $item)
                    <article class="rounded-[24px] bg-slate-50 px-4 py-4">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="font-medium text-slate-900">{{ $item->name }}</p>
                                    <span class="rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] {{ $item->stock <= $item->reorder_level ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                        {{ $item->stock <= $item->reorder_level ? 'Low stock' : 'Healthy' }}
                                    </span>
                                </div>
                                <p class="mt-2 text-sm text-slate-500">
                                    {{ rtrim(rtrim(number_format((float) $item->stock, 2), '0'), '.') }} {{ $item->unit }} on hand
                                </p>
                                <p class="mt-1 text-sm text-slate-500">
                                    Reorder at {{ rtrim(rtrim(number_format((float) $item->reorder_level, 2), '0'), '.') }} {{ $item->unit }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button
                                    type="button"
                                    class="rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white"
                                    @click="openModal('edit', {{ Js::from($item) }})"
                                >
                                    Edit
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        <div
            x-show="modalOpen"
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/45 p-3 sm:p-4 backdrop-blur-[2px]"
            x-cloak
        >
            <div
                @click.outside="closeModal()"
                class="w-full max-w-2xl rounded-[30px] border border-slate-300 bg-[#f7f9fc] p-5 shadow-[0_25px_80px_rgba(15,23,42,0.24)] sm:p-6"
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400" x-text="modalMode === 'edit' ? 'Edit Ingredient' : 'Add Ingredient'"></p>
                        <h3 class="mt-2 font-display text-2xl font-semibold text-slate-900" x-text="modalMode === 'edit' ? (selectedItem?.name ?? 'Edit ingredient') : 'Add ingredient'"></h3>
                        <p class="mt-2 text-sm text-slate-500">Track ingredient stock and set reorder levels for the admin inventory page.</p>
                    </div>
                    <button type="button" @click="closeModal()" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-300 bg-white text-slate-700">
                        <svg class="h-4 w-4" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" :action="modalMode === 'edit' && selectedItem ? `/admin/inventory/${selectedItem.id}` : '{{ route('admin.inventory.store') }}'" class="mt-6 space-y-5">
                    @csrf
                    <template x-if="modalMode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <input type="hidden" name="form_mode" :value="modalMode">
                    <input type="hidden" name="inventory_item_id" :value="selectedItem?.id ?? ''">

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-slate-900">Ingredient name</label>
                            <input type="text" name="name" x-model="form.name" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-slate-900 focus:outline-none" placeholder="Brown Sugar Pearls">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-900">Unit</label>
                            <input type="text" name="unit" x-model="form.unit" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-slate-900 focus:outline-none" placeholder="kg, liters, packs">
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-slate-900">Current stock</label>
                            <input type="number" step="0.01" min="0" name="stock" x-model="form.stock" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-slate-900 focus:outline-none" placeholder="0">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-900">Reorder level</label>
                            <input type="number" step="0.01" min="0" name="reorder_level" x-model="form.reorder_level" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 focus:border-slate-900 focus:outline-none" placeholder="0">
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
                    @endif

                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="text-sm text-slate-500" x-show="modalMode === 'edit' && selectedItem">
                            Editing: <span class="font-semibold text-slate-900" x-text="selectedItem?.name"></span>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" @click="closeModal()" class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700">Cancel</button>
                            <button type="submit" class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white" x-text="modalMode === 'edit' ? 'Save changes' : 'Add ingredient'"></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function adminInventoryPage(config) {
            return {
                items: config.items,
                editingItem: config.editingItem,
                initialForm: config.initialForm,
                hasErrors: config.hasErrors,
                oldFormMode: config.oldFormMode,
                oldItemId: config.oldItemId,
                modalOpen: false,
                modalMode: 'create',
                selectedItem: null,
                form: {},
                initialize() {
                    if (this.hasErrors) {
                        this.openModal(this.oldFormMode === 'edit' ? 'edit' : 'create', this.findItem(this.oldItemId), true);
                        return;
                    }

                    if (this.editingItem) {
                        this.openModal('edit', this.editingItem);
                    } else {
                        this.form = this.emptyForm();
                    }
                },
                emptyForm() {
                    return {
                        name: '',
                        unit: '',
                        stock: '',
                        reorder_level: '',
                    };
                },
                formFromItem(item) {
                    return {
                        name: item?.name ?? '',
                        unit: item?.unit ?? '',
                        stock: item?.stock?.toString?.() ?? '',
                        reorder_level: item?.reorder_level?.toString?.() ?? '',
                    };
                },
                findItem(itemId) {
                    return this.items.find(item => Number(item.id) === Number(itemId)) ?? null;
                },
                openModal(mode, item = null, preserveForm = false) {
                    this.modalMode = mode;
                    this.selectedItem = item;
                    this.modalOpen = true;
                    this.form = preserveForm
                        ? { ...this.emptyForm(), ...this.initialForm }
                        : (mode === 'edit' && item ? this.formFromItem(item) : this.emptyForm());
                },
                closeModal() {
                    this.modalOpen = false;
                    this.selectedItem = null;
                },
            }
        }
    </script>
</x-app-layout>
