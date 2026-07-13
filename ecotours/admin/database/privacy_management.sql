-- Privacy Management Database Tables
-- This file contains the SQL statements to create the necessary tables for privacy management

-- Create privacy_policy table
CREATE TABLE IF NOT EXISTS privacy_policy (
    id INT PRIMARY KEY AUTO_INCREMENT,
    content LONGTEXT NOT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create privacy_requests table
CREATE TABLE IF NOT EXISTS privacy_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    request_type ENUM('data_access', 'data_deletion', 'data_portability', 'data_correction') NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(500) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('pending', 'in_progress', 'completed', 'rejected') DEFAULT 'pending',
    admin_response TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_status (status),
    INDEX idx_request_type (request_type),
    INDEX idx_created_at (created_at)
);

-- Insert comprehensive privacy policy content from privacy.php
INSERT IGNORE INTO privacy_policy (id, content) VALUES (1, '
# Privacy Policy - Virunga Ecotours

**Last Updated: April 12, 2025**

At Virunga Ecotours, we''re committed to protecting your personal information and being transparent about how we use it.

## 1. Introduction

Welcome to Virunga Ecotours, your trusted partner for authentic wildlife and conservation experiences in the Virunga region. This Privacy Policy is designed to help you understand how we collect, use, and protect your personal information when you visit our website, book our tours, or interact with us in any way.

We are committed to privacy protection and are not interested in storing or using personal data commercially. Therefore, Virunga Ecotours takes the European General Data Protection Regulation (GDPR), the Canadian Personal Information Protection and Electronic Documents Act (PIPEDA), and other privacy laws seriously and with utmost respect.

This policy applies to all services offered by Virunga Ecotours, including our website, mobile applications, customer service interactions, and in-person experiences during our tours.

### Our Promise to You

At Virunga Ecotours, we promise to protect your information and ensure it remains confidential. We also promise never to sell your information to anyone. The information we request is solely to provide you with customized service and the highest levels of personal experience on every trip.

By using our services, you consent to the practices described in this policy. If you do not agree with any part of this policy, please do not use our services.

## 2. Information We Collect

We collect various types of information to provide you with the best possible experience and to fulfill our contractual and legal obligations. The aims of data collection are simply part of our research to get to know more about our travel clients, so the more information we have, the better we can customize and improve your experience.

### Personal Information You Provide

- **Contact Information:** Names, email addresses, phone numbers, countries of origin, mailing/billing addresses
- **Account Information:** Username, password, account preferences
- **Booking Information:** Travel dates, tour selections, accommodation preferences
- **Identity Documents:** Passport information (edited copies only), visa details (as required for booking certain tours or activities)
- **Health Information:** Medical conditions, allergies, dietary restrictions, or special requirements that may affect your tour experience
- **Payment Information:** Credit card details, billing address (note that payment processing is handled by secure third-party payment processors)

### Information Collected Automatically

- **Device Information:** IP address, browser type, operating system
- **Usage Data:** Pages visited, links clicked, time spent on each page
- **Cookies and Similar Technologies:** Information collected through cookies, web beacons, and similar technologies

### Special Note About Sensitive Information

We collect certain sensitive information (such as health data or passport details) only when necessary for your safety, to fulfill your booking requests, or to comply with legal requirements. This information receives special protection in our systems.

## 3. How We Use Your Information

The only purpose of all the requested information is to offer precise and customized service based on the information you decide to provide us. Here''s specifically how we use your information:

### Essential Service Provision

- Processing and confirming your tour bookings
- Arranging necessary permits, accommodations, and transportation
- Communicating important information about your tour
- Providing customer support before, during, and after your journey
- Processing payments and issuing refunds when applicable

### Personalization and Improvement

- Customizing your experience based on your preferences
- Recommending tours and activities that might interest you
- Analyzing how our services are used to improve them

### Communication

- Sending confirmation emails and important updates about your bookings
- Providing information about changes to our services or policies
- Sending newsletters or promotional content (only if you''ve opted in)
- Responding to your inquiries, comments, and requests

### Legal and Safety Purposes

- Complying with legal obligations and regulatory requirements
- Preventing, detecting, and investigating potential fraud or security issues
- Enforcing our terms of service and other policies
- Ensuring the safety and security of our customers, staff, and wildlife

### Lawful Basis for Processing

We process your information based on one or more of the following legal grounds:

- **Contract fulfillment:** When we need your data to provide services you''ve requested
- **Legal obligation:** When we must process your data to comply with laws
- **Legitimate interests:** When it serves our legitimate business interests in ways that don''t override your rights
- **Consent:** When you''ve explicitly agreed to certain types of processing

## 4. Information Sharing

We understand the importance of keeping your information secure and private. **We do not sell your personal information to third parties.** However, we may share your information in the following circumstances:

### Service Providers

We work with trusted third-party service providers who perform services on our behalf, such as:

- **Tour operators and local guides** who help us deliver your ecotour experience
- **Payment processors** who securely handle transactions
- **Transportation providers** including airlines and ground transport companies
- **Accommodation providers** such as lodges, hotels, and campsite operators
- **IT and cloud service providers** who help us maintain our website and systems

These service providers are bound by contractual obligations to keep your information confidential and use it only for the purposes for which we disclose it to them.

### Legal Requirements

We may disclose your information when we believe in good faith that disclosure is necessary to:

- Comply with applicable laws, regulations, or legal processes
- Respond to valid requests from public and governmental authorities
- Enforce our terms and conditions
- Protect our rights, property, or safety, as well as those of our customers

### Business Transfers

If Virunga Ecotours is involved in a merger, acquisition, or sale of assets, your information may be transferred as part of that transaction. We will notify you via email and/or a prominent notice on our website of any change in ownership or uses of your personal information.

### Third-Party Websites

Our website may include embedded content (e.g., videos, images, posts) from other websites like Facebook, Instagram, TripAdvisor, YouTube, or LinkedIn. These websites may collect data about you, use cookies, embed additional tracking, and monitor your interaction with that embedded content. We have no control or responsibility over what happens with third-party websites.

## 5. Your Rights

Depending on your location, you may have certain rights regarding your personal data. We respect these rights and will respond to any requests in accordance with applicable laws:

- **Right to Access:** You have the right to request access to the personal information we hold about you.
- **Right to Rectification:** You can request that we correct any inaccurate or incomplete information about you.
- **Right to Erasure:** In certain circumstances, you can ask us to delete your personal information.
- **Right to Restrict Processing:** You may have the right to request that we limit how we use your data.
- **Right to Data Portability:** You may have the right to receive your personal data in a structured, commonly used format.
- **Right to Object:** You can object to our processing of your personal information in certain circumstances.
- **Right to Withdraw Consent:** If we process your data based on your consent, you can withdraw that consent at any time.

To exercise any of these rights, please contact us using the information provided in the "Contact Us" section. We will respond to your request within the timeframe required by applicable law.

## 6. Data Security

We implement appropriate technical and organizational measures to protect your personal information against unauthorized or unlawful processing, accidental loss, destruction, or damage.

Our security measures include:

- Encryption of sensitive data
- Regular security assessments
- Access controls and authentication procedures
- Staff training on data protection and security
- Secure network architecture and monitoring

While we take reasonable steps to protect your information, no security system is impenetrable, and we cannot guarantee the absolute security of your data. If you have reason to believe that your interaction with us is no longer secure, please notify us immediately.

## 7. Data Retention

We retain your personal information for as long as necessary to fulfill the purposes for which it was collected, including to satisfy legal, accounting, or reporting requirements.

To determine the appropriate retention period, we consider:

- The amount, nature, and sensitivity of the personal data
- The potential risk of harm from unauthorized use or disclosure
- The purposes for which we process the data
- Whether we can achieve those purposes through other means
- Legal, regulatory, and contractual requirements

After the retention period expires, we will securely delete or anonymize your information in accordance with our data retention policies.

## 8. International Data Transfers

As a tour operator with global operations, we may transfer your personal information to countries outside your own. Some of these countries may have different data protection laws than your country of residence.

When we transfer your information internationally, we take appropriate safeguards to ensure that your personal information receives adequate protection, which may include:

- Using standard contractual clauses approved by relevant regulatory authorities
- Transferring to countries that have been deemed to provide an adequate level of protection
- Implementing appropriate technical and organizational measures to protect your information

By using our services, you consent to the transfer of your information to countries outside your country of residence, including to countries that may not provide the same level of data protection as your home country.

## 9. Policy Changes

We may update this Privacy Policy from time to time to reflect changes in our practices, services, or applicable laws and regulations. When we make changes, we will update the "Last Updated" date at the top of this policy.

For significant changes, we will provide notice through our website or by sending you an email notification. We encourage you to review this policy periodically to stay informed about how we are protecting your information.

Your continued use of our services after any changes to this Privacy Policy constitutes your acceptance of the revised policy.

## 10. Contact Us

If you have any questions, concerns, or requests regarding this Privacy Policy or our data practices, please contact us:

### Contact Information

**Email:** privacy@virungaecotours.com
**Phone:** +250 788 123 456
**Address:** Virunga Ecotours, P.O. Box 6754, Kigali, Rwanda

**Data Protection Officer:** For formal inquiries or if you wish to escalate a privacy concern, you can contact our Data Protection Officer at info@virungaecotours.com.

We will respond to your inquiry as soon as possible and within the timeframe required by applicable law.

### Exercise Your Privacy Rights

You have the right to access, correct, delete, or port your personal data. Submit a formal privacy request using our secure online form available on our website.

Last Updated: April 12, 2025
');

-- Force update of existing privacy policy record with comprehensive content
-- This ensures the database reflects the complete privacy policy from privacy.php
DELETE FROM privacy_policy WHERE id = 1;

-- Re-insert the comprehensive privacy policy
INSERT INTO privacy_policy (id, content) VALUES (1, '
# Privacy Policy - Virunga Ecotours

**Last Updated: April 12, 2025**

At Virunga Ecotours, we''re committed to protecting your personal information and being transparent about how we use it.

## 1. Introduction

Welcome to Virunga Ecotours, your trusted partner for authentic wildlife and conservation experiences in the Virunga region. This Privacy Policy is designed to help you understand how we collect, use, and protect your personal information when you visit our website, book our tours, or interact with us in any way.

We are committed to privacy protection and are not interested in storing or using personal data commercially. Therefore, Virunga Ecotours takes the European General Data Protection Regulation (GDPR), the Canadian Personal Information Protection and Electronic Documents Act (PIPEDA), and other privacy laws seriously and with utmost respect.

This policy applies to all services offered by Virunga Ecotours, including our website, mobile applications, customer service interactions, and in-person experiences during our tours.

### Our Promise to You

At Virunga Ecotours, we promise to protect your information and ensure it remains confidential. We also promise never to sell your information to anyone. The information we request is solely to provide you with customized service and the highest levels of personal experience on every trip.

By using our services, you consent to the practices described in this policy. If you do not agree with any part of this policy, please do not use our services.

## 2. Information We Collect

We collect various types of information to provide you with the best possible experience and to fulfill our contractual and legal obligations. The aims of data collection are simply part of our research to get to know more about our travel clients, so the more information we have, the better we can customize and improve your experience.

### Personal Information You Provide

- **Contact Information:** Names, email addresses, phone numbers, countries of origin, mailing/billing addresses
- **Account Information:** Username, password, account preferences
- **Booking Information:** Travel dates, tour selections, accommodation preferences
- **Identity Documents:** Passport information (edited copies only), visa details (as required for booking certain tours or activities)
- **Health Information:** Medical conditions, allergies, dietary restrictions, or special requirements that may affect your tour experience
- **Payment Information:** Credit card details, billing address (note that payment processing is handled by secure third-party payment processors)

### Information Collected Automatically

- **Device Information:** IP address, browser type, operating system
- **Usage Data:** Pages visited, links clicked, time spent on each page
- **Cookies and Similar Technologies:** Information collected through cookies, web beacons, and similar technologies

### Special Note About Sensitive Information

We collect certain sensitive information (such as health data or passport details) only when necessary for your safety, to fulfill your booking requests, or to comply with legal requirements. This information receives special protection in our systems.

## 3. How We Use Your Information

The only purpose of all the requested information is to offer precise and customized service based on the information you decide to provide us. Here''s specifically how we use your information:

### Essential Service Provision

- Processing and confirming your tour bookings
- Arranging necessary permits, accommodations, and transportation
- Communicating important information about your tour
- Providing customer support before, during, and after your journey
- Processing payments and issuing refunds when applicable

### Personalization and Improvement

- Customizing your experience based on your preferences
- Recommending tours and activities that might interest you
- Analyzing how our services are used to improve them

### Communication

- Sending confirmation emails and important updates about your bookings
- Providing information about changes to our services or policies
- Sending newsletters or promotional content (only if you''ve opted in)
- Responding to your inquiries, comments, and requests

### Legal and Safety Purposes

- Complying with legal obligations and regulatory requirements
- Preventing, detecting, and investigating potential fraud or security issues
- Enforcing our terms of service and other policies
- Ensuring the safety and security of our customers, staff, and wildlife

### Lawful Basis for Processing

We process your information based on one or more of the following legal grounds:

- **Contract fulfillment:** When we need your data to provide services you''ve requested
- **Legal obligation:** When we must process your data to comply with laws
- **Legitimate interests:** When it serves our legitimate business interests in ways that don''t override your rights
- **Consent:** When you''ve explicitly agreed to certain types of processing

## 4. Information Sharing

We understand the importance of keeping your information secure and private. **We do not sell your personal information to third parties.** However, we may share your information in the following circumstances:

### Service Providers

We work with trusted third-party service providers who perform services on our behalf, such as:

- **Tour operators and local guides** who help us deliver your ecotour experience
- **Payment processors** who securely handle transactions
- **Transportation providers** including airlines and ground transport companies
- **Accommodation providers** such as lodges, hotels, and campsite operators
- **IT and cloud service providers** who help us maintain our website and systems

These service providers are bound by contractual obligations to keep your information confidential and use it only for the purposes for which we disclose it to them.

### Legal Requirements

We may disclose your information when we believe in good faith that disclosure is necessary to:

- Comply with applicable laws, regulations, or legal processes
- Respond to valid requests from public and governmental authorities
- Enforce our terms and conditions
- Protect our rights, property, or safety, as well as those of our customers

### Business Transfers

If Virunga Ecotours is involved in a merger, acquisition, or sale of assets, your information may be transferred as part of that transaction. We will notify you via email and/or a prominent notice on our website of any change in ownership or uses of your personal information.

### Third-Party Websites

Our website may include embedded content (e.g., videos, images, posts) from other websites like Facebook, Instagram, TripAdvisor, YouTube, or LinkedIn. These websites may collect data about you, use cookies, embed additional tracking, and monitor your interaction with that embedded content. We have no control or responsibility over what happens with third-party websites.

## 5. Your Rights

Depending on your location, you may have certain rights regarding your personal data. We respect these rights and will respond to any requests in accordance with applicable laws:

- **Right to Access:** You have the right to request access to the personal information we hold about you.
- **Right to Rectification:** You can request that we correct any inaccurate or incomplete information about you.
- **Right to Erasure:** In certain circumstances, you can ask us to delete your personal information.
- **Right to Restrict Processing:** You may have the right to request that we limit how we use your data.
- **Right to Data Portability:** You may have the right to receive your personal data in a structured, commonly used format.
- **Right to Object:** You can object to our processing of your personal information in certain circumstances.
- **Right to Withdraw Consent:** If we process your data based on your consent, you can withdraw that consent at any time.

To exercise any of these rights, please contact us using the information provided in the "Contact Us" section. We will respond to your request within the timeframe required by applicable law.

## 6. Data Security

We implement appropriate technical and organizational measures to protect your personal information against unauthorized or unlawful processing, accidental loss, destruction, or damage.

Our security measures include:

- Encryption of sensitive data
- Regular security assessments
- Access controls and authentication procedures
- Staff training on data protection and security
- Secure network architecture and monitoring

While we take reasonable steps to protect your information, no security system is impenetrable, and we cannot guarantee the absolute security of your data. If you have reason to believe that your interaction with us is no longer secure, please notify us immediately.

## 7. Data Retention

We retain your personal information for as long as necessary to fulfill the purposes for which it was collected, including to satisfy legal, accounting, or reporting requirements.

To determine the appropriate retention period, we consider:

- The amount, nature, and sensitivity of the personal data
- The potential risk of harm from unauthorized use or disclosure
- The purposes for which we process the data
- Whether we can achieve those purposes through other means
- Legal, regulatory, and contractual requirements

After the retention period expires, we will securely delete or anonymize your information in accordance with our data retention policies.

## 8. International Data Transfers

As a tour operator with global operations, we may transfer your personal information to countries outside your own. Some of these countries may have different data protection laws than your country of residence.

When we transfer your information internationally, we take appropriate safeguards to ensure that your personal information receives adequate protection, which may include:

- Using standard contractual clauses approved by relevant regulatory authorities
- Transferring to countries that have been deemed to provide an adequate level of protection
- Implementing appropriate technical and organizational measures to protect your information

By using our services, you consent to the transfer of your information to countries outside your country of residence, including to countries that may not provide the same level of data protection as your home country.

## 9. Policy Changes

We may update this Privacy Policy from time to time to reflect changes in our practices, services, or applicable laws and regulations. When we make changes, we will update the "Last Updated" date at the top of this policy.

For significant changes, we will provide notice through our website or by sending you an email notification. We encourage you to review this policy periodically to stay informed about how we are protecting your information.

Your continued use of our services after any changes to this Privacy Policy constitutes your acceptance of the revised policy.

## 10. Contact Us

If you have any questions, concerns, or requests regarding this Privacy Policy or our data practices, please contact us:

### Contact Information

**Email:** privacy@virungaecotours.com
**Phone:** +250 788 123 456
**Address:** Virunga Ecotours, P.O. Box 6754, Kigali, Rwanda

**Data Protection Officer:** For formal inquiries or if you wish to escalate a privacy concern, you can contact our Data Protection Officer at info@virungaecotours.com.

We will respond to your inquiry as soon as possible and within the timeframe required by applicable law.

### Exercise Your Privacy Rights

You have the right to access, correct, delete, or port your personal data. Submit a formal privacy request using our secure online form available on our website.

Last Updated: April 12, 2025
');

-- Insert sample privacy requests for demonstration
INSERT IGNORE INTO privacy_requests (id, request_type, email, subject, message, status, created_at) VALUES
(1, 'data_access', 'john.doe@example.com', 'Request for Personal Data Access', 'I would like to request access to all personal data you have collected about me. Please provide me with a copy of all information you have stored in your systems.', 'pending', '2024-01-15 10:30:00'),
(2, 'data_deletion', 'jane.smith@example.com', 'Request for Data Deletion', 'Please delete all my personal information from your systems. I no longer wish to receive any communications and want all my data removed.', 'in_progress', '2024-01-20 14:45:00'),
(3, 'data_correction', 'bob.wilson@example.com', 'Correction of Personal Information', 'I need to update my contact information in your records. My email address has changed and I need to update my phone number as well.', 'completed', '2024-01-25 09:15:00'),
(4, 'data_portability', 'alice.johnson@example.com', 'Data Portability Request', 'I would like to receive all my personal data in a structured, machine-readable format so I can transfer it to another service provider.', 'pending', '2024-02-01 16:20:00'),
(5, 'data_access', 'mike.brown@example.com', 'GDPR Data Subject Access Request', 'Under GDPR Article 15, I am requesting access to my personal data. Please provide details about what data you process and for what purposes.', 'completed', '2024-02-05 11:00:00');

-- Create audit log table for privacy actions
CREATE TABLE IF NOT EXISTS privacy_audit_log (
    id INT PRIMARY KEY AUTO_INCREMENT,
    admin_id INT NOT NULL,
    action_type ENUM('policy_update', 'request_status_change', 'request_deletion', 'data_export') NOT NULL,
    target_id INT NULL, -- ID of the affected record (request ID, policy ID, etc.)
    details TEXT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_admin_id (admin_id),
    INDEX idx_action_type (action_type),
    INDEX idx_created_at (created_at)
);

-- Create privacy settings table for global privacy configurations
CREATE TABLE IF NOT EXISTS privacy_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    description TEXT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_setting_key (setting_key)
);

-- Insert default privacy settings
INSERT IGNORE INTO privacy_settings (setting_key, setting_value, description) VALUES
('data_retention_period', '7', 'Default data retention period in years'),
('auto_delete_completed_requests', '365', 'Auto-delete completed requests after X days'),
('email_notifications_enabled', '1', 'Enable email notifications for new privacy requests'),
('gdpr_compliance_mode', '1', 'Enable GDPR compliance features'),
('privacy_policy_version', '2.0', 'Current privacy policy version - Updated with comprehensive content from privacy.php'),
('request_response_deadline', '30', 'Days to respond to privacy requests'),
('admin_notification_email', 'admin@virungaecotours.com', 'Email for privacy request notifications');
