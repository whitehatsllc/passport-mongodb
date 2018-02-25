<?php

namespace Whitehatsllc\PassportMongodb\Http\Controllers;

use Whitehatsllc\PassportMongodb\Passport;

class ScopeController
{
    /**
     * Get all of the available scopes for the application.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return Passport::scopes();
    }
}
