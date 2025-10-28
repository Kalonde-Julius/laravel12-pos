<?php

namespace App\Livewire\Items;

use Livewire\Component;
use App\Models\Inventory;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class EditInventory extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Inventory $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit Inventory')
                    ->description('Update inventory details')
                    ->columns(['md' => 2])
                    ->schema([
                        TextInput::make('quantity')
                            ->label('Quantity'),
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
            ->title('Inventory updated !')
            ->body("Inventory {$this->record->name} updated successfully")
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.items.edit-inventory');
    }
}
