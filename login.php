<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Smart Pantry Chef</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f8e9ee;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .container {
            background: white;
            width: 360px;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .tabs {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            border-bottom: 2px solid #f2c1cf;
        }
        
        .tab {
            padding: 10px;
            cursor: pointer;
            font-weight: bold;
            color: #555;
        }
        
        .active {
            color: #d36a8a;
            border-bottom: 3px solid #d36a8a;
        }
        
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        
        .btn {
            width: 100%;
            background: #d36a8a;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            margin-top: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-top: 10px;
            text-align: left;
            font-size: 13px;
        }
        
        .checkbox-group input {
            width: auto;
            margin: 3px 0 0 0;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="tabs">
            <div class="tab active">Login</div>
            <div class="tab">Sign Up</div>
        </div>

        <div class="form" id="loginForm">
            <input type="email" placeholder="Enter your email">
            <input type="password" placeholder="Enter your password">
            <button class="btn">Login</button>
        </div>

      
        <div class="form" id="signupForm" style="display:none;">
            <input type="text" placeholder="Enter your name">
            <input type="email" placeholder="Enter your email">
            <input type="password" placeholder="Create password">

            <div class="checkbox-group">
                <input type="checkbox" id="newsletter">
                <label for="newsletter">Subscribe to our newsletter for new features, discounts, and updates</label>
            </div>

            <button class="btn">Sign Up</button>
        </div>

    </div>

    <script>
        
        const tabs = document.querySelectorAll('.tab');
        const loginForm = document.getElementById('loginForm');
        const signupForm = document.getElementById('signupForm');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                if (tab.textContent.trim() === "Login") {
                    loginForm.style.display = "block";
                    signupForm.style.display = "none";
                } else {
                    loginForm.style.display = "none";
                    signupForm.style.display = "block";
                }
            });
        });
    </script>

</body>

</html>