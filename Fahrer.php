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
class Fahrer extends Page
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
        // to do: fetch data for this view from the database
        try{
            $orders = array();
            $sqlOrderpizza = "SELECT * FROM `orders` WHERE orders.OrderStatus > 1 AND orders.IsComplete = 0";
            //$sqlOrderpizza = "SELECT * FROM `orders`";
            $orderRecords = $this->_database->query($sqlOrderpizza);
            if(!$orderRecords){
                throw new Exception("Kein Orders in Datenbank");
            }
            if ($orderRecords){
                while ($row = mysqli_fetch_array($orderRecords)){
                    $thisOrder = array();
                    $OrderId = $row["OrderId"];
                    $AddressId = $row["AdressId"];
                    $OrderStatus = $row["OrderStatus"];
                    $OrderPrice = $row["OrderPrice"];
                    $thisOrder["OrderId"] = $OrderId;
                    $thisOrder["OrderStatus"] = $OrderStatus;
                    $thisOrder["OrderPrice"] = $OrderPrice;
                    $thisOrder["Address"] = array();
                    $sqlOrderAddress = "SELECT * FROM `address` WHERE address.AddressId = $AddressId";
                    $orderAddressRecords = $this->_database->query($sqlOrderAddress);
                   if (!$orderAddressRecords){
                       throw new Exception("Kein OrderAddress in Datenbank");
                   }
                   if ($orderAddressRecords){
                       while ($row = mysqli_fetch_array($orderAddressRecords)){
                            $thisOrderAddress = array();
                            $firstName = $row["FirstName"];
                            $lastName = $row["LastName"];
                            $streetName = $row["StreetName"];
                            $streetNumber = $row["StreetNumber"];
                            $postCode = $row["Postcode"];
                            $cityName = $row["City"];
                            $thisOrderAddress["FirstName"] = $firstName;
                            $thisOrderAddress["LastName"] = $lastName;
                            $thisOrderAddress["StreetName"] = $streetName;
                            $thisOrderAddress["StreetNumber"] = $streetNumber;
                            $thisOrderAddress["Postcode"] = $postCode;
                            $thisOrderAddress["City"] = $cityName;
                            array_push($thisOrder["Address"],$thisOrderAddress);
                       }
                   }
                   array_push($orders,$thisOrder);
                }
                return $orders;
            } else{
                echo "<script type='text/javascript'>alert('Khong lay duoc du lieu Fahrer');</script>";
            }
            return null;

        }catch (Exception $ex){
            $ex->getMessage();
        }

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
        $orders = $this->getViewData();
        $this->generatePageHeader('to do: change headline');
        // to do: call generateView() for all members
        // to do: output view of this page
        echo <<<FAHRER
        <div class="container margin-top-index">
            <div style="background-color: black">
                <div style="background-color: white">
                    <div class="text-center">
                        <h1> <strong> FAHRER </strong></h1>
                        <h3 style="font-family: 'Gill Sans Ultra Bold'" class="text-uppercase"> Lieferliste </h3>
                        <hr>
                    </div>
                    <form action="" method="post" id="fahrer-form">
                        <div class="row">
FAHRER;
        //--------------Code Hier---------------------
             $ordersCount = count($orders);
            if (count($orders) > 0 && $orders != null){
                 foreach ($orders as $order){
                    $OrderId = $order["OrderId"];
                    $OrderPrice = $order["OrderPrice"];
                    $OrderStatus = $order["OrderStatus"];
                    $oderAddress = $order["Address"][0];
                    $firstName = $oderAddress["FirstName"];
                    $lastName = $oderAddress["LastName"];
                    $streetName = $oderAddress["StreetName"];
                    $streetNumber = $oderAddress["StreetNumber"];
                    $postCode = $oderAddress["Postcode"];
                    $cityName = $oderAddress["City"];
                    $unterWegStatus = "";
                    $gelifiertStatus = "";
                    if ($OrderStatus == "3"){
                        $unterWegStatus = "selected";
                    }elseif($OrderStatus == "4"){
                        $gelifiertStatus = "selected";
                    }
                    echo <<<STATUS
                    <div class="text-center">
                        <span> Bestellung Number : $OrderId</span> <br>
                        <span> Price: $OrderPrice</span>
                        <div>
                            <span> Kunden Address : $firstName : $lastName :  $streetName : $streetNumber : $postCode : $cityName </span>
                        </div>
                    </div>
                    <div class="text-center">
                        <select  name="FahrerStatus[$OrderId]" onchange="submitOnlyChangedSelect($OrderId)">
                            <option> Status </option>
                            <option value="Unterweg_$OrderId" $unterWegStatus > Unterweg </option>
                            <option value="Geliegiert_$OrderId" $gelifiertStatus > Geliefiert</option>
                        </select>
                    </div>
                    <br> <hr>
STATUS;
                 }
            } else{
                echo "<div class=\"text-center\">
                    <p> Es gibt zur Zeit keine Bestellungen.  $ordersCount </p>
                </div>";
            }

        //--------------End Hier ---------------------
        echo <<<ENDFAHRER
                        </div>
                    </form>
        </div>
    </div>
</div>
ENDFAHRER;


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
        try{
            switch ($_SERVER["REQUEST_METHOD"]){
                case "POST" : {
                    if (isset($_POST["FahrerStatus"])){
                        $orderStatusAndOrderId = $_POST["FahrerStatus"];
                        foreach ($orderStatusAndOrderId as $Order){
                            list($orderStatus,$orderIdStr) = explode('_',$Order);
                           $orderId = (int)$orderIdStr;
                           $status = 0;
                           if($orderStatus == "Unterweg"){
                                $status = 3;
                           }else if( $orderStatus == "Geliegiert"){
                                $status = 4;
                           }
                           if ($status != 0){
                               if ($status == 3){
                                   $SQLAbfrage = "UPDATE `orders` SET  OrderStatus = $status WHERE OrderId = $orderId";
                                $this->_database->query($SQLAbfrage);
                               } elseif ($status == 4){
                                    $SQLAbfrage = "UPDATE `orders` SET  OrderStatus = $status, orders.IsComplete = 1 WHERE OrderId = $orderId ";
                                $this->_database->query($SQLAbfrage);
                               }

                           }

                        }
                    }

                }


            }
        }catch (Exception $ex){
            echo $ex->getMessage();
        }
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
            $page = new Fahrer();
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
Fahrer::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >