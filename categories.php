<?php
session_start();
include 'db.php';

if(!isset($_SESSION['id_user'])){
    header("Location: login.php");
    exit();
}
if(!isset($_GET['id_meal'])){
    die("Meal type not specified.");
}
$meal_id = intval($_GET['id_meal']);
$qr="SELECT * FROM categories WHERE id_meal='$meal_id'";
$res=mysqli_query($con, $qr);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link rel="stylesheet" href="categories.css">
</head>
<body>
    <div class="container">
    <header>
        <nav class="navbar">
          <img src="logo.png" alt="Smart Pantry Chef" style="width:100px;height:auto;margin:0;padding:0;display:block;">  
              <a href="home.php">Home</a>
             <a href="meals.php">Meals</a>
            <a href="categories.php" class="active">Categories</a>
            <a href="recipes.php">Recipes</a>
            <a  href="matching.php">Matching</a>
         <a href="budget.php">Budget</a>
           <a href="mood.php">Mood</a>
           <a href="history.php">History</a>
            <a href="favorite.php">Favorites</a>
        </nav>
    </header>
    <hr>
        <div class="cards">
            <?php
            if(mysqli_num_rows($res)>0){
            while($row=mysqli_fetch_array($res)){
                ?>
                <a href="recipes.php?id_category=<?php echo $row['id_category']; ?>" class="card">
                    <p><?php echo $row['name_category']; ?></p>
                </a>
    
                <?php
            }}
            ?>
        </div>
    </div>
</body>
</html>