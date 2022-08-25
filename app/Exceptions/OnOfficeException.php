<?php

namespace App\Exceptions;

use Exception;

class OnOfficeException extends Exception
{
    public const NOT_FOUND_ANY_FILE_EXCEPTION = "Not found any files";
    public const IMAGE_UPLOAD_FAILED_EXCEPTION = "Image upload failed";
    public const NOT_FOUND_LIST_FIELDS_OF_ENTITIES_TYPE = "Not found list fields of entity type";
    public const NOT_FOUND_LIST_FIELDS_OF_SEARCH_CRITERIA = "Not found list fields of search criteria";
}
