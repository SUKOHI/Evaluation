<?php namespace Sukohi\Evaluation;

use Sukohi\Evaluation\Evaluation;

trait EvaluationTrait {

    private $evaluations_types = [
        '1' => 'like',
        '2' => 'dislike',
        '3' => 'favorite',
        '4' => 'remember'
    ];
    private $evaluation_filter_columns = [
        'user_id',
        'ip',
        'user_agent'
    ];
    private $evaluation_time_ranges = [
        'like' => ['start' => null, 'end' => null],
        'dislike' => ['start' => null, 'end' => null],
        'favorite' => ['start' => null, 'end' => null],
        'remember' => ['start' => null, 'end' => null]
    ];
    protected $evaluations_allow_duplications = [
        'user_id' => false,
        'ip' => false,
        'user_agent' => true
    ];

    // Relationship
    public function evaluations()
    {
        return $this->hasMany('Sukohi\Evaluation\Evaluation', 'parent_id', 'id')->where('parent', __CLASS__);
    }

    // Like
    public function like($user_id)
    {
        return $this->addEvaluation('like', $user_id);
    }

    public function unlike($params)
    {
        return $this->removeEvaluation('like', $params);
    }

    public function clearLike()
    {
        return $this->clearEvaluation('like');
    }

    public function hasLike($params)
    {
        return $this->hasEvaluation('like', $params);
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

    public function undislike($params)
    {
        return $this->removeEvaluation('dislike', $params);
    }

    public function clearDislike()
    {
        return $this->clearEvaluation('dislike');
    }

    public function hasDislike($params)
    {
        return $this->hasEvaluation('dislike', $params);
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

    public function unfavorite($params)
    {
        return $this->removeEvaluation('favorite', $params);
    }

    public function clearFavorite()
    {
        return $this->clearEvaluation('favorite');
    }

    public function hasFavorite($params)
    {
        return $this->hasEvaluation('favorite', $params);
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

    public function unremember($params)
    {
        return $this->removeEvaluation('remember', $params);
    }

    public function clearRemember()
    {
        return $this->clearEvaluation('remember');
    }

    public function hasRemember($params)
    {
        return $this->hasEvaluation('remember', $params);
    }

    public function getRememberCountAttribute()
    {
        return $this->evaluationCount('remember');
    }

    // Main Methods

    private function addEvaluation($type, $user_id)
    {
        $user_id = intval($user_id);
        $ip = request()->ip();
        $user_agent = request()->userAgent();
        $allow_user_id = $this->evaluations_allow_duplications['user_id'];
        $allow_ip = $this->evaluations_allow_duplications['ip'];
        $allow_user_agent = $this->evaluations_allow_duplications['user_agent'];

        if(in_array($type, ['favorite', 'remember'])) {

            $allow_user_id = false;

        }

        if(!$allow_user_id && $this->hasEvaluation($type, ['user_id' => $user_id])) {

            return false;

        } else if(!$allow_ip && $this->hasEvaluation($type, ['ip' => $ip])) {

            return false;

        } else if(!$allow_user_agent && $this->hasEvaluation($type, ['user_agent' => $user_agent])) {

            return false;

        }

        $evaluation = new Evaluation;
        $evaluation->parent = __CLASS__;
        $evaluation->parent_id = $this->id;
        $evaluation->type_id = $this->getTypeId($type);
        $evaluation->user_id = $user_id;
        $evaluation->ip = $ip;
        $evaluation->user_agent = $user_agent;
        $evaluation->save();
    }

    private function removeEvaluation($type, $params)
    {
        if(!is_array($params)) {

            $params = ['user_id' => $params];

        }

        $type_id = $this->getTypeId($type);
        $query = Evaluation::where('parent', __CLASS__)
            ->where('parent_id', $this->id)
            ->where('type_id', $type_id);

        foreach ($this->evaluation_filter_columns as $column) {

            if(array_has($params, $column)) {

                $query->where($column, $params[$column]);

            }

        }

        return $query->delete();
    }

    private function clearEvaluation($type)
    {
        $this->load('evaluations');
        $ids = $this->getTypeEvaluations($type)->pluck('id');
        return Evaluation::whereIn('id', $ids)->delete();
    }

    private function hasEvaluation($type, $params)
    {
        if(!is_array($params)) {

            $params = ['user_id' => $params];

        }

        $this->load('evaluations');
        $evaluations = $this->getTypeEvaluations($type);
        $duplications = $this->evaluations_allow_duplications;

        return $evaluations->contains(function($value) use($params, $duplications) {

            $checking_user_id = array_get($params, 'user_id', -1);
            $checking_ip = array_get($params, 'ip', null);
            $checking_user_agent = array_get($params, 'user_agent', null);

            return (
                $value->user_id == $checking_user_id ||
                $value->ip == $checking_ip ||
                $value->user_agent == $checking_user_agent
            );

        });
    }

    private function evaluationCount($type)
    {
        $this->load('evaluations');
        return $this->getTypeEvaluations($type)->count();
    }

    private function getTypeEvaluations($type) {

        return $this->evaluations->filter(function ($value) use($type) {

            return ($value->type_id == $this->getTypeId($type));

        });

    }

    // Allow

    public function allowEvaluationDuplicationByUserId($boolean) {

        $this->evaluations_allow_duplications['user_id'] = $boolean;
        return $this;

    }

    public function allowEvaluationDuplicationByIpAddress($boolean) {

        $this->evaluations_allow_duplications['ip'] = $boolean;
        return $this;

    }

    public function allowEvaluationDuplicationByUserAgent($boolean) {

        $this->evaluations_allow_duplications['user_agent'] = $boolean;
        return $this;

    }

    public function allowEvaluationDuplications($duplications) {

        foreach ($this->evaluation_filter_columns as $column) {

            if(array_has($duplications, $column)) {

                $this->evaluations_allow_duplications[$column] = $duplications[$column];

            }

        }

    }

    // Scope
    
    public function scopeWhereHasEvaluations($query, $type, $params, $boolean = 'and')
    {
        if(!is_array($params)) {

            $params = ['user_id' => $params];

        }

        $type_id = $this->getTypeId($type);
        $where_method = ($boolean == 'or') ? 'orWhereHas' : 'whereHas';

        return $query->$where_method('evaluations', function($q) use($params, $type_id) {

            foreach ($this->evaluation_filter_columns as $column) {

                if(array_has($params, $column)) {

                    $q->where($column, $params[$column]);

                }

            }

            $q->where('type_id', $type_id);

        });
    }

    public function scopeOrWhereHasEvaluations($query, $type, $params)
    {
        return $this->scopeWhereHasEvaluations($query, $type, $params, 'or');
    }

    public function scopeWhereHasLike($query, $params = [])
    {
        return $this->scopeWhereHasEvaluations($query, 'like', $params);
    }

    public function scopeOrWhereHasLike($query, $params = [])
    {
        return $this->scopeWhereHasEvaluations($query, 'like', $params, 'or');
    }

    public function scopeWhereHasDislike($query, $params = [])
    {
        return $this->scopeWhereHasEvaluations($query, 'dislike', $params);
    }

    public function scopeOrWhereHasDislike($query, $params = [])
    {
        return $this->scopeWhereHasEvaluations($query, 'dislike', $params, 'or');
    }

    public function scopeWhereHasFavorite($query, $params = [])
    {
        return $this->scopeWhereHasEvaluations($query, 'favorite', $params);
    }

    public function scopeOrWhereHasFavorite($query, $params = [])
    {
        return $this->scopeWhereHasEvaluations($query, 'favorite', $params, 'or');
    }

    public function scopeWhereHasRemember($query, $params = [])
    {
        return $this->scopeWhereHasEvaluations($query, 'remember', $params);
    }

    public function scopeOrWhereHasRemember($query, $params = [])
    {
        return $this->scopeWhereHasEvaluations($query, 'remember', $params, 'or');
    }
    
    public function scopeOrderByEvaluation($query, $type, $direction = 'asc')
    {
        $type_id = $this->getTypeId($type);
        $evaluations = Evaluation::select(\DB::raw('COUNT(id) AS COUNT_ID'), 'parent_id')
            ->where('parent', __CLASS__)
            ->where('type_id', $type_id)
            ->groupBy('parent_id')
            ->orderBy('COUNT_ID', $direction)
            ->pluck('parent_id');

        if($evaluations->count() > 0) {

            $evaluation_ids = $evaluations->all();
            $not_in_ids = self::whereNotIn('id', $evaluation_ids)->pluck('id');
            $ids = $evaluations->merge($not_in_ids);
            $query->orderBy(\DB::raw('FIELD(id, '. $ids->implode(',') .')'));

        }

        return $query;
    }

    public function scopeOrderByLike($query, $direction = 'asc')
    {
        return $query->orderByEvaluation('like', $direction);
    }

    public function scopeOrderByDislike($query, $direction = 'asc')
    {
        return $query->orderByEvaluation('dislike', $direction);
    }

    public function scopeOrderByFavorite($query, $direction = 'asc')
    {
        return $query->orderByEvaluation('favorite', $direction);
    }

    public function scopeOrderByRemember($query, $direction = 'asc')
    {
        return $query->orderByEvaluation('remember', $direction);
    }

    // Others
    private function getTypeId($type)
    {
        return array_search($type, $this->evaluations_types);
    }
}
