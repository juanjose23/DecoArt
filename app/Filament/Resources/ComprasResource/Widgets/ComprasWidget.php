<?php

namespace App\Filament\Resources\ComprasResource\Widgets;
use App\Filament\Resources\ComprasResource\Pages\ListCompras;
use App\Models\Compras;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Enums\CompraStatus;
class ComprasWidget extends BaseWidget
{
    use InteractsWithPageTable;
    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListCompras::class;
    }
    protected function getStats(): array
    {
        $CompraData = Trend::model(Compras::class)
            ->between(start: now()->subYear(), end: now())
            ->perMonth()
            ->count();
    
        return [
            Stat::make('Compras', $this->getPageTableQuery()->count())
                ->chart(
                    $CompraData
                        ->map(fn(TrendValue $value) => $value->aggregate)
                        ->toArray()
                )
                ->color('primary') // Color llamativo
                ->icon('heroicon-o-shopping-cart') // Icono de carrito
                ->description('Total de compras realizadas en el sistema'),
    
            Stat::make('Compras Nuevas', $this->getPageTableQuery()->whereIn('estado', [CompraStatus::Nueva->value, CompraStatus::Aprobada->value, CompraStatus::EnProceso->value])->count())
                ->color('info') 
                ->icon('heroicon-o-document') 
                ->description('Compras en estado Nueva, Aprobada o En Proceso'),
    
            Stat::make('Promedio de costo', number_format($this->getPageTableQuery()->avg('total'), 2))
                ->color('success')
                ->icon('heroicon-o-chart-bar') 
                ->description('Costo promedio de todas las compras'),
        ];
    }
    
}
