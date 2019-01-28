<?php

declare(strict_types=1);

namespace DropHead;


use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class DropHead extends PluginBase {

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents(new DropHeadListener(), $this);
        $this->getLogger()->info(TextFormat::GOLD . "DropHead has been enabled");
    }

}
