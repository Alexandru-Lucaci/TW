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

create or replace procedure extragere_cuvinte(p_cuvinte varchar2)
as
    v_nr_cuvinte integer;
    
    TYPE lista_cuvinte IS TABLE OF varchar2(100);    
    cuvinte lista_cuvinte := lista_cuvinte();
    
    v_cuvant varchar2(100);
begin
    select regexp_count(p_cuvinte,',')+1 into v_nr_cuvinte from dual;
    
    for v_pozitie in 1..v_nr_cuvinte loop
        select regexp_substr(p_cuvinte,'[^,]+',1,v_pozitie) into v_cuvant from dual;
        
        cuvinte.extend;
        cuvinte(v_pozitie):=v_cuvant;
    end loop;
    
        
    for v_pozitie in 1..v_nr_cuvinte loop
        dbms_output.put_line(cuvinte(v_pozitie));
    end loop;   
    
end;

declare
    v_cuvinte varchar2(100):='remus,alo,hey'; 
begin
    extragere_cuvinte(v_cuvinte);
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
