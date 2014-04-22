<?php
// Set the last run time of the corrected orders feed to the time when the
// extension is installed. This should help to reduce the number of untracked
// orders included in the initial run of the feed.
Mage::helper('eems_affiliate/config')->updateOrderLastRunTime();
