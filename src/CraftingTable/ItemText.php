<?php

namespace CraftingTable;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\scheduler\CallbackTask;
use pocketmine\Player;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\inventory\Inventory;

class ItemText extends PluginBase implements Listener{
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		if(!is_dir($this->getDataFolder())) @mkdir($this->getDataFolder());
		
		$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		
		if($this->config->getAll() == null){
			$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, array(
				"text1" => array('X,Y,Z' => '100,64,100', 'text' => "пример 1"),
				"text2" => array('X,Y,Z' => '100,64,100', 'text' => "пример 2")));
		}
          $this->addFlyText();
          $this->getServer()->getScheduler()->scheduleRepeatingTask(new CallbackTask(array($this, "respawn")), 20 * 60);
	}
	
public function onPickup(InventoryPickupItemEvent $e){
$config = $this->config->getAll();
foreach($config as $first => $second){
$text = $config[$first]["text"];
if($e->getItem()->getNameTag() == $text){
$e->setCancelled();
}
}
}

public function respawn(){
$config = $this->config->getAll();
foreach($config as $first => $second){
$text = $config[$first]["text"];
foreach($this->getServer()->getDefaultLevel()->getEntities() as $e){
if($e->getNameTag() == $text){
$e->close("", "");
}
}
}
$this->addItemText();
}

	public function addItemText(){
		$config = $this->config->getAll();
		foreach($config as $first => $second)
		{
			$get = $this->config->getAll()[$first];
			$vec = explode(',', $get['X,Y,Z']);
			$vec = new Vector3($vec[0], $vec[1], $vec[2]);
			$text = $get["text"];
               $item = Item::get(276,0,1);
               $level = $this->getServer()->getDefaultLevel();
			$item = $level->dropItem($vec, $item);
               $item->setNameTagVisible(true);
               $item->setNameTagAlwaysVisible(true);
               $item->setNameTag($text);
		}
	}
}
