CREATE TABLE ad (
  id          INT NOT NULL AUTO_INCREMENT,
  viewsCount  INT NOT NULL DEFAULT 0,
  clicksCount INT NOT NULL DEFAULT 0,
  site        VARCHAR(100) NOT NULL,
  targetUrl   VARCHAR(200) NOT NULL,

  PRIMARY KEY (id)
);

CREATE TABLE hit (
  id       INT NOT NULL AUTO_INCREMENT,
  adId     INT NOT NULL,
  `date`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ip       INT NOT NULL,
  referrer VARCHAR(255),

  INDEX ad_ind (adId),
  FOREIGN KEY (adId) REFERENCES ad(id) ON DELETE CASCADE,

  PRIMARY KEY (id)
);

CREATE TABLE stat (
  id              INT NOT NULL AUTO_INCREMENT,
  adId            INT NOT NULL,
  periodBeginDate TIMESTAMP NOT NULL,
  periodEndDate   TIMESTAMP NOT NULL,
  viewsCount      INT NOT NULL DEFAULT 0,
  clicksCount     INT NOT NULL DEFAULT 0,

  INDEX ad_ind (adId),
  INDEX period_ind (periodBeginDate, periodEndDate),
  FOREIGN KEY (adId) REFERENCES ad(id) ON DELETE CASCADE,

  PRIMARY KEY (id)
);
