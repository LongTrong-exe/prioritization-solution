<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeyModel extends Model
{
    use HasFactory;

    public const FLOWFACT_TOKEN = 'token';
    public const CUSTOMERWEBID = 'customerWebId';

    protected $table = 'keys';
    protected $fillable = ['token', 'customerWebId'];
    public $timestamps = true;

    /**
     * @var string
     */
    private $token;
    /**
     * @var string
     */
    private $customerWebId;

    /**
     * ConfigurationModel constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->token = $attributes[self::FLOWFACT_TOKEN] ?? '';
        $this->customerWebId = $attributes[self::CUSTOMERWEBID] ?? '';
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
    public function getCustomerWebId(): string
    {
        return $this->customerWebId;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @param string $customerWebId
     */
    public function setCustomerWebId(string $customerWebId): void
    {
        $this->customerWebId = $customerWebId;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::FLOWFACT_TOKEN => $this->token,
            self::CUSTOMERWEBID => $this->customerWebId,
        ];
    }
}
