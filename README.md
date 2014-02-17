OMB API
=======

Better structured data for Ontario Municipal Board cases and decisions.

## Features

- Scrape the OMB website and store the data in an SQLite database
- Search for OMB cases by municipality, status, case number, and/or keywords
- Get results as human-readable HTML or JSON

## Installation

This app relies on PHP and SQLite. Throw all the files up on your server, then run `scraper.php` to create and populate the SQLite database. This could take a while, as there are roughly 5000 OMB cases spread across hundreds of pages. You should run `scraper.php` periodically to keep the database up-to-date with the OMB website.

Once the database is set up, the webapp is ready to use. Just navigate to `index.php`.

## To do

- Search results
	- Get results as RSS
	- Add direct URLs to case documents
	- Add more metadata (contact information, last updated date, number of hearings and prehearings...)
- Create "hearings" table
	- Use case ID as a common variable
	- New hearings trigger a new status (e.g. Open -> Prehearing 1 -> Prehearing 2 -> Hearing 1 -> Hearing 2 -> Hearing 3 -> Closed)
- Email notifications
	- One-click email alert setup from search results page
	- User-defined triggers
		- Change in case status
		- New documents available
		- New hearing dates posted
- Scrape text of all case documents for searchability and cross-referencing (pie in the sky idea)