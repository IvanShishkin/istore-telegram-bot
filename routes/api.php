<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::any('/telegram_webhook', \App\Http\Controllers\TelegramController::class);

Route::any('/regUser', function (\App\Domain\User\Services\UserAuthService $service) {
    $service->registrationConfirmation('5HdQQpWz');
});

Route::any('test2', function (\App\Domain\Transactions\Services\TerminateService $service) {
    $list = $service->exec();
    dd($list);
});

Route::any('/createTransaction', function(
    \App\Domain\Transactions\Actions\CreateTransactionAction $action,
    \App\Domain\Wallets\Services\UserWalletService $userWalletService,
    \App\Domain\Wallets\Services\StoreWalletService $storeWalletService,
) {
    $walletFrom = $userWalletService->initWallet(1);
    $walletTo = $storeWalletService->initWallet();

    $res = $action->execute(new \App\Domain\Transactions\Dto\TransactionDto(
        from: $walletFrom,value: 100,to: $walletTo
    ));

    dd($res);
});

Route::any('/applyTransaction', function(
    \App\Domain\Transactions\Actions\ApplyTransactionAction $action,
    \App\Domain\Wallets\Services\UserWalletService $userWalletService,
    \App\Domain\Wallets\Services\StoreWalletService $storeWalletService,
) {
    $walletFrom = $userWalletService->initWallet(1);
    $walletTo = $storeWalletService->initWallet();

    $action->execute('17e4a9a8-6389-4b95-a77c-2a21f8dedbaa');
});

Route::any('/cancelTransaction', function(
    \App\Domain\Transactions\Actions\CancelTransactionAction $action,
    \App\Domain\Wallets\Services\UserWalletService $userWalletService,
    \App\Domain\Wallets\Services\StoreWalletService $storeWalletService,
) {
    $walletFrom = $userWalletService->initWallet(1);
    $walletTo = $storeWalletService->initWallet();

    $action->execute('9718780c-2834-4a3b-815c-4591db84d437');
});

Route::get('ff', function (\App\Domain\Products\Services\ProductService $service) {
    $service->reduceStock(2);
});

Route::get('createOrder', function (\App\Domain\Store\Actions\CreateOrderAction $service) {
    $user = App::make(\App\Domain\User\Services\UserService::class);
    $userDto = $user->byId(1);

    $product = App::make(\App\Domain\Products\Services\ProductService::class);
    $productDto = $product->get(1);
    //dd($userDto);
    $service->execute(
        $userDto,
        $productDto
    );

});

Route::get('crateStoreWallet', function (\App\Domain\Wallets\Services\StoreWalletService $service) {
    $service->create();
});

Route::get('crateUserWallet', function (\App\Domain\Wallets\Services\UserWalletService $service) {
    $service->create(1);
});



Route::get('changeStatus', function (\App\Domain\Store\Actions\CancelOrderAction $action) {
    $action->execute(4);

});

Route::any('/w', function (\App\Domain\Transactions\Actions\ApplyTransactionAction $action,
\App\Domain\Wallets\Actions\CreateStoreBalanceAction $balanceAction,
\App\Domain\Wallets\Services\UserWalletService $walletService) {
    $action->execute('16e3d323-cce9-4059-b0ef-5e232896c4ce', new \App\Domain\Wallets\StoreWallet('56eab60b-438c-4d6a-bedc-bdcc5ec79471'));

    $dto = $walletService->getWalletData(1);
    $wl = new \App\Domain\Wallets\UserWallet($dto->number);
    //$action->execute('d460425e-b9c2-414c-aab5-93eaf3706b6c');
    /*$wallet = new \App\Domain\Wallets\UserWallet('a79ce0e2-da73-4fec-9e3a-7c6cfff9c498');
    $action->execute(new \App\Domain\Transactions\Dto\TransactionDto(
        from: $wallet,
        value: 100
    ));*/
    /*$test = $repository->write(new \App\Domain\Wallets\Dto\WalletLogDto(
       number: 'a79ce0e2-da73-4fec-9e3a-7c6cfff9c498',operation: \App\Domain\Wallets\Enums\WalletLogOperationEnum::REDUCE,value: 11
    ));*/

   /* DB::transaction(function () use ($service){
        $fac = new \App\Domain\Wallets\WalletFactory();
        $test = $fac->make('a79ce0e2-da73-4fec-9e3a-7c6cfff9c498', \App\Domain\Wallets\Enums\WalletTypesEnum::USER);

        $service->increase($test, 100);
        $service->increase($test, 100);
        $service->reduce($test, 11);
    });*/



    //dd($test->increase(1));

    /*try {
        DB::beginTransaction();
        $wl = new \App\Domain\Wallets\StoreWallet();
        $wl->increase(5);
        //$wl->reduce(11);
        $b = $wl->balance();
        var_dump($b);
    } catch (\Exception $e) {
        var_dump($e->getMessage());
        DB::rollBack();
    }

    DB::commit();*/

});

Route::any('/test', function (Request $request) {
    if (($request->get('email') && str_contains($request->get('email'), '@example.com')) || ($request->get('company') && str_contains($request->get('company'), 'example.com'))) {
        dd($request);
    }
});
