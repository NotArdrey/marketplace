document.addEventListener("DOMContentLoaded", function() {
    // Get all elements with class "cart-button"
    var buttons = document.querySelectorAll(".cart-button");

    // Attach click event listener to each button
    buttons.forEach(function(btn) {
        btn.addEventListener("click", function() {
            // Extract productID from the button's data-productid attribute
            var productID = btn.getAttribute("data-productid");

            // Open the corresponding modal
            openModal(productID);
        });
    });

    // Get all elements with class "close"
    var closeButtons = document.querySelectorAll(".close");

    // Attach click event listener to each close button
    closeButtons.forEach(function(closeBtn) {
        closeBtn.addEventListener("click", function() {
            // Extract productID from the close button's data-productid attribute
            var productID = closeBtn.getAttribute("data-productid");

            // Close the corresponding modal
            closeModal(productID);
        });
    });

    // Close modal when clicking outside of it
    window.addEventListener("click", function(event) {
        if (event.target.classList.contains("modal")) {
            var productID = event.target.getAttribute("data-productid");
            closeModal(productID);
        }
    });
});

// Function to open modal
function openModal(productID) {
    var modal = document.getElementById("cartModal_" + productID);
    if (modal) {
        modal.style.display = "flex";
    }
}

// Function to close modal
function closeModal(productID) {
    var modal = document.getElementById("cartModal_" + productID);
    if (modal) {
        modal.style.display = "none";
    }
}

function searchProducts() {
    var input = document.getElementById('search-product-input').value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.status === 200) {
            document.getElementById('product-display').innerHTML = this.responseText;
        }
    };
    xhr.send('search=' + input);
}

function loadCategory(category) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.status === 200) {
            document.getElementById('product-display').innerHTML = this.responseText;
        }
    };
    xhr.send('category=' + category);
}
