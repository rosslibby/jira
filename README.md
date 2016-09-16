# /tfs

/tfs is a slash command for Slack

### Usage
- /tfs [work item id] | returns the linked work item
- /tfs [work item id] [text] | returns the linked work item beside your message
- /tfs [text with #[work item id] and more text] | returns a message with a linked work item
- /tfs build [build id] | returns a link to the build
- /tfs -git [environment] [full sha] | returns the linked commit

### Prerequisites
This integration runs on [PHP 5.5.9](http://php.net/releases/5_5_9.php)  
I use [Composer](https://getcomposer.org/download/) to manage dependencies

### Installation
## 1.
Clone this repo
## 2.
Run `composer install` or `composer.phar install` according to your setup. This will pull in the package for enabling `.env` files.
## 2.
Change the provided `.env.example` to `.env`
- Inside the `.env` file Set the `APP_TOKEN` to your Slack token
- Set the `TFS_DOMAIN` variable to your domain

That's it.
