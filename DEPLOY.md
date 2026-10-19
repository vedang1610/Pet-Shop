# Put Pet Shop online (InfinityFree, free)

GitHub stores the code, but it can't run PHP and MySQL. These steps put the site on **InfinityFree**, a free PHP + MySQL host. It takes about 15 minutes.

> This site is a student project. It shows a "demo project, do not pay" notice to visitors, and PayPal runs in sandbox (test) mode.

## 1. Create the hosting account

1. Sign up at **https://www.infinityfree.com** and verify your email.
2. Click **Create Account**, pick a free subdomain (for example `petshop-yourname.infinityfreeapp.com`) and finish.
3. Open the account's **Control Panel**. In **PHP Config**, choose PHP **8.1** or newer.
4. In **SSL Certificates**, request the free certificate so the site works on `https://` (it can take a few minutes).

## 2. Create the database

1. Control Panel → **MySQL Databases** → create a database, e.g. `petshop`.
2. Note the four values shown there (you'll need them in step 4):
   - **MySQL Hostname**, like `sql123.infinityfree.com`
   - **MySQL Database Name**, like `if0_12345678_petshop`
   - **MySQL Username**, like `if0_12345678`
   - **MySQL Password**: your hosting account password (Account → *Show* password)
3. Click **phpMyAdmin** next to the database → **Import** → choose `database/pet_shop.sql` from this project → **Go**.

## 3. Upload the files

1. Control Panel → **Online File Manager** → open the **htdocs** folder and delete the default `index2.html`.
2. Upload the files of this project into **htdocs**, so that `htdocs/index.php` exists (not `htdocs/Pet-Shop/index.php`).
   - Easiest: upload the ready-made zip and use **Extract** in the file manager, then delete the zip.
   - Or use an FTP app such as FileZilla (the FTP details are under **FTP Details** in the Control Panel).

## 4. Connect the site to the database

In the file manager, inside `htdocs/admin/`:

1. Copy `db_config.example.php` to **`db_config.php`** and fill in the four values from step 2:
   ```php
   return array(
       'host'     => 'sql123.infinityfree.com',
       'user'     => 'if0_12345678',
       'password' => 'your hosting account password',
       'database' => 'if0_12345678_petshop',
   );
   ```
2. Copy `recovery_config.example.php` to **`recovery_config.php`** and replace the key with your own long random text (it's used to reset the admin password).

## 5. Open the site

- Shop: `https://your-subdomain.infinityfreeapp.com/`
- Admin: `https://your-subdomain.infinityfreeapp.com/admin/index.php`, login `admin@admin.com` / `admin`
- **Change the admin password right away** (Admin → Profile), because the demo password is public in this repository.

## Good to know about free hosting

- **New sites can take up to 72 hours** to work everywhere after you create the subdomain (DNS). If it says the site can't be found, wait and try again.
- **PIN code lookup:** free hosting may block the site from contacting India Post. If so, the address form says it couldn't check the PIN and customers type the city and state themselves.
- **PayPal** stays in sandbox mode; the return links adjust to your domain automatically.
- Free hosting has daily limits and can be slower; a paid host (e.g. Hostinger) is better for a real shop.
- To update the live site later, upload the changed files again (InfinityFree doesn't pull from GitHub automatically).
