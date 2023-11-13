<?php

namespace App\Telegram;

use App\Telegram\Extends\Test;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Webhook;

abstract class AbstractBotProcessor
{
    public function __construct(protected Nutgram $bot)
    {
    }

    abstract protected function defineHandlers();

    public function process(): void
    {
        $this->bot->setRunningMode(Webhook::class);

        $this->defineHandlers();

        try {
            $this->bot->run();
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
        }
    }

    /**
     * @return Nutgram
     */
    public function getBot(): Nutgram
    {
        return $this->bot;
    }

}
