# Pepperjam Network for Magento 1

Launching an affiliate marketing program has never been easier. With Pepperjam Network Extension, you can start driving traffic and revenue right now. The extension empowers merchants with:

- A step by step integration and onboarding process
- Ability to track and record affiliate sales and conversions
- Flexible configuration
- Product feed export automated nightly in Pepperjam Network format
- Correction feed automated nightly in Pepperjam Network format

## Installation

**A) via modman**

Install modman 

    bash < <(wget -q --no-check-certificate -O - https://raw.github.com/colinmollenhour/modman/master/modman-installer)
    cd magento_root
    ~/bin/modman init

install the module

    ~/bin/modman clone https://github.com/pepperjam/ascend-magento1-extension.git

Clear cahe. 

Magento should be configured with dev/template/allow_symlink = 1 in core_config_data DB table.

Upgrades are done via modman and clear cache:
    
    ~/bin/modman update ascend-magento1-extension

**B) via composer**

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

run installation
    
    composer install --no-dev

Clear cahe. 

Magento 1 composer installs requires app/Mage.php to be patches, and this is done by included module magento-hackathon/magento-composer-installer, as done in composer.json file.

Upgrades are done by specifying the version and clear cache, for example:

    composer require pepperjam/network-magento1-module:1.3.4 --update-no-dev
    
    
**C) via file transfer**

Download the package from releases tab https://github.com/pepperjam/ascend-magento1-extension/releases
Transfer the files and clear cache.

Upgrades are done by replacing the files and clearing cache.    

## How to get started

1. Install the extension
1. Under System Configuration, you’ll find Pepperjam Network on the left hand navigation pane. Click to configure the extension.
1. Select “Yes” to enable Affiliate tracking.
1. Insert your unique Program ID (PID) into the extension config. Once you login to advertiser interface, PID can be found in the upper right corner.
1. Select “Dynamic” for tracking type, unless otherwise instructed.
1. Set Enable Container Tag = Yes and fill in Tag Identifier field. Tag identifier can be found in the advertiser interface by navigating to Resources > Tracking Integration > Tag Container.
1. Set export path to a directory accessible via FTP.
1. Place test transaction to confirm installation.

