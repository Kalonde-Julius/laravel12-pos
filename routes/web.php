<?php

use App\Livewire\POS;
use App\Livewire\Items\EditItems;
use App\Livewire\Items\ListItems;
use App\Livewire\Sales\ListSales;
use App\Livewire\Items\CreateItem;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Items\EditInventory;
use App\Livewire\Management\EditUser;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;
use App\Livewire\Management\ListUsers;
use App\Livewire\Items\CreateInventory;
use App\Livewire\Items\ListInventories;
use App\Livewire\Management\CreateUser;
use App\Livewire\Customer\EditCustomers;
use App\Livewire\Customer\ListCustomers;
use App\Livewire\Customer\CreateCustomer;
use App\Livewire\Management\EditPaymentMethod;
use App\Livewire\Management\ListPaymentMethods;
use App\Livewire\Management\CreatePaymentMethod;
use App\Livewire\Sales\EditSale;
use App\Livewire\Sales\SaleDetails;
use App\Models\Sale;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::middleware(['auth'])->group(function () {
    //Users
    Route::get('manage-users', ListUsers::class)->name('users.index');
    Route::get('create-user', CreateUser::class)->name('user.create');
    Route::get('edit-user/{record}', EditUser::class)->name('user.update');

    // Payment Methods
    Route::get('manage-payment-methods', ListPaymentMethods::class)->name('payment.method.index');
    Route::get('create-payment-method', CreatePaymentMethod::class)->name('paymentMethod.create');
    Route::get('edit-payment-method/{record}', EditPaymentMethod::class)->name('payment-method.update');

    // Customers
    Route::get('manage-customers', ListCustomers::class)->name('customers.index');
    Route::get('create-customer', CreateCustomer::class)->name('customer.create');
    Route::get('edit-customers/{record}', EditCustomers::class)->name('customer.update');

    // Items
    Route::get('manage-items', ListItems::class)->name('items.index');
    Route::get('create-item', CreateItem::class)->name('item.create');
    Route::get('edit-item/{record}', EditItems::class)->name('item.update');

    // Sales
    Route::get('manage-sales', ListSales::class)->name('sales.index');
    // Route::get('create-sale', CreateSale::class)->name('sale.create');
    Route::get('edit-sale/{record}', EditSale::class)->name('sale.update');

    // Inventories
    Route::get('manage-inventories', ListInventories::class)->name('inventories.index');
    Route::get('create-inventory', CreateInventory::class)->name('inventory.create');
    Route::get('edit-inventory/{record}', EditInventory::class)->name('inventory.update');

    // POS
    Route::get('pos', POS::class)->name('pos');

    // Sale Details
    Route::get('/sale-details/{record}', SaleDetails::class)->name('sale.details');

});

require __DIR__.'/auth.php';
