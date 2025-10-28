<?php

namespace App\Livewire;

use App\Models\Item;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\PaymentMethod;
use App\Models\Sale;
use App\Models\SalesItem;
use Filament\Notifications\Notification;
use Livewire\Attributes\Computed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Catch_;

class POS extends Component
{
    // Properties
    public $items;
    public $customers;
    public $paymentMethods;
    public $search = '';
    public $cart = [];

    // Properties for heckout
    public $customer_id = null;
    public $payment_method_id = null;
    public $paid_amount = 0;
    public $discount_amount = 0;
    public $change_amount = 0;

    public function mount() {
        // Load all the items
        $this->items = Item::with(['inventory' => function($builder) {
            $builder->where('quantity', '>', 0);
        }])
        ->get()
        ->where('status', 'active');

        // Load customers
        $this->customers = Customer::all();

        // Load payment methods
        $this->paymentMethods = PaymentMethod::all();
    }

    // filtered items
    #[Computed]
    public function filteredItems()
    {
        if (empty($this->search)) {
            return $this->items;
        }

    //    $searchTerm = strtolower($this->search);

        return $this->items->filter(function ($item)  {
            return str_contains(strtolower($item->name), strtolower($this->search)) ||
                   str_contains(strtolower($item->sku), strtolower($this->search));
        });
    }

    // Subtotal
    #[Computed]
    public function subtotal() {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    // Tax
    #[Computed]
    public function tax() {
        return $this->subtotal * 0.18; // Assuming a flat 18% tax rate
    }

    // Total before discount
    #[Computed]
    public function totalBeforeDiscount() {
        return $this->subtotal + $this->tax;
    }

    // Total after discount
    #[Computed]
    public function total() {
        $discountTotal = $this->totalBeforeDiscount - $this->discount_amount;
        return $discountTotal;
    }

    // Change due
    #[Computed]
    public function change()  {
        $changeAmount = $this->paid_amount - $this->total;
        return $changeAmount;
    }

    // Add item to cart
    public function addToCart($itemId) {
        // Access the item from db to get its inventory and status
        $item = Item::find($itemId);

        // Check if item exists, is active, and has inventory
        $inventory = Inventory::where('item_id', $itemId)->first();
        if(!$inventory || $inventory->quantity <= 0 || $item->status !== 'active') {
            Notification::make()
                ->title('Item is out of stock or inactive.')
                ->danger()
                ->send();
            return;
        }

        // Check if item in cart less than the available inventory
        $currentQuantity = $this->cart[$itemId]['quantity'] ?? 0;
        if($currentQuantity >= $inventory->quantity) {
            Notification::make()
                ->title("Cannot add more of this item. Only {$inventory->quantity} stock !")
                ->danger()
                ->send();
            return;
        }
        // If item already in cart
        if(isset($this->cart[$itemId])) {
            // Increment quantity
            $this->cart[$itemId]['quantity']++;
        } else {
            // Add new item to cart
            $this->cart[$itemId] = [
                'id' => $item->id,
                'name' => $item->name,
                'sku' => $item->sku,
                'price' => $item->price,
                'quantity' => 1,
            ];
        }
    }

    // Remove item from cart
    public function removeFromCart($itemId) {
        if(isset($this->cart[$itemId])) {
            unset($this->cart[$itemId]);
        }
    }

    // Update item quantity in cart
    public function updateQuantity($itemId, $quantity) {
        // Ensure quantity of an item is not less than 1
        $quantity = max(1, (int)$quantity);

        // Get inventory
        $inventory = Inventory::where('item_id', $itemId)->first();

        // Check if requested quantity exceeds available inventory
        if($quantity > $inventory->quantity) {
            Notification::make()
                ->title("Cannot set quantity. Only {$inventory->quantity} in stock !")
                ->danger()
                ->send();
                $this->cart[$itemId]['quantity'] = $inventory->quantity;
              return;
            }else{
                $this->cart[$itemId]['quantity'] = $quantity;
            }
        }

    // Checkout
    public function checkout() {

        // Ensure cart is not empty
        if(empty($this->cart)) {
            Notification::make()
                ->title('Failed sale')
                ->body('Your Cart is empty !')
                ->danger()
                ->send();
            return;
        }

        // Ensure paid amount is sufficient
        if($this->paid_amount < $this->total) {
            Notification::make()
                ->title('Insufficient amount paid !')
                ->danger()
                ->send();
            return;
        }

        // Create the sale.. db transaction
        DB::beginTransaction();

        // Create a sale record
        $sale = Sale::create([
            'customer_id' => $this->customer_id,
            'payment_method_id' => $this->payment_method_id,
            //'item_id' => $this->item_id,
            //'sales_items_id' => $this->sales_items_id,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount_amount,
            'tax' => $this->tax,
            'total' => $this->total,
            'paid_amount' => $this->paid_amount,
            'change' => $this->change,
            'status' => 'completed',
            'sale_date' => now(),
        ]);

        try {
            // Create sale items
            foreach($this->cart as $item) {

                SalesItem::create([
                    'sale_id' => $sale->id,
                    'item_id' => $item['id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity']
                ]);

                // Update/Reduce inventory
                $inventory = Inventory::where('item_id', $item['id'])->first();

                if($inventory) {
                    if($inventory->quantity < $item['quantity']) {
                        DB::rollBack();
                        Notification::make()
                            ->title("Insufficient stock for item:
                                {$item['name']}. Available: {$inventory->quantity}")
                            ->danger()
                            ->send();
                        return;
                    }

                    $inventory->quantity -= $item['quantity'];
                    $inventory->save();
                }
            }

            DB::commit();

            // Reset cart
            $this->cart = [];

            // Reset checkout fields
            $this->search = '';
            $this->customer_id = null;
            $this->payment_method_id = null;
            $this->paid_amount = 0;
            $this->discount_amount = 0;
            //$this->change_amount = 0;

                Notification::make()
                    ->title('Success')
                    ->body('Sale completed successfully')
                    ->success()
                    ->send();
        } catch(\Exception $th) {
            DB::rollback();
            Notification::make()
                ->title('Failed Sale !')
                ->body('Failed to complete sale, please try again !')
                ->danger()
                ->send();
        }

        /*
        // Reload items to reflect updated inventory
        $this->items = Item::with(['inventory' => function($builder) {
            $builder->where('quantity', '>', 0);
        }])
        ->get()
        ->where('status', 'active');
        */

    }


    public function render()
    {
        return view('livewire.p-o-s');
    }
}
