<?php

namespace Blomstra\SupportAi;

use Blomstra\SupportAi\Agent\Message;
use Flarum\Extension\ExtensionManager;
use Flarum\Foundation\AbstractServiceProvider;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\UserRepository;
use OpenAI;
use OpenAI\Client;

class ClientProvider extends AbstractServiceProvider
{
    public function boot()
    {
        /** @var SettingsRepositoryInterface $settings */
        $settings = $this->container->make(SettingsRepositoryInterface::class);

        $apiKey = $settings->get('blomstra-support-ai.openai-api-key');
        $organisation = $settings->get('blomstra-support-ai.openai-api-organisation');

        if ($apiKey) {
            $this->container->singleton(Client::class, fn () => OpenAI::client($apiKey, $organisation));
        }

        /** @var ExtensionManager $extensions */
        $extensions = $this->container->make(ExtensionManager::class);

        $this->container->singleton(Agent::class, fn () => $this->getAgent($settings, $extensions));
    }

    protected function getAgent(SettingsRepositoryInterface $settings, ExtensionManager $extensions): Agent
    {
        $username = $settings->get('blomstra-support-ai.username') ?? 'admin';
        $persona = $settings->get('blomstra-support-ai.persona');

        /** @var UserRepository $users */
        $users = $this->container->make(UserRepository::class);
        $user = $users->findOrFailByUsername($username);

        /** @var Client $client */
        $client = $this->container->has(Client::class)
            ? $this->container->make(Client::class)
            : null;

        $agent = new Agent(
            user: $user,
            persona: $persona,
            client: $client
        );

        $agent->toggleMentioning($extensions->isEnabled('flarum-mentions'));

        Message::$agent = $agent;

        return $agent;
    }
}
