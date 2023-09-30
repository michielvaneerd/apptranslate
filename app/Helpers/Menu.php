<?php

namespace App\Helpers;

use App\Models\User;

class Menu
{
    public static function getMenu(User $user): array
    {
        $items = [];
        //if ($user->can('users')) {
            $items[] = [
                'title' => __('app.users'),
                'route' => route('users.index')
            ];
        //}
        return $items;
    }
}
