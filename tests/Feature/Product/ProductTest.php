<?php

namespace Product;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Category;
use App\Models\Attribute;
use Laravel\Sanctum\Sanctum;
use Database\Seeders\TestDatabaseSeeder;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Category $category;
    protected array $attributeIds;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(TestDatabaseSeeder::class);
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
        $this->attributeIds = Attribute::all()->pluck('id')->toArray();

        Sanctum::actingAs($this->user, ['*']);
    }

    /**
     * Helper to get valid product creation data.
     */
    protected function getValidProductData(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Test Product Title',
            'content' => 'This is a test product description.',
            'price' => 123.45,
            'stock' => 10,
            'category_id' => $this->category->id,
            'attributes' => [
                ['attribute_id' => $this->attributeIds[0], 'value' => 'Red'],
                ['attribute_id' => $this->attributeIds[1], 'value' => 'Large'],
            ],
        ], $overrides);
    }

    /**
     * Test product creation fails with missing required fields.
     * @return void
     */
    public function test_product_creation_fails_with_missing_required_fields()
    {
        $response = $this->postJson('/api/products', []); // Empty data

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'price', 'stock', 'category_id']);
    }

    /**
     * Test product creation fails with invalid data types or values.
     * @return void
     */
    public function test_product_creation_fails_with_invalid_data_types_or_values()
    {
        $invalidData = $this->getValidProductData([
            'title' => 123, // Should be string
            'price' => 'abc', // Should be numeric
            'stock' => 'xyz', // Should be integer
            'category_id' => 'not-an-id', // Should be integer
        ]);

        $response = $this->postJson('/api/products', $invalidData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'price', 'stock', 'category_id']);

        $invalidData = $this->getValidProductData([
            'price' => -5.00, // Must be at least 0.01
            'stock' => -1, // Must be at least 0
        ]);

        $response = $this->postJson('/api/products', $invalidData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['price', 'stock']);
    }

    /**
     * Test product creation fails if category_id does not exist.
     * @return void
     */
    public function test_product_creation_fails_if_category_id_does_not_exist()
    {
        $productData = $this->getValidProductData(['category_id' => 99999]);

        $response = $this->postJson('/api/products', $productData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['category_id']);
    }

    /**
     * Test product creation fails if attribute_id in attributes array does not exist.
     * @return void
     */
    public function test_product_creation_fails_if_attribute_id_does_not_exist()
    {
        $productData = $this->getValidProductData([
            'attributes' => [
                ['attribute_id' => 99999, 'value' => 'NonExistent'],
            ],
        ]);

        $response = $this->postJson('/api/products', $productData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['attributes.0.attribute_id']);
    }

    //TODO: add more test logics

}
