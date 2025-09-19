<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Static\Countries;
use Auth;
use Carbon\Carbon;
use Throwable;

class StaticDataService
{
     /**
     * Search countries.
     *
     * @param array $criteria
     * @param string $method Optional method: 'get' or 'find'
     * 
     * @return array Returns filtered country(ies) as an array.
     */
    public function searchCountries($criteria, $method = 'get')
    {
        try {
            // Handle exact 'find' lookups
            if ($method === 'find') {
                if (!empty($criteria['searchname'])) {
                    return is_array($criteria['searchname'])
                        ? Countries::findManyByName($criteria['searchname'])->toArray()
                        : Countries::findByName($criteria['searchname']);
                }

                if (!empty($criteria['code'])) {
                    return is_array($criteria['code'])
                        ? Countries::findMany($criteria['code'])->toArray()
                        : Countries::find($criteria['code']);
                }

                if (!empty($criteria['curr_code'])) {
                    return Countries::findByField('currency_code', $criteria['curr_code']);
                }
            }

            // Build search terms dynamically for 'get' method
            $searchTerms = [];

            if (!empty($criteria['searchname'])) {
                $searchTerms[] = $criteria['searchname'];
            }

            if (!empty($criteria['code'])) {
                $searchTerms[] = $criteria['code'];
            }

            if (!empty($criteria['curr_code'])) {
                $searchTerms[] = $criteria['curr_code'];
            }

            if (!empty($criteria['currency'])) {
                $searchTerms[] = $criteria['currency'];
            }

            // Flatten all search terms into one string and search across fields
            $searchTerm = implode(' ', (array) $searchTerms);

            // Define searchable fields
            $fields = ['country_name', 'country_code', 'currency_code', 'currency_name'];

            return Countries::search($searchTerm, $fields)->toArray();

        } catch (Throwable $e) {
            Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/static-data-service-error.log')
            ])->error("Search Countries Failed: " . $e->getMessage(), [
                'criteria' => json_encode($criteria),
                'user_id' => Auth::id() ?? 'N/A',
                'exception' => $e->getTraceAsString()
            ]);

            return [
                "error" => true,
                "message" => __('messages.country.search_failed')
            ];
        }
    }
}