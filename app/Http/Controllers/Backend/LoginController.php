<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\Admin;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class LoginController extends Controller
{
    /**
     * Created By :Ankita Singh
     * Created On : 31 Jan 2022
     * Uses : This will load login view.
     */
    public function index()
    {
        return view('backend/auth/login');
    }

    /**
     * Created By :Ankita Singh
     * Created On : 31 Jan 2022
     * Uses : This will login admin user.
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        \Log::info("Logging in, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {
            $validationErrors = $this->validateLogin($request);
            if (count($validationErrors)) {
                \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                return redirect()->back()->withErrors(array("msg" => implode("\n", $validationErrors->all())));
            }

            $response = Admin::with('role')->where([['email', $request->email], ['password', md5($request->email . $request->password)]])->get();
            if (!count($response)) {
                \Log::error("User not found with this email id and password.");
                return redirect()->back()->withErrors(array("msg" => "Invalid login credentials"));
            } else {
                if ($response[0]['status'] == 1) {
                    \Log::info("Login Successful!");
                    $data = array(
                        "id" => $response[0]['id'],
                        "name" => $response[0]['admin_name'],
                        "email" => $request->email,
                        "role_id" => $response[0]['role_id'],
                        "permissions" => $response[0]['role']['permission']
                    );
                    $request->session()->put('data', $data);
                    return redirect('webadmin/dashboard');
                } else {
                    \Log::error("Account Suspended.");
                    return redirect()->back()->withErrors(array("msg" => "Your account is deactivated."));
                }
            }
        } catch (\Exception $e) {
            \Log::error("Login failed: " . $e->getMessage());
            return redirect()->back()->withErrors(array("msg" => "Something went wrong"));
        }
    }
    /**
     * Validates input login
     *
     * @param Request $request
     * @return Response
     */
    public function validateLogin(Request $request)
    {
        return \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ])->errors();
    }

    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\View\View
     */
    public function forgotPassword()
    {
        return view('backend/auth/forgot-password');
    }


    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function forgotPasswordStore(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email',
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        $email = strtolower($request->email);
        $token = Str::random(60);
        DB::table('password_resets')->updateOrInsert(
            [
                'email'   => $email,
            ],
            [
                'email' => $email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]
        );

        // $action_link = route('password.reset', ['token' => $token, 'email' => $email]);
        $action_link =  URL::temporarySignedRoute(
            'password.reset',
            now()->addHours(48),
            ['token' => $token, 'email' => $email]
        );
        $body = "We received a request to reset the passoword for PACKARMA account associated with " . $email . " You can reset the password by clicking the link below";
        Mail::send('backend/auth/email-forgot', ['link' => $action_link, 'body' => $body], function ($message) use ($email) {
            $message->from('crm2@mypcot.com', 'PACKARMA');
            $message->to($email, 'PACKARMA')->subject('Reset Password');
        });
        return back()->with('status', __('passwords.sent'));
    }

    /**
     * Display the password reset view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function passwordReset(Request $request)
    {
        $email = strtolower($request->email);
        $check_token = DB::table('password_resets')->where(['email' => $email])->first();
        if (!$check_token) {
            return view('backend/auth/page-not-found');
        }
        return view('backend/auth/reset-password', ['request' => $request]);
    }


    /**
     * Handle an incoming new password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => 'required|email|exists:admins,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $email = strtolower($request->email);
        $token = $request->token;
        $check_token = DB::table('password_resets')->where(['email' => $email, 'token' => $token])->first();
        if (!$check_token) {
            // return back()->withErrors('status', __('passwords.token'));
            return  back()->withInput($request->only('email'))
                ->withErrors(['email' => __('passwords.token')]);
        } else {

            Admin::where('email', $email)->update([
                'password' => md5($email . $request->password),
            ]);
            DB::table('password_resets')->where(['email' => $email])->delete();
            return redirect()->route('login')->with('status', __('passwords.reset'));
        }
    }
}
