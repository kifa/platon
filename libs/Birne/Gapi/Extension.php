<?php

namespace Birne\Gapi;

/**
 * @author Lukas DAnek
 */
class Extension extends \Nette\Config\CompilerExtension
{
    const EXTENSION_NAME = 'gapi';

	public function install(\Nette\Config\Configurator $configurator)
	{
		$self = $this;
		$configurator->onCompile[] = function ($configurator, $compiler) use ($self) {
		    $compiler->addExtension($self::EXTENSION_NAME, $self);
		};
	}

}
