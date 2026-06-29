<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class RoleComposer
{
    protected string $role;

    public function __construct()
    {
        $this->role = Sentinel::getUser()->roles[0]->slug;
    }

    public function compose(View $view)
    {
        $view->with('role', $this->role);
    }
}
