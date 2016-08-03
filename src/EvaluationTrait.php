<?php namespace Sukohi\Evaluation;

use Sukohi\Evaluation\Evaluation;

trait EvaluationTrait {

    // Relationships

    public function evaluations()
    {
        return $this->hasMany('Sukohi\Evaluation\Evaluation', 'parent_id', 'id');
    }

    // Like

    public function like($user_id)
    {
        return $this->addEvaluation('like', $user_id);
    }

    public function unlike($user_id)
    {
        return $this->removeEvaluation('like', $user_id);
    }

    public function clearLike()
    {
        return $this->clearEvaluation('like');
    }

    public function hasLike($user_id)
    {
        return $this->hasEvaluation('like', $user_id);
    }

    public function getLikeCountAttribute()
    {
        return $this->evaluationCount('like');
    }

    // Dislike

    public function dislike($user_id)
    {
        return $this->addEvaluation('dislike', $user_id);
    }

    public function undislike($user_id)
    {
        return $this->removeEvaluation('dislike', $user_id);
    }

    public function clearDislike()
    {
        return $this->clearEvaluation('dislike');
    }

    public function hasDisike($user_id)
    {
        return $this->hasEvaluation('dislike', $user_id);
    }

    public function getDislikeCountAttribute()
    {
        return $this->evaluationCount('dislike');
    }

    // Favorite

    public function favorite($user_id)
    {
        return $this->addEvaluation('favorite', $user_id);
    }

    public function unfavorite($user_id)
    {
        return $this->removeEvaluation('favorite', $user_id);
    }

    public function clearFavorite()
    {
        return $this->clearEvaluation('favorite');
    }

    public function hasFavorite($user_id)
    {
        return $this->hasEvaluation('favorite', $user_id);
    }

    public function getFavoriteCountAttribute()
    {
        return $this->evaluationCount('favorite');
    }

    // Remember

    public function remember($user_id)
    {
        return $this->addEvaluation('remember', $user_id);
    }

    public function unremember($user_id)
    {
        return $this->removeEvaluation('remember', $user_id);
    }

    public function clearRemember()
    {
        return $this->clearEvaluation('remember');
    }

    public function hasRemember($user_id)
    {
        return $this->hasEvaluation('remember', $user_id);
    }

    public function getRememberCountAttribute()
    {
        return $this->evaluationCount('remember');
    }

    // Main Methods

    private function addEvaluation($type, $user_id)
    {
        if($this->hasEvaluation($type, $user_id)) {

            return false;

        }

        $evaluation = new Evaluation;
        $evaluation->model = __CLASS__;
        $evaluation->parent_id = $this->id;
        $evaluation->type_id = $this->getTypeId($type);
        $evaluation->user_id = $user_id;
        $evaluation->save();

    }

    private function removeEvaluation($type, $user_id)
    {
        return $this->whereEvaluationCommon($type, $user_id)->delete();
    }

    private function clearEvaluation($type)
    {
        return $this->whereEvaluationCommon($type)->delete();
    }

    private function hasEvaluation($type, $user_id)
    {
        return $this->whereEvaluationCommon($type, $user_id)->exists();
    }

    private function evaluationCount($type)
    {
        $count = 0;
        $type_id = $this->getTypeId($type);

        if($this->evaluations->count() > 0) {

            foreach ($this->evaluations as $evaluation) {

                if($evaluation->type_id == $type_id) {

                    $count++;

                }

            }

        }

        return $count;
    }

    private function whereEvaluationCommon($type, $user_id = -1)
    {
        $type_id = $this->getTypeId($type);
        $query = Evaluation::where('model', __CLASS__)
            ->where('parent_id', $this->id)
            ->where('type_id', $type_id);

        if($user_id > 0) {

            $query->where('user_id', $user_id);

        }

        return $query;
    }

    // Others

    private function getTypeId($type)
    {
        $type_ids = [
            '1' => 'like',
            '2' => 'dislike',
            '3' => 'favorite',
            '4' => 'remember'
        ];
        return array_search($type, $type_ids);
    }

}