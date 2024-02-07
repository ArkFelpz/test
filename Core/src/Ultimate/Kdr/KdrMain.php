<?php
namespace Ultimate\Kdr;

use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\NamedTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\IntTag;

use pocketmine\item\Item;
use pocketmine\inventory\ShapedRecipe;

use Ultimate\Kdr\KdrListener;

use Ultimate\Main;

use pocketmine\utils\Config;

class KdrMain{
	
	private $plugin;
	private $kdrListener = null;
	private static $kdrConfig = null;
	
	private $factionsPro = null;
	
	public function __construct($plugin){
		$this->plugin = $plugin;
		$this->kdrListener = new KdrListener($plugin);
		$this->init();
	}
	
	public function init(){
		@mkdir($this->plugin->getDataFolder()."kdr");
		self::$kdrConfig = new Config($this->plugin->getDataFolder()."kdr/players.yml", Config::YAML);
		self::$kdrConfig->save();
		$this->factionsPro = $this->plugin->getServer()->getPluginManager()->getPlugin("FactionsPro");
		$this->plugin->getServer()->getPluginManager()->registerEvents($this->kdrListener, $this->plugin);
	}
	
	public static function getKdrConfig(){
		return self::$kdrConfig;
	}
	
	public function getFactionsPro(){
		return $this->factionsPro;
	}
	
}