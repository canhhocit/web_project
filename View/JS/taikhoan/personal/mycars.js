// Function to change image
function changeImage(button, direction) {
    // Find image section
    const imageSection = button.parentElement;
    const imageList = imageSection.querySelectorAll('.vehicle-image');
    const imageCounter = imageSection.querySelector('.image-counter');
    
    // Find current active image
    let currentIndex = 0;
    imageList.forEach((image, index) => {
        if (image.classList.contains('active')) {
            currentIndex = index;
            image.classList.remove('active');
        }
    });
    
    // Calculate new index
    let newIndex = currentIndex + direction;
    
    // Loop around if at end
    if (newIndex < 0) {
        newIndex = imageList.length - 1;
    } else if (newIndex >= imageList.length) {
        newIndex = 0;
    }
    
    // Show new image
    imageList[newIndex].classList.add('active');
    
    // Update counter
    if (imageCounter) {
        imageCounter.textContent = (newIndex + 1) + ' / ' + imageList.length;
    }
}

function confirmDelete(vehicleId) {
    if (confirm('Are you sure you want to delete this vehicle?')) {
        window.location = '/web_project/index.php?controller=vehicle&action=deleteV&id=' + vehicleId;
    }
    return false;
}