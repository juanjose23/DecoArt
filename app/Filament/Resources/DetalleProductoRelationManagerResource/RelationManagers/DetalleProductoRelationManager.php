<?php

namespace App\Filament\Resources\DetalleProductoRelationManagerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Components\Select;
class DetalleProductoRelationManager extends RelationManager
{
    protected static string $relationship = 'detalleProductos';

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                Select::make('color_id')
                    ->relationship('color', 'nombre')
                    ->label('Color')
                    ->required(),

                Select::make('marca_id')
                    ->relationship('marca', 'nombre')
                    ->label('Marca')
                    ->required(),

                Select::make('material_id')
                    ->relationship('material', 'nombre')
                    ->label('Material')
                    ->required(),
            ]);

    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Detalles')
            ->columns([
                Tables\Columns\TextColumn::make('Detalles'),
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
