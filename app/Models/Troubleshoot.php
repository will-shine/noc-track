<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Troubleshoot extends Model
{
    protected $fillable = [
        'ticket',
        'name',
        'client',
        'complaint',
        'incident_time',
        'response_time',
        'completion_time',
        'action',
        'root_cause',
        'handled_by',
        'priority',
        'status',
        'type',
        'notes',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    /**
     * Hitung durasi dari incident ke response.
     */
    public function getResponseDurationAttribute()
    {
        if ($this->incident_time && $this->response_time) {
            $incident = Carbon::parse($this->incident_time);
            $response = Carbon::parse($this->response_time);

            return $incident->diff($response)->format('%h jam %i menit');
        }
        return '-';
    }

    /**
     * Hitung durasi dari response ke completion.
     */
    public function getHandlingDurationAttribute()
    {
        if ($this->response_time && $this->completion_time) {
            $response = Carbon::parse($this->response_time);
            $completion = Carbon::parse($this->completion_time);

            return $response->diff($completion)->format('%h jam %i menit');
        }
        return '-';
    }

    /**
     * Hitung total durasi dari incident ke completion.
     */
    public function getTotalDurationAttribute()
    {
        if ($this->incident_time && $this->completion_time) {
            $incident = Carbon::parse($this->incident_time);
            $completion = Carbon::parse($this->completion_time);

            return $incident->diff($completion)->format('%h jam %i menit');
        }
        return '-';
    }

    protected static function booted()
    {
        static::creating(function ($ticket) {
            $date = Carbon::now()->format('Ymd');

            // Ambil tiket terakhir di hari yang sama untuk reset counter atau increment
            $latestTicket = static::whereDate('created_at', Carbon::today())->latest('id')->first();

            if ($latestTicket) {
                // Ambil 4 digit terakhir dari tiket
                $lastCounter = (int) substr($latestTicket->ticket, -4);
                $newCounter = $lastCounter + 1;
            } else {
                $newCounter = 1;
            }

            $ticket->ticket = 'air#' . $date . str_pad($newCounter, 4, '0', STR_PAD_LEFT);
        });
    }
}
