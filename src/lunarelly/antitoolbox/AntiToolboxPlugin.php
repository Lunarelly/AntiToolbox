<?php

declare(strict_types=1);

/**
 *  _                               _ _
 * | |   _   _ _ __   __ _ _ __ ___| | |_   _
 * | |  | | | |  _ \ / _  |  __/ _ \ | | | | |
 * | |__| |_| | | | | (_| | | |  __/ | | |_| |
 * |_____\____|_| |_|\____|_|  \___|_|_|\___ |
 *                                      |___/
 *
 * @author Lunarelly
 * @link https://github.com/Lunarelly
 *
 */

namespace lunarelly\antitoolbox;

use pocketmine\event\EventPriority;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\network\mcpe\protocol\types\DeviceOS;
use pocketmine\plugin\PluginBase;

use ReflectionException;

final class AntiToolboxPlugin extends PluginBase
{

    /**
     * @return void
     */
    protected function onEnable(): void
    {
        $this->saveDefaultConfig();

        try {
            $this->getServer()->getPluginManager()->registerEvent(PlayerPreLoginEvent::class, function (PlayerPreLoginEvent $event): void {
                $extraData = $event->getPlayerInfo()->getExtraData();
                if ($extraData["DeviceOS"] === DeviceOS::ANDROID) {
                    $model = explode(" ", $extraData["DeviceModel"], 2)[0];

                    if ($model !== strtoupper($model) && $model !== "") {
                        $event->setKickReason(PlayerPreLoginEvent::KICK_REASON_PLUGIN, $this->getConfig()->getNested("kick-message"));
                    }
                }
            }, EventPriority::HIGHEST, $this);
        } catch (ReflectionException $e) {
            $this->getLogger()->logException($e);
        }
    }
}