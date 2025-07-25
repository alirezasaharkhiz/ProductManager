<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [
            ['name' => 'size'],
            ['name' => 'length'],
            ['name' => 'color'],
            ['name' => 'material'],
            ['name' => 'brand'],
        ];

        foreach ($attributes as $attribute) {
            Attribute::firstOrCreate($attribute);
        }
    }
}
