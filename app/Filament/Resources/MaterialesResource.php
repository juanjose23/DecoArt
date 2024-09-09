<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialesResource\Pages;
use App\Filament\Resources\MaterialesResource\RelationManagers;
use App\Models\Material;
use App\Models\Materiales;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\SelectColumn;
class MaterialesResource extends Resource
{
    protected static ?string $model = Material::class;

  
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Materiales';

    protected static ?string $navigationGroup = 'Catalogos';

    protected static ?int $navigationSort = 0;
    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('nombre')
                ->required()
                ->maxLength(50)
                ->unique(ignoreRecord: true),

            TextInput::make('descripcion')
                ->required()
                ->maxLength(120),

            Select::make('estado')
                ->options([
                    1 => 'Activo',
                    0 => 'Inactivo',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('nombre')
                ->label('Nombre')
                ->sortable()
                ->searchable(),

            TextColumn::make('descripcion')
                ->label('Descripción')
                ->limit(50),

                TextColumn::make('estado')
                ->label('Estado')
                ->formatStateUsing(function ($state) {
                    return $state === 1 ? 'Activo' : 'Inactivo';
                })
                ->badge()
                ->color(fn ($state): string => $state === 1 ? 'success' : 'danger')
                ->sortable(),
        ])
        ->filters([
            SelectFilter::make('estado')
                ->label('Estado')
                ->options([
                    1 => 'Activo',
                    0 => 'Inactivo',
                ]),
        ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListMateriales::route('/'),
            'create' => Pages\CreateMateriales::route('/create'),
            'edit' => Pages\EditMateriales::route('/{record}/edit'),
        ];
    }
}
