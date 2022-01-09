<?php

namespace yomogibeta\reallySimpleWarp\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;



class warpCommand extends Command
{
    private $main;
    public function __construct($main)
    {
        parent::__construct("warp", "予め指定した地点にwarp", "/warp <地点の名前>");
        $this->setPermission("yomogi.simplewarp.public.cmd");
        $this->main = $main;
    }

    public function execute(CommandSender $sender, string $label, array $args): bool
    {
        if (!$sender->hasPermission("yomogi.simplewarp.public.cmd")){
            $sender->sendMessage("§cこのコマンドの実行権限がありません");
            return true;
        }
        if (count($args) <= 0){
            $sender->sendMessage($this->main::tag . "使用方法: /warp <地点の名前>");
            return true;
        }
        $result = $this->main->warp($sender, $args[0]);
        if ($result[0]) {
            $sender->sendMessage($this->main::tag . "§b貴方を" . $result[1] . "にTP!!!");
        } else {
            $sender->sendMessage($this->main::tag . "§d" . $args[0] . "という名前の地点は存在しないか、ワールドが削除されています");
        }

        return true;
    }
}
