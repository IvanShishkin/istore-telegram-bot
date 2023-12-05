<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Domain\Products\Models{
/**
 * App\Domain\Products\Models\Product
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property int $active
 * @property string $description
 * @property int $price
 * @property int $stock
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @property string|null $image_path
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImagePath($value)
 * @mixin \Eloquent
 */
	class Product extends \Eloquent {}
}

namespace App\Domain\Store\Models{
/**
 * App\Domain\Store\Models\Order
 *
 * @property int $id
 * @property int $user_id
 * @property string $status
 * @property int $canceled
 * @property int $product_id
 * @property int $price
 * @property string $transaction_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCanceled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @property-read User $customer
 * @property-read Product|null $product
 * @method static \Database\Factories\OrderFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class Order extends \Eloquent {}
}

namespace App\Domain\Transactions\Models{
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
 * @property-read \App\Domain\Transactions\Models\TransactionItem|null $items
 * @method static \Database\Factories\TransactionFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class Transaction extends \Eloquent {}
}

namespace App\Domain\Transactions\Models{
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
	class TransactionItem extends \Eloquent {}
}

namespace App\Domain\User\Models{
/**
 * App\Domain\User\Models\User
 *
 * @property mixed $password
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property int|null $external_id
 * @property int $active
 * @property string|null $confirm_token
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereConfirmToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @property string $name
 * @property int $is_admin
 * @property-read string $full_name
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

namespace App\Domain\Wallets\Models{
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
	class StoreBalanceModel extends \Eloquent {}
}

namespace App\Domain\Wallets\Models{
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
	class UserWalletModel extends \Eloquent {}
}

namespace App\Domain\Wallets\Models{
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
 * @property int $id
 * @property string|null $comment
 * @method static \Database\Factories\WalletLogFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|WalletLog whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletLog whereId($value)
 * @mixin \Eloquent
 */
	class WalletLog extends \Eloquent {}
}

namespace App\Domain\Wallets\Models{
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
 * @method static \Database\Factories\WalletUuidFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
	class WalletUuid extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Payment
 *
 * @property int $id
 * @property string $transaction_id
 * @property int $amount
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUpdatedAt($value)
 */
	class Payment extends \Eloquent {}
}

