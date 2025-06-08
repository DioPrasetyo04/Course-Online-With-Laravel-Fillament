<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use App\Models\CourseSection;
use App\Models\SectionContent;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SectionContentResource\Pages;
use App\Filament\Resources\SectionContentResource\RelationManagers;
use Hamcrest\Description;

class SectionContentResource extends Resource
{
    protected static ?string $model = SectionContent::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Courses';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Select::make('course_section_id')
                //     ->label('Course Section')
                //     ->options(function () {
                //         return CourseSection::with('course')->get()->mapWithKeys(function ($section) {
                //             return [
                //                 $section->id => $section->course ? "{$section->course->name} - {$section->name}" : $section->name
                //             ];
                //         })->toArray();
                //     })
                //     ->searchable()
                //     ->required(),

                TextInput::make('name')
                    ->label('Content Name')
                    ->maxLength(255)
                    ->required(),

                Select::make('course_section_id')
                    ->label('Course Section')
                    ->options(function () {
                        return courseSection::with('course')->get()->mapwithKeys(function ($section) {
                            return [
                                $section->id => $section->course ? "{$section->course->name} - {$section->name}" : $section->name
                            ];
                        })->toArray();
                    }),

                RichEditor::make('content')
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('courseSection.course.thumbnail')->disk('public')->visibility('public')->label('Thumbnail Course'),

                TextColumn::make('name')
                    ->label('Section Content Name')
                    ->sortable()
                    ->searchable(),

                textColumn::make('courseSection.name')
                    ->label('Section Name')->sortable()->searchable(),

                TextColumn::make('courseSection.course.name')->label('Course Name')->sortable()->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSectionContents::route('/'),
            'create' => Pages\CreateSectionContent::route('/create'),
            'edit' => Pages\EditSectionContent::route('/{record}/edit'),
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
