<?php

namespace App\Services\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use RuntimeException;

interface CategoryServiceInterface
{
    /**
     * Create a new category.
     *
     * @param array $data
     * @return Category
     * @throws ValidationException
     */
    public function createCategory(array $data): Category;

    /**
     * Get immediate children of a category.
     *
     * @param int $categoryId
     * @return Category
     * @throws ModelNotFoundException
     */
    public function getCategoryChildren(int $categoryId): Category;

    /**
     * Get all descendants (deep-nested) of a category.
     *
     * @param int $categoryId
     * @return Category
     * @throws ModelNotFoundException
     */
    public function getCategoryDescendants(int $categoryId): Category;

    /**
     * Get all ancestors of a category.
     *
     * @param int $categoryId
     * @return Category
     * @throws ModelNotFoundException
     */
    public function getCategoryAncestors(int $categoryId): Category;

    /**
     * Move a category to a new parent.
     *
     * @param Category $category
     * @param int|null $newParentId
     * @return Category
     * @throws ValidationException|RuntimeException
     */
    public function moveCategory(Category $category, ?int $newParentId): Category;
}
