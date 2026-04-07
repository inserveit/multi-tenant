<?php

/*
 * This file is part of the hyn/multi-tenant package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/hyn/multi-tenant
 */

namespace Hyn\Tenancy\Abstracts;

use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class SystemModel extends Model
{
    use UsesSystemConnection;

    /**
    * Laravel 13 resolves collection classes recursively through model parents.
    * Because this base model is abstract, recursion can try to instantiate it.
    * Returning null keeps the default Eloquent collection without parent recursion.
    *
    * @return class-string<Collection>|null
    */
    public function resolveCollectionFromAttribute()
    {
        return null;
    }
}
