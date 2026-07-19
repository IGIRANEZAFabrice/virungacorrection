document.addEventListener('DOMContentLoaded', function() {
    // FAQ Accordion Functionality
    const faqQuestions = document.querySelectorAll('.faq-question-content');
    
    faqQuestions.forEach(question => {
        question.addEventListener('click', () => {
            const faqItem = question.parentElement;
            const answer = faqItem.querySelector('.faq-answer-content');
            const icon = question.querySelector('i');
            
            // Close all other FAQs
            faqQuestions.forEach(otherQuestion => {
                if (otherQuestion !== question) {
                    const otherItem = otherQuestion.parentElement;
                    const otherAnswer = otherItem.querySelector('.faq-answer-content');
                    const otherIcon = otherQuestion.querySelector('i');
                    
                    otherItem.classList.remove('active');
                    otherAnswer.style.maxHeight = null;
                    otherIcon.style.transform = 'rotate(0deg)';
                }
            });
            
            // Toggle current FAQ
            faqItem.classList.toggle('active');
            
            // Animate the answer height
            if (faqItem.classList.contains('active')) {
                answer.style.maxHeight = answer.scrollHeight + "px";
                icon.style.transform = 'rotate(180deg)';
            } else {
                answer.style.maxHeight = null;
                icon.style.transform = 'rotate(0deg)';
            }
        });
    });
});

// Form Submission Validation
const contactFormContent = document.getElementById('contactForm-content');
if (contactFormContent) {
    contactFormContent.addEventListener('submit', function(e) {
        const recaptchaResponse = this.querySelector('[name="g-recaptcha-response"]');
        if (recaptchaResponse && !recaptchaResponse.value) {
            e.preventDefault();
            alert("Please complete the reCAPTCHA verification.");
        }
    });
}

// Scroll Animation
function checkVisibility() {
    const elements = document.querySelectorAll('.info-card, .contact-form, .map-section, .faq-section, .newsletter, .cta-card');
    
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 150;
        
        if (elementTop < window.innerHeight - elementVisible) {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }
    });
}

// Initial check and then on scroll
window.addEventListener('scroll', checkVisibility);
window.addEventListener('load', checkVisibility);
