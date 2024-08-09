<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;
class ModuleController extends Controller
{
    public function createModule(Request $request)
    {
        if($request->isMethod('post')){
      
            $request->validate([
                'name' => 'required|string|max:60|unique:modules',
              // Add other validation rules as needed
            ]);
        $module = new Module();
            $module->name = $request->name;
            $module->description = $request->description;
          
          $newModule = $module->save();
 
            if($newModule){
                return redirect()->back()->with('success_message','Module  Created Successfully');
                }else{
                    return redirect()->back()->with('error_message','Oops! an error occured. Kindly check your entries');
                }
        }
        return view('admin.module.create_module'); 
}


public function editModule(Request $request)
{
    if($request->isMethod('post')){
  
        $rules =[
            'name' => 'required',
        ];
        $customMessages = [
            'name.required' => 'Module name  is required',
           
        ];
        $this->validate($request,$rules,$customMessages);

       $updateModule = Module::where('id',$request->id)->update(['name'=>$request->name,'description'=>$request->description]);

        if($updateModule){
            return redirect()->route('module_view');
            }else{
                return redirect()->back()->with('error_message','Oops! an error occured. Kindly check your entries');
            }
    }
    
}


public function viewModules(){
    $modules = Module::orderBy('created_at','DESC')->paginate(10);
    return view('admin.module.view_module')->with(compact('modules'));
    }
    
    public function viewModuleById($id){
        $moduleDetails = Module::find($id);
        return view('admin.module.edit_module')->with(compact('moduleDetails'));
        }

        public function deleteModule($id){
           
            $moduleDeleted = Module::where('id',$id)->delete();
            
            return redirect()->route('module_view');
           
            }
}
