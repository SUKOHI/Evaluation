# Evaluation
A Laravel package to manage evaluation data like LIKE, DISLIKE, FAVORITE and REMEMBER.
(This package is maintained under L5.5.)  

* Note: This package is inspired by [rtconner/laravel-likeable](https://github.com/rtconner/laravel-likeable). Thank you, rtconner!

# Installation

Execute the following composer command.

    composer require sukohi/evaluation:4.*

Register the service provider in app.php.  
If you are in L5.5+ you don't need the above.

    'providers' => [
        //...Others...,  
        Sukohi\Evaluation\EvaluationServiceProvider::class,
    ]
    

# Preparation

To make a table for this package, execute the following commands.

    php artisan vendor:publish --provider="Sukohi\Evaluation\EvaluationServiceProvider"

and

    php artisan migrate

# Usage

Add `EvaluationTrait` to your model like so.

    <?php
    
    namespace App;
    
    use Illuminate\Database\Eloquent\Model;
    use Sukohi\Evaluation\EvaluationTrait;
    
    class Item extends Model
    {
        use EvaluationTrait;
    }

Now you can use new methods from `EvaluationTrait`.

## Like

    $item = \App\Item::find(1);
    
    /*  Add `like`  */
    $item->like($user_id);  // You also can set empty ID.
    
    /*  Remove `like`  */
    $item->unlike($user_id);
    $item->unlike(['user_id' => $user_id]);
    $item->unlike(['ip' => $ip]);
    $item->unlike(['user_agent' => $user_agent]);
    
    /*  Remove all `like`s  */
    $item->clearLike();

    /*  Has  */
    $item->hasLike($user_id)    // True or False
    $item->hasLike(['user_id' => $user_id]);
    $item->hasLike(['ip' => $ip]);
    $item->hasLike(['user_agent' => $user_agent]);
    
    /*  Count  */
    echo $item->like_count;
    
## Dislike

    $item = \App\Item::find(1);
    
    /*  Add `dislike`  */
    $item->dislike($user_id);  // You also can set empty ID.
    
    /*  Remove `dislike`  */
    $item->undislike($user_id);
    $item->undislike(['user_id' => $user_id]);
    $item->undislike(['ip' => $ip]);
    $item->undislike(['user_agent' => $user_agent]);
    
    /*  Remove all `dislike`s  */
    $item->clearDislike();

    /*  Has  */
    $item->hasDislike($user_id)    // True or False
    $item->hasDislike(['user_id' => $user_id]);
    $item->hasDislike(['ip' => $ip]);
    $item->hasDislike(['user_agent' => $user_agent]);
    
    /*  Count  */
    echo $item->dislike_count;
    
## Favorite

    $item = \App\Item::find(1);
    
    /*  Add `favorite`  */
    $item->favorite($user_id);  // You need to set user ID.
    
    /*  Remove `favorite`  */
    $item->unfavorite($user_id);
    $item->unfavorite(['user_id' => $user_id]);
    $item->unfavorite(['ip' => $ip]);
    $item->unfavorite(['user_agent' => $user_agent]);
    
    /*  Remove all `favorite`s  */
    $item->clearFavorite();

    /*  Has  */
    $item->hasFavorite($user_id)    // True or False
    $item->hasFavorite(['user_id' => $user_id]);
    $item->hasFavorite(['ip' => $ip]);
    $item->hasFavorite(['user_agent' => $user_agent]);
    
    /*  Count  */
    echo $item->favorite_count;

## Remember

    $item = \App\Item::find(1);
    
    /*  Add `remember`  */
    $item->remember($user_id);  // You need to set user ID.
    
    /*  Remove `remember`  */
    $item->unremember($user_id);
    $item->unremember(['user_id' => $user_id]);
    $item->unremember(['ip' => $ip]);
    $item->unremember(['user_agent' => $user_agent]);
    
    /*  Remove all `remember`s  */
    $item->clearRemember();

    /*  Has  */
    $item->hasRemember($user_id)    // True or False
    $item->hasRemember(['user_id' => $user_id]);
    $item->hasRemember(['ip' => $ip]);
    $item->hasRemember(['user_agent' => $user_agent]);
    
    /*  Count  */
    echo $item->remember_count;
    
# Where Clause

    $user_id = 1;
    $ip = request()->ip();
    $user_agent = request()->userAgent();

    // Like
    $items = \App\Item::whereHasLike()->get();
    $items = \App\Item::whereHasLike($user_id)->get();
    $items = \App\Item::whereHasLike(['user_id' => $user_id])->get();
    $items = \App\Item::where('id', 1)->orWhereHasLike(['user_id' => $user_id])->get();
    $items = \App\Item::where('id', 1)->orWhereHasLike(['ip' => $ip])->get();
    $items = \App\Item::where('id', 1)->orWhereHasLike(['user_agent' => $user_agent])->get();

    // Dislike
    $items = \App\Item::whereHasDislike()->get();
    $items = \App\Item::whereHasDislike($user_id)->get();
    $items = \App\Item::whereHasDislike(['user_id' => $user_id])->get();
    $items = \App\Item::where('id', 1)->orWhereHasDislike(['user_id' => $user_id])->get();
    $items = \App\Item::where('id', 1)->orWhereHasDislike(['ip' => $ip])->get();
    $items = \App\Item::where('id', 1)->orWhereHasDislike(['user_agent' => $user_agent])->get();

    // Favorite
    $items = \App\Item::whereHasFavorite()->get();
    $items = \App\Item::whereHasFavorite($user_id)->get();
    $items = \App\Item::whereHasFavorite(['user_id' => $user_id])->get();
    $items = \App\Item::where('id', 1)->orWhereHasFavorite(['user_id' => $user_id])->get();
    $items = \App\Item::where('id', 1)->orWhereHasFavorite(['ip' => $ip])->get();
    $items = \App\Item::where('id', 1)->orWhereHasFavorite(['user_agent' => $user_agent])->get();

    // Remember
    $items = \App\Item::whereHasRemember()->get();
    $items = \App\Item::whereHasRemember($user_id)->get();
    $items = \App\Item::whereHasRemember(['user_id' => $user_id])->get();
    $items = \App\Item::where('id', 1)->orWhereHasRemember(['user_id' => $user_id])->get();
    $items = \App\Item::where('id', 1)->orWhereHasRemember(['ip' => $ip])->get();
    $items = \App\Item::where('id', 1)->orWhereHasRemember(['user_agent' => $user_agent])->get();

Or

    $type = 'like'; // like, dislike, favorite or remember
        
    // And
    $items = \App\Item::whereHasEvaluations($type)->get();
    $items = \App\Item::\App\Music::whereHasEvaluations($type, ['user_id' => $user_id])->get();
    $items = \App\Item::\App\Music::whereHasEvaluations($type, ['ip' => $ip])->get();
    $items = \App\Item::\App\Music::whereHasEvaluations($type, ['user_agent' => $user_agent])->get();
    
    // Or
    $items = \App\Item::where('id', 1)->orWhereHasEvaluations($type)->get();
    $items = \App\Item::where('id', 1)->orWhereHasEvaluations($type, ['user_id' => $user_id])->get();
    $items = \App\Item::where('id', 1)->orWhereHasEvaluations($type, ['ip' => $ip])->get();
    $items = \App\Item::where('id', 1)->orWhereHasEvaluations($type, ['user_agent' => $user_agent])->get();


* This feature is from [hikernl](https://github.com/hikernl). Thank you!

# Order By Clause

    $direction = 'asc'; // or desc
    \App\Item::orderByLike($direction)->get();
    \App\Item::orderByDislike($direction)->get();
    \App\Item::orderByFavorite($direction)->get();
    \App\Item::orderByRemember($direction)->get();
    
    // or
    
    \App\Item::orderByEvaluation('like', $direction)->get();
    \App\Item::orderByEvaluation('dislike', $direction)->get();
    \App\Item::orderByEvaluation('favorite', $direction)->get();
    \App\Item::orderByEvaluation('remember', $direction)->get();

## Duplication

If you want to allow users to add duplicate evaluation point(s), please use the following methods.

    $item->allowDuplicationByUserId($boolean);      // Default: false
    
    $item->allowDuplicationByIpAddress($boolean);   // Default: false
    
    $item->allowDuplicationByUserAgent($boolean);   // Default: true
    
Or you also can set the values in your model like so.

        class Item extends Model
        {
            use EvaluationTrait;
            
            protected $evaluations_allow_duplications = [ // <- here
                'user_id' => false,
                'ip' => false,
                'user_agent' => true
            ];
        }
        
Note: `favorite` and `remember` can NOT duplicate user ID per item because they should solely have the point.
        
# License

This package is licensed under the MIT License.

Copyright 2017 Sukohi Kuhoh
