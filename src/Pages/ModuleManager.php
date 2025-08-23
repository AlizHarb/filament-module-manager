<?php

namespace Alizharb\FilamentModuleManager\Pages;

use Alizharb\FilamentModuleManager\Widgets\ModulesOverview;
use Alizharb\FilamentModuleManager\Models\Module;
use UnitEnum;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Actions\{Action, ActionGroup};
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\{Filter, SelectFilter};
use Alizharb\FilamentModuleManager\Services\ModuleManagerService;

class ModuleManager extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament-module-manager::module-manager';
    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';
    protected static protected static UnitEnum|string|null $navigationLabel = 'Module Manager';

    protected ModuleManagerService $service;

    public function boot(ModuleManagerService $service): void
    {
        $this->service = $service;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Module::query())
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament-module-manager::filament-module.table.module_name'))
                    ->sortable()
                    ->searchable()
                    ->extraAttributes(['class' => 'font-semibold']),
                TextColumn::make('version')
                    ->label(__('filament-module-manager::filament-module.table.version'))
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('active')
                    ->label(__('filament-module-manager::filament-module.table.status'))
                    ->sortable()
                    ->badge()
                    ->color(fn (Module $record) => $record->active ? 'success' : 'danger')
                    ->formatStateUsing(fn(Module $record) => $record->active ? 'enabled' : 'disabled')
                    ->tooltip(fn(Module $record) => !$record->active && !$this->service->canDisable($record->name) ? __('filament-module-manager::filament-module.status.cannot_be_disabled') : null),
                TextColumn::make('path')
                    ->label(__('filament-module-manager::filament-module.table.module_path'))
                    ->wrap()
                    ->extraAttributes(['class' => 'text-xs'])
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('view')
                        ->label(__('filament-module-manager::filament-module.actions.view'))
                        ->icon('heroicon-o-eye')
                        ->modal()
                        ->modalHeading(fn(Module $record) => __('filament-module-manager::filament-module.actions.view_module', ['name' => $record->name]))
                        ->schema($this->getViewSchema())
                        ->modalSubmitAction(false)
                        ->modalWidth('2xl'),

                    Action::make('enable')
                        ->label(__('filament-module-manager::filament-module.actions.enable'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn(Module $record) => !$record->active)
                        ->action(fn(Module $record) => $this->handleEnable($record->name)),

                    Action::make('disable')
                        ->label(__('filament-module-manager::filament-module.actions.disable'))
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn(Module $record) => $record->active && $this->service->canDisable($record->name))
                        ->action(fn(Module $record) => $this->handleDisable($record->name)),

                    Action::make('uninstall')
                        ->label(__('filament-module-manager::filament-module.actions.uninstall'))
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn(Module $record) => $this->service->canUninstall($record->name))
                        ->action(fn(Module $record) => $this->handleUninstall($record->name)),
                ]),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament-module-manager::filament-module.filters.status'))
                    ->options([
                        'enabled' => __('filament-module-manager::filament-module.status.enabled'),
                        'disabled' => __('filament-module-manager::filament-module.status.disabled'),
                    ])
                    ->query(fn($query, $data) => match($data['value'] ?? null) {
                        'enabled' => $query->where('active', true),
                        'disabled' => $query->where('active', false),
                        default => $query,
                    }),
                Filter::make('name')
                    ->label(__('filament-module-manager::filament-module.filters.name'))
                    ->schema([TextInput::make('name')->placeholder(__('filament-module-manager::filament-module.filters.name_placeholder'))])
                    ->query(fn($query, $data) => $data['name'] ? $query->where('name', 'like', "%{$data['name']}%") : $query),
            ])
            ->headerActions([
                Action::make('install')
                    ->label(__('filament-module-manager::filament-module.actions.install'))
                    ->icon('heroicon-o-arrow-up-tray')
                    ->schema($this->getUploadSchema())
                    ->action(fn(array $data) => $this->handleInstall($data['zip'])),
                Action::make('refresh')
                    ->label(__('filament-module-manager::filament-module.actions.refresh'))
                    ->icon('heroicon-o-arrow-path')
                    ->action(fn() => $this->resetTable()),
            ]);
    }

    protected function handleEnable(string $name): void
    {
        if ($this->service->toggleModuleStatus($name, true)) {
            Notification::make()->title(__('filament-module-manager::filament-module.notifications.module_enabled', ['name' => $name]))->success()->send();
        } else {
            Notification::make()->title(__('filament-module-manager::filament-module.notifications.module_not_found'))->danger()->send();
        }
        $this->resetTable();
    }

    protected function handleDisable(string $name): void
    {
        if ($this->service->toggleModuleStatus($name, false)) {
            Notification::make()->title(__('filament-module-manager::filament-module.notifications.module_disabled', ['name' => $name]))->success()->send();
        } else {
            Notification::make()->title(__('filament-module-manager::filament-module.notifications.module_not_found'))->danger()->send();
        }
        $this->resetTable();
    }

    protected function handleInstall(string $zipPath): void
    {
        $result = $this->service->installModulesFromZip($zipPath);

        // Notify about installed modules
        if (!empty($result->installed)) {
            Notification::make()
                ->title(__('filament-module-manager::filament-module.notifications.modules_installed'))
                ->body(__('filament-module-manager::filament-module.notifications.modules_installed_body', [
                    'names' => implode(', ', array_map(fn($m) => $m?->name, $result->installed))
                ]))
                ->success()
                ->send();
        }

        // Notify about skipped modules
        if (!empty($result->skipped)) {
            Notification::make()
                ->title(__('filament-module-manager::filament-module.notifications.modules_skipped'))
                ->body(__('filament-module-manager::filament-module.notifications.modules_skipped_body', [
                    'names' => implode(', ', $result->skipped)
                ]))
                ->warning()
                ->send();
        }

        // Only show an error if **nothing at all** was installed or skipped
        if (empty($result->installed) && empty($result->skipped)) {
            Notification::make()
                ->title(__('filament-module-manager::filament-module.notifications.module_install_error'))
                ->warning()
                ->send();
        }

        $this->resetTable();
    }

    protected function handleUninstall(string $name): void
    {
        if ($this->service->uninstallModule($name)) {
            Notification::make()
                ->title(__('filament-module-manager::filament-module.notifications.module_uninstalled'))
                ->body(__('filament-module-manager::filament-module.notifications.module_uninstalled_body', ['name' => $name]))
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title(__('filament-module-manager::filament-module.notifications.module_uninstall_error'))
                ->danger()
                ->send();
        }
        $this->resetTable();
    }

    protected function formatAuthors(array|string|null $authors): string
    {
        if (!$authors) return '';

        if (is_array($authors)) {
            return collect($authors)->map(fn($a) => ($a['name'] ?? '') . (isset($a['url']) ? " ({$a['url']})" : ''))->join("\n");
        }

        return (string)$authors;
    }

    private function getUploadSchema(): array
    {
        return [
            FileUpload::make('zip')
                ->label(__('filament-module-manager::filament-module.form.zip_file'))
                ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed', 'multipart/x-zip'])
                ->disk('public')
                ->directory('temp/modules')
                ->required()
                ->maxSize(intdiv($this->service->getMaxZipUploadSize(), 1024)),
        ];
    }

    private function getViewSchema(): array
    {
        return [
            Grid::make(2)
                ->schema([
                    TextEntry::make('name')->default(fn(Module $record) => $record->name)->disabled(),
                    TextEntry::make('version')->default(fn(Module $record) => $record->version ?? 'N/A')->disabled(),
                ]),
            TextEntry::make('author')->default(fn(Module $record) => $this->formatAuthors($record->authors ?? null))->disabled(),
            TextEntry::make('description')->default(fn(Module $record) => $record->description ?? 'N/A')->disabled(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ModulesOverview::class,
        ];
    }

    public static function getSlug(): string
    {
        return 'module-manager';
    }
}
