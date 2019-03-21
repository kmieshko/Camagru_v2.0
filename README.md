# Camagru

This web project is a small web application allowing to make basic photo and video editing using webcam and some predefined images.

*Notice:* Project has some restrictions:

  * I must use ony PHP language to create your server-side application, with just the standard library.
  *  Client-side, my pages must use HTML, CSS and JavaScript.
  *  Every framework, micro-framework or library that I don’t create are totally **forbidden**, except for CSS frameworks that doesn’t need forbidden JavaScript.
  *  I must use the PDO abstraction driver to communicate with your database, that must be queryable with SQL. The error mode of this driver must be set to PDO::ERRMODE_EXCEPTION
  *  Application must be free of any security leak.

# Feautues

**User features**
  * The application allow a user to sign up by asking a valid email address, an username and a password with minimum level of complexity.
  * At the end of the registration process, an user can confirm his account via a unique link sent at the email address fullfiled in the registration form.
  * The user is able to connect to application, using his username and his password. He also able to tell the application to send a password reinitialisation mail, if he forget his password.
  * The user is able to disconnect in one click at any time on any page.
  * Once connected, an user can modify his username, mail address or password.
  
**Gallery features**
  * This part is public and displays all the images edited by all the users, ordered by date of creation. It should also allow (only) a connected user to like them and/or comment them.
  * When an image receives a new comment, the author of the image should be notified by email. This preference set as false by default but can be activated in user’s preferences.
  * The list of images paginated with 6 elements per page.
  
**Editing features**

This part accessible only to users that are authentified/connected and gently reject all other users that attempt to access it without being successfully logged in.
  * This page contains a main section containing the preview of the user’s webcam, the list of superposable images and a button allowing to capture a picture.
  * Superposable images are selectable and the button allowing to take the picture is inactive (not clickable) as long as no superposable image has been selected.
  * The creation of the final image (so among others the superposing of the two images) is done on the server side, in PHP.
  * Because not everyone has a webcam, site allows the upload of a user image instead of capturing one with the webcam.
  * The user is able to delete his edited images, but only his, not other users creations.
  
# Mandatory Things

*Authorized languages*:
  * [**Server**] PHP
  * [**Client**] HTML - CSS - JavaScript (only with browser natives API)
  
*Authorized frameworks*:
  * [**Server**] None
  * [**Client**] CSS Frameworks tolerated, unless it adds forbidden JavaScript
  
*Project should contain imperatively*
  * A **config/setup.php** file, capable of creating or re-creating the database schema, by using the info cintained in the file **config/database.php**
  * A **config/database.php** file, containing your database configuration, that will be instanced via PDO in the following format:
  ```
  <?php
  $DB_DSN = ...;
  $DB_USER = ...;
  $DB_PASSWORD = ...;
  ```
  **DSN** (Data Source Name) contains required information needed to connect to the database, for instance `mysql:dbname=testdb;host=127.0.0.1`. 
  
# Bonus

1. Project is done with own MVC framework
2. reCAPTCHA v2.0 is present on *Log In* page
3. The user can not only add, but also delete his own comments. And user whose post left a comment can also delete comments.
4. “AJAXify” exchanges with the server.
5. If aspect ratio of upload image doesn't equivalent 4:3, user can fitted or scaled this image

# Some examples

