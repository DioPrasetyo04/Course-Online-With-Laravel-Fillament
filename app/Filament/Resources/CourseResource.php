<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\RelationManagers\CourseTestimonialsRelationManager;
use Filament\Forms;
use Filament\Tables;
use App\Models\Course;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CourseResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Filament\Resources\CourseResource\RelationManagers\CourseSectionsRelationManager;
use App\Models\CourseTestimonial;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make("Details")
                    ->schema([
                        TextInput::make("name")
                            ->maxLength(255)
                            ->required(),

                        FileUpload::make("thumbnail")
                            ->required()
                            ->image(),
                    ]),
                Fieldset::make("Addtional")
                    ->schema([
                        Repeater::make("benefits")
                            ->relationship("benefits")
                            ->schema([
                                TextInput::make("name")
                                    ->required()
                            ]),
                        // Repeater::make("sneak_peek")
                        //     ->schema([
                        //         FileUpload::make("sneak_peak")
                        //             ->required()
                        //     ]),
                        Textarea::make("about")
                            ->required(),
                        Select::make("is_popular")
                            ->options([
                                true => 'Popular',
                                false => 'Not Popular'
                            ])
                            ->required(),

                        Select::make("category_id")
                            ->relationship("category", "name")
                            ->searchable()
                            ->preload()
                            ->required()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make("thumbnail"),

                TextColumn::make("name"),

                TextColumn::make("category.name"),

                IconColumn::make("is_popular")
                    ->boolean()
                    ->trueColor("success")
                    ->falseColor("danger")
                    ->trueIcon("heroicon-o-check-circle")
                    ->falseIcon("heroicon-o-x-circle")
                    ->label("Popular")
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
            // nambahin course section didalam course yang sudah dibuat
            CourseSectionsRelationManager::class,
            CourseTestimonialsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
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
