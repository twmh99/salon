<?php

namespace App\Support;

class TreatmentCatalogue
{
    /**
     * Get list of treatments defined on config/treatments.php.
     */
    public static function all(): array
    {
        return config('treatments', []);
    }

    /**
     * Find treatment by its code (e.g. HC1).
     */
    public static function find(string $code): ?array
    {
        foreach (self::all() as $item) {
            if (($item['id'] ?? null) === $code) {
                return $item;
            }
        }

        return null;
    }
}
