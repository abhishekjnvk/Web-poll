# PHP-Poll
Lightweight Polling website in PHP 7 (Codeigniter 4) & MySQL

## How to Use
* clone this repo
* Start you MySql Server
* Create a DB with name 'polling' & restore it with polling.sql file
* change databse username & password in .env file
* start the your apache server
* visit url in browser

## Minimum Requirements
* PHP 7.2 OR Above
* MySQL 5.7 OR Above

## How it Works
* To uniquely identify users for each pole server issue a voting id for each user in poll. 
* One IP can only contribute one vote 
* User will get alert while trying to revote in pole & his vote will not be counted for 2nd time

## libraries & FrameWork Used
 * [Bootstrap 4.5](https://getbootstrap.com/docs/4.5/)
 * [Jquery](https://jquery.com/)
 * [jquery-confirm](https://craftpip.github.io/jquery-confirm)
 * [Codeigniter4](https://codeigniter4.github.io/userguide/index.html)
