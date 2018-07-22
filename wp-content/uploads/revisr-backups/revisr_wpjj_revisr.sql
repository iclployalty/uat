
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
DROP TABLE IF EXISTS `wpjj_revisr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpjj_revisr` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `message` text COLLATE utf8mb4_unicode_ci,
  `event` varchar(42) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wpjj_revisr` WRITE;
/*!40000 ALTER TABLE `wpjj_revisr` DISABLE KEYS */;
INSERT INTO `wpjj_revisr` VALUES (1,'2018-07-22 11:59:46','Successfully created a new repository.','init','admin'),(2,'2018-07-22 12:00:46','Successfully backed up the database.','backup','admin'),(3,'2018-07-22 12:00:49','There was an error committing the changes to the local repository.','error','admin'),(4,'2018-07-22 12:01:08','There was an error committing the changes to the local repository.','error','admin'),(5,'2018-07-22 12:02:38','Error pushing changes to the remote repository.','error','admin'),(6,'2018-07-22 12:11:21','Successfully backed up the database.','backup','admin'),(7,'2018-07-22 12:11:21','Committed <a href=\"http://uat.compareweb.hosting/wp-admin/admin.php?page=revisr_view_commit&commit=ac73d57&success=true\">#ac73d57</a> to the local repository.','commit','admin'),(8,'2018-07-22 12:11:22','Error pushing changes to the remote repository.','error','admin'),(9,'2018-07-22 12:14:12','Reverted to commit <a href=\"http://uat.compareweb.hosting/wp-admin/admin.php?page=revisr_view_commit&commit=ac73d57\">#ac73d57</a>.','revert','admin'),(10,'2018-07-22 12:59:55','Successfully backed up the database.','backup','admin'),(11,'2018-07-22 12:59:55','There was an error committing the changes to the local repository.','error','admin'),(12,'2018-07-22 13:00:59','Error pushing changes to the remote repository.','error','admin'),(13,'2018-07-22 13:03:09','Successfully backed up the database.','backup','Revisr Bot'),(14,'2018-07-22 13:03:09','The weekly backup was successful.','backup','Revisr Bot'),(15,'2018-07-22 13:03:42','Error pushing changes to the remote repository.','error','admin'),(16,'2018-07-22 13:08:39','Successfully pushed 3 commits to origin/master.','push','admin'),(17,'2018-07-22 13:09:33','There was an error committing the changes to the local repository.','error','admin'),(18,'2018-07-22 13:11:22','There was an error committing the changes to the local repository.','error','admin'),(19,'2018-07-22 15:26:17','Successfully backed up the database.','backup','admin'),(20,'2018-07-22 15:26:28','Successfully pushed 1 commit to origin/master.','push','admin'),(21,'2018-07-22 18:23:52','Successfully backed up the database.','backup','admin'),(22,'2018-07-22 18:23:53','There was an error committing the changes to the local repository.','error','admin');
/*!40000 ALTER TABLE `wpjj_revisr` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

