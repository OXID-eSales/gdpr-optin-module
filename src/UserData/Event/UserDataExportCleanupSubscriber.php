<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GdprOptinModule\UserData\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserDataExportCleanupSubscriber implements EventSubscriberInterface
{
    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserDataExportCleanupEvent::class => 'onUserDataExportCleanup',
        ];
    }

    public function onUserDataExportCleanup(UserDataExportCleanupEvent $event): void
    {
        $filePath = $event->getFilePath();

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
