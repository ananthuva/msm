/*
SQLyog Ultimate - MySQL GUI v8.21 
MySQL - 5.6.16-1~exp1 : Database - msm
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `attachment` */

DROP TABLE IF EXISTS `attachment`;

CREATE TABLE `attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(60) NOT NULL,
  `attachment` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `attachment` */

insert  into `attachment`(`id`,`order_id`,`attachment`) values (1,'5','uploads/orders/2017/06/28/attachment5953c8a54aa2b.jpg'),(2,'5','uploads/orders/2017/06/28/attachment5953c8a56350f.jpg'),(3,'6','uploads/orders/2017/06/28/attachment5953cbde0106f.jpg'),(4,'6','uploads/orders/2017/06/28/attachment5953cbde0b3bd.jpg'),(5,'7','uploads/orders/2017/07/01/attachment5957b27c11e36.jpg'),(6,'7','uploads/orders/2017/07/01/attachment5957b27c1e3f2.jpg');

/*Table structure for table `billing_address` */

DROP TABLE IF EXISTS `billing_address`;

CREATE TABLE `billing_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `house_name` varchar(200) NOT NULL,
  `street` varchar(200) NOT NULL,
  `postoffice` char(100) NOT NULL,
  `pin` int(10) NOT NULL,
  `state_id` int(10) NOT NULL,
  `country` char(10) DEFAULT 'India',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `billing_address` */

insert  into `billing_address`(`id`,`order_id`,`full_name`,`mobile`,`house_name`,`street`,`postoffice`,`pin`,`state_id`,`country`) values (1,2,'1a','17777','billing_house_name','billing_street','asdad',1555,1,'India'),(2,3,'1a','17777','billing_house_name','billing_street','asdad',1555,1,'India'),(3,4,'1a','17777','billing_house_name','billing_street','asdad',1555,1,'India'),(4,5,'1a','17777','billing_house_name','billing_street','asdad',1555,1,'India'),(5,6,'1a','17777','billing_house_name','billing_street','asdad',1555,1,'India'),(6,7,'1a','17777','billing_house_name','billing_street','asdad',1555,1,'India');

/*Table structure for table `city` */

DROP TABLE IF EXISTS `city`;

CREATE TABLE `city` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `state_id` int(10) NOT NULL,
  `name` char(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `city` */

/*Table structure for table `country` */

DROP TABLE IF EXISTS `country`;

CREATE TABLE `country` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `country` */

insert  into `country`(`id`,`Name`) values (1,'INDIA');

/*Table structure for table `delivery_address` */

DROP TABLE IF EXISTS `delivery_address`;

CREATE TABLE `delivery_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `house_name` varchar(200) NOT NULL,
  `street` varchar(200) NOT NULL,
  `postoffice` char(100) NOT NULL,
  `pin` int(10) NOT NULL,
  `state_id` int(10) NOT NULL,
  `country` char(10) DEFAULT 'India',
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `delivery_address` */

insert  into `delivery_address`(`id`,`order_id`,`full_name`,`mobile`,`house_name`,`street`,`postoffice`,`pin`,`state_id`,`country`,`latitude`,`longitude`) values (1,2,'delivery_full_name','delivery_mobile','ssss','delivery_street','3333',1,1,'India',23.444,33.44444),(2,3,'delivery_full_name','delivery_mobile','ssss','delivery_street','3333',1,1,'India',23.444,33.44444),(3,4,'delivery_full_name','delivery_mobile','ssss','delivery_street','3333',1,1,'India',23.444,33.44444),(4,5,'delivery_full_name','delivery_mobile','ssss','delivery_street','3333',1,1,'India',23.444,33.44444),(5,6,'delivery_full_name','9999999999','delivery_house_name','delivery_street','3333',55555,1,'India',23.444,33.44444),(6,7,'delivery_full_name','9999999999','delivery_house_name','delivery_street','3333',55555,1,'India',23.444,33.44444);

/*Table structure for table `district` */

DROP TABLE IF EXISTS `district`;

CREATE TABLE `district` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `state_id` int(11) unsigned NOT NULL,
  `name` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Data for the table `district` */

insert  into `district`(`id`,`state_id`,`name`) values (1,1,'Thiruvananthapuram'),(2,1,'Kollam'),(3,1,'Pathanamthitta'),(4,1,'Alappuzha'),(5,1,'Kottayam'),(6,1,'Idukki'),(7,1,'Ernakulam'),(8,1,'Thrissur'),(9,1,'Palakkad'),(10,1,'Malappuram'),(11,1,'Kozhikode'),(12,1,'Wayanad'),(13,1,'Kannur'),(14,1,'Kasargod');

/*Table structure for table `order` */

DROP TABLE IF EXISTS `order`;

CREATE TABLE `order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_bill_id` varchar(60) NOT NULL,
  `store_id` int(10) NOT NULL,
  `note` text,
  `status` enum('Pending','Processing','Completed') DEFAULT 'Pending',
  `user_id` int(11) NOT NULL,
  `order_date` date DEFAULT '0000-00-00',
  `payment_type` char(60) DEFAULT NULL,
  `payment_status` char(60) DEFAULT NULL,
  `created_on` datetime DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) DEFAULT NULL,
  `last_modified_by` int(11) DEFAULT NULL,
  `last_modified_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `order` */

insert  into `order`(`id`,`order_bill_id`,`store_id`,`note`,`status`,`user_id`,`order_date`,`payment_type`,`payment_status`,`created_on`,`created_by`,`last_modified_by`,`last_modified_on`) values (1,'Ord-20170625210945',1,'qewqwe','Pending',1,'2017-06-25',NULL,NULL,'2017-06-25 21:09:45',1,1,'2017-06-25 21:09:45'),(2,'Ord-20170625211210',1,'qewqwe','Pending',1,'2017-06-25',NULL,NULL,'2017-06-25 21:12:10',1,1,'2017-06-25 21:12:10'),(3,'Ord-20170628113340',1,'qewqwe','Pending',1,'2017-06-28',NULL,NULL,'2017-06-28 11:33:40',1,1,'2017-06-28 11:33:40'),(4,'Ord-20170628204657',1,'qewqwe','Pending',1,'2017-06-28',NULL,NULL,'2017-06-28 20:46:57',1,1,'2017-06-28 20:46:57'),(5,'Ord-20170628204757',1,'qewqwe','Pending',1,'2017-06-28',NULL,NULL,'2017-06-28 20:47:57',1,1,'2017-06-28 20:47:57'),(6,'Ord-20170628210141',1,'qewqwe','Pending',1,'2017-06-28',NULL,NULL,'2017-06-28 21:01:41',1,1,'2017-06-28 21:01:41'),(7,'Ord-20170701200227',1,'qewqwe','Pending',1,'2017-07-01',NULL,NULL,'2017-07-01 20:02:27',1,1,'2017-07-01 20:02:27');

/*Table structure for table `permission` */

DROP TABLE IF EXISTS `permission`;

CREATE TABLE `permission` (
  `id` int(122) unsigned NOT NULL AUTO_INCREMENT,
  `user_type` varchar(250) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `permission` */

insert  into `permission`(`id`,`user_type`,`data`) values (1,'member','{\"users\":{\"own_create\":\"1\",\"own_read\":\"1\",\"own_update\":\"1\",\"own_delete\":\"1\"},\"stores\":{\"own_create\":\"1\",\"own_read\":\"1\",\"own_update\":\"1\",\"own_delete\":\"1\"}}'),(2,'admin','{\"users\":{\"own_create\":\"1\",\"own_read\":\"1\",\"own_update\":\"1\",\"own_delete\":\"1\",\"all_create\":\"1\",\"all_read\":\"1\",\"all_update\":\"1\",\"all_delete\":\"1\"}}'),(3,'pharmasist','{\"users\":{\"own_create\":\"1\",\"own_read\":\"1\",\"own_update\":\"1\",\"own_delete\":\"1\"},\"stores\":{\"own_create\":\"1\",\"own_read\":\"1\",\"own_update\":\"1\",\"own_delete\":\"1\"}}');

/*Table structure for table `setting` */

DROP TABLE IF EXISTS `setting`;

CREATE TABLE `setting` (
  `id` int(122) unsigned NOT NULL AUTO_INCREMENT,
  `keys` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

/*Data for the table `setting` */

insert  into `setting`(`id`,`keys`,`value`) values (1,'website','MQ Staff Web'),(2,'logo','logo.png'),(3,'favicon','favicon.ico'),(4,'SMTP_EMAIL',''),(5,'HOST',''),(6,'PORT',''),(7,'SMTP_SECURE',''),(8,'SMTP_PASSWORD',''),(9,'mail_setting','simple_mail'),(10,'company_name','Company Name'),(11,'crud_list','users,User'),(12,'EMAIL',''),(13,'UserModules','yes'),(14,'register_allowed','1'),(15,'email_invitation','1'),(16,'admin_approval','0'),(17,'user_type','[\"Member\"]'),(18,'country','1');

/*Table structure for table `state` */

DROP TABLE IF EXISTS `state`;

CREATE TABLE `state` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` int(10) DEFAULT '1',
  `name` char(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `state` */

insert  into `state`(`id`,`country_id`,`name`) values (1,1,'Kerala'),(2,1,'Tamil Nadu');

/*Table structure for table `stores` */

DROP TABLE IF EXISTS `stores`;

CREATE TABLE `stores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `address` text,
  `license_no` varchar(100) DEFAULT NULL,
  `poc` varchar(50) DEFAULT NULL,
  `agreement` text,
  `user_id` int(10) NOT NULL,
  `city_id` int(10) DEFAULT NULL,
  `district_id` int(10) DEFAULT NULL,
  `state_id` int(10) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `is_deleted` tinyint(1) DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `last_modified_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `stores` */

insert  into `stores`(`id`,`name`,`address`,`license_no`,`poc`,`agreement`,`user_id`,`city_id`,`district_id`,`state_id`,`latitude`,`longitude`,`is_active`,`is_deleted`,`created_by`,`last_modified_by`,`created_on`,`last_modified_on`) values (1,'nayana','tampanoor,trivandrum','12345','10',NULL,1,1,NULL,1,8.490873,76.952748,1,0,1,1,'0000-00-00 00:00:00','2017-06-18 21:17:23'),(2,'test','test','44444','10',NULL,1,1,NULL,1,8.490873,76.952748,1,0,1,1,'0000-00-00 00:00:00','2017-06-19 21:53:38'),(3,'nayan','tvc','12354','8',NULL,15,1,5,1,9.591566799999999,76.52215309999997,1,0,1,1,'2017-07-01 21:08:02','2017-07-17 23:24:10'),(4,'asdasd','asdasdasd','2343242','44.3','alter_1500316457.txt,msm_1500316457.sql',2,NULL,6,1,9.918624610199986,77.09930419921875,1,0,1,1,'2017-07-17 21:35:37','2017-07-18 00:04:17'),(5,'fsdfsfd','sdfsf','sfddsf','55',NULL,11,NULL,9,1,10.7867303,76.65479319999997,1,0,1,1,'2017-07-17 22:31:36','2017-07-17 22:31:36'),(6,'dasdasd','asdasda','asdsad','5.2',NULL,15,NULL,4,1,9.498066699999999,76.33884839999996,1,0,1,1,'2017-07-17 22:32:08','2017-07-17 23:15:00');

/*Table structure for table `templates` */

DROP TABLE IF EXISTS `templates`;

CREATE TABLE `templates` (
  `id` int(121) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `template_name` varchar(255) DEFAULT NULL,
  `html` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `templates` */

insert  into `templates`(`id`,`module`,`code`,`template_name`,`html`) values (1,'forgot_pass','forgot_password','Forgot password','<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\r\n<style media=\"all\" rel=\"stylesheet\" type=\"text/css\">/* Base ------------------------------ */\r\n    *:not(br):not(tr):not(html) {\r\n      font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif;\r\n      -webkit-box-sizing: border-box;\r\n      box-sizing: border-box;\r\n    }\r\n    body {\r\n      \r\n    }\r\n    a {\r\n      color: #3869D4;\r\n    }\r\n\r\n\r\n    /* Masthead ----------------------- */\r\n    .email-masthead {\r\n      padding: 25px 0;\r\n      text-align: center;\r\n    }\r\n    .email-masthead_logo {\r\n      max-width: 400px;\r\n      border: 0;\r\n    }\r\n    .email-footer {\r\n      width: 570px;\r\n      margin: 0 auto;\r\n      padding: 0;\r\n      text-align: center;\r\n    }\r\n    .email-footer p {\r\n      color: #AEAEAE;\r\n    }\r\n  \r\n    .content-cell {\r\n      padding: 35px;\r\n    }\r\n    .align-right {\r\n      text-align: right;\r\n    }\r\n\r\n    /* Type ------------------------------ */\r\n    h1 {\r\n      margin-top: 0;\r\n      color: #2F3133;\r\n      font-size: 19px;\r\n      font-weight: bold;\r\n      text-align: left;\r\n    }\r\n    h2 {\r\n      margin-top: 0;\r\n      color: #2F3133;\r\n      font-size: 16px;\r\n      font-weight: bold;\r\n      text-align: left;\r\n    }\r\n    h3 {\r\n      margin-top: 0;\r\n      color: #2F3133;\r\n      font-size: 14px;\r\n      font-weight: bold;\r\n      text-align: left;\r\n    }\r\n    p {\r\n      margin-top: 0;\r\n      color: #74787E;\r\n      font-size: 16px;\r\n      line-height: 1.5em;\r\n      text-align: left;\r\n    }\r\n    p.sub {\r\n      font-size: 12px;\r\n    }\r\n    p.center {\r\n      text-align: center;\r\n    }\r\n\r\n    /* Buttons ------------------------------ */\r\n    .button {\r\n      display: inline-block;\r\n      width: 200px;\r\n      background-color: #3869D4;\r\n      border-radius: 3px;\r\n      color: #ffffff;\r\n      font-size: 15px;\r\n      line-height: 45px;\r\n      text-align: center;\r\n      text-decoration: none;\r\n      -webkit-text-size-adjust: none;\r\n      mso-hide: all;\r\n    }\r\n    .button--green {\r\n      background-color: #22BC66;\r\n    }\r\n    .button--red {\r\n      background-color: #dc4d2f;\r\n    }\r\n    .button--blue {\r\n      background-color: #3869D4;\r\n    }\r\n</style>\r\n<table cellpadding=\"0\" cellspacing=\"0\" class=\"email-wrapper\" style=\"\r\n    width: 100%;\r\n    margin: 0;\r\n    padding: 0;\" width=\"100%\">\r\n	<tbody>\r\n		<tr>\r\n			<td align=\"center\">\r\n			<table cellpadding=\"0\" cellspacing=\"0\" class=\"email-content\" style=\"width: 100%;\r\n      margin: 0;\r\n      padding: 0;\" width=\"100%\"><!-- Logo -->\r\n				<tbody><!-- Email Body -->\r\n					<tr>\r\n						<td class=\"email-body\" style=\"width: 100%;\r\n    margin: 0;\r\n    padding: 0;\r\n    border-top: 1px solid #edeef2;\r\n    border-bottom: 1px solid #edeef2;\r\n    background-color: #edeef2;\" width=\"100%\">\r\n						<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"email-body_inner\" style=\" width: 570px;\r\n    margin:  14px auto;\r\n    background: #fff;\r\n    padding: 0;\r\n    border: 1px outset rgba(136, 131, 131, 0.26);\r\n    box-shadow: 0px 6px 38px rgb(0, 0, 0);\r\n       \" width=\"570\"><!-- Body content -->\r\n							<thead style=\"background: #3869d4;\">\r\n								<tr>\r\n									<th>\r\n									<div align=\"center\" style=\"padding: 15px; color: #000;\"><a class=\"email-masthead_name\" href=\"{var_action_url}\" style=\"font-size: 16px;\r\n      font-weight: bold;\r\n      color: #bbbfc3;\r\n      text-decoration: none;\r\n      text-shadow: 0 1px 0 white;\">{var_sender_name}</a></div>\r\n									</th>\r\n								</tr>\r\n							</thead>\r\n							<tbody>\r\n								<tr>\r\n									<td class=\"content-cell\" style=\"padding: 35px;\">\r\n									<h1>Hi {var_user_name},</h1>\r\n\r\n									<p>You recently requested to reset your password for your {var_website_name} account. Click the button below to reset it.</p>\r\n									<!-- Action -->\r\n\r\n									<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"body-action\" style=\"\r\n      width: 100%;\r\n      margin: 30px auto;\r\n      padding: 0;\r\n      text-align: center;\" width=\"100%\">\r\n										<tbody>\r\n											<tr>\r\n												<td align=\"center\">\r\n												<div><!--[if mso]><v:roundrect xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:w=\"urn:schemas-microsoft-com:office:word\" href=\"{{var_action_url}}\" style=\"height:45px;v-text-anchor:middle;width:200px;\" arcsize=\"7%\" stroke=\"f\" fill=\"t\">\r\n                              <v:fill type=\"tile\" color=\"#dc4d2f\" ></v:fill>\r\n                              <w:anchorlock></w:anchorlock>\r\n                              <center style=\"color:#ffffff;font-family:sans-serif;font-size:15px;\">Reset your password</center>\r\n                            </v:roundrect><![endif]--><a class=\"button button--red\" href=\"{var_verification_link}\" style=\"background-color: #dc4d2f;display: inline-block;\r\n      width: 200px;\r\n      background-color: #3869D4;\r\n      border-radius: 3px;\r\n      color: #ffffff;\r\n      font-size: 15px;\r\n      line-height: 45px;\r\n      text-align: center;\r\n      text-decoration: none;\r\n      -webkit-text-size-adjust: none;\r\n      mso-hide: all;\">Reset your password</a></div>\r\n												</td>\r\n											</tr>\r\n										</tbody>\r\n									</table>\r\n\r\n									<p>If you did not request a password reset, please ignore this email or reply to let us know.</p>\r\n\r\n									<p>Thanks,<br />\r\n									{var_sender_name} and the {var_website_name} Team</p>\r\n									<!-- Sub copy -->\r\n\r\n									<table class=\"body-sub\" style=\"margin-top: 25px;\r\n      padding-top: 25px;\r\n      border-top: 1px solid #EDEFF2;\">\r\n										<tbody>\r\n											<tr>\r\n												<td>\r\n												<p class=\"sub\" style=\"font-size:12px;\">If you are having trouble clicking the password reset button, copy and paste the URL below into your web browser.</p>\r\n\r\n												<p class=\"sub\" style=\"font-size:12px;\"><a href=\"{var_verification_link}\">{var_verification_link}</a></p>\r\n												</td>\r\n											</tr>\r\n										</tbody>\r\n									</table>\r\n									</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n						</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n'),(3,'users','invitation','Invitation','<p>Hello <strong>{var_user_email}</strong></p>\r\n\r\n<p>Click below link to register&nbsp;<br />\r\n{var_inviation_link}</p>\r\n\r\n<p>Thanks&nbsp;</p>\r\n');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(121) NOT NULL AUTO_INCREMENT,
  `var_key` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `is_deleted` varchar(255) DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `mobile_no` varchar(255) DEFAULT NULL,
  `phone_no` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT '0000-00-00',
  `address` text,
  `profile_pic` varchar(255) DEFAULT NULL,
  `user_type` enum('admin','pharmasist','member') DEFAULT NULL,
  `hash` varchar(255) DEFAULT NULL,
  `var_otp` int(4) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `last_modified_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`user_id`,`var_key`,`status`,`is_deleted`,`name`,`lname`,`password`,`mobile_no`,`phone_no`,`email`,`dob`,`address`,`profile_pic`,`user_type`,`hash`,`var_otp`,`is_verified`,`created_by`,`last_modified_by`,`created_on`,`last_modified_on`) values (1,'','active','0','admin',NULL,'$2y$10$/R/ys6rRs86DEiwzDp1kU.2Ix7Hpr8cTyyBDuIpL80YdMXegi7cMW','+918129293159',NULL,'ava007jb@gmail.com','0000-00-00',NULL,'demo_pic.png','admin','$2y$10$SKR89bDDy.L5Qf7j1.qUbO64XEo5yEhoO9Ftvox5ezvOOUpy9Y29W',7096,0,1,NULL,'0000-00-00 00:00:00','2017-06-18 13:57:04'),(2,NULL,'active','0','pharmasist',NULL,'$2y$10$0.pVMGrmUNad52skOf3jauzGMBAhr7WoJda53V6WTcgURmZzrJV.2',NULL,NULL,'asd@wer.com','0000-00-00',NULL,'user.png','pharmasist',NULL,NULL,0,1,NULL,'0000-00-00 00:00:00','2017-06-18 13:57:04'),(4,NULL,'active','0','7546','456456456','$2y$10$/R/ys6rRs86DEiwzDp1kU.2Ix7Hpr8cTyyBDuIpL80YdMXegi7cMW','456456','456456456','456456','0000-00-00','456456456','user.png','member',NULL,NULL,0,1,NULL,'0000-00-00 00:00:00','2017-06-18 13:57:04'),(5,NULL,'active','0','444','456456456','$2y$10$/GJvwhf7spXJVhzwIGmIaeIPnxo/GGgHJZQ3pG.yzJXBEgnI15Um6','4564568','456456456','admin','0000-00-00','66565','user.png','member',NULL,NULL,0,1,NULL,'0000-00-00 00:00:00','2017-06-18 13:57:04'),(10,NULL,'active','0','a','A','$2y$10$M59WW/f9.qZU.yKmbHhfWu3TK66P8WFc69Z35sqBdvXqh1r2R2yIW','+911111111111',NULL,'a@s.x','0000-00-00',NULL,'user.png','member',NULL,NULL,0,1,NULL,'0000-00-00 00:00:00','2017-06-18 13:57:04'),(11,NULL,'active','0','fff','fff','$2y$10$ZwJx3nqQL8WDfEqJoFtwf.935HnV98104i6UgzQSp.rMkmwges5we','+913333333333',NULL,'f@e.s','0000-00-00',NULL,'user.png','pharmasist',NULL,NULL,0,1,NULL,'0000-00-00 00:00:00','2017-06-18 13:57:04'),(12,NULL,'active','0','ss','ss','$2y$10$Al7WuYvVy59LTV95Co2T0evKukmaTGlrc/KvQwWyshgTad.i3R052','+913333333335',NULL,'dd@w.d','2017-05-08',NULL,'user.png','member',NULL,NULL,0,1,NULL,'0000-00-00 00:00:00','2017-06-18 13:57:04'),(13,NULL,'active','0','admin','admin','$2y$10$vimhFtPMl/W5yr9d/3ulTOTFns5ikG1eImwdmIGJm2sjPwZuQzuta','+918126263569',NULL,'ava0017jb@gmail.com','1990-12-28',NULL,'user.png','member',NULL,NULL,0,1,NULL,'0000-00-00 00:00:00','2017-06-18 13:57:04'),(14,NULL,'active','0','admin','admin','$2y$10$E.1v6NFf01DCfklNiBzinuIOTlYDhI/BZLUmVWawUjP5wO/1c4a9S','+918126263569',NULL,'ava00117jb@gmail.com','1990-12-28',NULL,'user.png','member',NULL,NULL,0,1,NULL,'0000-00-00 00:00:00','2017-06-18 13:57:04'),(15,NULL,'active','0','ssss','admin','$2y$10$Tk2FQ5Wq8qYXwvFovXC2L.LLtSVRQoajfE157/deIxQkRxD6Cp5BK','+918126243569',NULL,'ava001117jb@gmail.com','1990-12-28',NULL,'user.png','pharmasist',NULL,NULL,0,1,NULL,'0000-00-00 00:00:00','2017-06-18 13:57:04'),(16,NULL,'active','0','admin','admin','$2y$10$k3VN10LRpvWnXnAKMajr5.t/iy5hrSorCzeP2c/Ps900iLvEkQTAW','+918126863569',NULL,'ava0tt07jb@gmail.com','1990-12-28',NULL,'user.png','member',NULL,NULL,0,1,1,'2017-07-01 20:00:24','2017-07-01 20:00:24'),(17,NULL,'active','0','admin','admin','$2y$10$9a0K0OTPU/aCXwiwJno13.5gCx26kPKlzrpnWEm/DFhXq9Ofk4tR6','+918826863569',NULL,'ava0stt07jb@gmail.com','1990-12-28',NULL,'user.png','member','$2y$10$s2KuNt.wZhOBWWNke1NGeO0DDBxhDr6JkYDhYUkKuG4kPCPXLY2oC',NULL,0,1,1,'2017-07-16 21:26:19','2017-07-16 21:26:19');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
