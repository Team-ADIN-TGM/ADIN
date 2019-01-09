CREATE TABlE ADIN_Users_tbl (
	username VARCHAR(255),
	email VARCHAR(255),
	password VARCHAR(255),
	is_superuser BOOLEAN,
	
	PRIMARY KEY (username)
)ENGINE=INNODB;

ALTER TABLE Domains_tbl
ADD COLUMN delegated_admin VARCHAR(255)
AFTER DomainName;