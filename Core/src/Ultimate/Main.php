<?php
namespace Ultimate;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use Ultimate\Coins\CoinsMain;
use Ultimate\Kdr\KdrMain;

use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{
	
	private $classes = [];
	
	public function onEnable(){
		@mkdir($this->getDataFolder());
		#(new Config($this->getDataFolder()."kdr/players.yml", Config::YAML, []))->save();
		$this->classes["coins"] = new CoinsMain($this);
		$this->classes["kdr"] = new KdrMain($this);
	}
	
	public function getCoinsInstance(){
		return $this->classes["coins"];
	}
	
	public function getKdrInstance(){
		return $this->classes["kdr"];
	}
}