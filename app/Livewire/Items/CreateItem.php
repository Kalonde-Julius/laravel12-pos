<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Livewire\Component;
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

class CreateItem extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Create Item')
                    ->description('Add new item to the inventory')
                    ->columns(['md' => 2])
                    ->schema([
                        TextInput::make('name')
                            ->label('Item/Product')
                            ->required(),

                        TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->unique(),

                        TextInput::make('price')
                            ->prefix('UGX')
                            ->numeric()
                            ->required(),

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
            ->model(Item::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = Item::create($data);

        $this->form->model($record)->saveRelationships();

        Notification::make()
            ->title('Created !')
            ->body("Item created successfully")
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.items.create-item');
    }
}
