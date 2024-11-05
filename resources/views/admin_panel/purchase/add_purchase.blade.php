@include('admin_panel.include.header_include')

<body>
    <!-- page-wrapper start -->
    <div class="page-wrapper default-version">

        <!-- sidebar start -->
        @include('admin_panel.include.sidebar_include')
        <!-- sidebar end -->

        <!-- navbar-wrapper start -->
        @include('admin_panel.include.navbar_include')
        <!-- navbar-wrapper end -->

        <div class="body-wrapper">
            <div class="bodywrapper__inner">

                <div class="d-flex mb-30 flex-wrap gap-3 justify-content-between align-items-center">
                    <h6 class="page-title">Add Purchase</h6>
                </div>

                <div class="row gy-3">
                    <div class="col-lg-12 col-md-12 mb-30">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('store-Purchase') }}" method="POST">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-xl-4 col-sm-4">
                                            <div class="form-group" id="supplier-wrapper">
                                                <label class="form-label">Supplier</label>
                                                <select name="supplier" class="select2-basic form-control" required>
                                                    <option selected disabled>Select One</option>
                                                    @foreach($Suppliers as $Supplier)
                                                    <option value="{{ $Supplier->name }}">{{ $Supplier->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-sm-4">
                                            <div class="form-group">
                                                <label>Date</label>
                                                <input name="purchase_date" type="date" class="datepicker-here form-control bg--white" required>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-sm-4">
                                            <div class="form-group">
                                                <label class="form-label">Warehouse</label>
                                                <select name="warehouse_id" class="form-control" required>
                                                    <option selected disabled>Select One</option>
                                                    @foreach($Warehouses as $Warehouse)
                                                    <option value="{{ $Warehouse->name }}">{{ $Warehouse->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="table-responsive">
                                            <table class="productTable table border">
                                                <thead class="border bg--dark">
                                                    <tr>
                                                        <th>Category</th>
                                                        <th>Name</th>
                                                        <th>Unit</th>
                                                        <th>Quantity<span class="text--danger">*</span></th>
                                                        <th>Price<span class="text--danger">*</span></th>
                                                        <th>Total</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="purchaseItems">
                                                    <tr>
                                                        <td>
                                                            <select name="item_category[]" class="form-control item-category" required>
                                                                <option value="" disabled selected>Select Category</option>
                                                                @foreach($Category as $Categories)
                                                                <option value="{{ $Categories->category }}">{{ $Categories->category }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="item_name[]" class="form-control item-name" required>
                                                                <option value="" disabled selected>Select Item</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="unit[]" class="form-control unit" readonly> <!-- Readonly Unit column -->
                                                        </td>
                                                        <td>
                                                            <input type="number" name="quantity[]" class="form-control quantity" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="price[]" class="form-control price" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="total[]" class="form-control total" readonly>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger remove-row">Delete</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <button type="button" class="btn btn-primary mt-4 mb-4" id="addRow">Add More</button>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8 col-sm-6">
                                            <div class="form-group">
                                                <label>Note</label>
                                                <textarea name="note" class="form-control"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>Total Price</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">$</span>
                                                            <input type="number" name="total_price" class="form-control total_price" required readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>Discount</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">$</span>
                                                            <input type="number" name="discount" class="form-control" step="any">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>Payable Amount</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">$</span>
                                                            <input type="number" name="payable_amount" class="form-control payable_amount" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn--primary w-100 h-45">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- bodywrapper__inner end -->
        </div><!-- body-wrapper end -->

    </div>
    @include('admin_panel.include.footer_include')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const purchaseItems = document.getElementById('purchaseItems');

            // Event listener for category selection
            purchaseItems.addEventListener('change', function(e) {
                if (e.target.classList.contains('item-category')) {
                    const categoryId = e.target.value;
                    const row = e.target.closest('tr');
                    const itemSelect = row.querySelector('.item-name');

                    if (categoryId) {
                        fetch(`/get-items-by-category/${categoryId}`)
                            .then(response => response.json())
                            .then(items => {
                                itemSelect.innerHTML = '<option value="" disabled selected>Select Item</option>';
                                items.forEach(item => {
                                    const option = document.createElement('option');
                                    option.value = item.product_name;
                                    option.textContent = item.product_name;
                                    itemSelect.appendChild(option);
                                });
                            })
                            .catch(error => console.error('Error fetching items:', error));
                    }
                }

                // Event listener for product selection to populate Unit
                if (e.target.classList.contains('item-name')) {
                    const productId = e.target.value;
                    const row = e.target.closest('tr');
                    const unitInput = row.querySelector('.unit');

                    if (productId) {
                        fetch(`/get-unit-by-product/${productId}`)
                            .then(response => response.json())
                            .then(product => {
                                unitInput.value = product.unit; // Assuming the API response includes 'unit'
                            })
                            .catch(error => console.error('Error fetching unit:', error));
                    }
                }
            });

            // Adding a new row
            const addRowButton = document.getElementById('addRow');
            addRowButton.addEventListener('click', function() {
                const newRow = `
                <tr>
                    <td>
                        <select name="item_category[]" class="form-control item-category" required>
                            <option value="" disabled selected>Select Category</option>
                            @foreach($Category as $Categories)
                                <option value="{{ $Categories->category }}">{{ $Categories->category }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="item_name[]" class="form-control item-name" required>
                            <option value="" disabled selected>Select Item</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="unit[]" class="form-control unit" readonly>
                    </td>
                    <td>
                        <input type="number" name="quantity[]" class="form-control quantity" required>
                    </td>
                    <td>
                        <input type="number" name="price[]" class="form-control price" required>
                    </td>
                    <td>
                        <input type="number" name="total[]" class="form-control total" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger remove-row">Delete</button>
                    </td>
                </tr>`;
                purchaseItems.insertAdjacentHTML('beforeend', newRow);
            });

            // Remove row
            purchaseItems.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row')) {
                    e.target.closest('tr').remove();
                }
            });
            // Calculate total and payable amount
            purchaseItems.addEventListener('input', function(e) {
                calculateTotalAndPayable();
            });

            // Event listener for the discount input
            document.querySelector('input[name="discount"]').addEventListener('input', calculateTotalAndPayable);

            // Function to calculate total price and payable amount
            function calculateTotalAndPayable() {
                const rows = purchaseItems.querySelectorAll('tr');
                let totalPrice = 0;

                rows.forEach(row => {
                    const quantityInput = row.querySelector('.quantity');
                    const priceInput = row.querySelector('.price');
                    const totalInput = row.querySelector('.total');

                    if (quantityInput.value && priceInput.value) {
                        const total = quantityInput.value * priceInput.value;
                        totalInput.value = total;
                        totalPrice += total;
                    } else {
                        totalInput.value = 0;
                    }
                });

                // Update total price
                document.querySelector('.total_price').value = totalPrice;

                // Calculate and update payable amount
                const discount = parseFloat(document.querySelector('input[name="discount"]').value) || 0;
                document.querySelector('.payable_amount').value = totalPrice - discount;
            }
        });
    </script>

</body>