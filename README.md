OMB API
=======

Better structured data for Ontario Municipal Board cases and decisions.

## Features

- View all OMB cases for one or more municipalities

## Installation

This app relies on PHP and SQLite. Throw all the files up on your server, then run `scraper.php` to create and populate the SQLite database. This could take a while, as there are roughly 5000 OMB cases spread across hundreds of pages. You should run `scraper.php` periodically to keep the database up-to-date with the OMB website.

Once the database is set up, the webapp is ready to use. Just navigate to `index.php`.

## To do

- Search results
	- Request and return data as JSON
	- Direct link to case documents and metadata
- Search options
	- Filter open vs. closed cases
	- Add specific case numbers to feed
- Email notifications
	- Change in case status
	- New documents available
	- New hearing dates posted