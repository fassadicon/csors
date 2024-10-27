<?php

namespace App\Filament\Pages;

use Filament\Forms;
use App\Models\User;
use App\Models\Order;
use App\Models\Package;
use Filament\Actions;
use App\Models\Caterer;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\FoodCategory;
use App\Models\FoodDetail;
use App\Models\ServingType;
use App\Models\Utility;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class GenerateSuperAdminReportPage extends Page implements HasForms
{
    // use HasPageShield;
    use InteractsWithForms;

    protected static string $view = 'filament.pages.generate-super-admin-report-page';

    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Generate SA Report';
    protected ?string $heading = 'Generate Report';
    protected static ?string $slug = 'reports/super-admin';

    public ?array $data = [];

    public $customers;
    public $caterers;

    public function mount()
    {
        $this->customers = User::where('is_customer', 1)->get();
        $this->caterers = Caterer::all();
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(4)
            ->statePath('data')
            ->schema([
                Forms\Components\Select::make('caterer_id')
                    ->label('Caterer')
                    ->options(function () {
                        return Caterer::all()->pluck('name', 'id');
                    })
                    ->nullable(),
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

        $selectedCaterer = Caterer::where('id', $state['caterer_id'])->first();

        $foodDetails = null;
        $foodCategories = null;
        $servingTypes = null;
        $utilities = null;
        $packagesWithItems = null;
        $orders = null;

        if ($selectedCaterer != null) {
            $foodDetails = $selectedCaterer->foodDetails;
            $foodCategories = $selectedCaterer->foodCategories;
            $servingTypes = $selectedCaterer->servingTypes;
            $utilities = $selectedCaterer->utilities;

            $packagesWithItems = $selectedCaterer->packages()->get();
            $packages = $packagesWithItems->load('packageItems');

            $orders = Order::with(
                'user',
                'orderItems',
                'cancellationRequest',
                'payments'
            )
                ->where('caterer_id', $state['caterer_id'])
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
        } else {
            $foodDetails = FoodDetail::all();
            $foodCategories = FoodCategory::all();
            $servingTypes = ServingType::all();
            $utilities = Utility::all();

            $packagesWithItems = Package::all();
            $packages = $packagesWithItems->load('packageItems');

            $orders = Order::with(
                'user',
                'orderItems',
                'cancellationRequest',
                'payments'
            )
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
        }

        $pdf  = Pdf::setOption([
            'isRemoteEnabled' => true,
        ])
            ->setPaper('a4', 'portrait')
            ->loadHtml(view('pdf.super-admin-report', [
                'selectedCaterer' => $selectedCaterer != null ? $selectedCaterer->toArray() : false,
                'customers' => $this->customers,
                'caterers' => $this->caterers,
                'foodDetails' => $foodDetails,
                'foodCategories' => $foodCategories,
                'servingTypes' => $servingTypes,
                'utilities' => $utilities,
                'packages' => $packages,
                'orders' => $orders,
            ])->render());

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'sa-report.pdf');
    }
}
