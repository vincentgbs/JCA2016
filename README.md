## JCA Church Website
This was a MVC framework that I developed to run the backend of our church website. The frontend design was done by Philip and Clement. The backend includes a simple CMS that allowed pastors and church staff to update the website as needed. This was a private repository while it hosted the website, but now that the backend has migrated to another platform, the code base is being made public.

## Custom features
* The application had a standard username/password login but the staff asked if there was a way to simplify the login process, so I implemented the Google OAuth2 API so that they could use SSO.
* The staff requested a way to grant new users permissions to manage parts of the website, so I developed a permissions module (I created the ADMINU and ADMINP to more easily manage the database locally).
* The CMS allows standard page, template, and form CRUD functions. Due to the limitations of the server that the website was run on, I had to create a custom upload function for files that were larger than 2MB.
* The staff requested that forms would go to their google drives, so I implemented the Google Drive API to write form data directly to a Google Sheet.
* The JCA controller manages the frontend website. The staff requested the ability to add an emergency banner for last minute announcements.
