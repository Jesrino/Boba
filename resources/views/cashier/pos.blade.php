<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.35em] text-amber-600">Cashier POS</p>
                <h2 class="font-display text-2xl font-semibold leading-tight text-slate-900">Products and checkout</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <div class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    {{ $user->name }}
                </div>
                <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-700">
                    {{ $products->count() }} menu items
                </div>
            </div>
        </div>
    </x-slot>

    <div class="px-5 py-5 sm:px-7 sm:py-7 lg:px-8" x-data="cashierPos({{ Js::from([
        'products' => $products,
        'categories' => $categories->map(fn ($category) => ['id' => $category->id, 'name' => $category->name]),
        'paymentMethods' => $paymentMethods,
        'defaultSugarLevels' => $defaultSugarLevels,
        'defaultIceLevels' => $defaultIceLevels,
        'defaultAddOnOptions' => $defaultAddOnOptions,
        'oldAmountReceived' => old('amount_received', ''),
    ]) }})">
        @if ($errors->any() && !$errors->has('amount_received'))
            <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
        @endif

        <div class="grid gap-6 2xl:grid-cols-[minmax(0,1.95fr)_420px]">
            <div class="space-y-6">
                <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <article class="rounded-[26px] border border-slate-200 bg-slate-900 p-6 text-white"><p class="text-xs uppercase tracking-[0.3em] text-slate-300">Orders Today</p><h3 class="mt-4 font-display text-3xl font-semibold">{{ $ordersToday }}</h3><p class="mt-2 text-sm text-slate-300">Completed sales</p></article>
                    <article class="rounded-[26px] border border-slate-200 bg-white p-6 shadow-sm"><p class="text-xs uppercase tracking-[0.3em] text-slate-400">Sales Today</p><h3 class="mt-4 font-display text-3xl font-semibold text-slate-900">PHP {{ number_format($salesToday, 2) }}</h3><p class="mt-2 text-sm text-slate-500">Saved transactions</p></article>
                    <article class="rounded-[26px] border border-slate-200 bg-white p-6 shadow-sm"><p class="text-xs uppercase tracking-[0.3em] text-slate-400">Change</p><h3 class="mt-4 font-display text-3xl font-semibold text-slate-900" x-text="formattedChange"></h3><p class="mt-2 text-sm text-slate-500">Based on amount received</p></article>
                    <article class="rounded-[26px] border border-teal-300 bg-teal-100 p-6 shadow-sm"><p class="text-xs uppercase tracking-[0.3em] text-teal-900">Avg Ticket</p><h3 class="mt-4 font-display text-3xl font-semibold text-slate-950">PHP {{ number_format($averageTicket, 2) }}</h3><p class="mt-2 text-sm font-medium text-teal-900">Today</p></article>
                </section>

                <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Products</p>
                            <h3 class="mt-2 font-display text-2xl font-semibold text-slate-900">Tap a product to customize</h3>
                        </div>
                        <div class="flex flex-wrap gap-2 text-sm">
                            <template x-for="category in categories" :key="category.id">
                                <button type="button" class="rounded-full px-4 py-2 font-medium" :class="selectedCategoryId === category.id ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600'" @click="selectedCategoryId = category.id" x-text="category.name"></button>
                            </template>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 lg:grid-cols-2 2xl:grid-cols-3">
                        <template x-for="product in filteredProducts" :key="product.id">
                            <button type="button" @click="selectProduct(product)" class="rounded-[26px] border border-slate-200 bg-slate-50 p-4 text-left shadow-sm transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md">
                                <div class="overflow-hidden rounded-[22px] bg-white">
                                    <div class="aspect-[4/3] overflow-hidden bg-slate-100">
                                        <img :src="product.menu_image" :alt="product.name" class="h-full w-full object-cover" loading="lazy">
                                    </div>
                                    <div class="p-5">
                                    <p class="text-xs uppercase tracking-[0.28em] text-slate-400" x-text="product.category?.name || 'Menu Item'"></p>
                                    <h4 class="mt-2 font-display text-xl font-semibold text-slate-900" x-text="product.name"></h4>
                                    <p class="mt-2 text-sm text-slate-500" x-text="product.description || 'Boba shop item'"></p>
                                    <p class="mt-8 text-3xl font-semibold text-slate-900" x-text="formatCurrency(product.base_price)"></p>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center justify-between gap-3">
                                    <div class="text-xs uppercase tracking-[0.18em] text-slate-400">Open customizer</div>
                                    <span class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Customize</span>
                                </div>
                            </button>
                        </template>
                    </div>
                </section>

                <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Recent Sales</p>
                    <h3 class="mt-2 font-display text-2xl font-semibold text-slate-900">Your transactions</h3>
                    <div class="mt-6 space-y-3">
                        @forelse ($recentTransactions as $transaction)
                            <a href="{{ route('cashier.receipt.show', $transaction) }}" class="block rounded-[22px] bg-slate-50 px-4 py-4 transition hover:bg-slate-100">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $transaction->receipt_number }}</p>
                                        <p class="text-sm text-slate-500">{{ $transaction->paid_at?->format('M d, h:i A') }}</p>
                                    </div>
                                    <p class="font-semibold text-slate-900">PHP {{ number_format((float) $transaction->total_amount, 2) }}</p>
                                </div>
                            </a>
                        @empty
                            <div class="rounded-[22px] bg-slate-50 px-4 py-4 text-sm text-slate-500">No transactions yet.</div>
                        @endforelse
                    </div>
                </section>
            </div>

            <aside class="space-y-6">
                <form method="POST" action="{{ route('cashier.checkout') }}" class="rounded-[28px] border border-slate-200 bg-slate-900 p-6 text-white shadow-sm">
                    @csrf
                    <input type="hidden" name="cart" :value="serializedCart">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-300">Cart</p>
                            <h3 class="mt-2 font-display text-2xl font-semibold">Current order</h3>
                        </div>
                        <button type="button" class="rounded-full bg-white/10 px-3 py-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-white" @click="clearCart">Clear</button>
                    </div>

                    <div class="mt-6 space-y-4" x-show="cart.length > 0">
                        <template x-for="(item, index) in cart" :key="`${item.product_id}-${index}`">
                            <article class="rounded-[22px] bg-white/5 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-medium text-white" x-text="item.product_name"></p>
                                        <p class="mt-1 text-sm text-slate-300" x-text="`${item.size} - ${item.sugar_level} - ${item.ice_level}`"></p>
                                        <p class="mt-2 text-xs uppercase tracking-[0.2em] text-amber-300" x-text="item.add_ons.length ? item.add_ons.join(', ') : 'No add-ons'"></p>
                                        <p class="mt-2 text-xs text-slate-400" x-show="item.notes" x-text="item.notes"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold" x-text="formatCurrency(item.line_total)"></p>
                                        <div class="mt-3 flex items-center gap-2">
                                            <button type="button" class="rounded-xl bg-white/10 px-2 py-1 text-xs" @click="updateCartQuantity(index, item.quantity - 1)">-</button>
                                            <span class="text-sm font-semibold" x-text="item.quantity"></span>
                                            <button type="button" class="rounded-xl bg-white/10 px-2 py-1 text-xs" @click="updateCartQuantity(index, item.quantity + 1)">+</button>
                                            <button type="button" class="rounded-xl bg-red-500/20 px-2 py-1 text-xs text-red-200" @click="removeCartItem(index)">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </template>
                    </div>

                    <div class="mt-6 rounded-[22px] bg-white/5 p-4 text-sm text-slate-300" x-show="cart.length === 0">Cart is empty.</div>

                    <div class="mt-6 space-y-3 border-t border-white/10 pt-5 text-sm">
                        <div class="flex items-center justify-between text-slate-300"><span>Subtotal</span><span x-text="formattedSubtotal"></span></div>
                        <div class="flex items-center justify-between text-slate-300"><span>Tax</span><span x-text="formattedTax"></span></div>
                        <div class="flex items-center justify-between border-t border-white/10 pt-3 text-base font-semibold text-white"><span>Total</span><span x-text="formattedTotal"></span></div>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div>
                            <label class="text-sm font-semibold text-white">Payment method</label>
                            <div class="mt-3 grid grid-cols-2 gap-3">
                                <template x-for="method in paymentMethods" :key="method">
                                    <button type="button" class="rounded-2xl px-4 py-3 text-sm font-semibold uppercase" :class="paymentMethod === method ? 'bg-white text-slate-900' : 'bg-white/10 text-white'" @click="paymentMethod = method" x-text="method"></button>
                                </template>
                            </div>
                            <input type="hidden" name="payment_method" :value="paymentMethod">
                        </div>
                        <div>
                            <label for="amount_received" class="text-sm font-semibold text-white">Amount received</label>
                            <input id="amount_received" type="number" step="0.01" min="0" name="amount_received" x-model="amountReceived" class="mt-3 w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-slate-400 focus:border-white focus:outline-none" placeholder="0.00">
                            @if ($errors->has('amount_received'))
                                <p
                                    x-data="{ visible: true }"
                                    x-init="setTimeout(() => visible = false, 3000)"
                                    x-show="visible"
                                    x-transition.opacity.duration.300ms
                                    class="mt-2 text-sm text-red-300"
                                >
                                    {{ $errors->first('amount_received') }}
                                </p>
                            @endif
                        </div>
                        <div class="rounded-[22px] bg-white/5 p-4 text-sm">
                            <div class="flex items-center justify-between"><span class="text-slate-300">Change</span><span class="font-semibold text-white" x-text="formattedChange"></span></div>
                        </div>
                    </div>

                    <button type="submit" class="mt-6 w-full rounded-[20px] bg-white px-5 py-4 text-sm font-semibold text-slate-900" :disabled="cart.length === 0">Complete payment</button>
                </form>
            </aside>
        </div>

        <div
            x-show="selectedProduct"
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/45 p-3 sm:p-4 backdrop-blur-[2px]"
            x-cloak
        >
            <div
                @click.outside="closeCustomizer()"
                class="max-h-[88vh] w-full max-w-2xl overflow-y-auto rounded-[28px] border border-slate-300 bg-[#f7f9fc] p-5 shadow-[0_25px_80px_rgba(15,23,42,0.24)] sm:p-6"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="flex min-w-0 items-start gap-4">
                        <div class="hidden h-20 w-20 overflow-hidden rounded-[22px] bg-slate-100 sm:block">
                            <img :src="selectedProduct?.menu_image" :alt="selectedProduct?.name" class="h-full w-full object-cover">
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Customize Product</p>
                            <h3 class="mt-2 font-display text-xl font-semibold text-slate-900 sm:text-2xl" x-text="selectedProduct?.name"></h3>
                            <p class="mt-2 max-w-xl text-sm text-slate-500" x-text="selectedProduct?.description"></p>
                        </div>
                    </div>
                    <button type="button" @click="closeCustomizer()" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-300 bg-white text-slate-700">
                        <svg class="h-4 w-4" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div class="rounded-[20px] bg-white p-4 shadow-sm">
                        <p class="text-sm font-semibold text-slate-900">Size</p>
                        <div class="mt-3 grid grid-cols-3 gap-2">
                            <template x-for="size in selectedProduct?.available_sizes ?? []" :key="size">
                                <button type="button" class="rounded-2xl px-3 py-2.5 text-sm" :class="builder.size === size ? 'bg-slate-900 text-white' : 'border border-slate-200 bg-[#f6f8fb] text-slate-600'" @click="builder.size = size" x-text="size"></button>
                            </template>
                        </div>
                    </div>
                    <div class="rounded-[20px] bg-white p-4 shadow-sm">
                        <p class="text-sm font-semibold text-slate-900">Quantity</p>
                        <div class="mt-3 flex items-center gap-3">
                            <button type="button" class="rounded-2xl border border-slate-200 bg-[#f6f8fb] px-4 py-2.5 text-sm text-slate-600" @click="decreaseQuantity()">-</button>
                            <div class="min-w-[70px] rounded-2xl bg-[#f6f8fb] px-4 py-2.5 text-center text-sm font-semibold text-slate-900" x-text="builder.quantity"></div>
                            <button type="button" class="rounded-2xl border border-slate-200 bg-[#f6f8fb] px-4 py-2.5 text-sm text-slate-600" @click="builder.quantity++">+</button>
                        </div>
                    </div>
                    <div class="rounded-[20px] bg-white p-4 shadow-sm" x-show="selectedSugarLevels.length > 0">
                        <p class="text-sm font-semibold text-slate-900">Sugar</p>
                        <div class="mt-3 grid grid-cols-3 gap-2">
                            <template x-for="level in selectedSugarLevels" :key="level">
                                <button type="button" class="rounded-2xl px-3 py-2.5 text-xs font-semibold uppercase tracking-[0.18em]" :class="builder.sugar_level === level ? 'bg-slate-900 text-white' : 'bg-[#f6f8fb] text-slate-500'" @click="builder.sugar_level = level" x-text="level"></button>
                            </template>
                        </div>
                    </div>
                    <div class="rounded-[20px] bg-white p-4 shadow-sm" x-show="selectedIceLevels.length > 0">
                        <p class="text-sm font-semibold text-slate-900">Ice</p>
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <template x-for="level in selectedIceLevels" :key="level">
                                <button type="button" class="rounded-2xl px-3 py-2.5 text-xs font-semibold uppercase tracking-[0.12em]" :class="builder.ice_level === level ? 'bg-slate-900 text-white' : 'bg-[#f6f8fb] text-slate-500'" @click="builder.ice_level = level" x-text="level"></button>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="mt-4 rounded-[20px] border border-dashed border-slate-200 bg-white p-4" x-show="selectedAddOnOptions.length > 0">
                    <p class="text-sm font-semibold text-slate-900">Add-ons</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <template x-for="addOn in selectedAddOnOptions" :key="addOn.name">
                            <button type="button" class="rounded-full px-4 py-2 text-sm" :class="builder.add_ons.includes(addOn.name) ? 'bg-slate-900 text-white' : 'bg-[#f6f8fb] text-slate-600'" @click="toggleAddOn(addOn.name)" x-text="`${addOn.name} +${formatCurrency(addOn.price)}`"></button>
                        </template>
                    </div>
                </div>

                <div class="mt-4 rounded-[20px] bg-white p-4 shadow-sm">
                    <label for="notes" class="text-sm font-semibold text-slate-900">Notes</label>
                    <textarea id="notes" x-model="builder.notes" rows="3" class="mt-3 w-full rounded-2xl border border-slate-200 bg-[#f6f8fb] px-4 py-3 text-sm text-slate-700 focus:border-slate-900 focus:outline-none" placeholder="Optional notes"></textarea>
                </div>

                <div class="mt-5 flex flex-wrap items-center justify-between gap-4">
                    <p class="text-sm text-slate-500">Unit price: <span class="font-semibold text-slate-900" x-text="formatCurrency(builderUnitPrice)"></span></p>
                    <div class="flex gap-3">
                        <button type="button" @click="closeCustomizer()" class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700">Cancel</button>
                        <button type="button" @click="addToCart()" class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Add to cart</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function cashierPos(config) {
            return {
                products: config.products.map(product => ({ ...product, base_price: Number(product.base_price) })),
                categories: config.categories,
                paymentMethods: config.paymentMethods,
                defaultSugarLevels: config.defaultSugarLevels,
                defaultIceLevels: config.defaultIceLevels,
                defaultAddOnOptions: config.defaultAddOnOptions,
                selectedCategoryId: config.categories[0]?.id ?? null,
                selectedProduct: null,
                paymentMethod: config.paymentMethods[0] ?? 'cash',
                amountReceived: config.oldAmountReceived ?? '',
                cart: [],
                builder: { quantity: 1, size: 'Medium', sugar_level: '50%', ice_level: 'Normal Ice', add_ons: [], notes: '' },
                get filteredProducts() { return this.products.filter(product => product.category_id === this.selectedCategoryId); },
                get selectedSugarLevels() {
                    return this.selectedProduct?.sugar_levels?.length ? this.selectedProduct.sugar_levels : this.defaultSugarLevels;
                },
                get selectedIceLevels() {
                    return this.selectedProduct?.ice_levels?.length ? this.selectedProduct.ice_levels : this.defaultIceLevels;
                },
                get selectedAddOnOptions() {
                    return this.selectedProduct?.add_on_options?.length ? this.selectedProduct.add_on_options : this.defaultAddOnOptions;
                },
                get builderUnitPrice() {
                    if (!this.selectedProduct) return 0;
                    const sizePrice = { Small: 0, Medium: 10, Large: 20 }[this.builder.size] ?? 0;
                    const addOnPrice = this.builder.add_ons.reduce((sum, addOn) => sum + (Number(this.selectedAddOnOptions.find(item => item.name === addOn)?.price ?? 0)), 0);
                    return Number(this.selectedProduct.base_price) + sizePrice + addOnPrice;
                },
                get subtotal() { return this.cart.reduce((sum, item) => sum + Number(item.line_total), 0); },
                get tax() { return this.subtotal * 0.12; },
                get total() { return this.subtotal + this.tax; },
                get change() { return Number(this.amountReceived || 0) - this.total; },
                get serializedCart() { return JSON.stringify(this.cart); },
                get formattedSubtotal() { return this.formatCurrency(this.subtotal); },
                get formattedTax() { return this.formatCurrency(this.tax); },
                get formattedTotal() { return this.formatCurrency(this.total); },
                get formattedChange() { return this.formatCurrency(Math.max(this.change, 0)); },
                formatCurrency(value) { return `PHP ${Number(value || 0).toFixed(2)}`; },
                selectProduct(product) {
                    this.selectedProduct = product;
                    this.builder = {
                        quantity: 1,
                        size: product.available_sizes?.includes('Medium') ? 'Medium' : product.available_sizes?.[0] ?? 'Small',
                        sugar_level: product.sugar_levels?.[2] ?? product.sugar_levels?.[0] ?? this.defaultSugarLevels[2] ?? '50%',
                        ice_level: product.ice_levels?.[2] ?? product.ice_levels?.[0] ?? this.defaultIceLevels[2] ?? 'Normal Ice',
                        add_ons: [],
                        notes: '',
                    };
                },
                closeCustomizer() {
                    this.selectedProduct = null;
                },
                toggleAddOn(name) {
                    this.builder.add_ons = this.builder.add_ons.includes(name)
                        ? this.builder.add_ons.filter(item => item !== name)
                        : [...this.builder.add_ons, name];
                },
                decreaseQuantity() {
                    this.builder.quantity = Math.max(1, this.builder.quantity - 1);
                },
                addToCart() {
                    if (!this.selectedProduct) return;
                    const unitPrice = this.builderUnitPrice;
                    const quantity = Number(this.builder.quantity);
                    this.cart.push({
                        product_id: this.selectedProduct.id,
                        product_name: this.selectedProduct.name,
                        size: this.builder.size,
                        sugar_level: this.builder.sugar_level,
                        ice_level: this.builder.ice_level,
                        add_ons: [...this.builder.add_ons],
                        notes: this.builder.notes.trim(),
                        quantity,
                        unit_price: unitPrice,
                        line_total: unitPrice * quantity,
                    });
                    this.closeCustomizer();
                },
                updateCartQuantity(index, quantity) {
                    if (quantity <= 0) return this.removeCartItem(index);
                    this.cart[index].quantity = quantity;
                    this.cart[index].line_total = Number(this.cart[index].unit_price) * quantity;
                    this.cart = [...this.cart];
                },
                removeCartItem(index) {
                    this.cart.splice(index, 1);
                    this.cart = [...this.cart];
                },
                clearCart() {
                    this.cart = [];
                    this.amountReceived = '';
                },
            }
        }
    </script>
</x-app-layout>
