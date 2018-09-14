<?php

//------------------------------------------------------------------------------
// LANGUAGE FILE
// Edit with care. Make a backup first before making changes.
//
// [1] Apostrophes should be escaped. ie: it\'s christmas.
// [2] Take care when editing arrays as they are spread across multiple lines
//
// If you make a mistake and you see a parse error, revert to backup file
//------------------------------------------------------------------------------

// General..
$pblang = array(
  'Buy Our Music Online',
  'Home',
  'Latest',
  'Popular',
  'Specials',
  'Search our music store',
  'My Account',
  'Basket: <span class="basket-count-items">{count}</span> Item(s)',
  'Toggle Menu',
  'Music Styles',
  'Other',
  'Powered by',
  'All Rights Reserved',
  'Account Menu',
  'Search Results',
  'Shopping Basket',
  'Total: {cost}',
  'Featured'
);

// Collections..
$pbcatlang = array(
  'Info &amp; Track Purchase',
  'MP3',
  'CD',
  'Item Added to Basket',
  'View Basket &amp; Checkout',
  '{count} item(s) @ {total}',
  'MP3 - Digital Download: <b class="trackHighlight">{track}</b> - {cost}',
  'Styles',
  'Release Date',
  'Cat. No',
  'Title',
  'Time',
  'Add Selected Track(s) to Basket',
  'Cost',
  'Bitrate',
  'Comments',
  'Related Products',
  'Search Tags',
  'Viewed {counter} times',
  '<i class="fa fa-ban fa-fw mm_red font_size_big"></i><span class="nothing">CD Purchase &amp; CD Download Not Available</span>',
  'Currently Out of Stock',
  'Collection Purchase ONLY',
  '<i class="fa fa-download fa-fw"></i> MP3 - Digital Download - {count} Tracks',
  'MP3 - Digital Download: <b class="trackHighlight">{track}</b> - {cost}',
  '<i class="fa fa-truck fa-fw"></i> CD - Shipped - {count} Tracks',
  'MP3 (BUY ALL)',
  'BUY CD'
);

// Account..
$pbaccount = array(
  'Account Menu',
  'Profile',
  'Orders',
  'Logout',
  'Account Profile',
  'Music Orders',
  'View Order',
  'Account Login',
  'Create Account',
  'Email',
  'Password',
  'Login',
  'Forgot Password?',
  'Reset Password',
  'Password Reset',
  'If you entered a valid email address and your account is enabled, your account password has been reset and your new password has been emailed to you.<br><br>Please check your inbox at:<br><b>{email}</b><br><br>If the expected email doesn`t arrive within a few minutes, you should check your mail spam folder as it may be there. Thank you.',
  'Dashboard',
  'Latest Orders',
  'Welcome to your account area. Your latest orders are shown below. Please keep your <a href="{url}">profile</a> up to date.',
  'View All',
  '<b>NOTICE!</b> Your account has not been verified. Please click <a href="#" onclick="mm_verifyResend();return false">here</a> to resend email<br><br>Note that certain areas are not accessible until your account is verified.',
  'Verification Email',
  'The verification email has been resent as requested.<br><br>Please check your inbox at:<br><b>{email}</b><br><br>If the expected email doesn`t arrive within a few minutes, you should check your mail spam folder as it may be there. Thank you.'
);

// Account profile
// Create account
$pbprofile = array(
  'Name',
  'Email',
  'Timezone',
  'Password',
  'Retype Password',
  'Default Shipping Address (for CDs ONLY)',
  'Address 1',
  'Address 2 (Optional)',
  'City',
  'Region/State/County',
  'Post/Zip Code',
  'Country of Residence',
  'Update Profile',
  'Shipping Rate',
  'Free',
  'Please enter name..',
  'Please enter valid email address..',
  'Email already exists, please choose another..',
  'Invalid timezone..',
  'Passwords do not match, please try again..',
  'Password too short, min {min} characters..',
  'Profile Updated',
  'Your profile was successfully updated, thank you',
  'Please enter password, min {min} characters..',
  'Account Created',
  'Your profile was successfully created, thank you.<br><br>Before you can log in, you must verify your account.<br><br>Please check your inbox at:<br><b>{email}</b>',
  'Account Verification',
  'Thank you, your account has successfully been verified.<br><br>Please wait..',
  'Default Shipping Rate (Optional - For Shipped CDs ONLY)',
  'Country'
);

// Basket
$pbbasket = array(
  'Item Name',
  'Sub Total',
  'Total',
  'Items in Basket',
  'Next',
  'There are currently no items in your basket',
  'Account Login',
  'Back',
  'Shipping Address / Rate',
  'Total / Payment Method / Notes',
  'Checkout &amp; Pay',
  'Please choose your preferred payment method',
  'Shipping',
  'Tax ( <a href="#" onclick="mm_taxInfo();return false"><i class="fa fa-info" title="View Tax Calculation"></i></a> )',
  'Total',
  'If you don`t have an account, please enter your preferred email and password to have one created for you.',
  'Please enter valid email and password..',
  'Please enter your name..',
  'Enter email address..',
  'Enter password..',
  'Enter name (new accounts only)..',
  'Please complete shipping fields..',
  'Additional Notes (Optional)',
  'Gift Coupon',
  'If you have a gift coupon code, please enter it below',
  'Invalid coupon code, please try again',
  'Error. Coupon code expired on: {expired}',
  'Coupon Discount',
  'Our store requires a min purchase of {min} before checkout can proceed<br><br>Please add more items to your basket.',
  'Please specify your country of residence (new accounts only)..',
  'Please specify your country of residence, this is required for Tax purposes and must be correct..',
  'Tax Information',
  'Check Box to Accept Our Terms &amp; Conditions',
  'Clear All'
);

// Orders..
$pborders = array(
  'Order Cancelled',
  'Your order was cancelled and no payment has been sent.<br><br>Thank you for your interest in our music.',
  'Checking Order',
  'Invoice No',
  'Date',
  'Payment Method',
  'Total',
  'Return to Orders',
  'Product Name',
  'Approx. Size',
  'Download',
  'Digital Downloads - Available for immediate download',
  'CD - The following items will be shipped to you',
  'Cost',
  'Full CD - All Tracks',
  'Track: {name} ({length}, {rate})',
  'Full CD - All Tracks ({length}, {rate})',
  'Sale Date',
  'Sub Total',
  'Shipping',
  'Tangible Goods Tax @ {tax}%: {amount}<span class="taxMarker">{count} Item(s) &amp; Shipping (if applicable)</span>',
  'Total',
  'Order shipping address (if applicable)',
  'Paid with thanks',
  'Discount',
  'Preparing download...please wait..',
  'Digital Goods Tax @ {tax}%: {amount}<span class="taxMarker">{count} Item(s)</span>',
);

// Downloads..
$pbdownloads = array(
  'Downloads are restricted to the same IP as the sale<br><br>Please contact us for assistance.',
  'This download has now expired.<br><br>Please contact us to have it reset',
  'Download links can be clicked a maximum of {max} times.<br><br>Please contact us to have it reset',
  'Sales access locked. Download clicks detected from too many IP addresses.<br><br>Please contact us for assistance.',
  'Sales access locked. Download clicks detected from too many IP addresses.',
  'Track Downloaded',
  'Collection Downloaded'
);

// Contact Form
$pbcontact = array(
  'Please complete the fields below to send us a message, thank you',
  'Name',
  'Email Address',
  'Subject',
  'Comments',
  'Send Message',
  'Message Sent',
  'We will try and reply to your enquiry as soon as possible',
  'All fields must be completed'
);

// RSS..
$pbrss = array(
  'Featured Collections',
  'Latest Collections',
  'Popular Collections',
  'Collections in "{style}"',
  '[MP3 - Digital Download]&nbsp;&nbsp;<b>{cost}</b>',
  '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[CD]&nbsp;&nbsp;<b>{cost}</b>'
);

// Global frontend..
$pbglobalfront = array(
  'Order By',
  'Title (A-Z)',
  'Title (Z-A)',
  'MP3 Price (High to Low)',
  'MP3 Price (Low to High)',
  'CD Price (High to Low)',
  'CD Price (Low to High)',
  'Release Date (Oldest)',
  'Release Date (Newest)',
  'Date Added (Oldest)',
  'Date Added (Newest)'
);

?>