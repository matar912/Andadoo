<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceLog extends Model
{
    use HasFactory;

    protected $fillable = ['vehicle_id', 'performed_at', 'description', 'cost'];

    protected function casts(): array
    {
        return ['performed_at' => 'date', 'cost' => 'decimal:2'];
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
