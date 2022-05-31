--legat de tabela salvari
create or replace procedure salvare_animal(p_nume_utilizator varchar2,p_nume_animal varchar2,p_raspuns IN OUT varchar2)
as
    v_id_animal animale.id%type;
    v_id_utilizator utilizatori.id%type;
    
begin
    
    --selectare id pentru animal,daca exista
    begin
        select id into v_id_animal from animale where lower(trim(p_nume_animal))=lower(trim(denumire_populara));
    exception
        when no_data_found then
        p_raspuns:='Nu exista un animal in baza de date cu acest nume';
        return;
    end;
    
    --selectarea id pentru utilizator,daca exista
    begin
        select id into v_id_utilizator from utilizatori where trim(p_nume_utilizator)=trim(nume_utilizator);
    exception
        when no_data_found then
        p_raspuns:='Nu exista un utilizator in baza de date cu acest nume';
        return;
    end;
    
    insert into salvari(id_utilizator,id_animal) values(v_id_utilizator,v_id_animal);
    
    act_statistici_salvari(v_id_utilizator,v_id_animal);
    
    p_raspuns:='OK';
end;

create or replace procedure descarcare_informatii_animal(p_nume_utilizator varchar2,p_nume_animal varchar2,p_raspuns IN OUT varchar2)
as
    v_id_animal animale.id%type;
    v_id_utilizator utilizatori.id%type;
begin
    --selectare id pentru animal,daca exista
    begin
        select id into v_id_animal from animale where lower(trim(p_nume_animal))=lower(trim(denumire_populara));
    exception
        when no_data_found then
        p_raspuns:='Nu exista un animal in baza de date cu acest nume';
        return;
    end;
    
    --selectarea id pentru utilizator,daca exista
    begin
        select id into v_id_utilizator from utilizatori where trim(p_nume_utilizator)=trim(nume_utilizator);
    exception
        when no_data_found then
        p_raspuns:='Nu exista un utilizator in baza de date cu acest nume';
        return;
    end;
    
    --operatii necesare pentru a transmte informatiile despre acest animal
    --etc,etc
    
    act_statistici_descarcari(v_id_utilizator,v_id_animal);    
end;


----------------posibil cu mult mai usor in php-------------------

--presupunem ca primim un sir de cuvinte cel putin 2,separate prin virgule
--primul cuvant reprezinta valoarea campului si restul valorile posibile ale acelui camp
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
--v_raspuns va fi OK daca a fost gasit macar un rezultat, 'Empty' daca nu exista rezultate si altfel mesaje de eroare specifice
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
            p_rezultat:=p_rezultat||'denumire_populara='||v_denumire_populara||v_separator_valori||'denumire_stintifica='||v_denumire_stintifica||v_separator_valori||'mini_descriere='||v_mini_descriere;
            --dbms_output.put_line(v_denumire_populara||' cu origine '||v_origine||', clasa '||v_clasa);
        else
            exit;
        end if;
    end loop;
    
    if(v_nr_animale=0)then
        p_raspuns:='Empty';
    end if;
    
end;

select line,text from user_source where lower(trim(name))='cautare_multicriteriala';

--populare animale cu niste date de test

delete from animale where id=0;

delete from animale;

describe animale;

insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(1,'tigru','tigris','a','asia','mamifere');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(2,'leu','leus','a','asia','mamifere');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(3,'bivol','bivolis bivo','a','europe','mamifere');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(4,'squirrel','a','a','europe','mamifere');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(5,'broasca','a','a','america','amfibieni');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(6,'vaduva neagra','a','a','australia','arahnide');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(7,'huntsman','a','a','australia','arahnide');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(8,'musca','musca','a','europe','insecte');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(9,'soparla','a','a','asia','reptile');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(10,'tantar','a','a','asia','insecte');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(11,'albine','a','a','europe','insecte');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(12,'elefant','a','a','africa','manifer');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(13,'bufnita','a','a','europa','pasari');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(14,'gondor','a','a','africa','pasari');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(15,'peste-balon','a','a','australia','pesti');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(16,'ton','tigris','a','america','pesti');
insert into animale(id,denumire_populara,denumire_stintifica,mini_descriere,origine,clasa) values(17,'polichet','tigris','a','asia','polichete');

--test

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

declare
    v_nume_utilizator varchar2(100):='remus';
    v_nume_animal varchar2(100):='tigru';
    v_raspuns varchar2(100);
begin
    salvare_animal(v_nume_utilizator,v_nume_animal,v_raspuns);
    dbms_output.put_line(v_raspuns);
end;

delete from salvari;

declare
    v_nume_utilizator varchar2(100):='remus';
    v_nume_animal varchar2(100):='tigru';
    v_raspuns varchar2(100);
begin
    descarcare_informatii_animal(v_nume_utilizator,v_nume_animal,v_raspuns);
    dbms_output.put_line(v_raspuns);
end;

select * from statistici_clase_animale;
delete from statistici_clase_animale;
