<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions</title>
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.container {
    margin: 0 auto;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    font-size: 2.5rem;
    margin-bottom: 20px;
}

h2 {
    font-size: 1.8rem;
    margin-top: 20px;
    margin-bottom: 10px;
}

p {
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 20px;
}

.back-btn {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    font-size: 1rem;
    cursor: pointer;
    border-radius: 5px;
    margin-bottom: 20px;
    transition: background-color 0.3s;
}

.back-btn:hover {
    background-color: #0056b3;
}

.terms {
    margin-top: 30px;
}

    </style>
</head>
<body>
    <div class="container">
        <!-- Back Button -->
        <button class="back-btn" onclick="goBack()">&#8592; Back</button>
        
        <h1>Terms and Conditions</h1>
        <p>Last updated: March 19, 2025</p>
        
        <div class="terms">
            <h2>Introduction</h2>
            <p>These terms and conditions govern your use of this website...</p>
            
            <h2>Use of Website</h2>
            <p>By accessing and using this website, you agree to abide by the terms of use outlined...</p>
            
            <h2>Privacy Policy</h2>
            <p>Your privacy is important to us. We collect and use your personal data according to our Privacy Policy...</p>
            
            <h2>Limitation of Liability</h2>
            <p>We are not responsible for any damages arising from the use of this website...</p>
            
            <h2>Changes to Terms</h2>
            <p>We reserve the right to update or modify these terms at any time without notice...</p>
        </div>
    </div>

    <script >
        // Function to go back to the previous page
function goBack() {
    window.history.back();
}

    </script>
</body>
</html>
