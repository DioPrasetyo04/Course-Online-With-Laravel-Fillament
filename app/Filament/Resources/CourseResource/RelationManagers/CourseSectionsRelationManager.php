<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Symfony\Component\Uid\MaxUlid;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;

class CourseSectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'courseSections';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Section Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('position')
                    ->label('Position')
                    ->prefix('Position')
                    ->default(function () {
                        $lastPosition = $this->getOwnerRecord()->courseSections()->max('position');
                        return $lastPosition ? $lastPosition + 1 : 1;
                    })
                    ->required()
                    ->numeric()
                    ->minValue(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('position'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
