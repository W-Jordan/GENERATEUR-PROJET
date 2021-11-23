<?php
// class DbConnect {
// 	private static $db;
	
// 	public static function getDb() {return DbConnect::$db;}

// 	public static function init() {

// 		if (DIRECTORY_SEPARATOR === '\\') {
// 			try {
// 			self::$db= new PDO ('mysql:host=localhost:3306;','root','');
// 			} catch ( Exception $e ) {
// 				die ( 'Erreur : ' . $e->getMessage () );
// 			}
// 		}else{
// 			try {
// 				self::$db= new PDO ('mysql:host=localhost;port=8889;','root','root');
// 			} catch ( Exception $e ) {
// 				die ( 'Erreur : ' . $e->getMessage () );
// 			}
// 		}
	
// 	}
// }