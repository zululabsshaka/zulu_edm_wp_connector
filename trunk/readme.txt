=== CF7 Zulu eDM Connector ===
Contributors: Zulu Labs, developer of Zulu eDM
Author URL: http://www.zululabs.com/
Tags: Zulu eDM, email service provider,email API cf7, contact form 7, Contact Form 7 Integrations, contact forms, Zulu eDM Integrations
Requires at least: 4.6
Tested up to: 4.8
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This addition to Contact Form 7

== Description ==

This plugin is a bridge between your [WordPress](https://wordpress.org/) [Contact Form 7](https://wordpress.org/plugin/contact-form-7/) and [Zulu eDM](https://www.zuluedm.com).

Want to intergrate your Contact Form 7  web forms with Zulu eDM,then install this light plug in and you will be set.

= How to Use this Plugin =

*In Zulu eDM*  
*Get your API Key from your Zulu eDM Account homescreen. 

*In WordPress Admin*  
* Install this plugin only if Contact Form 7 is install prior (pre-existing). Create or Edit the Contact Form 7 form from which you want to capture the data. Set up the form as usual in the Form and Mail etc tabs. Thereafter, go to the new "Zulu eDM" tab.  
* On the "Zulu eDM" tab, copy-paste the Zulu eDM sheet name and tab name into respective positions, and hit "Save".

*In Zulu eDM*  
* In the Zulu eDM tab, provide column names in row 1. The first column should be "date". For each further column, copy paste mail tags from the Contact Form 7 form (e.g. "your-name", "your-email", "your-subject", "your-message", etc).  
* Test your form submit and verify that the data shows up in your Zulu eDM.

= Important Notes = 

* firstname:text-52 lastname:text-744 email: email:email-706 mobilephone:email-706 subscribed:acceptance-281

* You must pay very careful attention to your naming. 
*Here is a sample for you to work off (see screen shots as well) 

== Installation ==

1. Upload `cf7-zulu-edm-connector` to the `/wp-content/plugins/` directory, OR `Site Admin > Plugins > New > Search > CF7 Zulu eDM Connector > Install`.  
2. Activate the plugin through the 'Plugins' screen in WordPress.  

Enjoy!

== Screenshots ==

1. Check each screen shot with the example code to ensure alignment

== Frequently Asked Questions ==

= Why isn't the data submitting to my Zulu eDM account? 

1. Wrong API Key ( Check debug log )
2. Wrong Username or password
3. Wrong Column name mapping ( Column names are the contact form mail-tags. Please refer to Zulu eDM fields specified or contact the support team )

Please double-check those items and hopefully getting them right will fix the issue.

== Changelog ==

= 1.0 =
* First public release
* Integrated Contact form 7 with Zulu eDM 

= 1.1 =
* Checked compatibility with WordPress 4.8 
* Updated imagery and screenshots
* Provided a sample 
* Corrected grammar etc
* Integrated Contact form 7 with Zulu eDM 


