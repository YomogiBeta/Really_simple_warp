<?php

namespace yomogibeta\reallySimpleWarp\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;



class delWarpPointCommand extends Command
{
    private $main;
    public function __construct($main)
    {
        parent::__construct("delwa", "ワープ地点を削除", "/delwa <地点の名前>");
        $this->setPermission("yomogi.simplewarp.onlyop.cmd");
        $this->main = $main;
    }

    public function execute(CommandSender $sender, string $label, array $args): bool
    {
        if (!$sender->hasPermission("yomogi.simplewarp.onlyop.cmd")) {
            $sender->sendMessage("§cこのコマンドの実行権限がありません");
            return true;
        }
        if (count($args) <= 0){
            $sender->sendMessage($this->main::tag . "使用方法: /delwa <地点の名前>");
            return true;
        }

        if ($this->main->delWarpPoint($args[0])) {
            $sender->sendMessage($this->main::tag . "§e" . $args[0] . "という名前の地点を削除しました");
        } else {
            $sender->sendMessage($this->main::tag . "" . $args[0] . "という名前の地点は存在しません");
        }
        return true;
    }
}
