<?php namespace Sukohi\Evaluation;

use Sukohi\Evaluation\Evaluation;

trait EvaluationTrait {

    private $evaluations_init_flag = false;
    private $evaluations_user_ids = [];
    private $evaluations_types = [
        '1' => 'like',
        '2' => 'dislike',
        '3' => 'favorite',
        '4' => 'remember'
    ];

    // Relationships

    public function evaluations()
    {
        return $this->hasMany('Sukohi\Evaluation\Evaluation', 'parent_id', 'id')->where('model', __CLASS__);
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

    public function hasDislike($user_id)
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
        $this->evaluationInit();

        if($this->hasEvaluation($type, $user_id)) {

            return false;

        }

        $this->evaluations_user_ids[$type][$user_id] = true;
        $evaluation = new Evaluation;
        $evaluation->model = __CLASS__;
        $evaluation->parent_id = $this->id;
        $evaluation->type_id = $this->getTypeId($type);
        $evaluation->user_id = $user_id;
        $evaluation->save();

    }

    private function removeEvaluation($type, $user_id)
    {
        $this->evaluationInit();
        $this->evaluations_user_ids[$type][$user_id] = false;
        return $this->whereEvaluationCommon($type, $user_id)->delete();
    }

    private function clearEvaluation($type)
    {
        $this->evaluationInit();

        foreach ($this->evaluations_user_ids[$type] as $user_id => $boolean) {

            $this->evaluations_user_ids[$type][$user_id] = false;

        }

        return $this->whereEvaluationCommon($type)->delete();
    }

    private function hasEvaluation($type, $user_id)
    {
        $this->evaluationInit();
        return array_get($this->evaluations_user_ids[$type], $user_id, false);
    }

    private function evaluationCount($type)
    {
        $this->evaluationInit();
        $evaluations = array_where($this->evaluations_user_ids[$type], function($user_id, $value){

            return $value;

        });
        return count($evaluations);
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

    private function evaluationInit()
    {
        if(!$this->evaluations_init_flag) {

            $this->load('evaluations');

            foreach ($this->evaluations_types as $type) {

                $this->evaluations_user_ids[$type] = [];

            }
            
            $this->evaluations_init_flag = true;
            $evaluations = $this->evaluations;

            if($evaluations->count() > 0) {

                foreach ($evaluations as $evaluation) {

                    $type_id = $evaluation->type_id;
                    $type = $this->evaluations_types[$type_id];
                    $user_id = $evaluation->user_id;
                    $this->evaluations_user_ids[$type][$user_id] = true;

                }

            }

        }

    }

    private function getTypeId($type)
    {
        return array_search($type, $this->evaluations_types);
    }

}