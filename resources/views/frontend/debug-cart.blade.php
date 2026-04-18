@extends('frontend.layouts.app')

@section('title', 'Test Cart - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Debug Cart</h5>
                </div>
                <div class="card-body">
                    <div id="debugOutput" class="mb-3 p-3 bg-light rounded" style="height: 300px; overflow-y: auto; font-family: monospace; white-space: pre-wrap; word-wrap: break-word; font-size: 12px;">
                        Debug output will appear here...
                    </div>

                    <div class="mb-3">
                        <label>Product ID:</label>
                        <input type="number" id="productId" class="form-control" value="1" min="1">
                    </div>

                    <div class="mb-3">
                        <label>Quantity:</label>
                        <input type="number" id="quantity" class="form-control" value="1" min="1">
                    </div>

                    <button class="btn btn-primary w-100" onclick="testAddToCart()">Test Add to Cart</button>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Cart Info</h5>
                </div>
                <div class="card-body">
                    <button class="btn btn-info w-100 mb-2" onclick="getCartInfo()">Get Cart Info</button>
                    <div id="cartInfo" class="p-2 bg-light rounded" style="max-height: 200px; overflow-y: auto; font-size: 12px;">
                        Cart info will appear here...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function logDebug(message) {
    const output = document.getElementById('debugOutput');
    const timestamp = new Date().toLocaleTimeString();
    output.innerHTML += `[${timestamp}] ${message}\n`;
    output.scrollTop = output.scrollHeight;
}

function testAddToCart() {
    const productId = parseInt(document.getElementById('productId').value);
    const quantity = parseInt(document.getElementById('quantity').value);
    
    logDebug(`\n>>> Testing add to cart`);
    logDebug(`Product ID: ${productId}`);
    logDebug(`Quantity: ${quantity}`);
    logDebug(`CSRF Token: ${document.querySelector('meta[name="csrf-token"]')?.content || 'NOT FOUND'}`);
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        logDebug('❌ CSRF Token not found!');
        return;
    }
    
    logDebug(`Sending fetch request...`);

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.content
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => {
        logDebug(`Response status: ${response.status} ${response.statusText}`);
        return response.json().then(data => ({ status: response.status, data }));
    })
    .then(({status, data}) => {
        logDebug(`Response data: ${JSON.stringify(data, null, 2)}`);
        if (data.success) {
            logDebug(`✓ Success! Cart count: ${data.cart_count}`);
        } else {
            logDebug(`✗ Error: ${data.message}`);
        }
    })
    .catch(error => {
        logDebug(`✗ Fetch error: ${error.message}`);
        logDebug(`Stack: ${error.stack}`);
    });
}

function getCartInfo() {
    logDebug(`\n>>> Getting cart info...`);
    
    fetch('/cart/get', {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        logDebug(`Cart data: ${JSON.stringify(data, null, 2)}`);
        document.getElementById('cartInfo').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
    })
    .catch(error => {
        logDebug(`✗ Error: ${error.message}`);
    });
}

// Initialize
logDebug('Page loaded');
logDebug(`Auth status: @auth authenticated @else not authenticated @endauth`);
logDebug(`CSRF Token meta tag found: ${!!document.querySelector('meta[name="csrf-token"]')}`);
</script>
@endsection
