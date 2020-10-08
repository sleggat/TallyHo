# TallyHo
> A single-user time tracker, built for simplicity, speed and portability.

![Preview](https://raw.githubusercontent.com/sleggat/TallyHo/master/screenshot.jpg)


## Features:
- Flat file (text/Yaml) ‘database’. Makes backups/syncing data super easy.
- Add a New task, or continue a previous task. New Clients/Projects created on-the-fly.
- Edit tasks (double-click, or select via dropdown)
- Duplicate / Restart tasks
- Filter by Client and/or Project
- Delete tasks
- Select rows/days and calculate total costs
- Add Reimbursable expenses (e.g. printing costs, client's domain name, 'research' trip to Bali)
- Different hourly rates; Default Rate / Client Rate / Project Rate
- Visual cue showing time spent on each task (blue = meh / red = good effort)
- Selecting individual rows and/or click the date headers to get a tally of Hours / Cost

#### Features I'd like to add at some point;
- Filtering by date range
- Basic Reports/Charts (Historical and Projection)
- Basic exporting to CSV


#### TallyHo is simple by design and I have no intention of adding the following;
- Invoicing
- Multi-users
- Multi-currency
- Login/Pass


## Installation:

I recommend putting it on your localhost, but you could also upload it to your webserver.
Configure default hourly rate, timezone and other stuff here: guts/config.php


## Usage:

Unzip and put the 'tallyho' in your '~/Sites' folder or wherever localhost points to, then just open 'http://localhost/tallyho' up in your web browser. Upon initiation it will create some demo tasks which you can edit/duplicate/delete.

Note: Tested and working on PHP v5.6 and PHP v7.2


## Flat-file structure

TallyHo uses flat-files (.txt/.yaml) instead of a database.

Why flat-files instead of a MySQL database? I much prefer being able to copy/backup and manually edit a bunch of text files rather than having to deal with a MySQL database. Keeps things simple.


A typical file structure;
```
/tallyho/data/ClientO/ProjectX/20191009-1230.txt
/tallyho/data/SupertouchX/Website2020/20201010-1645.txt
```


If you'd like varying hourly rates for each client and project, just add a \_info.yaml in the folder. 

E.g.

```
/tallyho/data/ClientO/ProjectX/_info.yaml
```
and add an hourly rate like so:
```
Rate: 75
```
The heirarchy is Project>Client>Default


## The Backstory:

Over the course of my 17 years as [a freelance designer/developer](https://steveleggat.com "Steve Leggat has been a freelance graphic designer and coder in New Zealand and Taiwan since 2006") I've tried out a decent number of time-trackers, from native apps, browser-based and even just using MacOS' Notes app. For the last year I have been using Kimai and have for the most part been pretty happy with it. Trouble is, it occupies over 2.5GB of my server space, and with over 21,000 files it's no surprise that it's quite slooooow and easy to break ...

... so I created TallyHo, with a 'simple by design' philosophy.


## Disclaimer:

TallyHo's code is not particularly elegant, yet. Please feel free to contribute!
