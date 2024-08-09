<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Model\Admin;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        if($request->isMethod('post')){
            $data = $request->all();
         //echo "<pre>"; print_r($data); die;
        // custom validation messages
        $rules = [
                 'email' => 'required|email',
                 'password' => 'required',
        ];
        $customMessages = [
            // Add custom messages here
            'email.required' =>'Email Address is required',
            'email.email' =>'Valid Email Address is required',
            'password.required' =>'Password is reqired'
        ];
        $this->validate($request,$rules,$customMessages);
       

        $credentials = $request->only('email','password');

        if(Auth::guard('admin')->attempt(['email'=>$data['email'], 'password'=>$data['password'], 'status'=>'ACTIVE'])){
           if(Auth::guard('admin')->user()->dashboard_access == "YES"){
            return redirect()->route('dashboard');
           }else{
            return redirect()->route('user_dashboard');
           }
           // return redirect()->route('admin.dashboard'); // Adjust the redirection as needed
        }else{
            return redirect()->back()->with(['error_message' => 'Invalid credentials']);
        }

       
    }
}

    /**
     * Handle admin logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect()->route('login'); // Or wherever you wish to redirect after logout
    }
}
