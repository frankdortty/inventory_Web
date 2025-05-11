<!DOCTYPE html>
<html lang="en">
<?php include("const/head.php")?>
<body>
        <div class="marketSpace">
            <div class="container">
            <header>
                <a href="#" class="back-btn" onclick="window.history.back();">←</a>
                <span class="title">MarketPlace</span>
            </header>

            <div class="market-grid">
                <a href="demo.php" class="market-item" >
                    <i class="fas fa-star"></i>
                    <span>Demo</span>
                </a>
                <a href="https://www.jumia.com.ng" class="market-item" target="_blank">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Jumia</span>
                </a>
                <a href="https://www.jiji.ng" class="market-item" target="_blank">
                    <i class="fas fa-store"></i>
                    <span>JiJi</span>
                </a>
                <a href="https://www.konga.com" class="market-item" target="_blank">
                    <i class="fas fa-warehouse"></i>
                    <span>Konga</span>
                </a>
                <a href="https://www.kara.com" class="market-item" target="_blank">
                    <i class="fas fa-box"></i>
                    <span>Kara</span>
                </a>
                <a href="https://www.dealdey.com" class="market-item" target="_blank">
                    <i class="fas fa-tags"></i>
                    <span>DealDey</span>
                </a>
                <a href="https://www.ajebomarket.com" class="market-item" target="_blank">
                    <i class="fas fa-building"></i>
                    <span>Ajebomarket</span>
                </a>
                <a href="https://www.printivo.com" class="market-item" target="_blank">
                    <i class="fas fa-print"></i>
                    <span>PRINTIVO</span>
                </a>
            </div>

            <?php include("const/bottomNav.php") ?>
            </div>
        </div>

    <script src="scripts/index.js"></script>
</body>
</html>
