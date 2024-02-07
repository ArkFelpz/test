<?php
namespace Ultimate\Coins;

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

use Ultimate\Coins\CoinsListener;

use Ultimate\Main;

class CoinsMain{
	
	public const TAG_ENCH = "ench";
	
	private $plugin;
	private $coinsListener = null;
	
	public function __construct($plugin){
		$this->plugin = $plugin;
		$this->coinsListener = new CoinsListener($plugin);
		$this->init();
	}
	
	public function getServer(){
		return $this->plugin->getServer();
	}
	
	public function setEnchTag(Item $item){
		$ench = $item->getNamedTagEntry(self::TAG_ENCH);
		if(!($ench instanceof ListTag)){
			$ench = new ListTag(self::TAG_ENCH, [], NBT::TAG_Compound);
			$item->setNamedTagEntry($ench);
		}
		return $item;
	}
	
	public function init(){
		$item = $this->setEnchTag(Item::get(351, 11, 1)->setCustomName("§r§eFragmento de Moeda"));
		$sunflower = Item::get(175, 0, 1)->setCustomName("§r§eMoeda");
		$rec = new ShapedRecipe(["ab","cd"], [
		    "a" => $item,
		    "b" => $item,
		    "c" => $item,
		    "d" => $item,
	    ], [$sunflower]);
        $rec->registerToCraftingManager($this->getServer()->getCraftingManager());
		$this->getServer()->getPluginManager()->registerEvents($this->coinsListener, $this->plugin);
	}
	
}