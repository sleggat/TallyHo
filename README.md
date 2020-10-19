# TallyHo

> A time tracker for the uncomplicated freelancer.

![Preview](https://raw.githubusercontent.com/sleggat/TallyHo/master/screenshot.jpg)

Demo at: [tallyho.steveleggat.com](http://tallyho.steveleggat.com)

## Features:

- Flat file (text/Yaml) ‘database’. Makes backups/syncing data super easy.
- Add a New task, or continue a previous task. New Clients/Projects created on-the-fly.
- Edit tasks (double-click, or select via dropdown)
- Duplicate / Restart tasks
- Filter by Client and/or Project
- Delete tasks
- Select rows/days to show a tally of costs and hours
- Add Reimbursable expenses (e.g. printing costs, client's domain name, 'research' trip to Bali)
- Different hourly rates; Default Rate / Client Rate / Project Rate
- Easily see which tasks have been invoiced and which need to be

#### Features I'd like to add at some point;

- Filtering by date range
- Basic Reports/Charts (Historical and Projection)
- Basic exporting to CSV

#### TallyHo is simple by design and I have no intention of adding the following;

- Invoice Creation
- Multi-user Support
- Multi-currencies (e.g per client)

## Installation:

Unzip and put the 'tallyho' in your '~/Sites' folder or wherever localhost points to. You'll want to configure default hourly rate, timezone and other stuff first. Just open up and edit guts/config.php

Once installed, just open 'http://localhost/tallyho' up in your web browser. Upon first-run TallyHo will create some demo tasks which you can edit/duplicate/delete until you're ready to start adding your own.

## Flat-file Structure:

TallyHo uses flat-files (.txt/.yaml) instead of a database.

Why flat-files instead of a MySQL database? I much prefer being able to copy/backup and manually edit a bunch of text files rather than having to deal with a MySQL database. Keeps things simple.

A typical file structure;

```
/tallyho/data/ClientO/ProjectX/20191009-1230.txt
/tallyho/data/SupertouchX/Website2020/20201010-1645.txt
```

## Additional info:

Hourly rates and invoicing info can be entered into a \_info.yaml file in a client or project folder.
In the future, this will be available from the frontend.

### Hourly Rates

Varying hourly rates can be set up per client and project.

```
/tallyho/data/ClientO/ProjectX/_info.yaml
```

Add an hourly rate like so:

```
Rate: 75
```

The heirarchy is Project>Client>Default

### Marking Tasks as Invoiced

I have implemented some basic invoice marking. Again this goes in a \_info.yaml file in the client or project folder.

Adding this line will mark everything on or before Christmas 2020 as invoiced (represented by the check/tick icon)

```
Invoiced: '20201225'
```

## The Backstory:

Over the course of my 17 years as [a freelance designer/developer](https://steveleggat.com "Steve Leggat has been a freelance graphic designer and coder in New Zealand and Taiwan since 2006") I've tried out a decent number of time-trackers, from native apps, browser-based and even just using MacOS' Notes app. For the last year I have been using Kimai and have for the most part been pretty happy with it. Trouble is, it occupies over 2.5GB of my server space, and with over 21,000 files it's no surprise that it's quite slooooow and easy to break ...

... so I created TallyHo, with a 'simple by design' philosophy.

## Disclaimer:

TallyHo's code is not particularly elegant, yet. Please feel free to contribute!

Don't put TallyHo where malicious users might mess with your data. Put it in a password-protected folder if you have to.

TallyHo has been tested and works sweet on PHP v5.6 and PHP v7.2
