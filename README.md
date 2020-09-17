# TallyHo
A single-user time tracker, built using flat-files for speed and portability.


# Features:
- Flat file (text/Yaml) ‘database’ (no need for MYSQL)
- Quick add (pre-fills previous client/project)
- Edit tasks
- Duplicate / Restart tasks
- Filters (half-arsed implementation)
- Select rows/days and calculate total costs.
- Visual cue (colour bar) showing time spent on each task


File structure example;

/data/ClientO/ProjectX/20191009-1230.txt
/data/SupertouchX/Website2020/20201010-1645.txt


If you'd like varying hourly rates for each client and project, just add a \_info.yaml in the folder. 

E.g.

/data/ClientO/ProjectX/\_info.yaml 

and add the hourly rate like so:
Rate: 75



Note: This is still very basic and the code is super-raw - no objects, classes or fancy coding.
