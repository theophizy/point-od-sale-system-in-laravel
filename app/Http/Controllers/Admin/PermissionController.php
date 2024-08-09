<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Module;

class PermissionController extends Controller
{
    public function createPermission(Request $request)
    {
        $modules = Module::all();
        if ($request->isMethod('post')) {

            $rules = [
                'name' => 'required',
                'module_id' => 'required',
            ];
            $customMessages = [
                'name.required' => 'Permission name  is required',
                'module_id.required' => 'You must select a module to which this permission belongs to',
            ];
            $this->validate($request, $rules, $customMessages);

            $permission = new Permission();
            $permission->name = $request->name;
            $permission->module_id = $request->module_id;
            $permission->description = $request->description;

            $newPermission = $permission->save();

            if ($newPermission) {
                return redirect()->back()->with('success_message', 'Permission  Created Successfully');
            } else {
                return redirect()->back()->with('error_message', 'Oops! an error occured. Kindly check your entries');
            }
        }
        return view('admin.permission.create_permission')->with(compact('modules'));
    }

    public function editPermission(Request $request)
    {
        if ($request->isMethod('post')) {

            $rules = [
                'name' => 'required',
                'module_id' => 'required',
            ];
            $customMessages = [
                'name.required' => 'Role name  is required',
                'module_id.required' => 'You must select a module to which this permission belongs to',

            ];
            $this->validate($request, $rules, $customMessages);

            $updatePermission = Permission::where('id', $request->id)->update(['name' => $request->name, 'module_id' => $request->module_id, 'description' => $request->description]);

            if ($updatePermission) {
                return redirect()->route('permission_view');
            } else {
                return redirect()->back()->with('error_message', 'Oops! an error occured. Kindly check your entries');
            }
        }
    }


    public function viewPermission()
    {
        $permissions = Permission::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.permission.view_permission')->with(compact('permissions'));
    }

    public function viewPermissionById($id)
    {
        $modules = Module::all();
        $permissionDetails = Permission::find($id);
        return view('admin.permission.edit_permission')->with(compact('permissionDetails', 'modules'));
    }

    public function deletePermission($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();
            return redirect()->route('permission_view')->with('success_message', 'Permission deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('permission_view')->with('error_message', $e->getMessage());
        }

    }
}
