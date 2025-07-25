<?php

namespace App\Repositories;

use App\Models\Attribute;
use App\Repositories\Contracts\AttributeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AttributeEloquentRepository implements AttributeRepositoryInterface
{
    /**
     * Get all attributes (to validate attribute_id in product creation/update).
     */
    public function getAllAttributeIds(): Collection
    {
        return Attribute::all();
    }
}
