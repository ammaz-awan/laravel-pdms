<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'street_address',
        'city',
        'state',
        'postal_code',
        'phone',
        'website',
        'latitude',
        'longitude',
        'google_place_id',
        'source',
        'external_source_url',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    protected $appends = [
        'display_name',
    ];

    /**
     * Clean messy testing/kiosk/drive-thru/screening prefixes and suffixes to return the pure relative laboratory business name.
     */
    public static function cleanDisplayName(?string $name): string
    {
        if (!$name) {
            return '';
        }

        $clean = trim($name);

        // 1. Remove leading service/testing/screening/kiosk prefixes (e.g. "COVID-19 Drive-Thru Testing at Quest Diagnostics" -> "Quest Diagnostics")
        $clean = preg_replace(
            '/^(?:.*?(?:testing|drive-thru|drive-through|screening|vaccin\w+|immuniz\w+|specimen|kiosk|collection\s+site|swab\s+site|covid(?:-19)?)\b.*?\s*(?:at|\@|[-|:])\s+)/i',
            '',
            $clean
        );

        // 2. Remove parenthetical or bracketed service descriptions (e.g. "Quest Diagnostics (Drive-thru Testing)" -> "Quest Diagnostics")
        $clean = preg_replace(
            '/\s*[\(\[\{](?:.*?(?:testing|drive-thru|drive-through|screening|vaccin\w+|immuniz\w+|covid(?:-19)?)\b.*?)[\)\]\}]/i',
            '',
            $clean
        );

        // 3. Remove trailing suffixes like " - Drive-Thru Testing", " - COVID Testing", etc.
        $clean = preg_replace(
            '/\s*[-|:]\s*(?:.*?(?:testing|drive-thru|drive-through|screening|vaccin\w+|immuniz\w+|covid(?:-19)?)\b.*?)$/i',
            '',
            $clean
        );

        // 4. Remove stray trailing punctuation
        $clean = trim($clean, " \t\n\r\0\x0B.,-:");

        return $clean ?: trim($name);
    }

    /**
     * Accessor for clean display name.
     */
    public function getDisplayNameAttribute(): string
    {
        return static::cleanDisplayName($this->attributes['name'] ?? '');
    }
}
