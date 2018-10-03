<?php

namespace App\Http\Controllers\Catalogos\User;

use App\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use Authorizable;

    protected $tableName = '';
    protected $lstAsigns = "";
    protected $redirectTo = '/home';

    public function __construct(){
        $this->middleware('auth');
    }

    public function index($Id = 0){
        $listEle     = Role::all()->sortByDesc('name')->pluck('name','name');
        $listTarget  = User::all()->sortByDesc('username')->pluck('username','id');
        $Id = $Id == 0 ? 1 : $Id;
        $users = User::findOrFail($Id);
        $this->lstAsigns = $users->roles->pluck('name','name');

        $user = Auth::User();
        return view ('catalogos.asignaciones.roles_usuario',
            [
                'listEle' => $listEle,
                'listTarget' => $listTarget,
                'lstAsigns' => $this->lstAsigns,
//                'id' => $ida,
                'titulo_catalogo' => "Asignación de Roles",
                'user' => $user,
                'Id' => $Id,
                'titlePanels' => 'Roles Usuario',
                'titleLeft0'  => "Roles",
                'urlAsigna'   => "assignRoleToUser",
                'urlRegresa'  => "asignaRole",
                'urlElimina'  => "unAssignRoleToUser",
            ]
        );

    }

    public function asignar($Id, $nameRoles)
    {
        $user = User::findOrFail($Id);
//        dd($user->username);
        $roles = explode('|',$nameRoles);
        foreach($roles AS $i=>$valor) {
            if ($roles[$i] !== ""){
                $role = Role::where('name', $roles[$i])->first();
                $rl = $user->hasRole($roles[$i]); // Role::where('name',$perm)->count();
                if (!$rl) {
                    $user->roles()->attach($role);
                    if ($roles[$i] === 'administrator'){
                        $user->admin = true;
                        $user->save();
                    }
                }
            }
        }
        return Response::json(['mensaje' => "/asignaRole/$Id", 'data' => 'OK', 'status' => '200'], 200);

    }

    public function desasignar($Id, $nameRoles)
    {
        $user = User::findOrFail($Id);
        $roles = explode('|',$nameRoles);
        foreach($roles AS $i=>$valor) {
            if ($roles[$i] !== "") {
                $role = Role::where('name', $roles[$i])->first();
                $user->removeRole($role);
                if ($roles[$i] === 'administrator'){
                    $user->admin = false;
                    $user->save();
                }

            }
        }
        return Response::json(['mensaje' => "/asignaRole/$Id", 'data' => 'OK', 'status' => '200'], 200);
    }

}
