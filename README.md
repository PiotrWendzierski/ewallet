# eWallet - Your electronic wallet
## File contains:
* project description
* best features
* tools
* how to run as Docker Container
* how to run
* inspiration
## Project description
eWallet is an backend app that allows you to control your expenses and income. The user uses the login system. It offers many practical tools and charts that allow you to control the state of your portfolio. Frontend is finished. The entire application is conceived and created by myself.
## Best features
* create account and login system
* adding transactions
* db connection (saving progress)
* tracking your portfolio
* filtering data based on various factors
* multiple charts based on data
* piggy bank

## Tools
* Docker, DockerHub
* GIT, GitHub
* PHP
* MySQL, phpMyAdmin
* JavaScript
* HTML
* CSS

## Running as Docker Container
You can run the server as a docker container. Clone the repository from GitHub to a local folder of your choice on your machine. Now locally navigate to the cloned repository
```
cd ewallet-main
```
Use the following command to build the container:
```
docker compose up
```
Install mysqli extension for PHP
```
docker exec -ti ewallet-main-www-1 sh
```
```
#docker-php-ext-install mysqli
```
Then stop & start the container with the following command:
```
docker compose stop
```
```
docker compose start
```
This should start up the example application and it can be accessed at http://localhost/index.php
Write http://localhost:8001 to see data base

## How to run
* Download XAMPP: https://www.apachefriends.org/pl/download.html
* Install XAMPP https://www.youtube.com/watch?v=SoKCB41tKW4&t=31s&ab_channel=GuideLoop. Watch till 1:15.
* Create folder eWallet in xampp/htdocs utworzyć (remember about letter case!)
* Clone repo to xampp/htdocs/eWallet (remember about letter case!):
<img src="https://github.com/PiotrWendzierski/ewallet/assets/114096435/f476a805-7c15-4dd9-af36-3bf8caafe971"></img>
* Copy 9 files from xampp/htdocs/ewallet-main/Nowy Folder/ewalletttt:
<img src="https://github.com/PiotrWendzierski/ewallet/assets/114096435/b956fd8c-a4cb-4e90-a004-e4794e4c7b58"></img>
* Create folder ewallet in (remember about letter case!) xampp/mysql/data and paste 9 files:
<img src="https://github.com/PiotrWendzierski/ewallet/assets/114096435/71661ffc-bf0c-4109-93c9-e9135d17ec4b"></img>
* Copy 5 files from xampp/htdocs/ewallet-main/Nowy Folder/db:
<img src="https://github.com/PiotrWendzierski/ewallet/assets/114096435/9be45253-81f9-47bb-849e-bd32b75943cd"></img>
* Paste and CHANGE 5 files in xampp/mysql/data:
<img src="https://github.com/PiotrWendzierski/ewallet/assets/114096435/db681e3d-8908-4ed6-abe4-92e144ac730d"></img>
* Open XAMPP and press START in 2 buttons:
<img src="https://github.com/PiotrWendzierski/ewallet/assets/114096435/200fc07d-7bf0-4215-8c6b-4e39131c4663"></img>
* Open chrome/opera write „localhost/ewallet” and press enter.
 <img src="https://github.com/PiotrWendzierski/ewallet/assets/114096435/c4d52c63-c455-4293-a8aa-c539164f8fac"></img>
* Write localhost/phpmyadmin to see data base

 ## Inspiration
 The project was partly inspired by the cointracking.info website
