# Real-Time Stock Tracking Implementation

## Overview
The stock tracking system connects the database stock levels to the UI, preventing customers from purchasing unavailable items and notifying them when stock is low or out.

## Features Implemented

### 1. **Add to Cart Validation** (ShopController.php)
- Prevents adding out-of-stock items (stock <= 0)
- Validates quantity doesn't exceed available stock
- Returns error messages: "Sorry, this product is currently out of stock" or "Sorry, only X items available in stock"

### 2. **Update Cart Validation** (ShopController.php)
- Checks requested quantity against available stock when updating cart
- Returns error: "Sorry, only X items available in stock"

### 3. **Cart Display with Stock Warnings** (cart.blade.php)
- **Out of Stock Badge**: Red badge showing "Out of Stock"
- **Low Stock Warning**: Yellow badge showing "Only X left!"
- **Stock Issues Alert**: Banner at top of cart warning about stock problems
- **Quantity Input Limits**: Max attribute prevents entering more than available stock
- **Visual Indicators**: Yellow border around items with stock issues

### 4. **Checkout Validation** (CheckoutController.php - index method)
- Validates stock before displaying checkout form
- Automatically skips out-of-stock items
- Adjusts quantities if they exceed available stock
- Redirects back to cart with errors if:
  - All items are out of stock
  - Any stock issues detected
- Shows specific messages: "Some items in your cart are out of stock or have insufficient quantity. Please review your cart."

### 5. **Order Processing** (CheckoutController.php - process method)
- Uses ACID-compliant transactions with `lockForUpdate()`
- Final stock validation before order confirmation
- Atomic stock decrement to prevent overselling

## CSS Styling

### Stock Badges
```css
.stock-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.out-of-stock {
    background-color: #fee;
    color: #c33;
    border: 1px solid #fcc;
}

.low-stock {
    background-color: #ffc;
    color: #c90;
    border: 1px solid #ffb;
}

.stock-warning {
    border: 2px solid #ffc107;
    background-color: #fff9e6;
}
```

## Testing the System

### Test Case 1: Out of Stock Item
1. Product ID 1 (Butter Croissant) has been set to stock = 0
2. Try to add it to cart → Should show "Sorry, this product is currently out of stock"
3. If already in cart → Shows "Out of Stock" badge with red styling

### Test Case 2: Low Stock Item
1. Product ID 2 (Chocolate Danish) has been set to stock = 3
2. Try to add 5 to cart → Should show "Sorry, only 3 items available in stock"
3. Add 3 to cart → Shows "Only 3 left!" badge with yellow styling
4. Try to update quantity to 5 → Error message appears
5. Proceed to checkout → Quantity cannot exceed 3

### Test Case 3: Stock During Checkout
1. Add items to cart with valid stock
2. Go to checkout → System validates stock again
3. If stock changed (another customer bought items), system:
   - Adjusts quantities automatically
   - Redirects back to cart with warning
   - Shows which items have issues

## Database Structure

### Products Table
- `stock` column (integer) tracks available quantity
- Updated atomically during order processing

### Stock Updates
```php
// During checkout (CheckoutController.php - process method)
DB::transaction(function () use ($request, &$order) {
    foreach ($cart as $productId => $quantity) {
        $product = Product::lockForUpdate()->findOrFail($productId);
        
        if ($product->stock < $quantity) {
            throw new \Exception("Insufficient stock for {$product->name}");
        }
        
        // Decrement stock atomically
        $product->decrement('stock', $quantity);
    }
});
```

## User Experience Flow

### Happy Path
1. Customer browses products → Sees real stock levels
2. Adds items to cart → Validated against current stock
3. Updates quantities → Cannot exceed available stock
4. Proceeds to checkout → Final validation before order
5. Completes order → Stock decremented atomically

### Stock Issue Path
1. Customer adds item to cart
2. Stock becomes low/unavailable (time passes or other customers buy)
3. Cart page shows warnings with badges
4. Checkout redirects back with clear error message
5. Customer adjusts cart based on available stock
6. Proceeds successfully with valid quantities

## ACID Compliance

The stock tracking maintains all ACID properties:

- **Atomicity**: All stock decrements happen within transactions
- **Consistency**: Multiple validation layers prevent invalid stock states
- **Isolation**: `lockForUpdate()` prevents race conditions between concurrent orders
- **Durability**: SQLite WAL mode ensures stock changes are persisted

## Future Enhancements

Potential improvements:
1. Real-time stock updates using WebSockets
2. "Notify me when back in stock" feature
3. Reserved stock during active cart sessions
4. Stock history tracking
5. Low stock admin alerts
