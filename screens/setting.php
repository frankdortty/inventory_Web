<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="../style.css">
    <script defer src="script.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
            .settings-container {
                background: #fff;
                border-radius: 10px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                padding:25px 15px;
            }

            header {
                display: flex;
                align-items: center;
                gap: 15px;
                margin-bottom: 20px;
            }

            .back-btn {
                background: none;
                border: none;
                font-size: 20px;
                cursor: pointer;
            }

            .title {
                text-align: center;
                font-size: 20px;
                font-weight: bold;
                margin-bottom: 25px;
            }

            .settings-card {
                background: white;
                border-radius: 10px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                margin-bottom: 15px;
            }

            .setting-item {
                padding: 22px;
                border-bottom: 1px solid #ddd;
                cursor: pointer;
                font-size: 22px;
            }

            .setting-item:last-child {
                border-bottom: none;
            }

            .setting-item:hover {
                background: #f0f0f0;
            }

            /* Toggle Switch */
            .toggle-section {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: white;
                padding: 12px;
                border-radius: 10px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }

            .toggle-label {
                font-weight: bold;
                color: blue;
            }

            /* Switch */
            .switch {
                position: relative;
                display: inline-block;
                width: 34px;
                height: 20px;
            }

            .switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                border-radius: 34px;
                transition: .4s;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 14px;
                width: 14px;
                left: 3px;
                bottom: 3px;
                background-color: white;
                border-radius: 50%;
                transition: .4s;
            }

            input:checked + .slider {
                background-color: blue;
            }

            input:checked + .slider:before {
                transform: translateX(14px);
            }

    </style>
</head>
<body>
    <div class="settings-container">
        <header>
            <a href="#" class="back-btn" onclick="window.history.back();">←</a>
            <h2>Settings</h2>
        </header>

        <h3 class="title">Settings</h3>

        <div class="settings-card">
            <div class="setting-item">My Account</div>
            <div class="setting-item">Messages</div>
            <div class="setting-item">Add User</div>
        </div>

        <div class="settings-card">
            <div class="setting-item">Terms & Conditions</div>
            <div class="setting-item">Log Out</div>
        </div>

        <div class="toggle-section">
            <span class="toggle-label">Upload Online</span>
            <label class="switch">
                <input type="checkbox" id="toggleSwitch">
                <span class="slider"></span>
            </label>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggleSwitch = document.getElementById("toggleSwitch");

            toggleSwitch.addEventListener("change", function () {
                if (this.checked) {
                    console.log("Upload Online: ON");
                } else {
                    console.log("Upload Online: OFF");
                }
            });
        });

    </script>
</body>
</html>
