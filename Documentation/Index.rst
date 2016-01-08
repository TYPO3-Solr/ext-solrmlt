.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: Includes.txt


.. _start:

======================================
Apache Solr for TYPO3 - More Like This
======================================

.. only:: html

	:Classification:
		solr

	:Version:
		|release|

	:Language:
		en

	:Description:
		Apache Solr for TYPO3 - More like this allows you to show related documents in your content retrieved from solr.

	:Keywords:
		search, full text, index, solr, lucene, fast, query, results, grouping, field group, query group

	:Copyright:
		2009-2015

	:Author:
		Ingo Renner & Timo Schmidt

	:Email:
		ingo@typo3.org
		timo.schmidt@dkd.de

	:License:
		This document is published under the Open Content License
		available from http://www.opencontent.org/opl.shtml

	:Rendered:
		|today|

	The content of this document is related to TYPO3,
	a GNU/GPL CMS/Framework available from `typo3.org <http://typo3.org/>`_.


What does it do?
================

Solr more like this can be used a a plugin on the page to show content that is related to this page and stored in
you solr server.

Before you start
================

Make sure your solr extension is configured to index everything you need

* EXT:solr is installed
* TypoScript template is included and solr endpoint is configured
* TYPO3 domain record exists
* Solr sites are initialized through "Initialize Solr connections"
* Solr checks in the reports module are green

If you run into any issues with setting up the base EXT:solr extension, please
consult the `documentation <https://forge.typo3.org/projects/extension-solr/wiki>`_.
Also please don't hesitate to ask for help on the
`TYPO3 Solr Slack channel <https://typo3.slack.com/messages/ext-solr/>`_

How to configure
================

The following steps are needed to configure "more like this":

* Install solrmlt
* Include the TypoScript Template "Apache Solr - More Like This" shipped with this extension
* Include the Plugin "Search: More Like This" on a page where you want to display solr results like the from the current page.
