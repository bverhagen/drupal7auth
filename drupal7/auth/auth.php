<?php
/**
 * DokuWiki Plugin drupal7 (Auth Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  big_smile_21 <big_smile_21@hotmail.com>
 */

// must be run within Dokuwiki
// FIXME Uncomment
 if(!defined('DOKU_INC')) die();

class auth_plugin_drupal7_auth extends DokuWiki_Auth_Plugin {


/*class auth_plugin_drupal7_auth {
    //FIXME delete this later?
    var $db;
    var $conf;
    var $USERINFO;*/

    /**
     * Constructor.
     */
    public function __construct() {
        //FIXME uncomment
        parent::__construct(); // for compatibility

        // FIXME set capabilities accordingly
        $this->cando['addUser']     = false; // can Users be created?
        $this->cando['delUser']     = false; // can Users be deleted?
        $this->cando['modLogin']    = false; // can login names be changed?
        $this->cando['modPass']     = false; // can passwords be changed?
        $this->cando['modName']     = false; // can real names be changed?
        $this->cando['modMail']     = false; // can emails be changed?
        //$this->cando['modGroups']   = false; // can groups be changed?
        $this->cando['getUsers']    = false; // can a (filtered) list of users be retrieved?
        $this->cando['getUserCount']= false; // can the number of users be retrieved?
        $this->cando['getGroups']   = true; // can a list of available groups be retrieved?
        $this->cando['external']    = true; // does the module do external auth checking?
        $this->cando['logout']      = false; // can the user logout again? (eg. not possible with HTTP auth)

        // FIXME intialize your auth system and set success to true, if successful
        $this->success = true;
    }


    /**
     * Log off the current user [ OPTIONAL ]
     */
    //public function logOff() {
    //}

    /**
     * Do all authentication [ OPTIONAL ]
     *
     * @param   string  $user    Username
     * @param   string  $pass    Cleartext Password
     * @param   bool    $sticky  Cookie should not expire
     * @return  bool             true on successful auth
     */
    public function trustExternal($user, $pass, $sticky = false) {
        global $USERINFO;

        if($this->_openDB()) {
            $cookies = $_COOKIE;
	
	        // Drupal user ID
	        $user_id = $cookies['DRUPAL_UID'];

	        // find session cookie
	        foreach($cookies as $key => $value) {
		        if(substr($key, 0, 4) == 'SESS') {
			        // Check session
			        $query = $this->getConf('checksession');
			        $query = str_replace('%{db_prefix}', $this->getConf('db_prefix'), $query);
			        $query = str_replace('%{session_id}', $value, $query);
                    $query = str_replace('%{user_id}', $user_id, $query);
                    $result = $this->_queryDB($query);

                    if($result) {
                            // Valid session found                            
                            $USERINFO['name'] = $result[0]['name'];
                            $USERINFO['mail'] = $result[0]['mail'];
                            $USERINFO['grps'] = array();

                            // Find groups
                            $query = $this->getConf('roles');
                            $query = str_replace('%{db_prefix}', $this->getConf('db_prefix'), $query);
                            $query = str_replace('%{user_id}', $user_id, $query);
                            $result = $this->_queryDB($query);
                            foreach($result as $row) {
                                $USERINFO['grps'][] = $row['name'];
                            }

                            $_SERVER['REMOTE_USER'] = $USERINFO['name'];
                            $_SESSION[DOKU_COOKIE]['auth']['user'] = $USERINFO['name'];
                            $_SESSION[DOKU_COOKIE]['auth']['info'] = $USERINFO;
                            break;
                    }
		        }
	        }
            $this->_closeDB();
            return true;
        } else {
            echo 'Error: could not open database';
            return false;
        }
    }

    /**
     * Check user+password
     *
     * May be ommited if trustExternal is used.
     *
     * @param   string $user the user name
     * @param   string $pass the clear text password
     * @return  bool
     */
    public function checkPass($user, $pass) {
        // This function should not be used, since authentication is external
        return false;
    }

    /**
     * Return user info
     *
     * Returns info about the given user needs to contain
     * at least these fields:
     *
     * name string  full name of the user
     * mail string  email addres of the user
     * grps array   list of groups the user is in
     *
     * @param   string $user the user name
     * @return  array containing user data or false
     */
    public function getUserData($user) {
        // FIXME implement
        return false;
    }

    /**
     * Create a new User [implement only where required/possible]
     *
     * Returns false if the user already exists, null when an error
     * occurred and true if everything went well.
     *
     * The new user HAS TO be added to the default group by this
     * function!
     *
     * Set addUser capability when implemented
     *
     * @param  string     $user
     * @param  string     $pass
     * @param  string     $name
     * @param  string     $mail
     * @param  null|array $grps
     * @return bool|null
     */
    //public function createUser($user, $pass, $name, $mail, $grps = null) {
        // FIXME implement
    //    return null;
    //}

    /**
     * Modify user data [implement only where required/possible]
     *
     * Set the mod* capabilities according to the implemented features
     *
     * @param   string $user    nick of the user to be changed
     * @param   array  $changes array of field/value pairs to be changed (password will be clear text)
     * @return  bool
     */
    //public function modifyUser($user, $changes) {
        // FIXME implement
    //    return false;
    //}

    /**
     * Delete one or more users [implement only where required/possible]
     *
     * Set delUser capability when implemented
     *
     * @param   array  $users
     * @return  int    number of users deleted
     */
    //public function deleteUsers($users) {
        // FIXME implement
    //    return false;
    //}

    /**
     * Bulk retrieval of user data [implement only where required/possible]
     *
     * Set getUsers capability when implemented
     *
     * @param   int   $start     index of first user to be returned
     * @param   int   $limit     max number of users to be returned
     * @param   array $filter    array of field/pattern pairs, null for no filter
     * @return  array list of userinfo (refer getUserData for internal userinfo details)
     */
    //public function retrieveUsers($start = 0, $limit = -1, $filter = null) {
        // FIXME implement
    //    return array();
    //}

    /**
     * Return a count of the number of user which meet $filter criteria
     * [should be implemented whenever retrieveUsers is implemented]
     *
     * Set getUserCount capability when implemented
     *
     * @param  array $filter array of field/pattern pairs, empty array for no filter
     * @return int
     */
    //public function getUserCount($filter = array()) {
        // FIXME implement
    //    return 0;
    //}

    /**
     * Define a group [implement only where required/possible]
     *
     * Set addGroup capability when implemented
     *
     * @param   string $group
     * @return  bool
     */
    //public function addGroup($group) {
        // FIXME implement
    //    return false;
    //}

    /**
     * Retrieve groups [implement only where required/possible]
     *
     * Set getGroups capability when implemented
     *
     * @param   int $start
     * @param   int $limit
     * @return  array
     */
    public function retrieveGroups($start = 0, $limit = 0) {
        $query = $this->getConf('groups');
		$query = str_replace('%{db_prefix}', $this->getConf('db_prefix'), $query);
        $result = $this->_queryDB($query);
        $groups = array();
        foreach($result as $grp) {
            $groups[] = $grp;
        }
        return $groups;
    }

    /**
     * Return case sensitivity of the backend
     *
     * When your backend is caseinsensitive (eg. you can login with USER and
     * user) then you need to overwrite this method and return false
     *
     * @return bool
     */
    public function isCaseSensitive() {
        return true;
    }

    /**
     * Sanitize a given username
     *
     * This function is applied to any user name that is given to
     * the backend and should also be applied to any user name within
     * the backend before returning it somewhere.
     *
     * This should be used to enforce username restrictions.
     *
     * @param string $user username
     * @return string the cleaned username
     */
    public function cleanUser($user) {
        return $user;
    }

    /**
     * Sanitize a given groupname
     *
     * This function is applied to any groupname that is given to
     * the backend and should also be applied to any groupname within
     * the backend before returning it somewhere.
     *
     * This should be used to enforce groupname restrictions.
     *
     * Groupnames are to be passed without a leading '@' here.
     *
     * @param  string $group groupname
     * @return string the cleaned groupname
     */
    public function cleanGroup($group) {
        return $group;
    }

    /**
     * Check Session Cache validity [implement only where required/possible]
     *
     * DokuWiki caches user info in the user's session for the timespan defined
     * in $conf['auth_security_timeout'].
     *
     * This makes sure slow authentication backends do not slow down DokuWiki.
     * This also means that changes to the user database will not be reflected
     * on currently logged in users.
     *
     * To accommodate for this, the user manager plugin will touch a reference
     * file whenever a change is submitted. This function compares the filetime
     * of this reference file with the time stored in the session.
     *
     * This reference file mechanism does not reflect changes done directly in
     * the backend's database through other means than the user manager plugin.
     *
     * Fast backends might want to return always false, to force rechecks on
     * each page load. Others might want to use their own checking here. If
     * unsure, do not override.
     *
     * @param  string $user - The username
     * @return bool
     */
    //public function useSessionCache($user) {
      // FIXME implement
    //}

/*    private function _openDB() {
        global $conf;
        $this->db = mysql_connect($conf['auth']['mysql']['server'],$conf['auth']['mysql']['user'],$conf['auth']['mysql']['password']);
        return mysql_select_db($conf['auth']['mysql']['database']);
    }

    private function _queryDB($query) {
        $result = mysql_query($query,$this->db);
        if ($result) {
                $resultarray = null;
                while (($t = mysql_fetch_assoc($result)) !== false) {
                    $resultarray[]=$t;
                }
                mysql_free_result ($result);
                return $resultarray;
        }
    }

    private function _closeDB() {
        return mysql_close($this->db);
    }*/

    /**
     * Opens a connection to a database and saves the handle for further
     * usage in the object. The successful call to this functions is
     * essential for most functions in this object.
     *
     * @author Matthias Grimm <matthiasgrimm@users.sourceforge.net>
     *
     * @return bool
     */
    protected function _openDB() {
        if(!$this->dbcon) {
            $con = @mysql_connect($this->getConf('server'), $this->getConf('user'), $this->getConf('password'));
            if($con) {
                if((mysql_select_db($this->getConf('database'), $con))) {
                    if((preg_match('/^(\d+)\.(\d+)\.(\d+).*/', mysql_get_server_info($con), $result)) == 1) {
                        $this->dbver = $result[1];
                        $this->dbrev = $result[2];
                        $this->dbsub = $result[3];
                    }
                    $this->dbcon = $con;
                    if($this->getConf('charset')) {
                        mysql_query('SET CHARACTER SET "'.$this->getConf('charset').'"', $con);
                    }
                    return true; // connection and database successfully opened
                } else {
                    mysql_close($con);
                    $this->_debug("MySQL err: No access to database {$this->getConf('database')}.", -1, __LINE__, __FILE__);
                }
            } else {
                $this->_debug(
                    "MySQL err: Connection to {$this->getConf('user')}@{$this->getConf('server')} not possible.",
                    -1, __LINE__, __FILE__
                );
            }

            return false; // connection failed
        }
        return true; // connection already open
    }

    /**
     * Closes a database connection.
     *
     * @author Matthias Grimm <matthiasgrimm@users.sourceforge.net>
     */
    protected function _closeDB() {
        if($this->dbcon) {
            mysql_close($this->dbcon);
            $this->dbcon = 0;
        }
    }

    /**
     * Sends a SQL query to the database and transforms the result into
     * an associative array.
     *
     * This function is only able to handle queries that returns a
     * table such as SELECT.
     *
     * @author Matthias Grimm <matthiasgrimm@users.sourceforge.net>
     *
     * @param string $query  SQL string that contains the query
     * @return array with the result table
     */
    protected function _queryDB($query) {
        if($this->getConf('debug') >= 2) {
            msg('MySQL query: '.hsc($query), 0, __LINE__, __FILE__);
        }

        $resultarray = array();
        if($this->dbcon) {
            $result = @mysql_query($query, $this->dbcon);
            if($result) {
                while(($t = mysql_fetch_assoc($result)) !== false)
                    $resultarray[] = $t;
                mysql_free_result($result);
                return $resultarray;
            }
            $this->_debug('MySQL err: '.mysql_error($this->dbcon), -1, __LINE__, __FILE__);
        }
        return false;
    }
    /**
     * Wrapper around msg() but outputs only when debug is enabled
     *
     * @param string $message
     * @param int    $err
     * @param int    $line
     * @param string $file
     * @return void
     */
    protected function _debug($message, $err, $line, $file) {
        if(!$this->getConf('debug')) return;
        msg($message, $err, $line, $file);
    }
}

// vim:ts=4:sw=4:et:
