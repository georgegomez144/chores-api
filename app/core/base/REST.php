<?php

/**
 * Created by PhpStorm.
 * User: George Gomez
 * Date: 3/30/2017
 * Time: 9:02 AM
 */
final class REST
{
    private static $_auth = [];
    private static $_db = [];
    private static $_url = [];
    private static $_json = [];

    /**
     * Start REST-let App here.....
     */
    public static function run()
    {
        self::_connectDB(); // Connect to Database
        self::_loadCoreHelper(); // Load core Helper
        self::_loadCoreModel(); // Load core Model
        self::_runBootstrap(); // Being Application Bootstrapping Process
    }

    /**
     * Load Core Model Class file into system
     */
    private static function _loadCoreModel()
    {
        $file = ROOT.D.'app'.D.'modules'.D.'core'.D.'Model'.D.'Core.php';
        include (!file_exists($file)) ? die('Core Model is required') : $file;
    }

    /**
     * Load Core Helper Class file into system
     */
    private static function _loadCoreHelper()
    {
        $file = ROOT.D.'app'.D.'modules'.D.'core'.D.'Helper'.D.'Data.php';
        include (!file_exists($file)) ? die('Core Helper is required') : $file;
    }

    /**
     * @param $helper
     * @return mixed
     */
    public static function _helper($helper)
    {
        $helper = explode('/', $helper);
        if(count($helper) === 1)
        {
            $className = implode('_',[APP_NAME,ucfirst($helper[0]),'Helper','Data']);
            return new $className();
        }
        $file = ROOT.D.'app'.D.'modules'.D.$helper[0].D.'Helper'.D.ucfirst($helper[1]).'.php';
        include (file_exists($file)) ? $file : die('file does not exist');
        $className = implode('_',[APP_NAME,ucfirst($helper[0]),'Helper',ucfirst($helper[1])]);
        return new $className();
    }

    /**
     * @param $model
     * @return mixed
     */
    public static function getModel($model)
    {
        $model = explode('/', $model);
        if(count($model) === 1)
        {
            $className = implode('_',[APP_NAME,ucfirst($model[0]),'Model',ucfirst($model[0])]);
            return new $className();
        }
        $file = ROOT.D.'app'.D.'modules'.D.$model[0].D.'Model'.D.ucfirst($model[1]).'.php';
        include (file_exists($file)) ? $file : die('file does not exist');
        $className = implode('_',[APP_NAME,ucfirst($model[0]),'Model',ucfirst($model[1])]);
        return new $className();
    }

    public static function post()
    {
        return json_decode(file_get_contents('php://input'));
    }

    /**
     * @param $file
     * @return SimpleXMLElement
     */
    public static function loadxml($file)
    {
        return simplexml_load_file($file);
    }

    /**
     * @param array self::$_db
     * Load DB and pass in db params
     */
    private static function _connectDB()
    {
        include ROOT.D.'app'.D.'core'.D.'base'.D.'Database.php';
        new Database();
    }

    /**
     * Load Bootstrap module here...
     */
    private static function _runBootstrap()
    {
        include ROOT.D.'app'.D.'core'.D.'base'.D.'Bootstrap.php';
        new Bootstrap();
    }

    /**
     * @param array $db
     */
    public static function setDatabase(Array $db)
    {
        define("HOST",$db[SERVER_LOCATION]->host);
        define("DATABASE",$db[SERVER_LOCATION]->database);
        define("USER",$db[SERVER_LOCATION]->user);
        define("PASS",$db[SERVER_LOCATION]->pass);
    }

    /**
     * @param array $auth
     */
    public static function setAuth(Array $auth)
    {
        self::$_auth = $auth;
    }

    /**
     * @param string $index
     * @return array|mixed
     */
    public static function getAuth($index = null)
    {
        if($index !== null)
            return self::$_auth[$index];

        return self::$_auth;
    }

    /**
     * @return array
     */
    public static function getUrlParams()
    {
        return self::$_url;
    }

    /**
     * @param array $url
     */
    public static function setUrlParams(Array $url)
    {
        self::$_url = $url;
    }

    /**
     * @param null $index
     * @return array
     */
    public static function getHeaders($index = null)
    {
        $headersArr = [];
        foreach(headers_list() as $header)
        {
            $headerSplit = explode(':', $header);
            $headersArr[$headerSplit[0]] = $headerSplit[1];
        }
        if($index !== null)
        {
            return apache_request_headers()[$index] ?? $headersArr[$index];
        }
        return array_merge($headersArr, apache_request_headers());
    }

    /**
     * @return bool
     */
    public static function _access()
    {
        $eMsg = '<p>failed...bad credentials provided.<br /><strong>admin has been notified</strong></p>';
        if(!isset($_GET['hash']))
        {
            do
            {
                $headerRequest = self::getHeaders('HASH');
                if (!empty($headerRequest) && $headerRequest === self::getAuth('hash')) break;
                self::red($eMsg); die;
            } while(0);
        } else {
            $providedSecurity = $_GET['hash'];
            if(REST::getAuth('hash') !== $providedSecurity)
            { self::red($eMsg); die; }
        }
    }

    private static function _setJson($any)
    {
        self::$_json[] = $any;
    }

    public static function json($any)
    {
        header("Content-Type: application/json");
        self::_setJson($any);
        echo json_encode($any);
    }

    /**
     * @param array $array
     * @return array
     */
    public static function kvp(Array $array)
    {
        if(count($array) % 2 == 0)
        {
            $nArray = [];
            $array = array_chunk($array, 2);
            foreach($array as $a)
            {
                $nArray[$a[0]] = $a[1];
            }
            $array = null;
            return $nArray;
        } else {
            die("Can't be parsed");
        }
    }

    public static function queryFilter($filter)
    {
        $type = $filter['filterBy'];
        unset($filter['filterBy']);
        $filterBy = [];
        forEach($filter as $key => $value)
        {
            if ($type === 'exact')
            {
                $filterBy[] = "{$key} = {$value}";
            }
            if ($type === 'like')
            {
                $filterBy[] = "{$key} LIKE '%{$value}%'";
            }
        }
        $filterBy = implode(' and ', $filterBy);
        return "WHERE ".$filterBy;
    }






    // DEVELOPER MODE AND DEBUGGING TOOLS


    /**
     * @param $bool
     */
    public static function devMode($bool)
    {
    	define('DEV_MODE', $bool);
        if($bool)
        {
            header("USER:15caf4c16c8c1b64266e8a13ec65e44f69066995");
            header("HASH:".AUTH_HASH);
        }
    }

    public static function red($msg)
    {
        echo '<h4 style="color: red;">'.$msg.'</h4>';
    }

    public static function debug($data, $die = false)
    {
        echo '<script>console.log('.json_encode($data).');</script>';
        if ($die) die;
    }

    public static function toScreen($data, $die = true)
    {
        echo "
            <link href=\"https://fonts.googleapis.com/icon?family=Material+Icons\" rel=\"stylesheet\">
            <style>
                icon {
                    font-family: Material Icons;
                    font-size: 24px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    margin: 5px;
                    height: 40px;
                    width: 40px;
                    cursor: pointer;
                    color: #bbb;
                }
                icon:hover {
                    color: #fff;
                }
                .hide { display: none; }
                .vertical-text {
                    writing-mode: vertical-rl;
                    text-orientation: upright;
                }
                #screen-debug {
                    position: fixed;
                    top: 0;
                    left: 0;
                    display: flex;
                    flex-direction: row;
                    justify-content: flex-start;
                    align-items: flex-start;
                    height: 100%;
                    width: 50%;
                    background-color: #111;
                    color: #fff;
                }
                #screen-debug.closed {
                    flex-direction: column;
                    justify-content: flex-start;
                    align-items: center;
                    width: 50px;
                }
                #screen-debug.closed .vertical-text { display: block; }
                #screen-debug .vertical-text,
                #screen-debug.closed pre{ display: none; }
                #screen-debug pre {
                    height: calc(100% - 25px);
                    width: calc(100% - 70px);
                    padding: 2%;
                    background-color: #222;
                    color: #FF0;
                }
            </style>
            <div id=\"screen-debug\" class=\"closed\">
                <icon>menu</icon>
                <p class=\"vertical-text\">Debug Window</p>
                <pre>".$data."</pre>
            </div>
            <script>
            (() => {
                const debug = document.getElementById('screen-debug');
                const menuIcon = debug.querySelector('icon');
                menuIcon.addEventListener('click', function() {
                    if (debug.classList.contains('closed')) {
                        debug.classList.remove('closed');
                        this.textContent = 'menu_open';
                    } else {
                        debug.classList.add('closed');
                        this.textContent = 'menu';
                    }
                });
            })()
            </script>
        ";
        if ($die) die;
    }
}
