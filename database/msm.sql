/*
SQLyog Ultimate - MySQL GUI v8.21 
MySQL - 5.7.18-0ubuntu0.16.04.1 : Database - msm
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `permission` */

DROP TABLE IF EXISTS `permission`;

CREATE TABLE `permission` (
  `id` int(122) unsigned NOT NULL AUTO_INCREMENT,
  `user_type` varchar(250) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `permission` */

LOCK TABLES `permission` WRITE;

insert  into `permission`(`id`,`user_type`,`data`) values (1,'Member','{\"users\":{\"own_create\":\"1\",\"own_read\":\"1\",\"own_update\":\"1\",\"own_delete\":\"1\"}}'),(2,'Admin','{\"users\":{\"own_create\":\"1\",\"own_read\":\"1\",\"own_update\":\"1\",\"own_delete\":\"1\",\"all_create\":\"1\",\"all_read\":\"1\",\"all_update\":\"1\",\"all_delete\":\"1\"}}'),(3,'Pharmasist',NULL);

UNLOCK TABLES;

/*Table structure for table `setting` */

DROP TABLE IF EXISTS `setting`;

CREATE TABLE `setting` (
  `id` int(122) unsigned NOT NULL AUTO_INCREMENT,
  `keys` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

/*Data for the table `setting` */

LOCK TABLES `setting` WRITE;

insert  into `setting`(`id`,`keys`,`value`) values (1,'website','MQ Staff Web'),(2,'logo','logo.png'),(3,'favicon','favicon.ico'),(4,'SMTP_EMAIL',''),(5,'HOST',''),(6,'PORT',''),(7,'SMTP_SECURE',''),(8,'SMTP_PASSWORD',''),(9,'mail_setting','simple_mail'),(10,'company_name','Company Name'),(11,'crud_list','users,User'),(12,'EMAIL',''),(13,'UserModules','yes'),(14,'register_allowed','1'),(15,'email_invitation','1'),(16,'admin_approval','0'),(17,'user_type','[\"Member\"]');

UNLOCK TABLES;

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

LOCK TABLES `templates` WRITE;

insert  into `templates`(`id`,`module`,`code`,`template_name`,`html`) values (1,'forgot_pass','forgot_password','Forgot password','<html xmlns=\"http://www.w3.org/1999/xhtml\"><head>\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\r\n  <style type=\"text/css\" rel=\"stylesheet\" media=\"all\">\r\n    /* Base ------------------------------ */\r\n    *:not(br):not(tr):not(html) {\r\n      font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif;\r\n      -webkit-box-sizing: border-box;\r\n      box-sizing: border-box;\r\n    }\r\n    body {\r\n      \r\n    }\r\n    a {\r\n      color: #3869D4;\r\n    }\r\n\r\n\r\n    /* Masthead ----------------------- */\r\n    .email-masthead {\r\n      padding: 25px 0;\r\n      text-align: center;\r\n    }\r\n    .email-masthead_logo {\r\n      max-width: 400px;\r\n      border: 0;\r\n    }\r\n    .email-footer {\r\n      width: 570px;\r\n      margin: 0 auto;\r\n      padding: 0;\r\n      text-align: center;\r\n    }\r\n    .email-footer p {\r\n      color: #AEAEAE;\r\n    }\r\n  \r\n    .content-cell {\r\n      padding: 35px;\r\n    }\r\n    .align-right {\r\n      text-align: right;\r\n    }\r\n\r\n    /* Type ------------------------------ */\r\n    h1 {\r\n      margin-top: 0;\r\n      color: #2F3133;\r\n      font-size: 19px;\r\n      font-weight: bold;\r\n      text-align: left;\r\n    }\r\n    h2 {\r\n      margin-top: 0;\r\n      color: #2F3133;\r\n      font-size: 16px;\r\n      font-weight: bold;\r\n      text-align: left;\r\n    }\r\n    h3 {\r\n      margin-top: 0;\r\n      color: #2F3133;\r\n      font-size: 14px;\r\n      font-weight: bold;\r\n      text-align: left;\r\n    }\r\n    p {\r\n      margin-top: 0;\r\n      color: #74787E;\r\n      font-size: 16px;\r\n      line-height: 1.5em;\r\n      text-align: left;\r\n    }\r\n    p.sub {\r\n      font-size: 12px;\r\n    }\r\n    p.center {\r\n      text-align: center;\r\n    }\r\n\r\n    /* Buttons ------------------------------ */\r\n    .button {\r\n      display: inline-block;\r\n      width: 200px;\r\n      background-color: #3869D4;\r\n      border-radius: 3px;\r\n      color: #ffffff;\r\n      font-size: 15px;\r\n      line-height: 45px;\r\n      text-align: center;\r\n      text-decoration: none;\r\n      -webkit-text-size-adjust: none;\r\n      mso-hide: all;\r\n    }\r\n    .button--green {\r\n      background-color: #22BC66;\r\n    }\r\n    .button--red {\r\n      background-color: #dc4d2f;\r\n    }\r\n    .button--blue {\r\n      background-color: #3869D4;\r\n    }\r\n  </style>\r\n</head>\r\n<body style=\"width: 100% !important;\r\n      height: 100%;\r\n      margin: 0;\r\n      line-height: 1.4;\r\n      background-color: #F2F4F6;\r\n      color: #74787E;\r\n      -webkit-text-size-adjust: none;\">\r\n  <table class=\"email-wrapper\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"\r\n    width: 100%;\r\n    margin: 0;\r\n    padding: 0;\">\r\n    <tbody><tr>\r\n      <td align=\"center\">\r\n        <table class=\"email-content\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%;\r\n      margin: 0;\r\n      padding: 0;\">\r\n          <!-- Logo -->\r\n\r\n          <tbody>\r\n          <!-- Email Body -->\r\n          <tr>\r\n            <td class=\"email-body\" width=\"100%\" style=\"width: 100%;\r\n    margin: 0;\r\n    padding: 0;\r\n    border-top: 1px solid #edeef2;\r\n    border-bottom: 1px solid #edeef2;\r\n    background-color: #edeef2;\">\r\n              <table class=\"email-body_inner\" align=\"center\" width=\"570\" cellpadding=\"0\" cellspacing=\"0\" style=\" width: 570px;\r\n    margin:  14px auto;\r\n    background: #fff;\r\n    padding: 0;\r\n    border: 1px outset rgba(136, 131, 131, 0.26);\r\n    box-shadow: 0px 6px 38px rgb(0, 0, 0);\r\n       \">\r\n                <!-- Body content -->\r\n                <thead style=\"background: #3869d4;\"><tr><th><div align=\"center\" style=\"padding: 15px; color: #000;\"><a href=\"{var_action_url}\" class=\"email-masthead_name\" style=\"font-size: 16px;\r\n      font-weight: bold;\r\n      color: #bbbfc3;\r\n      text-decoration: none;\r\n      text-shadow: 0 1px 0 white;\">{var_sender_name}</a></div></th></tr>\r\n                </thead>\r\n                <tbody><tr>\r\n                  <td class=\"content-cell\" style=\"padding: 35px;\">\r\n                    <h1>Hi {var_user_name},</h1>\r\n                    <p>You recently requested to reset your password for your {var_website_name} account. Click the button below to reset it.</p>\r\n                    <!-- Action -->\r\n                    <table class=\"body-action\" align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"\r\n      width: 100%;\r\n      margin: 30px auto;\r\n      padding: 0;\r\n      text-align: center;\">\r\n                      <tbody><tr>\r\n                        <td align=\"center\">\r\n                          <div>\r\n                            <!--[if mso]><v:roundrect xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:w=\"urn:schemas-microsoft-com:office:word\" href=\"{{var_action_url}}\" style=\"height:45px;v-text-anchor:middle;width:200px;\" arcsize=\"7%\" stroke=\"f\" fill=\"t\">\r\n                              <v:fill type=\"tile\" color=\"#dc4d2f\" />\r\n                              <w:anchorlock/>\r\n                              <center style=\"color:#ffffff;font-family:sans-serif;font-size:15px;\">Reset your password</center>\r\n                            </v:roundrect><![endif]-->\r\n                            <a href=\"{var_verification_link}\" class=\"button button--red\" style=\"background-color: #dc4d2f;display: inline-block;\r\n      width: 200px;\r\n      background-color: #3869D4;\r\n      border-radius: 3px;\r\n      color: #ffffff;\r\n      font-size: 15px;\r\n      line-height: 45px;\r\n      text-align: center;\r\n      text-decoration: none;\r\n      -webkit-text-size-adjust: none;\r\n      mso-hide: all;\">Reset your password</a>\r\n                          </div>\r\n                        </td>\r\n                      </tr>\r\n                    </tbody></table>\r\n                    <p>If you did not request a password reset, please ignore this email or reply to let us know.</p>\r\n                    <p>Thanks,<br>{var_sender_name} and the {var_website_name} Team</p>\r\n                   <!-- Sub copy -->\r\n                    <table class=\"body-sub\" style=\"margin-top: 25px;\r\n      padding-top: 25px;\r\n      border-top: 1px solid #EDEFF2;\">\r\n                      <tbody><tr>\r\n                        <td> \r\n                          <p class=\"sub\" style=\"font-size:12px;\">If you are having trouble clicking the password reset button, copy and paste the URL below into your web browser.</p>\r\n                          <p class=\"sub\"  style=\"font-size:12px;\"><a href=\"{var_verification_link}\">{var_verification_link}</a></p>\r\n                        </td>\r\n                      </tr>\r\n                    </tbody></table>\r\n                  </td>\r\n                </tr>\r\n              </tbody></table>\r\n            </td>\r\n          </tr>\r\n        </tbody></table>\r\n      </td>\r\n    </tr>\r\n  </tbody></table>\r\n\r\n\r\n</body></html>'),(3,'users','invitation','Invitation','<p>Hello <strong>{var_user_email}</strong></p>\r\n\r\n<p>Click below link to register&nbsp;<br />\r\n{var_inviation_link}</p>\r\n\r\n<p>Thanks&nbsp;</p>\r\n');

UNLOCK TABLES;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(121) NOT NULL AUTO_INCREMENT,
  `created_by` varchar(255) DEFAULT NULL,
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
  `user_type` enum('Admin','Pharmasist','Member') DEFAULT NULL,
  `var_otp` int(4) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

LOCK TABLES `users` WRITE;

insert  into `users`(`user_id`,`created_by`,`var_key`,`status`,`is_deleted`,`name`,`lname`,`password`,`mobile_no`,`phone_no`,`email`,`dob`,`address`,`profile_pic`,`user_type`,`var_otp`,`is_verified`) values (1,'1','','active','0','admin',NULL,'$2y$10$/R/ys6rRs86DEiwzDp1kU.2Ix7Hpr8cTyyBDuIpL80YdMXegi7cMW','+918129293159',NULL,'ava007jb@gmail.com','0000-00-00',NULL,'demo_pic.png','Admin',NULL,0),(2,'1',NULL,'active','0','pharmasist',NULL,'$2y$10$0.pVMGrmUNad52skOf3jauzGMBAhr7WoJda53V6WTcgURmZzrJV.2',NULL,NULL,'asd@wer.com','0000-00-00',NULL,'user.png','Pharmasist',NULL,0),(4,'1',NULL,'active','0','7546','456456456','$2y$10$/R/ys6rRs86DEiwzDp1kU.2Ix7Hpr8cTyyBDuIpL80YdMXegi7cMW','456456','456456456','456456','0000-00-00','456456456','user.png','Member',NULL,0),(5,'1',NULL,'active','0','444','456456456','$2y$10$/GJvwhf7spXJVhzwIGmIaeIPnxo/GGgHJZQ3pG.yzJXBEgnI15Um6','4564568','456456456','admin','0000-00-00','66565','user.png','Member',NULL,0),(10,'1',NULL,'active','0','a','A','$2y$10$M59WW/f9.qZU.yKmbHhfWu3TK66P8WFc69Z35sqBdvXqh1r2R2yIW','+911111111111',NULL,'a@s.x','0000-00-00',NULL,'user.png','Member',NULL,0),(11,'1',NULL,'active','0','fff','fff','$2y$10$ZwJx3nqQL8WDfEqJoFtwf.935HnV98104i6UgzQSp.rMkmwges5we','+913333333333',NULL,'f@e.s','0000-00-00',NULL,'user.png','Member',NULL,0),(12,'1',NULL,'active','0','ss','ss','$2y$10$Al7WuYvVy59LTV95Co2T0evKukmaTGlrc/KvQwWyshgTad.i3R052','+913333333335',NULL,'dd@w.d','2017-05-08',NULL,'user.png','Member',NULL,0);

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
