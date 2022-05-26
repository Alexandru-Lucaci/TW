-- Fiind conectat la sysdba 
CREATE USER TW_BD_ORACLE IDENTIFIED BY TW_BD_ORACLE DEFAULT TABLESPACE USERS TEMPORARY TABLESPACE TEMP;
 
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
-------------------------

drop table utilizatori;
/
create table utilizatori(id number(38,0),
nume_utilizator varchar2(20) unique not null,
parola varchar2(20) not null,
email varchar2(40),
telefon varchar2(50),
creat_la date default sysdate,
actualizat_la date default sysdate,
primary key (id)
);
/


drop table animale;
/
create table animale(
id number(38,0),
denumite_populara varchar2(50) not null,
denumire_stintifica varchar2(100) not null,
mini_descriere varchar2(500) not null,
etimologie varchar2(4000),
origine varchar2(20),
clasa varchar2(20),
invaziva varchar2(20),
stare_de_conservare varchar2(40),
regim_alimentar varchar2(15),
dieta varchar2(1300),
mod_de_inmultire varchar2(30),
reproducere varchar2(4000),
dezvoltare varchar2(4000),
viata varchar2(4000),
mortalitate varchar2(4000),
istorie varchar2(4000),
dusmani_naturali varchar2(1000),
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

create table salvari(
id_utilizator number(38,0) not null,
id_animal number(38,0) not null,
creat_la date default sysdate,
actualizat_la date default sysdate,
foreign key(id_utilizator) references utilizatori(id),
foreign key(id_animal) references animale(id)
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

create table statistici_clase_animale(
clasa varchar2(20),
nr_accesari integer default 0,
primary key(clasa)
);

--------------------------------------Insert utilizator
CREATE OR REPLACE PROCEDURE adaugUtilizator( p_nume_utilizator in varchar2, p_parola in varchar2, p_email in varchar2, p_telefon in varchar2) AS
begin

    INSERT INTO utilizatori(id, nume_utilizator, parola, email,telefon,creat_la, actualizat_la) values( (Select max(id) from utilizatori)+1,p_nume_utilizator, p_parola, p_email, p_telefon, sysdate, sysdate);


end;
/
insert into utilizatori(id) values(0);
INSERT INTO utilizatori(id, nume_utilizator, parola, email,telefon,creat_la, actualizat_la)
values( (Select max(id) from utilizatori)+1, 'alexNou', 'parolaBuna', 'lucacialexandru18@gmail.com', '0745881174', sysdate, sysdate);
/
INSERT INTO utilizatori(id, nume_utilizator, parola, email,telefon,creat_la, actualizat_la)
values( (Select max(id) from utilizatori)+1, 'HiImAlex', 'parolaBuna', 'lucacialexandru18@gmail.com', '0745881174', sysdate, sysdate);
/
adaugUtilizator('numeInteligent', 'parolaInteligenta', 'nume@yahoo.com', '0032123141231');
/
set serveroutput on;
declare 
begin
adaugUtilizator('chelsie.schuster','0z49367c0e2b','federico.blanda@hotmail.com','144-837-5361');
adaugUtilizator('thad.breitenberg','0f5nsmqwmkxy','nila.beer@hotmail.com','(968) 276-8241');
adaugUtilizator('blaine.abshire','kz7hh2ic2tudy','sha.schneider@gmail.com','517.539.4145');
adaugUtilizator('barry.parker','jaog5ikitcmf4','cecile.mraz@gmail.com','1-068-634-2333');
adaugUtilizator('travis.predovic','2xmo8x4ikh4mbp2','bradley.dooley@gmail.com','(303) 596-3324');
adaugUtilizator('lawana.gerhold','aokfgl8ocuouk','jamie.fay@yahoo.com','1-424-334-2772');
adaugUtilizator('leigh.dare','q10znrpvf8u78','philip.heathcote@yahoo.com','(037) 600-7433');
adaugUtilizator('desirae.cronin','7ika75r43c','jacinto.hodkiewicz@hotmail.com','231-257-0983');
adaugUtilizator('dewitt.baumbach','t669glcc','larae.kozey@hotmail.com','1-522-623-9240');
adaugUtilizator('barbera.weissnat','quihgv09','yajaira.smitham@gmail.com','1-322-575-3697');
adaugUtilizator('aleshia.medhurst','t25ddzhimtcld','clay.bechtelar@yahoo.com','346.507.4546');
adaugUtilizator('reuben.kuhn','xtyrbsihpaq','wilson.keeling@yahoo.com','540-792-1806');
adaugUtilizator('simona.deckow','itxnkf08sr','elfriede.schoen@gmail.com','180.996.9382');
adaugUtilizator('tera.balistreri','zx9t66x6n9l4','sharleen.gibson@hotmail.com','1-260-260-4974');
adaugUtilizator('teddy.ziemann','lj6syi0yapc','clay.bernier@yahoo.com','818.905.0968');
adaugUtilizator('theola.luettgen','g6d1ezuo','dana.armstrong@hotmail.com','108-631-5308');
adaugUtilizator('rigoberto.powlowski','sh113udy','rheba.steuber@gmail.com','242-032-2741');
adaugUtilizator('keisha.quitzon','jchfdxw3xq4ny','genaro.hansen@hotmail.com','522-263-5813');
adaugUtilizator('henry.kilback','wnmibygkrphvy','nell.sipes@gmail.com','031.820.2656');
adaugUtilizator('babette.bruen','erv7skda','taneka.steuber@yahoo.com','(127) 187-2125');
adaugUtilizator('edwin.gutkowski','c3cazcihpkbgj','jeanine.wehner@yahoo.com','097.593.3674');
adaugUtilizator('columbus.erdman','ajg4di63tzb','greg.ward@hotmail.com','994-295-1332');
adaugUtilizator('theodore.crist','gzkg7p9l3r','jack.bayer@hotmail.com','983.631.1173');
adaugUtilizator('berna.weimann','mmj7t4rlf2sb','beatrice.kilback@gmail.com','718.396.3080');
adaugUtilizator('pierre.orn','uyjutsrkot04','odell.beer@hotmail.com','375.946.8362');
adaugUtilizator('stephen.dare','n8p0c3wrbyrm8','gary.pagac@yahoo.com','928-498-1752');
adaugUtilizator('retta.connelly','kxfxxkjs09p6wa','alec.trantow@hotmail.com','978.886.4949');
adaugUtilizator('yahaira.welch','1djagn9uan','tom.pfannerstill@hotmail.com','207-318-0588');
adaugUtilizator('dessie.oconner','v62fh42w0nnse','kasie.waelchi@yahoo.com','1-522-626-8799');
adaugUtilizator('francisco.marquardt','i4a051qe0m92c','sharell.bayer@hotmail.com','1-161-990-4786');
adaugUtilizator('juliette.kohler','evbskul48','nellie.bradtke@hotmail.com','1-053-347-9481');
adaugUtilizator('louie.greenholt','1oiu25hn','roosevelt.raynor@gmail.com','1-860-271-8120');
adaugUtilizator('armida.runolfsson','w4x1vorgp7ik9vt','royce.mcdermott@hotmail.com','939-793-8993');
adaugUtilizator('ela.kuphal','1or19pmtj','terri.gutkowski@gmail.com','092-177-6921');
adaugUtilizator('sydney.champlin','7xhwz3lfm8hjnoo','charleen.nitzsche@gmail.com','190-660-4145');
adaugUtilizator('yuko.mccullough','14k9p4ux66r6m1','terence.ankunding@yahoo.com','983.352.4492');
adaugUtilizator('kimberely.sawayn','cj8qw99a5js','kristal.klein@gmail.com','991.637.5098');
adaugUtilizator('corinne.lynch','1i3tq29rb','santana.romaguera@gmail.com','(654) 315-2216');
adaugUtilizator('charmain.simonis','52prtemp8fsds','ida.collier@yahoo.com','625-778-2799');
adaugUtilizator('elizabet.dare','p3cw7r0g','eun.parker@gmail.com','(005) 112-6093');
adaugUtilizator('digna.ruecker','yzetsc5p','carlos.runolfsdottir@gmail.com','068-425-8401');
adaugUtilizator('carla.walker','hizwwcts','minh.bechtelar@hotmail.com','028-438-1248');
adaugUtilizator('kermit.towne','r07og4vhu','jay.jacobs@yahoo.com','351.350.9464');
adaugUtilizator('bennett.brekke','7wur9cpwplcwtp','carter.hamill@yahoo.com','745-410-3147');
adaugUtilizator('seth.heidenreich','hb7pgqvqzehg','seymour.marquardt@yahoo.com','945.446.4062');
adaugUtilizator('bell.kling','o1udsvs6yn84mz','roderick.harber@yahoo.com','848.383.4095');
adaugUtilizator('donovan.gulgowski','3a05iik9','martha.johns@gmail.com','1-584-342-9987');
adaugUtilizator('deloras.wolf','463c39l3','omar.brakus@yahoo.com','(288) 339-1513');
adaugUtilizator('charlie.sporer','2ps2wdw8ev','houston.powlowski@yahoo.com','818.325.1818');
adaugUtilizator('sina.greenfelder','09d8ijgx0','rory.doyle@hotmail.com','979-985-9858');
adaugUtilizator('ocie.huels','0hzg50qg','star.grant@yahoo.com','(304) 290-4041');
adaugUtilizator('apolonia.heller','k5u7j8hew3c','russ.romaguera@gmail.com','292.351.6353');
adaugUtilizator('luis.wintheiser','jx7mcfxgnq','joseph.hermiston@gmail.com','334-307-2738');
adaugUtilizator('ninfa.macgyver','d5eqjnj6','shelby.johns@yahoo.com','975-315-8380');
adaugUtilizator('miles.schmidt','w4k3hn7co','reed.hermann@yahoo.com','769.903.2648');
adaugUtilizator('del.marks','vmavqjaf9','tarah.kuhn@hotmail.com','(022) 721-5208');
adaugUtilizator('gillian.ortiz','1zbzt4pg6y9df','hyun.turcotte@hotmail.com','1-267-624-4755');
adaugUtilizator('leon.johns','qasi1pbb','lynn.wunsch@hotmail.com','829.624.8262');
adaugUtilizator('kory.denesik','l4hcku0ccjo7k','marquetta.hettinger@yahoo.com','(694) 896-9033');
adaugUtilizator('manuel.murray','9cnf92oxz8zljk9','kelly.hermann@gmail.com','1-396-479-4496');
adaugUtilizator('sammy.koch','yn3u79va5e','luis.collier@yahoo.com','621.110.6691');
adaugUtilizator('arlen.beahan','ppfc16ilhmr2p5','fernande.grant@gmail.com','292-004-7039');
adaugUtilizator('rosalyn.leffler','7rc8651e','odis.heidenreich@hotmail.com','(439) 817-8909');
adaugUtilizator('grazyna.feest','lpr93p3t','lewis.dach@hotmail.com','677.646.7480');
adaugUtilizator('ardelle.grady','cxeja8priu0','gaston.satterfield@gmail.com','246.025.7880');
adaugUtilizator('kai.mills','3cgewbv30','shaun.mitchell@yahoo.com','741.382.6174');
adaugUtilizator('kerry.cartwright','bloaqkpi','omar.hills@hotmail.com','779.781.9552');
adaugUtilizator('cody.mcdermott','lkw0vhnq','laila.zulauf@gmail.com','1-052-703-2087');
adaugUtilizator('brunilda.zulauf','rdrhi2ti','gisele.hilpert@gmail.com','(202) 188-9290');
adaugUtilizator('noma.rempel','kd0riqdaw5','alanna.heidenreich@gmail.com','(115) 713-1951');
adaugUtilizator('yuri.weimann','e5znf55vok','joelle.rice@gmail.com','(324) 817-8851');
adaugUtilizator('kaley.nienow','52axhn4r47bko','riley.buckridge@hotmail.com','238-786-7130');
adaugUtilizator('archie.lesch','a70w5j2h7','johnie.jaskolski@yahoo.com','(739) 315-8894');
adaugUtilizator('joanna.hyatt','26eys91m','hayden.corkery@yahoo.com','872.055.3465');
adaugUtilizator('sam.ernser','16ybmeyr4umd','joan.spencer@gmail.com','399.813.0406');
adaugUtilizator('patricia.kunze','3rtzszpzsmx','scottie.kassulke@hotmail.com','(075) 303-4393');
adaugUtilizator('shakia.block','bemy73rw2','ellis.feest@hotmail.com','1-616-175-8653');
adaugUtilizator('eboni.medhurst','ex7gr1p91yfawzn','lin.maggio@yahoo.com','607.538.3962');
adaugUtilizator('lyndsay.osinski','b2sovvq400ca','alease.jast@yahoo.com','997.949.8173');
adaugUtilizator('earl.morar','sonat4em','maryanne.damore@gmail.com','256-490-5358');
adaugUtilizator('daryl.wolff','ouxsbfmgggwda','roslyn.stanton@gmail.com','825.172.3674');
adaugUtilizator('wilson.kuvalis','i8dz544wsr74hv','kasie.gleason@yahoo.com','(163) 963-5982');
adaugUtilizator('vern.deckow','pviarc8p1','kirstin.lang@yahoo.com','857-268-7236');
adaugUtilizator('kelsey.kilback','duyjn2391v','wilhelmina.schroeder@gmail.com','1-226-189-5923');
adaugUtilizator('emmitt.lind','rdprrppfgkr1wu','cherish.kreiger@hotmail.com','440-289-4088');
adaugUtilizator('merle.collins','fq6j638lffbdlw','antony.wiza@hotmail.com','1-344-267-5097');
adaugUtilizator('hoyt.pacocha','cn6yiovua','shayne.carter@hotmail.com','408.485.9696');
adaugUtilizator('rickey.heller','nxqerkuw4x','wendolyn.dare@yahoo.com','858-892-8745');
adaugUtilizator('carlos.kertzmann','f7pul5bgwh','thalia.rogahn@yahoo.com','020-594-2875');
adaugUtilizator('ginette.fadel','5d61ctwahh20','grant.thompson@hotmail.com','(485) 404-5837');
adaugUtilizator('marcos.thompson','kltarnrpwrm','angelo.stokes@gmail.com','646.490.0842');
adaugUtilizator('jeromy.bogan','s594r7l1joyd','shila.murazik@hotmail.com','1-545-023-3471');
adaugUtilizator('jamel.connelly','jxqe0qk0aov2lh','breann.nikolaus@yahoo.com','809-711-9844');
adaugUtilizator('diego.smitham','jtik26quux2','deangelo.hamill@yahoo.com','784.053.1711');
adaugUtilizator('blake.okuneva','zapqfgesasypf8','anton.ebert@gmail.com','903.582.2689');
adaugUtilizator('michal.feeney','ys8rgo2t9z','britt.schneider@hotmail.com','1-521-845-1012');
adaugUtilizator('geri.schroeder','24sov5bsr1l82v','celeste.weimann@gmail.com','1-767-512-9689');
adaugUtilizator('erlinda.schaefer','qeg78yt3092b8','kaye.dietrich@yahoo.com','(109) 612-0056');
adaugUtilizator('usha.wilkinson','0m93smlkg9zd46h','jerrold.ratke@yahoo.com','(053) 532-3876');
adaugUtilizator('clifford.rodriguez','c0bte28p','cara.durgan@hotmail.com','029-746-8377');

end;
/


