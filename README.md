# Waffy
Simple PHP => Nginx denylist 

Waffy allows you to easily add and remove IP addresses from a global Nginx denylist.

This is useful for blocking bots and spammy crawlers that are hammering your site with requests. The major benefit of this package is that it blocks the request at the Nginx level before it even reaches your PHP application, conserving resources for legitimate requests

## Host Configuration (Ubuntu)

In order to allow a php process to run nginx commands you will need to assign dedicated permissions via the sudoers file

```bash
sudo visudo
```

Add the following lines

```
www-data ALL=(ALL) NOPASSWD: /usr/sbin/nginx -s reload
www-data ALL=(ALL) NOPASSWD: /usr/sbin/nginx -t
```

This will allow the www-data user to run these two command ONLY,

Check Nginx configuration `nginx -t`
Reload Nginx `nginx -s reload`

### Create directory structure to hold the denylist.conf file

```bash
sudo mkdir /etc/nginx/blacklist
```

### Ensure it is accessible and writable by www-data

```bash
sudo chown -R www-data:www-data /etc/nginx/blacklist
```

### Touch the denylist.conf file and ensure it is read/writable by www-data

```bash
sudo touch /etc/nginx/blacklist/denylist.conf
sudo chown www-data:www-data /etc/nginx/blacklist/denylist.conf
```

### Add the following to the http {} block in your nginx.conf file if you want a global denylist, or to each individual sites vhost config file if you only want a targeted denylist.

```
http {

    include /etc/nginx/blacklist/*;

}
```

### Test the config and reload Nginx 

```
sudo nginx -t
sudo service nginx reload
```

## Usage

```
composer require turbo124/waffy
```

Ban an IP Address

```php
use Turbo124\Waffy\Deny;

$deny = new Deny();
$deny->addDeny('1.2.3.4');
```

Unban an IP Address
```php
$deny = new Deny();
$deny->removeDeny('1.2.3.4');
```

Ban using CIDR notation
```php
$deny = new Deny();
$deny->addDeny('192.168.0.0/24');
```

Clear all IPs from deny list

```php
$deny = new Deny();
$deny->clearDenyList()
```

> **Note:**
> All methods retrun a boolean on success, or throw an \Exception.