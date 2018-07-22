
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
DROP TABLE IF EXISTS `wpjj_postmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpjj_postmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wpjj_postmeta` WRITE;
/*!40000 ALTER TABLE `wpjj_postmeta` DISABLE KEYS */;
INSERT INTO `wpjj_postmeta` VALUES (1,2,'_wp_page_template','default'),(2,3,'_wp_page_template','default'),(7,7,'_vc_post_settings','a:1:{s:10:\"vc_grid_id\";a:0:{}}'),(8,8,'_vc_post_settings','a:1:{s:10:\"vc_grid_id\";a:0:{}}'),(9,9,'_vc_post_settings','a:1:{s:10:\"vc_grid_id\";a:0:{}}'),(10,10,'_vc_post_settings','a:1:{s:10:\"vc_grid_id\";a:0:{}}'),(11,11,'_vc_post_settings','a:1:{s:10:\"vc_grid_id\";a:0:{}}'),(12,12,'_vc_post_settings','a:1:{s:10:\"vc_grid_id\";a:0:{}}'),(13,13,'_vc_post_settings','a:1:{s:10:\"vc_grid_id\";a:0:{}}'),(14,14,'_vc_post_settings','a:1:{s:10:\"vc_grid_id\";a:0:{}}'),(15,15,'_vc_post_settings','a:1:{s:10:\"vc_grid_id\";a:0:{}}'),(16,16,'_vc_post_settings','a:1:{s:10:\"vc_grid_id\";a:0:{}}'),(17,17,'_vc_post_settings','a:1:{s:10:\"vc_grid_id\";a:0:{}}'),(18,18,'_vc_post_settings','a:1:{s:10:\"vc_grid_id\";a:0:{}}'),(19,19,'_vc_post_settings','a:1:{s:10:\"vc_grid_id\";a:0:{}}'),(20,20,'_vc_post_settings','a:1:{s:10:\"vc_grid_id\";a:0:{}}'),(21,21,'_vc_post_settings','a:1:{s:10:\"vc_grid_id\";a:0:{}}'),(22,22,'_vc_post_settings','a:1:{s:10:\"vc_grid_id\";a:0:{}}'),(23,23,'_vc_post_settings','a:1:{s:10:\"vc_grid_id\";a:0:{}}'),(24,1,'_oembed_7cc581ac884561d7519033d79abd7536','<iframe src=\"https://player.vimeo.com/video/270507360?app_id=122963\" width=\"840\" height=\"473\" frameborder=\"0\" title=\"BuddyPress 3.0 - Nouveau template pack\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>'),(25,1,'_oembed_time_7cc581ac884561d7519033d79abd7536','1532259610');
/*!40000 ALTER TABLE `wpjj_postmeta` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

