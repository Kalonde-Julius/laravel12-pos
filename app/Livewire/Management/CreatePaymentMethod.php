<?php

namespace App\Livewire\Management;


use Livewire\Component;
use Filament\Schemas\Schema;
use App\Models\PaymentMethod;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class CreatePaymentMethod extends Component implements HasActions, HasSchemas
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
                Section::make('New Payment Method')
                    ->description('Add new payment method')
                    ->columns(['md' => 2])
                    ->schema([
                        TextInput::make('name')
                            ->label('Payment Method')
                            ->required(),

                        Textarea::make('description')
                            ->label('Description'),

                    ])
            ])
            ->statePath('data')
            ->model(PaymentMethod::class);
    }

    public function create(): void
    {
         $data = $this->form->getState();

        $record = PaymentMethod::create($data);

        $this->form->model($record)->saveRelationships();

        Notification::make()
            ->title('Created !')
            ->body("Payment Method created successfully")
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.management.create-payment-method');
    }
}
