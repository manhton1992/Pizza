<?php	// UTF-8 marker äöüÄÖÜß€
/**
 * Class Page for the exercises of the EWA lecture
 * Demonstrates use of PHP including class and OO.
 * Implements Zend coding standards.
 * Generate documentation with Doxygen or phpdoc
 *
 * PHP Version 5
 *
 * @category File
 * @package  Pizzaservice
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 * @license  http://www.h-da.de  none
 * @Release  1.2
 * @link     http://www.fbi.h-da.de
 */

/**
 * This abstract class is a common base class for all
 * HTML-pages to be created.
 * It manages access to the database and provides operations
 * for outputting header and footer of a page.
 * Specific pages have to inherit from that class.
 * Each inherited class can use these operations for accessing the db
 * and for creating the generic parts of a HTML-page.
 *
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 */
abstract class Page
{
    // --- ATTRIBUTES ---

    /**
     * Reference to the MySQLi-Database that is
     * accessed by all operations of the class.
     */
    protected $_database = null;
    //public $Connection = null;

    // --- OPERATIONS ---

    /**
     * Connects to DB and stores
     * the connection in member $_database.
     * Needs name of DB, user, password.
     *
     * @return none
     */
    protected function __construct()
    {
        $host = "localhost";
        $user = "root";
        $pwd = "";
        $db = "asiapizza";
        $Connection = new MySQLi($host, $user, $pwd, $db);
        $this->_database = $Connection; /* to do: create instance of class MySQLi */;
        if(mysqli_connect_errno()){
            throw new Exception( "Connect failed: ".mysqli_connect_error());
        }
        if(!$Connection -> set_charset("utf8")){
            throw new Exception( "Charset failed:".$Connection->error);
        }
    }

    /**
     * Closes the DB connection and cleans up
     *
     * @return none
     */
    protected function __destruct()
    {
        $this->_database->close();
        // to do: close database
    }

    /**
     * Generates the header section of the page.
     * i.e. starting from the content type up to the body-tag.
     * Takes care that all strings passed from outside
     * are converted to safe HTML by htmlspecialchars.
     *
     * @param $headline $headline is the text to be used as title of the page
     *
     * @return none
     */
    protected function generatePageHeader($headline = "")
    {
        $headline  = htmlspecialchars($headline);
        header("Content-type: text/html; charset=UTF-8");

        // to do: output common beginning of HTML code
        // including the individual headline
        echo <<<HEADER
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Navigation</title>
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <link rel="stylesheet" href="css/Style.css">
                <link rel="stylesheet" href="css/css/bootstrap.min.css">
                <link rel="stylesheet" href="css/css/bootstrap.css">
                <link rel="stylesheet" href="css/css/bootstrap-theme.css">
                <link rel="stylesheet" href="css/css/bootstrap-theme.min.css">
            </head>
            <body  id="nav-share-file">
                <nav class="navbar navbar-inverse navbar-fixed-top nav" style="position: absolute">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" id="myNavbarcode" onclick="myFunction()">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="Pizza_Service.php"> HOME</a>
                        </div>
                        <div class="collapse navbar-collapse" id="Hambergerbutton">
                            <ul class="nav navbar-nav">
                                <li> <a href="Kunden.php">KUNDE</a></li>
                                <li> <a href="Backer.php">BÄKER</a></li>
                                <li> <a href="Fahrer.php">FAHRER</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
HEADER;


    }

    /**
     * Outputs the end of the HTML-file i.e. /body etc.
     *
     * @return none
     */
    protected function generatePageFooter()
    {
        // to do: output common end of HTML code
        echo <<<FOOTER
        </body>
        </html>
        <script type = "text/javascript" src = "js/Style.js"></script>
FOOTER;



    }

    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If every page is supposed to do something with submitted
     * data do it here. E.g. checking the settings of PHP that
     * influence passing the parameters (e.g. magic_quotes).
     *
     * @return none
     */
    protected function processReceivedData()
    {
        session_start();
        if (isset($_COOKIE['Language']) && isset($_COOKIE['Version'])){
            if (session_status() == PHP_SESSION_ACTIVE){
                session_regenerate_id(true);
            }
        } else{
            setcookie("Language", "PHP");
            setcookie("Version", "7.0");
            session_regenerate_id(true);
        }
        // For Session
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
         // last request was more than 30 minutes ago
            session_unset();     // unset $_SESSION variable for the run-time
            session_destroy();   // destroy session data in storage
        }
        $_SESSION['LAST_ACTIVITY'] = time();
        if (!isset($_SESSION['CREATED'])) {
             $_SESSION['CREATED'] = time();
        } else if (time() - $_SESSION['CREATED'] > 1800) {
            // session started more than 30 minutes ago
             session_regenerate_id(true);    // change session ID for the current session an invalidate old session ID
            $_SESSION['CREATED'] = time();  // update creation time
        }
        if (get_magic_quotes_gpc()) {
            throw new Exception
                ("Bitte schalten Sie magic_quotes_gpc in php.ini aus!");
        }
    }
} // end of class

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >