<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Admin;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Input\Input;

class SaleController extends Controller
{
    public function createSales()
    {
        return view('admin.sale.sale_create');
    }
    public function completeTransaction(Request $request)
    {
        // Validate request data

        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'totalPrice' => 'required|numeric',
            'paymentMethod' => 'required|string',
            // Add more validation rules as needed
        ]);

        // Start a database transaction
        DB::beginTransaction();


        try {
            // Create a new order

            $sale = new Sale();
            $sale->admin_id = Auth::guard('admin')->user()->id; // Assuming you have user authentication
            $sale->total_amount = $validated['totalPrice'];
            $sale->payment_method = $validated['paymentMethod'];
            $sale->status = "SUCCESSFUL";
            $sale->save();

            foreach ($validated['items'] as $item) {
                $saleItem = new SaleItem();
                $saleItem->sale_id = $sale->id;
                $saleItem->product_id = $item['id'];
                $saleItem->quantity = $item['quantity'];


                // Decrement product stock
                $product = Product::find($item['id']);
                $saleItem->price_per_unit = $product->price;
                $saleItem->save();
                $product->quantity -= $item['quantity'];
                $product->save();
            }


            // Commit the transaction
            DB::commit();
            Log::info('Response data: ' . json_encode(['success' => true, 'sale_id' => $sale->id, 'message' => 'Sale completed successfully!']));
            // Return success response
            return response()->json(['success' => true, 'sale_id' => $sale->id, 'message' => 'Sale completed successfully!']);
        } catch (\Exception $e) {
            // Rollback the transaction in case of any error
            DB::rollBack();
            // Return error response
            return response()->json(['error' => 'Transaction failed'], $e->getMessage());
        }
    }

    public function showReceipt($sale_id)
    {
        $sale = Sale::findOrFail($sale_id);
        return view('admin.sale.receipt', compact('sale'));
    }
    public function ViewAllSales()
    {
        $sales = Sale::orderBy('created_at', 'DESC')->paginate(10);

        return view('admin.sale.view_sales', compact('sales'));
    }

    public function getSaleItems($saleId)
    {
         $items = SaleItem::with('product','sales')->where('sale_id', $saleId)->get();
        return response()->json($items);
    }

    public function showFilterPage()
    {
        $admins = Admin::all();
        return view('admin.sale.sales_filter', compact('admins'));
    }


    public function filterSales(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $adminId = $request->input('admin_id'); 
        $paymentMethod = $request->input('payment_method'); 
        $admins = Admin::all();
        $query = Sale::query();

        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate. ' 00:00:00', $toDate. ' 23:59:59']);
        }

        if ($adminId) {
            $query->where('admin_id', $adminId);
        }
        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
        }
        $sales = $query->orderBy('created_at', 'DESC')->paginate(10);

        // Append query parameters to pagination links
        $sales->appends(request()->except('page'));
 // calculatete the sum
 $totalAmount = Sale::query()
    ->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
    ->when($adminId, function ($query) use ($adminId) {
        return $query->where('admin_id', $adminId);
    })
    ->when($paymentMethod, function ($query) use ($paymentMethod) {
        return $query->where('payment_method', $paymentMethod);
    })
    ->sum('total_amount');
// Chart record for date range
    $salesByPaymentMethod = Sale::whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
    ->groupBy('payment_method')
    ->selectRaw('payment_method, SUM(total_amount) as total_amount')
    ->get();
        return view('admin.sale.sales_filter', compact('sales','admins','totalAmount','salesByPaymentMethod'));
    }



    public function filterSalesByLoginUser(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $paymentMethod = $request->input('payment_method'); 
        $query = Sale::query();

        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate. ' 00:00:00', $toDate. ' 23:59:59']);
        }

        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
        }
        $sales = $query->where('admin_id',Auth::guard('admin')->user()->id)->orderBy('created_at', 'DESC')->paginate(10);

        // Append query parameters to pagination links
        $sales->appends(request()->except('page'));
 // calculatete the sum
 $totalAmount = Sale::query()
    ->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
    ->where('admin_id',Auth::guard('admin')->user()->id)
    ->when($paymentMethod, function ($query) use ($paymentMethod) {
        return $query->where('payment_method', $paymentMethod);
    })
    ->sum('total_amount');
// Chart record for date range
    $salesByPaymentMethod = Sale::whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
    ->groupBy('payment_method')
    ->selectRaw('payment_method, SUM(total_amount) as total_amount')
    ->get();
        return view('admin.sale.sales_cashierAccount', compact('sales','totalAmount','salesByPaymentMethod'));
    }
}
