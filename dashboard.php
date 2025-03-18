<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="script.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>

.dashboard {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 15px;
}

header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
}

.back-btn {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
}

.settings-icon {
    font-size: 20px;
    cursor: pointer;
}

.loading {
    font-weight: bold;
    margin: 20px 0;
}

.profit-analysis {
    background: blue;
    color: white;
    padding: 25px;
    border-radius: 10px;
    margin-bottom: 25px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.amount {
    font-size: 24px;
    font-weight: bold;
}

.session-expired-cards {
    display: flex;
    flex-wrap: wrap;
    row-gap: 2em;
    gap: 10px;
}

.card {
    background: white;
    width: 48%;
    padding: 20px;
    text-align: center;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    font-size: 14px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.card.full-width {
    width: 100%;
}

.card i {
    font-size: 20px;
    margin-bottom: 5px;
}
.card p{
    font-size: 22px;
}

.bottom-nav {
    display: flex;
    justify-content: space-around;
    padding: 10px;
    background: white;
    border-radius: 10px;
    margin-top: 15px;
}

.nav-item {
    text-decoration: none;
    color: black;
    font-size: 14px;
    text-align: center;
}

.nav-item.active {
    color: blue;
}

    </style>
</head>
<body>
    <div class="dashboard">
        <header>
            <button class="back-btn">&#8592;</button>
            <h2>Dashboard</h2>
            <a href="screens/setting.php"><i class="settings-icon fas fa-cog"></i></a>
        </header>

        <div class="loading">Loading...</div>

        <div class="profit-analysis">
            <h3>Profit Analysis</h3>
            <p class="amount">$15,237,000</p>
            <p class="subtext">From the previous week</p>
        </div>

        <div class="session-expired-cards">
            <div class="card">
                <i class="fas fa-utensils"></i>
                <p> 22 </p>
                <span>Total Products</span>
            </div>
            <div class="card">
                <i class="fas fa-home"></i>
                <p> 42 </p>
                <span>Total Sales</span>
            </div>
            <div class="card full-width">
                <i class="fas fa-user"></i>
                <p> 52% </p>
                <span>Monthly Income Growth</span>
            </div>
        </div>

      
        <?php include("const/bottomNav.php") ?>
    </div>
    <script src="scripts/index.js"></script>
</body>
</html>
