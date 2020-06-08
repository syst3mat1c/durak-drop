<?php

namespace Modules\Users\Http\Controllers\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Modules\Users\Entities\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Modules\Users\Http\Requests\Auth\UserRegisterRequest;
use Modules\Users\Repositories\UserRepository;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectRoute = 'main.index';

    /** @var UserRepository */
    protected $userRepo;

    /**
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('guest');
        $this->userRepo = $userRepository;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('users::auth.register');
    }

    /**
     * @param UserRegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register(UserRegisterRequest $request)
    {
        event(new Registered($user = $this->create($request->only($request->validated))));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect()->route($this->redirectRoute);
    }

    /**
     * @param Request $request
     * @param $user
     */
    protected function registered(Request $request, $user) {}

    /**
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return $this->userRepo->store($data);
    }
}
