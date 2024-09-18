<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubcategoriasResource\Pages;
use App\Filament\Resources\SubcategoriasResource\RelationManagers;
use App\Models\Subcategorias;
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
class SubcategoriasResource extends Resource
{
    protected static ?string $model = Subcategorias::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Subcategorias';

    protected static ?string $navigationGroup = 'Catalogos';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make()
                        ->schema([

                            TextInput::make('nombre')
                                ->required()
                                ->maxLength(255)
    
                                ->unique(ignoreRecord: true),


                            Forms\Components\MarkdownEditor::make('descripcion')
                                ->columnSpan('full'),
                        ])
                        ->columns(1),

                  



                ])
                ->columnSpan(['lg' => 2]),
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Categoria')
                        ->schema([
                            Select::make('categoria_id')
                                ->label('Lista de categorias')
                                ->relationship(name: 'categoria', titleAttribute: 'nombre')
                                ->searchable()
                                ->required(),
                        ]),
                    Forms\Components\Section::make('Catalogos de estados')
                        ->schema([
                            Select::make('estado')
                                ->options([
                                    1 => 'Activo',
                                    0 => 'Inactivo',
                                ])
                                ->required()
                                ->label('Estado del Producto'),
                         



                        ]),


                ])
                ->columnSpan(['lg' => 1])
    
                
               

        ])
        ->columns(3);
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

                TextColumn::make('categoria.nombre')
                    ->label('Categoría')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->formatStateUsing(function ($state) {
                        return $state === 1 ? 'Activo' : 'Inactivo';
                    })
                    ->badge()
                    ->color(fn($state): string => $state === 1 ? 'success' : 'danger')
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
                BulkAction::make('delete')
                    ->label('Eliminar')
                    ->action(function (Tables\Actions\BulkAction $action, $records) {
                        foreach ($records as $record) {
                            // Asegúrate de que $record sea una instancia del modelo
                            if ($record instanceof Subcategorias) {
                                $record->markAsDeleted();
                            }
                        }
                    })
                    ->color('danger'),
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
            'index' => Pages\ListSubcategorias::route('/'),
            'create' => Pages\CreateSubcategorias::route('/create'),
            'edit' => Pages\EditSubcategorias::route('/{record}/edit'),
        ];
    }
}
