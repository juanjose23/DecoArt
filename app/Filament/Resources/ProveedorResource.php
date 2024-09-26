<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProveedorResource\Pages;
use App\Filament\Resources\ProveedorResource\RelationManagers;
use App\Models\Proveedor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
class ProveedorResource extends Resource
{
    protected static ?string $model = Proveedor::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = 'Proveedores';

    protected static ?string $navigationGroup = 'Compras';
    protected static ?int $navigationSort = 0;
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make()
                        ->schema([

                            TextInput::make('nombre')
                                ->required()
                                ->maxLength(120),

                            TextInput::make('correo')
                                ->required()
                                ->email()
                                ->unique('proveedors', 'correo')
                                ->maxLength(120),

                            TextInput::make('telefono')
                                ->tel()
                                ->required()
                                ->maxLength(120)
                        ])
                        ->columns(2),
                    Forms\Components\Section::make('Contactos del proveedor')
                        ->schema([
                            Repeater::make('contactoProveedors')
                                ->relationship('contactoProveedors')
                                ->schema([
                                    Grid::make(3)
                                        ->schema([
                                            TextInput::make('nombre')
                                                ->required()
                                                ->maxLength(120),
                                            Select::make('estado')
                                                ->required()
                                                ->options([
                                                    1 => 'Activo',
                                                    0 => 'Inactivo',
                                                ])
                                                ->default(1),

                                        ]),
                                ])
                                ->minItems(1)
                                ->maxItems(5)
                                ->columnSpanFull()

                        ])
                        ->columns(1),






                ])
                ->columnSpan(['lg' => 2]),
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Informacion Adiccional')
                        ->schema([
                            Select::make('sector_comercial')
                                ->required()
                                ->options([
                                    'decoracion' => 'Decoración',
                                    'muebles' => 'Muebles',
                                    'iluminacion' => 'Iluminación',
                                    'textiles' => 'Textiles',
                                ]),

                            TextInput::make('razon_social')
                                ->required()
                                ->maxLength(120),
                            TextInput::make('ruc')
                                ->required()
                                ->unique('proveedors', 'ruc')
                                ->regex('/^[A-Z]\d{12}$/')
                                ->maxLength(13),

                            Select::make('estado')
                                ->required()
                                ->options([
                                    1 => 'Activo',
                                    0 => 'Inactivo',
                                ])
                                ->default(1),

                        ]),


                ])
                ->columnSpan(['lg' => 1]),



        ])
            ->columns(3);


    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('correo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sector_comercial')
                    ->searchable(),
                Tables\Columns\TextColumn::make('razon_social')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ruc')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->formatStateUsing(function ($state) {
                        return $state === 1 ? 'Activo' : 'Inactivo';
                    })
                    ->badge()
                    ->color(fn($state): string => $state === 1 ? 'success' : 'danger')

                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListProveedors::route('/'),
            'create' => Pages\CreateProveedor::route('/create'),
            'edit' => Pages\EditProveedor::route('/{record}/edit'),
        ];
    }
}