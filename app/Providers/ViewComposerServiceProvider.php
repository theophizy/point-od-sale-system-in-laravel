<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Carbon\Carbon;
class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // sidebar Composer
        View::composer('admin.layout.sidebar', function ($view) {
            $admin = Auth::guard('admin')->user();
            
            if ($admin) {
                // Manually load roles and their permissions with modules
                $roles = $admin->roles()->with('permissions.module')->get();

                // Get unique permissions and modules
                $permissions = $roles->flatMap(function ($role) {
                    return $role->permissions;
                })->unique('id');

                $modules = $permissions->map(function ($permission) {
                    return $permission->module;
                })->unique('id');

                $sidebarPermissions = $permissions->filter(function ($permission) {
                    return strpos($permission->name, '_view') !== false || strpos($permission->name, '_create') !== false 
                    || strpos($permission->name, '_report') !== false
                    || strpos($permission->name, 'cashierAccount') !== false;
                });
               
                $view->with(compact('modules', 'permissions', 'sidebarPermissions'));
            }
        }); 
    
     View::composer('admin.layout.header', function($view){
        $drugsLessThanTenInQuantity = Product::where('quantity', '<', 10)->count();
        $today = Carbon::today();
        $thirtyDaysFromNow = Carbon::today()->addDays(30);
        $getExpiringOrExpiredProducts = Product::where('expiry_date', '<', $today)
            ->orWhereBetween('expiry_date', [$today, $thirtyDaysFromNow])
            ->count();
        $view->with(compact('drugsLessThanTenInQuantity','getExpiringOrExpiredProducts'));
     });
    
      }
}


