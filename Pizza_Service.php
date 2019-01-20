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
class Pizza_Service extends Page
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
            $pizzas = array();
            $sqlPizza = "SELECT * FROM pizza";
            $pizzaRecords = $this->_database->query($sqlPizza);
            if (!$pizzaRecords){
                throw new Exception("Kein Pizza in Datenbank");
            }
            if ($pizzaRecords){
                 while ($row = mysqli_fetch_array($pizzaRecords)){$thisPizza = array();
                    $pizaId = $row["PizzaId"];
                    $pizzaName = $row["PizzaName"];
                    $pizzaPrice = $row["PizzaPrice"];
                    $pizzaImageUrl = $row["ImageUrl"];
                    $thisPizza["PizzaId"] = $pizaId;
                    $thisPizza["PizzaName"] = $pizzaName;
                    $thisPizza["PizzaPrice"] = $pizzaPrice;
                    $thisPizza["ImageUrl"] = $pizzaImageUrl;
                    array_push($pizzas,$thisPizza);
                 }
            }
            return $pizzas;
        }catch (Exception $ex){
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
        $pizzas = $this->getViewData();
        $this->generatePageHeader('to do: change headline');
        // to do: call generateView() for all members
        // to do: output view of this page
        $pizzaCount = count($pizzas);
        echo <<<GENERATEVIEW

        <div class="container-fluid margin-top-index margin-botton">
        <div style="background-color: black">
            <div style="background-color: white">
                <div class="text-center">
                    <h1> <strong> MENU </strong></h1>
                    <hr>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12">   
GENERATEVIEW;
        foreach ($pizzas as $pizza){
                    $pizaId = $pizza["PizzaId"];
                    $pizzaName = $pizza["PizzaName"];
                    $pizzaPrice = (int)$pizza["PizzaPrice"];
                    $pizzaImageUrl = $pizza["ImageUrl"];
                    echo <<<PIZZA
                    <div class="col-md-4 text-center" id="$pizzaName" data-price="$pizzaPrice" onclick="addPizzaIntoListBestellung($pizzaPrice, '$pizzaName' , $pizaId)">
                            <img src="$pizzaImageUrl" alt="Pizza" class="img-for-all-device">
                            <p> $pizzaName - $pizzaPrice &euro; </p>
                     </div>
PIZZA;

        }
        echo <<<GENERATEVIEW
                      
                    </div>
                    
                </div>
                <div>
                    <hr>
                </div>
            </div>

            <div style="margin-top: 20px; background-color: white">
                <div class="text-center">
                    <h1> <strong> WARENKORB </strong></h1>
                    <hr>
                </div>
                <div class="col-md-12">
                    <div>
                        <form action="" method="post">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1" id="addressInfo">
                                        <div class="form-group" style="margin-top: 10px">
                                            <input type="text" id="firstName" name="firstName" value="" class="form-control" placeholder="First Name">
                                        </div>
                                         <div class="form-group" style="margin-top: 10px">
                                            <input type="text" id="lastName" name="lastName" value="" class="form-control" placeholder="Last Name">
                                        </div>
                                         <div class="form-group" style="margin-top: 10px">
                                            <input type="text" id="streetName" name="streetName" value="" class="form-control" placeholder="Street Name">
                                        </div>
                                         <div class="form-group" style="margin-top: 10px">
                                            <input type="text" id="streetNumber" name="streetNumber" value="" class="form-control" placeholder="Street Number">
                                        </div>
                                         <div class="form-group" style="margin-top: 10px">
                                            <input type="text" id="postCode" name="postCode" value="" class="form-control" placeholder="Post Code">
                                        </div>
                                         <div class="form-group" style="margin-top: 10px">
                                            <input type="text" id="cityName" name="cityName" value="" class="form-control" placeholder="City">
                                        </div>
                                    </div>
                                 </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-top: 10px">
                                        <select class="form-control" id="list-kunden-bestelung" name="listBestellung[]" size="5" multiple="multiple" readonly>
                                           
                                        </select> 
                                     </div>
                                     <div class="form-group" style="margin-top: 10px">
                                        <input type="text" class="form-control" id="gesamtprice" name="gesamtprice" rows="1">
                                    </div>
                                     <div>
                                        <input type="submit" class="btn btn-info" id="bestellung" value="Bestellung" disabled>
                                        <input type="button" class="btn btn-warning" id="loschen-one" value="One Löschen">
                                        <input type="button" class="btn btn-warning" id="loschen" value="Löschen">
                                       <!-- <input type="button" class="btn btn-warning" id="" value="COOKIE" onclick="printCookie()" > -->
                                    </div>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
GENERATEVIEW;
        echo <<<READY
            <script type="text/JavaScript">
                function funtionToBeCalled(){
                    
                    var Salami = getCookie("Salami");
                    var Pilze = getCookie("Pilze");
                    var Schinken = getCookie("Schinken");
                    
                    if (Salami != null && Salami > 0){
                        for (var i = 0; i < parseInt(Salami); i++){
                            document.getElementById('Salami').click();
                        }   
                        
                    }
                    if (Pilze != null && Pilze > 0){
                        for (var i = 0; i < parseInt(Pilze); i++){
                            document.getElementById('Pilze').click();
                        }
                        
                    } 
                    if (Schinken != null && Schinken > 0){
                        for (var i = 0; i < parseInt(Schinken); i++){
                            document.getElementById('Schinken').click();
                        }
                        
                    } 
                }
                window.onload=funtionToBeCalled;
            </script>
    
READY;

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
             switch($_SERVER["REQUEST_METHOD"]){
                case "POST": {
                    $price = $this->_database->real_escape_string(htmlspecialchars($_POST['gesamtprice']));
                    if (isset($price) && ($price != null) && (int)$price > 0){
                        $firstName = $this->_database->real_escape_string(htmlspecialchars($_POST["firstName"]));
                        $lastName = $this->_database->real_escape_string(htmlspecialchars($_POST["lastName"]));
                        $streetName =  $this->_database->real_escape_string(htmlspecialchars($_POST["streetName"]));
                        $streetNumber = $this->_database->real_escape_string(htmlspecialchars($_POST["streetNumber"]));
                        $postCode = $this->_database->real_escape_string(htmlspecialchars($_POST["postCode"]));
                        $cityName = $this->_database->real_escape_string(htmlspecialchars($_POST["cityName"]));
                        // Insert Kunden Address
                        if (isset($firstName) && isset($lastName) && isset($streetName) && isset($streetNumber) &&
                                    isset($postCode) && isset($cityName)) {
                            $SQLInsertAddress = "INSERT INTO address SET ".
                                        "FirstName = \"$firstName\", LastName = \"$lastName\", StreetName = \"$streetName\"
                                        , StreetNumber = \"$streetNumber\", Postcode = \"$postCode\", City=\"$cityName\"";
                            $Addressresult = $this->_database->query ($SQLInsertAddress);

                            // check if address has been in Database inserted else break
                            if ($Addressresult){
                                $newAddAdressID = $this->_database->insert_id;
                                $isComplete = 0;
                                $orderStaus = 0;
                                $SQLInsertOrders = "INSERT INTO `orders`(`AdressId`, `OrderStatus`, `IsComplete`, `OrderPrice`) VALUES ($newAddAdressID,$isComplete,$orderStaus,$price)";
                                $OrdersResult = $this->_database->query($SQLInsertOrders);

                                // check if orders has been in Database inserted
                                if ($OrdersResult){
                                    $newOrderId = $this->_database->insert_id;
                                    if (isset($_POST["listBestellung"])){
                                        $listBestellung =$_POST["listBestellung"];
                                        $salami = 0;
                                        $pilze = 0;
                                        $schinken = 0;
                                        foreach($listBestellung as $obj)
                                        {
                                            if ($obj == 1){
                                                $salami += 1;
                                            }
                                            elseif ($obj == 2){
                                                $pilze += 1;
                                            }
                                            elseif ($obj == 3){
                                                $schinken += 1;
                                            }
                                        }
                                        if ($salami > 0){
                                            $SQLInsertpizzaOrder = "INSERT INTO `orderedpizza`(`PizzaId`, `OrderId`, `NumberOfPizza`) VALUES (1,$newOrderId,$salami)";
                                            $OrdersPizzaResult = $this->_database->query($SQLInsertpizzaOrder);
                                            if (!$OrdersPizzaResult){
                                                echo "<script type='text/javascript'>alert('Error 1');</script>";
                                                break;
                                            }
                                        }
                                         if ($pilze > 0){
                                            $SQLInsertpizzaOrder = "INSERT INTO `orderedpizza`(`PizzaId`, `OrderId`, `NumberOfPizza`) VALUES (2,$newOrderId,$pilze)";
                                             $OrdersPizzaResult = $this->_database->query($SQLInsertpizzaOrder);
                                             if (!$OrdersPizzaResult){
                                                echo "<script type='text/javascript'>alert('Error 2');</script>";
                                                break;
                                            }
                                        }
                                         if ($schinken > 0){
                                            $SQLInsertpizzaOrder = "INSERT INTO `orderedpizza`(`PizzaId`, `OrderId`, `NumberOfPizza`) VALUES (3,$newOrderId,$schinken)";
                                             $OrdersPizzaResult = $this->_database->query($SQLInsertpizzaOrder);
                                             if (!$OrdersPizzaResult){
                                                echo "<script type='text/javascript'>alert('Error 3');</script>";
                                                break;
                                            }
                                        }

                                    }
                                    $_SESSION["OrderID"] = $newOrderId;
                                    $_SESSION['start'] = time();
                                    $_SESSION['expire']["OrderID"] = $_SESSION['start'] + (30 * 60);
                                    echo "<script type='text/javascript'>alert('Ihre Bestellung Bestätigung');</script>";

                                } else{
                                    echo "<script type='text/javascript'>alert('Error when Insert Orders in Database');</script>";
                                    break;
                                }
                            } else{
                                echo "<script type='text/javascript'>alert('Error when Insert Address in Database');</script>";
                                break;
                            }
                        } else{
                            echo "<script type='text/javascript'>alert('Passen Sie Ihr Address!');</script>";
                        }
                    } else{
                        echo "<script type='text/javascript'>alert('Kein Pizza Bestellung !');</script>";
                    }

                }
                default : {

                }
           }
        } catch (Exception $ex){
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
            $page = new Pizza_Service();
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
Pizza_Service::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >