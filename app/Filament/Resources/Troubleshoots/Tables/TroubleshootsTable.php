<?php

namespace App\Filament\Resources\Troubleshoots\Tables;

use Carbon\Carbon;
use EduardoRibeiroDev\FilamentLeaflet\Infolists\MapEntry;
use EduardoRibeiroDev\FilamentLeaflet\Support\Markers\Marker;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TroubleshootsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('incident_time', 'desc')
            ->columns([
                TextColumn::make('ticket')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->color(fn($record): string => match (strtolower($record->status)) {
                        'open' => 'danger',
                        'in_progress' => 'info',
                        'closed' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('name')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('client')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                // // --- KOLOM COMPLAINT (DENGAN DETAIL MODAL) ---
                // TextColumn::make('complaint')
                //     ->label('Complaint')
                //     ->searchable()
                //     ->limit(40) // Membatasi panjang teks agar tabel rapi
                //     ->suffix('...') // Memberikan indikasi bahwa teks dipotong
                //     ->tooltip('Klik untuk melihat detail keluhan'), // Tooltip saat di-hover
                TextColumn::make('incident_time')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('response_time')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('completion_time')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // --- DURASI / SELISIH WAKTU ---
                TextColumn::make('response_duration')
                    ->label('Response Duration')
                    ->getStateUsing(fn($record) => $record->response_duration)
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->color('gray'),

                TextColumn::make('handling_duration')
                    ->label('Handling Duration')
                    ->getStateUsing(fn($record) => $record->handling_duration)
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->color('info'),

                TextColumn::make('total_duration')
                    ->label('Total Time')
                    ->getStateUsing(fn($record) => $record->total_duration)
                    ->weight('bold')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->color('success'),

                TextColumn::make('root_cause')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),


                TextColumn::make('handled_by')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('priority')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('type')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                Filter::make('incident_time')
                    ->label('Order date')
                    ->schema([
                        DatePicker::make('created_from')
                            // ->default(now()->startOfMonth())
                            ->placeholder(fn($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                        DatePicker::make('created_until')
                            ->default(now()->endOfMonth())
                            ->placeholder(fn($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn(Builder $query, $incident_time): Builder => $query->whereDate('incident_time', '>=', $incident_time),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn(Builder $query, $incident_time): Builder => $query->whereDate('incident_time', '<=', $incident_time),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Order from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Order until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->recordActions([


                Action::make('Foto')
                    ->color('gray')
                    ->icon(Heroicon::Eye)
                    ->modalHeading('Foto')
                    ->schema([
                        Section::make()
                            ->schema([
                                Flex::make([
                                    ImageEntry::make('images')
                                        ->disk('public')

                                        ->imageSize(350)
                                        ->hiddenLabel()
                                        ->grow(false)
                                        ->url(fn($record, $state) => asset('storage/' . $state))
                                        ->openUrlInNewTab()
                                        ->alignCenter(),

                                ])->from('md'),


                            ]),
                    ])
                    ->modalAlignment(Alignment::Center)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),

                // Action::make('Catatan')
                //     ->color('gray')
                //     ->icon(Heroicon::Eye)
                //     ->modalHeading('Catatan')
                //     ->schema([
                //         Section::make()
                //             ->schema([
                //                 // Gunakan komponen form/teks untuk menampilkan data teks
                //                 \Filament\Forms\Components\Placeholder::make('notes')
                //                     ->label('Detail Catatan')
                //                     ->content(fn($record) => $record->notes ?? 'Tidak ada catatan'),
                //             ]),
                //     ])
                //     ->modalAlignment(Alignment::Center)
                //     ->modalSubmitAction(false)
                //     ->modalCancelActionLabel('Close'),


                // Action::make('location')
                //     ->color('gray')
                //     ->icon(Heroicon::Eye)
                //     ->modalHeading('location')
                //     ->schema([
                //         Section::make()
                //             ->schema([
                //                 MapEntry::make('location')
                //                     ->height(284)
                //                     ->zoom(10)
                //                     ->pickMarker(fn(Marker $marker) => $marker->red())
                //                     ->static()    // Disable interactions (enabled by default)
                //                     ->columnSpanFull(),
                //             ]),
                //     ])
                //     ->modalAlignment(Alignment::Center)
                //     ->modalSubmitAction(false)
                //     ->modalCancelActionLabel('Close'),

                // Action::make('location')
                //     ->color('gray')
                //     ->icon(Heroicon::Eye)
                //     ->modalHeading('Location')
                //     ->mountUsing(function ($form, $record) {
                //         // Mengisi form berdasarkan data record sebelum modal dibuka
                //         // Pastikan $record memiliki atribut lat dan lng atau sesuai dengan milik Anda
                //         $form->fill([
                //             'location' => [
                //                 'lat' => $record->latitude ?? null, // Ganti dengan kolom latitude Anda
                //                 'lng' => $record->longitude ?? null, // Ganti dengan kolom longitude Anda
                //             ]
                //         ]);
                //     })
                //     ->schema([
                //         Section::make()
                //             ->schema([
                //                 MapEntry::make('location')
                //                     ->height(284)
                //                     ->zoom(14) // Atur zoom agar sesuai, misalnya 14 atau 15
                //                     ->center($record->latitude ?? 0, $record->longitude ?? 0) // Memastikan pusat peta sesuai
                //                     ->latitudeFieldName('lat') // Sesuaikan dengan key di mountUsing
                //                     ->longitudeFieldName('lng') // Sesuaikan dengan key di mountUsing
                //                     ->pickMarker(fn(Marker $marker) => $marker->red())
                //                     ->static()
                //                     ->columnSpanFull(),
                //             ]),
                //     ])
                //     ->modalAlignment(Alignment::Center)
                //     ->modalSubmitAction(false)
                //     ->modalCancelActionLabel('Close'),


                // Action::make('location')
                //     ->color('gray')
                //     ->icon(Heroicon::Eye)
                //     ->modalHeading('Location')
                //     ->schema([
                //         Section::make()
                //             ->schema([
                //                 MapEntry::make('location')
                //                     ->height(284)
                //                     ->zoom(14)
                //                     ->center(
                //                         fn($record) => (float) $record?->latitude ?: 0,
                //                         fn($record) => (float) $record?->longitude ?: 0
                //                     )
                //                     ->markers(function ($record) {
                //                         return [
                //                             // Isi latitude dan longitude di dalam parameter make()
                //                             Marker::make(
                //                                 (float) $record?->latitude ?? 0,
                //                                 (float) $record?->longitude ?? 0
                //                             )->color('red'),
                //                         ];
                //                     })
                //                     ->static()
                //                     ->columnSpanFull(),
                //             ]),
                //     ])
                //     ->modalAlignment(Alignment::Center)
                //     ->modalSubmitAction(false)
                //     ->modalCancelActionLabel('Close'),


                // Action::make('location')
                //     ->color('gray')
                //     ->icon(Heroicon::Eye)
                //     ->modalHeading('Location')
                //     ->mountUsing(function ($form, $record) {
                //         // Mengisi form/data awal secara aman sebelum schema/infolist dirender
                //         $form->fill([
                //             'latitude'  => $record->latitude ?? 0,
                //             'longitude' => $record->longitude ?? 0,
                //         ]);
                //     })
                //     ->schema([
                //         Section::make()
                //             ->schema([
                //                 MapEntry::make('location')
                //                     ->height(284)
                //                     ->zoom(14)
                //                     // Ambil dari variabel atau biarkan default (leaflet akan membaca dari lat dan lng secara otomatis)
                //                     ->static()
                //                     ->columnSpanFull(),
                //             ]),
                //     ])
                //     ->modalAlignment(Alignment::Center)
                //     ->modalSubmitAction(false)
                //     ->modalCancelActionLabel('Close'),



                Action::make('location')
                    ->color('gray')
                    ->icon(Heroicon::Eye)
                    ->modalHeading('Location')
                    // ->mountUsing(function ($form, $record) {
                    //     // Mengisi form/data awal secara aman sebelum schema/infolist dirender
                    //     $form->fill([
                    //         'latitude'  => $record->latitude ?? 0,
                    //         'longitude' => $record->longitude ?? 0,
                    //     ]);
                    // })
                    ->schema([
                        Section::make()
                            ->schema([
                                MapEntry::make('location')
                                    // ->mapDraggable(true)
                                    ->height(484)
                                    ->zoom(15)
                                    ->pickMarker(fn(Marker $marker) => $marker->red())
                                    // ->static()    // Disable interactions (enabled by default)
                                    ->columnSpanFull()


                            ]),
                    ])
                    ->modalAlignment(Alignment::Center)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),






                EditAction::make(),

            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
