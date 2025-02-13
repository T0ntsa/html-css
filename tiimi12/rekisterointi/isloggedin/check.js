document.addEventListener("DOMContentLoaded", function () {
    fetch("check_login.php")
        .then(response => response.json())
        .then(data => {
            if (data.status === "loggedin") {
                // Ohjataan suojatulle PHP-sivulle
                window.location.href = "suojattu.php";
            } else {
                window.location.href = "login.html";
            }
        })
        .catch(error => console.error("Virhe kirjautumistarkistuksessa:", error));
});