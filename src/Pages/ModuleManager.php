<?php

namespace Alizharb\FilamentModuleManager\Pages;

use Alizharb\FilamentModuleManager\Models\Module;
use Alizharb\FilamentModuleManager\Models\ModuleAuditLog;
use Alizharb\FilamentModuleManager\Services\ModuleBackupService;
use Alizharb\FilamentModuleManager\Services\ModuleHealthService;
use Alizharb\FilamentModuleManager\Services\ModuleManagerService;
use Alizharb\FilamentModuleManager\Services\ModuleUpdateService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ModuleManager extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament-module-manager::module-manager';

    protected ModuleManagerService $service;

    protected ModuleUpdateService $updateService;

    protected ModuleBackupService $backupService;

    protected ModuleHealthService $healthService;

    public function boot(
        ModuleManagerService $service,
        ModuleUpdateService $updateService,
        ModuleBackupService $backupService,
        ModuleHealthService $healthService
    ): void {
        $this->service = $service;
        $this->updateService = $updateService;
        $this->backupService = $backupService;
        $this->healthService = $healthService;
    }

    public static function canAccess(): bool
    {
        return Gate::check('viewAny', Module::class);
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
                    ->badge()
                    ->color('info'),
                TextColumn::make('health')
                    ->label(__('filament-module-manager::filament-module.table.health'))
                    ->getStateUsing(function (Module $record) {
                        $health = $this->healthService->getLatestHealth($record->name);

                        return $health ? $health->score.'%' : 'N/A';
                    })
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        (int) $state >= 80 => 'success',
                        (int) $state >= 50 => 'warning',
                        default => 'danger',
                    })
                    ->tooltip(function (Module $record) {
                        $health = $this->healthService->getLatestHealth($record->name);

                        return $health ? $health->message : __('filament-module-manager::filament-module.health.not_checked');
                    }),
                TextColumn::make('active')
                    ->label(__('filament-module-manager::filament-module.table.status'))
                    ->sortable()
                    ->badge()
                    ->icon(fn (Module $record) => $record->active ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn (Module $record) => $record->active ? 'success' : 'danger')
                    ->formatStateUsing(fn (Module $record) => $record->active ? 'enabled' : 'disabled')
                    ->tooltip(fn (Module $record) => ! $this->service->canDisable($record->name) ? __('filament-module-manager::filament-module.status.cannot_be_disabled') : null),
                TextColumn::make('path')
                    ->label(__('filament-module-manager::filament-module.table.module_path'))
                    ->wrap()
                    ->extraAttributes(['class' => 'text-xs'])
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('install_dependencies')
                        ->label(__('filament-module-manager::filament-module.maintenance.install_dependencies'))
                        ->icon('heroicon-o-command-line')
                        ->action(fn (Module $record) => $this->handleInstallDependencies($record->name))
                        ->requiresConfirmation(),

                    Action::make('migrate')
                        ->label(__('filament-module-manager::filament-module.maintenance.run_migrations'))
                        ->icon('heroicon-o-circle-stack')
                        ->action(fn (Module $record) => $this->handleRunMigrations($record->name))
                        ->requiresConfirmation(),

                    Action::make('seed')
                        ->label(__('filament-module-manager::filament-module.maintenance.run_seeds'))
                        ->icon('heroicon-o-play')
                        ->action(fn (Module $record) => $this->handleRunSeeds($record->name))
                        ->requiresConfirmation(),

                    Action::make('edit_config')
                        ->label(__('filament-module-manager::filament-module.maintenance.edit_config'))
                        ->icon('heroicon-o-cog')
                        ->fillForm(fn (Module $record) => ['config' => $this->getModuleConfigJson($record->name)])
                        ->schema([
                            Textarea::make('config')
                                ->label(__('filament-module-manager::filament-module.maintenance.config_label'))
                                ->rows(15)
                                ->required()
                                ->json(),
                        ])
                        ->action(fn (Module $record, array $data) => $this->saveModuleConfigJson($record->name, $data['config']))
                        ->modalWidth('xl'),

                    Action::make('check_health')
                        ->label(__('filament-module-manager::filament-module.maintenance.check_health'))
                        ->icon('heroicon-o-heart')
                        ->action(fn (Module $record) => $this->handleCheckHealth($record->name)),

                    Action::make('create_backup')
                        ->label(__('filament-module-manager::filament-module.maintenance.create_backup'))
                        ->icon('heroicon-o-archive-box-arrow-down')
                        ->form([
                            TextInput::make('reason')
                                ->label(__('filament-module-manager::filament-module.maintenance.backup_reason'))
                                ->placeholder('Optional reason'),
                        ])
                        ->action(fn (Module $record, array $data) => $this->handleCreateBackup($record->name, $data['reason'])),

                    Action::make('restore_backup')
                        ->label(__('filament-module-manager::filament-module.maintenance.restore_backup'))
                        ->icon('heroicon-o-archive-box')
                        ->schema(function (Module $record) {
                            $backups = $this->backupService->listBackups($record->name);
                            $options = collect($backups)->mapWithKeys(fn ($b) => [
                                $b['id'] => "{$b['createdAt']} (".number_format($b['sizeBytes'] / 1024, 2).' KB)'.($b['reason'] ? " - {$b['reason']}" : ''),
                            ])->toArray();

                            return [
                                Select::make('backup_id')
                                    ->label(__('filament-module-manager::filament-module.maintenance.select_backup'))
                                    ->options($options)
                                    ->required()
                                    ->searchable(),
                            ];
                        })
                        ->action(fn (array $data) => $this->handleRestoreBackup((int) $data['backup_id']))
                        ->requiresConfirmation()
                        ->modalWidth('lg'),

                    Action::make('check_update')
                        ->label(__('filament-module-manager::filament-module.maintenance.check_update'))
                        ->icon('heroicon-o-cloud-arrow-down')
                        ->action(fn (Module $record) => $this->handleCheckUpdate($record->name)),
                ])
                    ->label(__('filament-module-manager::filament-module.maintenance.title'))
                    ->icon('heroicon-o-wrench')
                    ->color('gray')
                    ->visible(fn (Module $record) => Gate::allows('update', $record)),

                ActionGroup::make([
                    Action::make('view')
                        ->label(__('filament-module-manager::filament-module.actions.view'))
                        ->icon('heroicon-o-eye')
                        ->modal()
                        ->modalHeading(fn (Module $record) => __('filament-module-manager::filament-module.actions.view_module', ['name' => $record->name]))
                        ->schema(fn (Schema $schema) => $schema->components($this->getViewSchema()))
                        ->modalSubmitAction(false)
                        ->modalWidth('2xl')
                        ->visible(fn (Module $record) => Gate::allows('view', $record)),

                    Action::make('history')
                        ->label(__('filament-module-manager::filament-module.history.title'))
                        ->icon('heroicon-o-clock')
                        ->modal()
                        ->modalHeading(fn (Module $record) => __('filament-module-manager::filament-module.history.modal_heading', ['name' => $record->name]))
                        ->schema(fn (Schema $schema) => $schema->components($this->getHistorySchema()))
                        ->modalSubmitAction(false)
                        ->visible(fn (Module $record) => Gate::allows('view', $record)),

                    Action::make('enable')
                        ->label(__('filament-module-manager::filament-module.actions.enable'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Module $record) => ! $record->active && Gate::allows('enable', $record))
                        ->action(fn (Module $record) => $this->handleEnable($record->name)),

                    Action::make('disable')
                        ->label(__('filament-module-manager::filament-module.actions.disable'))
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (Module $record) => $record->active && $this->service->canDisable($record->name) && Gate::allows('disable', $record))
                        ->action(fn (Module $record) => $this->handleDisable($record->name)),

                    Action::make('uninstall')
                        ->label(__('filament-module-manager::filament-module.actions.uninstall'))
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn (Module $record) => $this->service->canUninstall($record->name) && Gate::allows('delete', $record))
                        ->action(fn (Module $record) => $this->handleUninstall($record->name)),
                ]),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament-module-manager::filament-module.filters.status'))
                    ->options([
                        'enabled' => __('filament-module-manager::filament-module.status.enabled'),
                        'disabled' => __('filament-module-manager::filament-module.status.disabled'),
                    ])
                    ->query(fn ($query, $data) => match ($data['value'] ?? null) {
                        'enabled' => $query->where('active', true),
                        'disabled' => $query->where('active', false),
                        default => $query,
                    }),
                Filter::make('name')
                    ->label(__('filament-module-manager::filament-module.filters.name'))
                    ->schema([TextInput::make('name')->placeholder(__('filament-module-manager::filament-module.filters.name_placeholder'))])
                    ->query(fn ($query, $data) => $data['name'] ? $query->where('name', 'like', "%{$data['name']}%") : $query),
            ])
            ->headerActions([
                Action::make('install')
                    ->label(__('filament-module-manager::filament-module.actions.install'))
                    ->icon('heroicon-o-arrow-up-tray')
                    ->schema($this->getUploadSchema())
                    ->action(fn (array $data) => $this->handleInstall($data))
                    ->visible(fn () => Gate::allows('create', Module::class)),
                Action::make('refresh')
                    ->label(__('filament-module-manager::filament-module.actions.refresh'))
                    ->icon('heroicon-o-arrow-path')
                    ->action(fn () => $this->dispatch('refreshTable')),
            ])
            ->toolbarActions([
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
            ->checkIfRecordIsSelectableUsing(fn (Module $record) => $this->service->canUninstall($record->name) && $this->service->canDisable($record->name));
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

        if ($source === 'github' && ! empty($data['github'])) {
            $result = $this->service->installModuleFromGitHub($data['github']);
        }

        if ($source === 'path' && ! empty($data['path'])) {
            $result = $this->service->installModuleFromPath($data['path']);
        }

        if (! $result) {
            Notification::make()
                ->title(__('filament-module-manager::filament-module.notifications.module_install_error'))
                ->danger()
                ->send();

            return;
        }

        if (! empty($result->installed)) {
            $names = array_map(fn ($m) => $m->name ?? 'Unknown', $result->installed);

            Notification::make()
                ->title(__('filament-module-manager::filament-module.notifications.modules_installed'))
                ->body(__('filament-module-manager::filament-module.notifications.modules_installed_body', [
                    'names' => implode(', ', $names),
                ]))
                ->success()
                ->send();
        }

        if (! empty($result->skipped)) {
            Notification::make()
                ->title(__('filament-module-manager::filament-module.notifications.modules_skipped'))
                ->body(__('filament-module-manager::filament-module.notifications.modules_skipped_body', [
                    'names' => implode(', ', $result->skipped),
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

    protected function handleInstallDependencies(string $name): void
    {
        if ($this->service->installDependencies($name)) {
            Notification::make()->title(__('filament-module-manager::filament-module.notifications.dependencies_installed', ['name' => $name]))->success()->send();
        } else {
            Notification::make()->title(__('filament-module-manager::filament-module.notifications.dependencies_failed', ['name' => $name]))->danger()->send();
        }
    }

    protected function handleRunMigrations(string $name): void
    {
        if ($this->service->runMigrations($name)) {
            Notification::make()->title(__('filament-module-manager::filament-module.notifications.migrations_run', ['name' => $name]))->success()->send();
        } else {
            Notification::make()->title(__('filament-module-manager::filament-module.notifications.migrations_failed', ['name' => $name]))->danger()->send();
        }
    }

    protected function handleRunSeeds(string $name): void
    {
        if ($this->service->runSeeds($name)) {
            Notification::make()->title(__('filament-module-manager::filament-module.notifications.seeds_run', ['name' => $name]))->success()->send();
        } else {
            Notification::make()->title(__('filament-module-manager::filament-module.notifications.seeds_failed', ['name' => $name]))->danger()->send();
        }
    }

    protected function handleCheckHealth(string $name): void
    {
        $result = $this->healthService->checkHealth($name);

        Notification::make()
            ->title(__('filament-module-manager::filament-module.notifications.health_check_completed', ['name' => $name]))
            ->body($result->message)
            ->status($result->status === 'healthy' ? 'success' : ($result->status === 'warning' ? 'warning' : 'danger'))
            ->send();

        $this->dispatch('refreshTable');
    }

    protected function handleCreateBackup(string $name, ?string $reason): void
    {
        try {
            $this->backupService->createBackup($name, $reason);
            Notification::make()->title(__('filament-module-manager::filament-module.notifications.backup_created', ['name' => $name]))->success()->send();
        } catch (\Exception $e) {
            Notification::make()->title(__('filament-module-manager::filament-module.notifications.backup_failed', ['name' => $name]))->body($e->getMessage())->danger()->send();
        }
    }

    protected function handleRestoreBackup(int $backupId): void
    {
        try {
            $this->backupService->restoreBackup($backupId);
            Notification::make()->title(__('filament-module-manager::filament-module.notifications.backup_restored'))->success()->send();
            $this->dispatch('refreshTable');
        } catch (\Exception $e) {
            Notification::make()->title(__('filament-module-manager::filament-module.notifications.restore_failed'))->body($e->getMessage())->danger()->send();
        }
    }

    protected function handleCheckUpdate(string $name): void
    {
        $updateData = $this->updateService->checkForUpdate($name);

        if (! $updateData) {
            Notification::make()->title(__('filament-module-manager::filament-module.notifications.update_check_failed'))->danger()->send();

            return;
        }

        if ($updateData->updateAvailable) {
            Notification::make()
                ->title(__('filament-module-manager::filament-module.notifications.update_available', ['version' => $updateData->latestVersion]))
                ->body($updateData->changelog ? Str::limit($updateData->changelog, 200) : null)
                ->actions([
                    NotificationAction::make('update')
                        ->button()
                        ->label('Update Now')
                        ->url($updateData->downloadUrl, shouldOpenInNewTab: true), // For now, linking to download, as automatic update is complex without queue
                ])
                ->success()
                ->persistent()
                ->send();
        } else {
            Notification::make()
                ->title(__('filament-module-manager::filament-module.notifications.no_updates_available'))
                ->success()
                ->send();
        }
    }

    protected function getModuleConfigJson(string $moduleName): string
    {
        $modulePath = $this->service->getPathToModule($moduleName);
        $path = "{$modulePath}/module.json";

        return File::exists($path)
            ? File::get($path)
            : '{}';
    }

    protected function saveModuleConfigJson(string $moduleName, string $content): void
    {
        $modulePath = $this->service->getPathToModule($moduleName);
        $path = "{$modulePath}/module.json";

        File::put($path, $content);

        Notification::make()->title(__('filament-module-manager::filament-module.notifications.config_saved', ['name' => $moduleName]))->success()->send();
        $this->dispatch('refreshTable');
    }

    protected function formatAuthors(array|string|null $authors): string
    {
        if (! $authors) {
            return '';
        }

        if (is_array($authors)) {
            return collect($authors)->map(fn ($a) => ($a['name'] ?? '').(isset($a['url']) ? " ({$a['url']})" : ''))->join("\n");
        }

        return (string) $authors;
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

    private function getHistorySchema(): array
    {
        return [
            RepeatableEntry::make('logs')
                ->label(__('filament-module-manager::filament-module.history.log_label'))
                ->schema([
                    TextEntry::make('action')->badge()->label(__('filament-module-manager::filament-module.history.action')),
                    TextEntry::make('user_name')->label(__('filament-module-manager::filament-module.history.user')),
                    TextEntry::make('created_at')->dateTime()->label(__('filament-module-manager::filament-module.history.date')),
                    TextEntry::make('success')
                        ->label(__('filament-module-manager::filament-module.history.result'))
                        ->formatStateUsing(fn (bool $state) => $state ? __('filament-module-manager::filament-module.history.success') : __('filament-module-manager::filament-module.history.failed'))
                        ->color(fn (bool $state) => $state ? 'success' : 'danger'),
                    TextEntry::make('error_message')->label(__('filament-module-manager::filament-module.history.error'))->visible(fn ($state) => ! empty($state)),
                ])
                ->default(function (Module $record) {
                    return ModuleAuditLog::where('module_name', $record->name)
                        ->orderBy('created_at', 'desc')
                        ->take(20)
                        ->get();
                })
                ->columnSpanFull(),
        ];
    }

    private function getViewSchema(): array
    {
        return [
            Tabs::make('Tabs')
                ->tabs([
                    Tab::make('Info')
                        ->label(__('filament-module-manager::filament-module.tabs.info'))
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextEntry::make('name')->default(fn (Module $record) => $record->name),
                                    TextEntry::make('version')->default(fn (Module $record) => $record->version ?? 'N/A'),
                                ]),
                            TextEntry::make('author')->default(fn (Module $record) => $this->formatAuthors($record->authors ?? null)),
                            TextEntry::make('description')->default(fn (Module $record) => $record->description ?? 'N/A'),
                        ]),
                    Tab::make('Readme')
                        ->label(__('filament-module-manager::filament-module.tabs.readme'))
                        ->schema([
                            TextEntry::make('readme')
                                ->hiddenLabel()
                                ->markdown()
                                ->default(fn (Module $record) => $this->getModuleReadme($record->name))
                                ->prose(),
                        ]),
                ])->columnSpanFull(),
        ];
    }

    protected function getModuleReadme(string $moduleName): string
    {
        // Try common readme filenames
        $basePath = $this->service->getPathToModule($moduleName);
        $candidates = ['README.md', 'readme.md', 'Readme.md', 'README.txt'];

        foreach ($candidates as $candidate) {
            if (File::exists("{$basePath}/{$candidate}")) {
                return File::get("{$basePath}/{$candidate}");
            }
        }

        return __('filament-module-manager::filament-module.readme.not_found');
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

    public static function getNavigationIcon(): string|BackedEnum|null
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
