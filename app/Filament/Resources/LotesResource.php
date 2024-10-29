<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LotesResource\Pages;
use App\Filament\Resources\LotesResource\RelationManagers;
use App\Models\Lotes;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LotesResource extends Resource
{
    protected static ?string $model = Lotes::class;
 
    protected static ?string $navigationLabel = 'Lotes';

    protected static ?string $navigationGroup = 'Inventario';
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código del Lote')
                    ->searchable(), // Hacer que esta columna sea buscable
                Tables\Columns\BooleanColumn::make('es_caducable')
                    ->label('Es Caducable')
                    ->sortable(), // Hacer que esta columna sea ordenable
                Tables\Columns\TextColumn::make('fecha_expiracion')
                    ->label('Fecha de Expiración')
                    ->sortable()
                    ->date(), // Formato de fecha
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->sortable()
                    ->searchable(), // Hacer que esta columna sea buscable
            ])
            ->filters([
                // Filtro para el estado
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        1 => 'Activo',
                        0 => 'Inactivo',
                    ]),
                // Filtro para caducabilidad
                Tables\Filters\SelectFilter::make('es_caducable')
                    ->label('Es Caducable')
                    ->options([
                        true => 'Sí',
                        false => 'No',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListLotes::route('/'),
            
            'view' => Pages\ViewLotes::route('/{record}'),
            
        ];
    }
}
