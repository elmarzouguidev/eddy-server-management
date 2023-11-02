<?php

namespace App;

enum Provider: string
{
    case DigitalOcean = 'digital_ocean';
    case Github = 'github';
    case HetznerCloud = 'hetzner_cloud';
    case Linode = 'linode';
    case Vagrant = 'vagrant';
    case CustomServer = 'custom_server';

    public function getDisplayName(): string
    {
        return match ($this) {
            self::DigitalOcean => 'DigitalOcean',
            self::Github => 'Github',
            self::HetznerCloud => 'Hetzner Cloud',
            self::Linode => 'Linode Akamai',
            self::Vagrant => 'Vagrant',
            self::CustomServer => 'Custom',
        };
    }

    /**
     * Returns the providers that can be managed by the user (e.g. adding a token manually).
     */
    public static function userManagable(): array
    {
        return [
            self::DigitalOcean,
            self::HetznerCloud,
            self::Linode,
        ];
    }

    public static function forServers(): array
    {
        $providers = [
            self::DigitalOcean,
            self::HetznerCloud,
            self::Linode,
            self::CustomServer,
        ];

        if (! app()->isProduction()) {
            $providers[] = self::Vagrant;
        }

        return $providers;
    }
}
