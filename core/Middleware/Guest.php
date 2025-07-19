<?php

namespace Framework\Middleware;

class Guest
{

    public function handle(): void
    {
        if (check_auth()) {
            response()->redirect(base_url('/recipes'));
        }
    }
}
