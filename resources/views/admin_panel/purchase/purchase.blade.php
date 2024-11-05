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
                    <h6 class="page-title">All Purchases</h6>
                    <div class="d-flex flex-wrap justify-content-end gap-2 align-items-center breadcrumb-plugins">
                        <form action="" method="GET" class="d-flex gap-2">
                            <div class="input-group w-auto">
                                <input type="search" name="search" class="form-control bg--white"
                                    placeholder="Search..." value="">
                                <button class="btn btn--primary" type="submit"><i class="la la-search"></i></button>
                            </div>

                            <div class="input-group w-auto">
                                <input name="date" type="search" data-range="true" data-multiple-dates-separator=" - "
                                    data-language="en" data-format="Y-m-d"
                                    class="datepicker-here form-control bg--white pe-2" data-position='bottom right'
                                    placeholder="Start Date - End Date" autocomplete="off" value="">
                                <button class="btn btn--primary input-group-text"><i class="la la-search"></i></button>
                            </div>

                        </form>
                        <a href="{{ route('add-purchase') }}"
                            class="btn btn-outline--primary h-45">
                            <i class="la la-plus"></i>Add New </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card b-radius--10 bg--transparent">
                            <div class="card-body p-0 ">
                                <div class="table-responsive--md table-responsive">
                                    <table class="table table--light style--two bg--white">
                                        <thead>
                                            <tr>
                                                <th>Invoice No. | Date</th>
                                                <th>Supplier </th>
                                                <th>Total Amount | Warehouse</th>
                                                <th>Discount | Payable </th>
                                                <th>Paid | Due</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($Purchases as $purchase)
                                            <tr>
                                                <td>
                                                    @if($purchase->is_return == 1)
                                                    <small><i class="fa fa-circle text--danger" aria-hidden="true" aria-label="Returned" data-bs-original-title="Returned"></i></small>
                                                    @endif
                                                    <span class="fw-bold">
                                                        {{ $purchase->invoice_no }}
                                                    </span>
                                                    <br>
                                                    <small>{{ $purchase->purchase_date }}</small>
                                                </td>

                                                <td>
                                                    <span class="text--primary fw-bold"> {{ $purchase->supplier}} </span>
                                                </td>

                                                <td>
                                                    <span class="fw-bold">{{ $purchase->total_price }} </span>
                                                    <br>
                                                    {{ $purchase->warehouse_id }}
                                                </td>
                                                <td>
                                                    {{ $purchase->discount }}
                                                    <br>
                                                    <span class="fw-bold">{{ $purchase->Payable_amount }}</span>
                                                </td>
                                                <td>
                                                    {{ $purchase->paid_amount }}
                                                    <br>
                                                    <span class="fw-bold" title="Payable to Supplier">
                                                        {{ $purchase->due_amount }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($purchase->status == 'Paid')
                                                    <span class="badge badge--success bg-success text-white">Paid</span>
                                                    @else
                                                    <span class="badge badge--danger bg-danger text-white">Due</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline--info ms-1 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="la la-ellipsis-v"></i>More
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item btn btn-sm btn-outline--primary ms-1 editBtn" href="#"> <i class="la la-pen"></i> Edit</a>
                                                            <a href="#" class="dropdown-item btn btn-sm btn-outline--primary ms-1 paymentBtn"
                                                                data-id="{{ $purchase->id }}"
                                                                data-invoice_no="{{ $purchase->invoice_no }}"
                                                                data-supplier="{{ $purchase->supplier }}"
                                                                data-payable_amount="{{ $purchase->Payable_amount }}">
                                                                <i class="la la-money-bill-wave"></i>
                                                                Pay
                                                            </a>
                                                            <a class="dropdown-item btn btn-sm btn-outline--primary ms-1 editBtn" href="{{ route('purchase-view',['id' => $purchase->id ]) }}
                                                            "> <i class="la la-eye"></i> View</a>

                                                            <a class="dropdown-item btn btn-sm btn-outline--primary ms-1 editBtn" href="{{ route('purchase-return',['id' => $purchase->id ]) }}
                                                            "> <i class="la la-undo"></i> View Return Details</a>
                                                            
                                                            <a class="dropdown-item btn btn-sm btn-outline--primary ms-1 editBtn" href="{{ route('purchase-return-damage-item',['id' => $purchase->id ]) }}
                                                            "> <i class="la la-undo"></i> Damage Item</a>
                                                        </div>
                                                    </div>

                                                    <form action="{{ route('purchase.destroy', $purchase->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this purchase?')">
                                                                <i class="la la-trash"></i> Delete
                                                            </button>
                                                        </form>

                                                </td>

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table><!-- table end -->
                                </div>
                            </div>
                        </div><!-- card end -->
                    </div>
                </div>

                <!-- Payment Modal -->
                <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="paymentModalLabel">Make Payment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('purchases-payment') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="purchase_id" class="form-label">Purchase ID</label>
                                        <input type="text" class="form-control" id="purchase_id" name="purchase_id" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="invoice_no" class="form-label">Invoice No</label>
                                        <input type="text" class="form-control" id="invoice_no" name="invoice_no" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="supplier" class="form-label">Supplier</label>
                                        <input type="text" class="form-control" id="supplier" name="supplier" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="payable_amount" class="form-label">Payable Amount</label>
                                        <input type="text" class="form-control" id="payable_amount" name="payable_amount" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="paid_amount" class="form-label">Paying Amount</label>
                                        <input type="text" class="form-control" id="paid_amount" name="paid_amount" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Make Payment</button>
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
        $(document).ready(function() {
            $('.paymentBtn').on('click', function() {
                var purchaseId = $(this).data('id');
                var invoiceNo = $(this).data('invoice_no');
                var supplier = $(this).data('supplier');
                var payableAmount = $(this).data('payable_amount');

                $('#purchase_id').val(purchaseId);
                $('#invoice_no').val(invoiceNo);
                $('#supplier').val(supplier);
                $('#payable_amount').val(payableAmount);

                $('#paymentModal').modal('show');
            });
        });
    </script>