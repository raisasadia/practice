document.addEventListener("DOMContentLoaded", function () {
    const btn = document.getElementById("calcBtn");
    if (btn) {
        console.log("Button found");
        btn.addEventListener("click", function () {
            const num1 = parseFloat(document.getElementById("num1").value) || 0;
            const num2 = parseFloat(document.getElementById("num2").value) || 0;
            document.getElementById("result").value = num1 + num2;
        });
    } else {
        console.log("Button not found");
    }
});
