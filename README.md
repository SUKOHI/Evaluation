# Evaluation
A Laravel package to manage evaluation data like LIKE, DISLIKE, FAVORITE and REMEMBER.
(This package is for Laravel 5+.)  

* Note: This package is inspired by [rtconner/laravel-likeable](https://github.com/rtconner/laravel-likeable). Thank you, rtconner!

# Installation

Execute the following composer command.

    composer require sukohi/evaluation:3.*

Register the service provider & alias in app.php

    'providers' => [
        ...Others...,  
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

    $user_id = 1;
    $item = \App\Item::find(1);
    
    $item->like($user_id);      // Add `like` a specific record
    $item->unlike($user_id);    // Remove a specific `like`
    $item->clearLike();         // Remove all `likes` of a specific record

    if($item->hasLike($user_id)) {  // Check if a record has `like` of a user

        echo 'Has it';

    }

    echo $item->likeCount;      // Get count
    
## Dislike

    $user_id = 1;
    $item = \App\Item::find(1);
    
    $item->dislike($user_id);       // Add `dislike` a specific record
    $item->undislike($user_id);     // Remove a specific `dislike`
    $item->clearDislike();          // Remove all `dislikes` of a specific record

    if($item->hasDislike($user_id)) {  // Check if a record has `dislike` of a user

        echo 'Has it';

    }

    echo $item->dislikeCount;      // Get count

## Favorite

    $user_id = 1;
    $item = \App\Item::find(1);
    
    $item->favorite($user_id);       // Add `favorite` a specific record
    $item->unfavorite($user_id);     // Remove a specific `favorite`
    $item->clearFavorite();          // Remove all `favorite` of a specific record

    if($item->hasFavorite($user_id)) {  // Check if a record has `favorite` of a user

        echo 'Has it';

    }

    echo $item->favoriteCount;      // Get count

## Remember

    $user_id = 1;
    $item = \App\Item::find(1);
    
    $item->remember($user_id);       // Add `remember` a specific record
    $item->unremember($user_id);     // Remove a specific `remember`
    $item->clearRemember();          // Remove all `remember` of a specific record

    if($item->hasRemember($user_id)) {  // Check if a record has `remember` of a user

        echo 'Has it';

    }

    echo $item->rememberCount;      // Get count

# Where Clause

    $type = 'like'; // like, dislike, favorite or remember
    $user_id = 1;

    // And
    $items = \App\Item::whereHasEvaluations($type)->get();
    $items = \App\Item::whereHasEvaluations($type, $user_id)->get();    // with User ID
    
    // Or
    $items = \App\Item::where('id', 1)->orWhereHasEvaluations($type)->get();
    $items = \App\Item::where('id', 1)->orWhereHasEvaluations($type, $user_id)->get();    // with User ID

or  

    $user_id = 1;

    // Like
    $items = \App\Item::whereHasLike()->get();
    $items = \App\Item::whereHasLike($user_id)->get();
    $items = \App\Item::where('id', 1)->orWhereHasLike($user_id)->get();

    // Dislike
    $items = \App\Item::whereHasDislike()->get();
    $items = \App\Item::whereHasDislike($user_id)->get();
    $items = \App\Item::where('id', 1)->orWhereHasDislike($user_id)->get();

    // Favorite
    $items = \App\Item::whereHasFavorite()->get();
    $items = \App\Item::whereHasFavorite($user_id)->get();
    $items = \App\Item::where('id', 1)->orWhereHasFavorite($user_id)->get();

    // Remember
    $items = \App\Item::whereHasRemember()->get();
    $items = \App\Item::whereHasRemember($user_id)->get();
    $items = \App\Item::where('id', 1)->orWhereHasRemember($user_id)->get();

* This feature is from [hikernl](https://github.com/hikernl). Thank you!

# Order By Clause

    $direction = 'asc'; // or desc
    \App\Item::orderByEvaluation('like', $direction)->get();
    \App\Item::orderByEvaluation('dislike', $direction)->get();
    \App\Item::orderByEvaluation('favorite', $direction)->get();
    \App\Item::orderByEvaluation('remember', $direction)->get();

    // or
    
    \App\Item::orderByLike($direction)->get();
    \App\Item::orderByDislike($direction)->get();
    \App\Item::orderByFavorite($direction)->get();
    \App\Item::orderByRemember($direction)->get();

# Delete All Evaluations by User ID

    $user_id = 1;

    if(\App\Item::removeAllEvaluationsByUserId($user_id)) {

        echo 'Deleted!';

    }

I suppose you use this method when a user delete his/her account. 

# License

This package is licensed under the MIT License.

Copyright 2016 Sukohi Kuhoh
