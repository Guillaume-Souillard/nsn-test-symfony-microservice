<?php

namespace App\Tests\Command;

use App\Command\FetchAllCryptoPricesCommand;
use App\Message\FetchCryptoPricesMessage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class FetchAllCryptoPricesCommandTest extends TestCase
{
    private MessageBusInterface $messageBus;
    private FetchAllCryptoPricesCommand $command;
    private array $dispatchedMessages = [];

    protected function setUp(): void
    {
        $this->messageBus = $this->createMock(MessageBusInterface::class);

        $this->messageBus
            ->method('dispatch')
            ->willReturnCallback(function ($message) {
                $this->dispatchedMessages[] = $message;
                return new Envelope($message, [new HandledStamp($message, 'handler_name')]);
            });

        $this->command = new FetchAllCryptoPricesCommand($this->messageBus);
    }

    public function testCommandDispatchesJobs(): void
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $this->assertCount(2, $this->dispatchedMessages);
        $this->assertInstanceOf(FetchCryptoPricesMessage::class, $this->dispatchedMessages[0]);
        $this->assertInstanceOf(FetchCryptoPricesMessage::class, $this->dispatchedMessages[1]);

        $providers = array_map(fn($msg) => $msg->getProvider(), $this->dispatchedMessages);
        $this->assertContains('coinmarketcap', $providers);
        $this->assertContains('coingecko', $providers);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString("Dispatching jobs to fetch crypto prices...", $output);
        $this->assertStringContainsString("Jobs dispatched. Processing asynchronously.", $output);
    }
}
