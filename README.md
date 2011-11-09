Quickies
--------

**Philosophy**

This code is running my Mac OS X programming code snippets on [http://quickies.seriot.ch](http://quickies.seriot.ch).

Credits to [Borkware Quickies](http://borkware.com/quickies/) for inspiration and prior art.

**Features**

  - PHP code and MySQL database, for maximal portability
  - two entities, `Category` and `Note`, one-to-many relationship
  - create, update and delete for all entities, search in notes title and text
  - [Markdown syntax](http://daringfireball.net/projects/markdown/basics)
  - made for code snippets but useable for virtually anything

Note that I am not a PHP developer and [any suggestion to improve the code is welcome](http://seriot.ch/contact.php).

**Installation**

  1. create a MySQL database as well as a user
  2. create the tables by running the queries in `quickies.sql`
  2. edit `quickies/config.inc` and `quickies/admin/config.inc` to match your settings
  3. upload the whole `quickies` directory to your web server
  4. go to `quickies/` to ensure the connection works
  5. go to `quickies/admin/` to see the admin page
  6. click on `categories` and create your first catetory
  7. click on `notes` and create your first note

**Caveats**

  - you can't save a note without a category
  - if you delete a category, related notes won't show up in the public part
  - if you delete a category, related notes will still show up in the admin part

**Security**

  - you should protect the `quickies/admin/` directory with a [.htaccess](http://httpd.apache.org/docs/2.0/howto/htaccess.html) file
  - you should [create a public user, GRANT him SELECT only](http://dev.mysql.com/doc/refman/5.1/en/adding-users.html) and use it in `config.inc`
  
**License**

  - this code is in the public domain
  - I would *really appreciate* if you could [send me a word](http://seriot.ch/contact.php) with the URL where you installed it
