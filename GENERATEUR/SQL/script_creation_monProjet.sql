DROP DATABASE IF EXISTS generateurBuldo ;
CREATE DATABASE IF NOT EXISTS generateurBuldo DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci ;
USE generateurBuldo;

#----------------------------------------------------------------
# table Entite
#----------------------------------------------------------------

CREATE TABLE Entite(
	idEntite INT AUTO_INCREMENT  PRIMARY KEY NOT NULL,
	nomEntite Varchar(50)   NOT NULL,
	idRelation Int   NULL,
	idCoordonnee Int   NULL,
	idProjet Int NOT NULL
)ENGINE=InnoDB CHARSET = UTF8 COLLATE utf8_general_ci;

#----------------------------------------------------------------
# table Attribut
#----------------------------------------------------------------

CREATE TABLE Attribut(
	idAttribut INT AUTO_INCREMENT  PRIMARY KEY NOT NULL,
	nomAttribut Varchar(50)   NOT NULL,
	longueurAttribut Varchar(50)  NULL,
	nullAttribut VARCHAR(10)  NOT NULL,
	LibelleLabelAttribut Varchar(50)   NOT NULL,
	idCategorie Int   NOT NULL,
	idType Int   NOT NULL,
	idCoordonnee Int   NOT NULL,
	idEntite Int   NOT NULL
)ENGINE=InnoDB CHARSET = UTF8 COLLATE utf8_general_ci;

#----------------------------------------------------------------
# table Type
#----------------------------------------------------------------

CREATE TABLE Type(
	idType INT AUTO_INCREMENT  PRIMARY KEY NOT NULL,
	libelleType Varchar(50)   NOT NULL
)ENGINE=InnoDB CHARSET = UTF8 COLLATE utf8_general_ci;

#----------------------------------------------------------------
# table Coordonnee
#----------------------------------------------------------------

CREATE TABLE Coordonnee(
	idCoordonnee INT AUTO_INCREMENT  PRIMARY KEY NOT NULL,
	xCoordonnee Float   NOT NULL,
	yCoordonnee Float   NOT NULL
)ENGINE=InnoDB CHARSET = UTF8 COLLATE utf8_general_ci;

#----------------------------------------------------------------
# table Categorie
#----------------------------------------------------------------

CREATE TABLE Categorie(
	idCategorie INT AUTO_INCREMENT  PRIMARY KEY NOT NULL,
	libelleCategorie Varchar(50)   NOT NULL
)ENGINE=InnoDB CHARSET = UTF8 COLLATE utf8_general_ci;

#----------------------------------------------------------------
# table Cardinalite
#----------------------------------------------------------------

CREATE TABLE Cardinalite(
	idCardinalite INT AUTO_INCREMENT  PRIMARY KEY NOT NULL,
	libelleCardinalite Varchar(50)   NULL,
	idEntite Int   NOT NULL,
	idTypeCardinalite Int   NOT NULL,
	idRelation Int   NOT NULL
)ENGINE=InnoDB CHARSET = UTF8 COLLATE utf8_general_ci;

#----------------------------------------------------------------
# table Relation
#----------------------------------------------------------------

CREATE TABLE Relation(
	idRelation INT AUTO_INCREMENT  PRIMARY KEY NOT NULL,
	libelleRelation Varchar(50)   NOT NULL,
	idCoordonnee Int   NOT NULL
)ENGINE=InnoDB CHARSET = UTF8 COLLATE utf8_general_ci;

#----------------------------------------------------------------
# table TypeCardinalite
#----------------------------------------------------------------

CREATE TABLE TypeCardinalite(
	idTypeCardinalite INT AUTO_INCREMENT  PRIMARY KEY NOT NULL,
	libelleTypeCardinalite Varchar(50)   NOT NULL
)ENGINE=InnoDB CHARSET = UTF8 COLLATE utf8_general_ci;

#----------------------------------------------------------------
# table projet
#----------------------------------------------------------------

CREATE TABLE projet(
	idProjet INT AUTO_INCREMENT  PRIMARY KEY NOT NULL,
	nomProjet Varchar(50)   NOT NULL
)ENGINE=InnoDB CHARSET = UTF8 COLLATE utf8_general_ci;

ALTER TABLE Entite ADD CONSTRAINT FK_Entite_Relation FOREIGN KEY (idRelation) REFERENCES Relation(idRelation);
ALTER TABLE Entite ADD CONSTRAINT FK_Entite_Coordonnee FOREIGN KEY (idCoordonnee) REFERENCES Coordonnee(idCoordonnee);
ALTER TABLE Attribut ADD CONSTRAINT FK_Attribut_Categorie FOREIGN KEY (idCategorie) REFERENCES Categorie(idCategorie);
ALTER TABLE Attribut ADD CONSTRAINT FK_Attribut_Type FOREIGN KEY (idType) REFERENCES Type(idType);
ALTER TABLE Attribut ADD CONSTRAINT FK_Attribut_Coordonnee FOREIGN KEY (idCoordonnee) REFERENCES Coordonnee(idCoordonnee);
ALTER TABLE Attribut ADD CONSTRAINT FK_Attribut_Entite FOREIGN KEY (idEntite) REFERENCES Entite(idEntite);
ALTER TABLE Cardinalite ADD CONSTRAINT FK_Cardinalite_Entite FOREIGN KEY (idEntite) REFERENCES Entite(idEntite);
ALTER TABLE Cardinalite ADD CONSTRAINT FK_Cardinalite_TypeCardinalite FOREIGN KEY (idTypeCardinalite) REFERENCES TypeCardinalite(idTypeCardinalite);
ALTER TABLE Cardinalite ADD CONSTRAINT FK_Cardinalite_Relation FOREIGN KEY (idRelation) REFERENCES Relation(idRelation);
ALTER TABLE Relation ADD CONSTRAINT FK_Relation_Coordonnee FOREIGN KEY (idCoordonnee) REFERENCES Coordonnee(idCoordonnee);
ALTER TABLE Entite ADD CONSTRAINT FK_entite_projet FOREIGN KEY (idProjet) REFERENCES Projet(idProjet);

INSERT INTO `categorie` (`idCategorie`, `libelleCategorie`) VALUES
(1, 'PRIMARY KEY'),(2, 'ATTRIBUT SIMPLE'),(3, 'UNIQUE'),(4, 'INDEX');

INSERT INTO `type` (`idType`, `libelleType`) VALUES
(1, 'VARCHAR'),(2, 'DATE'),(3, 'BOOL'),(4, 'INT');

INSERT INTO `typecardinalite` (`idTypeCardinalite`, `libelleTypeCardinalite`) VALUES 
(NULL, '0,1'), (NULL, '1,1'), (NULL, '0,n'), (NULL, '1,n');