<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'type',
        'bg_color',
        'bg_image',
        'strip_color',
        'positions',
        'elements',
        'is_default',
        'status',
    ];

    protected $casts = [
        'positions' => 'array',
        'elements' => 'array',
        'is_default' => 'boolean',
    ];

    public function getBgImageAttribute($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (str_starts_with($value, 'data:image') || str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        $clean = ltrim($value, '/');
        if (preg_match('/(?:storage\/)+(.+)/i', $clean, $matches)) {
            $clean = 'storage/' . ltrim($matches[1], '/');
        } elseif (preg_match('/(?:images\/)+(.+)/i', $clean, $matches)) {
            $clean = 'images/' . ltrim($matches[1], '/');
        } elseif (str_starts_with($clean, 'events/') || str_starts_with($clean, 'templates/') || str_starts_with($clean, 'uploads/')) {
            $clean = 'storage/' . $clean;
        }

        return asset($clean);
    }

    public function getPositionsAttribute($value): array
    {
        $positions = is_string($value) ? json_decode($value, true) : ($value ?: []);
        if (is_array($positions)) {
            foreach ($positions as &$pos) {
                if (is_array($pos) && !empty($pos['src'])) {
                    $src = $pos['src'];
                    if (!str_starts_with($src, 'data:image') && !str_starts_with($src, 'http://') && !str_starts_with($src, 'https://')) {
                        $clean = ltrim($src, '/');
                        if (preg_match('/(?:storage\/)+(.+)/i', $clean, $matches)) {
                            $clean = 'storage/' . ltrim($matches[1], '/');
                        } elseif (preg_match('/(?:images\/)+(.+)/i', $clean, $matches)) {
                            $clean = 'images/' . ltrim($matches[1], '/');
                        } elseif (str_starts_with($clean, 'events/') || str_starts_with($clean, 'templates/') || str_starts_with($clean, 'uploads/')) {
                            $clean = 'storage/' . $clean;
                        }
                        $pos['src'] = asset($clean);
                    }
                }
            }
        }
        return is_array($positions) ? $positions : [];
    }
}
