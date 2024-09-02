<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarcasResource\Pages;
use App\Filament\Resources\MarcasResource\RelationManagers;
use App\Models\Marcas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\TextColumn;
class MarcasResource extends Resource
{
    protected static ?string $model = Marcas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('nombre')
                ->label('Nombre')
                ->required()
                ->maxLength(50)
                ->unique(ignoreRecord: true), // Evita duplicados, ignorando el registro actual al actualizar

            TextInput::make('descripcion')
                ->label('Descripción')
                ->required()
                ->maxLength(120),

            Select::make('estado')
                ->label('Estado')
                ->options([
                    1 => 'Activo',
                    0 => 'Inactivo',
                ])
                ->default(1) // Opcional: Valor por defecto para estado
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
                ->sortable(),

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
            'index' => Pages\ListMarcas::route('/'),
            'create' => Pages\CreateMarcas::route('/create'),
            'edit' => Pages\EditMarcas::route('/{record}/edit'),
        ];
    }
}
