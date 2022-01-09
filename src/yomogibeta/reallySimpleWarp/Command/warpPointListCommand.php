<?php

namespace yomogibeta\reallySimpleWarp\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;



class warpPointListCommand extends Command
{
    private $main;
    public function __construct($main)
    {
        parent::__construct("walist", "ワープ地点一覧を表示", "/walist");
        $this->setPermission("yomogi.simplewarp.public.cmd");
        $this->main = $main;
    }

    public function execute(CommandSender $sender, string $label, array $args): bool
    {
        if (!$sender->hasPermission("yomogi.simplewarp.public.cmd")){
            $sender->sendMessage("§cこのコマンドの実行権限がありません");
            return true;
        }
        $result = $this->main->getPointLists();
        if (count($result) == 0) {
            $sender->sendMessage($this->main::tag . "この鯖にはまだwarp pointがありません..");
            return true;
        }
        $message = $this->main::tag . "WarpList\n"; //最初のメッセージ
        $count = 0;
        foreach ($result as $value) {
            if ($count >= 5) {
                $message .= $value . "," . "\n";
                $count = 0;
            } else {
                $message .= $value . ",";
                ++$count;
            }
        }
        $sender->sendMessage($message);
        return true;
    }
}
