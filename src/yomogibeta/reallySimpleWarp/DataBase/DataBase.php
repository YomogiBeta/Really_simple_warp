<?php

namespace yomogibeta\reallySimpleWarp\DataBase;

use PDO;
use PDOException;

class DataBase
{

  private $main;
  private $db;
  public function __construct($main)
  {
    $this->main = $main;
    $this->onConnection();
  }

  public function onConnection(): void
  {
    try {
      $path = $this->main->getDataFolder() . "place.db";
      $this->db = new PDO('sqlite:' . $path);
      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

      $this->db->exec("CREATE TABLE IF NOT EXISTS Place(
                            Name VARCHAR(64) PRIMARY KEY,
                            X INTEGER NOT NULL,
                            Y INTEGER NOT NULL,
                            Z INTEGER NOT NULL,
                            World VARCHAR(64) NOT NULL
                        )");
    } catch (PDOException $e) {
      $this->main->getLogger()->error($e->getMessage());
    }
  }

  public function registerPoint(String $name, int $x, int $y, int $z, String $worldName): bool
  {
    try {
      $stmt = $this->db->prepare("INSERT INTO Place (Name,X,Y,Z,World) VALUES (?,?,?,?,?)");
      $stmt->bindValue(1, $name);
      $stmt->bindValue(2, $x);
      $stmt->bindValue(3, $y);
      $stmt->bindValue(4, $z);
      $stmt->bindValue(5, $worldName);
      $stmt->execute();
      return true;
    } catch (PDOException $e) {
      if($e->getCode() != 23000){
        $this->main->getLogger()->error($e->getMessage());
      }
      return false;
    }
  }

  public function delWarpPoint(String $name): bool{
    try{
      $stmt = $this->db->prepare("DELETE FROM Place WHERE Name = (SELECT Name FROM Place WHERE Name like ? ORDER BY rowid ASC LIMIT 1)"); //Sqlite3のDELETEはLIMIT指定ができないため、安全を考慮し副問い合わせ
      $stmt->bindValue(1, addcslashes($name, '\_%') . "%");
      $stmt->execute();
      if($stmt->rowCount() <= 0) return false;
      return true;
    }catch(PDOException $e){
      $this->main->getLogger()->error($e->getMessage());
      return false;
    }
  }

  public function getPointData(String $name): array
  {
    try {
      $stmt = $this->db->prepare("SELECT Name,X,Y,Z,World FROM Place WHERE Name like ? ORDER BY rowid ASC LIMIT 1");
      $stmt->bindValue(1, addcslashes($name, '\_%') . "%");
      $stmt->execute();
      $result = $stmt->fetch();
      if (empty($result['Name'])) return array(
        "Name" => "",
        "X" => 0,
        "Y" => 0,
        "Z" => 0,
        "World" => ""
      );
      return $result;
    } catch (PDOException $e) {
      $this->main->getLogger()->error($e->getMessage());
      return array(
        "Name" => "",
        "X" => 0,
        "Y" => 0,
        "Z" => 0,
        "World" => ""
      );
    }
  }

  public function getPointLists(): array
  {
    try {
      $stmt = $this->db->query("SELECT Name FROM Place ORDER BY rowid ASC");
      $result = array();
      while ($data = $stmt->fetch(PDO::FETCH_NUM)) {
        $result[] = $data[0];
      }
      return $result;
    } catch (PDOException $e) {
      $this->main->getLogger()->error($e->getMessage());
      return [];
    }
  }
}
