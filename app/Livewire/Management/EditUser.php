<?php

namespace App\Livewire\Management;

use App\Models\User;
use Livewire\Component;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\Select;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class EditUser extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public User $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit User')
                    ->description('Update user details')
                    ->columns(['md' => 3])
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name'),

                        TextInput::make('email')
                            ->label('Email')
                            ->unique(),

                        Select::make('role')
                            ->options([
                                'cashier' => 'cashier',
                                'admin' => 'admin',
                            ])
                            ->label('Role'),


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
            ->title('User updated !')
            ->body(" {$this->record->name} updated successfully")
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.management.edit-user');
    }
}
