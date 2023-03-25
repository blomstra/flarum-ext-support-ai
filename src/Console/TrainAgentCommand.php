<?php

namespace Blomstra\SupportAi\Console;

use Blomstra\SupportAi\Agent;
use Illuminate\Console\Command;
use Illuminate\Container\Container;
use OpenAI\Client;

class TrainAgentCommand extends Command
{
    protected $signature = 'blomstra:support-ai:feed-agent';
    protected $description = 'Feeds the agent with your most valuable content.';

    public function handle(Agent $agent, Container $container, Agent\Training $training)
    {
        if (! $agent->operational()) {
            $this->error('Client not operational.');

            return Command::FAILURE;
        }

        $data = $training->data();

        if ($data->isEmpty()) {
            $this->error('No data for training.');

            return Command::FAILURE;
        }

        /** @var Client $client */
        $client = $container->make(Client::class);

        $embedding = $client->embeddings()->create([
            'model' => 'text-embedding-ada-002',
            'input' => $data
                ->prepend(['id', 'question', 'answer'])
                ->toJson()
        ]);

        dd($embedding);
    }

}
