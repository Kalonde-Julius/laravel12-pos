<?php

namespace App\Livewire\Management;

use Livewire\Component;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\PaymentMethod;
use Illuminate\Contracts\View\View;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use App\Models\PaymentMethod as ModelsPaymentMethod;


class ListPaymentMethods extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => PaymentMethod::query())
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                    ->label('Add New Payment Method')
                    ->url(fn (): string => route('paymentMethod.create'))
                    ->openUrlInNewTab(),
            ])
            ->recordActions([
                 Action::make('edit')
                    ->url(fn (PaymentMethod $record): string => route('payment-method.update', $record)),

                Action::make('Delete')
                // ->Icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(fn (PaymentMethod $record) => $record->delete())
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Payment Method deleted successfully')
                            //->body('Inventory Deleted successfully.')
                    ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.management.list-payment-methods');
    }
}
