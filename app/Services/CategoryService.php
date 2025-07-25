<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Services\Contracts\CategoryServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CategoryService implements CategoryServiceInterface
{
    public function __construct(protected CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function createCategory(array $data): Category
    {
        if (isset($data['parent_id'])) {
            $parentCategory = $this->categoryRepository->find($data['parent_id']);
            if (!$parentCategory) {
                throw ValidationException::withMessages(['parent_id' => 'The specified parent category does not exist.']);
            }
        }

        //TODO: add duplicate name check

        return $this->categoryRepository->create($data);
    }

    public function getCategoryChildren(int $categoryId): Category
    {
        $category = $this->categoryRepository->find($categoryId);
        if (!$category) {
            throw new ModelNotFoundException('Category not found.');
        }
        return $category;
    }

    public function getCategoryDescendants(int $categoryId): Category
    {
        $category = $this->categoryRepository->find($categoryId);
        if (!$category) {
            throw new ModelNotFoundException('Category not found.');
        }
        return $category;
    }

    public function getCategoryAncestors(int $categoryId): Category
    {
        $category = $this->categoryRepository->find($categoryId);
        if (!$category) {
            throw new ModelNotFoundException('Category not found.');
        }
        return $category;
    }

    public function moveCategory(Category $category, ?int $newParentId): Category
    {
        if ($newParentId !== null) {
            $newParent = $this->categoryRepository->find($newParentId);
            if (!$newParent) {
                throw ValidationException::withMessages(['new_parent_id' => 'The specified new parent category does not exist.']);
            }

            if (Category::ancestorsAndSelf($newParent->id)->pluck('id')->contains($category->id)) {
                throw ValidationException::withMessages(['new_parent_id' => 'Cannot move a category under its own descendant.']);
            }
        }

        if (!$this->categoryRepository->move($category, $newParentId)) {
            throw new \RuntimeException('Failed to move category.');
        }

        $category->refresh();
        return $category;
    }
}
