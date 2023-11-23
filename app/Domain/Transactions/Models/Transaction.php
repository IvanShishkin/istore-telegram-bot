<?php

namespace App\Domain\Transactions\Models;

use App\Domain\Transactions\Enums\TransactionStatusEnum;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property string $from
 * @property string|null $to
 * @property string $status
 * @property string|null $lifetime_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereLifetimeAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 * @property int $value
 * @property string|null $term_at
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTermAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereValue($value)
 * @property int $with_error
 * @property string|null $error_detail
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereErrorDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereWithError($value)
 * @mixin \Eloquent
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'status',
        'value',
        'term_at',
        'with_error',
        'error_detail'
    ];

    protected $casts = [
        'status' => TransactionStatusEnum::class
    ];

    protected static function newFactory(): TransactionFactory
    {
        return TransactionFactory::new();
    }
}
