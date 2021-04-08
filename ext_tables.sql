CREATE TABLE fe_users (
	reputation int(11) DEFAULT '0' NOT NULL,
    visited_by int(11) unsigned DEFAULT '0' NOT NULL,
	reported_by int(11) unsigned DEFAULT '0' NOT NULL,
	awards int(11) unsigned DEFAULT '0' NOT NULL,
	activity int(11) unsigned DEFAULT '0' NOT NULL,
    allowed_activity text
);

CREATE TABLE tx_akforum_domain_model_forum (
	forum_title varchar(255) DEFAULT '' NOT NULL,
    description text,
    forum_image int(11) unsigned DEFAULT '0' NOT NULL,
    forum_icon varchar(255) DEFAULT '' NOT NULL,
	topics_rel int(11) unsigned DEFAULT '0' NOT NULL,
	hmac varchar(255) DEFAULT '' NOT NULL
);
CREATE TABLE tx_akforum_domain_model_topic (
	forum int(11) unsigned DEFAULT '0' NOT NULL,
	name varchar(255) DEFAULT '' NOT NULL,
	slug varchar(255) DEFAULT '' NOT NULL,
	description text,
	themas_rel int(11) unsigned DEFAULT '0' NOT NULL,
	hmac varchar(255) DEFAULT '' NOT NULL,
    topic_image int(11) unsigned DEFAULT '0' NOT NULL,
    topic_icon varchar(255) DEFAULT '' NOT NULL
);
CREATE TABLE tx_akforum_domain_model_thema (
	topic int(11) unsigned DEFAULT '0' NOT NULL,
	name varchar(255) DEFAULT '' NOT NULL,
	description text,
	slug varchar(255) DEFAULT '' NOT NULL,
	posts_rel int(11) unsigned DEFAULT '0' NOT NULL,
	followers int(11) unsigned DEFAULT '0' NOT NULL,
	hmac varchar(255) DEFAULT '' NOT NULL,
    thema_image int(11) unsigned DEFAULT '0' NOT NULL,
    thema_icon varchar(255) DEFAULT '' NOT NULL
);
CREATE TABLE tx_akforum_domain_model_post (
	thema int(11) unsigned DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	slug varchar(255) DEFAULT '' NOT NULL,
	pinned tinyint DEFAULT 0,
	featured tinyint DEFAULT 0,
	body text,
	views int(11) unsigned DEFAULT '0' NOT NULL,
	reply_rel int(11) unsigned DEFAULT '0' NOT NULL,
    reaction_rel int(11) unsigned DEFAULT '0' NOT NULL,
	reported_by int(11) unsigned DEFAULT '0' NOT NULL,
	upvoted_by int(11) unsigned DEFAULT '0' NOT NULL,
	downvoted_by int(11) unsigned DEFAULT '0' NOT NULL,
	followers int(11) unsigned DEFAULT '0' NOT NULL,
	created_by int(11) unsigned DEFAULT '0' NOT NULL,
	can_edit SMALLINT DEFAULT 0,
	can_delete SMALLINT DEFAULT 0,
	times_edited int(11) DEFAULT '0' NOT NULL,
	hmac varchar(255) DEFAULT '' NOT NULL
);
CREATE TABLE tx_akforum_domain_model_reply (
	post int(11) unsigned DEFAULT '0' NOT NULL,
	body text,
    reaction_rel int(11) unsigned DEFAULT '0' NOT NULL,
	upvoted_by int(11) unsigned DEFAULT '0' NOT NULL,
	downvoted_by int(11) unsigned DEFAULT '0' NOT NULL,
	reported_by int(11) unsigned DEFAULT '0' NOT NULL,
	user int(11) unsigned DEFAULT '0' NOT NULL,
	created_by int(11) unsigned DEFAULT '0' NOT NULL,
	can_edit SMALLINT DEFAULT 0,
	can_delete SMALLINT DEFAULT 0,
	times_edited int(11) DEFAULT '0' NOT NULL,
	hmac varchar(255) DEFAULT '' NOT NULL
);
CREATE TABLE tx_akforum_domain_model_category (
    name varchar(255) DEFAULT '' NOT NULL,
    class varchar(255) DEFAULT '' NOT NULL
);
CREATE TABLE tx_akforum_domain_model_award (
    name varchar(255) DEFAULT '' NOT NULL,
    level int(11) unsigned DEFAULT '0' NOT NULL,
    reason int(11) unsigned DEFAULT '0' NOT NULL,
    amount int(11) DEFAULT '0' NOT NULL,
    image int(11) unsigned DEFAULT '0' NOT NULL,
    icon varchar(255) DEFAULT '' NOT NULL,
    reputation varchar(255) DEFAULT '' NOT NULL
);
CREATE TABLE tx_akforum_domain_model_awardlevel(
    name varchar(255) DEFAULT '' NOT NULL
);
CREATE TABLE tx_akforum_domain_model_topic (
	forum int(11) unsigned DEFAULT '0' NOT NULL
);
CREATE TABLE tx_akforum_domain_model_thema (
	topic int(11) unsigned DEFAULT '0' NOT NULL
);
CREATE TABLE tx_akforum_domain_model_post (
	thema int(11) unsigned DEFAULT '0' NOT NULL
);
CREATE TABLE tx_akforum_thema_frontenduser_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);
CREATE TABLE tx_akforum_domain_model_reply (
	post int(11) unsigned DEFAULT '0' NOT NULL
);

CREATE TABLE tx_akforum_domain_model_activity (
    title varchar(255) DEFAULT '' NOT NULL,
    subtitle varchar(255) DEFAULT '' NOT NULL,
    template varchar(255) DEFAULT '' NOT NULL,
    user int(11) unsigned DEFAULT '0' NOT NULL,
    foreign_user int(11) unsigned DEFAULT '0' NOT NULL,
    reply int(11) unsigned DEFAULT '0' NOT NULL,
    post int(11) unsigned DEFAULT '0' NOT NULL,
    thema int(11) unsigned DEFAULT '0' NOT NULL,
    topic int(11) unsigned DEFAULT '0' NOT NULL,
    vote_action varchar(255) DEFAULT '' NOT NULL,
    reaction int(11) unsigned DEFAULT '0' NOT NULL
);
CREATE TABLE tx_akforum_domain_model_reactionrel (
    reaction int(11) unsigned DEFAULT '0' NOT NULL,
    user int(11) unsigned DEFAULT '0' NOT NULL,
    post int(11) unsigned DEFAULT '0' NOT NULL,
    reply int(11) unsigned DEFAULT '0' NOT NULL
);
CREATE TABLE tx_akforum_domain_model_reaction (
    name varchar(255) DEFAULT '' NOT NULL,
    reaction_icon varchar(255) DEFAULT '' NOT NULL,
    reaction_image int(11) unsigned DEFAULT '0' NOT NULL,
    reputation int(11) DEFAULT '0'
);

CREATE TABLE tx_akforum_post_upvotedby_frontenduser_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);
CREATE TABLE tx_akforum_post_downvotedby_frontenduser_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);
CREATE TABLE tx_akforum_post_views_frontenduser_mm (
    uid_local int(11) unsigned DEFAULT '0' NOT NULL,
    uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
    sorting int(11) unsigned DEFAULT '0' NOT NULL,
    sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid_local,uid_foreign),
    KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign)
);
CREATE TABLE tx_akforum_post_followers_frontenduser_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);
CREATE TABLE tx_akforum_post_createdby_frontenduser_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);
CREATE TABLE tx_akforum_reply_frontenduser_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);
CREATE TABLE tx_akforum_reply_upvotedby_frontenduser_mm (
     uid_local int(11) unsigned DEFAULT '0' NOT NULL,
     uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
     sorting int(11) unsigned DEFAULT '0' NOT NULL,
     sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

     PRIMARY KEY (uid_local,uid_foreign),
     KEY uid_local (uid_local),
     KEY uid_foreign (uid_foreign)
);
CREATE TABLE tx_akforum_reply_downvotedby_frontenduser_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);
CREATE TABLE tx_akforum_reply_reportedby_frontenduser_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);
CREATE TABLE tx_akforum_reply_user_frontenduser_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);
CREATE TABLE tx_akforum_frontenduser_frontenduser_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);
CREATE TABLE tx_akforum_visitedby_frontenduser_mm (
    uid_local int(11) unsigned DEFAULT '0' NOT NULL,
    uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
    sorting int(11) unsigned DEFAULT '0' NOT NULL,
    sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid_local,uid_foreign),
    KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign)
);
CREATE TABLE tx_akforum_award_frontenduser_mm (
    uid_local int(11) unsigned DEFAULT '0' NOT NULL,
    uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
    sorting int(11) unsigned DEFAULT '0' NOT NULL,
    sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid_local,uid_foreign),
    KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign)
);