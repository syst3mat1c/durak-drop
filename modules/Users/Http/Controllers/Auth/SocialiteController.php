<?php

namespace Modules\Users\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Traits\Responseable;
use Laravel\Socialite\AbstractUser;
use Modules\Users\Http\Middleware\Auth\SocialiteExistsMiddleware;
use Modules\Users\Repositories\UserRepository;
use Socialite;

class SocialiteController extends Controller
{
    use AuthorizesRequests, Responseable;

    /** @var \Modules\Users\Services\Socialite\UserSocialiteable|null */
    protected $provider;

    /** @var string|null */
    protected $driver;

    /** @var UserRepository */
    protected $userRepo;

    /** @var string */
    protected $redirectRoute = 'index';

    /**
     * SocialiteController constructor.
     * @param Request $request
     * @param UserRepository $userRepository
     */
    public function __construct(Request $request, UserRepository $userRepository)
    {
        $this->middleware(SocialiteExistsMiddleware::class);

        $this->provider = $provider = app('user-socialite')->findByPath($request->route('provider'));

        if ($provider) {
            $this->driver = $provider->driver();
        }

        $this->userRepo = $userRepository;
    }

    /**
     * @return mixed
     */
    public function redirect()
    {
        return Socialite::with($this->driver)->redirect();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback()
    {
        /** @var AbstractUser $socialiteUser */
        $socialiteUser = Socialite::driver($this->driver)->fields(['photo_big'])->stateless()->user();
        $user = $this->userRepo->findOrCreate($socialiteUser, $this->provider->key());

        abort_if(!$user, 500, 'Пользователь не найден и не был создан');

        $this->userRepo->loginAs($user);

        return redirect()->route($this->redirectRoute);
    }
}
