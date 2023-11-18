===================================================
 Installation Steps - Sharetronix CometChat Plugin
===================================================

1. Upload the extracted files to your Sharetronix root folder (assuming Sharetronix is present at http://www.yoursite.com/sharetronix henceforth) 

Directory structure

- sharetronix
	- cometchat
		- cometchat files and folders
	- /apps/cometchat_plugin
		- plugin files
		- cometchat.zip


2. Go to our appstore and choose the app you want to install.

3. Click on the "Download" button in the app's page to get the .zip archive containg the source code of the app.

4. Extract the .zip archive, the extracted files should be located in a folder with the App's name(eg. appname.zip => appname folder).

5. Upload this folder to your web host in the ./apps folder in your Sharetronix directory. eg.
./apps/
   appname
   ...
   anotherappname
   ...

6. Go to ./system/cache_html folder in your Sharetronix directory and delete all files in it.

7. Go to your database management tool and open your sharetronix database to insert installation data for the new plugin.

8. Select the "plugins" table in it and insert a new row.
name - the name of the plugin, it MUST be the same as your plugin folder name (uploaded in ./apps)
is_installed - 1
date_installed - some integer value, eg. 1, eg. 3123123, eg. 12312
installed_by_user_id - enter 1 or your user id in the community

9.Select the "plugins_cache" table and TRUNCATE this table(delete all rows in it).

10. Check if there is a file named "Installer.php" in your app's folder. If there is you should check what database changes it performs and do it manually. Then add these changes in "plugins_tables" table in the database(if the app creates a table you should add this table name in it).

11. That's it. Go to your community URL and refresh the page.

