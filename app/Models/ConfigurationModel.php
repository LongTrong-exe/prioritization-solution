<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigurationModel extends Model
{
    public const OO_TOKEN = 'oo_token';
    public const OO_SECRET_KEY = 'oo_secret';
    public const OO_API_CLAIM = 'apiClaim';
    public const CUSTOMERWEBID = 'customerWebId';

    protected $table = 'configurations';
    protected $fillable = ['oo_token', 'oo_secret', 'apiClaim', 'customerWebId'];
    public $timestamps = true;

    /**
     * @var string
     */
    private $token;
    /**
     * @var string
     */
    private $secretKey;
    /**
     * @var string
     */
    private $customerWebId;

    /**
     * @var string
     */
    private $apiClaim;

    /**
     * ConfigurationModel constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->token = $attributes[self::OO_TOKEN] ?? '';
        $this->secretKey = $attributes[self::OO_SECRET_KEY] ?? '';
        $this->customerWebId = $attributes[self::CUSTOMERWEBID] ?? '';
        $this->apiClaim = $attributes[self::OO_API_CLAIM] ?? '';
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    /**
     * @return string|null
     */
    public function getApiClaim(): ?string
    {
        return $this->apiClaim;
    }

    /**
     * @return string
     */
    public function getCustomerWebId(): string
    {
        return $this->customerWebId;
    }

    /**
     * @param string $token
     *
     * @return ConfigurationModel
     */
    public function setToken(string $token): ConfigurationModel
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @param string $secretKey
     *
     * @return ConfigurationModel
     */
    public function setSecretKey(string $secretKey): ConfigurationModel
    {
        $this->secretKey = $secretKey;
        return $this;
    }

    /**
     * @param string $apiClaim
     *
     * @return ConfigurationModel
     */
    public function setApiClaim(string $apiClaim): ConfigurationModel
    {
        $this->apiClaim = $apiClaim;
        return $this;
    }

    /**
     * @param string $customerWebId
     *
     * @return ConfigurationModel
     */
    public function setCustomerWebId(string $customerWebId): ConfigurationModel
    {
        $this->customerWebId = $customerWebId;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::OO_TOKEN => $this->token,
            self::OO_SECRET_KEY => $this->secretKey,
            self::OO_API_CLAIM => $this->apiClaim,
            self::CUSTOMERWEBID => $this->customerWebId,
        ];
    }
}
