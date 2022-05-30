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
create or replace function extragere_cuvinte(p_cuvinte varchar2)
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

set serveroutput on;

declare
    v_cuvinte varchar2(100):='remus,alo,hey,wut'; 
begin
    dbms_output.put_line(extragere_cuvinte(v_cuvinte));
end;

create or replace procedure cautare_dupa_criterii(p_clasa varchar2)
as
    v_nr_cuvinte integer ;
    
begin
    select regexp_count(p_clase,',')+1 into v_nr_cuvinte; 
    
end;



--test
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
