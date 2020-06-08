<?php

namespace Modules\Main\Http\Controllers;

use App\Services\UI\HeaderService;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Traits\Responseable;
use Modules\Main\Repositories\QuestionRepository;

class QuestionController extends Controller
{
    use AuthorizesRequests, Responseable;

    /** @var QuestionRepository */
    protected $questionRepo;

    /** @var HeaderService */
    protected $headerService;

    /**
     * QuestionController constructor.
     * @param QuestionRepository $questionRepository
     * @param HeaderService $headerService
     */
    public function __construct(QuestionRepository $questionRepository, HeaderService $headerService)
    {
        $this->questionRepo = $questionRepository;
        $this->headerService = $headerService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->headerService->setTitle('F.A.Q');
        $questions = $this->questionRepo->all();
        return view('main::questions.index', compact('questions'));
    }
}
