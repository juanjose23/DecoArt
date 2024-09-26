<?php

namespace App\Filament\Resources;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
class UserResource extends Resource
{
    protected static ?string $model = User::class;


    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Seguridad';
    protected static ?string $navigationLabel = 'Usuarios';
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Información del Usuario')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nombre')
                                    ->required()
                                    ->maxLength(50),

                                TextInput::make('email')
                                    ->label('Correo Electrónico')
                                    ->email()
                                    ->required()
                                    ->unique('users', 'email', ignoreRecord: true),

                                TextInput::make('password')
                                    ->label('Contraseña')
                                    ->password()
                                    ->required(),

                                Select::make('estado')
                                    ->label('Estado')
                                    ->options([
                                        1 => 'Activo',
                                        0 => 'Inactivo',
                                    ])
                                    ->required(),



                            ])->columns(2), // Organiza los inputs en dos columnas
                    ])->columnSpan(['lg' => 2]),

                /* Forms\Components\Group::make()
                     ->schema([
                         Forms\Components\Section::make('Equipo y Foto de Perfil')
                             ->schema([
                                 Select::make('current_team_id')
                                     ->label('Equipo Actual')
                                     ->relationship('team', 'name')
                                     ->nullable(),
     
                                 FileUpload::make('profile_photo_path')
                                     ->label('Foto de Perfil')
                                     ->image()
                                     ->nullable()
                                     ->maxSize(2048),
                             ]),
                     ])->columnSpan(['lg' => 2]),**/
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Columna para mostrar el ID del usuario
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                // Columna para mostrar el nombre
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),

                // Columna para mostrar el correo electrónico
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->sortable()
                    ->searchable(),

                // Columna para mostrar el estado (Activo/Inactivo)
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->formatStateUsing(function ($state) {
                        return $state === 1 ? 'Activo' : 'Inactivo';
                    })
                    ->badge()
                    ->color(fn($state): string => $state === 1 ? 'success' : 'danger')
                    ->sortable(),
                // Columna para la fecha de creación
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                // Filtro para filtrar por estado (Activo/Inactivo)
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        1 => 'Activo',
                        0 => 'Inactivo',
                    ]),
            ])
            ->actions([
            
            ])
            ->bulkActions([
                // Acción en masa para eliminar registros seleccionados
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
