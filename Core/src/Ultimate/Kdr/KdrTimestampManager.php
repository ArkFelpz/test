<?php
namespace Ultimate\Kdr;

use pocketmine\Player;
use pocketmine\utils\Config;

use Ultimate\Kdr\KdrMain;

class KdrTimestampManager extends KdrMain{
	
	# PLAYER_NAME => INT(TIME)
	public static $timestamp = [];
	
	public static function hasTime($player){
		if($player instanceof Player){
			$player = strtolower($player->getName());
		}
		return isset(self::$timestamp[strtolower($player)]);
	}
	
	public static function getTime($player){
		if($player instanceof Player){
			$player = strtolower($player->getName());
		}
		return self::$timestamp[strtolower($player)][0];
	}
	
	public static function getTimeDamager($player){
		if($player instanceof Player){
			$player = strtolower($player->getName());
		}
		return self::$timestamp[strtolower($player)][1];
	}
	
	
	public static function setTime($player, $damager){
		if($player instanceof Player){
			$player = strtolower($player->getName());
		}
		if($damager instanceof Player){
			$damager = strtolower($damager->getName());
		}
		self::$timestamp[strtolower($player)] = [time(), strtolower($damager)];
	}
	
	public static function removeTime($player){
		if($player instanceof Player){
			$player = strtolower($player->getName());
		}
		unset(self::$timestamp[strtolower($player)]);
	}
}