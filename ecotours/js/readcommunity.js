 // Simple JavaScript to handle image interactions
 document.addEventListener('DOMContentLoaded', function() {
    const thumbnails = document.querySelectorAll('.thumbnail');
    const mainImage = document.querySelector('.main-image');
    
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            mainImage.src = this.src.replace('300/200', '1200/600');
            
            // Add active state styling
            thumbnails.forEach(thumb => thumb.style.opacity = 0.7);
            this.style.opacity = 1;
        });
    });
    
    // Handle link interactions
    const links = document.querySelectorAll('.link');
    links.forEach(link => {
        link.addEventListener('click', function() {
            alert("This is a demo link. In the real website, this would navigate to more information.");
        });
    });
});