Quafzi PdfDownload Extension
=====================
Download PDF documents
Facts
-----
- [extension on GitHub](https://github.com/quafzi/magento-pdf-download)

Description
-----------
This extension provides a shell script to dump invoice/credit memo pdfs to
`var/export/documents` (which you may run regularly) and provides easy download
access from the backend.

Requirements
------------
- PHP >= 5.5.0
- Mage_Core

Compatibility
-------------
- Magento >= 1.6

Installation Instructions
-------------------------
1. Install the extension via Composer or Modman.
2. Clear the cache, logout from the admin panel and then login again.
3. Add `php shell/export-invoices.php` to your crontab.

Uninstallation
--------------
1. Remove `php shell/export-invoices.php` from your crontab.
2. Remove all extension files from your Magento installation.
3. Clear the cache.

Support
-------
If you have any issues with this extension, open an issue on [GitHub](https://github.com/quafzi/magento-pdf-download/issues).

Contribution
------------
Any contribution is highly appreciated. The best way to contribute code is to open a [pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Developer
---------

[@quafzi](Thomas Birke)

Licence
-------
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)

Copyright
---------
(c) 2017 Thomas Birke
