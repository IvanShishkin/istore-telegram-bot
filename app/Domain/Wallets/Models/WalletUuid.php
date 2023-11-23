<?php

namespace App\Domain\Wallets\Models;

use App\Domain\Wallets\WalletFactory;
use Database\Factories\WalletUuidFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Domain\Wallets\Models\WalletUuid
 *
 * @property string $uuid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WalletUuid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletUuid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletUuid query()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletUuid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletUuid whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletUuid whereUuid($value)
 * @mixin \Eloquent
 */
class WalletUuid extends Model
{
    use HasFactory;

    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'uuid',
    ];

    protected static function newFactory(): WalletUuidFactory
    {
        return WalletUuidFactory::new();
    }
}
