<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductVersion;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductEloquentRepository implements ProductRepositoryInterface
{
    /**
     * Get products for a specific user with optional sorting.
     */
    public function getProductsForUser(int $userId, string $sortBy, string $sortOrder, int $perPage = 15): LengthAwarePaginator
    {
        $query = Product::where('user_id', $userId)
            ->with('category', 'attributes'); // eager load relationships

        if ($sortBy === 'availability') {
            $query->orderBy('stock', 'desc')
                ->orderBy('price', 'asc');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        return $query->paginate($perPage);
    }

    /**
     * Find a product by ID for a specific user.
     */
    public function findForUser(int $productId, int $userId): ?Product
    {
        return Product::where('id', $productId)
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * Create a new product.
     */
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Update an existing product.
     */
    public function update(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    /**
     * Sync attributes for a product.
     */
    public function syncAttributes(Product $product, array $attributesData): void
    {
        $syncData = [];
        foreach ($attributesData as $attr) {
            $syncData[$attr['attribute_id']] = ['value' => $attr['value']];
        }
        $product->attributes()->sync($syncData);
    }

    /**
     * Get all versions for a given product.
     */
    public function getProductVersions(Product $product): HasMany
    {
        return $product->versions()->orderBy('created_at', 'desc');
    }

    /**
     * Find a specific version for a given product.
     */
    public function findProductVersion(Product $product, int $versionId): ?ProductVersion
    {
        return $product->versions()->find($versionId);
    }
}
