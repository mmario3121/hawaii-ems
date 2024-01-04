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

    //create user
    public function store(StoreUserRequest $request)
{
    $validated = $request->validated();
    
    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
        'email_verified_at' => now(),
    ])->assignRole([$validated['role']]);

    return new JsonResponse([
        'message' => 'success',
    ], Response::HTTP_OK);
}

public function update(UpdateUserRequest $request)
    {
        $validated = $request->validated();
        $user = User::find($validated['id']);
        $user->update($validated);
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    public function destroy(Request $request)
    {
        $user = User::find($request->id);
        $user->delete();
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    //set image

    public function setImage(Request $request)
    {
        $user = User::find($request->id);
        //save image in public folder
        $image = $request->file('image');
        $imageName = time() . '.' . $image->extension();
        $image->move(public_path('images'), $imageName);
        $user->image = $imageName;
        $user->save();
        return new JsonResponse([
            'message' => 'success',
        ], Response::HTTP_OK);
    }
}
