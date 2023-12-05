<?php

namespace App\Domain\Wallets\Models;

use App\Domain\User\Models\User;
use Database\Factories\UserWalletFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Domain\Bank\Models\UserWallet
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UserWalletModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWalletModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWalletModel query()
 * @property int $id
 * @property int $user_id
 * @property int $balance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserWalletModel whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWalletModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWalletModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWalletModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWalletModel whereUserId($value)
 * @property string $number
 * @property int $holder_id
 * @method static \Illuminate\Database\Eloquent\Builder|UserWalletModel whereHolderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWalletModel whereNumber($value)
 * @property-read User $holder
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Domain\Wallets\Models\WalletLog> $logs
 * @property-read int|null $logs_count
 * @method static \Database\Factories\UserWalletFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
class UserWalletModel extends Model
{
    use HasFactory;

    protected $table = 'user_wallets';

    protected $fillable = [
        'holder_id',
        'balance',
        'number',
    ];

    protected static function newFactory(): UserWalletFactory
    {
        return UserWalletFactory::new();
    }

    public function holder()
    {
        return $this->belongsTo(User::class, 'holder_id', 'id');
    }

    public function logs()
    {
        return $this->hasMany(WalletLog::class, 'number', 'number');
    }
}
