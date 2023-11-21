<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
class UserController extends Controller
{
    //index

    public function index()
    {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['developer', 'admin']);
        })->get();

        // $users = User::paginate(10);
        return new JsonResponse([
            'message' => 'success',
            'data' => UserResource::collection($users),
        ], Response::HTTP_OK);
    }

    function getAllRoles()
    {
        // Get all roles
        $roles = Role::select('id', 'name')->get();

        return $roles;
    }

    public function assignRole(Request $request)
    {
        try {
            // Find the user
            $user = User::findOrFail($request->userId);

            // Find the role
            $role = Role::where('name', $request->roleName)->first();

            if (!$role) {
                return response()->json(['error' => 'Role not found'], 404);
            }

            // Assign the role to the user
            $user->syncRoles([$role->name]);

            return response()->json(['message' => 'Role assigned successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error assigning role'], 500);
        }
    }
}
