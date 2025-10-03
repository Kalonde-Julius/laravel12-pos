<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Item;
use Livewire\Component;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Builder;

class POS extends Component
{
    // Properties
    public $items;
    public $customers;
    public $paymentMethods;
    public $search = '';
    public $cart = [];

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

        dd(
            $this->items,
            $this->customers,
            $this->paymentMethods
        );
    }
    public function render()
    {
        return view('livewire.p-o-s');
    }
}
