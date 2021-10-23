# SwiftKit Database Manager

Used to manage the DataBase powering SwiftKit and ensuring it's always as up to date as possible.

---

_This project is **not** maintained._

It is left for historical purpose, you can look around at https://skdbm.clorith.space

## Capabilities

The system is at its current stage divided into three capability levels

- Disabled
- Regular user
- Administrator

Security dictates that all levels are modified in real time, this means that a logged in user will be logged out if his or her access level is amended to the Disabled user group

### Disabled Users

A disabled user is unable to login to the management interface, and will also be unable to perform actions like AJAX updates to elements where this is enabled if already signed in.

### Regular Users

A regular user is the default access level, these users may log in, modify data as well as add or remove data rows

### Administrators

The highest access tier, the Administrator has the capabilities to modify user access, but also has an extended Log view where he or she may Restore previously deleted data values or view the logged in address of the user making a change in the system (for security purpose).

The administrator is also the only access level (at this time) with access to the export system. The export system lets him generate a data file based on the datafile blueprints available in the system, which can be directly incorporated into SwiftKit.

## Security

We employ multiple security features to ensure the integrity of our data, some with fallbacks due to device restrictions, and others without.

### Define kill switches

All files in the system are prepended with a define() killswitch, this means if the file isn't included by a pre-approved path it is presumed directly accessed which is an illegal operation, and the user will receive an empty screen, or will be forced to the logout screen depending on the situation.

### Session Cookies

When signing in, you are assigned a cookie containing your user ID, this is the only form of identification used by the system after you have signed in.

To prevent cookie manipulation, a users last authenticated IP is added to the database, and on each page request this is validated against the users current IP. This is to prevent both cookie/session hijacking, as well as someone attempting to utilize the user id of a more privileged user.

Cookies are defined as non-persistent. This means they will be removed once you close your browser, or sign out of the system. This is a more reliable way to prevent false-positive logouts due to inactivity as some times adding data requires mid-entry research.

### G-Auth

Our login system also relies on the Google Authenticator for two-factor logins. This means that you are unable* to sign in to our system without your smart device at hand to get the current time-specific code along with your normal user credentials (username & password)

### IP-Local (G-Auth fallback)

For the users without a smart device, we have a fallback system called IP-Lock.

This system will bind your account to a single IP address (the one used during your first login), this is not an ideal approach as dynamic IP users will be unabe to sign in on new visits, but it's the only "safe" approach to a fallback.

## Local project setup

You can set up the project on your own, there is one file that matters, alongside the database.

1. Within `src/includes/config.php`, declare your database credentials on the first few lines.
2. Set up your database using the `database/scaffold.sql` file
3. Optionally, populate the database with pre-existing data from `database/populate.sql`

You will also have to manually add your first user, this is added to your database in the `users` table;
- Supply a username.
- Supply a password, this should be a `SHA1` encoded string.
- Supply a user access level of `100` (this is an admin level).

Upon your first login after setting this up, you will be prompted to either add an authenticator token, or click the link 
declaring that you have no smart device to get a 2-factor token with, and your user will instead be locked to your current IP address.
