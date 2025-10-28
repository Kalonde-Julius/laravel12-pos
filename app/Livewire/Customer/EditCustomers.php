<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Forms\Components\ToggleButtons;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class EditCustomers extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Customer $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit Customer')
                    ->description('Update customer details as you wish')
                    ->columns(['md' => 2])
                    ->schema([
                        TextInput::make('name')
                            ->label('Customer'),

                        TextInput::make('email')
                            ->label('Email')
                            ->unique(),

                        TextInput::make('phone')
                            ->label('Phone_No')
                            ->unique(),

                        TextInput::make('address')
                            ->label('Address'),

                    ])
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);

        Notification::make()
            ->title('Customer updated !')
            ->body("Customer {$this->record->name} updated successfully")
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.customer.edit-customers');
    }
}
