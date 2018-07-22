
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `wpjj_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpjj_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  KEY `slug` (`slug`(191)),
  KEY `name` (`name`(191))
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wpjj_terms` WRITE;
/*!40000 ALTER TABLE `wpjj_terms` DISABLE KEYS */;
INSERT INTO `wpjj_terms` VALUES (1,'Uncategorized','uncategorized',0),(2,'activity-comment','activity-comment',0),(3,'activity-comment-author','activity-comment-author',0),(4,'activity-at-message','activity-at-message',0),(5,'groups-at-message','groups-at-message',0),(6,'core-user-registration','core-user-registration',0),(7,'friends-request','friends-request',0),(8,'friends-request-accepted','friends-request-accepted',0),(9,'groups-details-updated','groups-details-updated',0),(10,'groups-invitation','groups-invitation',0),(11,'groups-member-promoted','groups-member-promoted',0),(12,'groups-membership-request','groups-membership-request',0),(13,'messages-unread','messages-unread',0),(14,'settings-verify-email-change','settings-verify-email-change',0),(15,'groups-membership-request-accepted','groups-membership-request-accepted',0),(16,'groups-membership-request-rejected','groups-membership-request-rejected',0),(17,'simple','simple',0),(18,'grouped','grouped',0),(19,'variable','variable',0),(20,'external','external',0),(21,'exclude-from-search','exclude-from-search',0),(22,'exclude-from-catalog','exclude-from-catalog',0),(23,'featured','featured',0),(24,'outofstock','outofstock',0),(25,'rated-1','rated-1',0),(26,'rated-2','rated-2',0),(27,'rated-3','rated-3',0),(28,'rated-4','rated-4',0),(29,'rated-5','rated-5',0),(30,'Uncategorized','uncategorized',0),(31,'Additional menu for sidebar','additional-menu-for-sidebar',0),(32,'Blog Categories','blog-categories',0),(33,'Footer Menu','footer-menu',0),(34,'Logged In Menu','logged-in-menu',0),(35,'Main menu','main-menu',0),(36,'Top menu','top-menu',0),(37,'Useful Links','useful-links',0);
/*!40000 ALTER TABLE `wpjj_terms` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

