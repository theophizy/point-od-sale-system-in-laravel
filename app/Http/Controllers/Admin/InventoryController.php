<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Inventory;
use App\Models\SupplierPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class InventoryController extends Controller
{
    // public function index()
    // {
    //     $products = Product::all();
    //     $suppliers = Supplier::all();
    //     return view('inventory.index', compact('products', 'suppliers'));
    // }

    public function createInventory(Request $request)
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        
        if ($request->isMethod('post')) {
          try{
            DB::beginTransaction();
            $supplier = Supplier::find($request->supplier_id);
            $inventory = Inventory::create([
                'supplier_id' => $supplier->id,
                'total_amount' => $request->total_amount,
                
            ]);
            $amount_paid = $request->amount_paid;
            $totalAmount = 0;
          

            foreach ($request->products as $productData) {
                $product = Product::find($productData['product_id']);
                $quantitySupplied = $productData['quantity_supplied'];
                $unitCost = $productData['unit_cost'];
                $suppliedIn = $productData['supplied_in'];
                $expiryDate = $productData['expiry_date'];
                
                $unitsPerPack = $productData['units_per_pack'] ?? 0;
                $packsPerCarton = $productData['packs_per_carton'] ?? 0;
                $percentageUnitPrice = $productData['percentage_unit_price'];

                $totalQuantity = $quantitySupplied;
                $purchasePrice = $unitCost;

                // Calculate total quantity and unit cost based on supplied_in
                if ($product->sold_in == 'PACKS') {
                    if($suppliedIn != 'carton' && $suppliedIn != 'pack'){
                        return redirect()->route('inventory_create')->with('error_message', 'The selected product is sold in packs and can not be supplied in units.');
                    }
                    if ($suppliedIn == 'carton') {
                        $totalQuantity = $quantitySupplied * $packsPerCarton;
                      //  dd($totalQuantity);
                        $purchasePrice = $unitCost / $packsPerCarton;
                    }
                } elseif ($product->sold_in == 'UNITS') {
                    if ($suppliedIn == 'carton') {
                        $totalQuantity = $quantitySupplied * $packsPerCarton * $unitsPerPack;
                        $purchasePrice = $unitCost / ($packsPerCarton * $unitsPerPack);
                     //   dd($totalQuantity);
                    } elseif ($suppliedIn == 'pack') {
                        $totalQuantity = $quantitySupplied * $unitsPerPack;
                        $purchasePrice = $unitCost / $unitsPerPack;
                    }
                }

                // Calculate selling price
                $sellingPrice = $purchasePrice + ($purchasePrice * $percentageUnitPrice / 100);

                $product->update([
                    'quantity' => $product->quantity + $totalQuantity,
                    'price' => $sellingPrice,
                    'expiry_date' => $expiryDate,
                ]);

                $inventory->product()->attach($product->id, [
                    'quantity_supplied' => $quantitySupplied,
                    'unit_cost' => $unitCost,
                    'supplied_in' => $suppliedIn,
                    'units_per_pack' => $unitsPerPack,
                    'packs_per_carton' => $packsPerCarton,
                    'percentage_unit_price' => $percentageUnitPrice,
                ]);

                $totalAmount += $unitCost * $quantitySupplied;

            }
            if($amount_paid > $totalAmount ){
                return redirect()->route('inventory_create')->with('error_message', 'Amount Paid can not be greater than the total amount from the supply.');
            }else{
$balance = $totalAmount - $amount_paid;
            Inventory::where('id', $inventory->id)->update(['total_amount' => $totalAmount,'balance'=>$balance]);

            SupplierPayment::create([
                'supplier_id' => $supplier->id,
                'inventory_id' => $inventory->id,
                'amount' => $amount_paid,
            ]);
        };
        DB::commit();
    
        return redirect()->route('inventory_create')->with('success_message', 'Inventory successfully updated.');
    } catch (ValidationException $e) {
        // Handle validation errors
        return redirect()->route('user_view')->withErrors($e->errors());
    } catch (\Exception $e) {
        // Handle other exceptions
        DB::rollback(); // Rollback the transaction
        // Log the error for further investigation
        Log::error('admin.employee.Error creating employee: ' . $e->getMessage());
        return redirect()->route('inventory_create')->with('error_message', 'Failed to create inventor.'.  $e->getMessage());  // Redirect with error message
    }
       
    }

    return view('admin.inventory.create_inventory',compact('products', 'suppliers'));
}

public function viewInventory(Request $request)
    {
        $suppliers = Supplier::all();

        $query = Inventory::with(['product', 'supplierPayments', 'supplier']);

        // Check if filter parameters are present
        if ($request->filled('supplier_id') || ($request->filled('start_date') && $request->filled('end_date'))) {
            if ($request->filled('supplier_id')) {
                $query->where('supplier_id', $request->supplier_id);
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $endDate = Carbon::parse($request->end_date)->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            $inventories = $query->paginate(5);
        } else {
            // Default case: show latest 100 inventories paginated into 20 per page
            $inventories = $query->latest()->take(100)->paginate(5);
        }
        $inventories->appends(request()->except('page'));
        return view('admin.inventory.view_inventory', compact('inventories', 'suppliers'));
    }


    public function viewInventoryDetails($id)
{
    $inventory = Inventory::with(['product', 'supplierPayments', 'supplier'])->findOrFail($id);

    $details = '<table class="table table-striped">';
    $details .= '<tr><th>Supplier:</th><td>' . $inventory->supplier->supplier_name . '</td></tr>';
    $details .= '<tr><th>Total Amount:</th><td>' . $inventory->total_amount . '</td></tr>';
    $details .= '<tr><th>Balance:</th><td>' . $inventory->balance . '</td></tr>';
    $details .= '<tr><th>Date:</th><td>' . $inventory->created_at . '</td></tr>';
    $details .= '</table><br><br>';

    $details .= '<h2>Inventory Details</h2><br><br>';
    $details .= '<table class="table table-bordered"><thead><tr><th>Name</th><th>Sold Mode</th><th>Quantity Supplied</th><th>Unit Cost</th><th>Supplied In</th><th>Units in the Pack</th><th>Packs in the Carton</th><th>Percentage unit price</th></tr></thead><tbody>';
    foreach ($inventory->product as $product) {
        $details .= '<tr>';
        $details .= '<td>' . $product->name . " ".$product->weight.'</td>';
        $details .= '<td>' . $product->sold_in . '</td>';
        $details .= '<td>' . $product->pivot->quantity_supplied . '</td>';
        $details .= '<td>' . $product->pivot->unit_cost . '</td>';
        $details .= '<td>' . $product->pivot->supplied_in . '</td>';
        $details .= '<td>' . $product->pivot->units_per_pack . '</td>';
        $details .= '<td>' . $product->pivot->packs_per_carton . '</td>';
        $details .= '<td>' . $product->pivot->percentage_unit_price . '</td>';
        $details .= '</tr>';
    }
    $details .= '</tbody></table>';

    return $details;
}


// View Payments for the inventories
public function inventoryPaymentsHistory($id)
{
    $inventory = Inventory::with('supplierPayments')->findOrFail($id);
    $payments = $inventory->supplierPayments;
    $details = '<table class="table table-striped">';
    $details .= '<tr><th>Supplier:</th><td>' . $inventory->supplier->supplier_name . '</td></tr>';
    $details .= '<tr><th>Total Amount:</th><td>' . $inventory->total_amount . '</td></tr>';
    $details .= '<tr><th>Balance:</th><td>' . $inventory->balance . '</td></tr>';
    $details .= '<tr><th>Date:</th><td>' . $inventory->created_at . '</td></tr>';
    $details .= '</table><br><br>';
    $details .= '<h2>Payments Details</h2><br><br>';
    $details .= '<table class="table table-striped">';
    $details .= '<thead><tr><th>Amount</th><th>Date</th></tr></thead><tbody>';
    foreach ($payments as $payment) {
        $details .= '<tr>';
        $details .= '<td>' . $payment->amount . '</td>';
        $details .= '<td>' . $payment->created_at . '</td>';
        $details .= '</tr>';
    }
    $details .= '</tbody></table>';

    return $details;
}


public function inventoryPayment(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'inventory_id' => 'required|exists:inventories,id',
                'amount' => 'required|numeric|min:0.01',
            ]);
    
            DB::beginTransaction();
    
            $inventory = Inventory::find($validatedData['inventory_id']);
            $amount = $validatedData['amount'];
    
            // Update the balance
            if($amount > $request->balance){
            return response()->json([
                'success' => false,
                'message' => 'Amount can not be greater than the balance!',
                
            ]);
        }
            $inventory->balance -= $amount;
            $inventory->save();
    
            // Create a payment record
            SupplierPayment::create([
                'inventory_id' => $inventory->id,
                'supplier_id' => $inventory->supplier_id,
                'amount' => $amount,
                
            ]);
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Payment successful!',
                'balance' => $inventory->balance,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json([
                'success' => false,
                'message' => 'Payment failed! ' . $e->getMessage(),
            ], 500);
        }
       
            
        
    }
    


    }

