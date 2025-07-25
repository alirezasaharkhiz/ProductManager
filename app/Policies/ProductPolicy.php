<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use App\Services\Contracts\ProductServiceInterface;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    public function __construct(private ProductServiceInterface $productService)
    {
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Product $product): bool
    {
        return $user->id === $product->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Product $product): bool
    {
        return $user->id === $product->user_id;
    }

    public function delete(User $user, Product $product): bool
    {
        return false;
    }

    public function restore(User $user, Product $product): bool
    {
        return false;
    }

    public function forceDelete(User $user, Product $product): bool
    {
        return false;
    }
}
