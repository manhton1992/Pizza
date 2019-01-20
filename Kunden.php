<?php	// UTF-8 marker äöüÄÖÜß€
/**
 * Class PageTemplate for the exercises of the EWA lecture
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

// to do: change name 'PageTemplate' throughout this file
require_once './Page.php';

/**
 * This is a template for top level classes, which represent
 * a complete web page and which are called directly by the user.
 * Usually there will only be a single instance of such a class.
 * The name of the template is supposed
 * to be replaced by the name of the specific HTML page e.g. baker.
 * The order of methods might correspond to the order of thinking
 * during implementation.

 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 */
class Kunden extends Page
{
    // to do: declare reference variables for members
    // representing substructures/blocks

    /**
     * Instantiates members (to be defined above).
     * Calls the constructor of the parent i.e. page class.
     * So the database connection is established.
     *
     * @return none
     */
    protected function __construct()
    {
        parent::__construct();
        // to do: instantiate members representing substructures/blocks
    }

    /**
     * Cleans up what ever is needed.
     * Calls the destructor of the parent i.e. page class.
     * So the database connection is closed.
     *
     * @return none
     */
    protected function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * @return none
     */
    protected function getViewData()
    {
        if (isset($_SESSION["OrderID"])){
            $OrderID = (int)$_SESSION["OrderID"];
            $thisOrder = array();
            $sqlFragen = "SELECT * FROM orders WHERE OrderID = $OrderID LIMIT 0, 1";
            $KundenOrders = $this->_database->query($sqlFragen);
            if (!$KundenOrders){
                throw new Exception("Kein OrderID in Datenbank");
            }
            if ($KundenOrders){
                $thisOrder["OrderID"] = $OrderID;
                while($row = mysqli_fetch_array($KundenOrders)){
                     $OrderPrice = $row["OrderPrice"];
                     $OrderStatus = $row["OrderStatus"];
                     $AdressId = $row["AdressId"];
                     $sqlAddressOrders = "SELECT * FROM address WHERE address.AddressID = $AdressId LIMIT 0, 1";
                    $KundenAddress = $this->_database->query($sqlAddressOrders);
                    if (!$KundenAddress){
                        throw new Exception("Error Kunden Data in Datenbank");
                    }
                    while ($addressrow = mysqli_fetch_array($KundenAddress)){
                        $firstName = $addressrow["FirstName"];
                        $lastName = $addressrow["LastName"];
                         $thisOrder["OrderPrice"] = $OrderPrice;
                        $thisOrder["OrderStatus"] = $OrderStatus;
                        $thisOrder["FirstName"] = $firstName;
                        $thisOrder["LastName"] = $lastName;
                        $thisOrder["AddressId"] = $AdressId;
                    }
                }
            }

            return $thisOrder;
        } else{
            return null;
        }
        // to do: fetch data for this view from the database
    }

    /**
     * First the necessary data is fetched and then the HTML is
     * assembled for output. i.e. the header is generated, the content
     * of the page ("view") is inserted and -if avaialable- the content of
     * all views contained is generated.
     * Finally the footer is added.
     *
     * @return none
     */
    protected function generateView()
    {
        $Order = $this->getViewData();
        $this->generatePageHeader('to do: change headline');
        // to do: call generateView() for all members
        // to do: output view of this page
        echo <<<KUNDEN
            <div class="container margin-top-index">
        <div style="background-color: black">
            <div style="background-color: white">
                <div class="text-center">
                    <h1> <strong> KUNDEN </strong></h1>
                    <h3 style="font-family: 'Gill Sans Ultra Bold'"> MEINE BESTELLUNG </h3>
                    <hr>
                </div>
        

KUNDEN;

       if ($Order != null){
           $OrderId = $Order["OrderID"];
           $OrderStatus = $Order["OrderStatus"];
           $OrderPrice = $Order["OrderPrice"];
           $OrderFirstName = $Order["FirstName"];
           $OrderLastName = $Order["LastName"];
           $bearbeitungStatus = "";
                 $inOfenStatus = "";
                 $fertigStatus ="";
                 $unterWegStatus = "";
                 $gelifiertStatus = "";
                 if ($OrderStatus == "0"){
                    $bearbeitungStatus = "checked";
                 } elseif($OrderStatus == "1"){
                     $inOfenStatus = "checked";
                 }elseif($OrderStatus == "2"){
                     $fertigStatus = "checked";
                 }elseif($OrderStatus == "3"){
                     $unterWegStatus = "checked";
                 }elseif($OrderStatus == "4"){
                     $gelifiertStatus = "checked";
                 }

           echo <<<ORDER
           <div class="text-center" style="font-size:18px">
                    <span class="show"> Hello $OrderFirstName $OrderLastName</span> <br>
                    <span class="show">Ihr Bestellung ID : $OrderId | Price : $OrderPrice &euro; </span> <br>
            </div>
                <div class="col-xs-12 col-sm-12 col-sm-offset-1">
                            <div class="form-group col-sm-2 text-center">
                                <label for="kunden">Bestellt</label>
                                <input type="radio" id="kunden" name="optradio" disabled="true" $bearbeitungStatus>
                            </div>
                            <div class="form-group col-sm-2 text-center">
                                <label> im offen </label>
                                <input type="radio" name="optradio" disabled="true" $inOfenStatus>
                            </div>
                            <div class="form-group col-sm-2 text-center">
                                <label> fertig </label>
                                <input type="radio" name="optradio" disabled="true" $fertigStatus>
                            </div>
                            <div class="form-group col-sm-2 text-center">
                                <label> unterweg </label>
                                <input type="radio" name="optradio" disabled="true" $unterWegStatus>
                            </div>
                            <div class="form-group col-sm-2 text-center">
                                <label> geliefert </label>
                                <input type="radio" name="optradio" disabled="true" $gelifiertStatus>
                            </div>
                 </div>
    
ORDER;


       } else{
            echo <<<NOORDER
             <div class="text-center" style="font-size:18px">
                    <span id="kunden-status" data-id="1" class="show"> Du hast noch keine Bestellung getätigt.</span>
             </div>
NOORDER;

       }
       echo <<<ENDKUNDEN
          </div>
        </div>
    </div>
ENDKUNDEN;


        $this->generatePageFooter();
    }

    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If this page is supposed to do something with submitted
     * data do it here.
     * If the page contains blocks, delegate processing of the
	 * respective subsets of data to them.
     *
     * @return none
     */
    protected function processReceivedData()
    {
        parent::processReceivedData();
        // to do: call processReceivedData() for all members
    }

    /**
     * This main-function has the only purpose to create an instance
     * of the class and to get all the things going.
     * I.e. the operations of the class are called to produce
     * the output of the HTML-file.
     * The name "main" is no keyword for php. It is just used to
     * indicate that function as the central starting point.
     * To make it simpler this is a static function. That is you can simply
     * call it without first creating an instance of the class.
     *
     * @return none
     */
    public static function main()
    {
        try {
            $page = new Kunden();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

// This call is starting the creation of the page.
// That is input is processed and output is created.
Kunden::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >