<?php

namespace Alizharb\FilamentModuleManager\Pages;

use Alizharb\FilamentModuleManager\Models\Module;
use Filament\Forms\Components\Select;
use UnitEnum;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Actions\{Action, ActionGroup, BulkAction, BulkActionGroup};
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\{Filter, SelectFilter};
use Alizharb\FilamentModuleManager\Services\ModuleManagerService;

class ModuleManager extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament-module-manager::module-manager';

    protected ModuleManagerService $service;

    public function boot(ModuleManagerService $service): void
    {
        $this->service = $service;
    }

    /**
     * Tell Livewire to listen for refreshTable event
     */
    protected function getListeners(): array
    {
        return [
            'refreshTable' => '$refresh',
        ];
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
                    ->icon(fn(Module $record) => $record->active ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn (Module $record) => $record->active ? 'success' : 'danger')
                    ->formatStateUsing(fn(Module $record) => $record->active ? 'enabled' : 'disabled')
                    ->tooltip(fn(Module $record) => !$this->service->canDisable($record->name) ? __('filament-module-manager::filament-module.status.cannot_be_disabled') : null),
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
                    ->action(fn(array $data) => $this->handleInstall($data)),
                Action::make('refresh')
                    ->label(__('filament-module-manager::filament-module.actions.refresh'))
                    ->icon('heroicon-o-arrow-path')
                    ->action(fn() => $this->dispatch('refreshTable')),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('enable')
                        ->label(__('filament-module-manager::filament-module.actions.enable'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('disable')
                        ->label(__('filament-module-manager::filament-module.actions.disable'))
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('uninstall')
                        ->label(__('filament-module-manager::filament-module.actions.uninstall'))
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation(),
                ]),
            ])
            ->checkIfRecordIsSelectableUsing(fn(Module $record) => $this->service->canUninstall($record->name) && $this->service->canDisable($record->name));
    }

    protected function handleEnable(string $name): void
    {
        if ($this->service->toggleModuleStatus($name, true)) {
            Notification::make()
                ->title(__('filament-module-manager::filament-module.notifications.module_enabled', ['name' => $name]))
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title(__('filament-module-manager::filament-module.notifications.module_not_found'))
                ->danger()
                ->send();
        }

        $this->dispatch('refreshTable');
    }

    protected function handleDisable(string $name): void
    {
        if ($this->service->toggleModuleStatus($name, false)) {
            Notification::make()
                ->title(__('filament-module-manager::filament-module.notifications.module_disabled', ['name' => $name]))
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title(__('filament-module-manager::filament-module.notifications.module_not_found'))
                ->danger()
                ->send();
        }

        $this->dispatch('refreshTable');
    }

    protected function handleInstall(array $data): void
    {
        $source = $data['source'] ?? 'zip';
        $result = null;

        if ($source === 'zip' && isset($data['zip'])) {
            $result = $this->service->installModulesFromZip($data['zip']);
        }

        if ($source === 'github' && !empty($data['github'])) {
            $result = $this->service->installModuleFromGitHub($data['github']);
        }

        if ($source === 'path' && !empty($data['path'])) {
            $result = $this->service->installModuleFromPath($data['path']);
        }

        if (!$result) {
            Notification::make()
                ->title(__('filament-module-manager::filament-module.notifications.module_install_error'))
                ->danger()
                ->send();
            return;
        }

        if (!empty($result->installed)) {
            $names = array_map(fn($m) => $m->name ?? 'Unknown', $result->installed);

            Notification::make()
                ->title(__('filament-module-manager::filament-module.notifications.modules_installed'))
                ->body(__('filament-module-manager::filament-module.notifications.modules_installed_body', [
                    'names' => implode(', ', $names)
                ]))
                ->success()
                ->send();
        }

        if (!empty($result->skipped)) {
            Notification::make()
                ->title(__('filament-module-manager::filament-module.notifications.modules_skipped'))
                ->body(__('filament-module-manager::filament-module.notifications.modules_skipped_body', [
                    'names' => implode(', ', $result->skipped)
                ]))
                ->warning()
                ->send();
        }

        $this->dispatch('refreshTable');
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

        $this->dispatch('refreshTable');
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
            Select::make('source')
                ->label(__('filament-module-manager::filament-module.form.source'))
                ->options([
                    'zip' => __('filament-module-manager::filament-module.form.zip_file'),
                    'github' => __('filament-module-manager::filament-module.form.github'),
                    'path' => __('filament-module-manager::filament-module.form.local_path'),
                ])
                ->searchable()
                ->default('zip')
                ->columnSpanFull()
                ->live()
                ->afterStateUpdated(function ($state, callable $set) {
                    if ($state === 'zip') {
                        $set('github', null);
                        $set('path', null);
                    } elseif ($state === 'github') {
                        $set('zip', null);
                    }
                })
                ->required()
                ->reactive(),

            FileUpload::make('zip')
                ->label(__('filament-module-manager::filament-module.form.zip_file'))
                ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed', 'multipart/x-zip'])
                ->disk(config('filament-module-manager.upload.disk', 'public'))
                ->directory(config('filament-module-manager.upload.temp_directory', 'temp/modules'))
                ->visible(fn ($get) => $get('source') === 'zip'),

            TextInput::make('github')
                ->label(__('filament-module-manager::filament-module.form.github'))
                ->placeholder('example: alizharb/my-module or https://github.com/alizharb/my-module')
                ->visible(fn ($get) => $get('source') === 'github'),

            TextInput::make('path')
                ->label(__('filament-module-manager::filament-module.form.local_path'))
                ->placeholder('/path/to/module')
                ->visible(fn ($get) => $get('source') === 'path'),
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
        if (! config('filament-module-manager.widgets.enabled', false)) {
            return [];
        }

        if (! config('filament-module-manager.widgets.page', true)) {
            return [];
        }

        return config('filament-module-manager.widgets.widgets', []);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return config('filament-module-manager.navigation.register', true);
    }

    public static function getNavigationSort(): ?int
    {
        return config('filament-module-manager.navigation.sort', 100);
    }

    public static function getNavigationIcon(): string | BackedEnum | null
    {
        return config('filament-module-manager.navigation.icon', 'heroicon-code-bracket');
    }

    public static function getNavigationGroup(): ?string
    {
        return __(config('filament-module-manager.navigation.group', 'filament-module-manager::filament-module.navigation.group'));
    }

    public static function getNavigationLabel(): string
    {
        return __(config('filament-module-manager.navigation.label', 'filament-module-manager::filament-module.navigation.label'));
    }
}
