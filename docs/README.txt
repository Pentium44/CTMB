/////
///CTMB - Crazy Tiny Message Board
/////

CTMB is a simple, small, and light message board that uses flatfile format as 
the database, and as for the posts. CTMB is release under the CC-BY-NC 3.0, and
does not include ANY KIND OF WARRANTY. CTMB was created by:
	* Chris Dorman, 2012-2013 <cddo@riseup.net>
	
////	
/// Installing CTMB
////
To install CTMB do these steps in order
1) Extract the CTMB archive to the root directory of your web server.
2) chmod 777 the db directory, and everything in it.
3) Visit the board using a web browser and complete the install form. 

After these steps are complete, go to: http://<yourdomain>/ctmb-<version>/
Here your CTMB will be setup, and ready to use! If you have any trouble, email
me at <cddo@riseup.net>

Thanks, Chris

////
/// Change Log
////

2.72--
* Fixed some bugs

2.70--
* Added user signatures (Modifible from ucp) (Thanks Colin M.!)
* Default theme only
* Removed User IP's
* Added post deleting feature for admin's
* Modified default theme with new css3 features
* PHP error fixes for Windows hosting (Runs smoothly on Windows)
* Added user post dates, and post numbers
* Added jQuery for user rank updating, and for signatures
* Fixed spoiler support in bbcode, now uses javascript

2.50--
* Added User Control Panel
* Theme tweaks
* avatar.php for avatar redirect only
* Updated "last post by" so replies are listed too
* User pages (Linked from userlist)
* User post recorded
* User colors used for "last post by"

2.47--
* Added categories!
* Added category descriptions
* Fixed remove users function
* Added Chris's Purple Theme
* Added User color, and logo changing in admin panel
* Fixed posting errors

2.13--
* Fixed login bug -- Logins work

2.12--
* Fixed Registeration Bugs
* Added Avatar updating (same avatar for all posts)
* added CTMB Green theme

2.10--
* Added Sessions (Logging in, and out)
* Fixed Admin Panel Exploits
* Fixed Bug that allowed multiple users to be created

1.99--
* Added multi admin support
* User logo, and name colors (based on user rank)
* Fixed admin panel exploit
* Added Key for user name colors
* Added bbcode help page
* Added install script (easier, and faster)
* Added BBCode Image Thumbnail (Auto margin, and width)
* Fixed Typo on Avatar Upload 

1.76--
* Added Header/Footer Support
* Avatar Support for Upload User only (No more global choosing)
* Updated HTML
* New Default theme (Less ugly)
* Logging

1.59--
* Added time and date stamps for posts
* Fixed Redirect bug for new topics
* Cleaned the code up for topic.php

1.44b--
* Fixed small typo's
* Cleaned up code

1.44--
* Theme support
* Fixed validation bug
* Modified HTML Code

1.37 --
* Added New Fonts (Sans-Serif as backup)
* Added global avatar support
* Added User IP posting (There IP is posted on there posts, and replies)
* Added post title's at the top of the posts while being viewed
* Tweaked the HTML/CSS (Added tables for posts)

1.25 --
* Added post validation
* Added nl2br to post and reply
* tweaked CSS, and HTML

1.12 --
* Added User Support (With Password Encryption)
* Admin Panel for user removal - One Admin support only
* Fixed some post Bugs from the original script
* Customized the CSS tables
* Added User List

1.00 -- 
* Initial Release
* In Working Condition - Nothing Special

////
/// Ideas for the future
////

* More jQuery usage
* Better IE support
* Compessing flatfile database (More info in one file)
