<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Inventory;
class SupplierController extends Controller
{
    public function createSupplier(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'supplier_name' => 'required|string|min:5',
                'supplier_email' => 'required|email|max:255|unique:suppliers',
                'supplier_phone' => 'required|digits_between:10,11|unique:suppliers',
                'supplier_address'=> 'required|string|min:15',
               // Add other validation rules as needed
            ]);

        $supplier = new Supplier();
            $supplier->supplier_name = $request->supplier_name;
            $supplier->supplier_address = $request->supplier_address;
            $supplier->supplier_phone = $request->supplier_phone;
            $supplier->supplier_email = $request->supplier_email;
            $supplier->status = "ACTIVE";
          
          $newSupplier = $supplier->save();
 
            if($newSupplier){
                return redirect()->back()->with('success_message','Supplier  Created Successfully');
                }else{
                    return redirect()->back()->with('error_message','Oops! an error occured. Kindly check your entries');
                }
        }
        return view('admin.supplier.create_supplier'); 
}


public function editSupplier(Request $request)
{
    if($request->isMethod('post')){
  
        $request->validate([
            'supplier_name' => 'required|string|min:5',
            'supplier_email' => 'required|email|max:255|unique:suppliers,supplier_email,' .$request->id,
            'supplier_phone' => 'required|digits_between:10,11|unique:suppliers,supplier_phone,' .$request->id,
            'supplier_address'=> 'required|string|min:15',
           // Add other validation rules as needed
        ]);

       $updateSupplier = Supplier::where('id',$request->id)->update(['supplier_name'=>$request->supplier_name,'supplier_address'=>$request->supplier_address,
       'supplier_phone'=>$request->supplier_phone,
       'supplier_email'=>$request->supplier_email,
       'status'=>$request->status]);

        if($updateSupplier){
            return redirect()->route('supplier_view')->with('success_message','Record updated successfully');
            }else{
                return redirect()->back()->with('error_message','Oops! an error occured. Kindly check your entries');
            }
    }
    
}


public function viewSuppliers(){
    $suppliers = Supplier::orderBy('created_at','DESC')->paginate(10);
    return view('admin.supplier.view_supplier')->with(compact('suppliers'));
    }
    
    public function viewSupplierById($id){
        $supplierDetails = Supplier::find($id);
        return view('admin.supplier.edit_supplier')->with(compact('supplierDetails'));
        }

        public function deleteSupplier($id){
            $getInventoryBySupplier = Inventory::where('supplier_id',$id)->first();
           if($getInventoryBySupplier){
            return redirect()->route('supplier_view')->with('error_message','Supplier has an associated records and can not be deleted');;
           }
            $supplierDeleted = Supplier::where('id',$id)->delete();
            
            return redirect()->route('supplier_view')->with('success_message','Supplier deleted successfully');;
           
            }
}
