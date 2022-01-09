<?php

namespace yomogibeta\reallySimpleWarp\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class setWarpPointCommand extends Command
{
    private $main;
    public function __construct($main)
    {
        parent::__construct("setwa", "ワープ地点の追加", "/setwa <地点の名前>");
        $this->setPermission("yomogi.simplewarp.onlyop.cmd");
        $this->main = $main;
    }

    public function execute(CommandSender $sender, string $label, array $args): bool
    {
        if (!$sender->hasPermission("yomogi.simplewarp.onlyop.cmd")) {
            $sender->sendMessage("§cこのコマンドの実行権限がありません");
            return true;
        }

        if (count($args) <= 0) {
            $sender->sendMessage($this->main::tag . "使用方法: /setwa <地点の名前>");
            return true;
        }
        if ($this->main->registerPoint(
            $args[0],
            $sender->getLocation()->getX(),
            $sender->getLocation()->getY(),
            $sender->getLocation()->getZ(),
            $sender->getLocation()->getWorld()->getFolderName()
        )) {
            $sender->sendMessage($this->main::tag . $args[0] . " という名前の地点を新しく作成しました!");
        } else {
            $sender->sendMessage($this->main::tag . $args[0] . " という名前は既に使用されている可能性があります！");
        }
        return true;
    }
}
