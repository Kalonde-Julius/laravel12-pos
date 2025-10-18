<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;
use Filament\Tables\Table;
use Filament\Actions\Action;
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

class ListCustomers extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Customer::query())
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Customer')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Phone_No')
                    ->searchable(),

                TextColumn::make('address')
                    ->label('Address')
                    ->limit(30),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                    ->label('Add New Customer')
                    ->url(fn (): string => route('customer.create'))
                    ->openUrlInNewTab(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->url(fn (Customer $record): string => route('customer.update', $record))
                    ->openUrlInNewTab(),

                Action::make('Delete')
                // ->Icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(fn (Customer $record) => $record->delete())
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Customer deleted successfully')
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
        return view('livewire.customer.list-customers');
    }
}
