#CREACION DE LA TABLA BASE DE DATOS

CREATE DATABASE eatright;
USE eatright;


#CREACION DE LA TABLA ALIMENTOS
CREATE TABLE alimentos(
	id_alimento int NOT NULL AUTO_INCREMENT,
	nombre_alimen varchar(255) NOT NULL,
	marca varchar(50) NOT NULL,
	porcion float NOT NULL,
	kcal float NOT NULL,
	grasas float NOT NULL,
	g_saturadas float NOT NULL,
	carbohidratos float NOT NULL,
	azucar float NOT NULL,
	proteina float NOT NULL,
	sal float NOT NULL,
	calidad int,
	valoracion int DEFAULT 0,
	PRIMARY KEY (id_alimento)



);







#CREACION DE LA TABLA CLIENTES
CREATE TABLE clientes(

	id_cli int AUTO_INCREMENT,
	n_user varchar(50) UNIQUE NOT NULL,
	email varchar(50) UNIQUE NOT NULL,
	pass varchar(250) NOT NULL,
	sexo varchar(1) NOT NULL,
	f_cumple DATE NOT NULL,
	peso float NOT NULL,
	altura float NOT NULL,
	nombre_completo varchar(100) NOT NULL,
	estado varchar(30) NOT NULL,
	intentos int NOT NULL,
	PRIMARY KEY (id_cli)
	
);


#CREACION DE LA TABLA COMEN,SE USA UN ID COMO CLAVE PRIMARIA AL CREAR LA TABLA POR HACER LA TABLA MAS SIMPLE
CREATE TABLE comen(
	id_comen int NOT NULL  AUTO_INCREMENT,
	cli_id int NOT NULL,
	alimen_id int NOT NULL,
	fecha DATE NOT NULL,
	cantidad float NOT NULL,
	moment_comida varchar(25) NOT NULL,
	PRIMARY KEY (id_comen),
	CONSTRAINT FK_comencli FOREIGN KEY (cli_id) REFERENCES clientes(id_cli) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT FK_comenalimen FOREIGN KEY (alimen_id) REFERENCES alimentos(id_alimento) ON UPDATE CASCADE ON DELETE CASCADE
);


#CREACION DE LA TABLA FAVORITOS
CREATE TABLE favoritos(

	id_clifav int NOT NULL,
	id_alimefav int NOT NULL,
	PRIMARY KEY (id_clifav,id_alimefav),
	CONSTRAINT FK_favcli FOREIGN KEY (id_clifav) REFERENCES clientes(id_cli) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT FK_favali FOREIGN KEY (id_alimefav) REFERENCES alimentos(id_alimento) ON UPDATE CASCADE ON DELETE CASCADE
);

#CREACION DE LA TABLA VALORACION ALIMENTOS
CREATE TABLE valoralimen(

	id_clival int NOT NULL,
	id_alimenval int NOT NULL,
	puntuacion int NOT NULL,
	PRIMARY KEY(id_clival,id_alimenval),
	CONSTRAINT FK_valcli FOREIGN KEY (id_clival) REFERENCES clientes(id_cli) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT FK_valali FOREIGN KEY (id_alimenval) REFERENCES alimentos(id_alimento) ON UPDATE CASCADE ON DELETE CASCADE

);



#CREACION DEL DISPARADOR DE LA CALIDAD DE UN PRODUCTO
DELIMITER $$

CREATE TRIGGER calc_calidad BEFORE INSERT ON alimentos 
FOR EACH ROW 
BEGIN 
	
    #DECLARACION DE LAS VARIABLES azucar, g_saturadas, sal y media_calidad 
	DECLARE azucar INT DEFAULT 0;
    DECLARE g_saturadas INT DEFAULT 0;
    DECLARE sal INT DEFAULT 0;
    DECLARE media_calidad INT;
    
    #COMPROBACIONES SOBRE EL AZUCAR DEL ALIMENTO
    IF NEW.azucar<0 
    	THEN SET azucar=0; 
    ELSEIF NEW.azucar>1 AND NEW.azucar<5 
    	THEN SET azucar=1;
    ELSEIF NEW.azucar>5 AND NEW.azucar<10 
    	THEN SET azucar=2;
    ELSEIF NEW.azucar>10 AND NEW.azucar<15 
    	THEN SET azucar=3;
    ELSEIF NEW.azucar>15 
    	THEN SET azucar=4;
    END IF; 
	
    
    #COMPROBACIONES SOBRE LAS GRASAS SATURADAS DEL ALIMENTO
    IF NEW.g_saturadas<=0.1 
    	THEN SET g_saturadas=0; 
    ELSEIF NEW.g_saturadas<=0.5 
    	THEN SET g_saturadas=1;
    ELSEIF NEW.g_saturadas>0.5 AND NEW.g_saturadas<=1 
    	THEN SET g_saturadas=3;
    ELSEIF NEW.g_saturadas>1
    	THEN SET g_saturadas=4;
    END IF;
	
    
    #COMPROBACIONES SOBRE LA SAL DEL ALIMENTO
    IF NEW.sal<=0.00 
    	THEN SET sal=0; 
    ELSEIF NEW.sal<=0.04 
    	THEN SET sal=1;
    ELSEIF NEW.sal<=0.12 
    	THEN SET sal=2;
    ELSEIF NEW.sal<=0.25
    	THEN SET sal=3;
    ELSEIF NEW.sal<=1.25 OR NEW.sal>1.25
    	THEN SET sal=4;
    END IF;
    
    SET media_calidad=CEIL((sal+g_saturadas+azucar)/3);
    
    SET NEW.calidad=media_calidad+1;
    
END;$$


#DISPARADOR QUE ACTUALIZA LA VALORACION DE UN ALIMENTO CUANDO SE ACTUALIZA SU PUNTUACION
DELIMITER $$
CREATE TRIGGER actu_puntu AFTER UPDATE ON valoralimen
FOR EACH ROW
BEGIN

	DECLARE puntu int;
	SELECT AVG(puntuacion) INTO puntu FROM valoralimen WHERE id_alimenval=NEW.id_alimenval;
    	UPDATE alimentos SET valoracion=puntu WHERE id_alimento=NEW.id_alimenval;


END;$$

#DISPARADOR QUE ACTUALIZA LA VALORACION DE UN ALIMENTO CUANDO UN USUARIO LO VALORA
DELIMITER $$
CREATE TRIGGER insert_puntu AFTER INSERT ON valoralimen
FOR EACH ROW
BEGIN

	DECLARE puntu int;
	SELECT AVG(puntuacion) INTO puntu FROM valoralimen WHERE id_alimenval=NEW.id_alimenval;
    	UPDATE alimentos SET valoracion=puntu WHERE id_alimento=NEW.id_alimenval;


END;$$

#DISPARADOR QUE ACTUALIZA LA PUNTUACION DE UN ALIMENTO CUANDO SE BORRA UNA VALORACION
DELIMITER $$
CREATE TRIGGER after_delete_puntu AFTER DELETE ON valoralimen
FOR EACH ROW
BEGIN
    DECLARE puntu FLOAT;
    SELECT IFNULL(AVG(puntuacion), 0) INTO puntu FROM valoralimen WHERE id_alimenval = OLD.id_alimenval;
    UPDATE alimentos SET valoracion = puntu WHERE id_alimento = OLD.id_alimenval;
END;
$$
DELIMITER ;

