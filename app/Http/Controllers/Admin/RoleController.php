<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Module;
use App\Models\Permission;

class RoleController extends Controller
{
    public function createRole(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required|string|max:60|unique:roles',
              // Add other validation rules as needed
            ]);

            $role = new Role();
            $role->name = $request->name;
            $role->description = $request->description;

            $newRole = $role->save();

            if ($newRole) {
                return redirect()->back()->with('success_message', 'Role  Created Successfully');
            } else {
                return redirect()->back()->with('error_message', 'Oops! an error occured. Kindly check your entries');
            }
        }
        return view('admin.role.create_role');
    }

    public function editRole(Request $request)
    {
        if ($request->isMethod('post')) {

            $rules = [
                'name' => 'required',
            ];
            $customMessages = [
                'name.required' => 'Role name  is required',

            ];
            $this->validate($request, $rules, $customMessages);

            $updateRole = Role::where('id', $request->id)->update(['name' => $request->name, 'description' => $request->description]);

            if ($updateRole) {
                return redirect()->route('role_view');
            } else {
                return redirect()->back()->with('error_message', 'Oops! an error occured. Kindly check your entries');
            }
        }
    }


    public function viewRoles()
    {
        $roles = Role::orderBy('created_at', 'DESC')->paginate(10);
       // $roles = Role::where('id', '!=', 5)->orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.role.view_role')->with(compact('roles'));
    }

    public function viewRoleById($id)
    {
        $roleDetails = Role::find($id);
        return view('admin.role.edit_role')->with(compact('roleDetails'));
    }

    public function deleteRole($id)
    {

        try {
            $product = Role::findOrFail($id);
            $product->delete();
            return redirect()->route('role_view')->with('success_message', 'Role successfully deleted.');
        } catch (\Exception $e) {
            return redirect()->route('role_view')->with('error_message', $e->getMessage());
        }
        
    }

    public function displayPermissions(Role $role)
    {
        $modules = Module::with('permissions')->get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.role.display-permissions', compact('role', 'modules', 'rolePermissions'));
    }

    public function asignPermission(Request $request, Role $role)
    {
        if ($request->isMethod('post')) {

            $rules = [
                'permissions' => 'required|array',
                'permissions.*' => 'exists:permissions,id'
            ];
            $customMessages = [
                'permissions.required' => 'A permission must be selected',
                'permissions.*.exist' => 'Permission must exist',

            ];
            $this->validate($request, $rules, $customMessages);
        // $data = $request->validate([
        //     'permissions' => 'required|array',
        //     'permissions.*' => 'exists:permissions,id',
        // ]);

        $role->permissions()->sync($request['permissions']);

        return redirect()->route('role_display_permissions', $role)->with('success_message', 'Permissions updated successfully.');
    }
    }
}
