<?php

namespace nao20010128nao;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\PluginIdentifiableCommand;

use pocketmine\Player;

use pocketmine\utils\TextFormat;

class MazeCommand extends Command implements PluginIdentifiableCommand
{
	private $plugin;
	public function __construct(PluginMain $plugin, $name, $description)
	{
		$this->plugin = $plugin;
		parent::__construct($name, $description);
	}
	
	private function checkPermission(CommandSender $sender){
		if(!($sender->isOp() or $sender->hasPermission("fn.maze"))){
			$sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
			return false;
		}
		return true;
	}
	
	public function execute(CommandSender $sender, $label, array $args)
	{
		if(!isset($args[0]))
		{
			if(!$this->checkPermission($sender)) return true;
			$sender->sendMessage(TextFormat::GREEN . "[FiredNubesco] Usage: /maze <id>[:<damage>] [<wall(inside|outside|none)> [<under(true|false)> [<top(true|false)>]]]");
			return false;
		}
		
		$generator=null;
		switch(mt_rand(0,1)){
		//まだ1個しかないのでこのまま
		case 0;
		case 1;
			$generator=new MazeGen1();
			break;
		}
		$size=$this->plugin->countBlocks($sender);
		$block=$this->getBlockInfo($args[0]);
		$wall=0;
		if(isset($args[1])){
			switch($args[1]){
			case "inside":
				$wall=1;
				break;
			case "outside":
				$wall=2;
				break;
			}
		}
		$under=false;
		if(isset($args[2])){
			if($args[2]=="true"){
				$under=true;
			}
		}
		$top=false;
		if(isset($args[3])){
			if($args[3]=="true"){
				$top=true;
			}
		}
		$this->plugin->getServer()->broadcastMessage("[FiredNubesco] Generating maze...");
		if($wall==1){//内部に作る場合、壁の分減らす
			$size[0]=$size[0]-2;
			$size[1]=$size[1]-2;
		}
	}

	public function getPlugin(){
		return $this->plugin;
	}
	public function getBlockInfo($str){
		$a=explode(":",$str);
		if(isset($a[1])){
			return array("id"=>intval($d[0]),"damage"=>intval($d[1]));
		}else{
			return array("id"=>intval($d[0]),"damage"=>0);
		}
	}
}