<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Employee;
use App\Models\Sale;
use App\Models\Module;
use App\Models\Role;
use App\Models\Admin;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function adminDashboard(Request $request)
    {

        $days = $request->input('days');
        $salesMethod = $request->input('sales_method');
        $paymentMethod = $request->input('payment_method');
        // Initial query setup

        $query = Sale::query();
        $totalAmountFromSuppliesQuery = Inventory::query();
        $selectedPaymentMethod = "";
        $daysSelected = "";
        // Apply filters based on days
        if ($days) {
            $query->where('created_at', '>', now()->subDays($days));
            $totalAmountFromSuppliesQuery->where('created_at', '>', now()->subDays($days));
            $daysSelected =  $days;
        }
        // Apply filters based on payment method if provided
        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
         
            $selectedPaymentMethod = $paymentMethod;
        }

        // Get the total amount of sales
        $totalAmountSales = $query->sum('total_amount');
        $totalExpendituresOnProductPurchased = $totalAmountFromSuppliesQuery->sum('total_amount');
        // Count of various entities
        $totalProducts = Product::count();

        $totalUsers = Employee::count();
        $totalRoles = Role::count();
        $totalModules = Module::count();

        $lowQantityProducts = Product::where('quantity', '<', 20)->orderBy('quantity', 'ASC')->paginate(10);


        // Fetch data for the previous month dynamically

        if (!empty($days)) {
            $startDate = now()->subDays($days)->toDateString();
            $endDate = now()->endOfDay();
            $daysSelected =  $days; // assuming the end date is today, adjust if needed
        } else {
            $startDate = now()->subMonth()->startOfMonth()->toDateString();
            $endDate = now()->subMonth()->endOfMonth()->toDateString();
        }
        // Base query for sales in the previous month
        $previousMonthQuery = Sale::whereBetween('created_at', [$startDate, $endDate]);


        // Apply payment method filter if provided
        if ($paymentMethod) {
            $previousMonthQuery->where('payment_method', $paymentMethod);
            $selectedPaymentMethod = $paymentMethod;
        }

        // Execute the query with grouping by payment method
        $salesByPaymentMethodPreviousMonth = $previousMonthQuery->groupBy('payment_method')
            ->selectRaw('payment_method, SUM(total_amount) as total_amount')
            ->get();


        // Fetch data for the bar chart for current year monthly sales trends
        $currentYear = Carbon::now()->year;
        $currentYearQuery = Sale::whereYear('created_at', $currentYear);


        // Apply payment method filter if provided
        if ($paymentMethod) {
            $currentYearQuery->where('payment_method', $paymentMethod);
        }

        // Execute the query for monthly trends
        $salesTrendMonthly = $currentYearQuery->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as total_amount')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $today = Carbon::today();
        $thirtyDaysFromNow = Carbon::today()->addDays(30);
        $getExpiringOrExpiredProducts = Product::where('expiry_date', '<', $today)
            ->orWhereBetween('expiry_date', [$today, $thirtyDaysFromNow])
            ->paginate(10);


        return view('admin.dashboard', compact('totalProducts', 'totalAmountSales', 'totalUsers', 'totalRoles', 'totalModules', 'lowQantityProducts', 'salesByPaymentMethodPreviousMonth', 'salesTrendMonthly', 'daysSelected', 'selectedPaymentMethod', 'getExpiringOrExpiredProducts','totalExpendituresOnProductPurchased'));
    }

    public function userDashboard(Request $request)
    { $days = $request->input('days');
        $salesMethod = $request->input('sales_method');
        $paymentMethod = $request->input('payment_method');
        // Initial query setup

        $query = Sale::query();
      
        $selectedPaymentMethod = "";
        $daysSelected = "";
        // Apply filters based on days
        if ($days) {
            $query->where('created_at', '>', now()->subDays($days));
          
            $daysSelected =  $days;
        }
        // Apply filters based on payment method if provided
        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
         
            $selectedPaymentMethod = $paymentMethod;
        }

        // Get the total amount of sales
        $totalAmountSales = $query->where('admin_id',Auth::guard('admin')->user()->id)->sum('total_amount');
       
        // Count of various entities
        $totalProducts = Product::count();


        $lowQantityProducts = Product::where('quantity', '<', 20)->orderBy('quantity', 'ASC')->paginate(10);


        // Fetch data for the previous month dynamically

        if (!empty($days)) {
            $startDate = now()->subDays($days)->toDateString();
            $endDate = now()->endOfDay();
            $daysSelected =  $days; // assuming the end date is today, adjust if needed
        } else {
            $startDate = now()->subMonth()->startOfMonth()->toDateString();
            $endDate = now()->subMonth()->endOfMonth()->toDateString();
        }
        // Base query for sales in the previous month
        $previousMonthQuery = Sale::whereBetween('created_at', [$startDate, $endDate]);


        // Apply payment method filter if provided
        if ($paymentMethod) {
            $previousMonthQuery->where('payment_method', $paymentMethod);
            $selectedPaymentMethod = $paymentMethod;
        }

        // Execute the query with grouping by payment method
        $salesByPaymentMethodPreviousMonth = $previousMonthQuery->where('admin_id',Auth::guard('admin')->user()->id)->groupBy('payment_method')
            ->selectRaw('payment_method, SUM(total_amount) as total_amount')
            ->get();


        // Fetch data for the line chart for current year monthly sales trends
        $currentYear = Carbon::now()->year;
        $currentYearQuery = Sale::whereYear('created_at', $currentYear);


        // Apply payment method filter if provided
        if ($paymentMethod) {
            $currentYearQuery->where('payment_method', $paymentMethod);
        }

        // Execute the query for monthly trends
        $salesTrendMonthly = $currentYearQuery->where('admin_id',Auth::guard('admin')->user()->id)->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as total_amount')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $today = Carbon::today();
        $thirtyDaysFromNow = Carbon::today()->addDays(30);
        $getExpiringOrExpiredProducts = Product::where('expiry_date', '<', $today)
            ->orWhereBetween('expiry_date', [$today, $thirtyDaysFromNow])
            ->paginate(10);


        return view('admin.user_dashboard', compact('totalProducts', 'totalAmountSales', 'lowQantityProducts', 'salesByPaymentMethodPreviousMonth', 'salesTrendMonthly', 'daysSelected', 'selectedPaymentMethod', 'getExpiringOrExpiredProducts'));
    }
}
