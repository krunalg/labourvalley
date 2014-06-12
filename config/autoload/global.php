<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
return array (
		'service_manager' => array (
				'factories' => array (
						'Commons\Db\Adapter\WriteAdapter' => 'Commons\Db\Adapter\WriteAdapterServiceFactory',
						'Commons\Db\Adapter\ReadAdapter' => 'Commons\Db\Adapter\ReadAdapterServiceFactory' 
				// 'Zend\Db\Adapter\Adapter' => 'Commons\Db\Adapter\ReadAdapterServiceFactory'
								) 
		),
		'api_keys' => array (
				'google' => 'AIzaSyA2ZM5moOcYeEEp808wO818WUWVqG3PmBU' 
		) 
);

