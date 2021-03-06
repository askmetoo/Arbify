<?php

namespace Arbify\Providers;

use Arbify\Contracts\Repositories as Contracts;
use Arbify\Repositories as Repositories;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    public array $singletons = [
        Contracts\CountryFlagRepository::class   => Repositories\CountryFlagRepository::class,
        Contracts\LanguageRepository::class      => Repositories\LanguageRepository::class,
        Contracts\MessageRepository::class       => Repositories\MessageRepository::class,
        Contracts\MessageValueRepository::class  => Repositories\MessageValueRepository::class,
        Contracts\MessagesTranslationStatisticsRepository::class
            => Repositories\MessagesTranslationStatisticsRepository::class,
        Contracts\ProjectRepository::class       => Repositories\ProjectRepository::class,
        Contracts\ProjectMemberRepository::class => Repositories\ProjectMemberRepository::class,
        Contracts\SecretRepository::class        => Repositories\SecretRepository::class,
        Contracts\SettingsRepository::class      => Repositories\SettingsRepository::class,
        Contracts\UserRepository::class          => Repositories\UserRepository::class,
    ];
}
