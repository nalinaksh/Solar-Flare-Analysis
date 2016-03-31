# Solar-Flare-Analysis

File Description:
- action_page*.php : Each of these files is used to download queried data files from a different source. Some of them can be used to downlaod data directly to the browser, while others can be used from the command line. For example,  action_page.php can be used to download data via the browser from "ftp://ftp.swpc.noaa.gov" anytime between June-01-2014 to June-30-2014.
- index2.html: To Query and download data files from "ftp://ftp.swpc.noaa.gov", to be used with action_page.php 
- parse*.php: Each of these files parses the downloaded data files (from steps above) and inserts data into database tables. The database tables have already been created in MySQL database, not shown here. 
- catalogs_v2_orig.php: This queries the various database catalogs, according to the user's web based query, and retrieves and publish data onto the web page.
- plotdata_goes15_v2.php: Used to match various events in response to the selected event and to visualize all the events and the raw data.
- index.html: To query and retrieve different MySQL database catalogs. 

Usage:
This work can be accessed from "https://web.njit.edu/~ng294/Projects.html"
- Open the above url
- Select a time range (Eg: June-01-2014 to June-30-2014)
- This will publish Solar Flare events from all the 3 catalogs.
- Select an event from any of the 3 catalogs (Eg: select the first event from the NOAA catalog (GOES class C2.4)) and press Plotdata button.
- This will plot the raw data from goes_15 database and also show all the matching events w.r.t the selected event C2.4. 
- Images, Movies of the selected/matched events can aslo be accessed from the URLs column of the matched HEK events.  
