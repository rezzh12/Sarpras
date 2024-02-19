<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {   
        $input = $request->all();
   
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
   
        if(auth()->attempt(array('email' => $input['email'], 'password' => $input['password'])))
        {
            if (auth()->user()->roles_id == 1) {
                return redirect()->route('admin.home');
            }
            elseif (auth()->user()->roles_id == 2) {
                return redirect()->route('user.home');
            }
            else{
                
                return redirect()->back()->withErrors(
                    [
                        'email' => 'Email-Address And Password Are Wrong.'
                    ]);
            }
        }else{
            return redirect()->back()->withErrors(
                [
                    'email' => 'Email-Address And Password Are Wrong.'
                ]);
        }
          
    }
}