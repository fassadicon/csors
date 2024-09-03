<x-filament-panels::page class="fi-dashboard-page">
    @if (method_exists($this, 'filtersForm'))
        {{ $this->filtersForm }}
    @endif

    @if (!$this->isVerifiedCaterer())
        <h1>
            WARNING: You have an ongoing process of verification, the services will not be available to the
            customers. Please contact the admin for more information.
        </h1>
    @endif

    <x-filament-widgets::widgets :columns="$this->getColumns()"
        :data="[
            ...property_exists($this, 'filters') ? ['filters' => $this->filters] : [],
            ...$this->getWidgetData(),
        ]"
        :widgets="$this->getVisibleWidgets()" />
</x-filament-panels::page>
