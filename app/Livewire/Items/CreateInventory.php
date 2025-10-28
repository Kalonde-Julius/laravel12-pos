<?php

namespace App\Livewire\Items;

use Livewire\Component;
use App\Models\Inventory;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use function Laravel\Prompts\select;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class CreateInventory extends Component implements HasActions, HasSchemas
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
                Section::make('Create Inventory')
                    ->description('Add new inventory')
                    ->columns(['md' => 2])
                    ->schema([
                        Select::make('item_id')
                            ->label('Item / Product')
                            ->relationship('item', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required(),

                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->required()
                            ->numeric(),
                    ])
                 ])
            ->statePath('data')
            ->model(Inventory::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = Inventory::create($data);

        $this->form->model($record)->saveRelationships();

        Notification::make()
            ->title('Created !')
            ->body("Inventory created successfully")
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.items.create-inventory');
    }
}
