<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 4/16/2018
 * Time: 9:51 PM
 */
namespace App\Services\Implementations;

use App\Models\Role;
use App\Models\Permission;

use DB;
use Exception;

use App\Services\RoleService;

class RoleServiceImpl implements RoleService
{
    public function create(
        $name,
        $display_name,
        $description,
        $permission
    )
    {
        DB::beginTransaction();

        try {
            $role = new Role();
            $role->name = $name;
            $role->display_name = $display_name;
            $role->description = $description;
            $role->save();

            foreach ($permission as $pl) {
                $role->permissions()->attach($pl);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        };
    }

    public function read()
    {
        return Role::with('permissions')->get();
    }

    public function update(
        $id,
        $name,
        $display_name,
        $description,
        $permission,
        $inputtedPermission
    )
    {
        DB::beginTransaction();

        try {
            $role = Role::with('permissions')->where('id', '=', $id)->first();
            $pl = Permission::whereIn('id', $permission)->get();

            $role->syncPermissions($pl);

            $role->update([
                'name' => $name,
                'display_name' => $display_name,
                'description' => $description,
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        };

    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $role = Role::with('permissions')->find($id);

            $role->detachPermissions($role->getSelectedPermissionIdsAttribute());

            $role->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getAllPermissions()
    {
        return Permission::get();
    }
}