<?php

namespace App\Filament\Resources\ComprasResource\Pages;
use App\Filament\Resources\ComprasResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Illuminate\Support\Facades\Auth;
use App\Models\Compras;
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


    protected function afterCreate(): void
    {
        $compras = $this->record;
        $user = auth()->user();

        Notification::make()
            ->title('Nueva compra')
            ->icon('heroicon-o-shopping-bag')
            ->success()
            ->body("**{$compras->proveedor?->nombre} se solicita la compra de {$compras->detalleCompras->count()} productos.**")
            ->actions([
                Action::make('Ver')
                    ->url(ComprasResource::getUrl('edit', ['record' => $compras])),
            ])
            ->sendToDatabase($user);
    }



}