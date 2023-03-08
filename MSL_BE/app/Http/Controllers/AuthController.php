<?php
namespace App\Http\Controllers;
 
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
 
class AuthController extends Controller
{   
   
  public function login(Request $request){
        $credentials = ['email' => $request->data["email"], 'password' => $request->data["password"]];   
        if (Auth::attempt($credentials)) {
            $token = hash('sha256', Str::random(60));
            Auth::user()->forceFill([
                "remember_token" => $token
            ])->save();
            return ['token' => $token, "updated_at" => Auth::user()->updated_at];
        }
        else{
            return response('Nesprávné přihlašovací údaje.', 401);
        }
  }
  }

