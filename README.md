qa-publish-to-email
===================

Question2Answer plugin to email posts to configured emails.

Installation
------------

Copy or clone this repository inside the ``qa-plugins`` folder and that's it!

Features
--------

- Select which types of posts (questions, answers, comments) have emails sent
- Multiple notification email addresses
- Emails' "From" fields contain original poster, while Sender is the admin
  email address
- Bcc to notification addresses instead of using To
- Configurable prefix in email subject
- Plain-text or HTML emails
- Customized styles for HTML emails, using deployed themes or custom CSS files
- Include content of other posts in the emails (e.g. answers can include the
  original question; comments can include their parent and the parent question)

More to come!

Configuration
-------------

All configuration is through the plugin's Admin form. Custom CSS stylesheets,
if used, must be copied into the plugin's ``custom-styles`` folder (see
[README](custom-styles/README)).

