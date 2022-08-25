<?php

namespace App\Repositories;

use App\Models\KeyModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KeyServiceRepository implements KeyRepositoryInterface
{
    /**
     * @param string $customerWebId
     * @return KeyModel
     */
    public function getKeyByCustomerWebId(string $customerWebId): KeyModel
    {
        /**
         *
         *
         * @var KeyModel $dataConfig
         */
        $dataConfig = KeyModel::where('customerWebId', $customerWebId)->first();

        if (!empty($dataConfig)) {
            return new KeyModel($dataConfig->getAttributes());
        }

        throw new ModelNotFoundException();
    }

    /**
     * @param string $customerWebId
     * @param array $data
     */
    public function saveKeyByCustomerWebId(string $customerWebId, array $data): void
    {
        KeyModel::updateOrCreate(
            [
                'customerWebId' => $customerWebId,
            ],
            [
                'token' => $data['token'],
            ]
        );
    }
}
