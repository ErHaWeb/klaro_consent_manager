#
# Table structure for table 'tx_klaroconsentmanager_configuration'
#
CREATE TABLE tx_klaroconsentmanager_configuration
(
    `title`                        tinytext,
    `testing`                      tinyint(4)       DEFAULT '0'  NOT NULL,
    `config_variable_name`         varchar(255)     DEFAULT ''   NOT NULL,
    `append_show_button`           tinyint(4)       DEFAULT '0'  NOT NULL,
    `append_reset_button`          tinyint(4)       DEFAULT '0'  NOT NULL,
    `element_i_d`                  varchar(255)     DEFAULT ''   NOT NULL,
    `additional_class`             tinytext,
    `style_prefix`                 varchar(255)     DEFAULT ''   NOT NULL,
    `storage_method`               varchar(255)     DEFAULT ''   NOT NULL,
    `storage_name`                 varchar(255)     DEFAULT ''   NOT NULL,
    `cookie_domain`                varchar(255)     DEFAULT ''   NOT NULL,
    `cookie_path`                  varchar(255)     DEFAULT ''   NOT NULL,
    `html_texts`                   tinyint(4)       DEFAULT '0'  NOT NULL,
    `embedded`                     tinyint(4)       DEFAULT '0'  NOT NULL,
    `group_by_purpose`             tinyint(4)       DEFAULT '1'  NOT NULL,
    `cookie_expires_after_days`    int(11) unsigned DEFAULT '0'  NOT NULL,
    `default`                      tinyint(4)       DEFAULT '0'  NOT NULL,
    `must_consent`                 tinyint(4)       DEFAULT '0'  NOT NULL,
    `accept_all`                   tinyint(4)       DEFAULT '1'  NOT NULL,
    `hide_decline_all`             tinyint(4)       DEFAULT '0'  NOT NULL,
    `hide_learn_more`              tinyint(4)       DEFAULT '0'  NOT NULL,
    `hide_toggle_all`              tinyint(4)       DEFAULT '0'  NOT NULL,
    `notice_as_modal`              tinyint(4)       DEFAULT '0'  NOT NULL,
    `no_notice`                    tinyint(4)       DEFAULT '0'  NOT NULL,
    `show_notice_title`            tinyint(4)       DEFAULT '1'  NOT NULL,
    `show_description_empty_store` tinyint(4)       DEFAULT '1'  NOT NULL,
    `auto_focus`                   tinyint(4)       DEFAULT '1'  NOT NULL,
    `disable_powered_by`           tinyint(4)       DEFAULT '0'  NOT NULL,
    `powered_by`                   varchar(255)     DEFAULT ''   NOT NULL,
    `purpose_order`                varchar(255)     DEFAULT ''   NOT NULL,
    `no_auto_load`                 tinyint(4)       DEFAULT '0'  NOT NULL,
    `color_scheme`                 tinytext,
    `alignment`                    tinytext,
    `callback`                     mediumtext,
    `fluidtemplate_rootpath`       varchar(255)     DEFAULT '''' NOT NULL,
    `locallang_path`               varchar(255)     DEFAULT ''   NOT NULL,
    `services`                     varchar(255)     DEFAULT ''   NOT NULL
);

#
# Table structure for table 'tx_klaroconsentmanager_service'
#
CREATE TABLE tx_klaroconsentmanager_service
(
    `title`                   tinytext,
    `name`                    tinytext,
    `default`                 tinyint(4)       DEFAULT '0' NOT NULL,
    `purposes`                varchar(255)     DEFAULT ''  NOT NULL,
    `cookies`                 int(11) unsigned DEFAULT '0' NOT NULL,
    `callback`                mediumtext,
    `required`                tinyint(4)       DEFAULT '0' NOT NULL,
    `opt_out`                 tinyint(4)       DEFAULT '0' NOT NULL,
    `only_once`               tinyint(4)       DEFAULT '0' NOT NULL,
    `contextual_consent_only` tinyint(4)       DEFAULT '0' NOT NULL,
    `on_accept`               mediumtext,
    `on_init`                 mediumtext,
    `on_decline`              mediumtext,
    `vars`                    mediumtext
);

#
# Table structure for table 'tx_klaroconsentmanager_cookie'
#
CREATE TABLE tx_klaroconsentmanager_cookie
(
    `identifier`         tinytext,
    `title`              tinytext,
    `pattern`            tinytext,
    `path`               tinytext,
    `domain`             tinytext,
    `expires_after`      int(11) unsigned DEFAULT '0' NOT NULL,
    `expires_after_unit` tinytext,
    `parentid`           int(11)          DEFAULT '0' NOT NULL,
    `parenttable`        varchar(255)     DEFAULT ''  NOT NULL
);

#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content
(
    `tx_klaroconsentmanager_service` tinytext
);