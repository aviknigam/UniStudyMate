// findbook.js is used in /account/sell to send an AJAX request to findbook.php
document.getElementById('textbookSearch').addEventListener('submit', findBook);
var searchResults = document.getElementById('searchResults');

function findBook(e) {
    // Disable normal form function
    e.preventDefault();

    var textbookISBN = document.getElementById('textbookISBN').value;

    // Create XHR Object
    var xhr = new XMLHttpRequest();

    // OPEN - type, url/file, async
    xhr.open("GET", "findbook.php?url=" + textbookISBN, true);

    // Loading animation
    // xhr.onprogress = function() {
        document.getElementById('loader').classList.remove('hide');
        document.getElementById('loader').classList.add('show');
    // }

    // onload means xhr.readystate = 4 (request finished and response is ready) //
    xhr.onload = function() {
        // Returns the status-number of a request (200: "OK") //
        if (this.status == 200) {
        document.getElementById('loader').classList.remove('show');
        document.getElementById('loader').classList.add('hide');
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
