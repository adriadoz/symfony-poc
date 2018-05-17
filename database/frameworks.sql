CREATE SCHEMA `frameworks` DEFAULT CHARACTER SET utf8mb4 ;

use frameworks;
DROP TABLE IF EXISTS `log_summary`;
CREATE TABLE `log_summary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` varchar(255) NOT NULL,
  `total` int(11) NOT NULL,
  `environment` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
