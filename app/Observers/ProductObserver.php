<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    public function updated(Product $product): void
    {
        Log::info("Send Email For ID: {$product->id}");
        //TODO: implement service/repo for mail entity
    }
}
