<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userFullNames' => 'required|string|max:255',
            'userEmail' => 'required|email|unique:sysusers',
            'userPhoneNumber' => 'required|string|max:13',
            'userPassword' => 'required|string|min:8',
            'userType' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'operation' => 'failure',
                'message' => 'Missing data/invalid data. Please try again.',
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $user = [
            'userFullNames' => $request->input('userFullNames'),
            'userEmail' => $request->input('userEmail'),
            'userPhoneNumber' => $request->input('userPhoneNumber'),
            'userPassword' => Hash::make($request->input('userPassword')),
            'userType' => $request->input('userType'),
        ];

        DB::connection('mysql')->table('sysusers')->insert($user);

        return response()->json(
            [
                'status' => 200,
                'operation' => 'success',
                'message' => 'User registered successfully'
            ],
            200
        );
    }
}
