<?php

namespace App\Filament\Resources\Troubleshoots\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TroubleshootForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Section 1: Informasi Utama
                Section::make('Informasi Utama')
                    ->description('Detail dasar mengenai tiket dan pelapor')
                    ->icon('heroicon-m-user-circle')
                    ->schema([
                        TextInput::make('ticket')
                            ->label('Nomor Tiket')
                            ->placeholder('Otomatis tergenerate')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('name')
                            ->label('Nama')
                            ->placeholder('Masukkan nama tiket')
                            ->required(),

                        TextInput::make('client')
                            ->label('Klien / Mitra')
                            ->placeholder('Masukkan nama klien')
                            ->required(),
                    ])->columns(3),

                // Section 2: Detail Masalah
                Section::make('Detail Masalah')
                    ->description('Kategori, tingkat prioritas, dan status penanganan masalah')
                    ->icon('heroicon-m-exclamation-circle')
                    ->schema([
                        Select::make('type')
                            ->label('Tipe Masalah')
                            ->required()
                            ->options([
                                'system' => 'System',
                                'network' => 'Network',
                                'hardware' => 'Hardware',
                            ])
                            ->default('system'),

                        Select::make('priority')
                            ->label('Prioritas')
                            ->required()
                            ->options([
                                'low' => 'Low',
                                'medium' => 'Medium',
                                'high' => 'High',
                            ])
                            ->default('medium'),

                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'open' => 'Open',
                                'in_progress' => 'In Progress',
                                'closed' => 'Closed',
                            ])
                            ->default('closed'),

                        Textarea::make('complaint')
                            ->label('Keluhan / Deskripsi Masalah')
                            ->placeholder('Jelaskan detail keluhan dari klien...')
                            ->rows(6)
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(3),

                // Section 3: Penanganan & Timeline
                Section::make('Penanganan & Timeline')
                    ->description('Catatan waktu dan langkah penyelesaian masalah')
                    ->icon('heroicon-m-clock')
                    ->schema([
                        DateTimePicker::make('incident_time')
                            ->label('Waktu Kejadian')
                            ->seconds(false), // Menghilangkan detik (opsional)

                        DateTimePicker::make('response_time')
                            ->label('Waktu Respon')
                            ->seconds(false), // Menghilangkan detik (opsional)

                        DateTimePicker::make('completion_time')
                            ->label('Waktu Selesai')
                            ->seconds(false), // Menghilangkan detik (opsional)

                        TextInput::make('handled_by')
                            ->label('Ditangani Oleh')
                            ->placeholder('Nama staf/teknisi'),

                        Textarea::make('root_cause')
                            ->label('Akar Masalah')
                            ->placeholder('Jelaskan penyebab utama masalah terjadi...')
                            ->rows(4)
                            ->columnSpanFull(),

                        Textarea::make('action')
                            ->label('Tindakan yang Dilakukan')
                            ->placeholder('Tuliskan langkah perbaikan yang dilakukan...')
                            ->rows(6)
                            ->required()
                            ->columnSpanFull(),

                        Textarea::make('notes')
                            ->label('Catatan Tambahan')
                            ->placeholder('Tuliskan catatan lain jika diperlukan...')
                            ->rows(12)
                            ->columnSpanFull(),

                        TextInput::make('latitude'),
                        TextInput::make('longitude'),
                    ])->columns(3),

                // Section 4: Dokumentasi
                Section::make('Dokumentasi')
                    ->description('Unggah gambar atau dokumen terkait untuk referensi')
                    ->icon('heroicon-m-photo')
                    ->schema([
                        FileUpload::make('images')
                            ->label(false)
                            ->multiple()
                            ->image()
                            ->imageEditor()
                            ->directory('troubleshoot-images')
                            ->panelLayout('grid')
                            ->disk('public')
                            ->reorderable()
                            ->appendFiles(),
                    ]),
            ]);
    }
}
