<?php

namespace Modules\Main\Repositories;

use Illuminate\Support\Collection;
use Modules\Main\Entities\Question;

class QuestionRepository
{
    /**
     * @return Collection
     */
    public function all()
    {
        return Question::defaultOrder()->get();
    }

    /**
     * @param array $data
     * @return Question
     */
    public function store(array $data)
    {
        return Question::create($data);
    }

    /**
     * @param Question $question
     * @param array $data
     * @return bool
     */
    public function update(Question $question, array $data)
    {
        return $question->update($data);
    }

    /**
     * @param Question $question
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Question $question)
    {
        return $question->delete();
    }
}
