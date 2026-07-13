document.addEventListener('DOMContentLoaded', function() {
    // Variables for currency
    const currencySymbols = {
        'USD': '$',
        'EUR': '€',
        'GBP': '£'
    };
    let currentCurrency = 'USD';
    let selectedAmount = null;
    
    // Handle tab navigation
    const tabs = document.querySelectorAll('.tab');
    const formSections = document.querySelectorAll('.form-section');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Update active tab
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Show active section
            formSections.forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(tabId).classList.add('active');
        });
    });
    
    // Handle donation type selection
    const donationTypes = document.querySelectorAll('.donation-type');
    donationTypes.forEach(type => {
        type.addEventListener('click', function() {
            donationTypes.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Handle payment method selection
    const paymentMethods = document.querySelectorAll('.payment-method');
    const creditCardSection = document.getElementById('credit-card-section');
    const paypalSection = document.getElementById('paypal-section');
    
    paymentMethods.forEach(method => {
        method.addEventListener('click', function() {
            const methodType = this.getAttribute('data-method');
            
            paymentMethods.forEach(m => m.classList.remove('active'));
            this.classList.add('active');
            
            if (methodType === 'credit') {
                creditCardSection.style.display = 'block';
                paypalSection.style.display = 'none';
            } else {
                creditCardSection.style.display = 'none';
                paypalSection.style.display = 'block';
            }
        });
    });
    
    // Handle amount selection
    const amountButtons = document.querySelectorAll('.amount-button');
    const customAmount = document.querySelector('.custom-amount');
    
    function updateButtonLabels() {
        amountButtons.forEach(button => {
            const amount = button.getAttribute('data-amount');
            button.textContent = currencySymbols[currentCurrency] + amount;
        });
        
        customAmount.placeholder = currencySymbols[currentCurrency] + ' Enter Amount';
    }
    
    amountButtons.forEach(button => {
        button.addEventListener('click', function() {
            selectedAmount = this.getAttribute('data-amount');
            
            amountButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            customAmount.value = '';
        });
    });
    
    // Handle custom amount
    customAmount.addEventListener('focus', function() {
        amountButtons.forEach(b => b.classList.remove('active'));
        selectedAmount = null;
    });
    
    customAmount.addEventListener('input', function() {
        if (this.value) {
            selectedAmount = this.value;
        } else {
            selectedAmount = null;
        }
    });
    
    // Handle currency change
    const currencySelect = document.getElementById('currency-select');
    
    currencySelect.addEventListener('change', function() {
        currentCurrency = this.value;
        updateButtonLabels();
    });
    
    // Initial setup
    updateButtonLabels();
    
    // Navigation between sections
    const toBillingBtn = document.getElementById('to-billing-btn');
    const toPaymentBtn = document.getElementById('to-payment-btn');
    
    toBillingBtn.addEventListener('click', function() {
        tabs[1].click(); // Click the Billing tab
    });
    
    toPaymentBtn.addEventListener('click', function() {
        tabs[2].click(); // Click the Payment tab
    });
});