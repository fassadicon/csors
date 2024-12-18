<?php

namespace App\Filament\Pages;

use Filament\Forms;
use App\Models\User;
use Filament\Actions;
use App\Models\Caterer;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class GenerateUserReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.pages.generate-user-report';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Generate User Report';
    protected ?string $heading = 'Generate User Report';
    protected static ?string $slug = 'reports/super-admin-users';

    public ?array $data = [];

    public $customers;
    public $caterers;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('superadmin');
    }

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
                Forms\Components\Radio::make('include_customers')
                    ->live()
                    ->label('Include Customer List?')
                    ->boolean()
                    ->required(function (Get $get) {
                        return !$get('include_caterers'); // Required if 'include_caterers' is not selected
                    }),
                Forms\Components\Radio::make('include_caterers')
                    ->live()
                    ->label('Include Caterer List?')
                    ->boolean()
                    ->required(function (Get $get) {
                        return !$get('include_customers'); // Required if 'include_customers' is not selected
                    }),
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

        $pdf  = Pdf::setOption([
            'isRemoteEnabled' => true,
        ])
            ->setPaper('a4', 'portrait')
            ->loadHtml(view('pdf.user-list-report', [
                'customers' => $state['include_customers'] ? $this->customers : null,
                'caterers' => $state['include_caterers'] ? $this->caterers : null,
            ])->render());

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'users-report.pdf');
    }
}
