<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\CustomerCredit;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // If using barryvdh/laravel-dompdf

class SaleController extends Controller
{

    public function Sale()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            // dd($userId);
            $Purchases = Purchase::get();
            // dd($Purchases);
            return view('admin_panel.sale.sales', [
                'Purchases' => $Purchases,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function add_Sale()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            // dd($userId);
            $Customers = Customer::get();
            $Warehouses = Warehouse::get();
            $Category = Category::get();

            // dd($Customers);
            return view('admin_panel.sale.add_sale', [
                'Customers' => $Customers,
                'Warehouses' => $Warehouses,
                'Category' => $Category,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function getItemsByCategory($categoryId)
    {
        $items = Product::where('category', $categoryId)->get(); // Adjust according to your database structure
        return response()->json($items);
    }


    public function view($id)
    {
        // Fetch the purchase details
        $purchase = Purchase::findOrFail($id);

        // Decode the JSON fields if necessary
        $purchase->item_category = json_decode($purchase->item_category);
        $purchase->item_name = json_decode($purchase->item_name);
        $purchase->quantity = json_decode($purchase->quantity);
        $purchase->price = json_decode($purchase->price);
        $purchase->total = json_decode($purchase->total);

        return view('admin_panel.purchase.view', [
            'purchase' => $purchase,
        ]);
    }

    // public function store_Sale(Request $request)
    // {
    //     // Generate a unique invoice number
    //     $invoiceNo = Sale::generateInvoiceNo();

    //     // Log the request data
    //     \Log::info('Request Data:', $request->all());

    //     // Get customer info from the concatenated string
    //     $customerInfo = explode('|', $request->input('customer_info'));
    //     if (count($customerInfo) < 2) {
    //         return redirect()->back()->with('error', 'Invalid customer information format.');
    //     }

    //     $customerId = $customerInfo[0]; // Customer ID
    //     $customerName = $customerInfo[1]; // Customer Name

    //     // Prepare data for storage
    //     $discount = (float) ($request->input('discount', 0));
    //     $totalPrice = (float) $request->input('total_price', 0);

    //     // Calculate the net total amount
    //     $netTotal = $totalPrice - $discount;

    //     // Get the existing customer credit to retrieve previous balance
    //     $customerCredit = CustomerCredit::where('customerId', $customerId)->first();

    //     // Calculate the previous balance, or set to 0 if there is no existing credit
    //     if ($customerCredit) {
    //         // If customer credit exists, get the previous balance
    //         $previousBalance = $customerCredit->previous_balance;

    //         // Update previous balance to include the new sale's payable amount
    //         $previousBalance += $netTotal;

    //         // Update existing credit
    //         $customerCredit->net_total = $netTotal; // Store the net total from the current sale
    //         $customerCredit->closing_balance = $previousBalance; // Closing balance is now the updated previous balance
    //         $customerCredit->previous_balance = $previousBalance; // Update to the new previous balance
    //         $customerCredit->save();
    //     } else {
    //         // Create new credit entry for the customer
    //         CustomerCredit::create([
    //             'customerId' => $customerId,
    //             'customer_name' => $customerName,
    //             'previous_balance' => $netTotal, // Set previous balance to the net total for the first sale
    //             'net_total' => $netTotal, // This is the first sale's amount
    //             'closing_balance' => $netTotal, // Closing balance for first entry
    //         ]);
    //     }

    //     // Prepare sale data
    //     $saleData = [
    //         'invoice_no' => $invoiceNo,
    //         'customerId' => $customerId,
    //         'customer' => $customerName,
    //         'sale_date' => $request->input('sale_date', ''),
    //         'warehouse_id' => $request->input('warehouse_id', ''),
    //         'item_category' => json_encode($request->input('item_category', [])),
    //         'item_name' => json_encode($request->input('item_name', [])),
    //         'quantity' => json_encode($request->input('quantity', [])),
    //         'price' => json_encode($request->input('price', [])),
    //         'total' => json_encode($request->input('total', [])),
    //         'note' => $request->input('note', ''),
    //         'total_price' => $totalPrice,
    //         'discount' => $discount,
    //         'Payable_amount' => $netTotal,
    //     ];

    //     // Save sale data
    //     $sale = Sale::create($saleData);

    //     // Update Product Stock
    //     foreach ($request->input('item_name', []) as $key => $item_name) {
    //         $item_category = $request->input('item_category', [])[$key] ?? '';
    //         $quantity = $request->input('quantity', [])[$key] ?? 0;

    //         $product = Product::where('product_name', $item_name)
    //             ->where('category', $item_category)
    //             ->first();

    //         if ($product) {
    //             $product->stock -= $quantity; // Decrease stock for sales
    //             $product->save();
    //         } else {
    //             return redirect()->back()->with('error', "Product $item_name in category $item_category not found.");
    //         }
    //     }

    //     // Redirect to receipt page for printing
    //     return redirect()->route('sale-receipt', ['id' => $sale->id])
    //         ->with('success', 'Sale recorded successfully. Redirecting to receipt...');

    // }


    public function store_Sale(Request $request)
    {
        // Generate a unique invoice number
        $invoiceNo = Sale::generateInvoiceNo();

        // Log the request data
        \Log::info('Request Data:', $request->all());

        // Get customer info from the concatenated string
        $customerInfo = explode('|', $request->input('customer_info'));
        if (count($customerInfo) < 2) {
            return redirect()->back()->with('error', 'Invalid customer information format.');
        }

        $customerId = $customerInfo[0]; // Customer ID
        $customerName = $customerInfo[1]; // Customer Name

        // Prepare data for storage
        $discount = (float) ($request->input('discount', 0));
        $totalPrice = (float) $request->input('total_price', 0);
        $netTotal = $totalPrice - $discount; // Calculate the net total amount

        // Get the existing customer credit to retrieve previous balance
        $customerCredit = CustomerCredit::where('customerId', $customerId)->first();

        $previous_balance = $request->input('previous_balance');
        $net_total = $request->input('net_total');
        $closing_balance = $request->input('closing_balance');
        // Initialize variables to hold balance details
        $previousBalance = 0;
        $closingBalance = 0;

        if ($customerCredit) {
            // If customer credit exists, get the previous balance
            $previousBalance = $customerCredit->previous_balance;

            // Update previous balance to include the new sale's payable amount
            $closingBalance = $previousBalance + $netTotal;

            // Update existing credit
            $customerCredit->net_total = $netTotal; // Store the net total from the current sale
            $customerCredit->closing_balance = $closingBalance; // Closing balance is now the updated previous balance
            $customerCredit->previous_balance = $closingBalance; // Update to the new previous balance
            $customerCredit->save();
        } else {
            // Create new credit entry for the customer
            CustomerCredit::create([
                'customerId' => $customerId,
                'customer_name' => $customerName,
                'previous_balance' => $netTotal, // Set previous balance to the net total for the first sale
                'net_total' => $netTotal, // This is the first sale's amount
                'closing_balance' => $netTotal, // Closing balance for first entry
            ]);

            // Set the balances for the first entry
            $previousBalance = 0; // No previous balance exists for new customers
            $closingBalance = $netTotal; // This will be the closing balance
        }

        // Prepare sale data
        $saleData = [
            'invoice_no' => $invoiceNo,
            'customerId' => $customerId,
            'customer' => $customerName,
            'sale_date' => $request->input('sale_date', ''),
            'warehouse_id' => $request->input('warehouse_id', ''),
            'item_category' => json_encode($request->input('item_category', [])),
            'item_name' => json_encode($request->input('item_name', [])),
            'quantity' => json_encode($request->input('quantity', [])),
            'price' => json_encode($request->input('price', [])),
            'total' => json_encode($request->input('total', [])),
            'note' => $request->input('note', ''),
            'total_price' => $totalPrice,
            'discount' => $discount,
            'Payable_amount' => $netTotal,
        ];

        // Save sale data
        $sale = Sale::create($saleData);

        // Update Product Stock
        foreach ($request->input('item_name', []) as $key => $item_name) {
            $item_category = $request->input('item_category', [])[$key] ?? '';
            $quantity = $request->input('quantity', [])[$key] ?? 0;

            $product = Product::where('product_name', $item_name)
                ->where('category', $item_category)
                ->first();

            if ($product) {
                $product->stock -= $quantity; // Decrease stock for sales
                $product->save();
            } else {
                return redirect()->back()->with('error', "Product $item_name in category $item_category not found.");
            }
        }

        // Redirect to receipt page for printing with necessary data
        return redirect()->route('sale-receipt', [
            'id' => $sale->id,
            'previous_balance' => $previousBalance, // Ensure this is the correct variable name
            'closing_balance' => $closingBalance, // Ensure this is the correct variable name
            'net_total' => $netTotal // Include this if needed
        ])
            ->with('success', 'Sale recorded successfully. Redirecting to receipt...');
    }




    public function all_sales()
    {
        if (Auth::id()) {
            $userId = Auth::id();

            // Retrieve all Sales with their related Purchase data (including invoice_no)
            $Sales = Sale::get();
            // dd($Sales);
            return view('admin_panel.sale.sales', [
                'Sales' => $Sales,
            ]);
        } else {
            return redirect()->back();
        }
    }


    public function get_customer_amount($id)
    {
        // Fetch the customer by ID (adjust the model and field names as necessary)
        $customer = CustomerCredit::find($id);

        if (!$customer) {
            return response()->json(['previous_balance' => 0]); // Or handle this case appropriately
        }

        // Return the previous amount as JSON
        return response()->json([
            'previous_balance' => $customer->previous_balance // Ensure this field exists in your model
        ]);
    }

    public function downloadInvoice($id)
    {
        // Fetch the sale data
        $sale = Sale::findOrFail($id);

        // Fetch the customer information based on the customer name in the sale
        $customer = Customer::where('customer_name', $sale->customer)->first();

        // If customer is not found, handle the case (optional)
        if (!$customer) {
            abort(404, 'Customer not found for this sale.');
        }

        // Load the view and pass both sale and customer data
        $pdf = Pdf::loadView('admin_panel.invoices.invoice', ['sale' => $sale, 'customer' => $customer]);

        // Download the PDF file
        return $pdf->download('invoice-' . $sale->invoice_no . '.pdf');
    }

    public function showReceipt(Request $request, $id)
    {
        // Fetch the sale data using the sale ID
        $sale = Sale::findOrFail($id);

        // Get customer credit details
        $customerCredit = CustomerCredit::where('customerId', $sale->customerId)->latest()->first();

        // Initialize variables for previous and closing balance
        $previous_balance = $customerCredit->previous_balance; // Get previous balance from customerCredit
        $closing_balance = $customerCredit->closing_balance;   // Get closing balance from customerCredit

        // Pass the sale, customer credit, and balances to the view
        return view('admin_panel.sale.receipt', compact('sale', 'customerCredit', 'previous_balance', 'closing_balance'));
    }
}
