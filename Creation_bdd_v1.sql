#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: Sav
#------------------------------------------------------------

CREATE TABLE Sav(
        id_sav Varchar (20) NOT NULL
	,CONSTRAINT Sav_PK PRIMARY KEY (id_sav)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Panneau_configuration
#------------------------------------------------------------

CREATE TABLE Panneau_configuration(
        id_panneau_configuration Varchar (20) NOT NULL
	,CONSTRAINT Panneau_configuration_PK PRIMARY KEY (id_panneau_configuration)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Administrateur
#------------------------------------------------------------

CREATE TABLE Administrateur(
        id_administrateur        Varchar (20) NOT NULL ,
        id_panneau_configuration Varchar (20) NOT NULL
	,CONSTRAINT Administrateur_PK PRIMARY KEY (id_administrateur)

	,CONSTRAINT Administrateur_Panneau_configuration_FK FOREIGN KEY (id_panneau_configuration) REFERENCES Panneau_configuration(id_panneau_configuration)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Client
#------------------------------------------------------------

CREATE TABLE Client(
        id_client                Varchar (20) NOT NULL ,
        autorisation_connexion   Bool NOT NULL ,
        id_panneau_configuration Varchar (20) NOT NULL
	,CONSTRAINT Client_PK PRIMARY KEY (id_client)

	,CONSTRAINT Client_Panneau_configuration_FK FOREIGN KEY (id_panneau_configuration) REFERENCES Panneau_configuration(id_panneau_configuration)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Application_mobile
#------------------------------------------------------------

CREATE TABLE Application_mobile(
        id_application Varchar (20) NOT NULL ,
        id_client      Varchar (20)
	,CONSTRAINT Application_mobile_PK PRIMARY KEY (id_application)

	,CONSTRAINT Application_mobile_Client_FK FOREIGN KEY (id_client) REFERENCES Client(id_client)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Allergene
#------------------------------------------------------------

CREATE TABLE Allergene(
        id_allegerne Varchar (20) NOT NULL ,
        nom          Varchar (20) NOT NULL ,
        description  Varchar (200) NOT NULL
	,CONSTRAINT Allergene_PK PRIMARY KEY (id_allegerne)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Liste_allergene_personelle
#------------------------------------------------------------

CREATE TABLE Liste_allergene_personelle(
        id_liste_allergenes Varchar (20) NOT NULL
	,CONSTRAINT Liste_allergene_personelle_PK PRIMARY KEY (id_liste_allergenes)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: contacte
#------------------------------------------------------------

CREATE TABLE contacte(
        id_sav    Varchar (20) NOT NULL ,
        id_client Varchar (20) NOT NULL
	,CONSTRAINT contacte_PK PRIMARY KEY (id_sav,id_client)

	,CONSTRAINT contacte_Sav_FK FOREIGN KEY (id_sav) REFERENCES Sav(id_sav)
	,CONSTRAINT contacte_Client0_FK FOREIGN KEY (id_client) REFERENCES Client(id_client)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: consulte
#------------------------------------------------------------

CREATE TABLE consulte(
        id_allegerne Varchar (20) NOT NULL ,
        id_client    Varchar (20) NOT NULL
	,CONSTRAINT consulte_PK PRIMARY KEY (id_allegerne,id_client)

	,CONSTRAINT consulte_Allergene_FK FOREIGN KEY (id_allegerne) REFERENCES Allergene(id_allegerne)
	,CONSTRAINT consulte_Client0_FK FOREIGN KEY (id_client) REFERENCES Client(id_client)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: maintient
#------------------------------------------------------------

CREATE TABLE maintient(
        id_allegerne             Varchar (20) NOT NULL ,
        id_panneau_configuration Varchar (20) NOT NULL
	,CONSTRAINT maintient_PK PRIMARY KEY (id_allegerne,id_panneau_configuration)

	,CONSTRAINT maintient_Allergene_FK FOREIGN KEY (id_allegerne) REFERENCES Allergene(id_allegerne)
	,CONSTRAINT maintient_Panneau_configuration0_FK FOREIGN KEY (id_panneau_configuration) REFERENCES Panneau_configuration(id_panneau_configuration)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: administre
#------------------------------------------------------------

CREATE TABLE administre(
        id_liste_allergenes Varchar (20) NOT NULL ,
        id_client           Varchar (20) NOT NULL
	,CONSTRAINT administre_PK PRIMARY KEY (id_liste_allergenes,id_client)

	,CONSTRAINT administre_Liste_allergene_personelle_FK FOREIGN KEY (id_liste_allergenes) REFERENCES Liste_allergene_personelle(id_liste_allergenes)
	,CONSTRAINT administre_Client0_FK FOREIGN KEY (id_client) REFERENCES Client(id_client)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: contient
#------------------------------------------------------------

CREATE TABLE contient(
        id_liste_allergenes Varchar (20) NOT NULL ,
        id_allegerne        Varchar (20) NOT NULL
	,CONSTRAINT contient_PK PRIMARY KEY (id_liste_allergenes,id_allegerne)

	,CONSTRAINT contient_Liste_allergene_personelle_FK FOREIGN KEY (id_liste_allergenes) REFERENCES Liste_allergene_personelle(id_liste_allergenes)
	,CONSTRAINT contient_Allergene0_FK FOREIGN KEY (id_allegerne) REFERENCES Allergene(id_allegerne)
)ENGINE=InnoDB;

