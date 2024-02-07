<?php
namespace Ultimate\Kdr;

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

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

use pocketmine\inventory\PlayerInventory;

use Ultimate\Kdr\KdrPlayerManager;
use Ultimate\Kdr\KdrTimestampManager;

class KdrListener implements Listener{
	
	private $plugin;
	
	public function __construct($plugin){
		$this->plugin = $plugin;
	}
	
	public function getServer(){
		return $this->plugin->getServer();
	}
	
	public function getKdrInstance(){
		return $this->plugin->getKdrInstance();
	}
	
	public function onJoin(PlayerJoinEvent $ev){
		$player = $ev->getPlayer();
		if(!$this->getKdrInstance()->getKdrConfig()->exists(strtolower($player->getName()))){
			# INIMIGO => JOGADOR INIMIGO DA FACÇÃO
			# NEUTRO => JOGADOR COM FACÇÃO, MAS NÃO É NEM INIMIGO NEM ALIADO.
			# CIVIL => JOGADOR SEM FACÇÃO
			$this->getKdrInstance()->getKdrConfig()->set(strtolower($player->getName()), 
			[
				"kills" => [
					"inimigo" => 0,
					"neutro" => 0,
					"civil" => 0,
					"total" => 0,
				],
				"deaths" => [
					"inimigo" => 0,
					"neutro" => 0,
					"civil" => 0,
					"total" => 0,
				]
			]);
			$this->getKdrInstance()->getKdrConfig()->save();
		}
	}
	
	public function kdVerification($player, $killer){
		if($this->getKdrInstance()->getFactionsPro()->areEnemies($this->getKdrInstance()->getFactionsPro()->getPlayerFaction($killer->getName()), $this->getKdrInstance()->getFactionsPro()->getPlayerFaction($player->getName()))){
			$this->plugin->getServer()->broadcastMessage("1");
			KdrPlayerManager::addKill($killer->getName(), "inimigo");
			KdrPlayerManager::addDeath($player->getName(), "inimigo");
		}elseif(!$this->plugin->getKdrInstance()->getFactionsPro()->isInFaction($player->getName())){
			$this->plugin->getServer()->broadcastMessage("2");
			KdrPlayerManager::addKill($killer->getName(), "civil");
			KdrPlayerManager::addDeath($player->getName(), "civil");
		}else{
			$this->plugin->getServer()->broadcastMessage("3");
			KdrPlayerManager::addKill($killer->getName(), "neutro");
			KdrPlayerManager::addDeath($player->getName(), "neutro");
		}
	}
	
	public function onDeath(PlayerDeathEvent $ev){
		$player = $ev->getPlayer();
		$cause = $ev->getPlayer()->getLastDamageCause();
		if($cause instanceof EntityDamageByEntityEvent){
			$killer = $cause->getDamager();
			$this->kdVerification($player, $killer);
		}else{
			if(KdrTimestampManager::hasTime($player)){
				if((KdrTimestampManager::getTime($player) - time()) >= 10){
					KdrTimestampManager::removeTime($player);
				}else{
					$killer = $this->plugin->getServer()->getPlayer(KdrTimestampManager::getTimeDamager($player));
					if($killer instanceof Player){
						$this->kdVerification($player, $killer);
					}
				}
			}
		}
	}
	
	public function onDamage(EntityDamageEvent $ev){
		$player = $ev->getEntity();
		if($ev instanceof EntityDamageByEntityEvent){
			$damager = $ev->getDamager();
			KdrTimestampManager::setTime($player, $damager);
		}
	}
	
	
}