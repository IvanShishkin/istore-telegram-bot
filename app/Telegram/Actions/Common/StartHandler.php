<?php

declare(strict_types=1);

namespace App\Telegram\Actions\Common;

use App\Telegram\Actions\StartAction;
use Illuminate\Support\Facades\Storage;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;

/**
 * Обработчик приветственной команды /start
 * Отправлять текст с изображением
 */
final class StartHandler
{
    public function __invoke(Nutgram $bot): void
    {
        $imagePreviewPath = Storage::disk('public')->path('store-preview.png');
        $file = fopen($imagePreviewPath, 'r+');

        $bot->sendPhoto(
            photo: InputFile::make($file),
            caption: __('bot_common.welcome'),
            parse_mode: 'HTML',
        );
    }
}
