=== UpiCRM – Universal WordPress CRM and Lead Management ===
Contributors: upi
Donate link: http://www.upicrm.com
Tags: CRM, WORDPRESS, Lead Management, UPICRM, UTM tagging, HTTP referrer
Requires at least: 3.0.1
Tested up to: 4.3
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.upicrm.com/wp-content/uploads/2015/03/license.txt

UpiCRM – Universal WordPress CRM and Lead Management

== Description ==

INTRODUCTION - WHAT IS UPICRM AND HOW DOES IT WORK?
UpiCRM is the simplest, most elegant and easy to use CRM solution and lead management solution, designed for WordPress users.

UpiCRM is designed to easily integrate with your WordPress website. It works in parallel with existing solutions you are currently using, such as existing contact forms (Contact Form7, Gravity Form, and any other contact forms you are currently using (future version – contact us), in order to collect and mange leads and customers from your website.

UpiCRM method of operation is as follows:
Elegant, non-intrusive, easy to set-up: 
UpiCRM maps your existing database/forms of leads from your current website, by using an advanced yet elegantly simple, semi-automatic mechanism, in order to create a new single, central, unified database of all your leads, while importing all past data from contact forms databases (if existing), and all new leads generated during the configuration mapping process. 

Complete Customer Lifecycle Management: 
UpiCRM unified leads database unfolds new capabilities for managing the lifecycle of all leads, from receiving a new lead and routing it to the designated person, through managing the process required per each lead, and up to the closing of the process with each lead as recorded by yourself. 

Teamwork & Collaboration: 
UpiCRM provides the means for the most efficient teamwork, by allowing new leads to be assigned to any designated function of your team – sales, marketing, executives, and service providers. 

Analytics, Monitoring and tracking: 
UpiCRM offers advanced capabilities of analytics and effectiveness analysis, by attaching the traffic source to each and every lead, as well as utilizing the URL tagging mechanism, in order to allow you to gather, report, and analyze the marketing activities you manage, starting right from the investment in a marketing channel. 

Dashboards and KPI's: 
Gain instant knowledge of your current funnel and situation with the UpiCRM dashboard. 
No more endless spreadsheets, manual reports or confused and angry managers. Simply provide your executive team with an easy access to the real-time, easy to understand UpiCRM dashboard.



== Installation ==


Upload `plugin-name.php` to the `/wp-content/plugins/` directory
Activate the plugin through the 'Plugins' menu in WordPress

FIRST TIME INSTALLATION AND CONFIGURATION
After installing the plugin from upicrm.com website, or from "plugins" page on your WordPress site, you will need to perform the following actions before you can enjoy the capabilities of UpiCRM.

First Step: Set up users and roles.

UpiCRM has two built in user roles: 

UpiCRM Admin: Can configure all options, grant access rights, view and edit all leads, access UpiCRM dashboard, and perform any additional task UpiCRM allows.

UpiCRM User: Can view & manage leads assigned to him only.

Simply create a WordPress user using the default "Users" menu provided by your WordPress management interface. Navigate to the lower end of the page, and assign the new user with the desired UpiCRM role:

Note: if you have an existing WordPress user, you can simply assign user roles using the same screen above. 

UpiCRM General Settings 

"Send all leads and updates to the following user": you may choose to distribute new leads by mail to any external eMail address, or to a list of multiple comma separated (",") eMail addresses.

"By default, Leads are assigned to" : use this option in order to determine who will be assigned to deal with all new leads. 
 
"Email format":  you can choose to send mail notifications as plain text or HTML. As some external systems (SalesForce, ZohoCRM etc) require plain text in order to parse new leads, you may choose to change default format to plain text. 

Change default "from" field for emails sent from UpiCRM: use this option in order to set the "from" name used on email that will be sent by UpiCRM. 


Step 2: map all your existing forms and fields onto UpiCRM's structured database
UpiCRM needs to import all your old data and map all your current forms and fields onto its superset of structured database. 
In order to perform this task, navigate to the "General Settings" screen on the UpiCRM sub-menu.

Please remember to perform this task again if you're adding more new forms to your site in the future!

Use the dropdown menus on this screen in order to map ALL your current forms fields onto the UpiCRM structured database.

Tip: if you are using an additional field that does not appear on the UpiCRM predefined fields list, you can always add it to the UpiCRM database using the "Add additional fields and datatypes to UpiCRM" option.

Note: this procedure needs to be performed only once per every form on your website.
UpiCRM Lead status:
Use this screen in order to add or change a status.



Step 3: Edit eMail notifications 
UpiCRM will consistently inform you and your team about any reception/edition/modification of any lead. 
Take a couple of minutes in order to personalize the messages you wish to distribute, by editing the eMail templates on the "eMail Notifications" screen:

Tip: use the variable [lead],  [url] and [assign-to] in order to embed information with the notifications you send. 
In the future, UpiCRM will provide even more optional variables.


== Frequently Asked Questions ==

= what does UpiCRM do? =

UpiCRM is a WordPress CRM and lead management plugin that enables you to manage your customers throughout the complete life cycle of the “leads to customers” process.

= CRM solutions are known to be very hard to implement… how easy is UpiCRM, starting with installation, set-up and work cycle? =

CRM solutions are known to be very hard to implement… how easy is UpiCRM, starting with installation, set-up and work cycle?

= with which forms plugins does UpiCRM work currently? = 

currently we support Contact form 7 + Contact form DB , as well as Gravity forms. If you are using another solution, please contact us on www.upicrm.com/contact, and we'll be happy to promptly respond to your needs and those of the community. 

= is UpiCRM free? = 
Yes. Please read the license file on the installation ZIP.

= How can I track lead source in UpiCRM ? =

UpiCRM provides 3 ways of tracking  a lead source: 1) Form name – which form was filled?  2) traffic source – Referral – from which site/URL did the user arrive from? 2) UTM URL Tagging : add UTM tags to all campaigns and traffic sources for your site, UpiCRM will parse and attached this information to every new lead. 

== Screenshots ==

1. UpiCRM Options
2. UpiCRM Lead Management
3. UpiCRM Map existing forms fields to UPiCRM stuctured database field
4. UpicCRM Status Management
5. UpcCRM email template for lead management
6. UpiCRM New Lead
7. UpiCRM Main Table with export to excel option
8. UpiCRM Main Table in row editor
9. UpiCRM Dashboard

== Changelog ==

= 1.6.2 =
* Fixed installation

== Upgrade Notice ==

= 1.6.2 =
be sure to backup your site before upgrading. 

== Arbitrary section ==

ANALYSIS, TRACKING, MARKETING EFFECTIVENES WITH UPICRM
The Challenge: Tracking leads from actual traffic source – campaigns, referring sites, search engines etc., through the complete lifecycle of every lead, and creating the full picture about your marketing effectiveness.


UpiCRM supports URL tagging used for identifying traffic sources to a web site. 
Note: Please read more about URL Builder provided by Google. (link)
Basically what this means is that if you tag all your inbound traffic in compliance with the UTM tagging rules, UpiCRM will attach the traffic source to each and every lead received. 

In addition, UpiCRM will also attach the HTTP referrer parameter to every lead, so you'll be able to track the source per every lead in your database. 
On the bigger picture: With UpiCRM you will be able to analyze the effectiveness of all your marketing activities and traffic sources, both on a single lead basis, and up to a complete reporting and analytics information. 

The way to achieve this is simply by adding the built-in fields  - ‘HTTP referrer’, ‘Campaign Source’ (utm_source), ‘Campaign Medium’ (utm_medium), ‘Campaign Term’ (utm_term) , ‘Campaign Content’ (utm_content), ‘Campaign Name’ (utm_campaign) - to the default "View leads" table.
Alternatively, simply export all data to excel in order to create your own views and analysis. 

