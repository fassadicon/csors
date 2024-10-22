<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Actions;
use App\Mail\OrderUpdateMail;
use Illuminate\Support\Facades\Mail;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->hasRole('caterer')) {
            $data['caterer_id'] = auth()->user()->caterer->id;
        }

        return $data;
    }

    protected function afterCreate()
    {
        if (auth()->user()->hasRole('caterer')) {
            $data['caterer_id'] = auth()->user()->caterer->id;
            Mail::to(auth()->user()->caterer->email)->send(new OrderUpdateMail(
                $this->record->id,
            ));
        }

        Mail::to('sa.csors.offical@gmail.com')->send(new OrderUpdateMail(
            $this->record->id,
        ));
    }
}
