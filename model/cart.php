<?php
require_once("model.php");

class Cart extends Model {
	// Renvoie la liste des commandes associés à un client
	 private $productsFactory;
	public function __construct() {
		$this->productsFactory = new ProductsFactory();    
		if (!isset($_SESSION['cart'])){
			$_SESSION['cart']=array();
		}
    }

	public function getCart(){
		return $_SESSION['cart'];
	}

	public function addToCart($productId, $quantity){
		$product = $this->productsFactory->GetProductById($productId);
		$find = false;
		for ($i=0; $i < count($_SESSION['cart']);$i++){
			
			if ($_SESSION['cart'][$i][0]->Id == $productId){
				$_SESSION['cart'][$i][1] = $_SESSION['cart'][$i][1] + 1;
				return true;
			}
		}
		array_push($_SESSION['cart'],array($product, $quantity));
		return true;
	}

	public function removeFromCart($productId){
		for ($i=0; $i < count($_SESSION['cart']);$i++){
			if ($_SESSION['cart'][$i][0]->Id == $productId){
				$_SESSION['cart'][$i][1] = $_SESSION['cart'][$i][1] - 1;
				if ($_SESSION['cart'][$i][1] == 0){
						array_splice($_SESSION['cart'], $i, 1);
				}
			}
		}
		return true;
	}

	public function clearCart(){
		array_splice($_SESSION['cart'], 0, count($_SESSION['cart']));
		return true;
	}

    public function checkOut(){
		$req = "INSERT INTO tOrders (creationDate,done,id_tClients) VALUES (?,0,?)";
		$date = date(DATE_W3C);
		$this->executerRequete($req, array($date,$_SESSION['customer']->Id));
		for ($i=0; $i < count($_SESSION['cart']);$i++){
			$productReq = "INSERT INTO torders_products (quantity,id,id_tProducts) VALUES (?,LAST_INSERT_ID(),?)";
			$this->executerRequete($productReq, array($_SESSION['cart'][$i][1],$_SESSION['cart'][$i][0]->Id));
		}
		$this->clearCart();
		return true;
	}
}
?>