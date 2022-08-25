<?php

namespace App\Repositories;

use App\Exceptions\ConfigurationServiceException;
use App\Models\ConfigurationModel;
use Illuminate\Support\Facades\Http;

class ConfigurationServiceRepository implements ConfigurationRepositoryInterface
{
    public const SIGNATURE_HEADER = 'service-signature';

    /**
     * @param  string $customerWebId
     * @return ConfigurationModel
     * @throws ConfigurationServiceException
     */
    public function getConfigurationByCustomerWebId(string $customerWebId): ConfigurationModel
    {
        $response = Http::withHeaders(
            [
                self::SIGNATURE_HEADER => env('FLOWFACT_CONNECTOR_SIGNATURE_HEADER')
            ]
        )->get(
            env('CONFIGURATION_SERVICE') . '/config',
            [
                ConfigurationModel::CUSTOMERWEBID => $customerWebId
                ]
        );

        if ($response->status() === 404) {
            throw new ConfigurationServiceException(ConfigurationServiceException::CUSTOMER_NOT_FOUND);
        }
        return new ConfigurationModel($response->json());
    }
}
