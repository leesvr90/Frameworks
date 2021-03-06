<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* -------------------------------------------------------------------
 * EXPLANATION OF VARIABLES
 * -------------------------------------------------------------------
 *
 * ['mongo_hostbase'] The hostname (and port number) of your mongod or mongos instances. Comma delimited list if connecting to a replica set.
 * ['mongo_database'] The name of the database you want to connect to
 * ['mongo_username'] The username used to connect to the database (if auth mode is enabled)
 * ['mongo_password'] The password used to connect to the database (if auth mode is enabled)
 * ['mongo_persist']  Persist the connection. Highly recommend you don't set to FALSE
 * ['mongo_persist_key'] The persistant connection key
 * ['mongo_replica_set'] If connecting to a replica set, the name of the set. FALSE if not.
 * ['mongo_query_safety'] Safety level of write queries. "safe" = committed in memory, "fsync" = committed to harddisk
 * ['mongo_suppress_connect_error'] If the driver can't connect by default it will throw an error which dislays the username and password used to connect. Set to TRUE to hide these details.
 * ['mongo_host_db_flag']   If running in auth mode and the user does not have global read/write then set this to true
 */


/* -------------------------------------------------------------------
 * Default database configuration
 * -------------------------------------------------------------------
 * MONITORING ASSISTANT
 * This is a replica set database include 1 master and 1 slave database server
 */
$config['default']['mongo_hostbase'] = '10.30.15.8:27017';
$config['default']['mongo_database'] = 'monitoring_assistant';
$config['default']['mongo_username'] = 'u_ma';
$config['default']['mongo_password'] = 'Ma20!3';
/* $config['default']['mongo_hostbase'] = '10.30.15.234:27017';
$config['default']['mongo_database'] = 'monitor_assistant';
$config['default']['mongo_username'] = 'u_ma';
$config['default']['mongo_password'] = 'Ma20!3'; */

$config['default']['mongo_connect_retry']   		= 3;
$config['default']['mongo_retry_interval']  		= 1; // second
$config['default']['mongo_persist']  				= FALSE;
$config['default']['mongo_persist_key']	 			= 'ci_persist';
$config['default']['mongo_query_safety'] 			= 'safe';
$config['default']['mongo_suppress_connect_error'] 	= TRUE;
$config['default']['mongo_host_db_flag'] 			= FALSE;
$config['default']['mongo_replica_set']  			= FALSE;
$config['default']['mongo_read_preference'] 		= FALSE;
