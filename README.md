# TallyHo
> A single-user time tracker, built using flat-files for speed and portability.

![Preview](https://raw.githubusercontent.com/sleggat/TallyHo/master/screenshot01.jpg)


## Features:
- Flat file (text/Yaml) ‘database’.
- Quick add (pre-fills previous client/project)
- Edit tasks
- Duplicate / Restart tasks
- Filter by Client and/or Project
- Select rows/days and calculate total costs
- Different hourly rates; Default Rate / Client Rate / Project Rate
- Visual cue showing time spent on each task (blue = meh / red = good effort)


TallyHo is simple by design and I have no intention of adding the following;
- Invoicing
- Multi-users
- Multi-currency
- Login/Pass


Features I'd like to add at some point;
- Adding of reimbursable expenses (e.g. printing costs, client's domain name, 'research' trip to Bali)
- Filtering by date range
- Basic Reports/Charts (Historical and Projection)
- Basic exporting


## Installation:

I recommend putting it on your localhost, but you could also upload it to your webserver.
Configure default hourly rate, timezone and base-path here: guts/config.php


## Usage:

Once installed just open 'http://localhost/tallyho' up in your web browser. Upon initiation it will create some demo tasks which you can edit/duplicate/delete.


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

Over the course of my 17 years as a freelance designer/developer I've tried out a decent number of time-trackers, from native apps, browser-based and even just using MacOS' Notes app. For the last year I have been using Kimai and have for the most part been pretty happy with it. Trouble is, it occupies over 2.5GB of my server space, and with over 21,000 files it's no surprise that it's quite slooooow and easy to break ...

... so I created TallyHo, with a 'simple by design' philosophy.


## Disclaimer:

TallyHo is not very robust or elegant (yay procedural hacking!). Give it time, and please feel free to contribute!
