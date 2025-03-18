let blinkCount = 0;
const icon = document.querySelector('.iconIndex');

function stopBlinkAndRedirect() {
    icon.style.animation = "none"; // Stop blinking
    window.location.href = "login.php"; // Redirect to login page
}

function countBlinks() {
    blinkCount++;
    if (blinkCount >= 5) {
        stopBlinkAndRedirect();
    }
}

setInterval(countBlinks, 1000); // Count blinks every second


document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', function () {
        document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
        this.classList.add('active');
    });
});