<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerCredit;
use App\Models\CustomerRecovery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function customer()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            // dd($userId);
            // Fetch customers along with their closing balance from customer_credits
            $Customers = Customer::where('admin_or_user_id', $userId)
                ->leftJoin('customer_credits', 'customers.id', '=', 'customer_credits.customerId')
                ->select('customers.*', 'customer_credits.closing_balance')
                ->get();

            return view('admin_panel.customers.customers', [
                'Customers' => $Customers
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function store_customer(Request $request)
    {
        if (Auth::id()) {
            $usertype = Auth()->user()->usertype;
            $userId = Auth::id();
            Customer::create([
                'admin_or_user_id'    => $userId,
                'customer_name'          => $request->customer_name,
                'customer_email'          => $request->customer_email,
                'customer_phone'          => $request->customer_phone,
                'customer_address'          => $request->customer_address,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ]);
            return redirect()->back()->with('Customer-added', 'Customer has been  created successfully');
        } else {
            return redirect()->back();
        }
    }
    public function update_customer(Request $request)
    {
        if (Auth::id()) {
            $usertype = Auth()->user()->usertype;
            $userId = Auth::id();
            // dd($request);
            $update_id = $request->input('customer_id');
            $name = $request->input('customer_name');
            $email = $request->input('customer_email');
            $phone = $request->input('customer_phone');
            $address = $request->input('customer_address');

            Customer::where('id', $update_id)->update([
                'customer_name'          => $name,
                'customer_email'          => $email,
                'customer_phone'          => $phone,
                'customer_address'          => $address,
                'updated_at' => Carbon::now(),
            ]);
            return redirect()->back()->with('Customer-update', 'Customer Updated Successfully');
        } else {
            return redirect()->back();
        }
    }

    public function processRecovery(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'recovery_date' => 'required|date',
            'recovery_amount' => 'required|numeric|min:0',
        ]);

        // Fetch the customer record
        $customer = CustomerCredit::find($request->customer_id);

        // Calculate new balances
        $recoveryAmount = $request->recovery_amount;
        $updatedClosingBalance = $customer->closing_balance - $recoveryAmount;

        // Store recovery details in the CustomerRecovery table (assumes you have this table)
        CustomerRecovery::create([
            'customer_id' => $customer->id,
            'customer_name' => $customer->customer_name,
            'recovery_date' => $request->recovery_date,
            'recovery_amount' => $recoveryAmount,
            'closing_balance' => $updatedClosingBalance,
        ]);

        // Update customer's balance in the Customer table
        $customer->previous_balance -= $recoveryAmount;
        $customer->closing_balance = $updatedClosingBalance;
        $customer->save();

        return redirect()->back()->with('success', 'Customer recovery details saved successfully!');
    }

    public function customer_recovires()
    {
        if (Auth::id()) {
            $userId = Auth::id();
            // dd($userId);
            // Fetch customers along with their closing balance from customer_credits
            $Customers = CustomerRecovery::get();

            return view('admin_panel.customers.customers_recoveries', [
                'Customers' => $Customers
            ]);
        } else {
            return redirect()->back();
        }
    }


}
