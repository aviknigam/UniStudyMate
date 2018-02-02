// findbook.js is used in /account/sell to send an AJAX request to findbook.php
document.getElementById('search').addEventListener('click', findBook);
var searchResults = document.getElementById('searchResults');

function findBook() {
    // Create XHR Object
    var xhr = new XMLHttpRequest();

    // OPEN - type, url/file, async
    xhr.open("GET", "findbook.php", true);

    // Loading animation
    xhr.onprogress = function() {

    }

    // onload means xhr.readystate = 4 (request finished and response is ready) //
    xhr.onload = function() {
        // Returns the status-number of a request (200: "OK") //
        if (this.status == 200) {
            searchResults.innerHTML = this.responseText;
        } else if (this.status == 404) {
            searchResults.innerHTML = 'Not found';
        }
    }

    // If there is no textbook available
    xhr.onerror = function() {

    }

    // Sends request
    xhr.send();
}
