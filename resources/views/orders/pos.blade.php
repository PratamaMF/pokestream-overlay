@extends('layout.main')

@section('title', 'Order Management - '  . Auth::user()->name)
@section('namepage', 'Order Management')
@section('route', route('pos.index'))
@section('namemenu', 'Order Management System')

@section('content')
<form id="posOrderForm" action="{{ route('pos.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-xl-7 col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-dark"><i class="fas fa-box-open me-2 text-primary"></i>Select Products</h6>
                </div>
                <div class="card-body bg-light" style="max-height: 600px; overflow-y: auto;">
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        @foreach($products as $product)
                        <div class="col">
                            <div id="product-card-{{ $product->id }}" 
                                 class="card h-100 border-0 shadow-xs product-card px-3 py-2 bg-white transition-all" 
                                 style="cursor: pointer; border-left: 4px solid #001f3f !important;"
                                 onclick="addToCart('{{ $product->id }}', '{{ $product->product_name }}', {{ $product->price }}, {{ $product->stock }})">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-truncate me-2">
                                        <span class="fw-bold text-dark d-block text-truncate mb-0" style="font-size: 14px;">
                                            {{ $product->product_name }}
                                        </span>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <small class="text-muted" style="font-size: 11px;">Stock: <strong class="text-primary">{{ $product->stock }}</strong> pcs</small>
                                            <span id="badge-cart-{{ $product->id }}" class="badge bg-success text-white px-1.5 py-0.5 rounded-pill d-none" style="font-size: 10px;">
                                                <i class="fas fa-shopping-basket me-1"></i><span class="qty-count">0</span> In Cart
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-soft-primary text-primary fw-bold" style="font-size: 13px;">
                                            Rp{{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-5 col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-dark"><i class="fas fa-shopping-cart me-2 text-warning"></i>Customer Checkout Cart</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-600">Customer Name</label>
                        <input type="text" id="customerNameInput" name="customer_name" class="form-control form-control-lg" placeholder="e.g. John Doe" required value="{{ old('customer_name') }}" />
                    </div>

                    <h6 class="fw-bold text-muted small text-uppercase mb-3">Items List</h6>
                    
                    <div id="cartContainer" class="mb-4" style="min-height: 150px; max-height: 300px; overflow-y: auto;">
                        <div class="text-center text-muted py-5" id="emptyCartMessage">
                            Your cart is empty. Click the product card on the left to add it.
                        </div>
                    </div>

                    <hr class="my-3 opacity-5" />

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold text-dark">Grand Total</span>
                        <h4 class="fw-bold text-primary mb-0" id="grandTotalLabel">Rp 0</h4>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary btn-lg fw-bold py-2.5 shadow-sm" onclick="openPreviewModal()">
                            <i class="fas fa-receipt me-2"></i>Review Order
                        </button>
                        <button type="button" class="btn btn-danger text-white btn-sm" onclick="clearCart()">Clear Cart</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="previewOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" style="font-size: 16px;"><i class="fas fa-check-circle text-warning me-2"></i>Confirm Transaction Queue</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-3">
                        <i class="fas fa-paper-plane text-primary fa-2x mb-2 opacity-50"></i>
                        <p class="text-muted small mb-0">Silakan periksa kembali detail pesanan sebelum dimasukkan ke antrean live streaming.</p>
                    </div>

                    <div class="mb-3 border rounded p-3 bg-light">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 10px; letter-spacing: 0.5px;">Customer Name</small>
                        <span id="previewCustomerName" class="h6 fw-bold text-dark mb-0 d-block">-</span>
                    </div>

                    <h6 class="fw-bold text-dark small text-uppercase mb-2">Order Breakdown</h6>
                    <div class="border rounded p-2 bg-white mb-3" id="previewItemsList" style="max-height: 200px; overflow-y: auto;">
                        </div>

                    <div class="d-flex justify-content-between align-items-center bg-soft-primary p-3 rounded border border-primary-subtle">
                        <span class="fw-bold text-dark">Total Amount Due</span>
                        <h5 class="fw-bold text-primary mb-0" id="previewGrandTotal">Rp 0</h5>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-2.5">
                    <button type="button" class="btn btn-soft-secondary btn-sm px-3" data-bs-dismiss="modal">Re-Check</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold shadow-sm" id="submitConfirmButton">
                        <i class="fas fa-check me-1"></i>Confirm & Push to Queue
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    let cart = {};

    function addToCart(id, name, price, maxStock) {
        if (maxStock <= 0) {
            alert("This product is out of stock!");
            return;
        }

        if (cart[id]) {
            if (cart[id].qty >= maxStock) {
                alert(`Cannot exceed maximum available stock! (${maxStock} pcs)`);
                return;
            }
            cart[id].qty += 1;
        } else {
            cart[id] = { id: id, name: name, price: price, qty: 1, maxStock: maxStock };
        }
        renderCart();
    }

    function updateQty(id, value) {
        let qty = parseInt(value);
        if (isNaN(qty) || qty < 1) qty = 1;
        
        if (qty > cart[id].maxStock) {
            alert(`Cannot exceed maximum available stock! (${cart[id].maxStock} pcs)`);
            qty = cart[id].maxStock;
        }
        cart[id].qty = qty;
        renderCart();
    }

    function incrementQty(id) {
        if (cart[id].qty >= cart[id].maxStock) {
            alert(`Cannot exceed maximum available stock! (${cart[id].maxStock} pcs)`);
            return;
        }
        cart[id].qty += 1;
        renderCart();
    }

    function decrementQty(id) {
        if (cart[id].qty <= 1) {
            removeItem(id);
        } else {
            cart[id].qty -= 1;
            renderCart();
        }
    }

    function removeItem(id) {
        delete cart[id];
        renderCart();
    }

    function clearCart() {
        cart = {};
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cartContainer');
        const emptyMsg = document.getElementById('emptyCartMessage');
        let html = '';
        let total = 0;
        let index = 0;

        const cartKeys = Object.keys(cart);

        document.querySelectorAll('.product-card').forEach(card => {
            card.classList.remove('border-success-active');
            card.style.borderLeft = "4px solid #001f3f";
        });
        document.querySelectorAll('[id^="badge-cart-"]').forEach(badge => {
            badge.classList.add('d-none');
        });

        if (cartKeys.length === 0) {
            emptyMsg.style.setProperty('display', 'block', 'important');
            document.getElementById('grandTotalLabel').innerText = 'Rp 0';
            container.querySelectorAll('.cart-row-item').forEach(el => el.remove());
            return;
        } else {
            emptyMsg.style.setProperty('display', 'none', 'important');
        }

        container.querySelectorAll('.cart-row-item').forEach(el => el.remove());

        cartKeys.forEach(id => {
            const item = cart[id];
            total += item.price * item.qty;
            
            const targetCard = document.getElementById(`product-card-${id}`);
            const targetBadge = document.getElementById(`badge-cart-${id}`);
            if (targetCard && targetBadge) {
                targetCard.classList.add('border-success-active');
                targetCard.style.borderLeft = "4px solid #28a745";
                targetBadge.classList.remove('d-none');
                targetBadge.querySelector('.qty-count').innerText = item.qty;
            }

            html += `
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded border cart-row-item shadow-xs animate-fade-in">
                    <div class="text-truncate me-2" style="max-width: 180px;">
                        <span class="fw-bold text-dark small d-block text-truncate mb-0">${item.name}</span>
                        <small class="text-primary fw-semibold">Rp ${item.price.toLocaleString('id-ID')}</small>
                    </div>
                    <div class="d-flex align-items-center flex-shrink-0 gap-1">
                        <input type="hidden" name="items[${index}][id]" value="${item.id}">
                        <input type="hidden" name="items[${index}][qty]" value="${item.qty}">
                        
                        <div class="input-group input-group-sm rounded border bg-white" style="width: 110px;">
                            <button type="button" class="btn btn-link text-dark px-2 py-0 border-0 shadow-none text-decoration-none" onclick="decrementQty('${id}')">
                                <i class="fas fa-minus small"></i>
                            </button>
                            <input type="text" class="form-control text-center border-0 p-0 bg-white fw-bold text-dark shadow-none" 
                                   style="font-size: 12px; pointer-events: none;" value="${item.qty}">
                            <button type="button" class="btn btn-link text-dark px-2 py-0 border-0 shadow-none text-decoration-none" onclick="incrementQty('${id}')">
                                <i class="fas fa-plus small"></i>
                            </button>
                        </div>

                        <button type="button" class="btn btn-sm text-danger ms-1 shadow-none" onclick="removeItem('${id}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            index++;
        });

        emptyMsg.insertAdjacentHTML('afterend', html);
        document.getElementById('grandTotalLabel').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    function openPreviewModal() {
        const customerName = document.getElementById('customerNameInput').value.trim();
        const cartKeys = Object.keys(cart);

        if (customerName === "") {
            alert("Please fill in the Customer Name first!");
            document.getElementById('customerNameInput').focus();
            return;
        }

        if (cartKeys.length === 0) {
            alert("Your shopping cart is empty! Choose at least 1 product.");
            return;
        }

        document.getElementById('previewCustomerName').innerText = customerName;
        
        let previewHtml = '';
        let totalSum = 0;
        
        cartKeys.forEach(id => {
            const item = cart[id];
            const subtotal = item.price * item.qty;
            totalSum += subtotal;
            
            previewHtml += `
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-light small">
                    <div class="text-truncate" style="max-width: 240px;">
                        <span class="fw-bold text-dark d-block text-truncate">${item.name}</span>
                        <small class="text-muted">Rp ${item.price.toLocaleString('id-ID')} × ${item.qty}</small>
                    </div>
                    <span class="fw-bold text-dark-subtle">Rp ${subtotal.toLocaleString('id-ID')}</span>
                </div>
            `;
        });

        document.getElementById('previewItemsList').innerHTML = previewHtml;
        document.getElementById('previewGrandTotal').innerText = 'Rp ' + totalSum.toLocaleString('id-ID');

        const modalElement = new bootstrap.Modal(document.getElementById('previewOrderModal'));
        modalElement.show();
    }

    document.getElementById('posOrderForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitConfirmButton');
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Processing...`;
    });
</script>

<style>
    .product-card {
        transition: all 0.2s ease-in-out;
    }
    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.08)!important;
    }
    .border-success-active {
        background-color: #f8fff9 !important;
        box-shadow: 0 .125rem .25rem rgba(40, 167, 69, 0.08) !important;
    }
    .shadow-xs {
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
</style>
@endsection