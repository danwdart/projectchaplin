CREATE TABLE IF NOT EXISTS Chaplin.Users (
    Username    VARCHAR(255) NOT NULL PRIMARY KEY,
    Password    VARCHAR(50) NOT NULL,
    Nick        VARCHAR(30) NOT NULL,
    Email       VARCHAR(255) NOT NULL,
    UserTypeId  TINYINT
);
CREATE TABLE IF NOT EXISTS Chaplin.Users_Credentials (
    CredentialId SMALLINT NOT NULL PRIMARY KEY,
    Username    VARCHAR(255) NOT NULL,
    Type        VARCHAR(10) NOT NULL,
    APIKey      VARCHAR(40) NOT NULL
);
CREATE TABLE IF NOT EXISTS Chaplin.Videos (
    VideoId     VARCHAR(50) NOT NULL PRIMARY KEY,
    TimeCreated DATETIME NOT NULL,
    Username    VARCHAR(255) NOT NULL REFERENCES Users,
    Filename    VARCHAR(255) NOT NULL,
    Thumbnail   VARCHAR(255) NOT NULL,
    Title       VARCHAR(255) NOT NULL,
    Description VARCHAR(255),
    Length      SMALLINT,
    Width       SMALLINT,
    Height      SMALLINT,
    Format      VARCHAR(10),
    Bitrate     SMALLINT,
    Size        INT,
    Views       BIGINT,
    PartialViews BIGINT,
    Bounces     BIGINT,
    Fb_Pos      BIGINT,
    Fb_Neg      BIGINT
);
CREATE TABLE IF NOT EXISTS Chaplin.Videos_Tags (
    VideoId     VARCHAR(50) NOT NULL REFERENCES Videos,
    Tag         VARCHAR(50) NOT NULL,
    PRIMARY KEY (VideoId, Tag)
);
CREATE TABLE IF NOT EXISTS Chaplin.Videos_NotTags (
    VideoId     VARCHAR(50) NOT NULL REFERENCES Videos,
    Tag         VARCHAR(50) NOT NULL,
    PRIMARY KEY (VideoId, Tag)
);
CREATE TABLE IF NOT EXISTS Chaplin.Videos_Comments (
    CommentId   VARCHAR(50) NOT NULL PRIMARY KEY,
    Username    VARCHAR(50) NOT NULL REFERENCES Users,
    Comment     VARCHAR(255) NOT NULL
);
CREATE TABLE IF NOT EXISTS Chaplin.Nodes (
    NodeId      VARCHAR(50) NOT NULL PRIMARY KEY,
    IP          VARCHAR(29) NOT NULL,
    Name        VARCHAR(30) NOT NULL,
    Active      BOOL
);
