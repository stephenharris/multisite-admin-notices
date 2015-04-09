# Multisite Admin Notices #
**Contributors:** stephenharris  
**Donate link:** http://stephenharris.info  
**Tags:** admin notices, alerts  
**Requires at least:** 3.9  
**Tested up to:** 3.9.1  
**Stable tag:** 0.1.2  
**License:** GPLv2 or later  

Allows a network admin to create admin notices for blog admins.

## Description ##

This plug-in will only work on a **Multisite** running **WordPress 3.9+**. 

This plug-in allows an Network Admin to create admin notices that appear on the top of all admin screens of the
network's sites. This notices are styled identically to the "normal" WordPress notices that you might see (WordPress 
updates, "post published" messages etc). Additionally each notice has a "dismiss" link which allows the user to 
instantly dismiss the notice. 

Once a user dismisses a notice there will never again see that notice, on any of the network's site.

**Health Warning:** The notice messages are considered HTML! A "small" inline tinyMCE editor will be used in a
future update. But for the time being be aware that when you create/edit notices you are editing HTML (think WordPress 
'text' tab on the editor).

Before using the support forums, please read the FAQ. Thank you!


## Installation ##

### Manual Installation ###

1. Upload the entire `/multisite-admin-notices` directory to the `/wp-content/plugins/` directory.
2. (Network) activate Multisite Admin Notices through the 'Plugins' menu in your Network dashboard.

## Frequently Asked Questions ##

### How do I add a notice? ###
Go to your **Network Dashboard**, and under *Settings* click *Admin Notices*. Here you can add, remove and update notices. 

### What does it do? ###
Allows a network admin to add, remove or change admin notices for their network's sites. This notices
are dismissable

### Who can see the notices? ###
Anyone how is logged into the admin page of a network site. This may later change to only admins of that
network site. 

Notices can be dismissed by the user, and they shall no longer see that notice (on any site). 

### Can I request a feature?  ###
Yes, but regrettably due to time/work limitations I can't take any *unpaid* on. If you really need a feature, 
you can get in touch to [hire me](http://stephenharris.info).

Pull requests are welcome, but to be merged into the plug-in will need to ensure that it does not disrupt the aesthetics
of the plug-in. I realise that there could hundreds of options that could potenitally be added, but the aim of this
plug-in is to be very simple to use and minimal.


## Screenshots ##

### 1. Creating and managing your admin notices ###
![Creating and managing your admin notices](http://s.wordpress.org/extend/plugins/multisite-admin notices/screenshot-1.png)

### 2. An example of an admin notice ###
![An example of an admin notice](http://s.wordpress.org/extend/plugins/multisite-admin notices/screenshot-2.png)



## Changelog ##

### 0.1.2 ###
* Removed obselete code

### 0.1.1 ###
* Added screenshots and plug-in banner image

### 0.1.0 ###
* First release

## Upgrade Notice ##
