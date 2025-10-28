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
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class CreateUser extends Component implements HasActions, HasSchemas
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
                Section::make('Create User')
                    ->description('Add new user details')
                    ->columns(['md' => 2])
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required(),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->unique()
                            ->required(),

                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required()
                            ->revealable()
                            ->minLength(8)
                            ->dehydrated(fn ($state) => filled($state))
                            ->hiddenOn('edit'),

                        Select::make('role')
                        ->label('Is this User a Cashier or Admin ?')
                        ->options([
                            'admin' => 'Admin',
                            'cashier' => 'Cashier',
                            'other' => 'Other',
                        ])
                        ->preload()
                        ->required(),
                       // ->grouped()
                    ])
               ])
            ->statePath('data')
            ->model(User::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = User::create($data);

        $this->form->model($record)->saveRelationships();

        Notification::make()
            ->title('Created !')
            ->body("User created successfully")
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.management.create-user');
    }
}
