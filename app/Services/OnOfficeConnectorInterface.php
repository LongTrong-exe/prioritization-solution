<?php

namespace App\Services;

interface OnOfficeConnectorInterface
{
    public function init(string $token, string $secret, string $apiClaim): void;

    public function mergeRequest(): void;

    public function getListUsers(): array;

    public function getDriverErrors(): string;

    public function uploadPhotoFile(int $propertyId, string $fileName, string $titleImage, string $fileContent, string $type): int;

    public function setRelatedEstate(int $propertyId, string $tmpUploadId, string $filename, string $titleImage, string $type): int;
}
