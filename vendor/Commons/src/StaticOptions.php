<?php

namespace Commons;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Adapter\Adapter;
use Restaurant\Model\Restaurant;
use Zend\Filter\File\RenameUpload;

class StaticOptions {
	protected static $_serviceLocator;
	protected static $_db_read_adapter;
	protected static $_db_write_adapter;
	protected static $_date_time_zone;
	protected static $_date_time;
	private static $_cache = array ();
	private static $_user_session = false;

	const MYSQL_DATE_FORMAT = 'Y-m-d H:i:s';
	public static function expiry_time() {
		return time () + (48 * 3600);
	}
	public static function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		static::$_serviceLocator = $serviceLocator;
	}
	public static function getServiceLocator() {
		if (! static::$_serviceLocator) {
			throw new \Exception ( "Unable to get service locator. Please set the instance of service locator first" );
		}
		return static::$_serviceLocator;
	}
	public static function setDbReadAdapter(Adapter $readAdapter) {
		static::$_db_read_adapter = $readAdapter;
	}
	public static function getDbReadAdapter() {
		if (! static::$_db_read_adapter) {
			static::setDbReadAdapter ( static::getServiceLocator ()->get ( 'Commons\Db\Adapter\ReadAdapter' ) );
		}
		return static::$_db_read_adapter;
	}
	public static function setDbWriteAdapter(Adapter $writeAdapter) {
		static::$_db_write_adapter = $writeAdapter;
	}
	public static function getDbWriteAdapter() {
		if (! static::$_db_write_adapter) {
			static::setDbWriteAdapter ( static::getServiceLocator ()->get ( 'Commons\Db\Adapter\WriteAdapter' ) );
		}
		return static::$_db_write_adapter;
	}
	/**
	 * Generate appropriate response with variables and response code
	 *
	 * @param ServiceLocatorInterface $sl        	
	 * @param array $vars        	
	 * @param number $response_code        	
	 * @return \Zend\Http\PhpEnvironment\Response $response
	 */
	public static function getResponse(ServiceLocatorInterface $sl, array $vars = array(), $response_code = 200) {
		/**
		 *
		 * @var \Zend\Di\Di $di
		 */
		$di = $sl->get ( 'di' );
		
		/**
		 *
		 * @var array $configuration
		 */
		$configuration = $sl->get ( 'config' );
		
		/**
		 *
		 * @var PostProcessor\AbstractPostProcessor $postProcessor
		 */
		$formatter = StaticOptions::getFormatter ();
		$response = $sl->get ( 'response' );
		
		$postProcessor = $di->get ( $formatter . "_processor", array (
				'vars' => $vars,
				'response' => $response 
		) );
		
		$response->setStatusCode ( $response_code );
		$postProcessor->process ();
		$response = $postProcessor->getResponse ();
		return $response;
	}
	public static function getErrorResponse(ServiceLocatorInterface $sl, $message = 'Error Occured', $response_code = 500) {
		/**
		 *
		 * @var \Zend\Di\Di $di
		 */
		$di = $sl->get ( 'di' );
		
		/**
		 *
		 * @var array $configuration
		 */
		$configuration = $sl->get ( 'config' );
		
		/**
		 *
		 * @var PostProcessor\AbstractPostProcessor $postProcessor
		 */
		$formatter = StaticOptions::getFormatter ();
		$response = $sl->get ( 'response' );
		
		$request = $sl->get ( 'request' );
		$requestType = ( bool ) $request->getQuery ( 'mob', false ) ? 'mobile' : 'web';
		
		$vars = StaticOptions::formatResponse ( array (
				'error' => $message 
		), $response_code, $message, $requestType );
		$postProcessor = $di->get ( $formatter . "_processor", array (
				'vars' => $vars,
				'response' => $response 
		) );
		
		$response->setStatusCode ( $response_code );
		$postProcessor->process ();
		$response = $postProcessor->getResponse ();
		return $response;
	}
	/**
	 * File validations (type, size, error)
	 *
	 * @param array $file        	
	 * @return array
	 */
	public static function validateImage($file) {
		$mTypes = array (
				'image/gif',
				'image/jpeg',
				'image/png',
				'image/psd',
				'image/bmp',
				'image/tiff',
				'image/jp2',
				'image/iff',
				'image/vnd.wap.wbmp',
				'image/xbm',
				'image/vnd.microsoft.icon' 
		);
		if (! in_array ( $file ['type'], $mTypes )) {
			$val_response = array (
					'status' => false,
					'message' => 'Invalid image' 
			);
		} else if ($file ['error'] != 0 && $file ['error'] != 4) {
			$err_value = self::getUploadError ( $file ['error'] );
			$val_response = array (
					'status' => false,
					'message' => $err_value ['msg'] 
			);
		} else if (round ( ($file ['size'] / 1048576), 2 ) > MAX_IMAGE_UPLOAD_SIZE_LIMIT) {
			// size validation
			$val_response = array (
					'status' => false,
					'message' => 'File size exceeded.' 
			);
		} else {
			$val_response = array (
					'status' => true,
					'message' => 'Success.' 
			);
		}
		return $val_response;
	}
	/**
	 * Identify file upload error
	 *
	 * @param int $error_code        	
	 * @return multitype:array
	 */
	public static function getUploadError($error_code) {
		$msg = '';
		$error = '';
		switch ($error_code) {
			case 1 :
				$msg = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
				$error = 'UPLOAD_ERR_INI_SIZE';
				break;
			case 2 :
				$msg = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the form.';
				$error = 'UPLOAD_ERR_FORM_SIZE';
				break;
			case 3 :
				$msg = 'The uploaded file was only partially uploaded.';
				$error = 'UPLOAD_ERR_PARTIAL';
				break;
			case 4 :
				$msg = 'No file was uploaded.';
				$error = 'UPLOAD_ERR_NO_FILE';
				break;
			case 6 :
				$msg = 'Missing a temporary folder.';
				$error = 'UPLOAD_ERR_NO_TMP_DIR';
				break;
			case 7 :
				$msg = 'Failed to write file to disk.';
				$error = 'UPLOAD_ERR_CANT_WRITE';
				break;
			case 8 :
				$msg = 'A PHP extension stopped the file upload.';
				$error = 'UPLOAD_ERR_EXTENSION';
				break;
			default :
				$msg = "No message";
				$error = "No error";
				break;
				
				return array (
						'msg' => $msg,
						'error' => $error 
				);
		}
	}
	/**
	 * function to upload user images
	 *
	 * @param array $files        	
	 * @param string $path        	
	 * @param string $dirname        	
	 * @throws \Exception
	 * @return array
	 */
	public static function uploadUserImages($files, $path, $dirname) {
		$isValid = true;
		$response = array ();
		$resp = array ();
		$directories = explode ( DS, $dirname );
		$newpath = $path;
		foreach ( $directories as $key => $dir ) :
			$newpath .= $dir . DS;
			if (! file_exists ( $newpath )) {
				mkdir ( $newpath, 0777, true );
			}
		endforeach
		;
		if (! empty ( $files )) {
			foreach ( $files as $fkey => $file ) :
				$valid = StaticOptions::validateImage ( $file );
				if ($valid ['status']) {
					$filter = new RenameUpload ( array (
							'target' => $path . DIRECTORY_SEPARATOR . $dirname . uniqid ( rand ( 99, 9999 ) . "-" . mt_rand ( 11111, 999999 ) . "-" ),
							'use_upload_extension' => true 
					) );
					$temp_resp = $filter->filter ( $files->$fkey );
					if (isset ( $temp_resp ['tmp_name'] )) {
						$filename = explode ( DS, $temp_resp ['tmp_name'] );
						$temp_resp ['path'] = WEB_URL . $dirname . $filename [count ( $filename ) - 1];
						unset ( $temp_resp ['tmp_name'] );
						unset ( $temp_resp ['type'] );
						unset ( $temp_resp ['error'] );
						unset ( $temp_resp ['size'] );
					}
					$resp [$fkey] = $temp_resp;
				} else {
					$isValid = false;
				}
				//
			endforeach
			;
		}
		if ($isValid) {
			$response = $resp;
		} else {
			throw new \Exception ( "Error in image upload", 400 );
		}
		return $response;
	}
	public static function generate_verification_code() {
		$length = 10;
		$verification_code = '';
		list ( $usec, $sec ) = explode ( ' ', microtime () );
		mt_srand ( ( float ) $sec + (( float ) $usec * 100000) );
		$inputs = array_merge ( range ( 'z', 'a' ), range ( 0, 9 ), range ( 'A', 'Z' ) );
		for($i = 0; $i < $length; $i ++) {
			$verification_code .= $inputs {mt_rand ( 0, 61 )};
		}
		return $verification_code;
	}
	/**
	 * function to fetch data from web service / API
	 *
	 * @param string $url        	
	 * @return multitype
	 */
	public static function fetchDataFromUrl($url) {
		try {
			$config = array (
					'adapter' => 'Zend\Http\Client\Adapter\Curl',
					'curloptions' => array (
							CURLOPT_FOLLOWLOCATION => true,
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_AUTOREFERER => true,
							CURLOPT_HEADER => true 
					) 
			);
			$client = new \Zend\Http\Client ( $url, $config );
			$req = $client->getRequest ();
			$data = $client->send ( $req )->getBody ();
		} catch ( \Exception $ex ) {
			return array (
					'error' => $ex->getMessage () 
			);
		}
		return $data;
	}
	public static function filterRequestParams($input) {
		$htmlEntities = new \Zend\Filter\HtmlEntities ();
		if (is_array ( $input )) {
			foreach ( $input as $ikey => $ival ) {
				$input [$ikey] = $htmlEntities->filter ( $ival );
			}
		}else{
		    $input = $htmlEntities->filter ( $input );
		}
		return $input;
	}
}

