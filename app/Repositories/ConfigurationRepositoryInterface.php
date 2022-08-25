<?php

namespace App\Repositories;

use App\Models\ConfigurationModel;

interface ConfigurationRepositoryInterface
{
    /**
     * @param string $customerWebId
     *
     * @return ConfigurationModel
     */
    public function getConfigurationByCustomerWebId(string $customerWebId): ConfigurationModel;
}
