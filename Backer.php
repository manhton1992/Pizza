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
class Backer extends Page
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
            // sql Fragen
           // $sqlOrderpizza = "SELECT * FROM orders WHERE orders.IsComplete = '0'";
           $sqlOrderpizza = "SELECT * FROM orders WHERE orders.IsComplete = 0";
            // call Records
            $orderRecords = $this->_database->query($sqlOrderpizza);
            if(!$orderRecords){
                throw new Exception("Kein Orders in Datenbank");
            }
            // Neu lay duoc du lieu tu Database
            if ($orderRecords){
                // Tao Bang Record
               //   $Record = $orderRecords->fetch_assoc();
               while ($row = mysqli_fetch_array($orderRecords)){
                   $thisOrder = array();
                   $OrderId = $row["OrderId"];
                   //$AddressId = $row["AdressId"];
                   $OrderStatus = $row["OrderStatus"];
                   $OrderPrice = $row["OrderPrice"];
                   $thisOrder["OrderId"] = $OrderId;
                   //$thisOrder["AddressId"] = $AddressId;
                   $thisOrder["OrderStatus"] = $OrderStatus;
                   $thisOrder["OrderPrice"] = $OrderPrice;
                   $thisOrder["AllOrderPizza"] = array();
                   // Lay Tat ca cac Pizza trong Order
                   $sqlAllOrderPizza = "SELECT * FROM orderedpizza WHERE orderedpizza.OrderId = $OrderId";
                   $orderPizzaRecords = $this->_database->query($sqlAllOrderPizza);
                   if (!$orderPizzaRecords){
                       throw new Exception("Kein OrderPizza in Datenbank");
                   }
                   if ($orderPizzaRecords){
                       while ($row = mysqli_fetch_array($orderPizzaRecords)){
                           $thisOrderpizzas = array();
                            $pizzaId = $row["PizzaId"];
                            $numOfPizza = $row["NumberOfPizza"];
                            $pizzaName = "";
                            $thisOrderpizzas["PizzaId"] = $pizzaId;
                            $thisOrderpizzas["NumberOfPizza"] = $numOfPizza;
                            // PIZZA NAME
                            $sqlPizzaName = "SELECT * FROM `pizza` WHERE pizza.PizzaId = $pizzaId LIMIT 0, 1";
                            $orderPizzaName = $this->_database->query($sqlPizzaName);
                            if (!$orderPizzaName){
                                throw new Exception("ERROR FIND PIZZA IN DATENBANK");
                            }
                            if ($orderPizzaName){
                                while ($row = mysqli_fetch_array($orderPizzaName)){
                                    $pizzaName =  $row["PizzaName"];
                                }
                            }
                            $thisOrderpizzas["PizzaName"] = $pizzaName;
                            array_push($thisOrder["AllOrderPizza"], $thisOrderpizzas);
                       }
                   }
                   array_push($orders,$thisOrder);
               }
               return $orders;
            }
            else{
                 echo "<script type='text/javascript'>alert('Khong lay duoc du lieu.');</script>";
            }
            return null;
            // Giai Phong bo nho
            //mysql_free_result($orderRecords);
        } catch (Exception $ex){
            echo $ex->getMessage();
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
        if (count($orders) > 0 && $orders != null){
            echo <<<LISTBESTELLUNG
            <div class="container margin-top-index">
                <div style="background-color: black">
                    <div style="background-color: white">
                        <div class="text-center">
                            <h1> <strong> Bäcker </strong></h1>
                            <h3 style="font-family: 'Gill Sans Ultra Bold'"> BESTELLISTE </h3>
                            <hr>
                        </div>
                        <hr>
                        <form action="" method="post" id="backer-form">
                            <div class="row">

LISTBESTELLUNG;
            // --------------CODE HIER ---------
            foreach ($orders as $order){
                $orderId = $order["OrderId"];
                $orderStatus = $order["OrderStatus"];
                //$orderPrice = $order["OrderPrice"];
                $AllOrderPizzas = $order["AllOrderPizza"];
                echo <<<ORDER
                <div class="text-center">
                        <span> <strong> OrderId : $orderId </strong> </span> <br>

ORDER;
                 foreach ($AllOrderPizzas as $allorderpizza){
                    $pizzaName = $allorderpizza["PizzaName"];
                    //$pizzaId = $allorderpizza["PizzaId"];
                    $numOfPizza = $allorderpizza["NumberOfPizza"];
                    echo "<span class='text-center'> <strong> $pizzaName : $numOfPizza </strong> </span> <span> | </span>";
                }
                $bearbeitungStatus = "";
                 $inOfenStatus = "";
                 $fertigStatus ="";
                 if ($orderStatus == "0"){
                    $bearbeitungStatus = "checked";
                 } elseif($orderStatus == "1"){
                     $inOfenStatus = "checked";
                 }elseif($orderStatus == "2"){
                     $fertigStatus = "checked";
                 }
                echo <<<STATUS
                
                <div class="col-xs-12 col-sm-12 col-sm-offset-3">
                        <div class="form-group col-sm-2 text-center">
                             <input type="radio" $bearbeitungStatus name="OrderStatus[$orderId]" value="Bestellung_$orderId" id="status_0" onclick="submitOnlyChangedInput($orderId)"> <label for="status_0"> In Bearbeitung</label>
                        </div>
                        <div class="form-group col-sm-2 text-center">
                            <input type="radio" $inOfenStatus name="OrderStatus[$orderId]" value="ImOfen_$orderId" id="status_1" onclick="submitOnlyChangedInput($orderId)"> <label for="status_1"> Im Ofen </label>
                        </div>
                        <div class="form-group col-sm-2 text-center">
                            <input type="radio" $fertigStatus name="OrderStatus[$orderId]" value="Fertig_$orderId" id="status_2" onclick="submitOnlyChangedInput($orderId)"><label for="status_2">  Fertig </label>
                        </div>                  
                </div>
STATUS;

                echo <<<ENDORDER
                </div>
                <br> <hr>
ENDORDER;
            }
            // ----------------END  HIER -------

            echo <<<ENDLISTBESTELLUNG
            </div>
                <hr>
                <div class="text-center">
                    <button class="btn btn-info btn-lg"> Aktualisieren </button>
                </div>
            </form>
             </div>
    </div>
</div>
ENDLISTBESTELLUNG;

        } else{
            echo <<<NOTBESTELLUNG
             <div class="text-center">
                <p> Es gibt zur Zeit keine Bestellungen.</p>
            </div>
NOTBESTELLUNG;
        }
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
                    if (isset($_POST["OrderStatus"])){
                        //echo "<script type='text/javascript'>alert('Backer Post');</script>";
                        $orderStatusAndOrderId = $_POST["OrderStatus"];
                        foreach ($orderStatusAndOrderId as $Order){
                           list($orderStatus,$orderIdStr) = explode('_',$Order);
                           $orderId = (int)$orderIdStr;
                           $status = 0;
                           if($orderStatus == "ImOfen"){
                                $status = 1;
                           }else if( $orderStatus == "Fertig"){
                                $status = 2;
                           }
                           $SQLAbfrage = "UPDATE orders SET "." OrderStatus = $status WHERE "." OrderId = $orderId";
                           $this->_database->query($SQLAbfrage);
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
            $page = new Backer();
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
Backer::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >