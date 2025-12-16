<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$results = [];
$error = '';

if (isset($_POST['submit'])) {

    $ingredientsText = trim($_POST['ingredients'] ?? '');

    if ($ingredientsText === '') {
        $error = 'Please enter at least one ingredient';
    } else {

        // split ingredients
        //array filter remove empty & null 
        // array map done for all index of array 
        // explode convert to array
        $ingredients = array_filter(array_map('trim', explode(',', $ingredientsText)));

    
        $whereParts = [];
        foreach ($ingredients as $ing) {
            $ing = mysqli_real_escape_string($con, strtolower($ing));
            $whereParts[] = "LOWER(i.name_ingredient) LIKE '%$ing%' ";
        
        }

        $whereSql = implode(' OR ', $whereParts);

        
        $sql = "
            SELECT 
                r.id_recipe,
                r.name_recipe,
                r.description,
                r.image,
                r.time_needed,
                COUNT(DISTINCT i.id_ingredient) AS matches
            FROM recipes r
            JOIN recipes_ingredient ri ON r.id_recipe = ri.id_recipe
            JOIN ingredient i ON ri.id_ingredient = i.id_ingredient
            WHERE ($whereSql)
            GROUP BY r.id_recipe
            ORDER BY matches DESC, r.name_recipe
            LIMIT 50
        ";

        $q = mysqli_query($con, $sql);

        while ($row = mysqli_fetch_assoc($q)) {
            $results[] = $row;
        }
    }
}
?>

<!doctype html>
<html lang="ar">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Smart Pantry Chef — Matching Ingredients</title>
<link rel="stylesheet" href="matching.css">
<link rel="website icon" type="png" href="logo.png">
</head>
<body>
<div class="container">
    <header>
       
        <nav class="navbar">
             <a href="logout.php">
     <img src="logo.png" alt="Smart Pantry Chef" style="width:100px;height:auto;margin:0;padding:0;display:block;">         
    </a> 
     <a href="home.php">Home</a>
             <a href="meals.php">Meals</a>
            <a href="categories.php">Categories</a>
            <a href="recipes.php">Recipes</a>
            <a class="active" href="matching.php">Matching</a>
         <a href="budget.php">Budget</a>
           <a href="mood.php">Mood</a>
           <a href="history.php">History</a>
            <a href="favorite.php">Favorites</a>
            <a href="supermarket.php">Market</a>
        </nav>
    </header>
<hr>
    
    <main>
        <section class="panel">
            <h2>Smart Ingredient Matching</h2>
            <h3>What's in Your Pantry?</h3>

            <?php if ($error): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>

            <form method="post">
                <label for="ingredients">Enter your ingredients (separated by commas):</label>
                <textarea id="ingredients" name="ingredients" placeholder="e.g., chicken, rice, tomatoes, garlic, onions, bell peppers..."><?= htmlspecialchars($_POST['ingredients'] ?? '') ?></textarea>
            

                <button type="submit" class="btn" name="submit">Find Matching Recipes</button>
            </form>
        </section>

        <section class="panel results">
            <h3>Results</h3>
            <?php if (empty($results) && !$error): ?>
                <p>No recipes were found that match the ingredients you entered!</p>
            <?php elseif (!empty($results)): ?>
                <div class="grid">
                    <?php foreach($results as $r): ?>
                        <article class="recipe-card">
                            <?php if (!empty($r['image'])): ?>
                                <img src="Image project/<?= htmlspecialchars($r['image']) ?>" alt="<?= htmlspecialchars($r['name_recipe']) ?>">
                            <?php else: ?>
                                <div class="noimg">No image</div>
                            <?php endif; ?>
                            <h4><?= htmlspecialchars($r['name_recipe']) ?></h4>
                            <p class="meta">Matches: <?= (int)$r['matches'] ?> — Time: <?= htmlspecialchars($r['time_needed']) ?></p>
              <p class="desc"><?= nl2br(htmlspecialchars(substr($r['description'],0,200))) ?><?php if (strlen($r['description'])>200) echo '...'; ?></p>
                            <a class="view" href="recipe_detail.php?id_recipe=<?= (int)$r['id_recipe'] ?>">View recipe</a>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

    </main>

    <footer>
        <p>Smart Pantry Chef &copy; <?= date('Y') ?></p>
    </footer>
</div>
</body>
</html>