<?php

namespace yomogibeta\reallySimpleWarp;

use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;
use pocketmine\world\Position;
use yomogibeta\reallySimpleWarp\Database\DataBase;

use yomogibeta\reallySimpleWarp\Command\setWarpPointCommand;
use yomogibeta\reallySimpleWarp\Command\warpCommand;
use yomogibeta\reallySimpleWarp\Command\warpPointListCommand;
use yomogibeta\reallySimpleWarp\Command\delWarpPointCommand;



class main extends PluginBase
{
    public const tag = "§a[§5YS§a]";
    private $db;

    public function onEnable(): void
    {
        $this->getServer()->getCommandMap()->register($this->getName(), new setWarpPointCommand($this));
        $this->getServer()->getCommandMap()->register($this->getName(), new warpCommand($this));
        $this->getServer()->getCommandMap()->register($this->getName(), new warpPointListCommand($this));
        $this->getServer()->getCommandMap()->register($this->getName(), new delWarpPointCommand($this));
        if (!file_exists($this->getDataFolder())) {
            mkdir($this->getDataFolder(), 0744, true);
        }
        $this->db = new Database($this);
    }

    public function registerPoint(String $name, int $x, int $y, int $z, String $worldName): bool
    {
        return $this->db->registerPoint($name, $x, $y, $z, $worldName);
    }

    public function delWarpPoint(String $name): bool
    {
        return $this->db->delWarpPoint($name);
    }

    public function warp(Player $player, String $name): array
    {
        $result = $this->db->getPointData($name);
        if ($result["Name"] === "") return [false, ""];

        if (!$this->getServer()->getWorldManager()->isWorldLoaded($result["World"])) {
            if (!$this->getServer()->getWorldManager()->loadWorld($result["World"])) {
                return [false, ""];
            } else {
                $this->getLogger()->info("§aReally simple warpが " . $result["World"] . " というワールドをロードしました");
            }
        }

        $world = $this->getServer()->getWorldManager()->getWorldByName($result["World"]);
        $player->teleport(new Position(
            (int)$result["X"],
            (int)$result["Y"],
            (int)$result["Z"],
            $world
        ));
        return [true, $result["Name"]];
    }

    public function getPointLists(): array
    {
        return $this->db->getPointLists();
    }
}
