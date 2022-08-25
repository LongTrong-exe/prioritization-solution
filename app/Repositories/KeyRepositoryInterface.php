<?php

namespace App\Repositories;

interface KeyRepositoryInterface
{
    /**
     * @param string $customerWebId
     * @return mixed
     */
    public function getKeyByCustomerWebId(string $customerWebId);

    /**
     * @param string $customerWebId
     * @param array $data
     * @return mixed
     */
    public function saveKeyByCustomerWebId(string $customerWebId, array $data);
}
