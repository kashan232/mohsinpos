<!-- meta tags and other links -->
@include('admin_panel.include.header_include')

<body>
    <!-- page-wrapper start -->
    <div class="page-wrapper default-version">
        @include('admin_panel.include.sidebar_include')
        <!-- sidebar end -->

        <!-- navbar-wrapper start -->
        @include('admin_panel.include.navbar_include')
        <!-- navbar-wrapper end -->

        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                <div class="d-flex mb-30 flex-wrap gap-3 justify-content-between align-items-center">
                    <h6 class="page-title">All Products</h6>
                    <div class="d-flex flex-wrap justify-content-end gap-2 align-items-center breadcrumb-plugins">
                        <form action="" method="GET" class="d-flex gap-2">
                            <div class="input-group w-auto">
                                <input type="search" name="search" class="form-control bg--white"
                                    placeholder="Name or SKU" value="">
                                <button class="btn btn--primary" type="submit"><i class="la la-search"></i></button>
                            </div>
                        </form>
                        <a href="{{ route('add-product') }}"
                            class="btn btn-outline--primary">
                            <i class="la la-plus"></i>Add New </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            <strong>Success!</strong> {{ session('success') }}.
                        </div>
                        @endif
                        <div class="card b-radius--10">
                            <div class="card-body p-0">
                                <div class="table-responsive--md table-responsive">
                                    <table class="table--light style--two table">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Name | SKU </th>
                                                <th>Category | Brand</th>
                                                <th>Stock </th>
                                                <th>Total Sale | Alert Qty</th>
                                                <th>Unit</th>
                                                <th>Wholesale Price</th>
                                                <th>Retail Price</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($all_product as $product)
                                            <tr>
                                                <td>
                                                    <img
                                                        src="{{ asset('product_images/' . $product->image) }}"
                                                        alt="Product Image" style="max-width: 100px;">
                                                </td>
                                                <td class="long-text">
                                                    <span class="fw-bold text--primary">{{ $product->product_name }}</span>
                                                    <br>
                                                    <span class="text--small ">{{ $product->sku }}</span>
                                                </td>
                                                <td>
                                                    {{ $product->category }}
                                                    <br>
                                                    <span class="text--primary">{{ $product->brand }}</span>
                                                </td>
                                                <td>
                                                    {{ $product->stock }}
                                                </td>
                                                <td>
                                                    0
                                                    <br>
                                                    <span class="badge badge--warning">{{ $product->alert_quantity }}</span>
                                                </td>
                                                <td>{{ $product->unit }}</td>
                                                <td>{{ $product->wholesale_price }}</td>
                                                <td>{{ $product->retail_price }}</td>
                                                <td>


                                                    <div class="button--group">
                                                        <a href="{{ route('edit-product',['id' => $product->id ]) }}"
                                                            class="btn btn-sm btn-outline--primary ms-1 editBtn"><i
                                                                class="las la-pen"></i> Edit</a>

                                                        <form action="{{ route('product.destroy', $product->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this product?')">
                                                                <i class="la la-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>



                                                </td>
                                                @endforeach
                                        </tbody>
                                    </table>
                                    <!-- table end -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin_panel.include.footer_include')