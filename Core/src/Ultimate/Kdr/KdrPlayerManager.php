<?php
namespace Ultimate\Kdr;

use pocketmine\Player;
use pocketmine\utils\Config;

use Ultimate\Kdr\KdrMain;

class KdrPlayerManager extends KdrMain{
	
	public static function getPlayerData($player){
		if($player instanceof Player){
			$player = strtolower($player->getName());
		}
		return self::getKdrConfig()->get(strtolower($player));
	}
	
	public static function setPlayerData($player, array $data){
		if($player instanceof Player){
			$player = strtolower($player->getName());
		}
		self::getKdrConfig()->set(strtolower($player), $data);
		var_dump($data);
		self::getKdrConfig()->save();
		self::getKdrConfig()->reload();
	}
	
	public static function getPlayerKills($player){
		if($player instanceof Player){
			$player = strtolower($player->getName());
		}
		return self::getPlayerData(strtolower($player))["kills"];
	}
	
	public static function getPlayerDeaths($player){
		if($player instanceof Player){
			$player = strtolower($player->getName());
		}
		return self::getPlayerData(strtolower($player))["deaths"];
	}
	
	public static function getPlayerKdr($player){
		if($player instanceof Player){
			$player = strtolower($player->getName());
		}
		if(self::getPlayerKills(strtolower($player))["total"] == 0 || self::getPlayerDeaths(strtolower($player))["total"] == 0){
			return 0;
		}
		return self::getPlayerKills(strtolower($player))["total"]/self::getPlayerDeaths(strtolower($player))["total"];
	}
	
	/*
		$victim => inimigo, neutro, civil, total
	*/
	public static function addKill($player, $victim){
		if($player instanceof Player){
			$player = strtolower($player->getName());
		}
		$playerData = self::getPlayerData(strtolower($player));
		#var_dump($playerData);
		$playerData["kills"][$victim]++;
		$playerData["kills"]["total"]++;
		self::setPlayerData(strtolower($player), $playerData);
	}
	
	/*
		$killer => inimigo, neutro, civil, total
	*/
	public static function addDeath($player, $killer){
		if($player instanceof Player){
			$player = strtolower($player->getName());
		}
		$playerData = self::getPlayerData(strtolower($player));
		$playerData["deaths"][$victim]++;
		$playerData["deaths"]["total"]++;
		self::setPlayerData(strtolower($player), $playerData);
	}
}