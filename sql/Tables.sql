drop table utilizatori;
/
create table utilizatori(id number(38,0),
nume_utilizator varchar2(20),
parola varchar2(20),
email varchar2(40),
telefon varchar2(50),
creat_la date default sysdate,
actualizat_la date default sysdate
);
/

drop table salvari;
/
create table salvari(
id_utilizator number(38,0),
id_animal number(38,0),
creat_la date default sysdate,
actualizat_la date default sysdate
);

drop table animale;
/
create table animale(
id number(38,0),
denumite_populara varchar2(50),
denumire_stintifica varchar2(100),
etimologie varchar2(4000),
origine varchar2(20),
clasa varchar2(20),
invaziva varchar2(20),
stare_de_conservare varchar2(40),
regim_alimentar varchar2(15),
dieta varchar2(200),
mod_de_inmultire varchar2(30),
reproducere varchar2(4000),
dezvoltare varchar2(4000),
viata varchar2(4000),
mortalitate varchar2(4000),
istorie varchar2(4000),
dusmani_naturali varchar2(1000),
creat_la date default sysdate,
actualizat_la date default sysdate
);

drop table asocieri_imagini;
/
create table asocieri_imagini(
id_imagine number(38,0),
id_animal number(38,0),
creat_la date default sysdate,
actualizat_la date default sysdate
);


drop table descrieri;
/
create table descrieri(
id number(38,0),
descriere varchar2(4000),
creat_la date default sysdate,
actualizat_la date default sysdate
);

drop table asocieri_descrieri;
/
create table asocieri_descrieri(
id_descriere number(38,0),
id_animal number(38,0),
creat_la date default sysdate,
actualizat_la date default sysdate
);



select table_name from user_tables;

drop table testing;

