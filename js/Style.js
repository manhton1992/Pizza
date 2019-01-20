
let address = {
    firstName : document.getElementById("firstName").value,
    lastName : document.getElementById("lastName").value,
    streetName : document.getElementById("streetName").value,
    streetNumber : document.getElementById("streetNumber").value,
    postCode : document.getElementById("postCode").value,
    cityName : document.getElementById("cityName").value,
};

function AddressInfo() {
    "use strict";
    address = {
        firstName: document.getElementById("firstName").value,
        lastName: document.getElementById("lastName").value,
        streetName: document.getElementById("streetName").value,
        streetNumber: document.getElementById("streetNumber").value,
        postCode: document.getElementById("postCode").value,
        cityName: document.getElementById("cityName").value,

    }
}

function checkValidAddressInfo() {
     AddressInfo();
     if (address.firstName == "" || address.lastName == "" || address.streetName == "" || address.streetNumber == "" || address.city == "" || address.cityName == "" || address.postCode == "") {
         return false;
     } else return true;
}
function print() {
    document.getElementById("list-kunden-bestelung").innerHTML = checkValidAddressInfo().toString() + address.firstName + address.lastName;
    console.log(address.firstName);
}

document.getElementById("addressInfo").onchange = function(){
    if (checkValidAddressInfo() ==  true) {
        document.getElementById("bestellung").disabled = false;
    } else {
        document.getElementById("bestellung").disabled = false;
    }
}

document.getElementById("bestellung").onclick = function () {
    if (checkValidAddressInfo() == false) {
        document.getElementById("list-kunden-bestelung").style.borderColor = "red";
        alert("Sie mussen zuerst passende Address!");
    } else {
        var select = document.getElementById("list-kunden-bestelung");
        for (var i = 0; i< select.length ; i++){
            select.options[i].setAttribute('selected', 'selected');
        }
        document.getElementById("list-kunden-bestelung").style.borderColor = "";
    }
}

function addPizzaIntoListBestellung(pizzaNumber, pizzaName, value) {
    var select = document.getElementById("list-kunden-bestelung");
    var option = document.createElement("option");
    var children = document.getElementById("list-kunden-bestelung").length;
    option.setAttribute("id", "pizza"+children);
    option.setAttribute("data-price", pizzaNumber);
    option.setAttribute("data-name",pizzaName);
    option.setAttribute("value", value);
    //option.setAttribute('selected', 'selected');
    option.appendChild(document.createTextNode("Pizza " + pizzaName));
    select.add(option);
    addPizzaCookie();
    document.getElementById("gesamtprice").value = SumPizzaPrice("list-kunden-bestelung");
    //alert(document.cookie);
}

function SumPizzaPrice(selectName) {
    "use strict";
    var sum = 0;
    var selectSum = document.getElementById(selectName);
    for (var i = 0; i< selectSum.length ; i++){
        sum = sum + parseFloat(selectSum.options[i].getAttribute("data-price"));
    }
    return sum;
}

function removeAll(selectId) {
    "use strict";
    var select = document.getElementById(selectId);
    while (select.options.length > 0) {
        select.remove(0);
    }
}

function removeAllPizzaBestellung() {
    "use strict";
    removeAll("list-kunden-bestelung");
    document.getElementById("gesamtprice").value = "";
    addPizzaCookie();
}

function removeOne(selectId){
    "use strict";
    var  euro = " &euro; ";
    var select = document.getElementById(selectId);
    select.remove(select.selectedIndex);
    document.getElementById("gesamtprice").value = SumPizzaPrice("list-kunden-bestelung");
    addPizzaCookie();
}
document.getElementById("loschen").onclick = function () {
    removeAllPizzaBestellung();
}
document.getElementById("loschen-one").onclick = function () {
    removeOne("list-kunden-bestelung");
}
function submitOnlyChangedInput(OrderId) {
    var allInputs = document.getElementsByTagName("input");
    for(var k = 0; k < allInputs.length; k++) {
        var name = allInputs[k].name.toString();
        var testName = "OrderStatus["+ OrderId + "]";
        //alert(name + testName);
        if (name != testName ){
            allInputs[k].disabled = true;
            }
    }
    document.getElementById("backer-form").submit();

}

function submitOnlyChangedSelect(OrderId) {
    var allInputs = document.getElementsByTagName("select");
    for(var k = 0; k < allInputs.length; k++) {
        var name = allInputs[k].name.toString();
        var testName = "FahrerStatus["+ OrderId + "]";
       //alert(name + testName);
        if (name != testName ){
            allInputs[k].disabled = true;
        }
    }
    document.getElementById("fahrer-form").submit();
}

/// Cookie in Client
function setCookie(name,value) {
    "use strict";
    var date = new Date();
    date.setTime(date.getTime() + (5*60*1000));
    var expires = "expires="+date.toUTCString();
    var cookie = [
        name,
        '=',
        JSON.stringify(value),
        ";"
    ].join('');
    document.cookie = cookie + expires;
}

let pizzaCookie = {
    Salami : 0,
    Pilze : 0,
    Schinken : 0,
}
function SoLuongPizza() {
    var Salami = 0;
    var Pilze = 0;
    var Schinken = 0;
    var select = document.getElementById("list-kunden-bestelung");
    for (var i = 0; i< select.length ; i++){
        var pizzaName = select.options[i].getAttribute('data-name');
        //alert(pizzaName);
        if (pizzaName == "Salami"){
            Salami += 1;
        } else if (pizzaName == "Pilze"){
            Pilze += 1;
        } else if (pizzaName == "Schinken") {
            Schinken += 1;
        }
    }
    pizzaCookie.Salami = Salami;
    pizzaCookie.Pilze = Pilze;
    pizzaCookie.Schinken = Schinken;

}
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length,c.length);
        }
    }
    return "";
}

function addPizzaCookie() {
    SoLuongPizza();
    setCookie("Salami",pizzaCookie.Salami);
    setCookie("Pilze",pizzaCookie.Pilze);
    setCookie("Schinken",pizzaCookie.Schinken);
}

function printCookie() {
    SoLuongPizza();
    alert(pizzaCookie.Pilze.toString() + pizzaCookie.Salami.toString() + pizzaCookie.Schinken.toString() + getCookie("Salami"));
}

function myOnloadFunction(){
    alert("Hello Ton");
}

