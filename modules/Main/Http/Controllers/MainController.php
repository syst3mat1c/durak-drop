<?php

namespace Modules\Main\Http\Controllers;

use App\Services\UI\HeaderService;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Traits\Responseable;
use Modules\Main\Repositories\QuestionRepository;
use Modules\Users\Repositories\UserRepository;

class MainController extends Controller {
    use AuthorizesRequests, Responseable;

    /** @var QuestionRepository */
    protected $questionRepo;

    /** @var UserRepository */
    protected $userRepo;

    /** @var HeaderService */
    protected $headerService;

    /**
     * MainController constructor.
     * @param QuestionRepository $questionRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        QuestionRepository $questionRepository,
        UserRepository $userRepository,
        HeaderService $headerService
    ) {
        $this->questionRepo  = $questionRepository;
        $this->userRepo      = $userRepository;
        $this->headerService = $headerService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function faq() {
        $questions = $this->questionRepo->all();
        return view('main::questions.index', compact('questions'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function top() {
        $users    = $this->userRepo->topGamblers();
        $topThree = $users->take(3);
        $topOther = $users->slice(3);
        return view('main::users.top', compact('users', 'topThree', 'topOther'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function rules() {
        return view('main::pages.rules');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ref() {
        $this->headerService->setTitle('Реферальная система');
        $user = auth()->user();
        return view('main::pages.ref', compact('user'));
    }

    /**
     * @param string $key
     * @return $this
     */
    public function referral(string $key) {
        $user = $this->userRepo->findByReferralKey($key);

        $cookie = $user
            ? cookie('referral_id', $user->id, 43200)
            : cookie('referral_id', false, 0);

        return redirect()->route('index')->withCookie($cookie);
    }
}
