<?php

namespace App\Filament\Pages;

use Filament\Forms;
use App\Models\Order;
use Filament\Actions;
use App\Models\Caterer;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class GenerateReportPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.pages.generate-report-page';

    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Generate Report';
    protected ?string $heading = 'Generate Report';
    protected static ?string $slug = 'reports/general';

    public Caterer $caterer;
    public $foodDetails;
    public $foodCategories;
    public $servingTypes;
    public $packages;
    public $utilities;

    public ?array $data = [];

    public function mount()
    {
        $this->caterer = Caterer::when(auth()->user()->hasRole('caterer'), function ($query) {
            return $query->where('id', auth()->user()->caterer->id);
        })->first();

        $this->foodDetails = $this->caterer->foodDetails;
        $this->foodCategories = $this->caterer->foodCategories;
        $this->servingTypes = $this->caterer->servingTypes;
        $this->utilities = $this->caterer->utilities;

        $packagesWithItems = $this->caterer->packages()->get();
        $this->packages = $packagesWithItems->load('packageItems');
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(4)
            ->statePath('data')
            ->schema([
                Forms\Components\Fieldset::make()
                    ->label('Reservation Request Submitted')
                    ->schema([
                        Forms\Components\DateTimePicker::make('created_at_start')
                            ->label('Start')
                            ->beforeOrEqual('created_at_end')
                            ->live(debounce: 500)
                            ->requiredWith('created_at_end'),
                        Forms\Components\DateTimePicker::make('created_at_end')
                            ->label('End')
                            ->afterOrEqual('created_at_start')
                            ->live(debounce: 500)
                            ->requiredWith('created_at_start'),
                    ]),
                Forms\Components\Fieldset::make()
                    ->label('Reservation Date Range')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start')
                            ->label('Start')
                            ->date()
                            ->beforeOrEqual('end')
                            ->live(debounce: 500)
                            ->requiredWith('end'),
                        Forms\Components\DateTimePicker::make('end')
                            ->label('End')
                            ->date()
                            ->afterOrEqual('start')
                            ->live(debounce: 500)
                            ->requiredWith('start'),
                    ]),
                Forms\Components\Fieldset::make()
                    ->label('Statuses')
                    ->schema([
                        Forms\Components\Select::make('order_status')
                            ->label('Reservation')
                            ->options(OrderStatus::class)
                            ->nullable(),
                        Forms\Components\Select::make('payment_status')
                            ->label('Payment')
                            ->options(PaymentStatus::class)
                            ->nullable(),
                    ])

            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('Generate')
                ->color('primary')
                ->submit('export'),
        ];
    }

    public function export()
    {
        $state = $this->form->getState();

        $orders = Order::with(
            'user',
            'orderItems',
            'cancellationRequest',
            'payments'
        )
            ->when(auth()->user()->hasRole('caterer'), function ($query) {
                return $query->where('caterer_id', auth()->user()->caterer->id);
            })
            ->when($state['created_at_end'], function ($query) use ($state) {
                return $query->whereBetween('created_at', [$state['created_at_start'], $state['created_at_end']]);
            })
            ->when($state['start'], function ($query) use ($state) {
                return $query->where('created_at', '>=', $state['start']);
            })
            ->when($state['end'], function ($query) use ($state) {
                return $query->where('created_at', '<=', $state['end']);
            })
            ->when($state['order_status'], function ($query) use ($state) {
                return $query->where('order_status', $state['order_status']);
            })
            ->when($state['payment_status'], function ($query) use ($state) {
                return $query->where('payment_status', $state['payment_status']);
            })
            ->get();

        $pdf  = Pdf::setOption([
            'isRemoteEnabled' => true,
        ])
            ->setPaper('a4', 'portrait')
            ->loadHtml(view('pdf.report', [
                'caterer' => $this->caterer->toArray(),
                'foodDetails' => $this->foodDetails,
                'foodCategories' => $this->foodCategories,
                'servingTypes' => $this->servingTypes,
                'utilities' => $this->utilities,
                'packages' => $this->packages,
                'orders' => $orders,
            ])->render());

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'report.pdf');
    }
}
