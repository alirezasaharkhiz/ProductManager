<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface AttributeRepositoryInterface
{
    /**
     * Get all attributes.
     *
     * @return Collection
     */
    public function getAllAttributeIds(): Collection;
}
