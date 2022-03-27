<?php
/**
 *      _                    _       
 *  ___| | ___   _ _ __ ___ (_)_ __  
 * / __| |/ / | | | '_ ` _ \| | '_ \ 
 * \__ \   <| |_| | | | | | | | | | |
 * |___/_|\_\\__, |_| |_| |_|_|_| |_|
 *           |___/ 
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the MIT License. see <https://opensource.org/licenses/MIT>.
 * 
 * @author skymin
 * @link   https://github.com/sky-min
 * @license https://opensource.org/licenses/MIT MIT License
 * 
 *   /\___/\
 * 　(∩`・ω・)
 * ＿/_ミつ/￣￣￣/
 * 　　＼/＿＿＿/
 *
 */

declare(strict_types = 1);

namespace skymin\autoupdater;

use pocketmine\plugin\PluginBase;
use pocketmine\event\EventPriority;
use pocketmine\event\server\UpdateNotifyEvent;

use skymin\data\Data;

final class AutoUpdater extends PluginBase{

	protected function onEnable() : void{
		$server = $this->getServer();
		$server->getPluginManager()->registerEvent(UpdateNotifyEvent::class, function(UpdateNotifyEvent $ev) use($server): void{
			$server->getAsyncPool()->submitTask(new AsyncUpdateTask($ev->getUpdater()->getUpdateInfo()->download_url));
		}, EventPriority::MONITOR, $this);
	}

}
