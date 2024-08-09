<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class EmployeeController extends Controller
{
    public function createUser(Request $request)
    {
        if ($request->isMethod('post')) {
            //dd($request->all());
        $request->validate([
            'name' => 'required|string|max:60',
            'email' => 'required|email|max:255|unique:admins',
            'phone' => 'required|digits_between:10,11',
            'other_name' => 'required|string|max:100',
            'address'=> 'required|string|min:15',
            'guarantor_name'=> 'required',
            'guarantor_address'=> 'required',
            'guarantor_phone'=>'required|digits_between:10,11',
            'guarantor_place_work'=> 'required|string|min:30',
            'contact_person_name'=> 'required|string|max:80',
            'contact_person_address'=> 'required|string|min:15',
            'contact_person_phone'=> 'required|digits_between:10,11',
            
            // Add other validation rules as needed
        ]);

        try{
            DB::beginTransaction();
        // Simultaneously, create Admin record for authentication
        $admin = new Admin();
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->phone = $request->phone;
            $admin->password = Hash::make("password");
           
            $admin->dashboard_access = $request->dashboard_access;
            $admin->status = "ACTIVE";
            $admin->save();
 
        // Create Employee record
        $employee = new Employee();
        $employee->admin_id = $admin->id; 
        $employee->other_name = $request->other_name;
        $employee->address = $request->address;
        $employee->guarantor_name = $request->guarantor_name;
        $employee->guarantor_address = $request->guarantor_address;
        $employee->guarantor_phone = $request->guarantor_phone;
        $employee->guarantor_place_work = $request->guarantor_place_work;
        $employee->contact_person_name = $request->contact_person_name;
        $employee->contact_person_address = $request->contact_person_address;
        $employee->contact_person_phone = $request->contact_person_phone;
        $employee->save();
        DB::commit();
           
        
        // Return response or redirect as needed
        return redirect()->route('user_create')->with('success_message', 'Employee created successfully');
    

} catch (ValidationException $e) {
    // Handle validation errors
    return redirect()->route('user_create')->withErrors($e->errors());
} catch (\Exception $e) {
    // Handle other exceptions
    DB::rollback(); // Rollback the transaction
    // Log the error for further investigation
    Log::error('admin.employee.Error creating employee: ' . $e->getMessage());
    // Redirect with error message
}
    return redirect()->route('user_create')->with('error_message', 'Failed to create an Employee');
}
return view('admin.employee.create_user');
    }

    public function displayRoles(Admin $admin)
    {
       $roles = Role::all();
        

        return view('admin.employee.display-roles', compact('admin','roles'));
    }

    public function asignRole(Request $request, Admin $admin)
    {
        if ($request->isMethod('post')) {

            $rules = [
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,id'
            ];
            $customMessages = [
                'roles.required' => 'A role must be selected',
                'roles.*.exist' => 'Role must exist',

            ];
            $this->validate($request, $rules, $customMessages);
        // $data = $request->validate([
        //     'permissions' => 'required|array',
        //     'permissions.*' => 'exists:permissions,id',
        // ]);

        $admin->roles()->sync($request['roles']);

        return redirect()->route('user_displayRoles', $admin)->with('success_message', 'Roles updated successfully.');
    }
    }

    public function viewUsers()
    {
      //  $adminUsers= Admin::with('employee')->where('id','!=', 1)->orderBy('created_at', 'DESC')->paginate(10);
        $adminUsers= Admin::with('employee')->orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.employee.view_users')->with(compact('adminUsers'));
    }

    public function viewEmployees()
    {
        $employees= Employee::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.employee.view_employee')->with(compact('employees'));
    }

    public function viewUserDetailsById($id)
    {
        
            $admin = Admin::with('employee')->findOrFail($id);
            return response()->json($admin);
        
    }
    public function viewUserById($id)
    {
        $adminDetails = Admin::with('employee')->findOrFail($id);
        return view('admin.employee.edit_user')->with(compact('adminDetails'));
    }

    public function editUser(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required|string|max:60',
                'email' => 'required|email|max:255|unique:admins,email,' .$request->id,
                'phone' => 'required|digits_between:10,11',
                'other_name' => 'required|string|max:100',
                'address'=> 'required|string|min:15',
                'guarantor_name'=> 'required',
                'guarantor_address'=> 'required',
                'guarantor_phone'=>'required|digits_between:10,11',
                'guarantor_place_work'=> 'required|string|min:10',
                'contact_person_name'=> 'required|string|max:80',
                'contact_person_address'=> 'required|string|min:15',
                'contact_person_phone'=> 'required|digits_between:10,11',
                
            ]);
            try{
                DB::beginTransaction();
             Admin::where('id', $request->id)->update(['name' => $request->name, 'email' => $request->email, 'phone' => $request->phone
            , 'dashboard_access' => $request->dashboard_access,'status' => $request->status]);
             Employee::where('id', $request->employee_id)->update(['other_name' => $request->other_name, 'address' => $request->address, 'guarantor_name' => $request->guarantor_name
            , 'guarantor_address' => $request->guarantor_address,'guarantor_phone' => $request->guarantor_phone
            ,'guarantor_place_work' => $request->guarantor_place_work,'contact_person_name' => $request->contact_person_name
            ,'contact_person_address' => $request->contact_person_address,'contact_person_phone' => $request->contact_person_phone]);

            DB::commit();
                return redirect()->route('user_view')->with('success_message', 'User account edited successfully');
            } catch (ValidationException $e) {
                // Handle validation errors
                return redirect()->route('user_view')->withErrors($e->errors());
            } catch (\Exception $e) {
                // Handle other exceptions
                DB::rollback(); // Rollback the transaction
                // Log the error for further investigation
                Log::error('admin.employee.Error creating employee: ' . $e->getMessage());
                // Redirect with error message
            }
                return redirect()->route('user_view')->with('error_message', 'Failed to create an Employee');
            }
           
        }

        public function changePassword(Request $request)
        {
            if ($request->isMethod('post')) {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|string|min:8|confirmed',
            ]);
      
            $admin = Auth::guard('admin')->user();
    
            if (!Hash::check($request->current_password, $admin->password)) {
                 return redirect()->back()->with('error_message', 'Current pasword is incorrect');
            }
    
            $newPassword = Hash::make($request->new_password);
            Admin::where('id',$admin->id)->update(['password'=>$newPassword]);
            return redirect()->route('logout');
        }
        return view('admin.employee.change_password');
        }

        public function resetPassword($id)
        {
    
            $defaultPassword = Hash::make('password');
            Admin::where('id',$id)->update(['password'=>$defaultPassword]);
            return redirect()->route('user_view')->with('success_message','User password has be reset to default as password. Let the use login nd change it');
        }
       
    
    public function userProfile(){
        $userProfileDetails = Admin::with('employee')->findOrFail(Auth::guard('admin')->user()->id);
        return view('admin.employee.user_profile')->with(compact('userProfileDetails'));
    }



    public function modifyMasterAccount(Request $request){
        $userProfileDetails = Admin::findOrFail(Auth::guard('admin')->user()->id);
        $admin = Auth::guard('admin')->user();
        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required|string|max:60',
                'email' => 'required|email|max:255|unique:admins,email,'.$admin->id,
                'phone' => 'required|digits_between:10,11',
              
            ]);
          
           Admin::where('id',$admin->id)->update(['name'=>$request->name,'email'=>$request->email,'phone'=>$request->phone]);
           return redirect()->route('user_masterAccounnt')->with('success_message','Settings changed successfully');
        }
        return view('admin.employee.modify_masterAcount')->with(compact('userProfileDetails'));
    }
}
