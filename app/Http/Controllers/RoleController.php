<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\CompanyMaster;
use App\Models\WlUser;
use App\Models\User;
use App\Models\infoTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Auth;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::User()->role_id == 2){
            $Roles = Role::orderBy('roles.id', 'DESC')
                ->select('roles.*', 'users.first_name', 'users.last_name')
                ->join('users', 'users.id', '=', 'roles.enterBy', 'left outer')
                ->where(['roles.isDelete' => 0, 'roles.iStatus' => 1, "roles.iCompanyId" => Session::get('CompanyId')])
                ->get();

            return view('wladmin.role.index', compact('Roles'));
        } else {
            return redirect()->route('home'); 
        }
    }

    public function infoindex(Request $request, $id)
    {
        if(Auth::User()->role_id == 2){
            $Role = Role::orderBy('id', 'DESC')->where(['isDelete' => 0, 'iStatus' => 1, "id" => $id, "iCompanyId" => Session::get('CompanyId')])->first();
            $userList = WlUser::where(["iRoleId" => $id, "iCompanyId" => Session::get('CompanyId'), "iStatus" => 1, "isDelete" => 0])
                ->select('strFirstName', 'strLastName')
                ->get();
            $infoTables = infoTable::where(["tableName" => "Roles", "tableAutoId" => $id])->orderBy('id', 'Desc')->limit(10)->get();
            $rolePermission = DB::table('role_has_permissions')->where(['role_id' => $id])
                ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->where(function ($query) {
                                    $query->where('permissions.module_id', 0)
                                          ->orWhereIn('permissions.module_id', function ($subQuery) {
                                              $subQuery->select('oem_company_modules.iModuleId')
                                                       ->from('oem_company_modules')
                                                       ->where('oem_company_modules.iOEMCompany', Session::get('CompanyId'));
                                          });
                                })
                                ->select('permissions.*', 'role_has_permissions.*')
                ->get();
            $menuArray = array();
            foreach ($rolePermission as $menu) {

                $menuArray[] = $menu->permission_id;
            }
            $permissionArray = array();
            $permissionList = DB::table('permissions')->get();

            foreach ($permissionList as &$permission) {
                $permission = get_object_vars($permission);

                if ($permission['menu_id'] == 0) {
                    $permissionArray[$permission['id']] = $permission;
                } else {
                    if(in_array($permission['id'],$menuArray))
                        $permissionArray[$permission['menu_id']]['submenu'][] = $permission;
                }
            }
           
            return view('wladmin.role.info', compact('Role', 'userList', 'infoTables', 'menuArray', 'permissionArray'));
        } else {
            return redirect()->route('home'); 
        }
    }

    public function createview()
    {
        if(Auth::User()->role_id == 2){
            $permissionArray = array();
            $permissionList = DB::table('permissions')
             ->where('module_id', 0)
            ->orWhere(function ($query) {
                $query->whereIn('module_id', function ($subquery) {
                    $subquery->select('iModuleId')
                        ->from('oem_company_modules')
                        ->where('iOEMCompany', Session::get('CompanyId'))
                        ->whereColumn('iModuleId', 'permissions.module_id');
                });
            })->get();

            foreach ($permissionList as &$permission) {
                $permission = get_object_vars($permission);

                if ($permission['menu_id'] == 0) {
                    $permissionArray[$permission['id']] = $permission;
                } else {
                    $permissionArray[$permission['menu_id']]['submenu'][] = $permission;
                }
            }

            return view('wladmin.role.add', compact('permissionArray'));
        } else {
            return redirect()->route('home'); 
        }
    }

    public function userlist()
    {
        if(Auth::User()->role_id == 2){
            $Role = Role::where(['isDelete' => 0, 'iStatus' => 1])->get();

            return view('wladmin.role.userlist', compact('Role'));
        } else {
            return redirect()->route('home'); 
        }
    }

    public function create(Request $request)
    {
        if(Auth::User()->role_id == 2){
            try {
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => Session::get('CompanyId')])->first();
                $roleData = array(
                    "iCompanyId" => $CompanyMaster->iCompanyId,
                    "name" => $request->role_name,
                    "guard_name" => 'web',
                    "enterBy" => $session,
                    "strIP" => $request->ip()
                );
                $roleId = DB::table('roles')->insertGetId($roleData);
                foreach ($request->permissionId as $permission) {
                    $menu_id = $permission . '_1';
                    $parent_menu = $permission . '_menu';
                    if ($request->$menu_id == 1) {
                        $Data = array([
                            'permission_id' => $permission,
                            'role_id' => $roleId,
                            "iCompanyId" => $CompanyMaster->iCompanyId
                        ]);
                        DB::table('role_has_permissions')->insert($Data);
                    }
                }
                DB::commit();
                $save = $request->save;
                $userdata = User::whereId($session)->first();
                $infoArr = array(
                    'tableName'    => "Roles",
                    'tableAutoId'    => $roleId,
                    'tableMainField'  => "Customer Role",
                    'action'     => "Inserted",
                    'strEntryDate' => date("Y-m-d H:i:s"),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);
                Session::flash('Success', 'Role Created Successfully.');
                if ($save == '1') {
                    echo $roleId;
                } else {
                    return redirect()->route('role.index');
                }

            } catch (\Throwable $th) {
                DB::rollBack();
                $save = $request->save;
                Session::flash('Error', $th->getMessage());
                if ($save == '1') {
                    echo 0;
                } else {
                    return redirect()->back()->withInput()->with('Error', $th->getMessage());
                }
            }
        } else {
            return redirect()->route('home'); 
        }
    }

    public function editview(Request $request, $id)
    {
        if(Auth::User()->role_id == 2){
            $Data = Role::where(['iStatus' => 1, 'isDelete' => 0, 'id' => $id])->first();
            $rolePermission = DB::table('role_has_permissions')->where(['role_id' => $id])
                ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->where(function ($query) {
                    $query->where('permissions.module_id', 0)
                          ->orWhereIn('permissions.module_id', function ($subQuery) {
                              $subQuery->select('oem_company_modules.iModuleId')
                                       ->from('oem_company_modules')
                                       ->where('oem_company_modules.iOEMCompany', Session::get('CompanyId'));
                          });
                })
                ->select('permissions.*', 'role_has_permissions.*')
                ->get();
            $menuArray = array();
            foreach ($rolePermission as $menu) {
                $menuArray[] = $menu->permission_id;
            }
            $permissionArray = array();
            $permissionList = DB::table('permissions')->where('module_id', 0)
                                        ->orWhere(function ($query) {
                                            $query->whereIn('module_id', function ($subquery) {
                                                $subquery->select('iModuleId')
                                                    ->from('oem_company_modules')
                                                    ->where('iOEMCompany', Session::get('CompanyId'))
                                                    ->whereColumn('iModuleId', 'permissions.module_id');
                                            });
                                        })->get();

            foreach ($permissionList as &$permission) {
                $permission = get_object_vars($permission);

                if ($permission['menu_id'] == 0) {
                    $permissionArray[$permission['id']] = $permission;
                } else {
                    $permissionArray[$permission['menu_id']]['submenu'][] = $permission;
                }
            }
            
            return view('wladmin.role.edit', compact('permissionArray', 'Data', 'menuArray'));
        } else {
            return redirect()->route('home'); 
        }
    }

    public function update(Request $request)
    {
        if(Auth::User()->role_id == 2){
            try {
                $session = Session::get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                $CompanyMaster = CompanyMaster::where(['isDelete' => 0, 'iStatus' => 1, "iCompanyId" => Session::get('CompanyId')])->first();
                $roleData = array(
                    "name" => $request->role_name,
                );
                $roleId = DB::table('roles')->where("id", "=", $request->iRoleId)->update($roleData);
                DB::table('role_has_permissions')->where(["iCompanyId" => $CompanyMaster->iCompanyId, "role_id" => $request->iRoleId])->delete();
                foreach ($request->permissionId as $permission) {
                    $menu_id = $permission . '_1';
                    $parent_menu = $permission . '_menu';
                    if ($request->$menu_id == 1) {
                        $Data = array([
                            'permission_id' => $permission,
                            'role_id' =>  $request->iRoleId,
                            "iCompanyId" => $CompanyMaster->iCompanyId
                        ]);
                        DB::table('role_has_permissions')->insert($Data);
                    }
                }
                DB::commit();
                $save = $request->save;
                $userdata = User::whereId($session)->first();
                $infoArr = array(
                    'tableName'    => "Roles",
                    'tableAutoId'    => $request->iRoleId,
                    'tableMainField'  => "Customer Role",
                    'action'     => "Update",
                    'strEntryDate' => date("Y-m-d H:i:s"),
                    'actionBy'    => $userdata->first_name . " " . $userdata->last_name,
                );
                $Info = infoTable::create($infoArr);
                Session::flash('Success', 'Role Updated Successfully.');
                if ($save == '1') {
                    echo $request->iRoleId;
                } else {
                    return redirect()->route('role.index');
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $save = $request->save;

                Session::flash('Error', $th->getMessage());
                if ($save == '1') {
                    echo 0;
                } else {
                    return redirect()->route('role.index');
                }
            }
        } else {
            return redirect()->route('home'); 
        }
    }

    public function delete($Id)
    {
        if(Auth::User()->role_id == 2){
            DB::table('role_has_permissions')->where(["iCompanyId" => Session::get('CompanyId'), "role_id" => $Id])->delete();
            DB::table('roles')->where(["iCompanyId" => Session::get('CompanyId'), "id" => $Id])->delete();

            return redirect()->route('role.index')->with('Success', 'Role Deleted Successfully!.');
        } else {
            return redirect()->route('home'); 
        }
    }
}
