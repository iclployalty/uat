
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
DROP TABLE IF EXISTS `wpjj_mailster_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wpjj_mailster_queue` (
  `subscriber_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `campaign_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `requeued` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `added` int(11) unsigned NOT NULL DEFAULT '0',
  `timestamp` int(11) unsigned NOT NULL DEFAULT '0',
  `sent` int(11) unsigned NOT NULL DEFAULT '0',
  `priority` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `count` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `error` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ignore_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `options` varchar(191) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  UNIQUE KEY `id` (`subscriber_id`,`campaign_id`,`requeued`,`options`),
  KEY `subscriber_id` (`subscriber_id`),
  KEY `campaign_id` (`campaign_id`),
  KEY `requeued` (`requeued`),
  KEY `timestamp` (`timestamp`),
  KEY `priority` (`priority`),
  KEY `count` (`count`),
  KEY `error` (`error`),
  KEY `ignore_status` (`ignore_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wpjj_mailster_queue` WRITE;
/*!40000 ALTER TABLE `wpjj_mailster_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `wpjj_mailster_queue` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

