<?php

namespace App\Domain\Wallets\Models;

use App\Domain\Wallets\Enums\WalletLogOperationEnum;
use App\Domain\Wallets\WalletFactory;
use Database\Factories\WalletLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Domain\Wallets\Models\WalletLog
 *
 * @property string $number
 * @property WalletLogOperationEnum $operation
 * @property int $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WalletLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletLog whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletLog whereOperation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletLog whereValue($value)
 * @mixin \Eloquent
 */
class WalletLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'operation',
        'value',
    ];

    protected $casts = [
        'operation' => WalletLogOperationEnum::class
    ];

    protected static function newFactory(): WalletLogFactory
    {
        return WalletLogFactory::new();
    }
}
