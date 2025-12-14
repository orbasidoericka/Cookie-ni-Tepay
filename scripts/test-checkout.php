<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;

$pdo = DB::connection()->getPdo();

try {
    DB::transaction(function () {
        $product = Product::lockForUpdate()->find(1);
        if (!$product) {
            echo "No product #1\n";
            return;
        }
        if ($product->stock < 1) {
            echo "Not enough stock\n";
            return;
        }
        $product->decrement('stock', 1);
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => 1,
            'customer_name' => 'Test Checkout',
            'address' => '123 Test St',
            'contact_number' => '09123456789',
            'total_amount' => $product->price,
            'status' => 'completed',
            'notes' => 'Test order'
        ]);
        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'subtotal' => $product->price,
        ]);
        echo "Inserted order id: {$order->id}\n";
    });
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
