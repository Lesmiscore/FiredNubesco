<?php

namespace nao20010128nao;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;

// FiredNubesco (放火されたヌベスコ) English (英語版)
class PluginMain extends PluginBase implements Listener
{
	public $data,$id;
	public function onEnable(){
		@mkdir($this->getDataFolder());
		$this->data=array();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$commandMap = $this->getServer()->getCommandMap();
		$commandMap->register("maze",new MazeCommand($this, "maze", "Generates a maze inside the world"));
		$d=array("id"=>170);
		if(file_exists($this->getDataFolder()."/config.yml")){
			$d=yaml_parse_file($this->getDataFolder()."/config.yml");
		}
		$this->id=$d["id"];
		$this->getServer()->getLogger()->info("Enabled FiredNubesco.");
		$this->getServer()->getLogger()->info("This is English version. これは英語版です。");
		
		$this->getServer()->getLogger()->info("If you need Japanese version, please delete \"FiredNubesco_ENG.phar\" and");
		$this->getServer()->getLogger()->info("download \"FiredNubesco_JPN.phar\" from the distribution page.");
		$this->getServer()->getLogger()->info("日本語版が必要な場合、\"FiredNubesco_ENG.phar\"を削除し、");
		$this->getServer()->getLogger()->info("\"FiredNubesco_JPN.phar\"を配布サイトからダウンロードして");
		$this->getServer()->getLogger()->info("頂きますようお願い申し上げます。");
	}
	public function onDisable(){
		$d=array("id"=>$this->id);
		yaml_emit_file($d,$this->getDataFolder()."/config.yml");
	}
	public function blockBreak(BlockBreakEvent $event){//1
		$id = $event->getItem()->getId();
		if($id == $this->id){
			$player = $event->getPlayer();
			$username = $player->getName();
			if(empty($this->data[$user][1])){
				$x = $event->getBlock()->x;
				$y = $event->getBlock()->y;
				$z = $event->getBlock()->z;
				$this->data[$user][1] = array($x, $y, $z);
				$ms="";
				if(isset($this->data[$user][2])){//片方がセットされていたら
					$ms = "[FiredNubesco] Second position has been set to: $x, $y, $z";
					$size = $this->countBlocks($player);
					if($num != false) $ms .= " (Size: ".$size[0]."X".$size[1].")";
				}else{
					$ms = "[FiredNubesco] First position has been set to: $x, $y, $z";
				}
				$player->sendMessage($ms);
				$event->setCancelled(true);
			}
		}
		return true;
	}
	public function blockPlace(BlockPlaceEvent $event){//2
		$id = $event->getItem()->getId();
		if($id == $this->id){
			$player = $event->getPlayer();
			$username = $player->getName();
			if(empty($this->data[$user][2])){
				$x = $event->getBlock()->x;
				$y = $event->getBlock()->y;
				$z = $event->getBlock()->z;
				$this->data[$user][2] = array($x, $y, $z);
				$ms="";
				if(isset($this->data[$user][2])){//片方がセットされていたら
					$ms = "[FiredNubesco] Second position has been set to: $x, $y, $z";
					$size = $this->countBlocks($player);
					if($num != false) $ms .= " (Size: ".$size[0]."X".$size[1].")";
				}else{
					$ms = "[FiredNubesco] First position has been set to: $x, $y, $z";
				}
				$player->sendMessage($ms);
				$event->setCancelled(true);
			}
		}
		return true;
	}
	public function countBlocks($player){
		if($player == null){
			$name = "CONSOLE";
		}else{
			$name = $player->getName();
		}
		if(isset($this->data[$name][1]) and isset($this->data[$name][2])){
			$pos = $this->data[$name];
			$sx = min($pos[1][0], $pos[2][0]);
			$sz = min($pos[1][2], $pos[2][2]);
			$ex = max($pos[1][0], $pos[2][0]);
			$ez = max($pos[1][2], $pos[2][2]);
			return array($ex-$sx,$ey-$sy);
		}else{
			return false;
		}
	}
}