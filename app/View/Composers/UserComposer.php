<?php

namespace App\View\Composers;

use App\Models\User;
use Illuminate\View\View;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class UserComposer
{
    protected User $user;

    public function __construct()
    {
        $this->user = Sentinel::getUser();
    }

    public function compose(View $view)
    {
        $view->with('user', $this->user);
    }
}
