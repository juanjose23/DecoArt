<?php

namespace App\Filament\Resources\ComprasResource\Pages;

use App\Filament\Resources\ComprasResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Enums\CompraStatus;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use App\Models\Compras;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Filament\Notifications\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Log;
class CreateCompras extends CreateRecord
{
    use HasWizard;
    protected static string $resource = ComprasResource::class;

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                Wizard::make($this->getSteps())
                    ->startOnStep($this->getStartStep())
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction($this->getSubmitFormAction())
                    ->skippable($this->hasSkippableSteps())
                    ->contained(false),
            ])
            ->columns(null);
    }
    protected function getSteps(): array
    {
        return [
            Step::make('Informacion General')
                ->schema([
                    Section::make()->schema(ComprasResource::getDetailsFormSchema())->columns(),
                ]),

            Step::make('Productos a comprar')
                ->schema([
                    Section::make()->schema([
                        ComprasResource::getItemsRepeater(),
                    ]),
                ]),
            Step::make('Costos de compra')
                ->schema([

                    Section::make()->schema(ComprasResource::getCostos())->columns(),
                ]),
        ];
    }
    protected function saveCompra(array $data): void
    {
        // Imprime si los campos requeridos están presentes
        if (!isset($data['costo_envio']) || !isset($data['costo_aduana']) || !isset($data['iva']) || !isset($data['subtotal']) || !isset($data['total'])) {
            Log::error('Faltan campos en los datos: ' . json_encode($data));
        }
        Log::error('no hay campos');

        $compra = Compras::create([
            'user_id' => $data['user_id'],
            'codigo' => $data['codigo'],
            'proveedor_id' => $data['proveedor_id'],
            'fecha_recepcion' => $data['fecha_recepcion'],
            'estado' => $data['estado'],
            'notas' => $data['notas'],
            'costo_envio' => $data['costo_envio'] ?? 0, // Asegúrate de capturar estos valores
            'costo_aduana' => $data['costo_aduana'] ?? 0,
            'iva' => $data['iva'] ?? 0,
            'subtotal' => $data['subtotal'] ?? 0,
            'total' => $data['total'] ?? 0,
        ]);
    }

    protected function afterCreate(): void
    {
        $compras = $this->record;
        $user = auth()->user();

        Notification::make()
            ->title('Nueva compra')
            ->icon('heroicon-o-shopping-bag')
            ->body("**{$compras->proveedor?->nombre} compró {$compras->detalleCompras->count()} productos.**")
            ->actions([
                Action::make('View')
                    ->url(ComprasResource::getUrl('edit', ['record' => $compras])),
            ])
            ->sendToDatabase($user);
    }

}