--Populare utilizatori
CREATE OR REPLACE PROCEDURE adaugare_utilizator( p_nume_utilizator in varchar2, p_parola in varchar2, p_email in varchar2, p_telefon in varchar2) AS
    v_id utilizatori.id%type;
begin
    
    select nvl(max(id),0)+1 into v_id from utilizatori;
    
    INSERT INTO utilizatori(id, nume_utilizator, parola, email,telefon) values( v_id,p_nume_utilizator, p_parola, p_email, p_telefon);

end;

declare 
begin
adaugare_utilizator('remus','remus',null,null);
adaugare_utilizator('chelsie.schuster','0z49367c0e2b','federico.blanda@hotmail.com','144-837-5361');
adaugare_utilizator('thad.breitenberg','0f5nsmqwmkxy','nila.beer@hotmail.com','(968) 276-8241');
adaugare_utilizator('blaine.abshire','kz7hh2ic2tudy','sha.schneider@gmail.com','517.539.4145');
adaugare_utilizator('barry.parker','jaog5ikitcmf4','cecile.mraz@gmail.com','1-068-634-2333');
adaugare_utilizator('travis.predovic','2xmo8x4ikh4mbp2','bradley.dooley@gmail.com','(303) 596-3324');
adaugare_utilizator('lawana.gerhold','aokfgl8ocuouk','jamie.fay@yahoo.com','1-424-334-2772');
adaugare_utilizator('leigh.dare','q10znrpvf8u78','philip.heathcote@yahoo.com','(037) 600-7433');
adaugare_utilizator('desirae.cronin','7ika75r43c','jacinto.hodkiewicz@hotmail.com','231-257-0983');
adaugare_utilizator('dewitt.baumbach','t669glcc','larae.kozey@hotmail.com','1-522-623-9240');
adaugare_utilizator('barbera.weissnat','quihgv09','yajaira.smitham@gmail.com','1-322-575-3697');
adaugare_utilizator('aleshia.medhurst','t25ddzhimtcld','clay.bechtelar@yahoo.com','346.507.4546');
adaugare_utilizator('reuben.kuhn','xtyrbsihpaq','wilson.keeling@yahoo.com','540-792-1806');
adaugare_utilizator('simona.deckow','itxnkf08sr','elfriede.schoen@gmail.com','180.996.9382');
adaugare_utilizator('tera.balistreri','zx9t66x6n9l4','sharleen.gibson@hotmail.com','1-260-260-4974');
adaugare_utilizator('teddy.ziemann','lj6syi0yapc','clay.bernier@yahoo.com','818.905.0968');
adaugare_utilizator('theola.luettgen','g6d1ezuo','dana.armstrong@hotmail.com','108-631-5308');
adaugare_utilizator('rigoberto.powlowski','sh113udy','rheba.steuber@gmail.com','242-032-2741');
adaugare_utilizator('keisha.quitzon','jchfdxw3xq4ny','genaro.hansen@hotmail.com','522-263-5813');
adaugare_utilizator('henry.kilback','wnmibygkrphvy','nell.sipes@gmail.com','031.820.2656');
adaugare_utilizator('babette.bruen','erv7skda','taneka.steuber@yahoo.com','(127) 187-2125');
adaugare_utilizator('edwin.gutkowski','c3cazcihpkbgj','jeanine.wehner@yahoo.com','097.593.3674');
adaugare_utilizator('columbus.erdman','ajg4di63tzb','greg.ward@hotmail.com','994-295-1332');
adaugare_utilizator('theodore.crist','gzkg7p9l3r','jack.bayer@hotmail.com','983.631.1173');
adaugare_utilizator('berna.weimann','mmj7t4rlf2sb','beatrice.kilback@gmail.com','718.396.3080');
adaugare_utilizator('pierre.orn','uyjutsrkot04','odell.beer@hotmail.com','375.946.8362');
adaugare_utilizator('stephen.dare','n8p0c3wrbyrm8','gary.pagac@yahoo.com','928-498-1752');
adaugare_utilizator('retta.connelly','kxfxxkjs09p6wa','alec.trantow@hotmail.com','978.886.4949');
adaugare_utilizator('yahaira.welch','1djagn9uan','tom.pfannerstill@hotmail.com','207-318-0588');
adaugare_utilizator('dessie.oconner','v62fh42w0nnse','kasie.waelchi@yahoo.com','1-522-626-8799');
adaugare_utilizator('francisco.marquardt','i4a051qe0m92c','sharell.bayer@hotmail.com','1-161-990-4786');
adaugare_utilizator('juliette.kohler','evbskul48','nellie.bradtke@hotmail.com','1-053-347-9481');
adaugare_utilizator('louie.greenholt','1oiu25hn','roosevelt.raynor@gmail.com','1-860-271-8120');
adaugare_utilizator('armida.runolfsson','w4x1vorgp7ik9vt','royce.mcdermott@hotmail.com','939-793-8993');
adaugare_utilizator('ela.kuphal','1or19pmtj','terri.gutkowski@gmail.com','092-177-6921');
adaugare_utilizator('sydney.champlin','7xhwz3lfm8hjnoo','charleen.nitzsche@gmail.com','190-660-4145');
adaugare_utilizator('yuko.mccullough','14k9p4ux66r6m1','terence.ankunding@yahoo.com','983.352.4492');
adaugare_utilizator('kimberely.sawayn','cj8qw99a5js','kristal.klein@gmail.com','991.637.5098');
adaugare_utilizator('corinne.lynch','1i3tq29rb','santana.romaguera@gmail.com','(654) 315-2216');
adaugare_utilizator('charmain.simonis','52prtemp8fsds','ida.collier@yahoo.com','625-778-2799');
adaugare_utilizator('elizabet.dare','p3cw7r0g','eun.parker@gmail.com','(005) 112-6093');
adaugare_utilizator('digna.ruecker','yzetsc5p','carlos.runolfsdottir@gmail.com','068-425-8401');
adaugare_utilizator('carla.walker','hizwwcts','minh.bechtelar@hotmail.com','028-438-1248');
adaugare_utilizator('kermit.towne','r07og4vhu','jay.jacobs@yahoo.com','351.350.9464');
adaugare_utilizator('bennett.brekke','7wur9cpwplcwtp','carter.hamill@yahoo.com','745-410-3147');
adaugare_utilizator('seth.heidenreich','hb7pgqvqzehg','seymour.marquardt@yahoo.com','945.446.4062');
adaugare_utilizator('bell.kling','o1udsvs6yn84mz','roderick.harber@yahoo.com','848.383.4095');
adaugare_utilizator('donovan.gulgowski','3a05iik9','martha.johns@gmail.com','1-584-342-9987');
adaugare_utilizator('deloras.wolf','463c39l3','omar.brakus@yahoo.com','(288) 339-1513');
adaugare_utilizator('charlie.sporer','2ps2wdw8ev','houston.powlowski@yahoo.com','818.325.1818');
adaugare_utilizator('sina.greenfelder','09d8ijgx0','rory.doyle@hotmail.com','979-985-9858');
adaugare_utilizator('ocie.huels','0hzg50qg','star.grant@yahoo.com','(304) 290-4041');
adaugare_utilizator('apolonia.heller','k5u7j8hew3c','russ.romaguera@gmail.com','292.351.6353');
adaugare_utilizator('luis.wintheiser','jx7mcfxgnq','joseph.hermiston@gmail.com','334-307-2738');
adaugare_utilizator('ninfa.macgyver','d5eqjnj6','shelby.johns@yahoo.com','975-315-8380');
adaugare_utilizator('miles.schmidt','w4k3hn7co','reed.hermann@yahoo.com','769.903.2648');
adaugare_utilizator('del.marks','vmavqjaf9','tarah.kuhn@hotmail.com','(022) 721-5208');
adaugare_utilizator('gillian.ortiz','1zbzt4pg6y9df','hyun.turcotte@hotmail.com','1-267-624-4755');
adaugare_utilizator('leon.johns','qasi1pbb','lynn.wunsch@hotmail.com','829.624.8262');
adaugare_utilizator('kory.denesik','l4hcku0ccjo7k','marquetta.hettinger@yahoo.com','(694) 896-9033');
adaugare_utilizator('manuel.murray','9cnf92oxz8zljk9','kelly.hermann@gmail.com','1-396-479-4496');
adaugare_utilizator('sammy.koch','yn3u79va5e','luis.collier@yahoo.com','621.110.6691');
adaugare_utilizator('arlen.beahan','ppfc16ilhmr2p5','fernande.grant@gmail.com','292-004-7039');
adaugare_utilizator('rosalyn.leffler','7rc8651e','odis.heidenreich@hotmail.com','(439) 817-8909');
adaugare_utilizator('grazyna.feest','lpr93p3t','lewis.dach@hotmail.com','677.646.7480');
adaugare_utilizator('ardelle.grady','cxeja8priu0','gaston.satterfield@gmail.com','246.025.7880');
adaugare_utilizator('kai.mills','3cgewbv30','shaun.mitchell@yahoo.com','741.382.6174');
adaugare_utilizator('kerry.cartwright','bloaqkpi','omar.hills@hotmail.com','779.781.9552');
adaugare_utilizator('cody.mcdermott','lkw0vhnq','laila.zulauf@gmail.com','1-052-703-2087');
adaugare_utilizator('brunilda.zulauf','rdrhi2ti','gisele.hilpert@gmail.com','(202) 188-9290');
adaugare_utilizator('noma.rempel','kd0riqdaw5','alanna.heidenreich@gmail.com','(115) 713-1951');
adaugare_utilizator('yuri.weimann','e5znf55vok','joelle.rice@gmail.com','(324) 817-8851');
adaugare_utilizator('kaley.nienow','52axhn4r47bko','riley.buckridge@hotmail.com','238-786-7130');
adaugare_utilizator('archie.lesch','a70w5j2h7','johnie.jaskolski@yahoo.com','(739) 315-8894');
adaugare_utilizator('joanna.hyatt','26eys91m','hayden.corkery@yahoo.com','872.055.3465');
adaugare_utilizator('sam.ernser','16ybmeyr4umd','joan.spencer@gmail.com','399.813.0406');
adaugare_utilizator('patricia.kunze','3rtzszpzsmx','scottie.kassulke@hotmail.com','(075) 303-4393');
adaugare_utilizator('shakia.block','bemy73rw2','ellis.feest@hotmail.com','1-616-175-8653');
adaugare_utilizator('eboni.medhurst','ex7gr1p91yfawzn','lin.maggio@yahoo.com','607.538.3962');
adaugare_utilizator('lyndsay.osinski','b2sovvq400ca','alease.jast@yahoo.com','997.949.8173');
adaugare_utilizator('earl.morar','sonat4em','maryanne.damore@gmail.com','256-490-5358');
adaugare_utilizator('daryl.wolff','ouxsbfmgggwda','roslyn.stanton@gmail.com','825.172.3674');
adaugare_utilizator('wilson.kuvalis','i8dz544wsr74hv','kasie.gleason@yahoo.com','(163) 963-5982');
adaugare_utilizator('vern.deckow','pviarc8p1','kirstin.lang@yahoo.com','857-268-7236');
adaugare_utilizator('kelsey.kilback','duyjn2391v','wilhelmina.schroeder@gmail.com','1-226-189-5923');
adaugare_utilizator('emmitt.lind','rdprrppfgkr1wu','cherish.kreiger@hotmail.com','440-289-4088');
adaugare_utilizator('merle.collins','fq6j638lffbdlw','antony.wiza@hotmail.com','1-344-267-5097');
adaugare_utilizator('hoyt.pacocha','cn6yiovua','shayne.carter@hotmail.com','408.485.9696');
adaugare_utilizator('rickey.heller','nxqerkuw4x','wendolyn.dare@yahoo.com','858-892-8745');
adaugare_utilizator('carlos.kertzmann','f7pul5bgwh','thalia.rogahn@yahoo.com','020-594-2875');
adaugare_utilizator('ginette.fadel','5d61ctwahh20','grant.thompson@hotmail.com','(485) 404-5837');
adaugare_utilizator('marcos.thompson','kltarnrpwrm','angelo.stokes@gmail.com','646.490.0842');
adaugare_utilizator('jeromy.bogan','s594r7l1joyd','shila.murazik@hotmail.com','1-545-023-3471');
adaugare_utilizator('jamel.connelly','jxqe0qk0aov2lh','breann.nikolaus@yahoo.com','809-711-9844');
adaugare_utilizator('diego.smitham','jtik26quux2','deangelo.hamill@yahoo.com','784.053.1711');
adaugare_utilizator('blake.okuneva','zapqfgesasypf8','anton.ebert@gmail.com','903.582.2689');
adaugare_utilizator('michal.feeney','ys8rgo2t9z','britt.schneider@hotmail.com','1-521-845-1012');
adaugare_utilizator('geri.schroeder','24sov5bsr1l82v','celeste.weimann@gmail.com','1-767-512-9689');
adaugare_utilizator('erlinda.schaefer','qeg78yt3092b8','kaye.dietrich@yahoo.com','(109) 612-0056');
adaugare_utilizator('usha.wilkinson','0m93smlkg9zd46h','jerrold.ratke@yahoo.com','(053) 532-3876');
adaugare_utilizator('clifford.rodriguez','c0bte28p','cara.durgan@hotmail.com','029-746-8377');

end;

----Adaugare animale(posibil mai mult cod pentru inserarea mai usoara a acestora)
create or replace procedure adaugare_animal(
p_denumire_populara animale.denumire_populara%type,
p_denumire_stintifica animale.denumire_stintifica%type,
p_mini_descriere animale.mini_descriere%type,
p_etimologie animale.etimologie%type,
p_origine animale.origine%type,
p_clasa animale.clasa%type,
p_invaziva animale.clasa%type,
p_stare_de_conservare animale.stare_de_conservare%type,
p_regim_alimentar animale.regim_alimentar%type,
p_dieta animale.dieta%type,
p_mod_de_inmultire animale.mod_de_inmultire%type,
p_reproducere animale.reproducere%type,
p_dezvoltare animale.dezvoltare%type,
p_viata animale.viata%type,
p_mortalitate animale.mortalitate%type,
p_istorie animale.istorie%type,
p_dusmani_naturali animale.dusmani_naturali%type
) as
    id_animal animale.id%type;
begin
    select nvl(max(id),0)+1 into id_animal from animale;
    
    
    insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,etimologie,
    origine,clasa,invaziva,stare_de_conservare,regim_alimentar,dieta,mod_de_inmultire,
    reproducere,dezvoltare,viata,mortalitate,istorie,dusmani_naturali) values(
    id_animal,
    p_denumire_populara,
    p_denumire_stintifica,
    p_mini_descriere,
    p_etimologie,
    p_origine,
    p_clasa,
    p_invaziva,
    p_stare_de_conservare,
    p_regim_alimentar,
    p_dieta,
    p_mod_de_inmultire,
    p_reproducere,
    p_dezvoltare,
    p_viata,
    p_mortalitate,
    p_istorie,
    p_dusmani_naturali
    );
end;

declare
    denumire_populara animale.denumire_populara%type := 'Tigru';
    denumire_stintifica animale.denumire_stintifica%type := 'Panthera tigris';
    mini_descriere animale.mini_descriere%type := 'Tigrul este o specie de mamifere carnivore din familia felidelor, fiind una dintre cele patru specii ale genului Panthera. Este cel mai mare reprezentant al subfamiliei Pantherinae ?i unul dintre cei mai mari r?pitori tere?tri.';
    etimologie animale.etimologie%type := 'Din francez? tigre, care provine din latin? tigris . De origine iranian?.';
    origine animale.origine%type := 'Asia';
    clasa animale.clasa%type := 'manifer';
    invaziva animale.clasa%type := 'da';
    stare_de_conservare animale.stare_de_conservare%type := 'specie în pericol';
    regim_alimentar animale.regim_alimentar%type := 'carnivor';
    dieta animale.dieta%type := 'Dieta tigrului consta din diverse specii de ungulate mari. În cazuri rare, tigrii atac? tapiriile malayene, elefantii indieni si rinocerii tineri indieni. Tigrii ataca in mod regulat si m?nânc? ursi bruni, ursi negri asiatici si ursi lenesi (Melursus ursinus).  Cand o prada mai mare nu este disponibila, printre acestea se numara pasarile mari, leoparzii, pestii, crocodilii, broastele testoase, sobolanii si broastele.';
    mod_de_inmultire animale.mod_de_inmultire%type := 'bisexuala';
    
    reproducere animale.reproducere%type := 'Masculii sunt poligami, iar femelele poliandre. Imperecherea poate avea loc oricand, dar este mai frecventa in lunile noiembrie–aprilie (decembrie–ianuarie la speciile nordice). Pentru a evita imperecherea intre rude, femela prefera sa se imperecheze cu masculi din regiuni relativ indepartate. In regiunile slab populate, o femela este urmarita de un singur mascul. Daca masculii sunt mai multi, intre acestia uneori apar conflicte pentru dreptul de a se imperechea cu o femela. Competitia insa nu este una acerba, deoarece femela permite mai multor masculi sa se imperecheze cu ea (prin aceasta asigurand generatiei viitoare o puternica varietate genetica) Atunci cand un mascul simte, dupa marcajele femelei, ca aceasta e in calduri, el incepe sa se comporte diferit (comportamentul Flehmen), schimbandu-si grimasa fetei. Pentru a-si „rezerva” partenera, masculul ii marcheaza teritoriul cu urina sa, desi aceste „rezervari” sunt ignorate de alti masculi, care isi fac propriile marcaje in acelasi loc. Tigroaica este capabila de fecundare numai cateva zile pe an, timp in care imperecherea are loc de mai multe ori pe zi, insotita de sunete puternice. Majoritatea femelelor nasc pentru prima oara la varsta de 3–4 ani, iar perioada dintre doua nasteri este de 2–2,5 ani, in unele cazuri 3–4 ani. Gestatia dureaza 97–112 zile, in medie 103 zile. Tigroaica isi aranjeaza culcusul in locuri greu accesibile: in pesteri, intre pietre, in desisurile de trestii. Poate folosi una si aceeasi ascunzatoare mai multi ani la rand.
Tigrisorii se nasc in martie–aprilie, cate doi–trei, mai rar unul singur si inca mai rar cate cinci–sase. Daca toti puii se nasc morti, femela este capabila sa nasca alti tigrisori peste 5 luni. In primele zile puii sunt absolut neajutorati, au greutatea de 1,3–1,5 kg, si incep sa vada in jurul celei de-a saptea zi. Primele 6 saptamani se hranesc doar cu laptele mamei. Aceasta nu-i permite tatalui sa se apropie, de frica ca acesta nu-i va recunoaste si-i va manca. La 8 saptamani tigrisorii sunt capabili sa iasa din culcus si sa-si urmareasca mama. Devin independenti la 18 luni, dar raman cu mama pana la varsta de 2–3 ani, uneori pana la 5 ani.
O data deveniti independenti, femelele raman aproape de teritoriul mamei, in timp ce masculii strabat distante lungi in cautarea unui teritoriu; daca sunt putini tigri in regiune, ei ocupa un teritoriu liber, iar daca nu, sunt nevoiti sa cucereasca teritoriul altor masculi. Femelele ating maturitatea sexuala la 3–4 ani, iar masculii la 4–5 ani. O femela poate aduce pe lume 10–20 tigrisori pe tot parcursul vietii, din care circa jumatate mor de mici. Tigrul traieste pana la 26 de ani.';
    
    dezvoltare animale.dezvoltare%type := 'Gol';
    viata animale.viata%type := 'Gol';
    mortalitate animale.mortalitate%type := ' Un studiu efectuat in Parcul National Chitwan , Nepal , a constatat o mortalitate infantila de 34% pentru tinerii sub un an si 29% pentru al doilea an. In primul an, 73% din decese au fost cauzate de pierderea intregului gunoi din cauza inundatiilor , a incendiilor sau a pruncuciderii . Acest ultim motiv este, in plus, principala cauza de deces pentru tigrii cu varsta sub un an; tinerii tigri sunt uneori ucisi de alti barbati care vin sa cucereasca teritoriul tatalui lor. Pentru al doilea an, pierderea unei asternuturi intregi este mult mai rara: atinge 29% din decese. Durata de viata a unui tigru este estimata la 26 de ani in captivitate si 15 ani in salbaticie.';
    istorie animale.istorie%type := 'Cele mai multe date ce tin de evolutia speciei tigrului au fost obtinute prin intermediul analizei ramasitelor pamantesti si a cercetarilor in domeniul filogeniei moleculare.In baza analizelor cladogenetice s-a demonstrat ca originea geografica a speciei se gaseste in Asia de est. Studierea osemintelor scoase la suprafata este dificila din mai multe puncte de vedere. Descoperirile paleontologice nu sunt numeroase si sunt puternic fragmentate. Osemintele sunt expuse la poluare cu material genetic strain si, in genere, vechimea lor este greu de determinat.Cele mai vechi ramasite ale unui schelet de tigru au fost scoase la suprafata in nordul Chinei si in insula Java.Stramosul tigrului, Panthera palaeosinensis (Zdansky, 1924), este destul de asemanator acestuia, dar de dimensiuni mai mici, care a trait in provincia Henan din China de nord de la sfarsitul pliocenului pana la inceputul pleistocenului. Clasificarea acestei specii este, la moment, ambigua. Aproximativ 2 milioane de ani in urma, la inceputul pleistocenului, tigrul era raspandit in estul Asiei, populand si insule precum Borneo si Palawan. Au fost scoase la suprafata, in numar mare, oseminte din pleistocen, in China, Sumatra si Java. In India, Altai si Siberia, tigrii n-au aparut decat la sfarsitul pleistocenului. De asemenea, au fost descoperite ramasite ale unor asa-zise pisici mari, clasificate la tigri, in nord-estul extrem al Siberiei. Pe continentele americane n-au fost facute niciun fel de descoperiri in ce priveste tigrul. Conform analizelor anatomice comparative, dimensiunile medii ale tigrului s-au micsorat din pleistocen pana in prezent (exceptie face subspecia tigrilor siberieni). Acest fenomen este caracteristic mamiferelor din pleistocen si ofera informatii despre scaderea bioproductivitatii sezoniere a mediului si/sau despre micsorarea dimensiunilor medii ale prazii ( in cazul rapitoarelor ). Oseminte din holocen au fost descoperite in insulele Java si Borneo. Coltii de tigru aflati in posesia bastinasilor din Borneo au fost, dupa spusele acestora, ai unor tigri salbatici pe care stramosii lor i-ar fi ucis 4–7 generatii mai inainte, ceea ce indica faptul ca tigrul din Borneo ar fi disparut acum aprox. 200 de ani. Cea mai evidenta dovada este recenta descoperire a unui fragment de dinte printre sedimentele neolitice dintr-o pestera din partea malaeziana a insulei (Sarawak). Datele molecularo-filogenice confirma relatiile stranse de rudenie intre speciile genului Panthera si demonstreaza ca tigrul s-a separat de linia ancestrala cu peste 2 milioane de ani mai devreme decat leul, leopardul si jaguarul.';
    dusmani_naturali animale.dusmani_naturali%type := 'Tigrul se afla in varful lantului alimentar din gama sa, deci are putini inamici naturali. Printre acestia se numara reprezentanti ai familiei canine, alti ursi felini, bruni, din Himalaya si Malaie, crocodili. Motivul principal al feudei dintre pradatori este lupta pentru hrana. In plus, tigrii vaneaza uneori in mod intentionat aceste animale.  Activitatile legate de om, cum ar fi braconajul, fragmentarea habitatelor si distrugerea habitatelor, raman cele mai mari amenintari la adresa existentei tigrilor din intreaga lume.';
begin
    adaugare_animal(denumire_populara,denumire_stintifica,mini_descriere,etimologie,origine,clasa,invaziva,stare_de_conservare,regim_alimentar,dieta,mod_de_inmultire,reproducere,dezvoltare,viata,mortalitate,istorie,dusmani_naturali);
end;


------testing