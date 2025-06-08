<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Pricing;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\ToggleButtons;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionResource\Pages;
use Illuminate\Database\Eloquent\Factories\Relationship;
use App\Filament\Resources\TransactionResource\RelationManagers;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Customers Navigation';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Pricing & Price')
                        ->schema([
                            Grid::make(2)->schema([
                                Select::make('pricing_id')
                                    ->relationship('pricing', 'name')
                                    ->preload()
                                    ->searchable()
                                    ->required()
                                    // live update
                                    ->live()
                                    // live update perubahan state yang dilakukan oleh user
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $getPricing = Pricing::find($state);
                                        if ($getPricing == null) {
                                            $set('sub_total_amount', 0);
                                            $set('total_tax_amount', 0);
                                            $set('grand_total_amount', 0);
                                            $set('duration', 0);
                                            return;
                                        }
                                        $getPrice = $getPricing->price;
                                        $getDuration = $getPricing->duration;

                                        $subTotal = $getPrice * $state;
                                        $totalPPN = $subTotal * 0.11;
                                        $totalAmount = $subTotal + $totalPPN;

                                        $set('sub_total_amount', $subTotal);
                                        $set('total_tax_amount', $totalPPN);
                                        $set('grand_total_amount', $totalAmount);
                                        $set('duration', $getDuration);
                                    })
                                    // load update state ketika edit data
                                    ->afterStateHydrated(function (callable $set, $state) {
                                        $pricingId = $state;
                                        if ($pricingId != null) {
                                            $pricing = Pricing::find($pricingId);
                                            $getDuration = $pricing->duration;
                                            $set('duration', $getDuration);
                                        }
                                    }),
                                TextInput::make('duration')
                                    ->required()
                                    ->numeric()
                                    ->readOnly()
                                    ->prefix('Months'),
                            ]),
                            Grid::make(3)->schema([
                                TextInput::make('sub_total_amount')
                                    ->required()
                                    ->numeric()
                                    ->readOnly()
                                    ->prefix('IDR'),
                                TextInput::make('total_tax_amount')
                                    ->required()
                                    ->numeric()
                                    ->readOnly()
                                    ->prefix('IDR'),
                                TextInput::make('grand_total_amount')
                                    ->required()
                                    ->numeric()
                                    ->readOnly()
                                    ->prefix('IDR')
                                    ->helperText('Harga include PPN 11%'),
                            ]),
                            Grid::make(2)->schema([
                                DatePicker::make('started_at')
                                    // live update    
                                    ->live()
                                    // setelah state berubah akan merubah state ended at
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $duration = $get('duration');
                                        if ($state && $duration) {
                                            $endedAt = Carbon::parse($state)->addMonths($duration);
                                            $set('ended_at', $endedAt->format('Y-m-d'));
                                        }
                                    })
                                    ->required(),

                                DatePicker::make('ended_at')
                                    ->readOnly()
                                    ->required(),
                            ])
                        ]),
                    Step::make('Customer Information')
                        ->schema([
                            Select::make('user_id')
                                ->relationship('student', 'name', function (Builder $query) {
                                    $query->students();
                                })
                                ->searchable()
                                ->preload()
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $user = User::find($state);

                                    $name = $user->name;
                                    $email = $user->email;

                                    $set('name', $name);
                                    $set('email', $email);
                                })

                                ->afterStateHydrated(function (callable $set, $state) {
                                    $userId = $state;
                                    if ($userId != null) {
                                        $user = User::find($userId);
                                        $name = $user->name;
                                        $email = $user->email;
                                        $set('name', $name);
                                        $set('email', $email);
                                    }
                                }),

                            TextInput::make('name')
                                ->readOnly()
                                ->required()
                                ->maxLength(255),

                            TextInput::make('email')
                                ->email()
                                ->readOnly()
                                ->required()
                                ->maxLength(255),
                        ]),
                    Step::make('Payment Information')
                        ->schema([
                            ToggleButtons::make('is_paid')
                                ->label('What is your success payment?')
                                ->boolean()
                                ->grouped()
                                ->icons([
                                    true => 'heroicon-s-check-circle',
                                    false => 'heroicon-s-x-circle',
                                ])
                                ->required(),

                            Select::make('payment_type')
                                ->options([
                                    'Midtrans' => 'Midtrans',
                                    'Manual' => 'Manual',
                                ])
                                ->required(),

                            FileUpload::make('proof')
                                ->image()
                        ])
                ])
                    ->columnSpanFull()
                    ->columns(1)
                    ->skippable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_id'),
                ImageColumn::make('proof')->disk('public')->visibility('public'),
                TextColumn::make('student.name')
                    ->searchable()
                    ->sortable()
                    ->label('Customer Name'),
                TextColumn::make('student.email')
                    ->searchable()
                    ->sortable()
                    ->label('Customer Email'),
                TextColumn::make('pricing.name')
                    ->searchable()
                    ->sortable()
                    ->label('Pricing Name'),
                TextColumn::make('pricing.price')
                    ->label('Pricing Price'),
                TextColumn::make('pricing.duration')
                    ->label('Pricing Duration'),
                TextColumn::make('started_at')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('Y-m-d')),
                TextColumn::make('ended_at')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('Y-m-d')),
                IconColumn::make('is_paid')
                    ->trueIcon('heroicon-s-check-circle')
                    ->falseIcon('heroicon-s-x-circle'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                ViewAction::make(),
                DeleteAction::make(),
                Action::make('approve')
                    ->label('Approve Transaction')
                    ->visible(fn($record) => $record && $record->is_paid == false)
                    ->action(function (Transaction $record) {
                        $record->update([
                            'is_paid' => true
                        ]);
                        $record->save();

                        Notification::make()
                            ->title('Approved Transaction')
                            ->success()
                            ->body('Transaction has been approved')
                            ->send();

                        // modifikasi kirim email atau sms
                    })
                    ->color('success')
                    ->requiresConfirmation()
                    ->icon('heroicon-s-check-circle'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
