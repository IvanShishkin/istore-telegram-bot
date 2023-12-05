<?php

namespace App\Domain\Wallets\Models;

use Database\Factories\StoreBalanceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Domain\Bank\Models\StoreBalance
 *
 * @method static \Illuminate\Database\Eloquent\Builder|StoreBalanceModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreBalanceModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreBalanceModel query()
 * @property int $id
 * @property int $balance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StoreBalanceModel whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreBalanceModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreBalanceModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreBalanceModel whereUpdatedAt($value)
 * @property string $number
 * @method static \Illuminate\Database\Eloquent\Builder|StoreBalanceModel whereNumber($value)
 * @method static \Database\Factories\StoreBalanceFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
class StoreBalanceModel extends Model
{
    use HasFactory;

    protected $table = 'store_balances';

    protected $fillable = [
        'balance',
        'number',
    ];

    protected static function newFactory(): StoreBalanceFactory
    {
        return StoreBalanceFactory::new();
    }
}
