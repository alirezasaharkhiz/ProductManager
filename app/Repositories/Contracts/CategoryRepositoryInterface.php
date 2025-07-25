<?php

namespace App\Repositories\Contracts;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    /**
     * Find a category by ID.
     *
     * @param int $id
     * @return Category|null
     */
    public function find(int $id): ?Category;

    /**
     * Create a new category.
     *
     * @param array $data
     * @return Category
     */
    public function create(array $data): Category;

    /**
     * Move a category to a new parent.
     *
     * @param Category $category
     * @param int|null $newParentId
     * @return bool
     */
    public function move(Category $category, ?int $newParentId): bool;
}
