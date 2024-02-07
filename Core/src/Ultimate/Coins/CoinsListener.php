<?php
namespace Ultimate\Coins;

use pocketmine\event\Listener;

use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\NamedTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\IntTag;

use pocketmine\item\Item;

use pocketmine\event\player\PlayerInteractEvent;

use pocketmine\event\block\BlockBreakEvent;

use pocketmine\event\inventory\InventoryTransactionEvent;

use pocketmine\inventory\PlayerInventory;

class CoinsListener implements Listener{
	
	private $plugin;
	
	public function __construct($plugin){
		$this->plugin = $plugin;
	}
	
	public function getServer(){
		return $this->plugin->getServer();
	}
	
	public function getCoinsInstance(){
		return $this->plugin->getCoinsInstance();
	}
	
	public function onInteract(PlayerInteractEvent $ev){
		$player = $ev->getPlayer();
		$item = $ev->getItem();
		if($item->getId() == 175 and $item->hasCustomName()){
			$ev->setCancelled();
		}
	}

	public function onBreak(BlockBreakEvent $ev){
		$player = $ev->getPlayer();
		$block = $ev->getBlock();
		if($block->getId() == 103 || $block->getId() == 86 || ($block->getId() == 59 && $block->getDamage() == 0x07) || ($block->getId() == 115 || $block->getDamage() == 0x07)){
			if(mt_rand(0,100) <= 50){
				$item = $this->getCoinsInstance()->setEnchTag(Item::get(351, 11, 1)->setCustomName("§r§eFragmento de Moeda"));
				$player->getInventory()->addItem($item);
				$player->sendMessage("§aVocê encontrou um fragmento de moeda");
			}
		}
	}
}