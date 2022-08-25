<?php

namespace App\Services;

use App\Exceptions\OnOfficeException;
use App\Services\Traits\OnOfficeCountries;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use onOffice\SDK\onOfficeSDK;

class OnOfficeConnector implements OnOfficeConnectorInterface
{
    use OnOfficeCountries;

    private string $token;
    private string $apiClaim;
    private string $secret;
    private onOfficeSDK $driver;

    public const ONOFFICE_API_VERSION = 'stable';
    public const RESOURCE_TYPE_USERS = 'users';
    public const ENTITY_TYPE_PROPERTY = 'estate';
    public const ENTITY_TYPE_ADDRESS = 'address';
    public const AGENTS_LOG = 'agentslog';
    public const ESTATE_PLZ_FIELD = 'plz';
    public const DEFAULT_LIST_LIMIT = 500;
    public const RESOURCE_ADDRESSES_TYPE = 'address';
    public const OBJEKTNR_EXTERN_FIELD = "objektnr_extern";
    public const LINK_TYPE_FILE = 'Link';
    public const NUMBER_RESEND = 2;

    private array $searchCriteriaActiveFields = [];
    private array $searchCriteriaRangeActiveFields = [];
    private array $searchCriteriaSingleSelectActiveFields = [];
    private array $searchCriteriaMultiSelectActiveFields = [];
    private array $estateActiveFieldsWithValues = [];
    private array $searchCriteriaActiveFieldsWithValues = [];
    private array $listDetailedFieldsOfEntityType = [];

    private array $listChoicesOfEntityField = [];
    private array $listIdEstates = [];
    private int $callGenericNumber = 0;
    private bool $canMergeRequest = false;
    private array $fileUploadResultData = [];
    private array $requestIds = [];

    /**
     * OnOfficeConnector constructor.
     *
     * @param onOfficeSDK $driver
     */
    public function __construct(onOfficeSDK $driver)
    {
        $this->driver = $driver;
        $this->driver->setApiVersion(self::ONOFFICE_API_VERSION);
    }

    /**
     * @param string $token
     * @param string $secret
     * @param string $apiClaim
     */
    public function init(string $token, string $secret, string $apiClaim): void
    {
        $this->token = $token;
        $this->secret = $secret;
        $this->apiClaim = $apiClaim;
    }


    /**
     * @param string $actionId
     * @param string $resourceType
     * @param array $parameters
     * @param string $resourceId
     * @param string $identifier
     *
     * @param int $stepResend
     * @return array
     * @throws OnOfficeException
     */
    private function sendRequest(
        string $actionId,
        string $resourceType,
        array $parameters = [],
        string $resourceId = '',
        string $identifier = '',
        int $stepResend = 0
    ): array {
        $parameters['extendedclaim'] = $this->apiClaim;
        $timeStart = microtime(true);
        $dataReturn = [];

        try {
            if (!empty($resourceId) || !empty($identifier)) {
                $this->requestIds[] = $this->driver->call(
                    $actionId,
                    $resourceId,
                    $identifier,
                    $resourceType,
                    $parameters
                );
            } else {
                $this->requestIds[] = $this->driver->callGeneric($actionId, $resourceType, $parameters);
            }

            $this->callGenericNumber++;
            if (!$this->canMergeRequest) {
                $this->driver->sendRequests($this->token, $this->secret);
                $this->canMergeRequest = false;
                $timeEnd = microtime(true);
                $timeExecute = round(($timeEnd - $timeStart) * 1000, 1);
                Log::channel("SendRequest")->info(
                    "{$actionId} - {$resourceType} (" . count($this->requestIds) . "): {$timeExecute}"
                );
            } else {
                $this->canMergeRequest = false;
                return [];
            }
            if (env('APP_DEBUG') === true && App::environment('test')) {
                foreach ($this->driver->getErrors() as $error) {
                    Log::error('Error message: ', $error);
                }
            }
            if (count($this->requestIds) > 1) {
                foreach ($this->requestIds as $requestId) {
                    $dataReturn[] = $this->driver->getResponseArray($requestId);
                }
                $this->requestIds = [];
                return $dataReturn;
            }

            $dataReturn = $this->driver->getResponseArray($this->requestIds[0]);
            $this->requestIds = [];
            $needResend = false;
            foreach ($this->driver->getErrors() as $error) {
                if ($error['status']['errorcode'] == 136 || $error['status']['errorcode'] == 1 || empty($dataReturn)) {
                    $needResend = true;
                    break;
                }
            }
            if ($needResend == true && $stepResend < self::NUMBER_RESEND) {
                $stepResend++;
                sleep($stepResend * 20);
                return $this->sendRequest(
                    $actionId,
                    $resourceType,
                    $parameters,
                    $resourceId,
                    $identifier,
                    $stepResend
                );
            }
        } catch (Exception $exception) {
            if ($stepResend < self::NUMBER_RESEND) {
                $stepResend++;
                sleep($stepResend * 20);
                return $this->sendRequest(
                    $actionId,
                    $resourceType,
                    $parameters,
                    $resourceId,
                    $identifier,
                    $stepResend
                );
            }
            throw new OnOfficeException(
                'Error with ' . $actionId . ' on ' . $resourceType . ' message: ' . $exception->getMessage()
            );
        }

        return $dataReturn['data']['records'] ?? $dataReturn;
    }


    /**
     *
     */
    public function mergeRequest(): void
    {
        $this->canMergeRequest = true;
    }

    /**
     * @return array
     * @throws OnOfficeException
     */
    public function getListUsers(): array
    {
        $parametersgroupId = [];

        $allUsers = $this->sendRequest(
            onOfficeSDK::ACTION_ID_GET,
            OnOfficeConnector::RESOURCE_TYPE_USERS,
            $parametersgroupId,
            ''
        );

        $dataListUsers = [];
        foreach ($allUsers as $key => $item) {
            $dataListUsers[] = $item['elements'];
        }

        if (!empty($dataListUsers)) {
            return $dataListUsers;
        }

        throw new OnOfficeException(OnOfficeException::NOT_FOUND_ANY_FILE_EXCEPTION);
    }

    /**
     * @return string
     */
    public function getDriverErrors(): string
    {
        $allErrors = $this->driver->getErrors();
        if (empty($allErrors)) {
            return '';
        }
        $lastError = end($allErrors);

        return (string)($lastError['status']['message'] ?? '');
    }

    /**
     * @param int $propertyId
     *
     * @param string $fileName
     * @param string $titleImage
     * @param string $fileContent
     * @param string $type
     * @return int
     * @throws OnOfficeException
     */
    public function uploadPhotoFile(
        int $propertyId,
        string $fileName,
        string $titleImage,
        string $fileContent,
        string $type
    ): int {
        if (empty($fileName)) {
            $fileName = "IMG_" . time();
        }

        $parametersFileUpload = [
            'file' => pathinfo($fileName)['filename'],
            'data' => $fileContent
        ];

        $dataReturn = $this->sendRequest(
            onOfficeSDK::ACTION_ID_DO,
            'uploadfile',
            $parametersFileUpload,
            '',
            '1'
        );


        if (!empty($dataReturn[0]['elements']['tmpUploadId'])) {
            $tmpUploadId = $dataReturn[0]['elements']['tmpUploadId'];
            return $this->setRelatedEstate($propertyId, $tmpUploadId, $fileName, $titleImage, $type);
        }
        throw new OnOfficeException(OnOfficeException::IMAGE_UPLOAD_FAILED_EXCEPTION);
    }

    /**
     * @param int $propertyId
     * @param string $tmpUploadId
     * @param string $filename
     * @param string $titleImage
     * @param string $type
     * @return int
     * @throws OnOfficeException
     */
    public function setRelatedEstate(
        int $propertyId,
        string $tmpUploadId,
        string $filename,
        string $titleImage,
        string $type
    ): int {
        $parametersRelatedEstate = [
            'module' => 'estate',
            'file' => $filename,
            "freetext" => '',
            "Art" => $type,
            'title' => $titleImage,
            'tmpUploadId' => $tmpUploadId,
            "setDefaultPublicationRights" => true,
            "relatedRecordId" => $propertyId,
        ];

        $dataReturn = $this->sendRequest(
            onOfficeSDK::ACTION_ID_DO,
            'uploadfile',
            $parametersRelatedEstate,
            '',
            '2'
        );

        if ($dataReturn[0]['elements']['success'] === 'success') {
            return $dataReturn[0]['elements']['fileId'];
        }
        throw new OnOfficeException(OnOfficeException::IMAGE_UPLOAD_FAILED_EXCEPTION);
    }
}
