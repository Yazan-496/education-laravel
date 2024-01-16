<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use App\Http\Validation\EditUserValidation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Validation\SignupValidation;
use App\Http\Validation\LoginValidation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class AuthController extends Controller
{
    public function storeUser(Request $request)
    {
        $data = $request->all();
        $validator = SignupValidation::validate($data);

        if ($validator) {
            return response()->json([
                'errors' => $validator
            ], 422);
        }

        $role = $data['role_id'];
        $roles = Role::where('id', $role)->first();

        if ($roles) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'role_id' => $data['role_id'],
                'password' => Hash::make($data['password']),
                'real' => $data['password'],
                'api_token' => Str::random(80),
                'photo' => $data['photo'],
            ]);

            return response()->json($user, 200);
        } else {
            return response()->json("role not found", 402);
        }
    }

    public function loginUser(Request $request)
    {
        $data = $request->all();
        $validator = LoginValidation::validate($data);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        $userData = User::select()
            ->where('email', '=', $data['email'])
            ->with(['role.permissions' => function ($query) {
                $query->pluck('name');
            }])
            ->first();

        if ($userData) {
            $permissions = $userData->role->permissions->pluck('name')->toArray();
        } else {
            $permissions = [];
        }
        $userData['permissions'] = $permissions;
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            // Authentication successful
            return response()->json(['message' => 'Login successful', 'user' => $userData]);
        } else {
            // Authentication failed
            return response()->json(['errors' => ['Invalid credentials']], 401);
        }
    }

    public function authenticate(Request $request)
    {
        $token = $request->input('token');
        // Find the user with the given token
        $user = User::where('api_token', $token)->first();
        // Check if the user exists
        if ($user) {
            // Log in the user
            Auth::login($user);
            // Redirect or return a response indicating successful authentication
            return $user;
        } else {
            // Return a response indicating failed authentication
            return response()->json(['message' => 'Invalid token'], 401);
        }
    }

    public function deleteUser($id)
    {
        $userData = User::find($id);
        if ($userData) {
            User::find($id)->delete();
            return response()->json(['message' =>  "User $userData->name has been successfully deleted", "user" => $userData], 200);
        } else {
            // Authentication failed
            return response()->json(['Error' => "Delete User $userData->name Failed", 'message' => 'User Not Found'], 200);
        }
    }
    public function deleteUsers(Request $request)
    {
        $data = $request->all();
        $ids = $data['ids'];

        $deletedUsers = [];

        foreach ($ids as $id) {
            $userData = User::find($id);

            if ($userData) {
                $userData->delete();
                $deletedUsers[] = $userData->name; // Store the deleted user's name
            }
        }

        if (!empty($deletedUsers)) {
            return response()->json(['message' => 'Users deleted successfully', 'deleted_users' => $deletedUsers], 200);
        } else {
            // No users were found for the provided IDs
            return response()->json(['error' => 'Delete Failed', 'message' => 'No users found for the provided IDs'], 200);
        }
    }


    public function getUser($email)
    {
        $userData = User::where(function ($query) use ($email) {
            $query->orWhere('email', 'like', '%' . $email . '%');
        })->get()->first();
        if ($userData) {
            return response()->json(["user" => $userData], 200);
        } else {
            return response()->json(['message' => 'User Not Found'], 200);
        }
    }

    public function EditUser(Request $request)
    {
        $data = $request->all();
//        $validator = SignupValidation::validate($data); // 1
        $validator = EditUserValidation::validate($data); // 2

        if ($validator) {
            return response()->json([
                'errors' => $validator
            ], 422);
        }
        $id = $data['id'];
        $userData = User::find($id);
        if (isset($data['name'])) {
            $userData->name = $data['name'];
        }
        if (isset($data['email']) && $data['email'] !== $userData->email) {
            $validator = Validator::make($data, [
                'email' => 'required|string|email|max:255|unique:users',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => ['The email has already been taken.']
                ], 422);
            }
            $userData->email = $data['email'];
            $userData->email = $data['email'];
        }
        if (isset($data['password'])) {
            $userData->password = Hash::make($data['password']);
        }
        if (isset($data['password'])) {
            $userData->real = $data['password'];
        }
        if (isset($data['photo'])) {
            $userData->photo = $data['photo'];
        }
        if (isset($data['role_id'])) {
            $userData->role_id = $data['role_id'];
        }
        $userData->save();
        if ($userData) {
            return response()->json(["user" => $userData], 200);
        } else {
            return response()->json(['message' => 'User Not Found'], 200);
        }
    }

    public function getAllUsers()
    {
        $users = User::with('role')->get();
        if ($users) {
            return response()->json($users, 200);
        } else {
            return response()->json(['message' => 'User Not Found'], 200);
        }
    }

    public function getAllRoles()
    {
        $roles = Role::All();
        if ($roles) {
            return response()->json($roles, 200);
        } else {
            return response()->json(['message' => 'roles Not Found'], 200);
        }
    }
}
