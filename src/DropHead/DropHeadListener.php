<?php

declare(strict_types=1);

namespace DropHead;


use onebone\economyapi\EconomyAPI;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class DropHeadListener implements Listener {

    /**
     * @param PlayerDeathEvent $event
     */
    public function onDeath(PlayerDeathEvent $event): void {
        $player = $event->getPlayer();
        if($player->getLastDamageCause() instanceof EntityDamageByEntityEvent) {
            $name = $player->getName();
            $item = Item::get(Item::MOB_HEAD, 3, 1);
            $item->setCustomName(TextFormat::GOLD . $name . " Head");
            $item->setLore([
                TextFormat::BOLD . TextFormat::ITALIC . TextFormat::GOLD . $name . " Head " .
                TextFormat::RESET . TextFormat::GRAY . "(Right-Click)",
                TextFormat::ITALIC . TextFormat::GRAY . "You have obtained the head of this ",
                TextFormat::ITALIC . TextFormat::GRAY . "player as a reward of defeating him",
                TextFormat::ITALIC . TextFormat::GRAY . "in a deadly combat competition",
                "",
                TextFormat::BOLD . TextFormat::ITALIC . TextFormat::GOLD . "Reward: " .
                TextFormat::RESET . TextFormat::GRAY . "1000 - 10000 " . TextFormat::GOLD . "Gold",
                "",
                TextFormat::BOLD . TextFormat::ITALIC . TextFormat::RED . "Warning: " .
                TextFormat::RESET . TextFormat::GRAY . "Be careful of where you ",
                TextFormat::RESET . TextFormat::GRAY . "place or right-click this head"
            ]);
            $event->setDrops(array_merge([$item], $player->getInventory()->getContents()));
        }
    }

    /**
     * @param PlayerInteractEvent $event
     */
    public function onInteract(PlayerInteractEvent $event): void {
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        if($item->getId() === Item::MOB_HEAD and $item->hasCustomName() and $event->getBlock()->getId() == Item::AIR) {
            for($i = 0; $i < $item->getCount(); $i++) {
                $this->giveReward($player, $item);
            }
        }
    }

    /**
     * @param BlockPlaceEvent $event
     */
    public function onPlace(BlockPlaceEvent $event): void {
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        if($item->getId() === Item::MOB_HEAD and $item->hasCustomName()) {
            $event->setCancelled();
        }
    }

    /**
     * @param Player $player
     * @param Item $item
     */
    public function giveReward(Player $player, Item $item) {
        $player->getInventory()->remove($item);
        $player->sendMessage(TextFormat::GREEN . "You have earned a reward!");
        EconomyAPI::getInstance()->addMoney($player, rand(1000, 10000));
    }

}