<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Traits\Responseable;
use Modules\Admin\Http\Requests\Question\QuestionRequest;
use Modules\Main\Entities\Question;
use Modules\Main\Repositories\QuestionRepository;

class QuestionController extends Controller
{
    use AuthorizesRequests, Responseable;

    /** @var QuestionRepository */
    protected $questionRepo;

    /**
     * QuestionController constructor.
     * @param QuestionRepository $questionRepository
     */
    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepo = $questionRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $questions = $this->questionRepo->all();
        return view('admin::modules.questions.index', compact('questions'));
    }

    /**
     * @param Question $question
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Question $question)
    {
        return view('admin::modules.questions.create', compact('question'));
    }

    /**
     * @param QuestionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(QuestionRequest $request)
    {
        $this->questionRepo->store($request->only($request->validated));
        return $this->routeSuccess('admin.questions.index');
    }

    /**
     * @param Question $question
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Question $question)
    {
        return view('admin::modules.questions.edit', compact('question'));
    }

    /**
     * @param Question $question
     * @param QuestionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Question $question, QuestionRequest $request)
    {
        $this->questionRepo->update($question, $request->only($request->validated));
        return $this->routeSuccess('admin.questions.index');
    }

    /**
     * @param Question $question
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Question $question)
    {
        $this->questionRepo->delete($question);
        return $this->routeSuccess('admin.questions.index');
    }
}
