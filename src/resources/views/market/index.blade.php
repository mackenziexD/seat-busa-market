@extends('web::layouts.grids.12', ['viewname' => 'seat-busa-market::index'])

@section('page_header', 'WELCUM TO BUSA-MART')

@section('full')

    <div class="card">
        <div class="card-body">
            <p>
                <div class="alert alert-info text-center" role="alert">
                    Prices are an ESTIMATE and may not be accurate, contracts will be issued with items at the correct price.
                </div>
            </p>
            
            <div class="row">
                <div class="col-md-6">
                        <form action="" method="post" class="mt-4">
                            <div class="col-lg-12">
                                @foreach($corpAssets as $asset)
                                    <div class="row mb-4">
                                        <div class="col-xs-1 mt-4">
                                            <img class="img-responsive" src="https://image.eveonline.com/Type/{{$asset->type_id}}_32.png">
                                        </div>
                                        <div class="col-lg-4 text-center mt-2">
                                            <p>{{ $asset->type->typeName }}<br>
                                        <small>est. {{number_format($asset->type->price->average_price)}} ISK<br>
                                        {{$asset->container->structure->name}}</small></p>
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="stock">Current Stock</label>
                                            <input type="text" class="form-control input-sm" name="stock" id="stock" value="{{$asset->quantity}}" disabled>
                                        </div>
                                        <div class="col-lg-2 mt-4">
                                        <button class="btn btn-primary mt-1 addToCart" type="button" data-type="{{ $asset->type->typeName }}" data-max-stock="{{$asset->quantity}}" data-price="{{ $asset->type->price->average_price }}">Add To Order</button>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </form>
                </div>

                <div class="col-md-6">
                    <div class="card mt-4" id="shoppingCart">
                        <div class="card-header bg-primary">
                            <h4>Shopping Cart</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-group" id="cart">
                                <!-- Cart items will be added here -->
                            </ul>
                            <p id="cart-empty" class="text-center">Nothing in the cart</p>
                        </div>
                        <div class="card-footer">
                            <form action="" method="post">
                                <p class="totalISK">Grand Total: <span id="grandTotal">0 ISK</span></p>
                                <button class="btn btn-success btn-block" id="submitOrder" disabled>Submit Order</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop

@push('javascript')

<script>
const submitButton = document.getElementById('submitOrder');
submitButton.addEventListener('click', function(event) {
    event.preventDefault();
    console.log('submitting order');

    // get the cart items and the quantity being ordered
    const cartItems = document.querySelectorAll('#cart li');
    const orderItems = [];
    cartItems.forEach((item) => {
        const typeName = item.querySelector('div > div:nth-child(1)').textContent.split(' - ')[0].replace(/\n/g, '').trim();
        const quantity = parseInt(item.querySelector('div > div:nth-child(2) > input').value);
        orderItems.push({ typeName, quantity });
    });

    console.log(orderItems);

    // generating janice apprasial link
    const url = 'https://janice.e-351.com/api/rest/v2/appraisal?market=2&compactize=true&pricePercentage=1';
    let responseData; // Declare a variable to store the response data

    fetch(url, {
        method: 'POST',
        headers: {
            'accept': 'application/json',
            'Content-Type': 'text/plain'
            'X-ApiKey': 'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6' // place holder not an actual key, idiots...
        },
        body: data
        })
        .then(response => response.json())
        .then(data => {
            // Assign the response data to the variable
            responseData = data;
        })
        .catch(error => {
            console.error('Error:', error);
        });


    

});
</script>

<script>
    // Shopping cart items will be stored in this array
    const cartItems = [];

    // Function to add an item to the cart
    function addToCart(item) {
        const existingItemIndex = cartItems.findIndex(cartItem => cartItem.typeName === item.typeName);
        
        if (existingItemIndex !== -1) {
            // If the item already exists in the cart, update the quantity
            const maxStock = cartItems[existingItemIndex].maxStock;
            const newQuantity = cartItems[existingItemIndex].quantity + item.quantity;

            if (newQuantity <= maxStock) {
                cartItems[existingItemIndex].quantity = newQuantity;
            } else {
                alert(`Quantity exceeds current stock (${maxStock})`);
            }
        } else {
            cartItems.push(item);
        }

        updateCart();
    }

    // Function to remove an item from the cart
    function removeFromCart(index) {
        cartItems.splice(index, 1);
        updateCart();
    }

    // Function to update the cart UI
    function updateCart() {
        const cartList = document.getElementById('cart');
        const cartEmpty = document.getElementById('cart-empty');
        const grandTotalElement = document.getElementById('grandTotal');
        cartList.innerHTML = ''; // Clear the cart list

        let grandTotal = 0;

        if (cartItems.length === 0) {
            cartEmpty.style.display = 'block';
        } else {
            cartEmpty.style.display = 'none';
            cartItems.forEach((item, index) => {
                const listItem = document.createElement('li');
                listItem.classList.add('list-group-item');
                const itemTotal = item.quantity * item.price;
                grandTotal += itemTotal;
                listItem.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            ${item.typeName} - Price: ${item.price.toLocaleString()} ISK
                        </div>
                        <div class="col-md-4">
                            <input type="number" class="form-control" value="${item.quantity}" min="1" max="${item.maxStock}" 
                                onchange="updateQuantity(${index}, this.value)">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-danger btn-sm" onclick="removeFromCart(${index})">Remove</button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            Total: <span class="itemTotal">${itemTotal.toLocaleString()} ISK</span>
                        </div>
                    </div>`;
                cartList.appendChild(listItem);
            });

            // remove disabled from submitOrder button
            document.getElementById('submitOrder').disabled = false;
        }

        // Update the grand total
        grandTotalElement.textContent = `${grandTotal.toLocaleString()} ISK`;
    }

    // Function to update the quantity in the cart
    function updateQuantity(index, newQuantity) {
        cartItems[index].quantity = parseInt(newQuantity);
        updateCart();
    }

    // Attach click event listeners to "Add To Order" buttons
    const addToCartButtons = document.querySelectorAll('.addToCart');
    addToCartButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const typeName = button.getAttribute('data-type');
            const price = parseFloat(button.getAttribute('data-price')); // Parse the price as a float
            const maxStock = parseInt(button.getAttribute('data-max-stock'));
            addToCart({ typeName, price, quantity: 1, maxStock }); // Set initial quantity to 1 and max stock level
        });
    });
</script>
@endpush


