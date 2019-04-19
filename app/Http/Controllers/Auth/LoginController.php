<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use Auth;
use Response;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use App\Http\Resources\User as UserRessource;
use App\Support\Google2FAAuthenticator;
use PragmaRX\Google2FALaravel\Support\Authenticator;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    
    
    protected function authenticated(Request $request, $user)
{
    if (is_null($user->passwordSecurity)||$user->passwordSecurity->enabled==false) {
      if(!empty($request->input("ajaxLogin"))){
        return new UserRessource(Auth::user());
      }
      return redirect()->intended($this->redirectTo);
    }
    Auth::logout();
    $request->session()->put('user-id', $user->id);
    if(!empty($request->input("ajaxLogin"))){
      return response('{"twofactor":true}', 200);
    }
    $authenticator = app(Google2FAAuthenticator::class)->boot($request);
    return $authenticator->makeRequestOneTimePasswordResponse();
}
    
  /*  public function authenticated(Request $request, $user)
    {
    //  var_dump($request->all());
    //  exit();
        if (!empty(Auth::id())) {
          //echo "logged in";
          UserSettings::firstOrCreate(['user_id' => Auth::id()]);
          //return response('{"success"}', 200);
          return new UserSettingsRessource(UserSettings::where('user_id', '=' ,Auth::id())->firstOrFail());
          //  return redirect('/home');
        } else {
        //  echo "not logged in";
          return response()->json(["data"=>["error_msg"=>"Login failed"]],403);
        }
    }*/
    /**
     * Custom logout function with no redirect if ajax.
     *
     * @return void
     */
    public function logout(Request $request) {
        $this->guard()->logout();
        $request->session()->invalidate();
        (new Authenticator(request()))->logout();
        if($request->ajax()) {
            return Response::json(array(
                'success' => true,
                'data'   => 'Logout success'
            ));
        }
        else {
          return Response::json(array(
              'success' => false,
              'data'   => 'Logout failed'
          ));
        }
    }
    public function login(Request $request)
    {
    //  var_dump($request->all());
    //  die();  
      //  $this->validateLogin($request);
      $loginSuccess = false;
      if (Auth::attempt(['email' => $request->input("email"), 'password' => $request->input("password")])) {
        $loginSuccess = true;
      } else {
        if(Auth::attempt(['username' => $request->input("email"), 'password' => $request->input("password")])){
          $loginSuccess = true;
        }
      }
      //  var_dump($request->all());
      //  exit();
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($loginSuccess) {
          //return response('{"success"}', 200);
        //  echo "aja$loginSuccessxLogin?";
        //  echo $request->input("ajaxLogin");
          if(!empty($request->input("ajaxLogin"))){
            $user = Auth::user();
          if (is_null($user->passwordSecurity)||$user->passwordSecurity->enabled==false) {
          
            return new UserRessource(Auth::user());
          } else {
            Auth::logout();
            $request->session()->put('user-id', $user->id);
              return response('{"twofactor":true}', 200);
          }
        } else {
          return $this->sendLoginResponse($request);
        }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        if(!empty($request->input("ajaxLogin"))){
          return response('{"login":invalid}', 401);
        } else {
          return $this->sendFailedLoginResponse($request);
        }
        
    }
}
