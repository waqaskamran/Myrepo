<?php

require_once( SERVER_CODE_DIR . "server.class.php" );
/**
 * eCash Server Factory Class - gets the right kind of server
 */
class Server_Factory
{
	/**
	 * get_server_class
	 *
	 * We can provide:
	 * skeletal (was skeletal_server.class.php )
     * mini ( was mini-server.class.php )
     * web ( was server.class.php )
	*/
	static public function get_server_class ($api, $session_id) {
	switch ($api)
	{
				case 'daemon':
				case 'cli':
				case 'skeletal':
					require_once( SERVER_CODE_DIR . "skeletal_server.class.php" );
					return new Server_Skeletal($session_id);
		case 'json-rpc':
					require_once( SERVER_CODE_DIR . "server_web_api.class.php" );
					return new Server_Web_Api($session_id);
		case 'dhtml':
		default:
					require_once( SERVER_CODE_DIR . "server_web.class.php" );
					return new Server_Web($session_id);
		}
	}

}
?>
