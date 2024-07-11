// Get references to the modal and the buttons
var modal = document.getElementById("uploadModal");
var openModalButton = document.getElementById("openModal");
var closeModalButton = document.getElementById("closeModal");

// Function to open the modal
function openModal() {
    modal.style.display = "block";
}

// Function to close the modal
function closeModal() {
    modal.style.display = "none";
}

// Event listeners for the buttons
openModalButton.addEventListener("click", openModal);
closeModalButton.addEventListener("click", closeModal);

// Close the modal if the user clicks outside of it
window.addEventListener("click", function(event) {
    if (event.target == modal) {
        closeModal();
    }
});
