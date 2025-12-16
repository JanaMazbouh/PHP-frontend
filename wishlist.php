<?php 
session_start();
include 'db.php';

if(!isset($_SESSION['id_user'])){
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

if(isset($_GET['remove'])){
    $id_wish = (int)$_GET['remove'];
    mysqli_query($con, "DELETE FROM wishlist WHERE id_wish='$id_wish' AND id_user='$id_user'");
    header("Location: wishlist.php");
    exit();
}

if(isset($_GET['move_to_cart'])){
    $id_wish = (int)$_GET['move_to_cart'];
    $wishQuery = mysqli_query($con, "SELECT id_item FROM wishlist WHERE id_wish='$id_wish' AND id_user='$id_user'");
    if(mysqli_num_rows($wishQuery) > 0){
        $wishItem = mysqli_fetch_assoc($wishQuery);
        $id_item = (int)$wishItem['id_item'];
        $checkCart = mysqli_query($con, "SELECT * FROM cart WHERE id_user='$id_user' AND id_item='$id_item'");
        if(mysqli_num_rows($checkCart) > 0){
            mysqli_query($con, "UPDATE cart SET quantity=quantity+1 WHERE id_user='$id_user' AND id_item='$id_item'");
        } else {
            mysqli_query($con, "INSERT INTO cart (id_user, id_item, quantity) VALUES ('$id_user','$id_item',1)");
        }
        mysqli_query($con, "DELETE FROM wishlist WHERE id_wish='$id_wish' AND id_user='$id_user'");
    }
    header("Location: wishlist.php");
    exit();
}

$wishlistQuery = "
    SELECT w.id_wish, w.id_item, i.name_ingredient
    FROM wishlist w
    JOIN supermarket s ON w.id_item = s.id_item
    JOIN ingredient i ON s.id_ingredient = i.id_ingredient
    WHERE w.id_user='$id_user'
";
$wishlistResult = mysqli_query($con, $wishlistQuery);

$cartCountQuery = mysqli_query($con, "SELECT SUM(quantity) AS total FROM cart WHERE id_user='$id_user'");
$cartCountRow = mysqli_fetch_assoc($cartCountQuery);
$cartCount = $cartCountRow['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Wishlist</title>
<link rel="website icon" type="png" href="logo.png">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Lora:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="wishlist.css">
</head>
<body>
<div class="container">
    <a class="back-button" href="recipe_detail.php?id_recipe=7">Back </a>

    <h1>My Wishlist</h1>
    <p class="cart-info">Items in Cart: <?php echo (int)($cartCount ?? 0); ?></p>

    <div class="wishlist-container">
    <?php
    if($wishlistResult && mysqli_num_rows($wishlistResult) > 0){
        while($row = mysqli_fetch_assoc($wishlistResult)){
            echo "<div class='item-card'>
                    <h3>".$row['name_ingredient']."</h3>
                    <div class='action-icons' >
                        <a href='wishlist.php?move_to_cart=".$row['id_wish']."' title='Move to Cart'>
                            <span class='material-symbols-outlined'>add_shopping_cart</span>
                        </a>
                        <a href='wishlist.php?remove=".$row['id_wish']."' title='Remove'>
                            <span class='material-symbols-outlined'>remove_shopping_cart</span>
                        </a>
                    </div>
                  </div>";
        }
    } else {
        echo "<p style='text-align:center;color:#888;'>Your wishlist is empty.</p>";
    }
    ?>
    </div>
</div>
</body>
</html>