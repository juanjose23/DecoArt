<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoResource\Pages;
use App\Models\Producto;
use App\Models\DetalleProducto;
use Illuminate\Validation\ValidationException;

use Illuminate\Validation\Rule;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;


use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Support\Str;
class ProductoResource extends Resource
{
    protected static ?string $model = Producto::class;
    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationLabel = 'Productos';

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
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                        if ($operation !== 'create') {
                                            return;
                                        }

                                        $set('slug', Str::slug($state));
                                    })
                                    ->unique(ignoreRecord: true),

                                TextInput::make('slug')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Producto::class, 'slug', ignoreRecord: true),

                                Forms\Components\MarkdownEditor::make('descripcion')
                                    ->columnSpan('full'),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Images')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('media')
                                    ->collection('product-images')
                                    ->multiple()
                                    ->maxFiles(5)
                                    ->hiddenLabel(),
                            ])
                            ->collapsible()
                            ->columns(1),





                    ])
                    ->columnSpan(['lg' => 2]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Categoria')
                            ->schema([
                                Select::make('subcategoria_id')
                                    ->label('Lista de categorias')
                                    ->options(function () {
                                       
                                        $subcategorias = \App\Models\Subcategorias::with('categoria')->get();

                                        
                                        $grouped = $subcategorias->groupBy(function ($subcategoria) {
                                            return $subcategoria->categoria ? $subcategoria->categoria->nombre : 'Sin Categoría';
                                        });

                                     
                                        return $grouped->mapWithKeys(function ($subcategorias, $categoriaNombre) {
                                            return [
                                                $categoriaNombre => $subcategorias->mapWithKeys(function ($subcategoria) {
                                                    return [$subcategoria->id => $subcategoria->nombre];
                                                })
                                            ];
                                        });
                                    })
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
                                Toggle::make('caducidad')
                                    ->label('¿Tiene caducidad?')
                                    ->helperText('Marca esta opción si el producto tiene una fecha de caducidad. ')
                                    ->default(false),



                            ]),


                    ])
                    ->columnSpan(['lg' => 1]),
                    Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Detalles del Producto')
                            ->schema([
                                Repeater::make('detalleProductos')
                                    ->relationship('detalleProductos')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Select::make('color_id')
                                                    ->relationship('color', 'nombre')
                                                    ->label('Color')
                                                    ->required()
                                                    ->placeholder('Selecciona un color')
                                                    ->searchable(),
                
                                                Select::make('marca_id')
                                                    ->relationship('marca', 'nombre')
                                                    ->label('Marca')
                                                    ->required()
                                                    ->placeholder('Selecciona una marca')
                                                    ->searchable(),
                
                                                Select::make('material_id')
                                                    ->relationship('material', 'nombre')
                                                    ->label('Material')
                                                    ->required()
                                                    ->placeholder('Selecciona un material')
                                                    ->searchable(),
                
                                                TextInput::make('codigo')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->label('Codigo de barra de la variante')
                                                    ->unique(ignoreRecord: true)
                                            ]),
                                    ])
                                    ->minItems(1)
                                    ->maxItems(10)
                                    ->columnSpanFull()
                                   
                            ])
                            ->columns(1),
                    ])
                    ->columnSpan(['lg' => 3])
                   

            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
             
                TextColumn::make('subcategoria.nombre')->label('Subcategoría')->sortable(),
                TextColumn::make('nombre')->label('Nombre')->sortable(),
                TextColumn::make('descripcion')->label('Descripción'),
                TextColumn::make('caducidad')
                    ->formatStateUsing(function ($state) {
                        return $state ? 'Caducable' : 'No caducible';
                    })
                    ->badge()
                    ->color(fn($state): string => $state ? 'danger' : 'success')
                    ->sortable()
                    ->label('Estado de Caducidad')
                    ->tooltip(fn($state) => $state ? 'Este producto tiene fecha de caducidad.' : 'Este producto no tiene fecha de caducidad.'),
                
                
                TextColumn::make('estado')
                    ->label('Estado')
                    ->formatStateUsing(function ($state) {
                        return $state === 1 ? 'Activo' : 'Inactivo';
                    })
                    ->badge()
                    ->color(fn($state): string => $state === 1 ? 'success' : 'danger')
                    ->sortable(),
                    SpatieMediaLibraryImageColumn::make('product-image')
                    ->label('Image')
                    ->collection('product-images'),
                
            ])
            ->filters([
                //
                SelectFilter::make('subcategoria_id')
                    ->label('Subcategoría')
                    ->relationship('subcategoria', 'nombre')
                    ->searchable(),
                SelectFilter::make('estado')
                    ->options([
                        1 => 'Activo',
                        0 => 'Inactivo',
                    ])
                    ->label('Estado'),
                SelectFilter::make('caducidad')
                    ->options([
                        true => 'Caducable',
                        false => 'No caducible',
                    ])
                    ->label('Caducable'),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),

            ])->searchable();
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
            'index' => Pages\ListProductos::route('/'),
            'create' => Pages\CreateProducto::route('/create'),
            'edit' => Pages\EditProducto::route('/{record}/edit'),
        ];
    }
}