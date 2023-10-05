#
# Table structure for table 'tx_klaroconsentmanager_configuration'
#
CREATE TABLE tx_klaroconsentmanager_configuration
(
	`title`                     tinytext,
	`testing`                   tinyint(4)       DEFAULT '0' NOT NULL,
	`element_id`                varchar(255)     DEFAULT ''  NOT NULL,
	`storage_method`            varchar(255)     DEFAULT ''  NOT NULL,
	`storage_name`              varchar(255)     DEFAULT ''  NOT NULL,
	`html_texts`                tinyint(4)       DEFAULT '0' NOT NULL,
	`cookie_domain`             varchar(255)     DEFAULT ''  NOT NULL,
	`cookie_expires_after_days` int(11) unsigned DEFAULT '0' NOT NULL,
	`default`                   tinyint(4)       DEFAULT '0' NOT NULL,
	`must_consent`              tinyint(4)       DEFAULT '0' NOT NULL,
	`accept_all`                tinyint(4)       DEFAULT '0' NOT NULL,
	`hide_decline_all`          tinyint(4)       DEFAULT '0' NOT NULL,
	`hide_learn_more`           tinyint(4)       DEFAULT '0' NOT NULL,
	`services`                  int(11) unsigned DEFAULT '0' NOT NULL,
	`callback`                  mediumtext,
	`locallang_path`            varchar(255)     DEFAULT ''  NOT NULL
);

#
# Table structure for table 'tx_klaroconsentmanager_service'
#
CREATE TABLE tx_klaroconsentmanager_service
(
	`name`                    tinytext,
	`default`                 tinyint(4)       DEFAULT '0' NOT NULL,
	`purposes`                varchar(255)     DEFAULT ''  NOT NULL,
	`cookies`                 int(11) unsigned DEFAULT '0' NOT NULL,
	`callback`                mediumtext,
	`required`                tinyint(4)       DEFAULT '0' NOT NULL,
	`opt_out`                 tinyint(4)       DEFAULT '0' NOT NULL,
	`only_once`               tinyint(4)       DEFAULT '0' NOT NULL,
	`contextual_consent_only` tinyint(4)       DEFAULT '0' NOT NULL,
	`parentid`                int(11)          DEFAULT '0' NOT NULL,
	`parenttable`             varchar(255)     DEFAULT ''  NOT NULL
);

#
# Table structure for table 'tx_klaroconsentmanager_cookie'
#
CREATE TABLE tx_klaroconsentmanager_cookie
(
	`name`          tinytext,
	`path`          tinytext,
	`cookie_domain` tinytext,
	`parentid`      int(11)      DEFAULT '0' NOT NULL,
	`parenttable`   varchar(255) DEFAULT ''  NOT NULL
);
