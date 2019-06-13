/*
*
*
* Procédure stockée
*
*/

BEGIN

INSERT INTO projet (nom_projet) VALUES (pName);
INSERT INTO objectif (nom_objectif, dateDebut_Objectif, id_projet) VALUES (oName, 1, @@IDENTITY);
SELECT COUNT(*) INTO tProject FROM projet;

END


/*-----------------*/

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateExampleProject`(IN `pName` VARCHAR(200), IN `oName` VARCHAR(200), OUT `tProject` INT)
    NO SQL
BEGIN

INSERT INTO projet (nom_projet) VALUES (pName);
INSERT INTO objectif (nom_objectif, dateDebut_Objectif, id_projet) VALUES (oName, 1, @@IDENTITY);
SELECT COUNT(*) INTO tProject FROM projet;

END$$
DELIMITER ;

/*
* Nom du déclancheur : historic_insert
* Table : palloxes
* MOMENT : AFTER
* EVENT : INSERT
*/
BEGIN

INSERT INTO historic (pallox_id, storage_id_before, storage_id_after) VALUES (new.pallox_id, 0, new.storage_id);

UPDATE storage SET storage_quantity = storage_quantity + 1 WHERE storage_id = new.storage_id;

END


/*-----------------*/


CREATE TRIGGER `historic_insert` AFTER INSERT ON `palloxes`
 FOR EACH ROW BEGIN

INSERT INTO historic (pallox_id, storage_id_before, storage_id_after) VALUES (new.pallox_id, 0, new.storage_id);

UPDATE storage SET storage_quantity = storage_quantity + 1 WHERE storage_id = new.storage_id;

END


/*
* Nom du déclancheur : historic_update
* Table : palloxes
* MOMENT : BEFORE
* EVENT : UPDATE
*/
BEGIN

IF (new.storage_id = 0) THEN

    INSERT INTO historic (pallox_id, storage_id_before, storage_id_after) VALUES (old.pallox_id, old.storage_id, 0);

    UPDATE storage SET storage_quantity = storage_quantity - 1 WHERE storage_id = old.storage_id;

ELSEIF (new.storage_id > 0) THEN

    INSERT INTO historic (pallox_id, storage_id_before, storage_id_after) VALUES (old.pallox_id, old.storage_id, new.storage_id);

    UPDATE storage SET storage_quantity = storage_quantity - 1 WHERE storage_id = old.storage_id;

    UPDATE storage SET storage_quantity = storage_quantity + 1 WHERE storage_id = new.storage_id;

END IF;

END

/*-----------------*/



CREATE TRIGGER `historic_update` BEFORE UPDATE ON `palloxes`
 FOR EACH ROW BEGIN

IF (new.storage_id = 0) THEN

    INSERT INTO historic (pallox_id, storage_id_before, storage_id_after) VALUES (old.pallox_id, old.storage_id, 0);

    UPDATE storage SET storage_quantity = storage_quantity - 1 WHERE storage_id = old.storage_id;

ELSEIF (new.storage_id > 0) THEN

    INSERT INTO historic (pallox_id, storage_id_before, storage_id_after) VALUES (old.pallox_id, old.storage_id, new.storage_id);

    UPDATE storage SET storage_quantity = storage_quantity - 1 WHERE storage_id = old.storage_id;

    UPDATE storage SET storage_quantity = storage_quantity + 1 WHERE storage_id = new.storage_id;

END IF;

END
