<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virunga Ecotours - Payment Methods</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link
      rel="shortcut icon"
      href="../images/logos/icon.png"
      type="image/x-icon"
    />
    
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/new.css" />
    <link rel="stylesheet" href="../css/earthy-theme.css" />
    <script src="../js/script.js" defer></script>
    
    <link rel="stylesheet" href="../css/payments.css" />
    
</head>
<body>
    <?php include "./includes/header.php"; ?>
    <div class="container">


        <section class="section">
            <h2><i class="fas fa-coins"></i> Accepted Currencies</h2>
            <div class="currency-grid">
                <div class="currency-card">
                    <i class="fas fa-dollar-sign currency-symbol"></i>
                    <h3>US Dollars (USD)</h3>
                    <p>Standard international currency for safari and ecotour payments. Widely accepted and recommended for all international transactions.</p>
                </div>
                <div class="currency-card">
                    <i class="fas fa-euro-sign currency-symbol"></i>
                    <h3>Euros (EUR)</h3>
                    <p>Widely accepted for European clients. Convenient option for travelers from the European Union and surrounding regions.</p>
                </div>
                <div class="currency-card">
                    <i class="fas fa-pound-sign currency-symbol"></i>
                    <h3>Pounds Sterling (GBP)</h3>
                    <p>Payments in British Pounds are accepted for UK-based clients and agencies. Perfect for British travelers.</p>
                </div>
                <div class="currency-card">
                    <i class="fas fa-money-bill-wave currency-symbol"></i>
                    <h3>Rwandan Francs (RWF)</h3>
                    <p>Local currency accepted for in-country transactions, additional services, and small balances.</p>
                </div>
            </div>
        </section>

        <section class="section payment-methods">
            <h2><i class="fas fa-credit-card"></i> Authorized Payment Methods</h2>
            <div class="payment-grid">
                <div class="payment-card">
                    <i class="fas fa-university payment-icon"></i>
                    <h3>International Bank Transfers</h3>
                    <p><strong>SWIFT/IBAN transfers</strong> - Recommended for deposits and large payments. Clients are responsible for covering their own bank charges. Funds are confirmed only once received in full in our account.</p>
                </div>
                <div class="payment-card">
                    <i class="fas fa-credit-card payment-icon"></i>
                    <h3>Credit & Debit Cards</h3>
                    <p><strong>Visa, Mastercard, American Express</strong> - Online payments processed with encryption and 3D-Secure authentication. Fast confirmation and global accessibility.</p>
                </div>
                <div class="payment-card">
                    <i class="fas fa-mobile-alt payment-icon"></i>
                    <h3>Mobile Money Services</h3>
                    <p><strong>MTN Mobile Money & Airtel Money</strong> - Available for regional clients. Convenient option for East African residents and last-minute payments.</p>
                </div>
                <div class="payment-card">
                    <i class="fas fa-money-bills payment-icon"></i>
                    <h3>Cash Transactions</h3>
                    <p><strong>USD, EUR, GBP, RWF accepted</strong> - For onsite payments. USD notes must be clean, undamaged, and issued from 2009 onwards to meet bank regulations.</p>
                </div>
            </div>
        </section>

        <section class="section">
            <h2><i class="fas fa-clipboard-list"></i> Payment Conditions</h2>
            <ul class="conditions-list">
                <li>
                    <strong>Deposit Requirement:</strong> A non-refundable deposit of 30–50% is mandatory upon booking to secure permits, accommodations, and services.
                </li>
                <li>
                    <strong>Balance Settlement:</strong> Full balance is due no later than 30 days before tour commencement.
                </li>
                <li>
                    <strong>Short-Notice Bookings:</strong> Reservations made within 30 days of departure require 100% upfront payment.
                </li>
            </ul>
        </section>

        <section class="section">
            <h2><i class="fas fa-file-invoice"></i> Confirmation & Documentation</h2>
            <p style="font-size: 1.1rem; line-height: 1.8; margin-bottom: var(--spacing-md);">Every transaction is followed by an official electronic receipt. Reservations are considered valid and confirmed only after the deposit is successfully received and acknowledged by the finance department.</p>
            
            <div class="highlight-box">
                <h3><i class="fas fa-shield-alt" style="margin-right: 0.5rem;"></i>Secure & Professional</h3>
                <p>Our payment system maintains professional financial standards for tourism operations across the Virunga Massif with complete transparency and security.</p>
                
                <div class="security-badges">
                    <span class="badge"><i class="fas fa-lock"></i> SSL Encrypted</span>
                    <span class="badge"><i class="fas fa-shield-check"></i> 3D Secure</span>
                    <span class="badge"><i class="fas fa-certificate"></i> PCI Compliant</span>
                    <span class="badge"><i class="fas fa-university"></i> Bank Grade Security</span>
                </div>
            </div>
        </section>
    </div>
    <?php include './includes/footer.php'; ?>
    <script src="../js/header.js" defer></script>
</body>
</html>