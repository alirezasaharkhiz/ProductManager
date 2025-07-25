<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryEloquentRepository implements CategoryRepositoryInterface
{
    /**
     * Find a category by ID.
     */
    public function find(int $id): ?Category
    {
        return Category::find($id);
    }

    /**
     * Create a new category.
     */
    public function create(array $data): Category
    {
        return Category::create($data);
    }

    /**
     * Move a category to a new parent.
     */
    public function move(Category $category, ?int $newParentId): bool
    {
        if ($newParentId === null) {
            //Using kalnoy/nestedset package
            return $category->makeRoot()->save();
        } else {
            $newParent = $this->find($newParentId);
            if (!$newParent) {
                return false;
            }
            //Using kalnoy/nestedset package
            return $category->appendToNode($newParent)->save();
        }
    }
}
