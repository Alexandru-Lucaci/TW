-- Fiind conectat la sysdba 
CREATE USER TW_BD_ORACLE IDENTIFIED BY TW_BD_ORACLE DEFAULT TABLESPACE USERS TEMPORARY TABLESPACE TEMP;
 /
set serveroutput on; 
 /
 ALTER USER TW_BD_ORACLE QUOTA 100240M ON USERS;

/
GRANT CONNECT TO TW_BD_ORACLE;
/

GRANT CREATE TABLE TO TW_BD_ORACLE;

/

GRANT CREATE VIEW TO TW_BD_ORACLE;
/

GRANT CREATE SEQUENCE TO TW_BD_ORACLE;

/

GRANT CREATE TRIGGER TO TW_BD_ORACLE;

/

GRANT CREATE SYNONYM TO TW_BD_ORACLE;

/

GRANT CREATE PROCEDURE TO TW_BD_ORACLE;

/
GRANT CREATE TYPE TO TW_BD_ORACLE;
/

--for utl_file
GRANT EXECUTE ON UTL_FILE TO TW_BD_ORACLE;

GRANT CREATE ANY DIRECTORY TO TW_BD_ORACLE;

CREATE OR REPLACE DIRECTORY MYDIR as 'D:\TW_BD_ORACLE';

GRANT READ,WRITE ON DIRECTORY MYDIR TO TW_BD_ORACLE;
-------------------------

drop table utilizatori;
/

create table utilizatori(id number(38,0),
nume_utilizator varchar2(20) unique not null,
parola varchar2(20) not null,
email varchar2(40),
telefon varchar2(50),
administrator number(1,0) default 0,
creat_la date default sysdate,
actualizat_la date default sysdate,
primary key (id)
);
/
--administrator ar avea valoarea 0 daca nu e administrator si diferit de 0 daca este

select istorie from animale where id=1;

drop table animale;
/
create table animale(
id number(38,0),
denumire_populara varchar2(50) not null unique,
denumire_stintifica varchar2(100) not null,
mini_descriere varchar2(1500) not null,
etimologie varchar2(4000),
origine varchar2(20),
clasa varchar2(20),
habitat varchar2(40),
invaziva varchar2(20),
stare_de_conservare varchar2(40),
regim_alimentar varchar2(15),
dieta varchar2(1300),
mod_de_inmultire varchar2(30),
reproducere varchar2(4000),
dezvoltare varchar2(4000),
viata varchar2(4000),
istorie varchar2(4000),
dusmani_naturali varchar2(4000),
nr_accesari integer default 0,
nr_salvari integer default 0,
nr_descarcari integer default 0,
creat_la date default sysdate,
actualizat_la date default sysdate,
primary key(id)
);
/

drop table imagini;
/
create table imagini(
id number(38,0),
creat_la date default sysdate,
actualizat_la date default sysdate,
primary key(id)
);
/

drop table descrieri;
/
create table descrieri(
id number(38,0),
descriere varchar2(4000) not null,
limba varchar2(20) not null,
creat_la date default sysdate,
actualizat_la date default sysdate,
primary key(id)
);
/

drop table salvari;
/
drop table asocieri_imagini;
/
drop table asocieri_descrieri;
/
drop table statistici_clase_animale;
/

drop table salvari;

create table salvari(
id_utilizator number(38,0) not null,
id_animal number(38,0) not null,
creat_la date default sysdate,
actualizat_la date default sysdate,
primary key(id_utilizator,id_animal),
foreign key(id_utilizator) references utilizatori(id) on delete cascade,
foreign key(id_animal) references animale(id) on delete cascade
);
/
create table asocieri_imagini(
id_imagine number(38,0),
id_animal number(38,0),
creat_la date default sysdate,
actualizat_la date default sysdate,
foreign key(id_imagine) references imagini(id),
foreign key(id_animal) references animale(id)
);
/

create table asocieri_descrieri(
id_descriere number(38,0),
id_animal number(38,0),
creat_la date default sysdate,
actualizat_la date default sysdate,
foreign key(id_descriere) references descrieri(id),
foreign key(id_animal) references animale(id)
);
/
drop table statistici_clase_animale;
create table statistici_clase_animale(
clasa varchar2(20),
nr_accesari integer default 0,
creat_la date default sysdate,
actualizat_la date default sysdate,
primary key(clasa)
);

drop table formulare_contact;
/
create table formulare_contact(
id integer primary key,
nume varchar2(100),
email varchar2(100) default null,
telefon varchar2(100) default null,
mesaj varchar2(3500),
creat_la date default sysdate,
actualizat_la date default sysdate
);
insert into utilizatori (id, NUME_UTILIZATOR, PAROLA, ADMINISTRATOR) values(10,'alexus','alex',0);/
