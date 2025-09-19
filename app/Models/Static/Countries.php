<?php

namespace App\Models\Static;

use Illuminate\Support\Collection;

class Countries 
{
    /**
     * Load countries from JSON file.
     *
     * @return Collection Returns a Laravel Collection of all countries.
     */
    private static function load(): Collection
    {
        $locale = app()->getLocale(); // e.g., 'en', 'swa', 'ar', etc.
        $path = storage_path("app/private/data/{$locale}/countries.json");

        if (!file_exists($path)) {
            // fallback to English
            $fallbackPath = storage_path("app/private/data/en/countries.json");
            if (!file_exists($fallbackPath)) {
                return collect(); // fallback also doesn't exist
            }
            $path = $fallbackPath;
        }

        $countries = json_decode(file_get_contents($path), true);

        return collect($countries);
    }

    /**
     * Get all available countries.
     *
     * @return Collection Returns a Laravel Collection of all countries.
     */
    public static function get(): Collection
    {
        return self::load()->keyBy(function ($item) {
            return (string) $item['country_code']; // Ensure string key
        });
    }

    /**
     * Get all countries keyed by country name (lowercased for consistency).
     *
     * @return Collection
     */
    public static function getByName(): Collection
    {
        return self::load()->keyBy(function ($item) {
            return strtolower($item['country_name']); // case-insensitive key
        });
    }

    /**
     * Find a country by its name (case-insensitive).
     *
     * @param string $name
     * @return array|null
     */
    public static function findByName(string $name): ?array
    {
        return self::getByName()->get(strtolower($name));
    }

    /**
     * Find a country by its code.
     *
     * @param string $code
     * @return array|null
     */
    public static function find(string $code): ?array
    {
        return self::get()->get((string) $code);
    }

    /**
     * Find multiple countries by their codes.
     *
     * @param array $codes
     * @return Collection Returns a collection of matching countries.
     */
    public static function findMany(array $codes): Collection
    {
        $codes = array_map('strval', $codes); // normalize all to string
        return self::get()->only($codes)->values();
    }

    /**
     * Find multiple countries by their names (case-insensitive).
     *
     * @param array $names
     * @return Collection Returns a collection of matching countries.
     */
    public static function findManyByName(array $names): Collection
    {
        $names = array_map('strtolower', $names); // normalize names
        return self::getByName()->only($names)->values();
    }

    /**
     * Find a country by any specific field (case-insensitive).
     *
     * @param string $field
     * @param string $value
     * @return array|null
     */
    public static function findByField(string $field, string $value): ?array
    {
        $value = strtolower($value);
        return self::load()->first(function ($country) use ($field, $value) {
            return isset($country[$field]) && strtolower($country[$field]) === $value;
        });
    }

     /**
     * Find multiple countries by a field and list of values (case-insensitive).
     *
     * @param string $field
     * @param array $values
     * @return Collection
     */
    public static function findManyBy(string $field, array $values): Collection
    {
        $values = array_map('strtolower', $values);
        return self::load()->filter(function ($country) use ($field, $values) {
            return isset($country[$field]) && in_array(strtolower($country[$field]), $values);
        })->values();
    }

    /**
     * Search countries across one or more fields.
     *
     * @param string $term
     * @param array|null $fields
     * @return Collection
     */
    public static function search(string $term, ?array $fields = null): Collection
    {
        $term = strtolower($term);
        return self::load()->filter(function ($country) use ($term, $fields) {
            $searchFields = $fields ?? array_keys($country);
            foreach ($searchFields as $field) {
                if (
                    isset($country[$field]) &&
                    stripos((string) $country[$field], $term) !== false
                ) {
                    return true;
                }
            }
            return false;
        })->values();
    }
}
