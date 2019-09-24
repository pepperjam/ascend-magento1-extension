# Pepperjam Network for Magento 1

Launching an affiliate marketing program has never been easier. With Pepperjam Network Extension, you can start driving traffic and revenue right now. The extension empowers merchants with:

- A step by step integration and onboarding process
- Ability to track and record affiliate sales and conversions
- Flexible configuration
- Product feed export automated nightly in Pepperjam Network format
- Correction feed automated nightly in Pepperjam Network format

## Installation

**via modman**

`modman clone https://github.com/pepperjam/ascend-magento1-extension.git`

**via composer**

Add the repository to your composer.json file

    "repositories":[
      {
        "type":"vcs",
        "url":"https://github.com/pepperjam/ascend-magento1-extension.git"
      }
    ],

`composer require pepperjam/network-magento1-module`
    
**via file transfer**

Download the package from releases tab https://github.com/pepperjam/ascend-magento1-extension/releases   

## How to get started

1. Install the extension
1. Under System Configuration, you’ll find Pepperjam Network on the left hand navigation pane. Click to configure the extension.
1. Select “Yes” to enable Affiliate tracking.
1. Insert your unique Program ID (PID) into the extension. If you do not yet have a PID, click on the “Register Now” link to register for an account.
1. Select “Dynamic” for tracking type, unless otherwise instructed.
1. Set export path to a directory accessible via FTP.
1. Place test transaction to confirm installation.

