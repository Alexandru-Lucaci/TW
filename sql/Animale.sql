--legat de animale

describe animale;
describe utilizatori;

----------------posibil cu mult mai usor in php-------------------

--functie ajutatoare pentru cautare_multicriteriala
--presupunem ca primim un sir de cuvinte cel putin 2,separate prin virgule
--primul cuvant reprezinta valoarea campului si restul valorile posibile ale acelui camp
--format: nume_camp,valoare_1,valoare_2,...
--presupunem ca avem doar egalitati cu tipuri varchar2
--returneaza un sir care are nume_camp=cuvant_1 or ... or nume_camp=cuvant_n

drop function extragere_cuvinte;

create or replace function extragere_expresie(p_cuvinte varchar2)
return varchar2
as
    v_nr_cuvinte integer;
    
    v_valoare_camp varchar2(100);
    v_nume_camp varchar2(100);
    
    v_rezultat varchar2(1000):='';
begin
    --calculeaza numar de cuvinte si ,daca sunt mai putin de 2,iesi
    select regexp_count(p_cuvinte,',')+1 into v_nr_cuvinte from dual;
    if(v_nr_cuvinte<2)then
        return null;
    end if;
    
    --asigneaza valoarea corespunzatoare pentru v_nume_camp
    select regexp_substr(p_cuvinte,'[^,]+',1,1) into v_nume_camp from dual;
    
    for v_pozitie in 2..v_nr_cuvinte loop
        --extragere valoare camp
        select regexp_substr(p_cuvinte,'[^,]+',1,v_pozitie) into v_valoare_camp from dual;
        
        --concatenare
        if(v_pozitie!=2)then
            v_rezultat:=v_rezultat||' or ';
        end if;
        
        v_rezultat:=v_rezultat||'lower(trim('||v_nume_camp||'))'||' = lower('||chr(39)||v_valoare_camp||chr(39)||')';
    end loop;
    
    return v_rezultat;    
end;

---functie cautare multi-criteriala
--primeste un sir care are informatii despre valorile si numele criteriului
--format p_sir : criteriu1,valoare1,valoare2,...#crieteriu2,valoare1,valoare2,...
--p_rezultat este un sir de caractere care va contine informatiile despre animalele care indeplinesc criteriile
--format p_rezultat : denumire_populara=valoare,denumire_stintifica=valoare,mini_descriere=valoare#...
--v_raspuns va fi 'OK' daca a fost gasit macar un rezultat, 'Empty' daca nu exista rezultate si altfel mesaj de eroare specific
create or replace procedure cautare_multicriteriala(p_sir varchar2,p_rezultat IN OUT varchar2,p_raspuns IN OUT varchar2)
as
    v_sql varchar2(2000):='select denumire_populara,denumire_stintifica,mini_descriere from animale where ';
    
    v_nr_criterii integer;
    
    v_cuvinte varchar2(200);
    
    v_expresie varchar2(500);
    
    v_id_cursor integer;
    v_ok integer;
    
    v_denumire_populara varchar2(50);
    v_denumire_stintifica varchar2(100);
    v_mini_descriere varchar2(500);
    
    v_nr_animale integer := 0;
    
    --setari pentru rezultat
    v_separator_linii character := '#';
    v_separator_valori character := ',';
begin
    
    p_raspuns:='OK';
    p_rezultat:='';
    
    --obtinere numar de criterii
    select regexp_count(p_sir,'#')+1 into v_nr_criterii from dual;
    
    --scriere expresie pentru clauza where in functie de criterile din sir
    for v_pozitie in 1..v_nr_criterii loop
        
        if(v_pozitie!=1)then
            v_sql:=v_sql||' and ';
        end if;
        
        v_sql:=v_sql||'( ';
        
        --obtine valorile si numele campului pentru un criteriu
        select regexp_substr(p_sir,'[^#]+',1,v_pozitie) into v_cuvinte from dual;
        
        --obtine acestea intr-o expresie logica
        v_expresie:=extragere_expresie(v_cuvinte);
        
        --adaugare la cod sql
        v_sql:=v_sql||v_expresie;
        
        v_sql:=v_sql||' )';
        
    end loop;
    
    --folosim dbms_sql pentru a face selectia multicriteriala
    
    v_id_cursor:=dbms_sql.open_cursor;
    dbms_sql.parse(v_id_cursor,v_sql,dbms_sql.native);
    dbms_sql.define_column(v_id_cursor,1,v_denumire_populara,50);
    dbms_sql.define_column(v_id_cursor,2,v_denumire_stintifica,100);
    dbms_sql.define_column(v_id_cursor,3,v_mini_descriere,500);
    v_ok:=dbms_sql.execute(v_id_cursor);
    
    loop
        if(dbms_sql.fetch_rows(v_id_cursor)>0)then
            v_nr_animale:=v_nr_animale+1;
            if(v_nr_animale!=1)then
                p_rezultat:=p_rezultat||v_separator_linii;
            end if;
            
            --concatenare valori pentru linie
            dbms_sql.column_value(v_id_cursor,1,v_denumire_populara);
            dbms_sql.column_value(v_id_cursor,2,v_denumire_stintifica);
            dbms_sql.column_value(v_id_cursor,3,v_mini_descriere);
            p_rezultat:=p_rezultat||'DENUMIRE_POPULARA='||v_denumire_populara||v_separator_valori||'DENUMIRE_STINTIFICA='||v_denumire_stintifica||v_separator_valori||'MINI_DESCRIERE='||v_mini_descriere;
            --dbms_output.put_line(v_denumire_populara||' cu origine '||v_origine||', clasa '||v_clasa);
        else
            exit;
        end if;
    end loop;
    
    if(v_nr_animale=0)then
        p_raspuns:='Empty';
    end if;
    
end;

describe animale;

select * from animale;

-----------------------------------------
--returneaza de la linia respectiva valoarea unui camp denumire_populara,denumire_stintifica,origine,clasa,stare_de_conservare,regim_alimentar,mod_de_inmultire
create or replace function obtine_valoare_camp(p_linie animale%rowtype,p_nume_camp varchar2)
return varchar2
as
begin
    if(p_nume_camp='denumire_populara')then
        return p_linie.denumire_populara;
    elsif(p_nume_camp='denumire_stintifica')then
        return p_linie.denumire_stintifica;
    elsif(p_nume_camp='origine')then
        return p_linie.origine;
    elsif(p_nume_camp='clasa')then
        return p_linie.clasa;
    elsif(p_nume_camp='stare_de_conservare')then
        return p_linie.stare_de_conservare;
    elsif(p_nume_camp='regim_alimentar')then
        return p_linie.regim_alimentar;
    elsif(p_nume_camp='mod_de_inmultire')then
        return p_linie.mod_de_inmultire;
    else
        return null;
    end if;
end;

--calculeaza punctajul pe baza unui cuvant,unei valorii si a punctajului total al valorii
--formula: nr_litere_egale/nr_litere_total*punctaj
--nr_litere_egale este un numar real,si va poate fi de forma 5.5,cum adaugam un 0.5 daca 2 litere difera,dar sunt aceleasi daca consideram case-insensitive
create or replace function punctaj_cuvant_camp(p_cuvant varchar2,p_valoare varchar2,p_punctaj_valoare real)
return real
as
    v_nr_litere_egale real :=0;
    v_total real;
    
    v_pozitie integer :=0;
    
    v_lungime_cuvant integer:=length(p_cuvant);
    v_lungime_valoare integer:=length(p_valoare);
    
    v_caracter1 char(1);
    v_caracter2 char(1);
    
begin
    while(v_pozitie<v_lungime_cuvant and v_pozitie<v_lungime_valoare) loop
        
        v_caracter1:=substr(p_cuvant,v_pozitie,1);
        v_caracter2:=substr(p_valoare,v_pozitie,1);
        
        --adaug +1 daca aceste 2 litere sunt egale(exact acelasi caracter) sau 0.5 daca sunt litere pe cazuri diferite
        if(v_caracter1=v_caracter2) then
            v_nr_litere_egale:=v_nr_litere_egale+1;
        elsif(lower(v_caracter1)=lower(v_caracter2)) then
            v_nr_litere_egale:=v_nr_litere_egale+0.5;
        end if;
        
        v_pozitie:=v_pozitie+1;
    end loop;
    
    v_total:=v_lungime_valoare;
    if(v_lungime_cuvant>v_total)then
        v_total:=v_lungime_cuvant;
    end if;
    
    return v_nr_litere_egale*p_punctaj_valoare/v_total;
end;

select * from animale;


--cauta cuvantul primit in urmatoarele campuri:
--va selecta : denumire_populara,denumire_stintifica,origine,clasa
--usor adaptabila pentru stare_de_conservare,regim_alimentar,mod_de_inmultire(cand vom avea date)
create or replace function punctaj_animal(p_text varchar2,p_nume_animal varchar2)
return real
as
    v_punctaj real:=0;
    
    type punctaje_campuri is table of real index by varchar2(50);
    punctaje punctaje_campuri;
    
    v_nume_camp varchar2(50);
    v_valoare_camp varchar2(100);
    v_punctaj_camp real;
    
    v_info animale%rowtype;
    
    v_cuvant varchar2(100);
    v_nr_aparitie integer:=1;
    
begin
    --initializare tablou punctaje
    punctaje('denumire_populara'):=100;
    punctaje('denumire_stintifica'):=70;
    punctaje('origine'):=100;
    punctaje('clasa'):=100;
    --punctaje('stare_de_conservare'):=80;
    --punctaje('regim_alimentar'):=90;
    --punctaje('mod_de_inmultire'):=80;
    
    --selectare informatii despre animal
    begin
        select * into v_info from animale where lower(trim(p_nume_animal))=lower(trim(denumire_populara));
    exception
        when no_data_found then
        return 0;
        when too_many_rows then
        return 0;
    end;
    
    --parcurgere cuvinte din propozitia scrisa(ignoram spatiile)
    
    select regexp_substr(p_text,'[^ ]+',1,v_nr_aparitie) into v_cuvant from dual;
    while(v_cuvant is not null and length(v_cuvant)>0) loop
        --actualizare punctaj
        
        v_nume_camp:=punctaje.first;
        while(v_nume_camp is not null) loop
            
            --obtine valoare campului cu numele respectiv
            v_valoare_camp:=obtine_valoare_camp(v_info,v_nume_camp);
            --obtine punctaj camp
            v_punctaj_camp:=punctaje(v_nume_camp);

            --adaugare punctaj al cuvantului fata de valoarea campului
            v_punctaj:=v_punctaj+punctaj_cuvant_camp(v_cuvant,v_valoare_camp,v_punctaj_camp);
            
            --next
            v_nume_camp:=punctaje.next(v_nume_camp);
        end loop;              
        
        --obtinere cuvant nou
        v_nr_aparitie:=v_nr_aparitie+1;
        select regexp_substr(p_text,'[^ ]+',1,v_nr_aparitie) into v_cuvant from dual;
    end loop;
    
    return v_punctaj;
end;

select line,text from user_source where lower(trim(name))='inserare_informatii_animal';

--inserare informatii animal
create or replace function exista_animal(p_denumire_animal varchar2)
return integer
as
    v_nr integer;
begin
    select count(*) into v_nr from animale where lower(denumire_populara)=lower(p_denumire_animal);
    return v_nr;
end;

create or replace procedure inserare_informatii_animal(
p_denumire_populara varchar2,
p_denumire_stintifica varchar2,
p_mini_descriere varchar2,
p_etimologie varchar2 default null,
p_origine varchar2 default null,
p_clasa varchar2 default null,
p_invaziva varchar2 default null,
p_stare_de_conservare varchar2 default null,
p_regim_alimentar varchar2 default null,
p_dieta varchar2 default null,
p_mod_de_inmultire varchar2 default null,
p_reproducere varchar2 default null,
p_dezvoltare varchar2 default null,
p_viata varchar2 default null,
p_mortalitate varchar2 default null,
p_istorie varchar2 default null,
p_dusmani_naturali varchar2 default null,
p_raspuns in out varchar2
)
as
    v_id animale.id%type;
begin
    if(exista_animal(p_denumire_populara)=0)then
        select nvl(max(id),0)+1 into v_id from animale;
        
        insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,etimologie,origine,clasa,invaziva,stare_de_conservare,regim_alimentar,dieta,mod_de_inmultire,reproducere,dezvoltare,viata,mortalitate,istorie,dusmani_naturali) values (
        v_id,
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
        p_dusmani_naturali);
        
        p_raspuns:='OK';
    else
        p_raspuns:='The name of the animal'||chr(39)||p_denumire_populara||chr(39)||'is already taken';
    end if;
end;

--populare animale cu niste date de test
delete from animale where id>17;
/
commit;

select * from animale;

delete from animale where id<3;

describe animale;

insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(1,'tigru','tigris','a','asia','mamifere');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(2,'leu','leus','a','asia','mamifere');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(3,'bivol','bivolis bivo','a','europa','mamifere');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(4,'squirrel','a','a','europa','mamifere');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(5,'broasca','a','a','america','amfibieni');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(6,'vaduva neagra','a','a','australia','arahnide');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(7,'huntsman','a','a','australia','arahnide');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(8,'musca','musca','a','europa','insecte');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(9,'soparla','a','a','asia','reptile');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(10,'tantar','a','a','asia','insecte');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(11,'albine','a','a','europa','insecte');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(12,'elefant','a','a','africa','manifer');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(13,'bufnita','a','a','europa','pasari');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(14,'gondor','a','a','africa','pasari');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(15,'peste-balon','a','a','australia','pesti');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(16,'ton','tigris','a','america','pesti');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(17,'polichet','tigris','a','asia','polichete');

--test

select denumire_populara,origine,clasa,punctaj_animal('tigriasdas leu',denumire_populara) as scor
from animale
where punctaj_animal('tigriasdas leu',denumire_populara)>0
order by scor desc;

declare
    v_scor real;
begin
    v_scor:=punctaj_animal('tigru leu urs tigru','leu');
    dbms_output.put_line('|Scor = '||v_scor||'|');
end;

declare
    v_scor real :=-1;
begin
    v_scor:=punctaj_cuvant_camp('tigru','tigru',100);
    dbms_output.put_line(v_scor);
end;


select * from animale;

set serveroutput on;

declare
    v_sir varchar2(1000):='origine,asia';
    v_rezultat varchar2(2000);
    v_raspuns varchar2(4000);
begin
    cautare_multicriteriala(v_sir,v_rezultat,v_raspuns);
    dbms_output.put_line(v_rezultat);
end;

update animale set origine='europa' where origine='europe';

set serveroutput on;

--testing

