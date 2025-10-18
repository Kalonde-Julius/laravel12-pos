<?php

namespace App\Livewire\Management;

use Livewire\Component;
use Filament\Schemas\Schema;
use App\Models\PaymentMethod;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class EditPaymentMethod extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public PaymentMethod $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit Payment Method')
                    ->description('Update payment method details')
                    ->columns(['md' => 2])
                    ->schema([
                        Select::make('name')
                            ->label('Payment Method')
                            ->options([
                                'cash' => 'Cash',
                                'card' => 'Card',
                                'Mobile Money' => 'Mobile Money',
                            ])
                            ->required(),

                        Textarea::make('description')
                            ->label('Description'),

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
            ->title('Payment Method updated !')
            ->body("Payment by {$this->record->name} updated successfully")
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.management.edit-payment-method');
    }
}
