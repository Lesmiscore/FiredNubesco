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
	public function __construct(xSudo $plugin, $name, $description)
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
		
	}

	public function getPlugin(){
		return $this->plugin;
	}
}