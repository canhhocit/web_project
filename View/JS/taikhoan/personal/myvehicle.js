function changeImage(button, direction) {
    const imageSection = button.parentElement;
    const imageList = imageSection.querySelectorAll('.vehicle-image');
    const imageCounter = imageSection.querySelector('.image-counter');
    
    let currentIndex = 0;
    imageList.forEach((image, index) => {
        if (image.classList.contains('active')) {
            currentIndex = index;
            image.classList.remove('active');
        }
    });
    
    let newIndex = currentIndex + direction;
    if (newIndex < 0) {
        newIndex = imageList.length - 1;
    } else if (newIndex >= imageList.length) {
        newIndex = 0;
    }
    imageList[newIndex].classList.add('active');
    
    if (imageCounter) {
        imageCounter.textContent = (newIndex + 1) + ' / ' + imageList.length;
    }
}

