# Pepperjam Network for Magento 1

Launching an affiliate marketing program has never been easier. With Pepperjam Network Extension, you can start driving traffic and revenue right now. The extension empowers merchants with:

- A step by step integration and onboarding process
- Ability to track and record affiliate sales and conversions
- Flexible configuration
- Product feed export automated nightly in Pepperjam Network format
- Correction feed automated nightly in Pepperjam Network format

## Installation

**via modman**

Install modman 

    bash < <(wget -q --no-check-certificate -O - https://raw.github.com/colinmollenhour/modman/master/modman-installer)
    cd magento_root
    ~/bin/modman init

Install the module

    ~/bin/modman clone https://github.com/pepperjam/ascend-magento1-extension.git

Magento should be configured with dev/template/allow_symlink = 1 in core_config_data DB table.

**via composer**

Create composer.json file

    {
        "require":{
               "pepperjam/network-magento1-module": "master@dev"
        },
        "repositories":[         
    	{
                "type":"git",
                "url":"git@github.com:pepperjam/ascend-magento1-extension.git"
            }
        ],
        "extra": {
            "magento-root-dir": ".",
            "with-bootstrap-patch": false
        }
    }

Run installation
    
    composer install --no-dev

Magento 1 composer installs requires app/Mage.php to be patches, and this is done by included module magento-hackathon/magento-composer-installer 
    
**via file transfer**

Download the package from releases tab https://github.com/pepperjam/ascend-magento1-extension/releases   

## How to get started

1. Install the extension
1. Under System Configuration, you’ll find Pepperjam Network on the left hand navigation pane. Click to configure the extension.
1. Select “Yes” to enable Affiliate tracking.
1. Insert your unique Program ID (PID) into the extension. If you do not yet have a PID, click on the “Register Now” link to register for an account.
1. Select “Dynamic” for tracking type, unless otherwise instructed.
1. Set Enable Container Tag = Yes and fill in Tag Identifier field.
1. Set export path to a directory accessible via FTP.
1. Place test transaction to confirm installation.

