=== Easy SMTP Mail ===
Contributors:priyanshu.mittal
Tags:email, gmail, mail, mail smtp, outgoing mail, phpmailer, privacy, security, sendmail, smtp, ssl, tls, wordpress smtp, wp smtp, wp-phpmailer, wp_mail
Requires at least: 3.0
Tested up to: 4.2.2
Stable tag: 1.5.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easy SMTP Plugin help you to send Emails from your WordPress Blog using your preferred SMTP Server. 

== Description ==

Webriti SMTP Mail can help us to send emails via SMTP instead of the PHP mail() function. It adds a settings page to "Dashboard"->"Settings"->"Webriti SMTP Email" where you can configure the email settings.

This plugin reconfigure's the wp_mail() function to send SMTP Mails. But you have to make sure that leaving Smtp Host name blank will not reconfigure the WordPress wp_mail() function.

The Practical use of this plugins comes when theme have a contact page, which on submit, shows you the successful message, but, still admin did not receive any email, here comes this smtp plugin which will makes your contact form workable. = Make sure that you are using the php version 5.6.0 plus. =
With the help of this you can make themes contact form or widget workable.

**Webriti Smtp Mail Plugin has following settings.**

###General Settings

The general settings section consists of the following options

1. From Email Address: The email address that was sending emails to users.
2. From Name: Specify your name which you want to show in the Email Headers.
3. SMTP Host: Your outgoing mail server (example: smtp.gmail.com)
4. SMTP Port: The port that will be used to relay outbound mail to your mail server (example: 465).In case of TLS encryption use port 587 for gmail server.
5. SMTP Authentication: Yes
6. Encryption : SSL / TLS. 
6. Username: Enter your mail server username.
7. Password: Enter password you use to login to the mail server.

###Test Mail Utility
With this utility you can always check weither you have configured your SMTP Mail Server details correctly or not. 



== Installation ==
1. Download
2. Upload to your `/wp-contents/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress and Enjoy.

== Screenshots ==

1. Configure SMTP Settings.
2. Help and support.


== Frequently Asked Questions ==

= How to use gmail  as your smtp server in SSL encryption mode? = 

For  using Gmail Smtp Server with this plugin then set the following details

* Smtp Host smtp.gmail.com
* Smtp Port usually 465.
* Username: Your gmail username.
* Password: Your gmail password.
* Encryption : SSL

= How to use gmail in TLS mode? = 

For  using Gmail Smtp Server with this plugin then set the following details

* Smtp Host smtp.gmail.com
* Smtp Port usually 587.
* Username: Your gmail username.
* Password: Your gmail password.
* Encryption : TLS

= What is the difference between SSL and TLS types of encryption's? = 

TLS (Transport Layer Security) and SSL (Secure Sockets Layer) are protocols that provide data encryption and authentication between applications and servers in scenarios where that data is being sent across an insecure network, such as checking your email. 
The terms SSL and TLS are often used interchangeably or in conjunction with each other (TLS/SSL), but one is in fact the predecessor of the other — SSL 3.0 served as the basis for TLS 1.0 which, as a result, is sometimes referred to as SSL 3.1.

= Which is more secure – SSL or TLS? =

It used to be believed that TLS v1.0 was marginally more secure than SSL v3.0, its predecessor.  
However, SSL v3.0 is getting very old and recent developments, such as the POODLE vulnerability have shown that SSL v3.0 is now completely insecure (especially for web sites using it).
[Read More](https://luxsci.com/blog/ssl-versus-tls-whats-the-difference.html "ssl vs tls").


== Changelog ==

@Version 1.5.4
1. Recommended soem more plugins.

@Version 1.5.3
remove debug mode errros.
= 1.5.1 =
Typo fixed.
= 1.5 = 
1. Option Panel revamped.
2. Added SMTP common error codes and suggestions.

= 1.4 =
1. Added TLS encryption mode.

= 1.3 =
1. Reply to field is now corrected
2. Removed Filters
= 1.2 =
* SSL Turn off/on option added in option settings

= 1.1 =
* Small bug fixed.

= 1.0.4 =
* New Tab adding for Free wordPress Themes.

= 1.0.3 =
* Formatting not fixed in 1.0.2 is now set.

= 1.0.2 =
* Proper formatting of readme.txt file is done.

= 1.0.1 =
* Added Desctipin Text.

= 1.0 =
* Easy Smtp Mail Plugin Released

== Upgrade Notice ==
Always try to use latest version on repository.