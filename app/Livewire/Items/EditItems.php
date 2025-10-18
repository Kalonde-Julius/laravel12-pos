<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Livewire\Component;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Filament\Schemas\Components\Section;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Phiki\Grammar\Injections\Prefix;

class EditItems extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Item $record;

    public ?array $data = [];

    public function mount(): void
    {
        // It will populate the default values from db
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit Item')
                    ->description('Update item details as you wish')
                    ->columns(['md' => 2])
                    ->schema([
                        TextInput::make('name')
                            ->label('Item Name'),

                        TextInput::make('sku')
                            ->label('SKU')
                            ->unique(),

                        TextInput::make('price')
                            ->prefix('UGX')
                            ->numeric(),

                        ToggleButtons::make('status')
                        ->label('Is this Item Active ?')
                        ->options([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                        ])
                        ->grouped()
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
            ->title('Item updated !')
            ->body("Item {$this->record->name} updated successfully")
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.items.edit-items');
    }
}
