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

use pocketmine\Server;
use pocketmine\utils\Internet;
use pocketmine\scheduler\AsyncTask;

use PrefixedLogger;

use function rename;
use function file_put_contents;
use function register_shutdown_function;

final class AsyncUpdateTask extends AsyncTask{

	private string $path;
	private PrefixedLogger $logger;

	public function __construct(private string $download){
		$server = Server::getInstance();
		$this->path = $server->getDataPath();
		$this->logger = new PrefixedLogger($server->getLogger(), 'AutoUpdater');
		$this->storeLocal('server', $server);
	}

	public function onRun() : void{
		$this->logger->alert('Starting update PocketMine-MP !!!');
		$result = Internet::getURL($this->download);
		$code = $result->getCode();
		$this->setResult($result->getCode());
		if($code !== 200) return;
		file_put_contents($this->path . 'LatestVersionPocketMine-MP.phar', $result->getBody());
	}

	public function onCompletion() : void{
		if($this->getResult() !== 200){
			$this->logger->error('PocketMine-MP file download failed');
			return;
		}
		register_shutdown_function(function(){
			$this->logger->alert('success update PocketMine-MP');
			rename($this->path . 'LatestVersionPocketMine-MP.phar', $this->path . 'PocketMine-MP.phar');
		});
		$this->fetchLocal('server')->shutdown();
	}

}
