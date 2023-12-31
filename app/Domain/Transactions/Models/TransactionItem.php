<?php

namespace App\Domain\Transactions\Models;

use App\Domain\Transactions\Enums\TransactionDirectionEnum;
use App\Domain\Transactions\Enums\TransactionStatusEnum;
use App\Domain\Wallets\Enums\WalletTypesEnum;
use App\Domain\Wallets\Models\UserWalletModel;
use App\Domain\Wallets\UserWallet;
use Database\Factories\TransactionFactory;
use Database\Factories\TransactionItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Domain\Transactions\Models\TransactionItem
 *
 * @property int $id
 * @property string $transaction_id
 * @property string $wallet_number
 * @property TransactionDirectionEnum $direction
 * @property WalletTypesEnum $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionItem whereDirection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionItem whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionItem whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionItem whereWalletNumber($value)
 * @property-read UserWalletModel|null $userWallet
 * @method static \Database\Factories\TransactionItemFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'wallet_number',
        'direction',
        'type'
    ];

    protected $casts = [
        'direction' => TransactionDirectionEnum::class,
        'type' => WalletTypesEnum::class
    ];

    protected static function newFactory(): TransactionItemFactory
    {
        return TransactionItemFactory::new();
    }

    public function userWallet()
    {
        return $this->belongsTo(UserWalletModel::class, 'wallet_number', 'number');
    }
}
